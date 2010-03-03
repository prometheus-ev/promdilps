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
 * Database handling
 * -------------------------------------------------------------
 * File:	  db.inc.php
 * Purpose:	  Establish a connection to the database and assign
 *			  the global variables
 * -------------------------------------------------------------
 */


include_once( $config['includepath'].'adodb/adodb.inc.php' );

$db = NewADOConnection( "mysql" );
$res = $db->PConnect( $db_host, $db_user, $db_pwd, $db_db );

// $db->debug = true;

$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;

if( !$res )
{
	?>
	
	<html>
	<head><title>Database Error</title></head>
	<body>
	<h1>Database Error</h1>
	<table border="0">
	<tr><td align="right"><b>type</b></td><td><?= $db->dataProvider ?></td></tr>
	<!--
	<tr><td align="right"><b>function</b></td><td><?= $db->fnExecute?></td></tr>
	<tr><td align="right"><b>params</b></td><td><?= $db->params ?></td></tr>
	-->
	<tr><td align="right"><b>host</b></td><td><?= $db->host ?></td></tr>
	<tr><td align="right"><b>database</b></td><td><?= $db->database ?></td></tr>
	<tr><td align="right"><b>message</b></td><td><?= $db->ErrorMsg(); ?></td></tr>
	</table>
	</body>
	
	<?php
	die( "" );
}


$sql = "SELECT * FROM {$db_prefix}config";
$result = $db->Execute( $sql );

while( !$result->EOF ) 
{
	$name = $result->fields["name"];
    $config[$name] = $result->fields["val"];
    $result->MoveNext();
}
   
   // print_r($config);

?>