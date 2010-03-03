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

class authStaticUser extends authUser
{
	var $uid;
	var $users;

   function authStaticUser($domain)
   {
		global $db, $db_prefix;
		
		$sql 	= 	"SELECT a.userid, passwd FROM ".$db_prefix."user_auth as a, ".$db_prefix."user_passwd as p WHERE "
					."a.userid = p.userid AND a.authtype = 'static' AND active > 0";
					
		$rs 	= $db->Execute($sql);
		
		if (!$rs)
		{
			die("Error reading users from database\n<br>\n");
		}
		
		while (!$rs->EOF)
		{
			$this->users[$rs->fields['userid']] = array('password' => $rs->fields['passwd'], 'groups' => array());
			
			$rs->MoveNext();
		}		
   	
      	authUser::authUser( $domain );      	
   }

   function isLoggedIn()
   {
      return (strlen( $this->uid ) > 1);
   }

   function login( $uid, $pwd, $ip=0 )
   {
		if( !array_key_exists( $uid, $this->users )) return false;
		if( $this->users[$uid]['password'] != md5($pwd) ) return false;
		$this->uid = $uid;
		sess_log( LOG_LOGIN, 0, "uid=$uid", 1 );
		return true;
   }

   function logout()
   {
      unset( $this->uid );
   }

   function getData()
   {
      $retval = authUser::getData();
      $retval["uid"] = $this->uid;
      return $retval;
   }

   function setData( $data )
   {
      authUser::setData( $data );
      $this->uid = $data["uid"];
   }
   
   function getUID()
   {
      return $this->uid;
   }
}


?>
