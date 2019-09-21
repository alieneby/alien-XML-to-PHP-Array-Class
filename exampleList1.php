<?php 

$strXml = <<<XML
<list>
   <item>a</item>
   <item>b</item>
   <item>c</item>
   <item>d</item>
</list>
XML;

require_once( 'AlienXml2Array.php');

$arr = AlienXml2Array::string2array( $strXml );
print_r( $arr );

/*
RESULT:
    [//list/item-0//] => a
    [//list/item-1//] => b
    [//list/item-2//] => c
    [//list/item-3//] => d
    [//list/item-count] => 5
*/