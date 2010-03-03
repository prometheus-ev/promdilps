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
 * accesses the Amazon webservice
 * -------------------------------------------------------------
 * File:     	amazon_image.php
 * Purpose:  	reads the image in the Amazon database via
 *				webservice
 * -------------------------------------------------------------
 */

include( 'globals.inc.php' );

if( !isset( $_REQUEST['ASIN']))
{
 header("Content-type: image/gif\n\n");
 readfile( 'blank.gif' );
 exit;
}

switch( trim(strtolower($_REQUEST['size'])))
{
	case "medium":
	   $pattern = "/<MediumImage><URL>([^<]*)<\\/URL>/";
	   break;
	default: 
	   $pattern = "/<SmallImage><URL>([^<]*)<\\/URL>/";
}

$filename = 'http://webservices.amazon.com/onca/xml?Service=AWSECommerceService&SubscriptionId=1XKHV6RJW6ZE3BKMTYR2&Operation=ItemLookup&ItemId='.$_REQUEST['ASIN'].'&ResponseGroup=Medium';
$xml = file_get_contents(($filename));
if( !preg_match( $pattern, $xml, $matches ))
{
	$xml = file_get_contents("http://webservices.amazon.co.uk/onca/xml?Service=AWSECommerceService&SubscriptionId=1XKHV6RJW6ZE3BKMTYR2&Operation=ItemLookup&ItemId=".trim($_REQUEST['ASIN'])."&ResponseGroup=Medium");
	if( !preg_match( $pattern, $xml, $matches ))
	{
          header("Content-type: image/gif\n\n");
	  readfile('blank.gif');
    	  exit;
	}
}

header( "Content-type: image/jpeg\n\n");
readfile( $matches[1] );

?>