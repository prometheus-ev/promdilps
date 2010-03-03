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
 * smarty include file
 * -------------------------------------------------------------
 * File:     smarty.inc.php
 * Purpose:  provided by smarty installation
 * -------------------------------------------------------------
 */
// error_reporting(E_ALL);
require_once( $config['includepath'].'htmlquery.inc.php' );
$smarty = new Smarty();
$smarty->template_dir = $config['skinBase'];
/*
$smarty->compile_dir = $config['smartyBase'].'template_c/';
$smarty->cache_dir = $config['smartyBase'].'cache/';
*/
$smarty->compile_dir = $config['dilpsdir'].'cache/';
$smarty->cache_dir = $config['dilpsdir'].'cache/';
$smarty->plugins_dir[] = $config['includepath'].'plugins/';

$smarty->force_compile = true;
$smarty->caching = false;
$smarty->compile_check = true;
$smarty->debugging = false;

$query = array();
if( isset($_REQUEST['query']) && is_array( $_REQUEST['query'] )) {
	$query = $_REQUEST['query'];
} else {
    $query = array('querypiece'=>array(), 'querytype' => '');
}
cleanQuery($query);
$query['queryid'] = empty($_SESSION['counter']) ? 0 : $_SESSION['counter'];
$query = prepare_html_query($query);
//var_dump($query); exit;
//debug($_REQUEST);
/*if ($query['collectionid'] == '-1') {
    $config['remotequery'] = 1;
}*/
$config['soapresults'] = (!empty($query['collectionid']) && $query['collectionid'] == '-1') ? true : false;

$smarty->assign( 'config', $config );

if( !$valid_login )
{
  $logins->logout();
  $smarty->display( $config['skin'].'/login.tpl' );
  exit();
}

/*
$admin = $logins->isInGroup( $config['authdomain'], $config['admingroup'] );
$editor = $logins->isInGroup( $config['authdomain'], $config['editorgroup'] );
*/

$admin 			=	(isset($permissions['admin']) ? $permissions['admin'] : 0);
$editor 		= 	(isset($permissions['editor']) ? $permissions['editor'] : 0);
$usemygroup		=	(isset($permissions['usegroups']) ? $permissions['usegroups'] : 0);
$usemyfolder	= 	(isset($permissions['usefolders']) ? $permissions['usefolders'] : 0);
$insertimage	=	(isset($permissions['addimages']) ? $permissions['addimages'] : 0);;

if( $admin ) 
{
	$editor = true;
}

$user = array( 	
			'login'			=>	$logins->getUID( $config['authdomain'] ),
			'editor'		=>	($editor ? 1 : 0 ),
			'admin'			=>	($admin ? 1 : 0 ),
			'usemygroup' 	=>	(($admin || $usemygroup) ? 1 : 0),
			'usemyfolder' 	=>	(($admin || $usemyfolder) ? 1 : 0),
			'editgroup' 	=>	($admin ? 1 : 0),
			'insertimage' 	=>	(($admin || $insertimage) ? 1 : 0)	
		);
	
$smarty->assign( 'user', $user );
					
$view = array();
if( isset($_REQUEST['view']) && is_array( $_REQUEST['view'] ))
	$view = $_REQUEST['view'];
$smarty->assign( 'view', $view );

$edit = array();
if( isset($_REQUEST['edit']) && is_array( $_REQUEST['edit'] ))
	$edit = $_REQUEST['edit'];
$smarty->assign( 'edit', $edit );

$template = array();
$sql = "SELECT * FROM {$db_prefix}type";
$rs = $db->Execute( $sql );
while( !$rs->EOF )
{
	$template[$rs->fields['name']] = $rs->fields;
	$rs->MoveNext();
}
$rs->Close();
$smarty->assign( 'template', $template );

if( !isset( $view['type'] ) )
{
	$view['type'] = 'grid_detail';
}

switch( $view['type'] )
{
	case 'detail':
		if( !isset( $query['rows'] )) $query['rows'] = 12;
	   $tpl = 'detail.tpl';
		break;
	case 'liste':
		if( !isset( $query['rows'] )) $query['rows'] = 12;
	   $tpl = 'liste.tpl';
		break;
	case 'grid':
		if( !isset( $query['rows'] )) $query['rows'] = 4;
		if( !isset( $query['cols'] )) $query['cols'] = 4;
	   $tpl = 'grid.tpl';
		break;
	case 'grid_detail':
		if( !isset( $query['rows'] )) $query['rows'] = 4;
		if( !isset( $query['cols'] )) $query['cols'] = 3;
	   $tpl = 'grid_detail.tpl';
		break;
	default:
		if( !isset( $query['rows'] )) $query['rows'] = 4;
		if( !isset( $query['cols'] )) $query['cols'] = 3;
	   $tpl = 'grid_detail.tpl';
		break;
}
$smarty->assign( 'query', $query );

?>
