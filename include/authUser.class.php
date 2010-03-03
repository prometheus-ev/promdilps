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


/**
 * Basisklassen zur Benutzerauthentisierung
 * @package Authentication
 */


class authUser
{
   var $domain;

   function authUser( $domain )
   {
      $this->domain = $domain;
   }

   function isLoggedIn()
   {
      die( "authUser::isLoggedIn() - pure virtual function called" );
   }

   function login($userid,$password,$ip=0)
   {
      die( "authUser::login() - pure virtual function called" );
   }

   function logout()
   {
      die( "authUser::logout() - pure virtual function called" );
   }

   function getData()
   {
      return array( "domain" => $this->domain,
                    "classname" => get_class( $this ) );
   }

   function setData( $data )
   {
      $this->domain = $data["domain"];
   }

   function getGroups()
   {
      die( "authUser::getGroups() - pure virtual function called" );
   }

   function getInfo()
   {
      die( "authUser::getInfo() - pure virtual function called" );
   }

   function getDomain()
   {
      return $this->domain;
   }

   function isInGroup( $group )
   {
      return in_array( $group, $this->getGroups());
   }

   function getUID()
   {
      die( "authUser::getUID() - pure virtual function called" );
   }

}

?>
