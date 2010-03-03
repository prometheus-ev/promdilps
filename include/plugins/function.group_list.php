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

 * File:     function.group_list.php

 * Type:     function

 * Name:     group_list

 * Purpose:  assign the group_list to a template variable

 * -------------------------------------------------------------

 */


 

function smarty_function_group_list($params, &$smarty)
{
	global $db, $db_prefix;
	
    /*
		print_r($params);
		
		$db->debug = true;    	
	*/
    
    
	
    if (empty($params['var'])) {

        $smarty->trigger_error("assign: missing 'var' parameter");
        return;

    }
    
     if (empty($params['show'])) {

        $smarty->trigger_error("assign: missing 'show' parameter");
        return;

    }
    
    if (!empty($params['user'])) 
    {
    	$userid = $params['user'];
    }
    
   	if ($params['show'] == 'all')
   	{
   		$show = 1;
   	}
   	else 
   	{
   		$show = 0;
   	}

    
    // we always start on the top of the group hierarchy
   	$parentid = '0';
    
	$db->SetFetchMode(ADODB_FETCH_ASSOC);
	
	if (!empty($userid))
	{
		// look whether user already has a private group hierarchy, otherwise create his top-level group
		// groupname: [$userid], owner: $userid
		
		$sql = 	"SELECT * FROM ".$db_prefix."group WHERE"
	 			." parentid = ".$db->qstr($parentid)
	 			." AND owner=".$db->qstr($userid)
				." ORDER BY name";

 		$rs  = $db->Execute($sql);
 		
 		if ($rs->RecordCount() < 1)
 		{
 			// no group exists, create one
 			$sql = "SELECT ifnull((max(id)+1),1) FROM ".$db_prefix."group ";

			$newid 	= $db->GetOne($sql);
			$name 	= '['.$userid.']';
	
			$sql = "INSERT INTO ".$db_prefix."group "
						."(id, name,parentid,owner) "
						."VALUES ("
						.$db->qstr($newid).", "
						.$db->qstr($name).", "
						.$db->qstr($parentid).", "
						.$db->qstr($userid).")";
	
			$rs  = $db->Execute ($sql);
 		}
	}
    
    $sql = 	"SELECT id, name, owner FROM ".$db_prefix."group WHERE"
	 			." parentid = ".$db->qstr($parentid)
	 			." AND ("
	 			.($show ? " 1" : " owner = 'public'")
	 			.($userid ? " OR owner=".$db->qstr($userid) : '')
	 			.")"
				." ORDER BY name"; 	

 	$rs  = $db->Execute($sql);
	
	// our result
	$groups = array();
	
	while (!$rs->EOF)
	{
		$sql2 = "SELECT id, name, owner FROM ".$db_prefix."group WHERE"
					." parentid = ".$db->qstr($rs->fields['id'])
					." AND ("
		 			.($show ? " 1" : " owner = 'public'")
		 			.($userid ? " OR owner=".$db->qstr($userid) : '')
		 			.")"
					." ORDER BY name"; 	

		$rs2 = $db->Execute($sql2);
		
		$l2groups = array();
		
		while(!$rs2->EOF)
		{
			$sql3 = "SELECT id, name, owner FROM ".$db_prefix."group WHERE"
					." parentid = ".$db->qstr($rs2->fields['id'])
					." AND ("
		 			.($show ? " 1" : " owner = 'public'")
		 			.($userid ? " OR owner=".$db->qstr($userid) : '')
		 			.")"
					." ORDER BY name"; 	

		

			$rs3 = $db->Execute($sql3);
			
			$l3groups = array();
			
			while(!$rs3->EOF)
			{
				$l3groups[$rs3->fields['id']] = array(	'id'		=> $rs3->fields['id'],
																			'name' 	=> $rs3->fields['name'],
																			'owner' => $rs3->fields['owner'] );
																			
				$rs3->MoveNext();
			}
			
			$l2groups[$rs2->fields['id']] = array(	'id'				=> $rs2->fields['id'],
																		'name' 			=> $rs2->fields['name'],
																		'owner' 		=> $rs2->fields['owner'],
																		'subgroups' 	=> $l3groups );
																		
			unset($l3groups);
			
			$rs2->MoveNext();
		}				
		
		$groups[$rs->fields['id']] = array(	'id'				=> $rs->fields['id'],
																'name' 			=> $rs->fields['name'],
																'owner'		 	=> $rs->fields['owner'],
																'subgroups' 	=> $l2groups );
																
		unset($l2groups);
		
		$rs->MoveNext();
	}

	/*
	echo ("\n<br><br><br><br>\n");
	
	print_r($groups); 	
	*/
 	

		 	

    $smarty->assign($params['var'], $groups);

    

    if( !empty($params['sql'])) {

		     $smarty->assign($params['sql'], $sql);

	 }

	 

}

?>

