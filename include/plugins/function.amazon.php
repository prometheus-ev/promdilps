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
 * File:     function.amazon.php
 * Type:     function
 * Name:     amazon
 * Purpose:  read data from the amazon webservice and assign
 *			 it to a template variable
 * -------------------------------------------------------------
 */
global $config;

require_once( $config['includepath'].'tools.inc.php' );
require_once( $config['includepath'].'xml2array.class.php' );

function smarty_function_amazon($params, &$smarty)
{
  
    if (empty($params['var'])) {
        $smarty->trigger_error("assign: missing 'var' parameter");
        return;
    }
    if (empty($params['asin'])) {
        $smarty->trigger_error("assign: missing 'asin' parameter");
        return;
    }
 	$wsdl_url = 
	  'http://webservices.amazon.com/AWSECommerceService/DE/AWSECommerceService.wsdl';
	$wsdl_url = 
	  'http://soap.amazon.com/schemas3/AmazonWebServices.wsdl';	  
	$WSDL     = new SOAP_WSDL($wsdl_url); 
	$client   = $WSDL->getProxy(); 
	$p   = array(
	    'ItemId'       => trim(str_replace( '-', '', $params['asin'] )),
	    'IdType'	   => 'ASIN',
	    'tag'          => 'wwwdilpsnet-21',
	    'devtag'       => '1XKHV6RJW6ZE3BKMTYR2'
	);	

    $book    = $client->ItemLookup($p);
    print_r( $book );
    $smarty->assign( $params['var'], $book );
}
?>