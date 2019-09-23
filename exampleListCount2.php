<?php

$strXml = <<<XML
<list>
   <item>
        <name firstName="tom"/>
   </item>
   <item>
        <name firstName="tina"/>
   </item>
   <item>
        <name />
   </item>
   <single>e</single>   
</list>
XML;

require_once( 'AlienXml2Array.php' );

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

print_r( array( $n1, $n2, $n3, $n4, $n5, $n6 ) );

/*
RESULT:
    [0] => 3
    [1] => 3
    [2] => 1
    [3] => 1
    [4] => 1
    [5] => 0
*/


echo "EXAMPLE LOOP 1  \n";

$nItems = AlienXml2Array::getCount( '>list>item', $arrAll ); // = 3
for ( $i = 0; $i < $nItems; $i++ ) {
    $k = ">list>item-$i>name<firstName";
    $strName = isset( $arrAll[ $k ] ) ? $arrAll[ $k ] : '';
    echo "Item name $i: $strName\n";
}

/*
RESULT:
Item name 0: tom
Item name 1: tina
Item name 2:
*/


echo "EXAMPLE LOOP 2  \n";
$arrKeys = AlienXml2Array::getCountKeys( '>list>item', $arrAll );
foreach ( $arrKeys as $key ) {
    echo "$key first name: "
        . AlienXml2Array::findFirstValue( $key . '>name<firstName', $arrAll )
        . "\n";
}

/*
RESULT:
>list>item-0 first name: tom
>list>item-1 first name: tina
>list>item-2 first name: 
*/
