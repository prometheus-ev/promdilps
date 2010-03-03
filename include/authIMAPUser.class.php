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


class authIMAPUser extends authUser
{
	// standard is imap, works with pop too

	var $uid, $host, $port, $ssl;

	function authIMAPUser($domain, $host="localhost", $port=143, $ssl=false)
	{
		authUser::authUser( $domain );

		
		$this->host 	= $host;
		$this->port 	= $port;
		$this->ssl		= ($ssl == 'true' || $ssl === true) ? true : false;
	}

	function isLoggedIn()
	{
		return (strlen( $this->uid ) > 1);
	}

	function login( $uid, $pwd, $ip=0 )
	{
		// connect via imap/pop

		$hoststring = '{'.$this->host.':'.$this->port.($this->ssl ? '/ssl/novalidate-cert':'').'}';
		
		if ( strpos($uid,'@') > 0){
			// complete email address - strip domain part
			$userid		= substr($uid,0,strpos($uid,'@'));
		} else {
			// only userid given
			$userid		= $uid;
		}
		
		// echo ("UserID: $userid\n<br>\n");

		$ms = @imap_open($hoststring,$userid,$pwd);

		if ($ms)
		{
			// connection successful
			@imap_close($ms);
			$this->uid = $uid;

			return true;
		}
		else
		{
			// connection failed
			@imap_close($ms);
			return false;
		}
	}

	function logout()
	{
		unset( $this->uid );
	}

	function getData()
	{
		$retval = authUser::getData();

		$retval["uid"] 	= $this->uid;
		$retval["host"] = $this->host;
		$retval["port"] = $this->port;
		$retval["ssl"] 	= $this->ssl;

		return $retval;
	}

	function setData( $data )
	{
		authUser::setData( $data );

		$this->uid 	= $data["uid"];
		$this->host = $data["host"];
		$this->port = $data["port"];
		$this->ssl 	= $data["ssl"];
	}

	function getUID()
	{
		return $this->uid;
	}
}



?>