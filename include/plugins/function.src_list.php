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
 * File:     function.src_list.php 
 * Type:     function Name:     artist_list
 * Purpose:  assign the artist_list to a template var
 * -------------------------------------------------------------
 */
global $config;
require_once( $config['includepath'].'tools.inc.php' );

function smarty_function_src_list($params, &$smarty)
{
    if (empty($params['var'])) {
        $smarty->trigger_error("assign: missing 'var' parameter");
        return;
    }

	 global $db, $db_prefix, $query;
	 extractID( $query['id'], $collectionid, $imageid );
	 $sql = "SELECT DISTINCT literature,isbn FROM {$db_prefix}meta WHERE literature LIKE ".$db->qstr('%'.$query['literature'].'%')." OR isbn ";
		$page = (isset( $query['page'] ) ? intval( $query['page'] ) : 1 );

	$defaultpagesize = 30;
	$pagesize = (isset( $query['pagesize'] ) ? intval( $query['pagesize'] ) : $defaultpagesize );
	if( $pagesize < 1 || $pagesize > 500 ) $pagesize = $defaultpagesize;
	
	if( isset( $query['next'] )) $page++;
	if( isset( $query['prev'] )) $page--;
	if( isset( $query['new'] )) $page = 1;
	if( $page < 1 ) $page = 1;

	$rs = $db->PageExecute( $sql, $pagesize, $page );
	$result = array();
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
	
	$smarty->assign($params['var'], $result);
	 
	 if( !empty($params['sql'])) {
		     $smarty->assign($params['sql'], $sql);
	 }
}
?>
