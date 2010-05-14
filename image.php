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
 * Image Displaying
 * -------------------------------------------------------------
 * File:     	image.php
 * Purpose:  	extracts the image and collection ID from its
 *				arguments and reads the approriate information
 *				from the database, outputs the image in the
 *				specified resolution or an empty image if the
 *				latter one doesn't exist.
 * -------------------------------------------------------------
 */
ini_set( 'zend.ze1_compatibility_mode', 'On' );

// read sessiond id from get/post
if (isset($_REQUEST['PHPSESSID'])) {
	$sessionid = $_REQUEST['PHPSESSID'];
} else {
	$sessionid = '';
}
include_once( 'config.inc.php');
include_once( $config['includepath'].'../includes.inc.php' );
//include_once( $config['includepath'].'tools.inc.php' );

function extractID( $id, &$sammlung, &$imageid )
{
	$p = strpos( $id, ':' );
	if( !$p )
	{
		$sammlung = false;
		$imageid = false;
		return;
	}
	$sammlung = intval( substr( $id, 0, $p ));
	$imageid = substr( $id, $p+1 );
}

global $formats_suffix, $db, $db_prefix;

if( !isset( $_REQUEST['id'] ))
{
	header( "Content-type: image/jpeg\n\n" );
	readfile( 'empty.jpg' );
	exit;
}
$id = $_REQUEST['id'];

if( !isset( $_REQUEST['resolution'] ))
{
	$resolution = "400x400";
}
else
{
	$resolution = $_REQUEST['resolution'];
}

$remoteCollectionId = isset($_REQUEST['remoteCollection']) ? intval($_REQUEST['remoteCollection']) : 0;

if ($remoteCollectionId) {
    
    // fetch image from remote server
    include_once ($config['includepath'].'remote.inc.php');
    include_once "HTTP/Request.php";
    if ($collection = get_remote_collection_info($remoteCollectionId)) {
        $path = dirname($collection['soap_url']) . '/image.php';
        $url = "http://{$collection['host']}/$path";
        $params = array (
            'id' => $id,
            'resolution' => $resolution,
            'inter-dilps-request' => true,
            );
        $req =& new HTTP_Request($url);
        foreach ($params as $param=>$value) {
            $req->addQueryString($param, $value);
        }
        
        $result = $req->sendRequest();
        if (PEAR::isError($result)) {
            no_file();
        } else {
            header( "Content-type: image/jpeg\n\n" );
            echo $req->getResponseBody();
        }
    } else {
        no_file();
    }
    
} else {

    //local image
    
    if ( isset($_REQUEST['debug']))
    {
    	$debug = $_REQUEST['debug'];
    }
    else 
    {
    	$debug = false;
    }
    
    if ($debug)
    {
    	echo ("Local image\n<br>\n");
    }
    
	extractID( $id, $collectionid, $imageid );    
  
    $sql = "SELECT filename,base FROM {$db_prefix}img,{$db_prefix}img_base WHERE {$db_prefix}img.imageid=".intval($imageid)
    		." AND {$db_prefix}img.collectionid=".intval($collectionid)
    		." AND {$db_prefix}img.collectionid={$db_prefix}img_base.collectionid"
    		." AND {$db_prefix}img.img_baseid={$db_prefix}img_base.img_baseid";
    	
    $row =	@$db->GetRow( $sql );
    $base = $row['base'];
    $file = intval($imageid).'.jpg';
    if( $base != '' && $base{strlen($base)-1} != DIRECTORY_SEPARATOR )
    {
    	$base .= DIRECTORY_SEPARATOR;
    }
    
    if ($debug)
    {
    	echo "Base Directory: $base\n<br>\n";
    }
    
    $file_exists_test = false;
    
    foreach ($formats_suffix as $mime => $suffix){
    	$path = $base.'cache'.DIRECTORY_SEPARATOR.($resolution=="original"?"":$resolution.DIRECTORY_SEPARATOR).intval($collectionid).'-'.intval($imageid).".".$suffix;
    	// $path = $base.$resolution.'/'.intval($collectionid).'-'.intval($imageid).".".$suffix;
    
    	// echo "Test-Path: $path";
    
    	if (file_exists($path)){
    		$file_exists_test = true;
    		break;
    	}
    }
    
    /*
    if ( !($file_exists_test)){
    	
    	$path = $base.'cache/'.$resolution.'/'.$file;
    	$file_exists_test = file_exists($path);
    }
    */
    
    if ($debug)
    {
    	echo ("Path: ".$path."\n<br>\n");
    	exit;
    }
    
    if( !$file_exists_test )
    {
    	// echo "$path<br>$sql";
    	
    	if ($debug)
    	{
    		echo "No corresponding file found\n<br>\n";
    	}
    	
    	no_file();    	
    }
    
    header( "Content-type: image/jpeg\n\n" ); 

    readfile( $path );
    // echo ("Pfad: ".$path."\n");
    
}

function no_file() {
	header( "Content-type: image/jpeg\n\n" );
	readfile( 'empty.jpg' );
	exit;
}

?>
