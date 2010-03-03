<?php
/*
   +----------------------------------------------------------------------+
   | DILPS - Distributed Image Library System                             |
   +----------------------------------------------------------------------+
   | Copyright (c) 2002-2004 Juergen Enge                                 |
   | juergen@info-age.net                                                 |
   | http://www.dilps.net                                                 |
   +----------------------------------------------------------------------+
   | published by the Free Software Foundation; either version 2 of the   |
   | This source file is subject to the GNU General Public License as     |
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
 * --------------------------------------------------------------------
 * File:     		function.group_change.php
 * Type:     	function
 * Name:     	group_change
 * Purpose:  add, delete or edit group
 * --------------------------------------------------------------------
 */

function smarty_function_group_change($params, &$smarty)
{
    if (empty($params['result']))
	{
        $smarty->trigger_error("assign: missing 'result' parameter");
        return;
    }

    global $db, $db_prefix;

    /*
	print_r($params);

    $db->debug = true;
    */

    if (empty($params['action'])) {
    	$smarty->trigger_error("assign: missing 'action' parameter");
        return;
    } else {
    	$action = $params['action'];
    }

	// assign parameters
	switch ($action)
	{
		case 'add':
		case 'edit':
			if (empty($params['gname']) && $params['gname'] != '0') {
				$smarty->trigger_error("assign: missing 'gname' parameter");
				return;
			} else {
				$groupname = $params['gname'];
			}
		default:
			 if (empty($params['gid']) and $params['gid'] != '0') {
				$smarty->trigger_error("assign: missing 'gid' parameter");
				return;
			} else {
				$groupid = $params['gid'];
			}
			if (empty($params['gowner'])) {
				$smarty->trigger_error("assign: missing 'gowner' parameter");
				return;
			} else {
				$groupowner = $params['gowner'];
			}
	}

	// perform actions

	if ($action == "add")
	{
		// get new groupid
		// $sql = "SELECT ifnull((max(id)+1),1) as newid FROM ".$db_prefix."group ";
		$sql = "SELECT ifnull((max(id)+1),1) FROM ".$db_prefix."group ";
		$newid = $db->GetOne($sql);

		$sql = "INSERT INTO ".$db_prefix."group "
					."(id, name,parentid,owner) "
					."VALUES ("
					.$db->qstr($newid).", "
					.$db->qstr($groupname).", "
					.$db->qstr($groupid).", "
					.$db->qstr($groupowner).")";

		$rs  = $db->Execute ($sql);

		// keep result codes for the moment
		if (!$rs)
		{
			$result = "E_ADD_FAILED";
		} else
		{
			$result = "R_ADD_SUCCESS";
		}
	}
	elseif ($action == "edit")
	{
		$sql = "UPDATE ".$db_prefix."group SET "
			."name=".$db->qstr($groupname).", "
			."owner=".$db->qstr($groupowner)." "
			." WHERE id= ".$db->qstr($groupid);

		$rs  = $db->Execute ($sql);

		// keep result codes for the moment
		if (!$rs)
		{
			$result = "E_EDIT_FAILED";
		}
		else
		{
			$result = "R_EDIT_SUCCESS";
		}

	}
	elseif ($action == "del" || $action == "delr")
	{

		// a bit more care is required - we have to look for subgroups
		// and delete images there as well as the groups themselv

		$sql = "SELECT id, name, owner FROM ".$db_prefix."group "
				." WHERE parentid = ".$db->qstr($groupid);

 		$rs = $db->Execute($sql);

		// collect all groups to delete, how far we get depends on the level
		// in the group hierarchy our group resides in

		$groups = array();

		// add our group first
		$groups[$groupid] = $groupid;

		// get its children
		while (!$rs->EOF)
		{
			$sql2 = "SELECT id, name, owner FROM ".$db_prefix."group WHERE "
						."parentid = ".$db->qstr($rs->fields['id'])
						." ORDER BY name";

			$rs2 = $db->Execute($sql2);

			while(!$rs2->EOF)
			{
				$sql3 = "SELECT id, name, owner FROM ".$db_prefix."group WHERE "
						."parentid = ".$db->qstr($rs2->fields['id'])
						." ORDER BY name";

				$rs3 = $db->Execute($sql3);

				while(!$rs3->EOF)
				{
					$groups[$rs->fields['id']] = $rs3->fields['id'];
					$rs3->MoveNext();
				}

				$groups[$rs2->fields['id']] = $rs2->fields['id'];
				$rs2->MoveNext();
			}

			$groups[$rs->fields['id']] = $rs->fields['id'];
			$rs->MoveNext();
		}

		// we have our groupids now, empty them, then delete

		$delete_success = true;

		foreach ($groups as $delid)
		{
			// empty group - otherwise ghost members will appear later
 			$sql = 	"DELETE FROM ".$db_prefix."img_group "
						." WHERE groupid = ".$db->qstr($delid);

 			$rs = $db->Execute ($sql);

			if (!$rs)
			{
				$delete_success = false;
			}

			// delete group
 			$sql = 	"DELETE FROM ".$db_prefix."group "
						." WHERE id = ".$db->qstr($delid);

 			$rs = $db->Execute ($sql);
			if (!$rs)
			{
				$delete_success = false;
			}
 		}

		// keep result codes for the moment
		if ($rs === false)
		{
			$result = "E_DELETE_FAILED";
		}
		else
		{
			$result = "R_DELETE_SUCCESS";
		}
	}
	else
	{
		$result = 'NO_ACTION_TAKEN';
	}

   	$smarty->assign($params['result'], $result);

    if( !empty($params['sql']))
	{
		$smarty->assign($params['sql'], $sql);
	}
}
?>
