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
 * File:     function.image_change.php
 * Type:     function
 * Name:     image_change
 * Purpose:  rotate or delete an image from hd and db
 * -------------------------------------------------------------
 */
 
function smarty_function_image_change($params, &$smarty)
{
    if (empty($params['result'])) {
        $smarty->trigger_error("assign: missing 'result' parameter");
        return;
    }
    
    global $db, $db_prefix;
	global $formats_suffix, $resolutions_available, $imagemagick_convert;
    
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
    
    if (empty($params['cid'])) {
    	$smarty->trigger_error("assign: missing 'cid' parameter");
		return;
    } else {
    	$collectionid = $params['cid'];
    }
    
    if (empty($params['imageid'])) {
		$smarty->trigger_error("assign: missing 'imageid' parameter");
		return;
    } else {
		$imageid = $params['imageid'];
    }    

	if ($action == 'rotate') {
		
		// Get file location and image width and height

		$sql = "SELECT filename,base,width,height FROM ".$db_prefix."img,".$db_prefix."img_base WHERE ".$db_prefix."img.imageid=".intval($imageid)
				." AND ".$db_prefix."img.collectionid=".intval($collectionid)
				." AND ".$db_prefix."img.collectionid=".$db_prefix."img_base.collectionid"
				." AND ".$db_prefix."img.img_baseid=".$db_prefix."img_base.img_baseid";
				
		$row = $db->GetRow( $sql );
		
		$height = $row['height'];
		$width = $row['width'];
		
		$base = $row['base'];
		$file = intval($imageid).'.jpg';
		
		if( $base != '' && $base{strlen($base)-1} != DIRECTORY_SEPARATOR ) 
		{
			$base .= DIRECTORY_SEPARATOR;
		}
		
		// Iterate through all available resolutions and formats and rotate the corresponding images
		
		foreach ($resolutions_available as $resolution)
		{
			$path = $base.'cache'.DIRECTORY_SEPARATOR.$resolution.DIRECTORY_SEPARATOR.intval($collectionid).'-'.intval($imageid).'.jpg';
			
			if (file_exists($path)){
				
				$path = escapeshellarg($path);
				
				$cmd = $imagemagick_convert." ".$path." -rotate 90 -resize ".$resolution." ".$path;
				exec($cmd,$result,$ret);
			}			
	
		}
		
		// Rotate JPEG-version of the orignal image
		
		$path = $base.'cache'.DIRECTORY_SEPARATOR.intval($collectionid).'-'.intval($imageid).'.jpg';
			
		// echo "File-Path: $path";
	
		if (file_exists($path)){
			
			$path = escapeshellarg($path);			
			$cmd = $imagemagick_convert." ".$path." -rotate 90 ".$path;
			exec($cmd,$result,$ret);
		}

		
		// Rotate the orignal image
		
		foreach ($formats_suffix as $mime => $suffix){
			$path = $base.intval($collectionid).'-'.intval($imageid).'.'.$suffix;
			
			// echo "File-Path: $path";
		
			if (file_exists($path)){
				
				$path = escapeshellarg($path);
				
				$cmd = $imagemagick_convert." ".$path." -rotate 90 ".$path;
				exec($cmd,$result,$ret);
			}
		}
		
		// apply the exchange of width and height to database
		
		$sql = 	"UPDATE ".$db_prefix."img SET width=".$db->qstr($height).","
				."height=".$db->qstr($width)." WHERE imageid=".intval($imageid)
				." AND collectionid=".intval($collectionid);
				
		$rs = $db->Execute( $sql );
			
	}
	else if ($action == 'delete')
	{
		// Get file location

		$sql = "SELECT filename,base FROM ".$db_prefix."img,".$db_prefix."img_base WHERE ".$db_prefix."img.imageid=".intval($imageid)
				." AND ".$db_prefix."img.collectionid=".intval($collectionid)
				." AND ".$db_prefix."img.collectionid=".$db_prefix."img_base.collectionid"
				." AND ".$db_prefix."img.img_baseid=".$db_prefix."img_base.img_baseid";
				
		$row = $db->GetRow( $sql );
		
		$base = $row['base'];
		
		$file = intval($imageid).'.jpg';
		if( $base != '' && $base{strlen($base)-1} != DIRECTORY_SEPARATOR )
		{
			$base .= DIRECTORY_SEPARATOR;
		}
		
		// Iterate through all available resolutions and formats and delete corresponding files
		
		foreach ($resolutions_available as $resolution)
		{		
			$path = $base.'cache'.DIRECTORY_SEPARATOR.$resolution.DIRECTORY_SEPARATOR.intval($collectionid).'-'.intval($imageid).'.jpg';
			
			// echo "Delete-Path: $path";
		
			if (file_exists($path)){
				unlink($path);
			}
		}
		
		// Delete JPEG-version of original image
		
		$path = $base.'cache'.DIRECTORY_SEPARATOR.intval($collectionid).'-'.intval($imageid).'.jpg';
		
		// echo "Delete-Path: $path";
	
		if (file_exists($path)){
			unlink($path);
		}

		
		// Delete copy of original image
		
		foreach ($formats_suffix as $mime => $suffix)
		{
			$path = $base.intval($collectionid).'-'.intval($imageid).'.'.$suffix;
			
			// echo "Delete-Path: $path";
		
			if (file_exists($path)){
				unlink($path);
			}
		}
		
		
		// Delete image information
		
		$sql = "DELETE FROM ".$db_prefix."img WHERE imageid=".intval($imageid)
				." AND collectionid=".intval($collectionid);																										
		$rs = $db->Execute( $sql );
		
		$sql = "DELETE FROM ".$db_prefix."meta WHERE imageid=".intval($imageid)
				." AND collectionid=".intval($collectionid);																										
		$rs = $db->Execute( $sql );
		
		$sql = "DELETE FROM ".$db_prefix."img_group WHERE imageid=".intval($imageid)
				." AND collectionid=".intval($collectionid);
		$rs = $db->Execute( $sql );		
	}
	else 
	{
		$rs = false;
	}
	
	if (!$rs)
	{		 	
    	$smarty->assign($params['result'], 'failed to '.$action.' image '.$imageid.' from collection '.$collectionid);
	}
	else 
	{
		if ($action == 'delete')
		{
			$action = 'delet';
		}
		$smarty->assign($params['result'], $action.'ed image '.$imageid.' from collection '.$collectionid.' successfully');
	}
    
    if( !empty($params['sql'])) 
	{
		$smarty->assign($params['sql'], $sql);
	}	 
}
?>
