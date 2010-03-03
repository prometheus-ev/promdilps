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

 /**
  * Liste aller Benutzerlogins
  * @author Juergen Enge <juergen@info-age.net>
  * @copyright 2002 Juergen Enge
  */
class userList
{
   /**
    * Benutzerliste
    * @var array $liste
    */
   var $liste;

   /**
    * Constructor
    */
   function userList()
   {
      $this->liste = array();
   }

   /**
    * Benutzer hinzufuegen
    * @param class $user benutzer
    */
   function add( $user )
   {
      $this->liste[$user->getDomain()] = $user;
   }

   /**
    * pruefen, ob der Benutzer in einer bestimmten Gruppe ist
    * @param string $group Gruppenname
    * @param string $domain Domainname
    */
   function isInGroup( $domain, $group )
   {
      if( !isset( $this->liste[$domain] )) return false;
      return $this->liste[$domain]->isInGroup( $group );
   }

   /**
    * alle Benutzer ein ein array packen
    */
   function getData()
   {
      $retval = array();
      reset( $this->liste );
      while( list( $domain, $value ) = each( $this->liste ))
      {
         $retval[$domain] = $value->getData();
      }
      return $retval;
   }

   /**
    * die Daten wiederherstellen
    * @param array $data
    */
   function setData( $data )
   {
      if( !is_array( $data )) return;
      reset( $data );
      while( list( $domain, $value ) = each( $data ))
      {
         if( !isset( $value["classname"] )) continue;
         $this->liste[$domain] = new $value["classname"]($domain);
		 $this->liste[$domain]->setData( $value );
      }
   }

   /**
    * liefert alle enthaltenen Domains
    */
   function getDomains()
   {
      reset( $this->liste );
      return array_keys( $this->liste );
   }

   /**
    * Benutzer ueberall abmelden
    */
   function logout()
   {
      while( list( $domain, $value ) = each( $this->liste ))
         $value->logout();
   }

   /**
    * testen, ob Benutzer angemeldet ist
    */
	function isLoggedIn()
	{
		reset( $this->liste );
		
		while( list( $domain, $value ) = each( $this->liste ))
		{
			if( $value->isLoggedIn())
			{
				return true;
			}
		}
		return false;
	}

   /**
    * Benutzer an bestimmter Domain anmelden
    * @param string $domain Domainname
    * @param string $uid Bentzerkennung
    * @param string $pwd Passwort
    */
	function login( $domain, $uid, $pwd )
	{		
		if( !isset( $this->liste[$domain] ))
		{
			return false;
		}		
		
		return ($this->liste[$domain]->login( $uid, $pwd ));
	}

   /**
    * Benutzerkennung auslesen
    * @param string $domain Domainname
    */
   function getUID( $domain )
   {
      if( !isset( $this->liste[$domain] )) return "";
      return $this->liste[$domain]->getUID();
   }
}

?>
