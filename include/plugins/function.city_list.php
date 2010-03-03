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
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     function.city_list.php
 * Type:     function
 * Name:     city_list
 * Purpose:  assign the city_list to a template var
 * -------------------------------------------------------------
 */

global $config;
require_once( $config['includepath'].'thesaurusDB.class.php');

function smarty_function_city_list($params, &$smarty)
{
    if (empty($params['var'])) {
        $smarty->trigger_error("assign: missing 'var' parameter");
        return;
    }

    if (empty($params['query'])) {
        $smarty->trigger_error("assign: missing 'query' parameter");
        return;
    }

	global $db, $db_prefix;


	// query the existing locations
	/*$sql = "SELECT DISTINCT city FROM {$db_prefix}meta ORDER BY city ASC";
	$rs = $db->GetArray( $sql );*/
	//debug($_REQUEST, false);
	//debug($params['query']);

	$thesaurus = new thesaurusDB($db, $db_prefix);


	// $frommeta indicates whether or not the data is coming from the _existing_ metadata
	$frommeta = false;

	// search by the citysearch field if given, by the meta-id otherwise
	if (empty($params['query']['citysearch'])) {
	    if (empty($params['query']['id'])) {

	        $searchtype = false;

	    } else {

	        $searchterm = $params['query']['id'];
         	$searchtype = 'metaid';
    	    $frommeta = true;
		}

	 } else {

	     $searchterm = $params['query']['citysearch'];
	     $searchtype = 'location';
	 }

	 $result = array();

	 if ($searchtype != false) {

    	 $rs = $thesaurus->query('locations', $searchterm, $searchtype);
    	 $sql = "queried through thesaurusDB::query('locations', '$searchterm', '$searchtype');";


         if ($frommeta) {
             $params['query']['city'] = $rs[0]['id'];
             $params['query']['cityname'] = $rs[0]['location'];
             $smarty->assign('query', $params['query']);
         }


    	 //make the ids be keys
    	 $result = array();
    	 foreach ($rs as $row) {
    	     $result[$row['id']] = $row;
    	 }


	 } else {

	     $sql = 'no query';
	 }

     $smarty->assign($params['var'], $result);

     $smarty->assign($params['frommeta'], $frommeta);


	 if( !empty($params['sql'])) {
		     $smarty->assign($params['sql'], $sql);
	 }
}
?>
