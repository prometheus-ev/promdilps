<?php

	include( 'config.inc.php' );
	include( 'globals.inc.php' );
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
                        Current Password
                </th>
		<th>
			New Password
		</th>
		<th>
			New Password (Confirm)
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
				echo ("<tr>\n<td colspan='5'>Data unchanged - an empty UserID was passed</td>\n<tr>");
			}
			else 
			{
                                if (empty($_REQUEST['current']))
                                {
                                        echo ("<tr>\n<td colspan='5'>Please enter your current password</td>\n<tr>");
                                }
                                else
                                {
                                        // authenticate user
                                        $sql = "SELECT userid FROM ".$db_prefix."user_passwd "
                                                ."WHERE userid=".$db->qstr($userid)
                                                ." AND passwd=".$db->qstr(md5(trim($_REQUEST['current'])));

                                        $rs = $db->Execute($sql);

                                        if ($rs === false || $rs->RecordCount() != 1)
                                        {
                                                echo ("<tr>\n<td colspan='5'>Authentication failed!</td>\n<tr>");
                                        }
                                        else
                                        {
                				if (empty($_REQUEST['password']))
	                			{
		                			echo ("<tr>\n<td colspan='5'>You may not use empty passwords</td>\n<tr>");
			                	}
				                else 
        				        {
	        				        $passwd = trim($_REQUEST['password']);
		       			
        		        			if (empty($_REQUEST['confirm']))
	        		        		{
		        		        		echo ("<tr>\n<td colspan='5'>Please confirm your password</td>\n<tr>");
			        		        }
        			        		else 
	        			        	{
		        			        	$confirm = trim($_REQUEST['confirm']);
						
			        			        if ($passwd != $confirm)
        				        		{
	        				        		echo ("<tr>\n<td colspan='5'>Password and confirmation did not match</td>\n<tr>");
		        				        }
        		        				else 
	        		        			{				
						
		        		        			$sql = 	"UPDATE ".$db_prefix."user_passwd SET "
			        		        				."passwd=".$db->qstr(md5($passwd))
				        		        			." WHERE userid=".$db->qstr($userid);
								
					        		        $rs = $db->Execute($sql);
		
        						        	if ($rs === false)				
	        						        {
		        						        echo ("<tr>\n<td colspan='5'>Password unchanged - a database error occured while saving your data</td>\n<tr>");
        		        					}
	        		        				else 
		        		        			{
			        		        			echo ("<tr>\n<td colspan='5'>Password changed successfully (<a href='index.php'>go back to login</a>)</td>\n<tr>");
				        		        	}
					        		}
						        }
       						
        					}
	       				
					
	        			}
				
					
				}
				
			}
			
			
		}
		
		echo ("<tr>\n");		
		
		echo ("<form method='POST' action='".$_SERVER['PHP_SELF']."'>");
			
		echo ("<td><input type='text' name='userid' value='".$userid."'></td>");

                echo ("<td><input type='password' name='current'></td>");
		
		echo ("<td><input type='password' name='password'></td>");	
		
		echo ("<td><input type='password' name='confirm'></td>");	
		
		echo (	"<td>"
				."<input type='hidden' name='changed' value='1'>\n"
				."<input type='submit' value='Save'>\n"
				."<a href='index.php'><button type='button' value='Cancel'>Cancel</button></a>"
				."</td>");
		
		echo ("</form>");
					
	
	?>

</table>
