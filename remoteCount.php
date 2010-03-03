<?php

/** this file is used to query the count of matching images 
 *  on remote dilps collections
 *  It is called via ajax from the html page.  This framework allows for partial results to be seen,
 *  even if some of the remote collections are temporarily down.
 * 
*/
define('DILPS_SOAP_QUERY', 1);
require_once('./config.inc.php');
require_once("{$config['includepath']}db.inc.php");
require_once("{$config['includepath']}session.inc.php");
require_once("{$config['includepath']}tools.inc.php");
require_once("{$config['includepath']}remote.inc.php");
include_once('SOAP/Client.php');

global $db, $db_prefix;
// get querystruct from session
$queryid = empty($_REQUEST['queryid']) ? 0 : $_REQUEST['queryid'];
if (!isset($_SESSION['queries'][$queryid])) {
    die ('stored query not found');
}
$querystruct = $_SESSION['queries'][$queryid];

// get collection & build url
$collectionid = empty($_REQUEST['collectionid']) ? 0 : $_REQUEST['collectionid'];
$sql = "select * from {$db_prefix}collection where collectionid =".$db->qstr($collectionid);
if (!$collection = $db->GetRow($sql)) {
    die ('collection not found');
}
$url = "http://{$collection['host']}/{$collection['soap_url']}";
$params = array('querystruct'=>$querystruct);

$response = queryRemoteServer($url, 'queryCount', $params, false);
if (isset($response->error)) {
    die ("(soap error: {$response->error})");
}
if (!isset($response->count)) {
    die ('?  (error: unexpected response)');
}
echo "{$response->count}";
    
?>
