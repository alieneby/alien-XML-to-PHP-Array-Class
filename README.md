# 👽 Alien XML to PHP Array Class / Function

XML String to a flat array.

No issues with namespaces or soap response messages:


Example:

<list>
   <item>a</item>
   <item>b</item>
   <item>c</item>
   <item>d</item>
</list>

$arr = AlienXml2Array::string2array( $strXML ); 

/*
RESULT:
    [//list/item-0//] => a
    [//list/item-1//] => b
    [//list/item-2//] => c
    [//list/item-3//] => d
    [//list/item-count] => 5
*/

-------------------------------
Example:


<x a="2" b="3" c="4">
   <y q="5" w="6">My Y content</y>
</x>

$arr = AlienXml2Array::string2array( $strXML ); 

/* RESULT:
    [//x/y//q] => 5
    [//x/y//w] => 6
    [//x/y//] => My Y content
    [//x//a] => 2
    [//x//b] => 3
    [//x//c] => 4
*/
