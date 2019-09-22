# 👽 Alien XML to PHP Array Class / Function

XML string to a flat array.
Very easy and simple to use.

No issues with namespaces or soap messages.
Namespaces are supported.  


## Easy Example XML:
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
## Simple Example XML:

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

1. AlienXml2Array::findFirstValue( $strSearchKeyEnd, $arrXML )
2. AlienXml2Array::findFirstKey( $strSearchKeyEnd, $arrXML )
3. AlienXml2Array::getCount( $strSearchKeyEnd, $arrXML )
3. AlienXml2Array::getCountKeys( $strSearchKeyEnd, $arrXML )

-------------------------------


### 1) AlienXml2Array::findFirstValue( $strSearchKeyEnd, $arrXML )

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


### 2) AlienXml2Array::findFirstKey( $strSearchKeyEnd, $arrXML )

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


### 3) AlienXml2Array::getCount( $strSearchKeyEnd, $arrXML )

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

```
AlienXml2Array::getCount( '>list>item', $arr ) = 3
AlienXml2Array::getCount( '>item', $arr ) = 3
AlienXml2Array::getCount( '>lis.*>single', $arr ) = 1
AlienXml2Array::getCount( '>list', $arr ) = 1
AlienXml2Array::getCount( '>doesNotExist', $arr ) = 0
```


### 4) AlienXml2Array::getCountKeys( $strSearchKeyEnd, $arrXML )

If there is a list in xml, then return keys within an array.

We use the xml and data from previous example.

Examples:
```
AlienXml2Array::getCountKeys( '>list>item', $arr ) 
    = array( '>list>item-0', '>list>item-1' );

AlienXml2Array::getCountKeys( '>item', $arr ) 
    = array( '>list>item-0', '>list>item-1' );

AlienXml2Array::getCountKeys( '>list>single', $arr ) 
    = array( '>list>single' );

AlienXml2Array::getCountKeys( '>list', $arr ) 
    = array( '>list' );

AlienXml2Array::getCountKeys( '>doesNotExist', $arr ) 
    = array();

```

---------------------------------
# How do I write a loop over a list of items
-------------------------------

```
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
```

$arr = AlienXml2Array::string2array( $strXML );
```
    [>list>item-0>name<firstName] => tom
    [>list>item-1>name<firstName] => tina
    [>list>item-2>name] => 
    [>list>single] => e
    [>list>item-count] => 3
```

Easy PHP example: POSSIBILITY 1:
```
$arrKeys =  AlienXml2Array::getCountKeys( '>list>item', $arrAll );
foreach ($arrKeys as $key) {
    echo "K: $key first name: " 
        . AlienXml2Array::findFirstValue( $key . '>name<firstName', $arrAll ) 
        . "\n";
}
```

RESULT:
```
K: >list>item-0 first name: tom
K: >list>item-1 first name: tina
K: >list>item-2 first name:
```


It is also possible to interate over a NONE list element like single:
```
$arrKeys =  AlienXml2Array::getCountKeys( '>list>single', $arrAll );
foreach ($arrKeys as $key) {
    echo "K: $key first name: " 
        . AlienXml2Array::findFirstValue( $key, $arrAll ) 
        . "\n";
}
```

RESULT:
```
K: >list>single value: e
```

-----------------

Simple PHP example: POSSIBILITY 2:
```
$nItems = AlienXml2Array::getCount( '>list>item', $arrAll ); // = 3
for ( $i = 0; $i < $nItems; $i++ ) {
    $k = ">list>item-$i>name<firstName";
    $strName = isset( $arrAll[ $k ] ) ? $arrAll[ $k ] : ''; 
    echo "Item name $i: $strName\n";
}
```
RESULT:
```
Item name 0: tom
Item name 1: tina
Item name 2:
```