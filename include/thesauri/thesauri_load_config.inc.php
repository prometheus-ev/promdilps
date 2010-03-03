<?php

/////////////////////////////////////////////////
//  FOR transformer.php
/////////////////////////////////////////////////
// Set $transform_sourcedir to the full path to the directory where the xml files are located.
// The directory should contain only the main xml files (i.e. tgn1.xml, tgn2.xml, etc)
//  and not the accessory files (tgn_merge.xml, tgn_sources.xml, etc).
$transform_sourcedir = '/home/brian/projects/dilps/temp/tgn/tgn_xml_2004/tgn_fix';

// set $transform_destdir to the directory where the transformed files should be written
$transform_destdir = '/home/brian/projects/dilps/temp/tgn/tgn_xml_2004/tgn';

// $transform_destprefix will be prefixed to the destination file name.  
// This can be left blank, if the source and destination directories are different.
$transform_destprefix = '';


/////////////////////////////////////////////////
//  FOR pieces.php
/////////////////////////////////////////////////
// Set $piece_sourcedir to the full path to the directory where the xml files are located.
// The directory should contain only the main xml files (i.e. tgn1.xml, tgn2.xml, etc)
//  and not the accessory files (tgn_merge.xml, tgn_sources.xml, etc).
$piece_sourcedir = '/home/brian/projects/dilps/temp/tgn/tgn_xml_2004/tgn_dev';

// set $piece_destdir to the directory where the transformed files should be written
$piece_destdir = '/home/brian/projects/dilps/temp/tgn/tgn_xml_2004/tgn_dev2';

// $piece_destprefix will be prefixed to the destination file name.  
// This can be left blank, if the source and destination directories are different.
$piece_destprefix = '';


/////////////////////////////////////////////////
//  FOR reload_location_thesaurus.php
/////////////////////////////////////////////////
// Set $load_sourcedir to the full path to the directory where the transformed xml files are located.
// The directory should contain only the main xml files (i.e. tgn1.xml, tgn2.xml, etc)
//  and not the accessory files (tgn_merge.xml, tgn_sources.xml, etc).
// Normally, this will be same as $piece_destdir
$load_sourcedir = $piece_destdir;



?>