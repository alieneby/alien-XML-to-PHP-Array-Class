<?php 

$strXml = <<<XML
<list>
   <item>
        <name first="tom"/>
   </item>
   <item>
        <name first="tina"/>
   </item>
   <item>
        <name />
   </item>
   <single>e</single>   
</list>
XML;

require_once( 'AlienXml2Array.php');

$arrAll = AlienXml2Array::string2array( $strXml );
print_r( $arrAll );

/*
RESULT:
    [>list>item-0>name<first] => tom
    [>list>item-1>name<first] => tina
    [>list>item-2>name] => 
    [>list>single] => e
    [>list>item-count] => 3
*/

$n1 = AlienXml2Array::getCount( '>list>item', $arrAll ); // = 3
$n2 = AlienXml2Array::getCount( '>list>item-count', $arrAll ); // = 3
$n3 = AlienXml2Array::getCount( '>list>single', $arrAll ); // = 1
$n4 = AlienXml2Array::getCount( '>list>single-count', $arrAll ); // = 1
$n5 = AlienXml2Array::getCount( '>list', $arrAll ); // = 1
$n6 = AlienXml2Array::getCount( '>list>doesNotExist', $arrAll ); // = 0

print_r ( array( $n1, $n2, $n3, $n4, $n5, $n6 ) );

/*
RESULT:
    [0] => 3
    [1] => 3
    [2] => 1
    [3] => 1
    [4] => 1
    [5] => 0
*/


$nItems = AlienXml2Array::getCount( '>list>item', $arrAll ); // = 3
for ( $i = 0; $i < $nItems; $i++ ) {
    $k = ">list>item-$i>name<first";
    $strName = isset( $arrAll[ $k ] ) ? $arrAll[ $k ] : ''; 
    echo "Item name $i: $strName\n";
}



// print_r( AlienXml2Array::getListElement( '.li.*>item', $arrAll ) );
// print_r( AlienXml2Array::getListElement( '>item', $arrAll ) );
print_r( AlienXml2Array::getListElement( 'item-1>name<first', $arrAll ) );