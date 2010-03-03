<?php
function soundex2( $sIn ) {
   // Si il n'y a pas de mot, on sort imm�diatement
   if ( $sIn === '' ) return '    ';
   // On met tout en minuscule
   $sIn = strtoupper( $sIn );
   // On supprime les accents
   $sIn = strtr( $sIn, '��������&#338;��������', 'AAASEEEEEIIOOUUUS' );
   // On supprime tout ce qui n'est pas une lettre
   $sIn = preg_replace( '`[^A-Z]`', '', $sIn );
   // Si la cha�ne ne fait qu'un seul caract�re, on sort avec.
   if ( strlen( $sIn ) === 1 ) return $sIn . '   ';
   // on remplace les consonnances primaires
   $convIn = array( 'GUI', 'GUE', 'GA', 'GO', 'GU', 'CA', 'CO', 'CU',
'Q', 'CC', 'CK' );
   $convOut = array( 'KI', 'KE', 'KA', 'KO', 'K', 'KA', 'KO', 'KU', 'K',
'K', 'K' );
   $sIn = str_replace( $convIn, $convOut, $sIn );
   // on remplace les voyelles sauf le Y et sauf la premi�re par A
   $sIn = preg_replace( '`(?<!^)[EIOU]`', 'A', $sIn );
   // on remplace les pr�fixes puis on conserve la premi�re lettre
   // et on fait les remplacements compl�mentaires
   $convIn = array( '`^KN`', '`^(PH|PF)`', '`^MAC`', '`^SCH`', '`^ASA`',
'`(?<!^)KN`', '`(?<!^)(PH|PF)`', '`(?<!^)MAC`', '`(?<!^)SCH`',
'`(?<!^)ASA`' );
   $convOut = array( 'NN', 'FF', 'MCC', 'SSS', 'AZA', 'NN', 'FF', 'MCC',
'SSS', 'AZA' );
   $sIn = preg_replace( $convIn, $convOut, $sIn );
   // suppression des H sauf CH ou SH
   $sIn = preg_replace( '`(?<![CS])H`', '', $sIn );
   // suppression des Y sauf pr�c�d�s d'un A
   $sIn = preg_replace( '`(?<!A)Y`', '', $sIn );
   // on supprime les terminaisons A, T, D, S
   $sIn = preg_replace( '`[ATDS]$`', '', $sIn );
   // suppression de tous les A sauf en t�te
   $sIn = preg_replace( '`(?!^)A`', '', $sIn );
   // on supprime les lettres r�p�titives
   $sIn = preg_replace( '`(.)\1`', '$1', $sIn );
   // on ne retient que 4 caract�res ou on compl�te avec des blancs
   return substr( $sIn . '    ', 0, 4);
}
?> 