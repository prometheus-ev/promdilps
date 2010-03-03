<?php

	include( '../config.inc.php' );
	include( '../globals.inc.php' );
	include( $config['includepath'].'adodb/adodb.inc.php' );
	
	error_reporting("E_ALL");

	global $db_db, $db_host, $db_prefix, $db_pwd, $db_user;

	$db_driver	= 'mysql';

	//	print_r($_REQUEST);
	
	if (isset($_REQUEST['userid'])){
		$userid = trim(urldecode($_REQUEST['userid']));
	}
	else
	{
		$userid = "";
	}
	
	if (isset($_REQUEST['changed'])){
		$changed = intval(trim($_REQUEST['changed']));
	}
	else
	{
		$changed = 0;
	}
	
?>

<html>
<head>
<title> DILPS Password Management</title>
<link rel="stylesheet" type="text/css" href="admin.css" />
</head>
<body>
<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr class="top-bar">
		<td class="links">
			[ <a href="manageusers.php">Back to User Management</a> ]
		</td>
		<td class="title">
			<a href="<?php echo ($_SERVER['PHP_SELF']); ?>">Manage passwords for static users<a/>
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

<table border="1" cellpadding="10" cellspacing="1">

	<tr>
		<th>
			UserID
		</th>
		<th>
			Password
		</th>
		<th>
			Password (Confirm)
		</th>
		<th>
			Action
		</th>
	</tr>

	<?php
	
		if ($changed >0)
		{
			if (empty($userid))
			{
				echo ("<tr>\n<td colspan='4'>Data unchanged - an empty UserID was passed</td>\n<tr>");
			}
			else 
			{
				if (empty($_REQUEST['password']))
				{
					echo ("<tr>\n<td colspan='4'>You may not use empty passwords</td>\n<tr>");
				}
				else 
				{
					$passwd = trim($_REQUEST['password']);
					
					if (empty($_REQUEST['confirm']))
					{
						echo ("<tr>\n<td colspan='4'>Please confirm your password</td>\n<tr>");
					}
					else 
					{
						$confirm = trim($_REQUEST['confirm']);
						
						if ($passwd != $confirm)
						{
							echo ("<tr>\n<td colspan='4'>Password and confirmation did not match</td>\n<tr>");
						}
						else 
						{				
						
							$sql = 	"UPDATE ".$db_prefix."user_passwd SET "
									."passwd=".$db->qstr(md5($passwd))
									." WHERE userid=".$db->qstr($userid);
								
							$rs = $db->Execute($sql);
		
							if ($rs === false)				
							{
								echo ("<tr>\n<td colspan='4'>Password unchanged - a database error occured while saving your data</td>\n<tr>");
							}
							else 
							{
								echo ("<tr>\n<td colspan='4'>Password changed successfully (<a href='manageusers.php'>go back to user management</a>)</td>\n<tr>");
							}
							
						}
						
					}
					
					
				}
				
					
				
				
			}
			
			
		}
		
		echo ("<tr>\n");		
		
		echo ("<form method='POST' action='".$_SERVER['PHP_SELF']."'>");
			
		echo ("<td>".$userid."</td>");
		
		echo ("<td><input type='text' name='password'></td>");	
		
		echo ("<td><input type='text' name='confirm'></td>");	
		
		echo (	"<td>"
				."<input type='hidden' name='changed' value='1'>\n"
				."<input type='hidden' name='userid' value='".$userid."'>\n"
				."<input type='submit' value='Save'>\n"
				."<a href='manageusers.php'><button type='button' value='Cancel'>Cancel</button></a>"
				."</td>");
		
		echo ("</form>");
					
	
	?>

</table>