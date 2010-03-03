<?php
//
// REMOTE-INFO FUNCTIONS
//

// returns a single row containing the collection info, if it exists and is a remote collection.  false otherwise
function get_remote_collection_info($collectionid) {
    global $db, $db_prefix;
    $sql = "select * from {$db_prefix}collection where collectionid = $collectionid and host != 'local' and soap_url != ''";
    if ($collection = $db->GetRow($sql)) {
        return $collection;
    } else {
        return false;
    }
}

// calls a remote soap method with the given params.  if $toArray is true, the results will be converted to an array before being returned
function queryRemoteServer($url, $method, $params, $toArray = true) {
    global $config;
    include_once('SOAP/Client.php');
    $client = new SOAP_Client($url);
    if (!$config['utf8']) {
        $params = utf8_encode_recursive($params);
    }
    $response = $client->call($method, $params ,array('namespace'=> 'urn:DILPSQuery'));
    if (is_a($response, 'SOAP_Fault')) {
        $fault = $response->getFault();
        $response = new stdClass();
        $err = new stdClass();
        $err->error = var_export($fault, true);
        $response->result = $err;
    }
    if ($toArray) {
        $response = _stdclass2array($response);
        if (!$config['utf8']) {
            $response = utf8_decode_recursive($response);
        }
        $result = $response['result'];
    } else {
       $result = $response->result;
    }
    return $result;
}


/**
 * Checks whether the requesting ip is a known dilps system and is allowed access to this system
 *
 * @return boolean
 */
function interdilpsRequestorAllowed() {
    global $config;
    require_once("{$config['includepath']}db.inc.php");
    global $db, $db_prefix;
    $ip = getIP();
    $allowed = false;
    $sql = "select access from {$db_prefix}interdilps_hosts where ip = ".$db->qstr($ip)." and access > 0";
    
    if ($access = $db->GetOne($sql)) {
        $allowed = true;
    }
    return $allowed;
}

/**
 * get the best-guess ip address of the request
 *
 * @return string
 */
function getIP() {
    if (getenv("HTTP_CLIENT_IP")) 
        $ip = getenv("HTTP_CLIENT_IP");
    else if(getenv("HTTP_X_FORWARDED_FOR")) 
        $ip = getenv("HTTP_X_FORWARDED_FOR");
    else if(getenv("REMOTE_ADDR")) 
        $ip = getenv("REMOTE_ADDR");
    else $ip = "UNKNOWN";

    return $ip;
} 	

?>