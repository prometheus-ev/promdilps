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
 * File:     function.artist_list.php
 * Type:     function
 * Name:     artist_list
 * Purpose:  assign the artist_list to a template var
 * -------------------------------------------------------------
 */
global $config;
require_once( $config['includepath'].'tools.inc.php' );
require_once( $config['includepath'].'thesaurusDB.class.php');

function smarty_function_artist_list($params, &$smarty)
{
    if (empty($params['var'])) {
        $smarty->trigger_error("assign: missing 'var' parameter");
        return;
    }

    if (empty($params['query'])) {
        $smarty->trigger_error("assign: missing 'var' parameter");
        return;
    } else {
        $query = $params['query'];
    }

	//debug($query);
	global $db, $db_prefix;

    $thesaurus = new thesaurusDB($db, $db_prefix);


	 // $frommeta indicates whether or not the data is coming from the _existing_ metadata
	 $frommeta = false;


	 // search for artists by the artistsearch field if given or by the meta-id otherwise
	 if (empty($query['searchartist'])) {
	     if (empty($query['id'])) {

	         $searchtype = false;

	     } else {

	         extractID( $query['id'], $collectionid, $imageid );
	         $searchterm = "$collectionid:$imageid";
    	     $searchtype = 'metaid';
    	     $frommeta = true;
	     }

	 } else {

	     $searchterm = $query['searchartist'];
	     $searchtype = 'name';
	 }

	 $result = array();

	 if ($searchtype != false) {

    	 $rs = $thesaurus->query('names', $searchterm, $searchtype);
    	 $sql = "queried through thesaurusDB::query('names', '$searchterm', '$searchtype');";
         /*if ($frommeta) {
             $params['query']['name1id'] = $rs[0]['id'];
             $params['query']['name1text'] = $rs[0]['name'];
             $params['query']['name1id'] = $rs[0]['id'];
             $params['query']['name1text'] = $rs[0]['name'];
             $smarty->assign('query', $params['query']);
         }*/


    	 //make the ids be keys, and strip slashes out

    	 foreach ($rs as $row) {
    	     array_walk($row, '__stripslashes');
    	     $result[$row['id']] = $row;
    	 }

	 } else {

	     $sql = 'no query';
	 }



	 //debug($result);







	$smarty->assign($params['var'], $result);

	 if( !empty($params['sql'])) {
		     $smarty->assign($params['sql'], $sql);
	 }
}
?>
