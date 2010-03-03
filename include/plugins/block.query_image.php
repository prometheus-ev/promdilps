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
 * File:     block.query_image.php
 * Type:     block
 * Name:     query_image
 * Purpose:  execute query
 * -------------------------------------------------------------
 */
 
global $config;
require_once( $config['includepath'].'tools.inc.php' );
require_once( $config['includepath'].'remote.inc.php' );

function smarty_block_query_image($params, $content, &$smarty, &$repeat)
{
	
	if( !isset($content)) 
	{
		
		if (empty($params['id'])) {
		  $smarty->trigger_error("assign: missing 'id' parameter");
		  return;
		}
		if (empty($params['var'])) {
		  $smarty->trigger_error("assign: missing 'var' parameter");
		  return;
		}
		if (empty($params['remoteCollection'])) {
		    $remoteCollectionId = 0;
		} else {
		    $remoteCollectionId = $params['remoteCollection'];
		}

		global $db, $db_prefix, $query;
		
		$ids = explode( ':', $params['id'] );
		$collectionid = intval( $ids[0] );
		$imageid = intval( $ids[1] );
	    if ($remoteCollectionId && $collection = get_remote_collection_info($remoteCollectionId)) {
            $url = "http://{$collection['host']}/{$collection['soap_url']}";
	        // search a remote collection
        	$queryfields = array(
        	   'collectionid' => $collectionid,
        	   'imageid' => $imageid
        	   );
	        $result = queryRemoteServer($url, 'queryDetail', $queryfields);
    	    $result['local'] = false;
	    } else {
	        // search a local collection
    		$sql = "SELECT {$db_prefix}meta.collectionid AS collectionid
    		                          ,{$db_prefix}meta.imageid AS imageid
    		                          ,{$db_prefix}meta.type AS type
    		                          ,{$db_prefix}meta.status AS status
    		                                  ,{$db_prefix}meta.name1id, {$db_prefix}meta.name2id
    										  ,{$db_prefix}meta.name1 as name1text
    		                                  ,{$db_prefix}meta.name2 as name2text
    										  ,{$db_prefix}meta.addition,{$db_prefix}meta.title,{$db_prefix}meta.dating
    										  ,{$db_prefix}meta.material,{$db_prefix}meta.technique,{$db_prefix}meta.format
    										  ,{$db_prefix}meta.location as city
    		                                  ,{$db_prefix}meta.locationid as locationid
    		                                  ,{$db_prefix}meta.exp_prometheus, {$db_prefix}meta.exp_sid, {$db_prefix}meta.exp_unimedia
    		                                  ,{$db_prefix}meta.commentary
    		                                  ,{$db_prefix}meta.metacreator, {$db_prefix}meta.metaeditor
    		                                  ,{$db_prefix}meta.imagerights
    		                                  ,{$db_prefix}meta.institution,{$db_prefix}meta.literature
    										  ,{$db_prefix}meta.page,{$db_prefix}meta.figure,{$db_prefix}meta.`table`
    										  ,{$db_prefix}meta.isbn,base,filename,width,height,xres,yres,size,magick ".
    		
            		  "FROM {$db_prefix}meta,{$db_prefix}img,{$db_prefix}img_base ".
                       " WHERE {$db_prefix}meta.collectionid={$db_prefix}img.collectionid".
    									" AND {$db_prefix}meta.imageid={$db_prefix}img.imageid".
    									" AND {$db_prefix}img.img_baseid={$db_prefix}img_base.img_baseid".
    									" AND {$db_prefix}img.collectionid={$db_prefix}img_base.collectionid".
    									" AND {$db_prefix}meta.collectionid=$collectionid".
    									" AND {$db_prefix}meta.imageid=$imageid";
    		$sqls = $sql;
    		$row = $db->GetRow( $sql );
    		if( $row ) array_walk( $row, "__stripslashes" );
    		$result = array();
    		$result['id'] = ($row ? $params['id'] : '' );
    		$result['rs'] = $row;
    		$result['local'] = true;
    		
    		if( !empty($params['sql'])) {
    			  $smarty->assign($params['sql'], $sqls);
    		}
	    }
		$smarty->assign($params['var'], $result);
	}
	else
	{
		echo $content;
	}
}
?>
