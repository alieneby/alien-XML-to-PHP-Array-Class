<?php 

$strXml = <<<XML
<x>
   <h i="m1">
    <n>bla1</n>
   </h> 
   <h i="m2">
    <n>bla2</n>
   </h> 
</x>
XML;

require_once( 'AlienXml2Array.php');

$arr = AlienXml2Array::string2array( $strXml );
print_r( $arr );

/*
RESULT:
    [//x/h-0/n//] => bla1
    [//x/h-0//i] => m1
    [//x/h-1/n//] => bla2
    [//x/h-1//i] => m2
    [//x/h-count] => 3
*/