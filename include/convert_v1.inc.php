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

ini_set( 'zend.ze1_compatibility_mode', 'On' );

// header( "Content-type: text/plain" );

function migrate_insert_img( $collectionid, $baseid, $imageid, &$db, $db_prefix, &$db2 )
{
	$sql = "SELECT * FROM bild WHERE bildid=$imageid";
	$bild = $db2->GetRow( $sql );
	if( $bild )
	{
		$sql = "REPLACE INTO `{$db_prefix}img` (`collectionid`".
									", `imageid`".
									", `img_baseid`".
									", `filename`".
									", `width`".
									", `height`".
									", `xres`".
									", `yres`".
									", `size`".
									", `magick`".
									", `insert_date`".
									", `modify_date`)".
				" VALUES ( $collectionid, $imageid".
				", ".$db->qstr($baseid).
				", ".$db->qstr(stripslashes($bild['filename'])).
				", ".intval($bild['width']).
				", ".intval($bild['height']).
				", ".intval($bild['xres']).
				", ".intval($bild['yres']).
				", ".intval($bild['size']).
				", ".$db->qstr(stripslashes($bild['magick'])).
				", ".$db->qstr(stripslashes($bild['insert_date'])).
				", ".$db->qstr(stripslashes($bild['modify_date'])).
				");";
		echo "$sql\n<br>\n";
		$db->Execute( $sql );
	}
}

function migrate_insert_meta( $collectionid, $baseid, $fields, &$db, $db_prefix, &$db2 )
{
	$name = preg_replace( "/([kK]\\.[[:space:]]*[aA])\\.?/", "", trim(stripslashes($fields['name'])));

	if (strlen($name) > 0 and strlen($fields['vorname']) > 0) {

		$artistname = $name.", ".trim(stripslashes($fields['vorname']));

	} else {

		$artistname = '';

	}

	$sql = 	"REPLACE INTO `{$db_prefix}meta` (`collectionid`".
			", `imageid`".
			", `type`".
			", `status`".
			", `addition`".
			", `title`".
			", `dating`".
			", `material`".
			", `technique`".
			", `format`".
			", `institution`".
			", `literature`".
			", `page`".
			", `figure`".
			", `table`".
			", `isbn`".
			", `keyword`".
			", `insert_date`".
			", `modify_date`".
			", `name1id`".
			", `name2id`".
			", `locationid`".
			", `exp_prometheus`".
			", `exp_sid`".
			", `exp_unimedia`".
			", `metacreator`".
			", `name1`".
			", `location`".
			", `locationsounds`".
			" ) VALUES (".$db->qstr(trim(stripslashes($collectionid))).
			", ".$fields['bildid'].
			", 'image'".
			", 'new'".
			", ".$db->qstr(trim(stripslashes($fields['zusatz']))).
			", ".$db->qstr(trim(stripslashes($fields['titel']))).
			", ".$db->qstr(trim(stripslashes($fields['datierung']))).
			", ".$db->qstr(trim(stripslashes($fields['material']))).
			", ".$db->qstr(trim(stripslashes($fields['technik']))).
			", ".$db->qstr(trim(stripslashes($fields['format']))).
			", ".$db->qstr(trim(stripslashes($fields['institution']))).
			", ".$db->qstr(trim(stripslashes($fields['literatur']))).
			", ".$db->qstr(trim(stripslashes($fields['seite']))).
			", ".$db->qstr(trim(stripslashes($fields['abbildung']))).
			", ".$db->qstr(trim(stripslashes($fields['tafel']))).
			", ".$db->qstr(trim(stripslashes($fields['isbn']))).
			", ".$db->qstr(trim(stripslashes($fields['stichworte']))).
			", ".$db->qstr(trim(stripslashes($fields['insert_date']))).
			", ".$db->qstr(trim(stripslashes($fields['modify_date']))).

			/* Name1ID, Name2ID, LocationID */

			", 1, 0, 1".

			/* Export alle auf 0 */

			", 0, 0, 0".

			/* Creator auf 'dilps-import' setzen */

			", 'dilps-import'".

			", ".$db->qstr($artistname).
			", ".$db->qstr(trim(stripslashes($fields['stadt']))).

			/* Locationsounds */
			", ".$db->qstr('.'.soundex2($db->qstr(trim(stripslashes($fields['stadt'])))).'.').

			");";

	echo "$sql\n<br>\n";
	$db->Execute( $sql );

    dating( $db, stripslashes($fields['datierung']), $datelist );
	if( count( $datelist ))
	{
	   foreach( $datelist as $date )
	   {
	   		$sql = "INSERT INTO {$db_prefix}dating(collectionid,imageid,`from`,`to`) VALUES(4,{$fields['bildid']},{$date['from']},{$date['to']})";
	   		echo "$sql\n";
	   		$db->Execute( $sql );
	   }
	}

 	migrate_insert_img( $collectionid, $baseid, $fields['bildid'], $db, $db_prefix, $db2);
}


function migrate_insert_group( $groupid, $name, $owner, $parentid, &$db, $db_prefix )
{
	$sql = "REPLACE INTO `".$db_prefix."group` ("
				."`groupid`"
				.", `name`"
				.", `owner`"
				.", `parentid`)"
				." VALUES ( "
				.$db->qstr($groupid).", "
				.$db->qstr($name).", "
				.$db->qstr($owner).", "
				.$db->qstr($parentid).				
				");";
				
		echo "$sql\n<br>\n";
		$db->Execute( $sql );
}

function migrate_insert_into_group( $groupid, $collectionid, $imageid, &$db, $db_prefix )
{
	$sql = "REPLACE INTO `".$db_prefix."img_group` ("
				."`groupid`"
				.", `collectionid`"
				.", `imageid`)"
				." VALUES ( "
				.$db->qstr($groupid).", "
				.$db->qstr($collectionid).", "
				.$db->qstr($imageid).
				");";
				
		echo "$sql\n<br>\n";
		$db->Execute( $sql );
}


?>
