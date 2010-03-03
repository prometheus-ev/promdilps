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
 * PHP function that inserts an image into or deletes it from
 * a DILPS-group
 * -------------------------------------------------------------
 * File:     	view_detail.php
 * Purpose:  	show detailed view of the currently selected image
 * -------------------------------------------------------------
 */

// import standard libraries and configuraiton values
include_once('includes.inc.php');

// read sessiond id from get/post
if (isset($_REQUEST['PHPSESSID']))
{
	$sessionid = $_REQUEST['PHPSESSID'];
}
else
{
	$sessionid = '';
}

// we have a new window
if (isset($_REQUEST['newwin']))
{
	$newwin = $_REQUEST['newwin'];
}
else
{
	$newwin = 0;
}

$smarty->assign('newwin',$newwin);
$smarty->assign('sessionid',$sessionid);

$smarty->display( $config['skin'].'/view_detail.tpl' );

//phpinfo();
?>
