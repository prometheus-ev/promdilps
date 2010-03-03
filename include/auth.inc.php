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


include_once( 'config.inc.php');
include_once( 'globals.inc.php');
include( $config['includepath'].'adodb/adodb.inc.php' );

// fix problems with user management
include_once( $config['includepath'].'userList.class.php');
include_once( $config['includepath'].'authUser.class.php');
include_once( $config['includepath'].'authIMAPUser.class.php');
include_once( $config['includepath'].'authLDAPUser.class.php');
include_once( $config['includepath'].'authStaticUser.class.php');

global $db, $config;

// $db->debug = true;



//error_reporting("E_ALL");

if( isset( $_REQUEST['auth'] ))
{
	$auth = $_REQUEST['auth'];
}
else 
{
	$auth = array();
	
}

if( isset( $_REQUEST['logout'] ))
{
	if ($_REQUEST['logout'] == 1)
	{
		killSession();
	}
}

/*
echo ("Session:");
print_r($_SESSION);
echo ("\n<br>\n");

echo ("Request:");
print_r($_REQUEST);
echo ("\n<br>\n");
*/

$logins = new userList();

// $user = new $config['userClass']($config['authdomain'] );

// get the authentication method to use

if (isset( $_SESSION["authtype"]))
{
	$authtype = $_SESSION["authtype"];	
}	
else 
{
	$authtype = "";
}

if (isset( $_SESSION["permissions"]))
{
	$permissions = $_SESSION["permissions"];	
}	
else 
{
	$permissions = array();
}

if (!empty($auth['uid']))
{
	$sql 	= 	"SELECT * FROM ".$db_prefix."user_auth WHERE "
				."active > 0 AND userid="
				.$db->qstr($auth['uid']);
				
	$rs = $db->Execute($sql);
	
	if ($rs->RecordCount() == 1)
	{
		// found a rule for the specific user
		
		$authtype = $rs->fields['authtype'];
		
		// we assing user-specific permissions
		
		$permissions['admin'] = $rs->fields['admin'];
		$permissions['editor'] = $rs->fields['editor'];
		$permissions['addimages'] = $rs->fields['addimages'];
		$permissions['usegroups'] = $rs->fields['usegroups'];
		$permissions['usefolders'] = $rs->fields['usefolders'];
	}
	else 
	{	
		if ( strpos($auth['uid'],'@') > 0)
		{
			// if the user gave us a domain in the username, look for a corresponding rule	
			$domain = substr($auth['uid'],strpos($auth['uid'],'@')+1);
		}
		else 
		{
			// use the default domain
			$domain = $config["authdomain"];
		}
		
		$sql	= 	"SELECT * FROM ".$db_prefix."user_auth WHERE "
					."active > 0 AND userid="
					.$db->qstr('@'.$domain);
				
		$rs		= $db->Execute($sql);
		
		if ($rs->RecordCount() == 1)
		{	
			// found a rule for the domain
		
			$authtype = $rs->fields['authtype'];
			
			// we assign domain-specific permissions
			
			$permissions['admin'] = $rs->fields['admin'];
			$permissions['editor'] = $rs->fields['editor'];
			$permissions['addimages'] = $rs->fields['addimages'];
			$permissions['usegroups'] = $rs->fields['usegroups'];
			$permissions['usefolders'] = $rs->fields['usefolders'];
		}	
		
	}	
}

switch ($authtype)
{
	case 'static':
		// static users - we put the default domain here, but actually do not use it
		$user = new authStaticUser($config['authdomain']);
		break;
	case 'ldap':		
		// since we can only use one ldap-server, use the default domain, the default basedn and the configured
		// ldap server.
		// we do not use nontstandard ports		
		echo ("ldap user!\n<br>\n");
		
		$user = new authLDAPUser($config['authdomain'], $config['basedn'], $config['ldapserver'] );			
		break;
	case 'imap':
		// since we can only use one imap-server the default mailserver and ports are used.
		// ports and usage of ssl can be specified via config file
		
		// check whether we have an email address or an actual user id
		
		if (isset($auth['uid']))
		{
			if ( strpos($auth['uid'],'@') > 0){
				// complete email address, 
				$authdomain = substr($auth['uid'],strpos($auth['uid'],'@')+1);
			} else {
				// only userid given, use default domain (imap users will not have 'static' as their domain)
				$authdomain = $config['authdomain'];
			}
		}
		else 
		{
			$authdomain = $config['authdomain'];
		}
		
		$user = new authIMAPUser($authdomain,$config['mailserver'],$config['mailport'],$config['mailssl']);
		
		break;
	default:
		// nothing to do, authentication method not valid, so we assign invalid
		$authtype = "invalid";
}



// echo ("Authtype: ".$authtype."\n<br>\n");

$valid_login = false;

if ($authtype != 'invalid')
{	
	$logins->add( $user );
	
	if( isset( $_SESSION["login"] ))
	{
		// echo "Session - ";
		$logins->setData( $_SESSION["login" ] );
	}
	
	// $is_admin = false;
	
	if( !$logins->isLoggedIn())
	{
		// echo "not logged in - ";
	
		if( isset( $auth['domain'] ) && strlen( $auth['domain'] ) > 0 
			&& isset( $auth['uid'] ) && strlen( $auth['uid'] ) > 0 
			&& isset( $auth['pwd'] ) && strlen( $auth['pwd'] ) > 0 )
		{
			if( $logins->login( $auth['domain'], $auth['uid'], $auth['pwd'] ))
	      	{
	        	$_login = $logins->getUID( $auth['domain'] );
	        	
	         	// echo "login ok - ";
	         	
	         	$_SESSION["login"] = $logins->getData();
	         	$_SESSION["authtype"] = $authtype;
	         	$_SESSION["permissions"] = $permissions;
	            $valid_login = true;
	      	}
	      	else
	      	{
	        	// echo "invalid login - ";
	        	
	        	$valid_login = false;
	        	$txt = "{$auth['domain']}: {$auth['uid']}: falsches Passwort oder techn. Problem";
	      	}
		}
	}
	else
	{
		$_login = $logins->getUID( $config['authdomain'] );
		
		// echo "logged in - $_login";
		
		$valid_login = true;
	}
}
else 
{
	$valid_login = false;	        
	// $txt = "{$auth['domain']}: {$auth['uid']}: falsches Passwort oder techn. Problem";
}

?>