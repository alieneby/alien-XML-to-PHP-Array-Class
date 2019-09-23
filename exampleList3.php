<?php

$strXml = <<<XML
<list>
   <item>a</item>
   <item>b</item>
   <item>c</item>
   <somethingTotallyDifferent>d</somethingTotallyDifferent>
   <itemXXX attr="x" />
   <itemXXX attr="xx" />
   <itemXXX attr="xxx" />
</list>
XML;

require_once( 'AlienXml2Array.php' );

$arr = AlienXml2Array::string2array( $strXml );
print_r( $arr );

/*
RESULT:
    [>list>item-0] => a
    [>list>item-1] => b
    [>list>item-2] => c
    [>list>somethingTotallyDifferent] => d
    [>list>itemXXX-0<attr] => x
    [>list>itemXXX-1<attr] => xx
    [>list>itemXXX-2<attr] => xxx
    [>list>item-count] => 3
    [>list>itemXXX-count] => 3
*/
