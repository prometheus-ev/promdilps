<?php
function soundex2( $sIn ) {
   // Si il n'y a pas de mot, on sort immédiatement
   if ( $sIn === '' ) return '    ';
   // On met tout en minuscule
   $sIn = strtoupper( $sIn );
   // On supprime les accents
   $sIn = strtr( $sIn, 'ÂÄÀÇÈÉÊË&#338;ÎÏÔÖÙÛÜß', 'AAASEEEEEIIOOUUUS' );
   // On supprime tout ce qui n'est pas une lettre
   $sIn = preg_replace( '`[^A-Z]`', '', $sIn );
   // Si la chaîne ne fait qu'un seul caractère, on sort avec.
   if ( strlen( $sIn ) === 1 ) return $sIn . '   ';
   // on remplace les consonnances primaires
   $convIn = array( 'GUI', 'GUE', 'GA', 'GO', 'GU', 'CA', 'CO', 'CU',
'Q', 'CC', 'CK' );
   $convOut = array( 'KI', 'KE', 'KA', 'KO', 'K', 'KA', 'KO', 'KU', 'K',
'K', 'K' );
   $sIn = str_replace( $convIn, $convOut, $sIn );
   // on remplace les voyelles sauf le Y et sauf la première par A
   $sIn = preg_replace( '`(?<!^)[EIOU]`', 'A', $sIn );
   // on remplace les préfixes puis on conserve la première lettre
   // et on fait les remplacements complémentaires
   $convIn = array( '`^KN`', '`^(PH|PF)`', '`^MAC`', '`^SCH`', '`^ASA`',
'`(?<!^)KN`', '`(?<!^)(PH|PF)`', '`(?<!^)MAC`', '`(?<!^)SCH`',
'`(?<!^)ASA`' );
   $convOut = array( 'NN', 'FF', 'MCC', 'SSS', 'AZA', 'NN', 'FF', 'MCC',
'SSS', 'AZA' );
   $sIn = preg_replace( $convIn, $convOut, $sIn );
   // suppression des H sauf CH ou SH
   $sIn = preg_replace( '`(?<![CS])H`', '', $sIn );
   // suppression des Y sauf précédés d'un A
   $sIn = preg_replace( '`(?<!A)Y`', '', $sIn );
   // on supprime les terminaisons A, T, D, S
   $sIn = preg_replace( '`[ATDS]$`', '', $sIn );
   // suppression de tous les A sauf en tête
   $sIn = preg_replace( '`(?!^)A`', '', $sIn );
   // on supprime les lettres répétitives
   $sIn = preg_replace( '`(.)\1`', '$1', $sIn );
   // on ne retient que 4 caractères ou on complète avec des blancs
   return substr( $sIn . '    ', 0, 4);
}
?> 