<?php
/*  to load locations into db from thesaurus file.
 * NOTE: backup and truncate the location table before running this script
 * NOTE: after running this script, run update_meta_table_after_location_reload.php
*/

//$load_sourcedir is defined in thesauri_load_config.inc.php

require_once('../../globals.inc.php');
include_once( $config['includepath'].'adodb/adodb.inc.php' );
include_once( $config['includepath'].'thesauri/thesauri_load_config.inc.php' );

$db = NewADOConnection( "mysql" );
$res = $db->PConnect( $db_host, $db_user, $db_pwd, $db_db );
$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;

if( !$res )
{
    echo 'no connection';
    exit(1);
}

if (isset($argv[1])) {
    $load_sourcefile = $argv[1];
    if (!is_readable($load_sourcefile)) {
        echo ("file '$load_sourcefile' does not exist or could not be read");
        exit(1);
    }
} else {
    echo ('must specify a source file');
    exit(1);
}


$thesaurus = new thesaurusDB($db, $db_prefix);
//$thesaurus->loadDB_locations($load_sourcedir); 
$result = $thesaurus->loadDB_locations($load_sourcefile); 

exit($result);

?>
