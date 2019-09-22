# 👽 Alien XML to PHP Array Class / Function

XML String to a flat array.

No issues with namespaces or soap response messages:

## Example:
```
<list>
   <item>a</item>
   <item>b</item>
   <item>c</item>
   <item>d</item>
</list>
```

$arr = AlienXml2Array::string2array( $strXML ); 

```
/*
RESULT:
    [>list>item-0] => a
    [>list<item-1] => b
    [>list>item-2] => c
    [>list>item-3] => d
    [>list>item-count] => 5
*/
```

-------------------------------
## Example:

```
<x a="2" b="3" c="4">
    <y q="5" w="6">My Y content</y>
</x>
```

$arr = AlienXml2Array::string2array( $strXML ); 

```
/* RESULT:
    [>x>y<q] => 5
    [>x>y<w] => 6
    [>x<y] => My Y content
    [>x<a] => 2
    [>x<b] => 3
    [>x<c] => 4
*/
```

-------------------------------
## 4 Helper Methods 

a) AlienXml2Array::findFirstValue( $strSearchKeyEnd, $arrXML )
b) AlienXml2Array::findFirstKey( $strSearchKeyEnd, $arrXML )
c) AlienXml2Array::getCount( $strSearchKeyEnd, $arrXML )

### AlienXml2Array::findFirstValue( $strSearchKeyEnd, $arrXML )

Returns first found value.
$strSearchKeyEnd: Key or end part of the key.

Example:
```
<x a="2" b="3" c="4">
    <y q="5" w="6">My Y content</y>
    <w>
        <v>
            <k>this is k content</k>
        </v>
    </w>
</x>
```

```
AlienXml2Array::findFirstValue( '>x>w>v>k', $arr ) = 'this is k content'
AlienXml2Array::findFirstValue( '>x.*>k', $arr ) = 'this is k content'
AlienXml2Array::findFirstValue( '>k', $arr ) = 'this is k content'
AlienXml2Array::findFirstValue( '>x<a', $arr ) = '2'
AlienXml2Array::findFirstValue( '>x>y<q', $arr ) = '5'
AlienXml2Array::findFirstValue( 'y<q', $arr ) = '5'
AlienXml2Array::findFirstValue( '>doesNotExist', $arr ) = ''
```

### AlienXml2Array::findFirstKey( $strSearchKeyEnd, $arrXML )

Returns first found key.
$strSearchKeyEnd: Key or end part of the key.

Example:
```
<x a="2" b="3" c="4">
    <y q="5" w="6">My Y content</y>
    <w>
        <v>
            <k>this is k content</k>
        </v>
    </w>
</x>
```

```
AlienXml2Array::findFirstKey( '>x>w>v>k', $arr ) = '>x>w>v>k'
AlienXml2Array::findFirstKey( '>x.*>k', $arr ) = '>x>w>v>k'
AlienXml2Array::findFirstKey( '>k', $arr ) = '>x>w>v>k'
AlienXml2Array::findFirstKey( '>x<a', $arr ) = '>x<a'
AlienXml2Array::findFirstKey( '>y<q', $arr ) = '>x>y<q'
AlienXml2Array::findFirstKey( '>x>.*>y<q', $arr ) = '>x>y<q'
AlienXml2Array::findFirstKey( '>doesNotExist', $arr ) = ''
```

### AlienXml2Array::getCount( $strSearchKeyEnd, $arrXML )

```
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
```

$arr = AlienXml2Array::string2array( $strXML ); 
```
    [>list>item-0>name<first] => tom
    [>list>item-1>name<first] => tina
    [>list>item-2>name] => 
    [>list>single] => e
    [>list>item-count] => 3
```

AlienXml2Array::getCount( '>list>item', $arr ) = 3
AlienXml2Array::getCount( '>item', $arr ) = 3
AlienXml2Array::getCount( '>lis.*>single', $arr ) = 1
AlienXml2Array::getCount( '>list', $arr ) = 1
AlienXml2Array::getCount( '>doesNotExist', $arr ) = 0


### AlienXml2Array::getCountKeys( $strSearchKeyEnd, $arrXML )

If there is a list in xml, then return the array key.

We use the xml and array from previous example.

Examples:
        AlienXml2Array::getCountKeys( '>list>item', $arr ) 
            = array( '>list>item-0', '>list>item-1');

getCountKeys( '>item', $arr ) = array( '>list>item-0', '>list>item-1');
getCountKeys( '>xml>a', $arrAll ) = array( '>xml>a-0', '>xml>a-1');
getCountKeys( '.*>b', $arrAll ) = array( '>xml>b');
getCountKeys( '>xml', $arrAll ) = array( '>xml');
getCountKeys( '>doesNotExist', $arrAll ) = array();
