#!/bin/bash

# call php script to load location data into database  (and (optionally) delete the source files after load)

deleteFilesAfterSuccessfulLoad=1

sourcedir="/home/brian/projects/dilps/temp/tgn/tgn_xml_2004/tgn_dev2"

phpscriptdir="/home/brian/projects/dilps/dilps/include/thesauri"
phpscriptname="reload_location_thesaurus.php"
phpcommand="/usr/bin/php $phpscriptdir/$phpscriptname"

cd "$phpscriptdir"
for filename in $sourcedir/*.xml
do
	echo "executing: $phpcommand $filename"
	$phpcommand "$filename"
	if [ $? -ne 0 ] ; then
	   echo "exiting after php script returned non-zero value"
	   exit
	else
    	if [ $deleteFilesAfterSuccessfulLoad -eq 1 ] ; then
    	   rm -f "$filename"
    	fi
	fi
done
echo "finished!"

