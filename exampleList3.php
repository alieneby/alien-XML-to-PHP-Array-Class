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

require_once( 'AlienXml2Array.php');

$arr = AlienXml2Array::string2array( $strXml );
print_r( $arr );
