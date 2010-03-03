<?php

/*      

   +----------------------------------------------------------------------+

   | DILPS - Distributed Image Library System                             |

   +----------------------------------------------------------------------+

   | Copyright (c) 2002-2004 Juergen Enge                                 |

   | juergen@info-age.net                                                 |

   | http://www.dilps.net                                                 |

   +----------------------------------------------------------------------+

   | This source file is subject to the GNU General Public License as     |

   | published by the Free Software Foundation; either version 2 of the   |

   | License, or (at your option) any later version.                      |

   |                                                                      |

   | Distributed Playout Infrastructure is distributed in the hope that   |

   | it will be useful,but WITHOUT ANY WARRANTY; without even the implied |

   | warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.     |

   | See the GNU General Public License for more details.                 |

   |                                                                      |

   | You should have received a copy of the GNU General Public License    |

   | along with this program; if not, write to the Free Software          |

   | Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA            |

   | 02111-1307, USA                                                      |

   +----------------------------------------------------------------------+

*/



/*

 * Smarty plugin

 * -------------------------------------------------------------

 * File:     function.mygroup_change.php

 * Type:     function

 * Name:     mygroup_change

 * Purpose:  change the membership of an image to a group

 * -------------------------------------------------------------

 */


global $config;
include_once($config['includepath'].'tools.inc.php');

 

function smarty_function_mygroup_change($params, &$smarty)

{

    if (empty($params['result'])) {

        $smarty->trigger_error("assign: missing 'result' parameter");

        return;

    }

    

    global $db, $db_prefix;

    

    /*

    print_r($params);

    

    $db->debug = true;

    */
	
	if (empty($params['gid'])) {

		$smarty->trigger_error("assign: missing 'gid' parameter");

		return;

    } else {

    	$mygroupid = $params['gid'];

    }
	
	if (empty($params['action'])) {

    	$smarty->trigger_error("assign: missing 'action' parameter");

        return;

    } else {

    	$action = $params['action'];

    }

    

	if (empty($params['changes'])) {

    	$smarty->trigger_error("assign: missing 'changes' parameter");

        return;

    } else {

    	$changes = $params['changes'];

    }
	
	if ($action == 'update')
	{ 
		foreach ($changes as $key => $val)
		{
			extractID($key, $cid, $imageid );
			
			if ($val == 'add') {

				

				$sql = "REPLACE INTO ".$db_prefix."img_group "

						."(groupid, collectionid, imageid) "

						."VALUES ("
						.$db->qstr($mygroupid)." ,"

						.$db->qstr($cid)." ,"				

						.$db->qstr($imageid).")";

				

				$rs  = @$db->Execute ($sql);

			

			}
			else if ($val == 'del')
			{
				
				// search all subgroups, look where we can find the image, then delete it
				
				$sql = "SELECT * FROM ".$db_prefix."img_group WHERE "
					."collectionid=".$db->qstr($cid)
					." AND imageid=".$db->qstr($imageid)
					.get_groupid_where($groupid,$db,$db_prefix,true); 	
		
				$rs  = $db->Execute ($sql);

				while (!$rs->EOF)
				{
					$sql2 = "DELETE FROM ".$db_prefix."img_group "
							."WHERE groupid=".$db->qstr($rs->fields['groupid'])." "
							."AND collectionid=".$db->qstr($cid)." "
							."AND imageid=".$db->qstr($imageid);
	
					$rs2  = @$db->Execute ($sql2);
					
					$rs->MoveNext();
				}

			}
		}
	}
	else 
	{
		$rs = false;
	}

	

	if (!$rs)
	{		 	
    	$smarty->assign($params['result'], 'failed to '.$action.' group '.$mygroupid);
	}
	else 
	{
		$smarty->assign($params['result'], $action.'ed group '.$mygroupid.' successfully');
	}

    
    if( !empty($params['sql'])) {
		$smarty->assign($params['sql'], $sql);
	}

}

?>

