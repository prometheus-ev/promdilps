<?php
/* transformer.php
 * Used to get rid of the special character encoding in the Getty Thesaurus of Geographical Names.
 * For example, the string It$00alia is transformed to Itália
 * @author brian@mediagonal.ch
 * @copyright mediagonal.ch, 
 * @history creation: May, 2005
*/

require_once('thesauri_load_config.inc.php');
require_once('UtfNormal.php');

//$transform_sourcedir, $transform_destdir, and $transform_destprefix are defined in thesauri_load_config.inc.php

// the $translation array is based on the tgn_chars.xml file which comes with TGN
$translation = array(
    '$00'=> array('switch'=>true,  'unicode'=>array(0x0301)),
    '$01' => array('switch'=>true, 'unicode'=>array(0x0304)),
    '$02' => array('switch'=>true, 'unicode'=>array(0x0300)),
    '$03' => array('switch'=>true, 'unicode'=>array(0x0302)),
    '$04' => array('switch'=>true, 'unicode'=>array(0x0308)),
    '$05' => array('switch'=>true, 'unicode'=>array(0x0327)),
    '$06' => array('switch'=>true, 'unicode'=>array(0x0306)),
    '$07' => array('switch'=>true, 'unicode'=>array(0x030C)),
    '$08' => array('switch'=>true, 'unicode'=>array(0x0307)),
    '$09' => array('switch'=>true, 'unicode'=>array(0x0303)),
    '$10' => array('switch'=>true, 'unicode'=>array(0x030A)),
    '$12' => array('switch'=>true, 'unicode'=>array(0x030B)),
    '$13L' => array('switch'=>false, 'unicode'=>array(0x0141)),
    '$13l' => array('switch'=>false, 'unicode'=>array(0x0142)),
    '$14O' => array('switch'=>false, 'unicode'=>array(0x00D8)),
    '$14o' => array('switch'=>false, 'unicode'=>array(0x00F8)),
    '$15' => array('switch'=>true, 'unicode'=>array(0x0323)),
    '$17' => array('switch'=>true, 'unicode'=>array(0x0328)),
    '$18s' => array('switch'=>false, 'unicode'=>array(0x00DF)),
    '$19th' => array('switch'=>false, 'unicode'=>array(0x00FE)),
    '$20TH' => array('switch'=>false, 'unicode'=>array(0x00DE)),
    '$21th' => array('switch'=>false, 'unicode'=>array(0x00F0)),
    '$22' => array('switch'=>true, 'unicode'=>array(0xFE20)),
    '$23' => array('switch'=>true, 'unicode'=>array(0xFE21)),
    '$24' => array('switch'=>true, 'unicode'=>array(0x0302, 0x0301)),
    '$27' => array('switch'=>true, 'unicode'=>array(0x0301, 0x0306)),
    '$28O' => array('switch'=>false, 'unicode'=>array(0x01A0)),
    '$28U' => array('switch'=>false, 'unicode'=>array(0x01AF)),
    '$28o' => array('switch'=>false, 'unicode'=>array(0x01A1)),
    '$28u' => array('switch'=>false, 'unicode'=>array(0x01B0)),
    '$29' => array('switch'=>true, 'unicode'=>array(0x0302, 0x0323)),
    '$30' => array('switch'=>true, 'unicode'=>array(0x0300, 0x0302)),
    '$31' => array('switch'=>true, 'unicode'=>array(0x0300, 0x0306)),
    '$32O' => array('switch'=>true, 'unicode'=>array(0x0300, 0x01A0)),
    '$32U' => array('switch'=>true, 'unicode'=>array(0x0300, 0x01AF)),
    '$32o' => array('switch'=>true, 'unicode'=>array(0x0300, 0x01A1)),
    '$32u' => array('switch'=>true, 'unicode'=>array(0x0300, 0x01B0)),
    '$33' => array('switch'=>true, 'unicode'=>array(0x0309, 0x0302)),
    '$34O' => array('switch'=>true, 'unicode'=>array(0x0302, 0x01A0)),
    '$34U' => array('switch'=>true, 'unicode'=>array(0x0302, 0x01AF)),
    '$34o' => array('switch'=>true, 'unicode'=>array(0x0302, 0x01A1)),
    '$34u' => array('switch'=>true, 'unicode'=>array(0x0302, 0x01B0)),
    '$35O' => array('switch'=>true, 'unicode'=>array(0x0309, 0x01A0)),
    '$35U' => array('switch'=>true, 'unicode'=>array(0x0309, 0x01AF)),
    '$35o' => array('switch'=>true, 'unicode'=>array(0x0309, 0x01A1)),
    '$35u' => array('switch'=>true, 'unicode'=>array(0x0309, 0x01B0)),
    '$36O' => array('switch'=>true, 'unicode'=>array(0x0303, 0x01A0)),
    '$36U' => array('switch'=>true, 'unicode'=>array(0x0303, 0x01AF)),
    '$36o' => array('switch'=>true, 'unicode'=>array(0x0303, 0x01A1)),
    '$36u' => array('switch'=>true, 'unicode'=>array(0x0303, 0x01B0)),
    '$37' => array('switch'=>true, 'unicode'=>array(0x0306, 0x0303)),
    '$38' => array('switch'=>true, 'unicode'=>array(0x0302, 0x0303)),
    '$39' => array('switch'=>true, 'unicode'=>array(0x0306, 0x0323)),
    '$40' => array('switch'=>true, 'unicode'=>array(0x0309, 0x0306)),
    '$41' => array('switch'=>true, 'unicode'=>array(0x0301, 0x0308)),
    '$46' => array('switch'=>true, 'unicode'=>array(0x0332)),
    '$47' => array('switch'=>true, 'unicode'=>array(0x0326)),
    '$48' => array('switch'=>true, 'unicode'=>array(0x031C)),
    '$50' => array('switch'=>true, 'unicode'=>array(0x0309)),
    '$55D' => array('switch'=>false, 'unicode'=>array(0x0110)),
    '$55d' => array('switch'=>false, 'unicode'=>array(0x0111)),
    '$57AE' => array('switch'=>false, 'unicode'=>array(0x00C6)),
    '$57Ae' => array('switch'=>false, 'unicode'=>array(0x00C6)),
    '$58OE' => array('switch'=>false, 'unicode'=>array(0x0152)),
    '$58Oe' => array('switch'=>false, 'unicode'=>array(0x0152)),
    '$59OE' => array('switch'=>false, 'unicode'=>array(0x0306, 0x0152)),
    '$59Oe' => array('switch'=>false, 'unicode'=>array(0x0306, 0x0152)),
    '$60oe' => array('switch'=>false, 'unicode'=>array(0x0306, 0x0153)),
    '$67' => array('switch'=>true, 'unicode'=>array(0x02BF)),
    '$70ae' => array('switch'=>false, 'unicode'=>array(0x00E6)),
    '$71oe' => array('switch'=>false, 'unicode'=>array(0x0153)),
    '$73' => array('switch'=>true, 'unicode'=>array(0x0131)),
    '$91' => array('switch'=>true, 'unicode'=>array(0x02BE)));

function utf8_to_unicode( $str ) {
        
    $unicode = array();        
    $values = array();
    $lookingFor = 1;
    
    for ($i = 0; $i < strlen( $str ); $i++ ) {

        $thisValue = ord( $str[ $i ] );
        
        if ( $thisValue < 128 ) $unicode[] = $thisValue;
        else {
        
            if ( count( $values ) == 0 ) $lookingFor = ( $thisValue < 224 ) ? 2 : 3;
            
            $values[] = $thisValue;
            
            if ( count( $values ) == $lookingFor ) {
        
                $number = ( $lookingFor == 3 ) ?
                    ( ( $values[0] % 16 ) * 4096 ) + ( ( $values[1] % 64 ) * 64 ) + ( $values[2] % 64 ):
                	( ( $values[0] % 32 ) * 64 ) + ( $values[1] % 64 );
                    
                $unicode[] = $number;
                $values = array();
                $lookingFor = 1;
        
            } // if
        
        } // if
        
    } // for

    return $unicode;

} // utf8_to_unicode

function unicode_to_utf8( $str ) {

    $utf8 = '';
    
    foreach( $str as $unicode ) {
    
        if ( $unicode < 128 ) {

            $utf8.= chr( $unicode );
        
        } elseif ( $unicode < 2048 ) {
            
            $utf8.= chr( 192 +  ( ( $unicode - ( $unicode % 64 ) ) / 64 ) );
            $utf8.= chr( 128 + ( $unicode % 64 ) );
                    
        } else {
            
            $utf8.= chr( 224 + ( ( $unicode - ( $unicode % 4096 ) ) / 4096 ) );
            $utf8.= chr( 128 + ( ( ( $unicode % 4096 ) - ( $unicode % 64 ) ) / 64 ) );
            $utf8.= chr( 128 + ( $unicode % 64 ) );
            
        } // if
        
    } // foreach

    return $utf8;

} // unicode_to_utf8    

    

//get the list of files
$files = array();
if ($handle = opendir($transform_sourcedir)) {
    $i = 0;
    while (false !== ($file = readdir($handle))) {
        if ($file != '.' && $file != '..') {
            $files[$i]['in'] = "$transform_sourcedir/$file";
            $files[$i]['out'] = "$transform_destdir/{$transform_destprefix}$file";
            $i++;
        }
    }
    closedir($handle);
} else {
    die ("Unable to open directory ($transform_sourcedir) for reading.  Check that the path is correct.\n");
}

if (empty($files)) {
    die ("No files found in directory ($transform_sourcedir).\n");
}

// transform the files
foreach ($files as $file) {
    $infile = $file['in'];
    $outfile = $file['out'];
    
    $handlein = fopen("$infile", "r");
    if ($handlein === false) {
        die ("Unable to open file '$infile' for reading\n");
    }
    $handleout = fopen("$outfile", "w");
    if ($handleout === false) {
        die ("Unable to open file '$outfile' for writing\n");
    }
    
    while (!feof($handlein)) {
        
        $buffer = fgets($handlein, 8192);
        
        $encoding_replaced = false;
//$i = 0;        
        while ( strpos($buffer, '$') !== false) {
//echo $i++ . ": strpos=". (string)strpos($buffer, '$') ."\nbuffer: $buffer\n";            
            
            $replaced = false;
            foreach ($translation as $symbol=>$character) {
                
            	$sym_pos = strpos($buffer, $symbol);
            	
            	if ($sym_pos !== false) {
            	    $sym_length = strlen($symbol);
            	    
            	    $piece1 = substr($buffer, 0, $sym_pos);
            	    
            	    if ($character['switch']) {
            	        // the character after the special charater needs to come before it
            	        $partnerchar = utf8_encode($buffer[$sym_pos+$sym_length]); 
            	        $piece2 = unicode_to_utf8(array_merge(utf8_to_unicode($partnerchar), $character['unicode']));
            	        $piece3start = $sym_pos+$sym_length + 1;
            	    } else {
             	        $piece2 = unicode_to_utf8($character['unicode']);
             	        $piece3start = $sym_pos+$sym_length;
            	    }
            	    
            	    $piece2 = utf8_decode(UtfNormal::NFKC($piece2));
            	    
            	    // strip out any ? characters, which are characters not existing in ISO-8859-1
            	    $piece2 = str_replace('?', '', $piece2);
            	    
            	    $piece3 = substr($buffer, $piece3start);
            	    
            	    $buffer =  $piece1 . $piece2 . $piece3;
            	    
            	    $replaced = true;
                	continue;
            	}
            }
            
            if (!$replaced) {
                // we've encountered some character that we have no translation for
                echo "unable to find a translation to transform this buffer, the untranslatable code will be stripped out:\n$buffer\n";
                $pieces = preg_split('/\$\d*/', $buffer, 2);
                $buffer = implode('', $pieces);
            }
        }
        
        if (!$encoding_replaced) {
            if (strpos($buffer, 'encoding="US-ASCII"') !== false) {
                $buffer = str_replace('encoding="US-ASCII"', 'encoding="ISO-8859-1"', $buffer);
                $encoding_replaced = true;
            }
        }
        
        fputs($handleout, $buffer);
        
    }
    
    fclose($handlein);
    fclose($handleout);
}

    

?>