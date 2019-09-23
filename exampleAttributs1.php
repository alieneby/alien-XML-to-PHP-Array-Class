<?php

$strXml = <<<XML
<x a="2" b="3" c="4">
   <y q="5" w="6">My Y content</y>
</x>
XML;

require_once( 'AlienXml2Array.php' );

$arr = AlienXml2Array::string2array( $strXml );
print_r( $arr );

/* RESULT:
    [>x>y<q] => 5
    [>x>y<w] => 6
    [>x>y] => My Y content
    [>x<a] => 2
    [>x<b] => 3
    [>x<c] => 4
*/
