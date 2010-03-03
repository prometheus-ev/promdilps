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
 * File:     block.query.php
 * Type:     block
 * Name:     query
 * Purpose:  execute query
 * -------------------------------------------------------------
 */

global $config;
require_once( $config['includepath'].'htmlquery.inc.php' );
require_once( $config['includepath'].'dilpsQuery.class.php' );

function smarty_block_query($params, $content, &$smarty, &$repeat)
{
    global $db, $db_prefix, $query, $view;

    if (isset($content)) {
	    echo $content;
	    return;
	}
	if (empty($params['var'])) {
	  $smarty->trigger_error("assign: missing 'var' parameter");
	  return;
	}

	$dbquery = html_to_db_query($query['submitted_query']);
	$pagesize = get_pagesize($dbquery, $view);
	$page = get_page($dbquery);
    $options = array(
       'pagesize' => $pagesize,
       'page' => (isset( $dbquery['page'] ) ? intval( $dbquery['page'] ) : 1 ),
       );

	$collectionid = $dbquery['collectionid'];
	if ($collectionid  == '-1') {
	    // search all collections, showing only a count of matching records
	    $options['count'] = 1;
	    $localCollections = query_local_collection_overview($dbquery, $options, $db, $db_prefix);
	    $remoteCollections = get_remote_collections_links($db, $db_prefix);
	    $result = array('result_type'=>'collections');
	    $result['rs'] = array_merge($localCollections, $remoteCollections);
	    
        // save the query structure in the session so that it can be re-used for an ajax request to get the remote counts
        if (!empty($remoteCollections)) {
            $remoteQueryStruct = transform_query($dbquery['querypiece'], true);
        	if (empty($_SESSION['queries'])) {
                $_SESSION['queries'] = array();    
        	}
        	$_SESSION['queries'][$_SESSION['counter']]=$remoteQueryStruct;
        }
    	
	} else {
	    // search a particular collection
//error_reporting(E_ALL);	    
        include_once( $config['includepath'].'remote.inc.php' );
	    if ($collection = get_remote_collection_info($collectionid)) {
	        // search a remote collection
        	$querystruct = transform_query($dbquery['querypiece'], true);
        	$url = "http://{$collection['host']}/{$collection['soap_url']}";
        	$soapparams = array('querystruct'=>$querystruct, 'options'=>$options);
	        $result = queryRemoteServer($url, 'queryOverview', $soapparams);
    	    $result['local'] = 0;
    	    $result['remoteCollection'] = $collectionid;
	    } else {
	        // search a local collection
	        $result = query_local_collection($dbquery, $options, $db, $db_prefix);
    	    $result['local'] = 1;
    	    $result['remoteCollection'] = 0;
	    }
	    $result['result_type'] = 'collection';
//debug($result);	    
//error_reporting(E_ALL);
	}
    if (!empty($result['error'])) {
	  $smarty->trigger_error($result['error']);
	  return;
    }
//debug($result, false);    
	$smarty->assign($params['var'], $result);
}

//
// PAGING FUNCTIONS
//
function get_page($query) {
	$page = (isset( $query['page'] ) ? intval( $query['page'] ) : 1 );
	if( isset( $query['next'] )) $page++;
	if( isset( $query['prev'] )) $page--;
	if( isset( $query['new'] )) $page = 1;
	if( $page < 1 ) $page = 1;
	return $page;
}

function get_pagesize($query, $view) {
	switch( $view['type'] )
	{
		case 'liste':
		   $defaultpagesize = 8;
			break;
		case 'detail':
		   $defaultpagesize = 4;
			break;
		case 'grid':
		   $defaultpagesize = 16;
			break;
		case 'grid_detail':
		   $defaultpagesize = 16;
			break;
		default:
		   $defaultpagesize = 4;
	}
	$pagesize = (isset( $query['pagesize'] ) ? intval( $query['pagesize'] ) : $defaultpagesize );
	if( intval( $query['rows'] ) && intval( $query['cols'] )) {
	   $pagesize = intval( $query['cols'] ) * intval( $query['rows'] );
	}
	if( $pagesize < 1 || $pagesize > 500 ) {
	    $pagesize = $defaultpagesize;
	}
	return $pagesize;
}


//
// LOCAL-QUERY FUNCTIONS
//
function query_local_collection($query, $options, $db, $db_prefix) {
    
	$querystruct = transform_query($query['querypiece']);
	$dbQuery = new dilpsQuery($db, $db_prefix);
	$where = $dbQuery->buildWhere($querystruct);
	$from = "{$db_prefix}meta ";
	$fields = "{$db_prefix}meta.type as type,{$db_prefix}meta.collectionid as collectionid,{$db_prefix}meta.imageid as imageid,ifnull({$db_prefix}meta.name1, '') as name1,ifnull({$db_prefix}meta.name2, '') as name2,{$db_prefix}meta.addition as addition, {$db_prefix}meta.title as title, {$db_prefix}meta.dating as dating";
	if (!empty($query['groupid'])){
		// add grouptable to query
		$fields .= ", {$db_prefix}img_group.groupid as groupid";
		$from .= "LEFT JOIN {$db_prefix}img_group ON {$db_prefix}img_group.imageid = {$db_prefix}meta.imageid AND {$db_prefix}img_group.collectionid = {$db_prefix}meta.collectionid";
		$where .= get_groupid_where_clause($query, $db, $db_prefix);
	}

	$sql = "SELECT DISTINCT $fields FROM $from WHERE $where"
	       ." ORDER BY insert_date DESC, imageid DESC";
	// die($sql);
    $pagesize = $options['pagesize'];
    $page = $options['page'];
	$rs = $db->PageExecute( $sql, $pagesize, $page );
	$result = array();
	if( !$rs ) {
	    $result['error'] = $db->ErrorMsg();
	} else {
    	$result['lastpage'] = $rs->LastPageNo();
    	$result['maxrecords'] = $rs->MaxRecordCount();
    	$result['rs'] = array();
    	while( !$rs->EOF )
    	{
    		array_walk( $rs->fields, '__stripslashes' );
    		$result['rs'][] = $rs->fields;
    		$rs->MoveNext();
    	}
    	$rs->Close();
    	if( $page > $result['lastpage'] ) $page = $result['lastpage'];
    	$result['page'] = $page;
    	$result['pagesize'] = $pagesize;
	}
	return $result;
}

// gets an overview of local collections, with a count of records that match as per the query
function query_local_collection_overview($query, $options, $db, $db_prefix) {
    $querystruct = transform_query($query['querypiece']);
	$dbQuery = new dilpsQuery($db, $db_prefix);
    $fields =  "1 as local, {$db_prefix}collection.collectionid, {$db_prefix}collection.name";
	$joinOn = $dbQuery->buildWhere($querystruct);
    $joinOn .= " and {$db_prefix}collection.collectionid = {$db_prefix}meta.collectionid ";
    $from = "{$db_prefix}collection left join {$db_prefix}meta  on $joinOn";
    $where = "{$db_prefix}collection.host = 'local'";
    if (!empty($query['groupid'])){
		// add grouptable to query
		$fields .= ", sum(if(groupid, 1, 0)) as count";
		$from .= " LEFT JOIN {$db_prefix}img_group ON {$db_prefix}img_group.imageid = {$db_prefix}meta.imageid AND {$db_prefix}img_group.collectionid = {$db_prefix}meta.collectionid";
		$from .= get_groupid_where_clause($query, $db, $db_prefix);
	} else {
		$fields .= ", count({$db_prefix}meta.id) as count";
	}

	$sql = "SELECT DISTINCT $fields FROM $from WHERE $where"
            ." group by {$db_prefix}collection.collectionid, {$db_prefix}collection.name";
//die($sql);	
	$result = array();
    $rs = $db->GetAll($sql);
	if( !$rs ) {
	    if ($db->ErrorNo()) {
	       $result['error'] = $db->ErrorMsg();
	    } else {
	        $result = array();
	    }
	} else {
	    $result = $rs;
	}
	return $result;
}

function get_groupid_where_clause($query, $db, $db_prefix) {
    
    $where = '';
    
	if (!empty($query['groupid']))
	{
		// query group and all subgroups
		$groupid = $query['groupid'];
		// our result
		$groups = array();
		// first id is the give one
		$groups[] = $groupid;
		$db->SetFetchMode(ADODB_FETCH_ASSOC);
		$sql = "SELECT id FROM ".$db_prefix."group WHERE "
					."parentid = ".$db->qstr($query['groupid'])
					." ORDER BY id"; 	
		$rs  = $db->Execute($sql);
		if (!$rs)
		{
			// we have no subgroups, just query one id
			$where .= " AND {$db_prefix}img_group.groupid = ".$db->qstr($query['groupid']);
		}
		else
		{
			// get next sublevel		
			while (!$rs->EOF)
			{
				// add group to result array
				$groups[] = $rs->fields['id'];
				// get next but one sublevel, if available
				$sql2 = "SELECT id FROM ".$db_prefix."group WHERE "
							."parentid = ".$db->qstr($rs->fields['id'])
							." ORDER BY id"; 	
				$rs2 = $db->Execute($sql2);
				
				while(!$rs2->EOF)
				{
					$groups[] = $rs2->fields['id'];				
					$rs2->MoveNext();
				}			
				$rs->MoveNext();
			}
			$where .= " AND (0 ";
			foreach ($groups as $gid)
			{
				$where .= " OR {$db_prefix}img_group.groupid = ".$db->qstr($gid);
			}
			$where .= ") ";
		}
	}
    return $where;
}


// returns remote collection info
function get_remote_collections_links($db, $db_prefix) {
    
    $sql = "select c.*  from {$db_prefix}collection c where (host!='local' and soap_url!='') order by collectionid";
    $collections = $db->GetAll($sql);
    if (!$collections) {
        $result = array('error'=>$db->ErrorMsg());
    } else {
        $result = array();
        foreach ($collections as $collection) {
	        $result[] = array(
	               'local'=> 0,
                   'collection' => $collection,
	               );
        }
    }
    return $result;
}

?>
