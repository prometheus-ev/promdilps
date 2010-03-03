<?php
/*  to update meta table after running reload_location_thesaurus.php
 * NOTE: backup the meta table before running this script
*/
require_once('../../globals.inc.php');
include_once( $config['includepath'].'adodb/adodb.inc.php' );

$db = NewADOConnection( "mysql" );
$res = $db->PConnect( $db_host, $db_user, $db_pwd, $db_db );
$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;

if( !$res )
{
    die ('no connection');
}

$thesaurus = new thesaurusDB($db, $db_prefix);
$thesaurus->_update_records_from_location_thesaurus(); 
echo "Finished!\n";
?>
