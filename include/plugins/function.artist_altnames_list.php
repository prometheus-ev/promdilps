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
 * File:     function.artist_altnames_list.php
 * Type:     function
 * Name:     artist_altnames_list
 * Purpose:  assign the artist_altnames_list to a template var
 * Note:     will assign false to the template var if no results are found
 * -------------------------------------------------------------
 */
global $config;
require_once( $config['includepath'].'tools.inc.php' );
require_once( $config['includepath'].'thesaurusDB.class.php');


function smarty_function_artist_altnames_list($params, &$smarty)
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
    
    $searchterm = $query['artist'];
    $searchtype = 'parentid';

    global $db, $db_prefix;
    
    $thesaurus = new thesaurusDB($db, $db_prefix);
    
    $rs = $thesaurus->query('names', $searchterm, $searchtype);
    $sql = "queried through thesaurusDB::query('names', '$searchterm', '$searchtype');";
    
    if (count($rs)) {
        foreach ($rs as $row) {
            array_walk($row, '__stripslashes');
            $result[$row['id']] = $row;
        }
    } else {
        $result = false;   
    }
	 
    $smarty->assign($params['var'], $result);
	 
    if( !empty($params['sql'])) {
         $smarty->assign($params['sql'], $sql);
    }
}
?>
