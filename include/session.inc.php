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
 * Session handling
 * -------------------------------------------------------------
 * File:     session.inc.php
 * Purpose:  session handling for the DILPS system
 *			 (based on GET/POST and db)
 * -------------------------------------------------------------
 */

require_once($config['includepath'].'adodb/adodb.inc.php');
include( $config['includepath'].'db.inc.php' );

ini_set( 'session.use_cookies' , 0 );

register_shutdown_function('session_write_close');

define( "SESS_MAX_LIFETIME", 36000 );
define( "SESS_MAX_INACTIVE", 1800 );

define( "LOG_LOGIN", 1 );
define( "LOG_LOGOUT", 2 );
define( "LOG_GENERIC", 4 );

function sess_open( $save_path, $session_name )
{		
	// session handling globals
	global $sess_save_path, $sess_session_name, $sess_invalid;

	$sess_invalid = false;

	// assign global data
	$sess_save_path = $save_path;
	$sess_session_name = $session_name;

	return true;
}

function sess_close()
{
	return true;
}

function sess_read( $id )
{
	// session handling globals
	global $sess_save_path, $sess_session_name, $sess_invalid;

	// configuration data
	global $db, $db_prefix;

	// read from database

	$sql = 	"SELECT session_data, end-NOW() as lifetime, active FROM ".$db_prefix."session"
			." WHERE sessionid=".($db->qstr($id))."";

	$rs = $db->Execute ($sql);

	// error executing database query
	if(!($rs))
	{
		return false;
	}

	// session does not exist
	if($rs->RecordCount() == 0)
	{
		return "";
	}

	$data 		= $rs->fields["session_data"];
	$lifetime	= $rs->fields["lifetime"];
	$active		= $rs->fields["active"];

	// cleanup
	$rs->Close();

	if( $lifetime <= 0 || $active != 1 )
	{
		$sess_invalid = true;
	 	return "";
	}

	return stripslashes($data);
}

function sess_write( $id, $sess_data )
{
	// session handling globals
	global $sess_save_path, $sess_session_name, $sess_invalid;

	// configuration data
	global $db, $db_prefix;

	if( $sess_invalid )
	{
		return false;
	}

	$sql =  "UPDATE ".$db_prefix."session SET counter=counter+1,lastaccess=NOW(),session_data="
			.$db->qstr($sess_data)." WHERE sessionid=".$db->qstr($id);

	$rs = $db->Execute ($sql);

	// error executing database query
	if(!($rs))
	{
		return false;
	}

	// session does not exist in database, creat it
	if($db->Affected_Rows() != 1)
	{
		$sql = 	"INSERT INTO ".$db_prefix."session (sessionid,start,end,lastaccess,ip,counter,session_data)"
				."VALUES(".$db->qstr($id).",NOW(),NOW() + INTERVAL ".SESS_MAX_LIFETIME
				." SECOND,NOW(),INET_ATON(".$db->qstr($_SERVER["REMOTE_ADDR"])."),1,"
				.$db->qstr( $sess_data ).")";

		$rs = $db->Execute ($sql);

		// error executing database query
		if(!($rs))
		{
			return false;
		}

		// just in case something strange went wron
		if( $db->Affected_Rows() != 1)
		{
			return false;
		}

		return true;
	}
	else
	{
		return true;
	}
}

function sess_destroy ($id)
{
	// session handling globals
	global $sess_save_path, $sess_session_name, $sess_invalid;

	// configuration data
	global $db, $db_prefix;

	$sql = "UPDATE ".$db_prefix."session SET lastaccess=NOW(),active=0 WHERE sessionid="
			.$db->qstr($id);

	$rs = $db->Execute($sql);

	// error executing database query
	if( !$rs)
	{
		return false;
	}

	return true;
}


function sess_gc ($maxlifetime)
{
	return true;
}

function sess_log( $type, $subtype, $descr, $status )
{
	// session handling globals
	global $sess_save_path, $sess_session_name, $sess_invalid;

	// configuration data
	global $db, $db_prefix;

	if( $sess_invalid )
		$sessionid = "invalid";
	else
		$sessionid = session_id();

	$sql = 	"INSERT INTO ".$db_prefix."logfile (sessionid,logdate,type,subtype,descr,status) "
			."VALUES(".$db->qstr($sessionid).",NOW(),".$type.",".$subtype
			.",".$db->qstr($descr).",".$status.")";

	$rs = $db->Execute($sql);

	// error executing database query
	if( !$rs)
	{
		return false;
	}

	return true;
}

function killSession()
{
	@session_unset();
	@session_destroy();
}

session_set_save_handler( "sess_open", "sess_close", "sess_read", "sess_write", "sess_destroy", "sess_gc" );
session_start();

if( $sess_invalid )
{
	session_unset();
	session_destroy();
}

if( !isset( $_SESSION["counter"] ))
{
	$_SESSION["counter"] = 0;
}
else
{
	$_SESSION["counter"]++;
}

?>
