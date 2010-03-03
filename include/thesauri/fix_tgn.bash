#!/bin/bash

# change $e to $00e 
sourcedir="/home/brian/projects/dilps/temp/tgn/tgn_xml_2004/tgn_before"
destdir="/home/brian/projects/dilps/temp/tgn/tgn_xml_2004/tgn_fix"
destprefix=""
search1="\$e"
replace1="\$00e"
sedexpression1="s/\\$search1/\\$replace1/g"
# to replace several things at a time, use a line like the below:
#sedcommand="sed -e $sedexpression1 -e $sedexpression2 -e $sedexpression3"
sedcommand="sed -e $sedexpression1"
cd "$sourcedir"
pwd
for filename in *.xml
do
    echo "processing $filename"
	echo "$sedcommand $filename >$destdir/$destprefix$filename"
    $sedcommand $filename >$destdir/$destprefix$filename
done

