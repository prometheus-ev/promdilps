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
 * determine the correct template for editing
 * -------------------------------------------------------------
 * File:     	edit_element.php
 * Purpose:  	determines the correct template for the result
 *				of a query
 * -------------------------------------------------------------
 */

// import standard libraries and configuraiton values
require('includes.inc.php');

// read sessiond id from get/post
if (isset($_REQUEST['PHPSESSID']))
{
	$sessionid = $_REQUEST['PHPSESSID'];
}
else
{
	$sessionid = '';
}

$query = array();
if( is_array( $_REQUEST['query'] ))
	$query = $_REQUEST['query'];
switch( $query['element'] )
{
	case 'name':
	   $tpl = 'edit_element_name.tpl';
		break;
	case 'loc':
	   $tpl = 'edit_element_loc.tpl';
		break;
	case 'src':
	   $tpl = 'edit_element_src.tpl';
		break;
	default:
	   $tpl = 'edit_element_none.tpl';
}

$smarty->assign('sessionid',$sessionid);
$smarty->assign( 'query', $query );

$smarty->display( $config['skin'].DIRECTORY_SEPARATOR.$tpl );

//phpinfo();
?>
