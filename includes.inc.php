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
 * dilps includes
 * -------------------------------------------------------------
 * File:     			includes.inc.php
 * Purpose:  	central place to configure all
 *						standard includes and their order
 * -------------------------------------------------------------
 */
 	
	//error_reporting(E_ALL);

/* configuration start */

	// read db-config from file
	$config = array();
	include('config.inc.php');	
	
	// read config-variables from db and connect
	include( $config['includepath'].'db.inc.php' );	
	
	// read the rest of the configuration
	include( 'globals.inc.php' );

	// session management
	if (!defined('DILPS_SOAP_QUERY') && !defined('DILPS_INTER_DILPS_IMAGE_REQUEST')) {
	   include( $config['includepath'].'session.inc.php' );
	   
    	// user management
    	/*
    	include( $config['includepath'].'userList.class.php' );
    	include( $config['includepath'].'authUser.class.php' );
    	include( $config['includepath'].'authStaticUser.class.php' );
    	*/
    	
    	// authentication handling
    	include( $config['includepath'].'auth.inc.php' );
   	
    	// smarty base include
    	include( $config['includepath'].'smarty/Smarty.class.php' );
		
    	// smarty customization
    	include( $config['includepath'].'smarty.inc.php' );
		
}
	
	
	// add some libraries to include path
	
	$lib_include_path = ini_get('include_path');
	
	if(!defined('PATH_SEPARATOR')) {
		define('PATH_SEPARATOR',(preg_match('/WIN/i',PHP_OS) ? ';' : ':'));
	}

    //bk: use the pear classes included with dilps code before any installed in system-default locations
	$new_include_path = 	$config['includepath'].'pear'.PATH_SEPARATOR
                            .$lib_include_path.PATH_SEPARATOR
										.$config['includepath'].'xml'.PATH_SEPARATOR
										.$config['includepath'].'mime'.PATH_SEPARATOR
										.$config['includepath'].'console'.PATH_SEPARATOR
										.$config['includepath'].'system'.PATH_SEPARATOR
										.$config['includepath'].PATH_SEPARATOR
										.$config['dilpsdir'];
										
	ini_set('include_path', $new_include_path);
	
	// we use this nearly everywhere
	
	global $db, $db_prefix, $user;
	
	/* configuration end */	
	
	// this may be useful for debugging purposes
	function print_html($value, $key)
	{
		if (is_array($value))
		{
			array_walk($value,"print_html");
		}
		else 
		{
			echo ($key." => ".$value."\n<br>\n");
		}
		
		return;
	}

?>
