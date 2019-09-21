<?php 

class AlienXml2Array {
  static $_fTrimContent = true;
  static $_fKeepEmptyContent = false;

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
   * returns: array(
   *  "//x/a/f//" => "g"
   *  "//x/b//d" => "e"
   *  "//x/h-0//i" => "j"
   *  "//x/h-0//" => "m"
   *  "//x/h-1//i" => "k"
   *  "//x/h-count" => "2"
   * )
   * @param $str string xml / soap 
   * @return array Flat array. Has no subarrays.
   */
  public function string2array( $str ) {
    $node = simplexml_load_string( $str );
    $arr = array();
    self::add_node( $node, $arr );
    return $arr;
  }
 
  // inpired by @see comment examples on https://www.php.net/manual/de/simplexmlelement.children.php
  static private function add_node( $node, &$arr = null, $strNamespace = '', $fRecursive = false, $strPath = '/', $strCounter = '' ) {

    $arrNamespaces = $node->getNameSpaces( true );
    $strContent = "$node";
    $strElementName = $node->getName();
    $strPath .=  '/' . $strElementName . $strCounter;

    if ( ! $fRecursive ) {
      $arrNS = array_keys( $node->getNameSpaces( false ) );
      foreach ( $arrNS as $n => $strNS ) {
        $arr[ $strPath . '/namespace/' . $n ] = $strNS;
      }
    }

    if ( $strNamespace ) $arr[$strPath . '/namespace'] = $strNamespace;
    
   
    foreach ( $arrNamespaces as $pre => $ns ) {
      foreach ( $node->children( $ns ) as $k => $v ) {
        self::add_node( $v, $arr, $pre, true, $strPath );
      }
      foreach ( $node->attributes( $ns ) as $k => $v) {
        $arr[ $strPath . '//' . $k ]="$pre:$v";
      }
    }

    $arrChildrenNamesCount = array();
    foreach ( $node->children() as $k => $v ) {
      $arrChildrenNamesCount[ "$k" ] = 1 + ( empty( $arrChildrenNamesCount[ "$k" ] ) ? 0 : $arrChildrenNamesCount[ "$k" ] );
    }
   // print_r( $arrChildrenNamesCount );

    $arrChildrenNeedsCounter = array();
    foreach ( $arrChildrenNamesCount as $k => $v ) {
      if ( $arrChildrenNamesCount[ "$k" ] > 1 ) $arrChildrenNeedsCounter[ "$k" ] = 0;
    }
    //print_r( $arrChildrenNamesCount );

    foreach ( $node->children() as $k => $v ) {
      $strCounterLoop = '';
      if ( isset( $arrChildrenNeedsCounter[ "$k" ] ) ) {
        $strCounterLoop = '-' . $arrChildrenNeedsCounter[ "$k" ];
        $arrChildrenNeedsCounter[ "$k" ] += 1;
      }
      self::add_node( $v, $arr, '', true, $strPath, $strCounterLoop );
    }

    foreach ( $arrChildrenNeedsCounter as $k => $nCountValue ) {
      $arr[ $strPath . '/' . $k . '-count'] = 1 + $nCountValue;
    }

    foreach ( $node->attributes() as $k => $v) {
      $arr[ $strPath . '//' . $k ] = "$v";
    }

    if ( self::$_fTrimContent ) $strContent = trim( $strContent );

    if ( $strContent || self::$_fKeepEmptyContent ) {
      $arr[ $strPath . '//'] = $strContent;
    } 
  }

}
