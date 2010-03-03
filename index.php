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
 * index file
 * -------------------------------------------------------------
 * File:     	index.php
 * Purpose:  	startpoint of the DILPS system
 * -------------------------------------------------------------
 */
ini_set( 'zend.ze1_compatibility_mode', 'On' );
ini_set( 'session.use_cookies' , 0 );

// ini_set('display_errors',1);

ini_set('display_errors',0);
ini_set('magic_quotes_runtime',0);
error_reporting (E_ALL ^ E_NOTICE);


// error_reporting (E_ALL);

if (!file_exists('config.inc.php'))
{
	echo ("It seems, your installation has not been configured or your configuration file has been deleted.\n<br>\n");
	echo ("Please go to <a href='admin/index.php'>the administration area</a> to setup your installation\n<br>\n");
	exit();
}

// import standard libraries and configuraiton values
include('includes.inc.php');

//debug($_REQUEST, false);
if (isset($_REQUEST['Login']))
{
	$smarty->assign('newlogin', '1');
}

if (isset($_REQUEST['PHPSESSID']))
{
	$sessionid = $_REQUEST['PHPSESSID'];
}
else
{
	$sessionid = session_id();
}

if (isset($_REQUEST['mygroup']) && is_array($_REQUEST['mygroup']))
{
	$smarty->assign('mygroup',$_REQUEST['mygroup']);
}
else
{
	$smarty->assign('mygroup','');
}

$action = array();

if( isset($_REQUEST['action']) && is_array( $_REQUEST['action'] )) 
{
	$action = $_REQUEST['action'];	
}
else 
{
	$action = array();
}

$smarty->assign('action',$action);
$smarty->assign('sessionid',$sessionid);

//bk: debugging
//error_reporting(E_ALL);
//$smarty->error_reporting = E_ALL;

//debug(array($config, $tpl));
$smarty->display( $config['skin'].'/'.$tpl);

// phpinfo();
?>
