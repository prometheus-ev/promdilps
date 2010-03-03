<?
// handles remote queries with soap

/*
    *** REQUIREMENTS FOR USING the version of PEAR:SOAP that this code uses: ***
    SOAP-0.9.3
    HTTP_Request >= 1.3.0
    Net_URL >= 1.0.14
*/
if (!defined('DILPS_SOAP_QUERY')) define('DILPS_SOAP_QUERY', 1);
error_reporting(0);  // suppress warnings related to unfound (and unnecessary) soap include files
require_once('./config.inc.php');
require_once("{$config['includepath']}dilpsSoapServer.class.php");
require_once('SOAP/Server.php');

// Create the SOAP server
$soapServer = new SOAP_Server();
$dilpsSoap = new DilpsSoapServer(!$config['utf8']);
$soapServer->addObjectMap($dilpsSoap, 'urn:DILPSQuery');

// Service the request
$status = $soapServer->service($GLOBALS['HTTP_RAW_POST_DATA']);
if (is_a($status, 'SOAP_Fault')) {
    $fault = $response->getFault();
    //mail('brian@mediagonal.ch', 'soapserverFault', var_export($fault));
}
?>
