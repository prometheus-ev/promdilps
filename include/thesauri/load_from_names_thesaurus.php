<?php
// to load names into db from thesaurus file:    
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
$thesaurus->loadDB_names('/home/brian/projects/dilps/test/pknd_0.1.xml'); 
?>
