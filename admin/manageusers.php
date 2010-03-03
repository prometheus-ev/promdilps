<?php

	// generate a random password - taken from PHPFAQ

	function genpassword($length){ 

	    srand((double)microtime()*1000000); 
	     
	    $vowels = array("a", "e", "i", "o", "u"); 
	    $cons = array("b", "c", "d", "g", "h", "j", "k", "l", "m", "n", "p", "r", "s", "t", "u", "v", "w", "tr", 
	    "cr", "br", "fr", "th", "dr", "ch", "ph", "wr", "st", "sp", "sw", "pr", "sl", "cl"); 
	     
	    $num_vowels = count($vowels); 
	    $num_cons = count($cons); 
	     
	    for($i = 0; $i < $length; $i++){ 
	        $password .= $cons[rand(0, $num_cons - 1)] . $vowels[rand(0, $num_vowels - 1)]; 
	    } 
	     
	    return substr($password, 0, $length); 
	}  
	
	

	include( '../config.inc.php' );
	include( '../globals.inc.php' );
	include( $config['includepath'].'adodb/adodb.inc.php' );
	
	error_reporting("E_ALL");

	global $db_db, $db_host, $db_prefix, $db_pwd, $db_user;

	$db_driver	= 'mysql';

	define( 'BAD', 0 );
	define( 'GOOD', 1 );
	
	
	
	//	print_r($_REQUEST);
	
	if (isset($_REQUEST['action'])){
		$action = trim($_REQUEST['action']);
	}
	else
	{
		$action = "view";
	}
	
	if (isset($_REQUEST['userid'])){
		$userid = trim(urldecode($_REQUEST['userid']));
	}
	else
	{
		$userid = "";
	}
	
	if (isset($_REQUEST['authtype'])){
		$authtype = trim($_REQUEST['authtype']);
	}
	else
	{
		$authtype = "";
	}
	
	if (isset($_REQUEST['oldauthtype'])){
		$oldauthtype = trim($_REQUEST['oldauthtype']);
	}
	else
	{
		$oldauthtype = "";
	}
	
	if (isset($_REQUEST['line'])){
		$line = intval(trim($_REQUEST['line']));
	}
	else
	{
		$line = -1;
	}	
	
	if (isset($_REQUEST['changed'])){
		$changed = intval(trim($_REQUEST['changed']));
	}
	else
	{
		$changed = 0;
	}
	
	if (isset($_REQUEST['added'])){
		$added = intval(trim($_REQUEST['added']));
	}
	else
	{
		$added = 0;
	}

?>

<html>
<head>
<title> DILPS User &amp; Permission Management  </title>
<link rel="stylesheet" type="text/css" href="admin.css" />
</head>
<body>
<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr class="top-bar">
		<td class="links">
			[ <a href="index.php">Back to Administration</a> ]
		</td>
		<td class="title">
			<a href="<?php echo ($_SERVER['PHP_SELF']); ?>">Manage user logins &amp; permissions<a/>
		</td>
	</tr>
</table>

<?php

	$db = NewADOConnection("mysql");
	$rs = $db->Connect( $db_host, $db_user, $db_pwd, $db_db);
	$db->SetFetchMode(ADODB_FETCH_ASSOC);	
	
	// $db->debug = true;
	
	if (!$rs)
	{
		die("Error connecting to database - please check your configuration");
	}	

?>

<table width="100%" border="1" cellpadding="10" cellspacing="1">

	<tr>
		<th>
			UserID - Expression
		</th>
		<th>
			Authentication
		</th>
		<th>
			Admin?
		</th>
		<th>
			Editor?
		</th>
		<th>
			Add Images?
		</th>
		<th>
			Use Groups?
		</th>
		<th>
			Use Folders?
		</th>
		<th>
			Active?
		</th>
		<th>
			Actions
		</th>
	</tr>

	<?php
	
		if ($changed >0)
		{
			if (empty($userid))
			{
				echo ("<tr>\n<td colspan='9'>Data unchanged - you may not use empty UserID - Expressions</td>\n<tr>");
			}
			else 
			{
				if (empty($authtype))
				{
					echo ("<tr>\n<td colspan='9'>Data unchanged - a transfer error occured while saving your data</td>\n<tr>");
				}
				else 
				{					
					$admin 		= ($_REQUEST['admin'] == 'on' ? 1 : 0);
					$editor 	= ($_REQUEST['editor'] == 'on' ? 1 : 0);
					$addimages 	= ($_REQUEST['addimages'] == 'on' ? 1 : 0);
					$usegroups	= ($_REQUEST['usegroups'] == 'on' ? 1 : 0);
					$usefolders	= ($_REQUEST['userfolders'] == 'on' ? 1 : 0);
					$active		= ($_REQUEST['active'] == 'on' ? 1 : 0);
					
					$sql = "UPDATE ".$db_prefix."user_auth SET "
					."authtype=".$db->qstr($authtype).","
					."admin=".$db->qstr($admin).","
					."editor=".$db->qstr($editor).","
					."addimages=".$db->qstr($addimages).","
					."usegroups=".$db->qstr($usegroups).","
					."usefolders=".$db->qstr($usefolders).","
					."active=".$db->qstr($active)
					." WHERE userid=".$db->qstr($userid);
					
					$rs = $db->Execute($sql);

					if ($rs === false)				
					{
						echo ("<tr>\n<td colspan='9'>Data unchanged - a database error occured while saving your data</td>\n<tr>");
					}
					
					if ($oldauthtype == 'static' && $authtype != 'static')
					{
						$sql = "DELETE FROM ".$db_prefix."user_passwd WHERE "
						."userid=".$db->qstr($userid);
						
						$rs = $db->Execute($sql);
	
						if ($rs === false)				
						{
							echo ("<tr>\n<td colspan='9'>Critical error - a database error occured while deleting the password entry</td>\n<tr>");
						}
					}
					
					if ($oldauthtype != 'static' && $authtype == 'static')
					{
						$randompassword = genpassword(8);
						
						$pwd_hash = md5($randompassword);
						
						$sql = "INSERT INTO ".$db_prefix."user_passwd VALUES("
						.$db->qstr($userid).","
						.$db->qstr($pwd_hash).")";
						
						$rs = $db->Execute($sql);
	
						if ($rs === false)				
						{
							echo ("<tr>\n<td colspan='9'>Data not added - a database error occured while setting the new password</td>\n<tr>");
						}
						
						echo ("<tr>\n<td colspan='9'>The user has been set to static authentification and the password '".$randompassword."' was created. You can change it with the link in the list.</td>\n<tr>");
						
					}
					
				}			
				
				
			}
			
			
		}
		
		if ($added >0)
		{
			if (empty($userid))
			{
				echo ("<tr>\n<td colspan='9'>Data note added - you may not use empty UserID - Expressions</td>\n<tr>");
			}
			else 
			{
				if (empty($authtype))
				{
					echo ("<tr>\n<td colspan='9'>Data not added - a transfer error occured while saving your data</td>\n<tr>");
				}
				else 
				{
					$admin 		= ($_REQUEST['admin'] == 'on' ? 1 : 0);
					$editor 	= ($_REQUEST['editor'] == 'on' ? 1 : 0);
					$addimages 	= ($_REQUEST['addimages'] == 'on' ? 1 : 0);
					$usegroups	= ($_REQUEST['usegroups'] == 'on' ? 1 : 0);
					$usefolders	= ($_REQUEST['userfolders'] == 'on' ? 1 : 0);
					$active		= ($_REQUEST['active'] == 'on' ? 1 : 0);
					
					$sql = 	"SELECT * FROM ".$db_prefix."user_auth WHERE userid="
							.$db->qstr($userid);
					
					$rs  = $db->Execute($sql);
					
					if ($rs === false)				
					{
						echo ("<tr>\n<td colspan='9'>Data not added - a database error occured while saving your data</td>\n<tr>");
					}
					else 
					{
						if ($rs->RecordCount() > 0)
						{
							echo ("<tr>\n<td colspan='9'>Data not added - UserID-Expression already exists</td>\n<tr>");
						}
						else 
						{
							if ($userid == 'public')
							{
								echo ("<tr>\n<td colspan='9'>Data not added - you cannot use 'public' as UserID</td>\n<tr>");
							}
							else 
							{
								$sql = 	"INSERT INTO ".$db_prefix."user_auth VALUES("
									.$db->qstr($userid).","
									.$db->qstr($authtype).","
									.$db->qstr($admin).","
									.$db->qstr($editor).","
									.$db->qstr($addimages).","
									.$db->qstr($usegroups).","
									.$db->qstr($usefolders).","
									.$db->qstr($active).")";
							
								$rs = $db->Execute($sql);
			
								if ($rs === false)				
								{
									echo ("<tr>\n<td colspan='9'>Data not added - a database error occured while saving your data</td>\n<tr>");
								}
								
								if ($authtype == 'static')
								{
									$randompassword = genpassword(8);
									
									$pwd_hash = md5($randompassword);
									
									$sql = "INSERT INTO ".$db_prefix."user_passwd VALUES("
									.$db->qstr($userid).","
									.$db->qstr($pwd_hash).")";
									
									$rs = $db->Execute($sql);
				
									if ($rs === false)				
									{
										echo ("<tr>\n<td colspan='9'>Data not added - a database error occured while setting the new password</td>\n<tr>");
									}
									
									echo ("<tr>\n<td colspan='9'>A new static user has been created with the password '".$randompassword."'. You can change it with the link in the list.</td>\n<tr>");
									
								}
								
							}
							
						}
						
					}
					
				}
				
			}
			
		}
		
		if ($action == "delete" && $userid > -1)
		{
			if ($userid == "")
			{
				echo ("<tr>\n<td colspan='9'>Data unchanged - you must specify the entry you want to delete</td>\n<tr>");
			}
			else 
			{
				$sql = "DELETE FROM ".$db_prefix."user_auth WHERE "
					."userid=".$db->qstr($userid);
					
				$rs = $db->Execute($sql);
	
				if ($rs === false)				
				{
					echo ("<tr>\n<td colspan='9'>Data unchanged - a database error occured while deleting the entry</td>\n<tr>");
				}
				
				if ($authtype == 'static')
					{
						$sql = "DELETE FROM ".$db_prefix."user_passwd WHERE "
						."userid=".$db->qstr($userid);
						
						$rs = $db->Execute($sql);
	
						if ($rs === false)				
						{
												echo ("<tr>\n<td colspan='9'>Critical error - a database error occured while deleting the password entry</td>\n<tr>");
						}
						
					}
				
			}			
		}
		
		$sql 	= "SELECT * FROM ".$db_prefix."user_auth";
		
		$rs 	= $db->Execute($sql);
		
		$count	= 0;
		
		while(!$rs->EOF)
		{
			echo ("<tr>\n");
			
			if ($action == 'edit' && $count == $line)
			{
				echo ("<form method='POST' action='".$_SERVER['PHP_SELF']."'>");
			
				echo(
						"<td>".$rs->fields["userid"]
						."<input type='hidden' name='userid' value='".$rs->fields["userid"]."'"
						."</td>"
					);
				
				echo(
						"<td>"
						."<select name='authtype' size='1'>\n"
      					."<option value='static'"
  					);
  					
				if ($rs->fields['authtype'] == 'static')
				{
					echo (" selected ");
				}
				
				echo (">Static</option>\n");
				
				echo ("<option value='ldap'");
				
				if ($rs->fields['authtype'] == 'ldap')
				{
					echo (" selected ");
				}
				
				echo (">LDAP</option>\n");
				
				echo ("<option value='imap'");
				
				if ($rs->fields['authtype'] == 'imap')
				{
					echo (" selected ");
				}
								
				echo(
						">IMAP</option>\n"
      					."</select>\n"
      					."</td>"
      				);				
				
				echo ("<td><input type='checkbox' name='admin' ");
				
				if ($rs->fields["admin"] > 0)
				{
					echo ("checked='checked'");				
				}			
							
				echo("></td>");
				
				echo ("<td><input type='checkbox' name='editor' ");
				
				if ($rs->fields["editor"] > 0)
				{
					echo ("checked='checked'");				
				}			
							
				echo("></td>");
				
				echo ("<td><input type='checkbox' name='addimages' ");
				
				if ($rs->fields["addimages"] > 0)
				{
					echo ("checked='checked'");				
				}
							
				echo("></td>");
				
				echo ("<td><input type='checkbox' name='usegroups' ");
				
				if ($rs->fields["usegroups"] > 0)
				{
					echo ("checked='checked'");				
				}			
							
				echo("></td>");
				
				echo ("<td><input type='checkbox' name='userfolders' ");
				
				if ($rs->fields["usefolders"] > 0)
				{
					echo ("checked='checked'");				
				}			
							
				echo("></td>");
				
				echo ("<td><input type='checkbox' name='active' ");
				
				if ($rs->fields["active"] > 0)
				{
					echo ("checked='checked'");				
				}
							
				echo("></td>");
				
				echo (	"<td>"
						."<input type='hidden' name='changed' value='1'>\n"
						."<input type='hidden' name='line' value='".$line."'>\n"
						."<input type='hidden' name='oldauthtype' value='".$rs->fields['authtype']."'>\n"
						."<input type='submit' value='Save'>\n"
						."<a href='".$_SERVER['PHP_SELF']."'><button type='button' value='Cancel'>Cancel</button></a>\n"
						."</td>");
				
				echo ("</form>");
				
			}
			else 
			{
				echo ("<td>".$rs->fields["userid"]."</td>");
				
				echo ("<td>".$rs->fields["authtype"]);
				
				if ($rs->fields['authtype'] == 'static')
				{
					echo ("&nbsp; (<a href='changepassword.php?userid=".urlencode($rs->fields['userid'])."'>Change password</a>) &nbsp;");
				}				
				
				echo ("</td>");
				
				echo ("<td><input type='checkbox' ");
				
				if ($rs->fields["admin"] > 0)
				{
					echo ("checked='checked'");				
				}			
							
				echo("></td>");
				
				echo ("<td><input type='checkbox' ");
				
				if ($rs->fields["editor"] > 0)
				{
					echo ("checked='checked'");				
				}			
							
				echo("></td>");
				
				echo ("<td><input type='checkbox' ");
				
				if ($rs->fields["addimages"] > 0)
				{
					echo ("checked='checked'");				
				}
							
				echo("></td>");
				
				echo ("<td><input type='checkbox' ");
				
				if ($rs->fields["usegroups"] > 0)
				{
					echo ("checked='checked'");				
				}			
							
				echo("></td>");
				
				echo ("<td><input type='checkbox' ");
				
				if ($rs->fields["usefolders"] > 0)
				{
					echo ("checked='checked'");				
				}			
							
				echo("></td>");
				
				echo ("<td><input type='checkbox' ");
				
				if ($rs->fields["active"] > 0)
				{
					echo ("checked='checked'");				
				}
							
				echo("></td>");
				
				echo ("<td><a href='".$_SERVER['PHP_SELF']."?action=edit&line=".$count."'><button type='button' value='Edit'>Edit</button></a> <a href='".$_SERVER['PHP_SELF']."?action=delete&userid=".urlencode($rs->fields['userid'])."&authtype=".$rs->fields['authtype']."'><button type='button' value='Delete'>Delete</button></a></td>");
				
			}
			
			echo ("</tr>\n");
			
			$count++;
																		
			$rs->MoveNext();
		}
		
		if ($action != "edit")
		{
			echo ("<tr>\n");
		
			echo ("<form method='POST' action='".$_SERVER['PHP_SELF']."'>");
		
			echo ("<td><input type='text' name='userid'></td>");
			
			echo(
				"<td>"
				."<select name='authtype' size='1'>\n"
				."<option value='static' selected>Static</option>\n"
				."<option value='ldap'>LDAP</option>\n"
				."<option value='imap'>IMAP</option>\n"
				."</select>\n"
				."</td>"
			);				
			
			echo ("<td><input type='checkbox' name='admin' ");
			
			echo ("<td><input type='checkbox' name='editor' ");
			
			echo ("<td><input type='checkbox' name='addimages' ");		
						
			echo("></td>");
			
			echo ("<td><input type='checkbox' name='usegroups' checked='checked'");
						
			echo("></td>");
			
			echo ("<td><input type='checkbox' name='userfolders' checked='checked'");
						
			echo("></td>");
			
			echo ("<td><input type='checkbox' name='active' checked='checked'");
						
			echo("></td>");
			
			echo (	"<td>"
					."<input type='hidden' name='added' value='1'>\n"
					."<input type='submit' value='Add'>\n"
					."</td>");
			
			echo ("</form>");
			
			echo ("</tr>");			
		}
			
	
	?>

</table>