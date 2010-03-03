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
 * exports a group to the DILPS viewer
 * -------------------------------------------------------------
 * File:     	export_group.php
 * Purpose:  	gathers information and inmages from a group
 *				and writes the necessary XML and image files
 *				to use the export with the DILPS viewer
 * -------------------------------------------------------------
 */

// import standard libraries and configuraiton values
require('includes.inc.php');

// import helper functions
include($config['includepath'].'tools.inc.php');

// from block.query.php

function get_groupid_where_clause($groupid, &$db, $db_prefix, $subgroups = true) {
    
    $where = '';
    
	if (!empty($groupid))
	{
		// our result
		$groups = array();
		// first id is the give one
		$groups[] = $groupid;
		$db->SetFetchMode(ADODB_FETCH_ASSOC);
		$sql = "SELECT id FROM ".$db_prefix."group WHERE "
					."parentid = ".$db->qstr($groupid)
					." ORDER BY id"; 	
		$rs  = $db->Execute($sql);
		if (!$rs || !$subgroups)
		{
			// we have no subgroups, just query one id
			$where .= " AND {$db_prefix}img_group.groupid = ".$db->qstr($groupid);
		}
		else
		{
			// get next sublevel		
			while (!$rs->EOF)
			{
				// add group to result array
				$groups[] = $rs->fields['id'];
				// get next but one sublevel, if available
				$sql2 = "SELECT id FROM ".$db_prefix."group WHERE "
							."parentid = ".$db->qstr($rs->fields['id'])
							." ORDER BY id"; 	
				$rs2 = $db->Execute($sql2);
				
				while(!$rs2->EOF)
				{
					$groups[] = $rs2->fields['id'];				
					$rs2->MoveNext();
				}			
				$rs->MoveNext();
			}
			$where .= " AND (0 ";
			foreach ($groups as $gid)
			{
				$where .= " OR {$db_prefix}img_group.groupid = ".$db->qstr($gid);
			}
			$where .= ") ";
		}
	}
    return $where;
}

// read sessiond id from get/post
if (isset($_REQUEST['PHPSESSID']))
{
	$sessionid = $_REQUEST['PHPSESSID'];
}
else
{
	$sessionid = '';
}

global $db, $db_prefix;

global $resolutions_available;
global $formats_available;
global $formats_suffix;
global $imagemagick_identify;
global $imagemagick_convert;
global $file_binary;
global $zip_binary;

$resolutions = $resolutions_available;

global $db, $db_prefix, $dilpsdir, $exportdir, $exportdirlong, $exporturl, $user;

// print_r($_REQUEST);

// $db->debug = true;

if (!empty($_REQUEST['export']))
{
	$export = $_REQUEST['export'];
}
else
{
	$export = false;
}

if (!empty($_REQUEST['groupid'])){
		$groupid = $_REQUEST['groupid'];
} else {
	$groupid = '';
	die ("Error - no group ID\n<br>\n");	
}

if (!empty($_REQUEST['groupname'])){
		$groupname = $_REQUEST['groupname'];
} else {
	$groupname = '';
	echo ("Error - no group name\n<br>\n");
	flush();
	exit;
}

if ($export)
{
	// export with or without viewer program?

	if (!empty($_REQUEST['withviewer'])){
		$withviewer = trim($_REQUEST['withviewer']);
	} else {
		$withviewer = false;
	}
	
	// with or without subgroup content
	
	if (!empty($_REQUEST['subgroups'])){
		if ($_REQUEST['subgroups'] == '1') {
			$subgroups = true;	
		} else  {
			$subgroups = false;
		}		
	} else {
		$subgroups = false;
	}

	// which version of the viewer do we use?
	if (!empty($_REQUEST['targetsystem'])){
		$targetsystem = trim($_REQUEST['targetsystem']);
	} else {
		$targetsystem = 'win';
	}

	// check if export sub-directories already exists and is writeable

	$dir = $exportdir.$user['login'];

	$ret = check_dir($dir,true, true, 0755);

	if (!$ret){
		$errorstring = "Error creating export directory\n<br>\n"
								."Name: ".$dir."\n<br>\n"
								."\n<br>\n";
		die ($errorstring);
	}

	$time = time();
	$date = date("Y-m-d - H-i-s",$time);

	$dir = $exportdir.$user['login'].DIRECTORY_SEPARATOR.$date;

	$ret = check_dir($dir,true, true, 0755);

	if (!$ret){
		$errorstring = "Error creating export directory\n<br>\n"
								."Name: ".$dir."\n<br>\n"
								."\n<br>\n";
		die ($errorstring);
	}

	$dir = $exportdir.$user['login'].DIRECTORY_SEPARATOR.$date.DIRECTORY_SEPARATOR.'xml';

	$ret = check_dir($dir,true, true, 0755);

	if (!$ret){
		$errorstring = "Error creating export directory\n<br>\n"
								."Name: ".$dir."\n<br>\n"
								."\n<br>\n";
		die ($errorstring);
	}

	$dir = $exportdir.$user['login'].DIRECTORY_SEPARATOR.$date.DIRECTORY_SEPARATOR.'images';

	$ret = mkdir($dir,0755);

	if (!$ret){
		$errorstring = "Error creating export directory\n<br>\n"
								."Name: ".$dir."\n<br>\n"
								."\n<br>\n";
		die ($errorstring);
	}

	$dir = $exportdir.$user['login'].DIRECTORY_SEPARATOR.$date.DIRECTORY_SEPARATOR.'thumbnails';

	$ret = mkdir($dir,0755);

	if (!$ret){
		$errorstring = "Error creating export directory\n<br>\n"
								."Name: ".$dir."\n<br>\n"
								."\n<br>\n";
		die ($errorstring);
	}


	// do we need to copy the viewer skeleton?

	if ($withviewer)
	{
		$viewerdir = '';

		if ($targetsystem == 'mac')
		{
			$viewerdir = $dilpsdir.'viewer'.DIRECTORY_SEPARATOR.'mac'.DIRECTORY_SEPARATOR;
		}
		else
		{
			$viewerdir = $dilpsdir.'viewer'.DIRECTORY_SEPARATOR.'windows'.DIRECTORY_SEPARATOR;
		}

		// copy viewer with PHP-functions

		$olddir = getcwd();

		chdir($viewerdir);

		$ret = copy_recursive('.',$exportdirlong.$user['login'].DIRECTORY_SEPARATOR.$date.DIRECTORY_SEPARATOR);

		if (!$ret){
			echo ("Error copying viewer skeleton for current export\n<br>\n");
			echo ("\n<br>\n");
			exit;
		}

		chdir($olddir);

	}

	// start reading actual data
	
	// $db->debug = true;

	$sql = "SELECT DISTINCT * FROM ".$db_prefix."img, ".$db_prefix."img_group, ".$db_prefix."meta"
			." WHERE ".$db_prefix."img.collectionid = ".$db_prefix."meta.collectionid"
			." AND ".$db_prefix."img.imageid = ".$db_prefix."meta.imageid"
			." AND ".$db_prefix."img.collectionid = ".$db_prefix."img_group.collectionid"
			." AND ".$db_prefix."img.imageid = ".$db_prefix."img_group.imageid"
			.get_groupid_where_clause($groupid,$db,$db_prefix,$subgroups);

	$rs = $db->Execute( $sql );
	
	// die ($sql);

	

	while ( !$rs->EOF )
	{
		// print_r($rs->fields);
		
		$xmlout = "<?"."xml version=\"1.0\" encoding=\"UTF-8\" ?".">\n";
		$xmlout .= "<imgdata>\n";
		$xmlout .= "<width>".utf8_encode($rs->fields['width'])."</width>\n";
		$xmlout .= "<height>".utf8_encode($rs->fields['height'])."</height>\n";
		$xmlout .= "<name>".utf8_encode($rs->fields['name1'])."</name>\n";
		$xmlout .= "<vorname>".utf8_encode($rs->fields['name2'])."</vorname>\n";
		$xmlout .= "<titel>".utf8_encode($rs->fields['title'])."</titel>\n";
		$xmlout .= "<datierung>".utf8_encode($rs->fields['dating'])."</datierung>\n";
		$xmlout .= "<material>".utf8_encode($rs->fields['material'])."</material>\n";
		$xmlout .= "<technik>".utf8_encode($rs->fields['technique'])."</technik>\n";
		$xmlout .= "<format>".utf8_encode($rs->fields['format'])."</format>\n";
		$xmlout .= "<stadt>".utf8_encode($rs->fields['location'])."</stadt>\n";
		$xmlout .= "<institution>".utf8_encode($rs->fields['institution'])."</institution>\n";
		$xmlout .= "<literatur>".utf8_encode($rs->fields['literature'])."</literatur>\n";
	    $xmlout .= "<url>".utf8_encode("images".DIRECTORY_SEPARATOR.$rs->fields['filename'])."</url>\n";
		$xmlout .= "<thumbnail>".utf8_encode("thumbnail".DIRECTORY_SEPARATOR.$rs->fields['filename'])."</thumbnail>\n";
	    $xmlout .= "<schlagworte>".utf8_encode($rs->fields['keyword'])."</schlagworte>\n";
	   	$xmlout .= "<quelle>".utf8_encode($rs->fields['imagerights'])."</quelle>\n";
		$xmlout .= "<comment>".utf8_encode($rs->fields['commentary'])."</comment>\n";
		$xmlout .= "</imgdata>\n";
		
		// print_r($xmlout);

		$sql2 = "SELECT base FROM ".$db_prefix."img_base WHERE "
				.$db_prefix."img_base.img_baseid = ".$db->qstr($rs->fields['img_baseid']);

		$path = $db->GetOne ($sql2);

		$copyfilename = $rs->fields['collectionid']."-".$rs->fields['imageid'].".jpg";

		$source = $path.DIRECTORY_SEPARATOR.'cache'.DIRECTORY_SEPARATOR.'1600x1200'.DIRECTORY_SEPARATOR.$copyfilename;

		$dest = $exportdir.$user['login'].DIRECTORY_SEPARATOR.$date.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.$copyfilename;

		/*
		echo ("Source: ".$source."\n<br>\n");
		echo ("Dest: ".$dest."\n<br>\n");
		*/

		$ret = @copy($source,$dest);

		if (!$ret){
			echo ("Error copying image ".$rs->fields['filename']."\n<br>\n");
			echo ("\n<br>\n");
		}

		$source = $path.DIRECTORY_SEPARATOR.'cache'.DIRECTORY_SEPARATOR.'120x90'.DIRECTORY_SEPARATOR.$copyfilename;

		$dest = $exportdir.$user['login'].DIRECTORY_SEPARATOR.$date.DIRECTORY_SEPARATOR.'thumbnails'.DIRECTORY_SEPARATOR.$copyfilename;

		$ret = @copy($source,$dest);

		if (!$ret){
			echo ("Error copying thumbnail for image ".$rs->fields['filename']."\n<br>\n");
			echo ("\n<br>\n");
		}

		$handle = fopen ($exportdir.$user['login'].DIRECTORY_SEPARATOR.$date
							.DIRECTORY_SEPARATOR.'xml'.DIRECTORY_SEPARATOR
							.$rs->fields['collectionid'].'-'.$rs->fields['imageid']
							.'.xml','w');

		fwrite($handle,$xmlout);

		// cleanup
		fclose($handle);
		unset($xmlout);

		flush();

		$rs->MoveNext();
	}

	$old = getcwd();
	chdir ($exportdir.$user['login']);
	$cmd = $zip_binary.' -9 -r -m  '.escapeshellarg($date.'.zip').' '.escapeshellarg($date);

	exec($cmd, $result, $ret);

	if ($ret){
		echo ("Error building archive\n<br>\n");
		print_r($result);
		echo ("\n<br>\n");
	}

	chdir($olddir);

	$ret = @chmod($exportdir.$user['login'].DIRECTORY_SEPARATOR.$date.'.zip',0755);

	if (!$ret){
		echo ("Error changing archive permissions\n<br>\n");
		echo ("\n<br>\n");
	}

	/*
	echo ("Delete: ".$exportdir.DIRECTORY_SEPERATOR.$user['login'].DIRECTORY_SEPERATOR.$date."\n<br>\n");

	$ret = delete_recursive($exportdir.DIRECTORY_SEPERATOR.$user['login'].DIRECTORY_SEPERATOR.$date);

	if (!$ret){
		echo ("Error removing temporary files\n<br>\n");
		echo ("\n<br>\n");
	}
	*/

	$exportdir_to_url = str_replace("\\","/",$exportdir);

	echo ('<html>');
	echo ('<head>');
	echo ('<meta name="robots" content="index,follow">');
	echo ('<meta http-equiv="pragma" content="no-cache">');
	echo ('<meta http-equiv="expires" content="0">');
	echo ('<meta http-equiv="cache-control" content="no-cache">');
	echo ('<meta name="keywords" content="Bilddatenbanksystem, Bilddatenbank, Diathek, digitalisiert">');
	if ($config['utf8'])
	{
		echo ('<meta http-equiv="Content-Type" content="text/html; charset=utf-8">');
	}
	else
	{
		echo ('<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">');
	}
	echo ('<meta http-equiv="Content-Script-Type" content="text/javascript">');
	echo ('<meta http-equiv="Content-Style-Type" content="text/css">');
	echo ('<meta name="author" content="Sebastian Doeweling"> ');
	echo ('<meta name="date" content="2005-12-16T15:36:02+0100">');
	echo ('<link rel="shortcut icon" href="favicon.ico">');
	echo ('<title>. : DILPS : .</title>');
	echo ('<link rel="stylesheet" type="text/css" href="css.php">');
	echo ('</head>');
	echo ('<body class="main">');
	echo ("<table class='header' width='100%'>\n");
	echo ("<tr>\n<td class='result_list'>\n");
	echo ("<strong>This Export:</strong>\n<br>\n");
	echo ("<a href='".$exportdir_to_url.$user["login"].'/'.$date.".zip'>".$date.".zip </a>\n<br>\n");
	echo ("\n<br>\n<br>\n");

	echo ("<strong>All Exports:</strong>\n<br><br>\n");

	$exportdir = opendir($exportdir.$user['login']);



	while ($file = readdir ($exportdir)) {
		if ($file != "." && $file != "..")
		{
			echo "<a href='".$exportdir_to_url.$user['login'].'/'.$file."'>".$file."</a>\n<br>\n";
		}
	}
	closedir($exportdir);

	echo ("<br>\n<br>\n");
	echo ('<strong onclick="window.close();">Close window</strong>');
	echo ("</td>\n</tr>\n</table>");
	echo ('</body>');
	echo ('</html>');
}
else
{
	$smarty->assign('sessionid',$sessionid);
	$smarty->assign('groupid', $groupid);
	$smarty->assign('groupname', $groupname);
	$smarty->display( $config['skin'].DIRECTORY_SEPARATOR.'export_group.tpl' );
}


//phpinfo();
?>
