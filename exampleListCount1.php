<?php 

$strXml = <<<XML
<list>
   <item>a</item>
   <item>b</item>
   <item>c</item>
   <item>d</item>
   <single>e</single>   
</list>
XML;

require_once( 'AlienXml2Array.php');

$arrAll = AlienXml2Array::string2array( $strXml );
print_r( $arrAll );

/*
RESULT:
    [>list>item-0] => a
    [>list>item-1] => b
    [>list>item-2] => c
    [>list>item-3] => d
    [>list>single] => e
    [>list>item-count] => 4
*/

$n1 = AlienXml2Array::getCount( '>list>item', $arrAll ); // = 4
$n2 = AlienXml2Array::getCount( '>list>item-count', $arrAll ); // = 4
$n3 = AlienXml2Array::getCount( '>list>single', $arrAll ); // = 1
$n4 = AlienXml2Array::getCount( '>list>single-count', $arrAll ); // = 1
$n5 = AlienXml2Array::getCount( '>list', $arrAll ); // = 1
$n6 = AlienXml2Array::getCount( '>list>doesNotExist', $arrAll ); // = 0

print_r ( array( $n1, $n2, $n3, $n4, $n5, $n6 ) );
/*
RESULT:
    [0] => 4
    [1] => 4
    [2] => 1
    [3] => 1
    [4] => 1
    [5] => 0
*/
