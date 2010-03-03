<?php
// this file sets the constant DILPS_INTER_DILPS_IMAGE_REQUEST, if appropriate.
// if this constant is set, the session, authentication and smarty includes will not be included

// after this file is included, $invalidRequestor will be false unless this was an inter-dilps-request that failed validation
$invalidRequestor = false;
if (!empty($_REQUEST['inter-dilps-request'])) {
    include_once($config['includepath'].'db.inc.php');
    include_once($config['includepath'].'remote.inc.php');
    if (interdilpsRequestorAllowed()) {
        define('DILPS_INTER_DILPS_IMAGE_REQUEST', 2);
        $invalidRequestor = false;
    } else {
        $invalidRequestor = true;
    }
} 
?>