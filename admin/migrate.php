<?php

	include( '../config.inc.php' );
	include( '../globals.inc.php' );
	include( $config['includepath'].'convert_v1.inc.php');
	include( $config['includepath'].'adodb/adodb.inc.php' );
	include( $config['includepath'].'tools.inc.php' );
	include( $config['includepath'].'thesauri/soundex_fr.php' );

	global $db_db, $db_host, $db_prefix, $db_pwd, $db_user;

	$db_driver	= 'mysql';

	define( 'BAD', 0 );
	define( 'GOOD', 1 );

	if (isset($_REQUEST['step'])){
		$step = $_REQUEST['step'];
	}
	else
	{
		$step = 0;
	}

	if (isset($_REQUEST['ng_hostname'])){
		$ng_hostname = $_REQUEST['ng_hostname'];
	}
	else
	{
		$ng_hostname = '';
	}

	if (isset($_REQUEST['ng_username'])){
		$ng_username = $_REQUEST['ng_username'];
	}
	else
	{
		$ng_username = '';
	}

	if (isset($_REQUEST['ng_password'])){
		$ng_password = $_REQUEST['ng_password'];
	}
	else
	{
		$ng_password = '';
	}

	if (isset($_REQUEST['ng_name'])){
		$ng_name = $_REQUEST['ng_name'];
	}
	else
	{
		$ng_name = '';
	}

	if (isset($_REQUEST['ng_prefix'])){
		$ng_prefix = $_REQUEST['ng_prefix'];
	}
	else
	{
		$ng_prefix = '';
	}

	if (isset($_REQUEST['old_hostname'])){
		$old_hostname = $_REQUEST['old_hostname'];
	}
	else
	{
		$old_hostname = '';
	}

	if (isset($_REQUEST['old_username'])){
		$old_username = $_REQUEST['old_username'];
	}
	else
	{
		$old_username = '';
	}

	if (isset($_REQUEST['old_password'])){
		$old_password = $_REQUEST['old_password'];
	}
	else
	{
		$old_password = '';
	}

	if (isset($_REQUEST['old_name'])){
		$old_name = $_REQUEST['old_name'];
	}
	else
	{
		$old_name = '';
	}

?>

<html>
<head>
<title> DILPS Installer  </title>
<link rel="stylesheet" type="text/css" href="admin.css" />
</head>
<body>
<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr class="top-bar">
		<td class="links">
			[ <a href="index.php">Back to Administration</a> ]
		</td>
		<td class="title">
		<?php
			switch ( $step ) {
				case 3:
					echo "Processing";
					break;
				case 2:
					echo "Test and Proceed";
					break;
				case 1:
					echo "Select Collections";
					break;
				case 0:
				default:
					echo "Collect Database Information";
					break;
			}
		?>
		</td>
	</tr>
</table>


<form method='POST'>
<?php

	$failed = false;

	if ( 0 == $step)
	{

	?>

	<table width="100%" border="0" cellpadding="10" cellspacing="1">
		<tr>
			<td bgcolor="#e8e8e8" colspan="2">
				<span class="title">Target Database Option</span> (read from your configuration file)
			</td>
		</tr>

		<tr>
			<td>
				Hostname (for Database Server)
			</td>
			<td>
				<input size="80"  name="ng_hostname" type="textbox" value="<?php echo ($db_host); ?>"></input>
			</td>
		</tr>

		<tr>
			<td>
				Username (for Database)
			</td>
			<td>
				<input  size="80" name="ng_username" type="textbox" value="<?php echo ($db_user); ?>"></input>
			</td>
		</tr>

		<tr>
			<td>
				Password (for Database)
			</td>
			<td>
				<input  size="80" name="ng_password" type="password" value="<?php echo ($db_pwd); ?>"></input>
			</td>
		</tr>

		<tr>
			<td>
				Database name (for Database)
			</td>
			<td>
				<input  size="80" name="ng_name" type="textbox" value="<?php echo ($db_db); ?>"></input>
			</td>
		</tr>

		<tr>
			<td>
			  Table prefix (for Database)
			</td>
			<td>
				<input  size="80" name="ng_prefix" type="textbox" value="<?php echo ($db_prefix); ?>"></input>
			</td>
		</tr>

		<tr>
			<td bgcolor="#e8e8e8" colspan="2">
				<span class="title">Source Database Option</span> (guessed by installer)
			</td>
		</tr>

		<tr>
			<td>
				Hostname (for Database Server)
			</td>
			<td>
				<input size="80"  name="old_hostname" type="textbox" value="<?php echo ($db_host); ?>"></input>
			</td>
		</tr>

		<tr>
			<td>
				Username (for Database)
			</td>
			<td>
				<input  size="80" name="old_username" type="textbox" value="<?php echo ($db_user); ?>"></input>
			</td>
		</tr>

		<tr>
			<td>
				Password (for Database)
			</td>
			<td>
				<input  size="80" name="old_password" type="password" value="<?php echo ($db_pwd); ?>"></input>
			</td>
		</tr>

		<tr>
			<td>
				Database name (for Database)
			</td>
			<td>
				<input  size="80" name="old_name" type="textbox" value="<?php echo ($db_db); ?>"></input>
			</td>
		</tr>
		<tr>
			<td>
				<input type="hidden" name="step" value="1">
			</td>
			<td>
				<input type="button" value="Proceed" onclick="submit()">
			</td>
		</tr>
	</table>

	<?php

	}
	else if (1 == $step)
	{

	?>

		<!-- Test the values and select tables to migrate -->
		<table width="100%" border="0" cellpadding="10" cellspacing="1">
			<tr>
				<td bgcolor="#e8e8e8" colspan="2">
					<span class="title">Testing Databases</span>
				</td>
			</tr>

			<tr>
				<td bgcolor="#ffffff">
					Attempting to connect to target database
				</td>

				<?php

					$ng_db = NewADOConnection("mysql");
					$ng_res = $ng_db->NConnect( $ng_hostname, $ng_username, $ng_password, $ng_name);

					$ng_db->SetFetchMode(ADODB_FETCH_ASSOC);


					echo '<td ';
					if ( !$ng_res) {
							echo 'bgcolor="red">BAD';
							$failed = true;
					}
					else
					{
						echo 'bgcolor="green">GOOD';
					}
					echo '</td>';
				?>

			</tr>
			<tr>
				<td bgcolor="#ffffff">
					Attempting to connect to source database
				</td>
				<?php

					$old_db = NewADOConnection("mysql");
					$old_res = $old_db->NConnect( $old_hostname, $old_username, $old_password, $old_name);

					$old_db->SetFetchMode(ADODB_FETCH_ASSOC);


					echo '<td ';
					if ( !$old_res) {
							echo 'bgcolor="red">BAD';
							$failed = true;
					}
					else
					{
						echo 'bgcolor="green">GOOD';
					}
					echo '</td>';

				?>
			</tr>
		</table>

		<table width="100%" border="0" cellpadding="10" cellspacing="1">
			<tr>
				<td bgcolor="#e8e8e8" colspan="2">
					<span class="title">Select the source collection</span>
				</td>
			</tr>
		</table>
		<table width="100%">
			<tr>

				<?php
					$sql = "SELECT smg.sammlungid as sammlungid, smg.name as name, smg.sammlung_ort as sammlung_ort, "
							."imb.base as base, imb.img_baseid as baseid "
							."FROM sammlung AS smg LEFT JOIN img_base imb ON smg.sammlungid = imb.sammlungid "
							."WHERE active = 1 AND base != '' ORDER BY sammlungid, baseid";
					$old_rs = @$old_db->Execute( $sql );

					echo '<td>';
					if ($old_rs === false)
					{
						$failed = true;
						echo '<strong>No active collections where found</strong>';

					}
					else
					{
						echo ('<table width="100%" border="0" cellpadding="10" cellspacing="1">');
							echo ('<tr>');
								echo ('<td width="10%"><strong>&nbsp; #</strong></td>');
								echo ('<td width="40%"><strong>Name</strong></td>');
								echo ('<td width="20%"><strong>Place</strong></td>');
								echo ('<td width="30%"><strong>Image directory</strong></td>');
							echo ('</tr>');

							while (!$old_rs->EOF)
							{
								echo ('<tr>');
									echo ('<td>');
									echo ('<input type="radio" name="old_baseid" value="'.$old_rs->fields['baseid'].'">'.$old_rs->fields['sammlungid'].":".$old_rs->fields['baseid'].'<br>');
									echo ('</td>');
									echo ('<td>');
									echo ($old_rs->fields['name']);
									echo ('</td>');
									echo ('<td>');
									echo ($old_rs->fields['sammlung_ort']);
									echo ('</td>');
									echo ('<td>');
									echo ($old_rs->fields['base']);
									echo ('</td>');
								echo ('</tr>');

								$old_rs->MoveNext();
							}
						echo ('</table>');
					}

					echo '</td>';
				?>
			</tr>
		</table>

		<table width="100%" border="0" cellpadding="10" cellspacing="1">
			<tr>
				<td bgcolor="#e8e8e8" colspan="2">
					<span class="title">Select the target collection</span>
				</td>
			</tr>
		</table>
		<table width="100%">
			<tr>
				<?php

					$sql = "SELECT col.collectionid AS collectionid, col.name AS name, col.sammlung_ort AS sammlung_ort, "
							."imb.base AS base, imb.img_baseid AS baseid "
							."FROM ".$ng_prefix."collection AS col "
							."LEFT JOIN ".$ng_prefix."img_base AS imb ON col.collectionid = imb.collectionid "
							."WHERE active = 1 AND base != '' "
							."ORDER BY collectionid, baseid";

					$ng_rs = @$ng_db->Execute( $sql );

					if ($ng_rs === false)
					{
						$failed = true;
						echo '<strong>No active collections where found</strong>';

					}
					else
					{
						echo ('<table width="100%" border="0" cellpadding="10" cellspacing="1">');
							echo ('<tr>');
								echo ('<td width="10%"><strong>&nbsp; #</strong></td>');
								echo ('<td width="40%"><strong>Name</strong></td>');
								echo ('<td width="20%"><strong>Place</strong></td>');
								echo ('<td width="30%"><strong>Image directory</strong></td>');
							echo ('</tr>');

							while (!$ng_rs->EOF)
							{
								echo ('<tr>');
									echo ('<td>');
									echo ('<input type="radio" name="ng_baseid" value="'.$ng_rs->fields['baseid'].'">'.$ng_rs->fields['collectionid'].":".$ng_rs->fields['baseid'].'<br>');
									echo ('</td>');
									echo ('<td>');
									echo ($ng_rs->fields['name']);
									echo ('</td>');
									echo ('<td>');
									echo ($ng_rs->fields['sammlung_ort']);
									echo ('</td>');
									echo ('<td>');
									echo ($ng_rs->fields['base']);
									echo ('</td>');
								echo ('</tr>');

								$ng_rs->MoveNext();
							}
						echo ('</table>');
					}

					echo '</td>';
				?>
			</tr>
		</table>

		<input size="80"  name="ng_hostname" type="hidden" value="<?php echo ($ng_hostname); ?>"></input>
		<input  size="80" name="ng_username" type="hidden" value="<?php echo ($ng_username); ?>"></input>
		<input  size="80" name="ng_password" type="hidden"" value="<?php echo ($ng_password); ?>"></input>
		<input  size="80" name="ng_name" type="hidden"" value="<?php echo ($ng_name); ?>"></input>
		<input  size="80" name="ng_prefix" type="hidden" value="<?php echo ($ng_prefix); ?>"></input>
		<input size="80"  name="old_hostname" type="hidden" value="<?php echo ($old_hostname); ?>"></input>
		<input  size="80" name="old_username" type="hidden" value="<?php echo ($old_username); ?>"></input>
		<input  size="80" name="old_password" type="hidden" value="<?php echo ($old_password); ?>"></input>
		<input  size="80" name="old_name" type="hidden" value="<?php echo ($old_name); ?>"></input>

		<?php

		if (!$failed)
		{
			?>
			<table width="100%" border="0" cellpadding="10" cellspacing="1">
				<tr>
					<td width="48%">
						<strong>
							Please note that this migration tool will only work <br/>
							predictably, if the target collection is still empty!
						</strong>
						<input type="hidden" name="step" value="2">
					</td>
					<td width="52%">
						<input type="button" value="Proceed" onclick="submit()">
					</td>
				</tr>
			</table>
		<?php
		}

		$old_db->Close();
		$ng_db->Close();

	}
	elseif (2 == $step) {

		?>

		<table width="100%" border="0" cellpadding="10" cellspacing="1">
			<tr>
				<td bgcolor="#e8e8e8" colspan="2">
					<span class="title">Checking directories</span>
				</td>
			</tr>

			<tr>
				<td bgcolor="#ffffff">
					Source directory exists
				</td>

				<?php
					$old_db = NewADOConnection("mysql");
					$old_res = $old_db->NConnect( $old_hostname, $old_username, $old_password, $old_name);
					$old_db->SetFetchMode(ADODB_FETCH_ASSOC);


					$sql = "SELECT smg.sammlungid as sammlungid, smg.name as name, smg.sammlung_ort as sammlung_ort, "
							."imb.base as base, imb.img_baseid AS baseid "
							."FROM sammlung AS smg LEFT JOIN img_base imb ON smg.sammlungid = imb.sammlungid "
							."WHERE imb.img_baseid = ".$old_db->qstr($_REQUEST['old_baseid']);
					$rs = @$old_db->Execute( $sql );

					$olddir_exists = is_dir($rs->fields["base"]);

					$old_collection = $rs->fields['sammlungid'];
					$old_baseid = $_REQUEST['old_baseid'];

					echo '<td ';
					if ( $olddir_exists === false ) {
							echo 'bgcolor="red">BAD';
							$failed = true;
					}
					else
					{
						echo 'bgcolor="green">GOOD';
					}
					echo '</td>';
				?>

			</tr>
			<tr>
				<td bgcolor="#ffffff">
					Destination directory exists and is writeable
				</td>

				<?php
					$ng_db = NewADOConnection("mysql");
					$ng_res = $ng_db->NConnect( $ng_hostname, $ng_username, $ng_password, $ng_name);

					$ng_db->SetFetchMode(ADODB_FETCH_ASSOC);

					$sql = "SELECT col.collectionid AS collectionid, col.name AS name, col.sammlung_ort AS sammlung_ort, "
							." imb.base AS base, imb.img_baseid AS baseid "
							."FROM ".$ng_prefix."collection AS col "
							."LEFT JOIN ".$ng_prefix."img_base AS imb ON col.collectionid = imb.collectionid "
							."WHERE imb.img_baseid = ".$ng_db->qstr($_REQUEST['ng_baseid']);
					$rs = @$ng_db->Execute( $sql );

					$ngdir_exists = @is_dir($rs->fields["base"]);
					$ngdir_writeable = @fopen( $rs->fields['base'] . DIRECTORY_SEPARATOR . 'test.txt', 'w+' );

					$ng_collection = $rs->fields['collectionid'];
					$ng_baseid = $_REQUEST['ng_baseid'];

					echo '<td ';
					if ( false !== ($ngdir_exists && $ngdir_writeable) ) {
						echo 'bgcolor="green">GOOD';
						fclose( $ngdir_writeable);
						@unlink( $rs->fields['base'] . DIRECTORY_SEPARATOR . 'test.txt');
					} else {
						echo 'bgcolor="red">BAD';
						$failed = true;
					}
					echo '</td>';

				?>

			</tr>
		</table>

		<input size="80"  name="ng_hostname" type="hidden" value="<?php echo ($ng_hostname); ?>"></input>
		<input  size="80" name="ng_username" type="hidden" value="<?php echo ($ng_username); ?>"></input>
		<input  size="80" name="ng_password" type="hidden"" value="<?php echo ($ng_password); ?>"></input>
		<input  size="80" name="ng_name" type="hidden"" value="<?php echo ($ng_name); ?>"></input>
		<input  size="80" name="ng_prefix" type="hidden" value="<?php echo ($ng_prefix); ?>"></input>
		<input size="80"  name="old_hostname" type="hidden" value="<?php echo ($old_hostname); ?>"></input>
		<input  size="80" name="old_username" type="hidden" value="<?php echo ($old_username); ?>"></input>
		<input  size="80" name="old_password" type="hidden" value="<?php echo ($old_password); ?>"></input>
		<input  size="80" name="old_name" type="hidden" value="<?php echo ($old_name); ?>"></input>
		<input  size="80" name="old_collection" type="hidden" value="<?php echo ($old_collection); ?>"></input>
		<input  size="80" name="ng_collection" type="hidden" value="<?php echo ($ng_collection); ?>"></input>
		<input  size="80" name="old_baseid" type="hidden" value="<?php echo ($old_baseid); ?>"></input>
		<input  size="80" name="ng_baseid" type="hidden" value="<?php echo ($ng_baseid); ?>"></input>

			<?php
			if (!$failed)
			{
				?>
				<table width="100%" border="0" cellpadding="10" cellspacing="1">
					<tr>
						<td>
							<input type="hidden" name="step" value="3">
							<input type="button" value="Proceed" onclick="submit()">
						</td>
					</tr>
				</table>

			<?php
			}

			$old_db->Close();
			$ng_db->Close();

		}
		elseif (3 == $step) {
			

			$old_db = NewADOConnection("mysql");
			$old_res = $old_db->NConnect( $old_hostname, $old_username, $old_password, $old_name);
			$old_db->SetFetchMode(ADODB_FETCH_ASSOC);
			
			// $old_db->debug = true;
			
			$ng_db = NewADOConnection("mysql");
			$ng_res = $ng_db->NConnect( $ng_hostname, $ng_username, $ng_password, $ng_name);
			$ng_db->SetFetchMode(ADODB_FETCH_ASSOC);
			
			// $ng_db->debug = true;
			
			// get old image base and image table

			$sql = "SELECT smg.sammlungid as sammlungid, smg.tabelle as tabelle, imb.base as base, imb.img_baseid AS baseid "
					."FROM sammlung AS smg LEFT JOIN img_base imb ON smg.sammlungid = imb.sammlungid "
					."WHERE imb.img_baseid = ".$old_db->qstr($_REQUEST['old_baseid']);
			$rs = @$old_db->Execute( $sql );

			$old_table = $rs->fields['tabelle'];

			$old_img_dir = $rs->fields['base'];
			
			// get new image base

			$sql3 = "SELECT col.collectionid AS collectionid, col.name AS name, col.sammlung_ort AS sammlung_ort, imb.base AS base, imb.img_baseid AS baseid "
							."FROM ".$ng_prefix."collection AS col "
							."LEFT JOIN ".$ng_prefix."img_base AS imb ON col.collectionid = imb.collectionid "
							."WHERE imb.img_baseid = ".$ng_db->qstr($_REQUEST['ng_baseid']);
			$rs3 = @$ng_db->Execute( $sql3 );

			$new_img_dir = $rs3->fields['base'];
			
			// convert all old database entries
			
			/*

			$sql2 = "SELECT ".$old_table.".*,".$old_table."_bild.bildid FROM ".$old_table.",".$old_table."_bild WHERE ".$old_table.".bildnr = ".$old_table."_bild.bildnr";
			// echo "$sql2\n";
			$rs2 = $old_db->Execute( $sql2 );			
			
			while( !$rs2->EOF )
			{
				migrate_insert_meta($_REQUEST['ng_collection'], $_REQUEST['ng_baseid'], $rs2->fields, $ng_db, $ng_prefix, $old_db );
				$rs2->MoveNext();
			}
			
			// convert all old groups
			
			$sql4 = "SELECT groupsid, owner, name FROM `groups` WHERE 1";
			$rs4 = @$old_db->Execute( $sql4 );
			
			while( !$rs4->EOF)
			{
				migrate_insert_group($rs4->fields['groupsid'],$rs4->fields['name'],'public',0,$ng_db,$ng_prefix);
				$rs4->MoveNext();
			}
			
			// transfer the group content (imageid's) to new groups (only for images for which we
			// have db entries
			
			
			$sql2 = "SELECT ".$old_table.".*,".$old_table."_bild.bildid FROM ".$old_table.",".$old_table."_bild WHERE ".$old_table.".bildnr = ".$old_table."_bild.bildnr";
			
			$sql5 	= 	"SELECT g.groupsid as groupsid, g.bildid as bildid FROM `groups_bild` as g, `".$old_table."_bild` as b WHERE "
						."g.bildid = b.bildid";
			$rs5 	= 	@$old_db->Execute( $sql5 );
			
			while( !$rs5->EOF)
			{
				migrate_insert_into_group($rs5->fields['groupsid'],$_REQUEST['ng_collection'],$rs5->fields['bildid'],$ng_db,$ng_prefix);
				$rs5->MoveNext();
			}			
			
			
			// generate an artist cache

			$sql = "SELECT DISTINCT name1 FROM {$ng_prefix}meta WHERE"
					." collectionid = ".$ng_db->qstr($_REQUEST['ng_collection'])
					." AND name1 != ''";
			$rs = $ng_db->Execute( $sql );
			while( !$rs->EOF )
			{
				$name = $ng_db->qstr(stripslashes($rs->fields['name1']));
				$artistnames = explode(',',$name);

				if (isset($artistnames[0])){

					if (strlen($artistnames[0]))
					{
						$art_name = trim(stripslashes(($artistnames[0])));
					}

				}
				else
				{
					$art_name = '';
				}

				if (isset($artistnames[1])){
					if (strlen($artistnames[1]))
					{
						$art_surname = trim(stripslashes($artistnames[1]));
					}
				}
				else
				{
						$art_surname = '';
				}

				$soundslike = '';

				if (strlen($art_surname) > 0){

					$soundslike .= '.'.soundex2($art_surname).'.';

				}

				if (strlen($art_name) > 0){

					$soundslike .= soundex2($art_name).'.';

				}

				$soundslike = $ng_db->qstr($soundslike);

				$sql = "REPLACE INTO `{$ng_prefix}artist` (src,name,sounds) VALUES('dilps',$name,".$soundslike.")";
				echo $sql."\n<br>\n";
				$rs2 = $ng_db->Execute($sql);
				if( !$rs2 )
				{
					echo $ng_db->ErrorMsg()."\n<br>\n";
				}
				$rs->MoveNext();
			}
			
			$sql = "SELECT * FROM {$ng_prefix}artist";
			$rs = $ng_db->Execute( $sql );
			while( !$rs->EOF )
			{
				$sql2 = "UPDATE `{$ng_prefix}meta` SET name1id = ".$ng_db->qstr($rs->fields['id']).
						", name1sounds = ".$ng_db->qstr($rs->fields['sounds']).
						" WHERE name1 = ".$ng_db->qstr($rs->fields['name']);

				echo $sql2."\n<br>\n";
				$rs2 = $ng_db->Execute($sql2);
				if( !$rs2 )
				{
					echo $ng_db->ErrorMsg()."\n<br>\n";
				}
				
				$sql2 = "UPDATE `{$ng_prefix}meta` SET name2id = ".$ng_db->qstr($rs->fields['id']).
						", name2sounds = ".$ng_db->qstr($rs->fields['sounds']).
						" WHERE name2 = ".$ng_db->qstr($rs->fields['name']);

				echo $sql2."\n<br>\n";
				$rs2 = $ng_db->Execute($sql2);
				if( !$rs2 )
				{
					echo $ng_db->ErrorMsg()."\n<br>\n";
				}
				
				$rs->MoveNext();
			}
			
			$sql = "SELECT DISTINCT name2 FROM {$ng_prefix}meta WHERE"
					." collectionid = ".$ng_db->qstr($_REQUEST['ng_collection'])
					." AND name2 != ''";
			$rs = $ng_db->Execute( $sql );
			while( !$rs->EOF )
			{
				$name = $ng_db->qstr(stripslashes($rs->fields['name2']));
				$artistnames = explode(',',$name);

				if (isset($artistnames[0])){

					if (strlen($artistnames[0]))
					{
						$art_name = trim(stripslashes(($artistnames[0])));
					}

				}
				else
				{
					$art_name = '';
				}

				if (isset($artistnames[1])){
					if (strlen($artistnames[1]))
					{
						$art_surname = trim(stripslashes($artistnames[1]));
					}
				}
				else
				{
						$art_surname = '';
				}

				$soundslike = '';

				if (strlen($art_surname) > 0){

					$soundslike .= '.'.soundex2($art_surname).'.';

				}

				if (strlen($art_name) > 0){

					$soundslike .= soundex2($art_name).'.';

				}

				$soundslike = $ng_db->qstr($soundslike);

				$sql = "REPLACE INTO `{$ng_prefix}artist` (src,name,sounds) VALUES('dilps',$name,".$soundslike.")";
				echo $sql."\n<br>\n";
				$rs2 = $ng_db->Execute($sql);
				if( !$rs2 )
				{
					echo $ng_db->ErrorMsg()."\n<br>\n";
				}
				$rs->MoveNext();
			}

			$sql = "SELECT * FROM {$ng_prefix}artist";
			$rs = $ng_db->Execute( $sql );
			while( !$rs->EOF )
			{
				$sql2 = "UPDATE `{$ng_prefix}meta` SET name1id = ".$ng_db->qstr($rs->fields['id']).
						", name1sounds = ".$ng_db->qstr($rs->fields['sounds']).
						" WHERE name1 = ".$ng_db->qstr($rs->fields['name']);

				echo $sql2."\n<br>\n";
				$rs2 = $ng_db->Execute($sql2);
				if( !$rs2 )
				{
					echo $ng_db->ErrorMsg()."\n<br>\n";
				}
				
				$sql2 = "UPDATE `{$ng_prefix}meta` SET name2id = ".$ng_db->qstr($rs->fields['id']).
						", name2sounds = ".$ng_db->qstr($rs->fields['sounds']).
						" WHERE name2 = ".$ng_db->qstr($rs->fields['name']);

				echo $sql2."\n<br>\n";
				$rs2 = $ng_db->Execute($sql2);
				if( !$rs2 )
				{
					echo $ng_db->ErrorMsg()."\n<br>\n";
				}
				
				$rs->MoveNext();
			}
			
			$sql = "SELECT DISTINCT location, locationsounds FROM {$ng_prefix}meta WHERE"
					." collectionid = ".$ng_db->qstr($_REQUEST['ng_collection'])
					." AND location != ''";
			$rs = $ng_db->Execute( $sql );
			while( !$rs->EOF )
			{
				$location = trim(stripslashes($rs->fields['location']));
				$locationsounds = trim(stripslashes(soundex2($location)));
				$sql2 = "REPLACE INTO `{$ng_prefix}location` (src, source_id, location, sounds) "
						."VALUES ('dilps','dilps_v1',".$ng_db->qstr($location).", ".$ng_db->qstr($locationsounds).")";
				echo $sql2."\n<br>\n";
				$rs2 = $ng_db->Execute($sql2);
				if( !$rs2 )
				{
					echo $ng_db->ErrorMsg()."\n<br>\n";
				}
				$rs->MoveNext();
			}

			$sql = "SELECT * FROM {$ng_prefix}location";
			$rs = $ng_db->Execute( $sql );

			while( !$rs->EOF )
			{
				$sql2 = "UPDATE `{$ng_prefix}meta` SET locationid = ".$ng_db->qstr($rs->fields['id']).
						", locationsounds = ".$ng_db->qstr($rs->fields['sounds']).
						" WHERE location = ".$ng_db->qstr($rs->fields['location']);

				echo $sql2."\n<br>\n";
				$rs2 = $ng_db->Execute($sql2);
				if( !$rs2 )
				{
					echo $ng_db->ErrorMsg()."\n<br>\n";
				}
				$rs->MoveNext();
			}
			
			*/
			
			// database updates done, copy files
			
			echo ("Database conversion complete. Your old files will now be copied.\n<br>\n");

			$resolutions = array(
				"0"	=>	"120x90",
				"1"	=>	"640x480",
				"2"	=>	"800x600",
				"3"	=>	"1024x768",
				"4"	=>	"1280x1024",
				"5"	=>	"1600x1200"
			);

			$ng_sql = "SELECT * FROM ".$ng_prefix."img WHERE collectionid = ".$ng_db->qstr($_REQUEST['ng_collection']);
			$ng_rs = $ng_db->Execute($ng_sql);

			while (!$ng_rs->EOF) {

				$collectionid = $ng_rs->fields['collectionid'];
				$imageid = $ng_rs->fields['imageid'];

				$old_sql = "SELECT * FROM bild WHERE bildid=".$ng_db->qstr($imageid);
				$old_rs = $old_db->GetRow( $old_sql );

				if( $old_rs )
				{
					foreach ($resolutions as $res) {

						$old_file = $old_img_dir.DIRECTORY_SEPARATOR.'cache'.DIRECTORY_SEPARATOR.$res.DIRECTORY_SEPARATOR.$imageid.'.jpg';
						$new_file = $new_img_dir.DIRECTORY_SEPARATOR.'cache'.DIRECTORY_SEPARATOR.$res.DIRECTORY_SEPARATOR.$collectionid.'-'.$imageid.'.jpg';

						/*
						echo ("Res:".$res."\n<br>\n");
						echo ("Old: ".$old_file."\n<br>\n");
						echo ("New: ".$new_file."\n<br>\n");
						*/

						if (file_exists($old_file)) {
							
							if (file_exists($new_file))
							{
								/*
								$errorstring = "Skipping file! \n<br>\n";
								$errorstring .= "File:\t\t ".$old_file." \n<br>\n";
								$errorstring .= "Resolution:\t ".$res." \n<br>\n";
								echo ($errorstring);
								*/
							}
							else 
							{

								$ret = @copy($old_file,$new_file);
	
								if (!$ret) {
									$errorstring = "Error copying file! \n<br>\n";
									$errorstring .= "File:\t\t ".$old_file." \n<br>\n";
									$errorstring .= "Resolution:\t ".$res." \n<br>\n";
									echo ($errorstring);
	
								} else {
	
									$errorstring = "\n File successfully copied!\n<br>\n";
									$errorstring .= "File:\t\t ".$old_file." \n<br>\n";
									$errorstring .= "Resolution:\t ".$res." \n<br>\n";
									echo ($errorstring);
	
								}
							}

						}

					}
				}
				
				$ng_rs->MoveNext();

			}

			$old_db->Close();
			$ng_db->Close();

			?>

			<table width="100%" border="0" cellpadding="10" cellspacing="1">
				<tr>
					<td>
						<strong>
							Your collection has been migrated to your new DILPS.<br>
							See above output for detail.
						</strong>
					</td>
				</tr>
				<tr></tr>
					<td class="links">
						<a href="index.php">Back to Administration</a>
					</td>
				</tr>
			</table>

		<?php

		}

	?>

	</form>

</body>
</html>
