<?php
/* Breaks all of the files in the given directory into smaller pieces.
 * This is necessary, because the PEAR Unserializer class reads everything into memory,
 * and it chokes even with 1 GB of memory.  (Original file sizes: about 270 MB)
 *
 * $piece_sourcedir, $piece_destdir and $piece_destprefix are defined in thesauri_load_config.inc.php
*/
require_once('thesauri_load_config.inc.php');

$subjects_per_file = 10000;
//$subjects_per_file = 100;

//get the list of files
$files = array();
if ($handle = opendir($piece_sourcedir)) {
    $i = 0;
    while (false !== ($file = readdir($handle))) {
        if ($file != '.' && $file != '..') {
            $files[$i]['in'] = "$piece_sourcedir/$file";
            $files[$i]['out'] = "$file";
            $i++;
        }
    }
    closedir($handle);
} else {
    die ("Unable to open directory ($piece_sourcedir) for reading.  Check that the path is correct.\n");
}

if (empty($files)) {
    die ("No files found in directory ($piece_sourcedir).\n");
}

foreach ($files as $file) {
    
    $piececount = 1;
    $subjectend = "</Subject>\n";
    
    $infile = $file['in'];
    $outfile = "$piece_destdir/{$piece_destprefix}_{$piececount}_{$file['out']}";
    
    $handlein = fopen("$infile", "r");
    if ($handlein === false) {
        die ("Unable to open file '$infile' for reading\n");
    }
    
    $handleout = fopen("$outfile", "w");
    if ($handleout === false) {
        die ("Unable to open file '$outfile' for writing\n");
    }
    
    // read first two lines, they'll be used to start all files.
    $xml = fgets($handlein, 8192) or die ("Could not read first line of file!\n");
    $vocab_begin = fgets($handlein, 8192) or die ("Could not read second line of file!\n");
    
    $vocab_end = "</Vocabulary>\n";
    
    
    // write the first two lines to the first file
    fputs($handleout, $xml);
    fputs($handleout, $vocab_begin);
    $subjectcount = 0;
    
    $line = fgets($handlein, 8192);
    
    while ($line) {
        
        fputs($handleout, $line);
        
        if ($line == $subjectend) {
            $subjectcount++;
        }
        
        if ($subjectcount >= $subjects_per_file) {
            
            fputs($handleout, $vocab_end);
            fclose($handleout);
            
            $piececount++;
            $outfile = "$piece_destdir/{$piece_destprefix}_{$piececount}_{$file['out']}";
            $handleout = fopen("$outfile", "w");
            if ($handleout === false) {
                die ("Unable to open file '$outfile' for writing\n");
            }
            fputs($handleout, $xml);
            fputs($handleout, $vocab_begin);
            $subjectcount = 0;
        }
    	
        $line = fgets($handlein, 8192);
    }
    
    //fputs($handleout, $vocab_end);
    fclose($handleout);
}

?>