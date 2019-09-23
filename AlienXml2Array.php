<?php

class AlienXml2Array {

    static $_fTrimContent = true;
    static $_fKeepEmptyContent = false;
    static $_fIgnoreNameSpaces = false;
    static $_charSeperatorElement = '>';
    static $_charSeperatorAttr = '<';

    private static $_strSeperatorsEscaped; // will be set

    /**
     * Example:
     * string2array('
     *  <x>
     *    <a>
     *      <f>g</f>
     *    </a>
     *    <b d="e" />
     *    <h i="j">m</h>
     *    <h i="k" />
     * </x>')
     *
     * returns:
     *    [>list>item-0>name<first] => tom
     *    [>list>item-1>name<first] => tina
     *    [>list>item-2>name] =>
     *    [>list>single] => e
     *    [>list>item-count] => 3
     *
     * @param $str string xml / soap
     * @return array Flat array. Has no subarrays.
     */
    public function string2array( $str ) {
        self::$_strSeperatorsEscaped
            = preg_quote( self::$_charSeperatorElement ) . '|' . preg_quote( self::$_charSeperatorAttr );
        $node = simplexml_load_string( $str );
        $arr = array();
        self::add_node( $node, $arr );
        return $arr;
    }

    /**
     * Search a key, which fits "$strSearchEnd".
     *
     * Example: $arr = array(
     *  ">a>b>c" => "x",
     *  ">a>b<d" => "y"
     * )
     * findFirstKey( '>a>b>c', $arr ) = '>a>b>c'
     * findFirstKey( '>c', $arr ) = '>a>b>c'
     * findFirstKey( '>a>.*>c', $arr ) = '>a>b>c'
     * findFirstKey( '>e', $arr ) = ''
     * findFirstKey( '<d', $arr ) = '>a>b<d'
     * findFirstKey( '', $arr ) = ''
     *
     * @param $strSearchEnd string
     * @param $arrAll array with the xml values
     * @return string Key of array
     */
    public static function findFirstKey( $strSearchEnd, $arrAll ) {
        if ( isset( $arrAll[ $strSearchEnd ] ) ) {
            return $strSearchEnd;
        }
        $arrMatch = array();
        foreach ( $arrAll as $k => $v ) {
            if ( preg_match( "/$strSearchEnd\$/", $k ) ) {
                return $k;
            }
        }
        return '';
    }

    /**
     * Search a key, which fits "$strSearchEnd".
     *
     * Example: $arr = array(
     *  ">a>b>c" => "x",
     *  ">a>b<d" => "y"
     * )
     * findFirstValue( '>a>b>c', $arr ) = 'x'
     * findFirstValue( '>c', $arr ) = 'x'
     * findFirstValue( '>a>.*>c', $arr ) = 'x'
     * findFirstValue( '>e', $arr ) = ''
     * findFirstValue( '>b<d', $arr ) = 'y'
     * findFirstValue( '', $arr ) = ''
     *
     * @param $strSearchEnd string
     * @param $arrAll array with the xml values
     * @return string value of array
     */
    public static function findFirstValue( $strSearchEnd, $arrAll ) {
        $strKey = self::findFirstKey( $strSearchEnd, $arrAll );
        if ( ! $strKey ) return '';
        return $arrAll[ $strKey ];
    }

    /**
     * Get the "-count" value.
     * Eg:
     *  $arrAll = array(
     *      '>xml>a-count' = 2,
     *      '>xml>a-0' = 'x'
     *      '>xml>a-1' = 'y'
     *      '>xml>b' = 'z' );
     *
     * getCount( '>xml>a-count', $arrAll ) = 2
     * getCount( '>xml>a', $arrAll ) = 2
     * getCount( '>xml>b', $arrAll ) = 1
     * getCount( '>xml>b-count', $arrAll ) = 1
     * getCount( '>xml>c', $arrAll ) = 0
     * getCount( '>xml', $arrAll ) = 1
     *
     * @strKey string Full key name
     * @arrAll array with the xml content
     * @return number count value
     */
    public static function getCount( $strKey, $arrAll ) {
        $fDebugMethod = false;

        $fHasCount = substr( $strKey, -6 ) == '-count' ? true : false;
        $strKeyCount = $fHasCount ? $strKey : $strKey . '-count';

        if ( $fDebugMethod ) echo __LINE__ . " strKeyCount: $strKeyCount\n";

        $strFullKeyCount = self::findFirstKey( $strKeyCount, $arrAll );

        if ( $strFullKeyCount ) {
            if ( $fDebugMethod ) echo __LINE__ . " strFullKeyCount: $strFullKeyCount\n";
            return 0 + $arrAll[ $strFullKeyCount ];
        }

        $strKey = substr( $strKeyCount, 0, -6 );;
        $strFullKey = self::findFirstKey( $strKey, $arrAll );
        if ( $fDebugMethod ) echo __LINE__ . " $strFullKey $strKey\n";


        if ( $strFullKey ) {
            if ( $fDebugMethod ) echo __LINE__ . " found $strKey\n";
            return 1;
        }

        // Perhaps the key is a parent element, without a count value
        $strChildrenKey = self::findFirstKey( $strKey . '(' . self::$_strSeperatorsEscaped . ').*', $arrAll );

        if ( $strChildrenKey ) {
            if ( $fDebugMethod ) echo __LINE__ . " return 1\n";
            return 1; // has children... then key does exists.
        }

        if ( $fDebugMethod ) echo __LINE__ . " return 0\n";
        return 0;
    }

    /**
     * getCountKeys( ".*listItems>Item", $arrAll )
     * Exmaple:
     *  $arrAll = array(
     *      '>xml>a-count' = 2,
     *      '>xml>a-0' = 'x'
     *      '>xml>a-1' = 'y'
     *      '>xml>b' = 'z' );
     *
     * getCountKeys( '>a', $arrAll ) = array( '>xml>a-0', '>xml>a-1');
     * getCountKeys( '>xml>a', $arrAll ) = array( '>xml>a-0', '>xml>a-1');
     * getCountKeys( '.*>b', $arrAll ) = array( '>xml>b');
     * getCountKeys( '>xml', $arrAll ) = array( '>xml');
     * getCountKeys( '>doesNotExist', $arrAll ) = array();
     *
     * @strSearchEnd string Full key name
     * @arrAll array with the xml content
     * @return array count value
     */
    public static function getCountKeys( $strSearchEnd, $arrAll ) {

        $strFullKeyCount = self::findFirstKey( $strSearchEnd . '-count', $arrAll );
        if ( ! $strFullKeyCount ) {
            // Perhaps it is a single key, not a list.
            $strFullKey = self::findFirstKey( $strSearchEnd, $arrAll );
            if ( $strFullKey ) return array( $strFullKey );

            // Perhaps the key is a parent element, without a count value
            $strChildrenKey = self::findFirstKey( $strSearchEnd . '(' . self::$_strSeperatorsEscaped . ').*', $arrAll );
            if ( ! $strChildrenKey ) return array(); // no children... then key does not exists.

            // we know $strSearchEnd is a parent element. So we return it full name until  
            $arr = array();
            if ( preg_match( "/^(.*$strSearchEnd)(" . self::$_strSeperatorsEscaped . ")/", $strChildrenKey, $arr ) ) {
                return array( $arr[1] );
            }
            return array( 'ERROR ' . __LINE__ );
        }

        //echo __LINE__ . "strFullKeyCount: $strFullKeyCount\n";
        if ( ! $strFullKeyCount ) return array();
        $nCount = self::getCount( $strFullKeyCount, $arrAll );
        //echo __LINE__ . "nCount: $nCount\n";
        if ( ! $nCount ) return array();
        $strFullKey = substr( $strFullKeyCount, 0, -6 );
        if ( $nCount == 1 ) return array( $strFullKey );
        $arrReturn = array();
        for ( $i = 0; $i < $nCount; $i++ ) {
            $arrReturn[ $i ] = "$strFullKey-$i";
        }
        return $arrReturn;
    }


    // inpired by @see comment examples on https://www.php.net/manual/de/simplexmlelement.children.php
    static private function add_node( $node, &$arr = null, $strNamespace = '', $fRecursive = false, $strPath = '', $strCounter = '' ) {

        $arrNamespaces = $node->getNameSpaces( true );
        $strContent = "$node";
        $strElementName = $node->getName();
        $strPrefixNamespace = ( $strNamespace && ! self::$_fIgnoreNameSpaces ) ? ( $strNamespace . ':' ) : '';
        $strPath .= self::$_charSeperatorElement . $strPrefixNamespace . $strElementName . $strCounter;

        if ( ! $fRecursive ) {
            $arrNS = array_keys( $node->getNameSpaces( false ) );
            foreach ( $arrNS as $n => $strNS ) {
                $arr[ $strPath . '/namespace/' . $n ] = $strNS;
            }
        }

        //if ( $strNamespace ) $arr[$strPath . '/namespace'] = $strNamespace;


        foreach ( $arrNamespaces as $pre => $ns ) {
            $arrChildrenNeedsCounter = self::getChildrenWhoNeedsACounter( $node->children( $ns ) );
            foreach ( $node->children( $ns ) as $k => $v ) {
                $strCounterLoop = self::getLoopCounter( $arrChildrenNeedsCounter, $k );
                self::add_node( $v, $arr, $pre, true, $strPath, $strCounterLoop );
            }
            foreach ( $arrChildrenNeedsCounter as $k => $nCountValue ) {
                $arr[ $strPath . self::$_charSeperatorElement . "$pre:$k-count" ] = $nCountValue;
            }
            foreach ( $node->attributes( $ns ) as $k => $v ) {
                $arr[ $strPath . self::$_charSeperatorAttr . $k ] = "$pre:$v";
            }
        }

        // key = element name, value = init counter value = 0;
        $arrChildrenNeedsCounter = self::getChildrenWhoNeedsACounter( $node->children() );

        foreach ( $node->children() as $k => $v ) {
            $strCounterLoop = self::getLoopCounter( $arrChildrenNeedsCounter, $k );
            self::add_node( $v, $arr, '', true, $strPath, $strCounterLoop );
        }


        foreach ( $arrChildrenNeedsCounter as $k => $nCountValue ) {
            $arr[ $strPath . self::$_charSeperatorElement . $k . '-count' ] = $nCountValue;
        }

        foreach ( $node->attributes() as $k => $v ) {
            $arr[ $strPath . self::$_charSeperatorAttr . $k ] = "$v";
        }

        if ( self::$_fTrimContent ) $strContent = trim( $strContent );

        $nChildrenAndAttributes = count( $node->children() ) + count( $node->attributes() );
        if ( $strContent || self::$_fKeepEmptyContent || ! $nChildrenAndAttributes ) {
            $arr[ $strPath ] = $strContent;
        }
    }

    private static function getChildrenWhoNeedsACounter( $arrChildren ) {
        // key = element name, value = How many times does this element is in the arrChildren list.
        $arrChildrenNamesCount = array();

        foreach ( $arrChildren as $k => $v ) {
            $arrChildrenNamesCount["$k"] = 1 + ( empty( $arrChildrenNamesCount["$k"] ) ? 0 : $arrChildrenNamesCount["$k"] );
        }
        // print_r( $arrChildrenNamesCount );

        // key = element name, value = 0 (counter start value)
        $arrChildrenNeedsCounter = array();
        foreach ( $arrChildrenNamesCount as $k => $v ) {
            if ( $arrChildrenNamesCount["$k"] > 1 ) $arrChildrenNeedsCounter["$k"] = 0;
        }
        //print_r( $arrChildrenNamesCount );
        return $arrChildrenNeedsCounter;
    }

    private static function getLoopCounter( &$arrChildrenNeedsCounter, $k ) {
        if ( isset( $arrChildrenNeedsCounter["$k"] ) ) {
            $strCounterLoop = '-' . $arrChildrenNeedsCounter["$k"];
            $arrChildrenNeedsCounter["$k"] += 1;
            return $strCounterLoop;
        } else {
            return '';
        }
    }

}
