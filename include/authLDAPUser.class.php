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


class authLDAPUser extends authUser
{
	var $uid, $basedn, $host, $port;
	var $ldapver;

	function authLDAPUser($domain, $basedn="", $host="", $port=389)
	{
		authUser::authUser( $domain );

		$this->basedn 	= $basedn;
		$this->host 	= $host;
		$this->port 	= $port;

		// try ldap v3 first
		$this->ldapver = 3;
	}

	function isLoggedIn()
	{
		return (strlen( $this->uid ) > 1);
	}

	function login( $uid, $pwd, $ip=0 )
	{
		// connect to ldap-server
		
		// echo ("Host: ".$this->host." Port: ".$this->port." BaseDN: ".$this->basedn." UID: $uid, PWD: $pwd \n<br>\n");
		
		if( $connect = @ldap_connect($this->host) )
		{
			// if connected to ldap server, check for protocol version

			if ( !(@ldap_set_option($connect, LDAP_OPT_PROTOCOL_VERSION, 3)) )
			{
				// echo "version 2<br>\n";
				$this->ldapver = 2;
			}

			// echo "verification on '$this->host': ";

			// bind to ldap connection
			if( ($bind=@ldap_bind($connect)) == false )
			{
				// print "bind:__FAILED__<br>\n";
				return false;
			}

			// check whether we have an email address or an actual user id

			if ( strpos($uid,'@') > 0)
			{
				$filter = "mail=".$uid;
			}
			else
			{
				$filter = "uid=".$uid;
			}

		   	// search for user
		   	if ( ($res_id = @ldap_search( $connect,
		   								 $this->basedn,
		    	                         $filter))  == false )
			{
		    	// print "failure: search in LDAP-tree failed<br>";
		     	return false;
		   	}

			if (@ldap_count_entries($connect, $res_id) != 1)
			{
				// print "failure: error looking up user $username<br>\n";
				return false;
			}

			if (( $entry_id = @ldap_first_entry($connect, $res_id)) == false)
			{
				// print "failure: entry of searchresult couln't be fetched<br>\n";
				return false;
			}

			if (( $user_dn = @ldap_get_dn($connect, $entry_id)) == false)
			{
				// print "failure: user-dn coulnd't be fetched<br>\n";
				return false;
			}

			// authenticate user
			if (($link_id = @ldap_bind($connect, $user_dn, $pwd)) == false)
			{
				// print "failure: username, password didn't match: $user_dn<br>\n";
				return false;
			}

			// login went fine, user login is ok
			@ldap_close($connect);

			$this->uid = $uid;

			return true;
		}
		else
		{
	   		// no conection to ldap server

	   		echo "no connection to '$ldap_server'<br>\n";
		}

	  	// echo "failed: ".ldap_error($connect)."<BR>\n";

	  	// something went wrong, cleanup and return false
	  	@ldap_close($connect);
	 	return false;

	}

	function logout()
	{
		unset( $this->uid );
	}

	function getData()
	{
		$retval = authUser::getData();

		$retval["uid"] 	= $this->uid;
		$retval["host"] 	= $this->host;
		$retval["port"] 	= $this->port;
		$retval["basedn"] = $this->basedn;

		return $retval;
	}

	function setData( $data )
	{
		authUser::setData( $data );

		$this->uid = $data["uid"];
		$this->host = $data["host"];
		$this->port = $data["port"];
		$this->basedn = $data["basedn"];
	}

	function getInfo()
	{
		return $this->basedn;
	}

	function getUID()
	{
		return $this->uid;
	}
}


?>
