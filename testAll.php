<?php 
require_once( 'AlienXml2Array.php');

class TestAll {

    static function err( $strLine, $strArrPrintR, $strCommand, $strResultBase64, $strExpectedBase64 ) {
        if ( $strResultBase64 == $strExpectedBase64 ) return false;
        echo "Line #$strLine ERROR:\n";
        echo "$strArrPrintR\n";            
        echo "Cmd: $strCommand\n";
        echo "Result Base64: $strResultBase64\n";
        echo "Result:\n" . base64_decode( $strResultBase64 ) . "\n";
        echo "Expected:\n" .base64_decode( $strExpectedBase64 ) . "\n\n----------------------\n"; 
        return true;
    }

   
    static function simple1() {
        $strXml = <<<XML
        <x>
            <a>
                <f>g</f>
            </a>
            <b d="e" />
            <h i="j">m</h>
            <h i="k" />
        </x>
XML;

        // CREATTION OF ARRAY
        $arrAll = AlienXml2Array::string2array( $strXml );
        ksort( $arrAll );
        $strArrPrintR = print_r( $arrAll, true );
        $strArrBase64 = base64_encode( $strArrPrintR );
        $strExpectedBase64 = 'QXJyYXkKKAogICAgWz54PmE+Zl0gPT4gZwogICAgWz54PmI8ZF0gPT4gZQogICAgWz54PmgtMF0gPT4gbQogICAgWz54PmgtMDxpXSA9PiBqCiAgICBbPng+aC0xPGldID0+IGsKICAgIFs+eD5oLWNvdW50XSA9PiAyCikK';
        if ( $strExpectedBase64 != $strArrBase64 ) {
            echo 'Line #' . __LINE__ . " ERROR:\n";
            echo "XML: \n$strXml\nBase64: $strArrBase64\n$strArrPrintR\n";            
            echo "Expected was \n" . base64_decode( $strExpectedBase64 ) . "\n\n----------------------\n"; 
            return false;
        }

        /// ----- findFirstValue ---- START ----

        // findFirstValue
        $nResult = AlienXml2Array::findFirstValue( '>x>a>f', $arrAll );
        $strResultBase64 = base64_encode( $nResult );
        $strExpectedBase64 = 'Zw==';
        if ( self::err( __LINE__, $strArrPrintR, "findFirstValue( '>x>a>f', arr )", $strResultBase64, $strExpectedBase64 ) ) {
            return false;
        }

        // findFirstValue
        $nResult = AlienXml2Array::findFirstValue( '>x>b<d', $arrAll );
        $strResultBase64 = base64_encode( $nResult );
        $strExpectedBase64 = 'ZQ==';
        if ( self::err( __LINE__, $strArrPrintR, "findFirstValue( '>x>b<d', arr )", $strResultBase64, $strExpectedBase64 ) ) {
            return false;
        }        

        // list elements
        $arrResult = AlienXml2Array::getCountKeys( '>h', $arrAll );
        $strResultBase64 = base64_encode( print_r( $arrResult, true ) );
        $strExpectedBase64 = 'QXJyYXkKKAogICAgWzBdID0+ID54PmgtMAogICAgWzFdID0+ID54PmgtMQopCg==';
        if ( self::err( __LINE__, $strArrPrintR, "getCountKeys( '>h', arr )", $strResultBase64, $strExpectedBase64 ) ) {
            return false;
        }        

        /// ----- for loop ---- START ----
        $arrElementsH = $arrResult;
        $strResult = '';
        for( $i=0; $i<count( $arrElementsH ); $i++ ) {
            $strKey = $arrElementsH[$i];
            $strResult 
                .= "$i. h->i: " 
                . AlienXml2Array::findFirstValue( $strKey . '<i', $arrAll )
                . ", content: " 
                . AlienXml2Array::findFirstValue( $strKey, $arrAll )
                . "\n";            
        }
        $strResultBase64 = base64_encode( $strResult );
        $strExpectedBase64 = 'MC4gaC0+aTogaiwgY29udGVudDogbQoxLiBoLT5pOiBrLCBjb250ZW50OiAK';
        if ( self::err( __LINE__, $strArrPrintR, "for(h)", $strResultBase64, $strExpectedBase64 ) ) {
            return false;
        }        

    }



    static function list1() {
        $strXml = <<<XML
        <list>
            <item><name first="tom"/></item>
            <item><name first="tina"/></item>
            <item><name /></item>
            <single>e</single>   
        </list>
XML;

        // CREATTION OF ARRAY
        $arrAll = AlienXml2Array::string2array( $strXml );
        ksort( $arrAll );
        $strArrPrintR = print_r( $arrAll, true );
        $strArrBase64 = base64_encode( $strArrPrintR );
        $strExpectedBase64 = 'QXJyYXkKKAogICAgWz5saXN0Pml0ZW0tMD5uYW1lPGZpcnN0XSA9PiB0b20KICAgIFs+bGlzdD5pdGVtLTE+bmFtZTxmaXJzdF0gPT4gdGluYQogICAgWz5saXN0Pml0ZW0tMj5uYW1lXSA9PiAKICAgIFs+bGlzdD5pdGVtLWNvdW50XSA9PiAzCiAgICBbPmxpc3Q+c2luZ2xlXSA9PiBlCikK';
        if ( $strExpectedBase64 != $strArrBase64 ) {
            echo 'Line #' . __LINE__ . " ERROR:\n";
            echo "XML: \n$strXml\nBase64: $strArrBase64\n$strArrPrintR\n";            
            echo "Expected was \n" . base64_decode( $strExpectedBase64 ) . "\n\n----------------------\n"; 
            return false;
        }

        

        /// ----- findFirstKey ---- START ----

        // key
        $nResult = AlienXml2Array::findFirstKey( '>single', $arrAll );
        $strResultBase64 = base64_encode( $nResult );
        $strExpectedBase64 = 'Pmxpc3Q+c2luZ2xl';
        if ( self::err( __LINE__, $strArrPrintR, "findFirstKey( '>single', arr )", $strResultBase64, $strExpectedBase64 ) ) {
            return false;
        }

        // key 
        $nResult = AlienXml2Array::findFirstKey( '>item-0', $arrAll );
        $strResultBase64 = base64_encode( $nResult );
        $strExpectedBase64 = '';
        if ( self::err( __LINE__, $strArrPrintR, "findFirstKey( '>item-0', arr )", $strResultBase64, $strExpectedBase64 ) ) {
            return false;
        }

        // key 
        $nResult = AlienXml2Array::findFirstKey( '>item-0>name<first', $arrAll );
        $strResultBase64 = base64_encode( $nResult );
        $strExpectedBase64 = 'Pmxpc3Q+aXRlbS0wPm5hbWU8Zmlyc3Q=';
        if ( self::err( __LINE__, $strArrPrintR, "findFirstKey( '>item-0>name<first', arr )", $strResultBase64, $strExpectedBase64 ) ) {
            return false;
        }

        /// ----- findFirstValue ---- START ----

        // findFirstValue
        $nResult = AlienXml2Array::findFirstValue( '>single', $arrAll );
        $strResultBase64 = base64_encode( $nResult );
        $strExpectedBase64 = 'ZQ==';
        if ( self::err( __LINE__, $strArrPrintR, "findFirstValue( '>single', arr )", $strResultBase64, $strExpectedBase64 ) ) {
            return false;
        }

        // findFirstValue 
        $nResult = AlienXml2Array::findFirstValue( '>item-0', $arrAll );
        $strResultBase64 = base64_encode( $nResult );
        $strExpectedBase64 = '';
        if ( self::err( __LINE__, $strArrPrintR, "findFirstValue( '>item-0', arr )", $strResultBase64, $strExpectedBase64 ) ) {
            return false;
        }

        // findFirstValue 
        $nResult = AlienXml2Array::findFirstValue( '>item-0>name<first', $arrAll );
        $strResultBase64 = base64_encode( $nResult );
        $strExpectedBase64 = 'dG9t';
        if ( self::err( __LINE__, $strArrPrintR, "findFirstValue( '>item-0>name<first', arr )", $strResultBase64, $strExpectedBase64 ) ) {
            return false;
        }

        /// ----- count ---- START ----

        // count list values
        $nResult = AlienXml2Array::getCount( '.li.*>item-count', $arrAll );
        $strResultBase64 = base64_encode( $nResult );
        $strExpectedBase64 = 'Mw==';
        if ( self::err( __LINE__, $strArrPrintR, "getCount( '.li.*>item', arr )", $strResultBase64, $strExpectedBase64 ) ) {
            return false;
        }
        
        // count list values
        $nResult = AlienXml2Array::getCount( '>list>item', $arrAll );
        $strResultBase64 = base64_encode( $nResult );
        $strExpectedBase64 = 'Mw==';
        if ( self::err( __LINE__, $strArrPrintR, "getCount( '>list>item', arr )", $strResultBase64, $strExpectedBase64 ) ) {
            return false;
        }

        // count list values
        $nResult = AlienXml2Array::getCount( '>single', $arrAll );
        $strResultBase64 = base64_encode( $nResult );
        $strExpectedBase64 = 'MQ==';
        if ( self::err( __LINE__, $strArrPrintR, "getCount( '>single', arr )", $strResultBase64, $strExpectedBase64 ) ) {
            return false;
        }

        // count list values
        $nResult = AlienXml2Array::getCount( '>doesNotExist', $arrAll );
        $strResultBase64 = base64_encode( $nResult );
        $strExpectedBase64 = 'MA==';
        if ( self::err( __LINE__, $strArrPrintR, "getCount( '>doesNotExist', arr )", $strResultBase64, $strExpectedBase64 ) ) {
            return false;
        }

        /// ----- getting list ---- START ----

        // getting list values
        $arrResult = AlienXml2Array::getCountKeys( '.li.*>item', $arrAll );
        $strResultBase64 = base64_encode( print_r( $arrResult, true ) );
        $strExpectedBase64 = 'QXJyYXkKKAogICAgWzBdID0+ID5saXN0Pml0ZW0tMAogICAgWzFdID0+ID5saXN0Pml0ZW0tMQogICAgWzJdID0+ID5saXN0Pml0ZW0tMgopCg==';
        if ( self::err( __LINE__, $strArrPrintR, "getCountKeys( '.li.*>item', arr )", $strResultBase64, $strExpectedBase64 ) ) {
            return false;
        }

        // getting list
        $arrResult = AlienXml2Array::getCountKeys( '>item', $arrAll );
        $strResultBase64 = base64_encode( print_r( $arrResult, true ) );
        $strExpectedBase64 = 'QXJyYXkKKAogICAgWzBdID0+ID5saXN0Pml0ZW0tMAogICAgWzFdID0+ID5saXN0Pml0ZW0tMQogICAgWzJdID0+ID5saXN0Pml0ZW0tMgopCg==';
        if ( self::err( __LINE__, $strArrPrintR, "getCountKeys( '>item', arr )", $strResultBase64, $strExpectedBase64 ) ) {
            return false;
        }
        
        // getting list
        $arrResult = AlienXml2Array::getCountKeys( '>list>item', $arrAll );
        $strResultBase64 = base64_encode( print_r( $arrResult, true ) );
        $strExpectedBase64 = 'QXJyYXkKKAogICAgWzBdID0+ID5saXN0Pml0ZW0tMAogICAgWzFdID0+ID5saXN0Pml0ZW0tMQogICAgWzJdID0+ID5saXN0Pml0ZW0tMgopCg==';
        if ( self::err( __LINE__, $strArrPrintR, "getCountKeys( '>list>item', arr )", $strResultBase64, $strExpectedBase64 ) ) {
            return false;
        }

        // getting list Parent
        $arrResult = AlienXml2Array::getCountKeys( '>list', $arrAll );
        $strResultBase64 = base64_encode( print_r( $arrResult, true ) );
        $strExpectedBase64 = 'QXJyYXkKKAogICAgWzBdID0+ID5saXN0CikK';
        if ( self::err( __LINE__, $strArrPrintR, "getCountKeys( '>list', arr )", $strResultBase64, $strExpectedBase64 ) ) {
            return false;
        }
        
        // getting list single
        $arrResult = AlienXml2Array::getCountKeys( '>list>single', $arrAll );
        $strResultBase64 = base64_encode( print_r( $arrResult, true ) );
        $strExpectedBase64 = 'QXJyYXkKKAogICAgWzBdID0+ID5saXN0PnNpbmdsZQopCg==';
        if ( self::err( __LINE__, $strArrPrintR, "getCountKeys( '>list>single', arr )", $strResultBase64, $strExpectedBase64 ) ) {
            return false;
        }

        // getting list doesNotExistElement
        $arrResult = AlienXml2Array::getCountKeys( '>doesNotExistElement', $arrAll );
        $strResultBase64 = base64_encode( print_r( $arrResult, true ) );
        $strExpectedBase64 = 'QXJyYXkKKAopCg==';
        if ( self::err( __LINE__, $strArrPrintR, "getCountKeys( '>doesNotExistElement', arr )", $strResultBase64, $strExpectedBase64 ) ) {
            return false;
        }        

        return true;
    }




    static function list2() {
        $strXml = <<<XML
        <list>
            <item>tim</item>
            <item>tom</item>
            <item></item>
        </list>
XML;

        // CREATTION OF ARRAY
        $arrAll = AlienXml2Array::string2array( $strXml );
        ksort( $arrAll );
        $strArrPrintR = print_r( $arrAll, true );
        $strArrBase64 = base64_encode( $strArrPrintR );
        $strExpectedBase64 = 'QXJyYXkKKAogICAgWz5saXN0Pml0ZW0tMF0gPT4gdGltCiAgICBbPmxpc3Q+aXRlbS0xXSA9PiB0b20KICAgIFs+bGlzdD5pdGVtLTJdID0+IAogICAgWz5saXN0Pml0ZW0tY291bnRdID0+IDMKKQo=';
        if ( $strExpectedBase64 != $strArrBase64 ) {
            echo 'Line #' . __LINE__ . " ERROR:\n";
            echo "XML: \n$strXml\nBase64: $strArrBase64\n$strArrPrintR\n";            
            echo "Expected was \n" . base64_decode( $strExpectedBase64 ) . "\n\n----------------------\n"; 
            return false;
        }

        
        // getting list doesNotExistElement
        $arrResult = AlienXml2Array::getCountKeys( '>doesNotExistElement', $arrAll );
        $strResultBase64 = base64_encode( print_r( $arrResult, true ) );
        $strExpectedBase64 = 'QXJyYXkKKAopCg==';
        if ( self::err( __LINE__, $strArrPrintR, "getCountKeys( '>doesNotExistElement', arr )", $strResultBase64, $strExpectedBase64 ) ) {
            return false;
        }        

        // getting list doesNotExistElement
        $arrResult = AlienXml2Array::getCountKeys( '>list', $arrAll );
        $strResultBase64 = base64_encode( print_r( $arrResult, true ) );
        $strExpectedBase64 = 'QXJyYXkKKAogICAgWzBdID0+ID5saXN0CikK';
        if ( self::err( __LINE__, $strArrPrintR, "getCountKeys( '>list', arr )", $strResultBase64, $strExpectedBase64 ) ) {
            return false;
        }        

        // getting list item
        $arrResult = AlienXml2Array::getCountKeys( '>item', $arrAll );
        $strResultBase64 = base64_encode( print_r( $arrResult, true ) );
        $strExpectedBase64 = 'QXJyYXkKKAogICAgWzBdID0+ID5saXN0Pml0ZW0tMAogICAgWzFdID0+ID5saXN0Pml0ZW0tMQogICAgWzJdID0+ID5saXN0Pml0ZW0tMgopCg==';
        if ( self::err( __LINE__, $strArrPrintR, "getCountKeys( '>item', arr )", $strResultBase64, $strExpectedBase64 ) ) {
            return false;
        }        

        return true;
    }

    static function attribute1() {
        $strXml = <<<XML
            <x a="2" b="3" c="4">
            <y q="5" w="6">My Y content</y>
            </x>
XML;

        // CREATTION OF ARRAY
        $arrAll = AlienXml2Array::string2array( $strXml );
        ksort( $arrAll );
        $strArrPrintR = print_r( $arrAll, true );
        $strArrBase64 = base64_encode( $strArrPrintR );
        $strExpectedBase64 = 'QXJyYXkKKAogICAgWz54PGFdID0+IDIKICAgIFs+eDxiXSA9PiAzCiAgICBbPng8Y10gPT4gNAogICAgWz54PnldID0+IE15IFkgY29udGVudAogICAgWz54Pnk8cV0gPT4gNQogICAgWz54Pnk8d10gPT4gNgopCg==';
        if ( $strExpectedBase64 != $strArrBase64 ) {
            echo 'Line #' . __LINE__ . " ERROR:\n";
            echo "XML: \n$strXml\nBase64: $strArrBase64\n$strArrPrintR\n";            
            echo "Expected was \n" . base64_decode( $strExpectedBase64 ) . "\n\n----------------------\n"; 
            return false;
        }

        // ----- findFirstValue ---- START ----

        // findFirstValue
        $nResult = AlienXml2Array::findFirstValue( '>x<a', $arrAll );
        $strResultBase64 = base64_encode( $nResult );
        $strExpectedBase64 = 'Mg==';
        if ( self::err( __LINE__, $strArrPrintR, "findFirstValue( '>x<a', arr )", $strResultBase64, $strExpectedBase64 ) ) {
            return false;
        }

        // findFirstValue
        $nResult = AlienXml2Array::findFirstValue( '>x>y', $arrAll );
        $strResultBase64 = base64_encode( $nResult );
        $strExpectedBase64 = 'TXkgWSBjb250ZW50';
        if ( self::err( __LINE__, $strArrPrintR, "findFirstValue( '>x>y', arr )", $strResultBase64, $strExpectedBase64 ) ) {
            return false;
        }

        // findFirstValue
        $nResult = AlienXml2Array::findFirstValue( '>y<w', $arrAll );
        $strResultBase64 = base64_encode( $nResult );
        $strExpectedBase64 = 'Ng==';
        if ( self::err( __LINE__, $strArrPrintR, "findFirstValue( '>y<w', arr )", $strResultBase64, $strExpectedBase64 ) ) {
            return false;
        }

        // findFirstValue
        $nResult = AlienXml2Array::findFirstValue( '>y<attrDoesNotExist', $arrAll );
        $strResultBase64 = base64_encode( $nResult );
        $strExpectedBase64 = '';
        if ( self::err( __LINE__, $strArrPrintR, "findFirstValue( '>y<attrDoesNotExist', arr )", $strResultBase64, $strExpectedBase64 ) ) {
            return false;
        }

        // findFirstValue
        $nResult = AlienXml2Array::findFirstValue( '>elementDoesNotExist<attrDoesNotExist', $arrAll );
        $strResultBase64 = base64_encode( $nResult );
        $strExpectedBase64 = '';
        if ( self::err( __LINE__, $strArrPrintR, "findFirstValue( '>elementDoesNotExist<attrDoesNotExist', arr )", $strResultBase64, $strExpectedBase64 ) ) {
            return false;
        }

        return true;
    }


    static function soap1() {
        $strXml = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
  <soap:Envelope 
    xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/" 
    xmlns:awsse="http://xml.amadeus.com/2010/06/Session_v3" 
    xmlns:wsa="http://www.w3.org/2005/08/addressing">
    <soap:Header>
      <wsa:To>http://www.w3.org/2005/08/addressing/anonymous</wsa:To>
      <wsa:To>https://alien.de</wsa:To>
      <awsse:To>https://spacex.com</awsse:To>
      <wsa:From>
        <wsa:Address>https://alien.de</wsa:Address>
      </wsa:From>
      <wsa:Action>https://alien.de/GetDocument</wsa:Action>
      <wsa:MessageID>urn:uuid:88ba5cd2</wsa:MessageID>
      <wsa:RelatesTo RelationshipType="http://www.w3.org/2005/08/addressing/reply">7ad4672f</wsa:RelatesTo>
      <awsse:Session TransactionStatusCode="End">
        <awsse:SessionId>0RFGSMTJ8I</awsse:SessionId>
        <awsse:SequenceNumber>1</awsse:SequenceNumber>
        <awsse:SecurityToken>25UYOS54ETP6DISXGHXUDHJON</awsse:SecurityToken>
      </awsse:Session>
    </soap:Header>
    <soap:Body>
      <GetDocumentRS xmlns="http://xml/Document_v1" Version="1.0">
        <Success>
          <ResponseData>
            <StatusMessage Code="K225">
              <Text>Darst/RBE ok / S111 Druck LB</Text>
            </StatusMessage>
            <Attachment Encoding="PDF" Name="Bestaetigung">
              <Content>PDF DATA CUTTED!!!!!</Content>
            </Attachment>
            <Operator>UFO</Operator>
            <ReservationReference ID="575666999" ReservationNumberModule="01"/>
          </ResponseData>
        </Success>
      </GetDocumentRS>
    </soap:Body>
  </soap:Envelope>
XML;

        // CREATTION OF ARRAY
        $arrAll = AlienXml2Array::string2array( $strXml );
        ksort( $arrAll );
        $strArrPrintR = print_r( $arrAll, true );
        $strArrBase64 = base64_encode( $strArrPrintR );
        $strExpectedBase64 = 'QXJyYXkKKAogICAgWz5FbnZlbG9wZV0gPT4gCiAgICBbPkVudmVsb3BlL25hbWVzcGFjZS8wXSA9PiBzb2FwCiAgICBbPkVudmVsb3BlPnNvYXA6Qm9keT5HZXREb2N1bWVudFJTPFZlcnNpb25dID0+IDEuMAogICAgWz5FbnZlbG9wZT5zb2FwOkJvZHk+R2V0RG9jdW1lbnRSUz5TdWNjZXNzPlJlc3BvbnNlRGF0YT5BdHRhY2htZW50PEVuY29kaW5nXSA9PiBQREYKICAgIFs+RW52ZWxvcGU+c29hcDpCb2R5PkdldERvY3VtZW50UlM+U3VjY2Vzcz5SZXNwb25zZURhdGE+QXR0YWNobWVudDxOYW1lXSA9PiBCZXN0YWV0aWd1bmcKICAgIFs+RW52ZWxvcGU+c29hcDpCb2R5PkdldERvY3VtZW50UlM+U3VjY2Vzcz5SZXNwb25zZURhdGE+QXR0YWNobWVudD5Db250ZW50XSA9PiBQREYgREFUQSBDVVRURUQhISEhIQogICAgWz5FbnZlbG9wZT5zb2FwOkJvZHk+R2V0RG9jdW1lbnRSUz5TdWNjZXNzPlJlc3BvbnNlRGF0YT5PcGVyYXRvcl0gPT4gVUZPCiAgICBbPkVudmVsb3BlPnNvYXA6Qm9keT5HZXREb2N1bWVudFJTPlN1Y2Nlc3M+UmVzcG9uc2VEYXRhPlJlc2VydmF0aW9uUmVmZXJlbmNlPElEXSA9PiA1NzU2NjY5OTkKICAgIFs+RW52ZWxvcGU+c29hcDpCb2R5PkdldERvY3VtZW50UlM+U3VjY2Vzcz5SZXNwb25zZURhdGE+UmVzZXJ2YXRpb25SZWZlcmVuY2U8UmVzZXJ2YXRpb25OdW1iZXJNb2R1bGVdID0+IDAxCiAgICBbPkVudmVsb3BlPnNvYXA6Qm9keT5HZXREb2N1bWVudFJTPlN1Y2Nlc3M+UmVzcG9uc2VEYXRhPlN0YXR1c01lc3NhZ2U8Q29kZV0gPT4gSzIyNQogICAgWz5FbnZlbG9wZT5zb2FwOkJvZHk+R2V0RG9jdW1lbnRSUz5TdWNjZXNzPlJlc3BvbnNlRGF0YT5TdGF0dXNNZXNzYWdlPlRleHRdID0+IERhcnN0L1JCRSBvayAvIFMxMTEgRHJ1Y2sgTEIKICAgIFs+RW52ZWxvcGU+c29hcDpIZWFkZXJdID0+IAogICAgWz5FbnZlbG9wZT5zb2FwOkhlYWRlcj5hd3NzZTpTZXNzaW9uPFRyYW5zYWN0aW9uU3RhdHVzQ29kZV0gPT4gRW5kCiAgICBbPkVudmVsb3BlPnNvYXA6SGVhZGVyPmF3c3NlOlNlc3Npb24+YXdzc2U6U2VjdXJpdHlUb2tlbl0gPT4gMjVVWU9TNTRFVFA2RElTWEdIWFVESEpPTgogICAgWz5FbnZlbG9wZT5zb2FwOkhlYWRlcj5hd3NzZTpTZXNzaW9uPmF3c3NlOlNlcXVlbmNlTnVtYmVyXSA9PiAxCiAgICBbPkVudmVsb3BlPnNvYXA6SGVhZGVyPmF3c3NlOlNlc3Npb24+YXdzc2U6U2Vzc2lvbklkXSA9PiAwUkZHU01USjhJCiAgICBbPkVudmVsb3BlPnNvYXA6SGVhZGVyPmF3c3NlOlRvXSA9PiBodHRwczovL3NwYWNleC5jb20KICAgIFs+RW52ZWxvcGU+c29hcDpIZWFkZXI+d3NhOkFjdGlvbl0gPT4gaHR0cHM6Ly9hbGllbi5kZS9HZXREb2N1bWVudAogICAgWz5FbnZlbG9wZT5zb2FwOkhlYWRlcj53c2E6RnJvbV0gPT4gCiAgICBbPkVudmVsb3BlPnNvYXA6SGVhZGVyPndzYTpGcm9tPndzYTpBZGRyZXNzXSA9PiBodHRwczovL2FsaWVuLmRlCiAgICBbPkVudmVsb3BlPnNvYXA6SGVhZGVyPndzYTpNZXNzYWdlSURdID0+IHVybjp1dWlkOjg4YmE1Y2QyCiAgICBbPkVudmVsb3BlPnNvYXA6SGVhZGVyPndzYTpSZWxhdGVzVG9dID0+IDdhZDQ2NzJmCiAgICBbPkVudmVsb3BlPnNvYXA6SGVhZGVyPndzYTpSZWxhdGVzVG88UmVsYXRpb25zaGlwVHlwZV0gPT4gaHR0cDovL3d3dy53My5vcmcvMjAwNS8wOC9hZGRyZXNzaW5nL3JlcGx5CiAgICBbPkVudmVsb3BlPnNvYXA6SGVhZGVyPndzYTpUby0wXSA9PiBodHRwOi8vd3d3LnczLm9yZy8yMDA1LzA4L2FkZHJlc3NpbmcvYW5vbnltb3VzCiAgICBbPkVudmVsb3BlPnNvYXA6SGVhZGVyPndzYTpUby0xXSA9PiBodHRwczovL2FsaWVuLmRlCiAgICBbPkVudmVsb3BlPnNvYXA6SGVhZGVyPndzYTpUby1jb3VudF0gPT4gMgopCg==';
        if ( $strExpectedBase64 != $strArrBase64 ) {
            echo 'Line #' . __LINE__ . " ERROR:\n";
            echo "XML: \n$strXml\nBase64: $strArrBase64\n$strArrPrintR\n";            
            echo "Expected was \n" . base64_decode( $strExpectedBase64 ) . "\n\n----------------------\n"; 
            return false;
        }

        // ----- findFirstValue ---- START ----

        // findFirstValue
        $nResult = AlienXml2Array::findFirstValue( '>Envelope>soap:Header>awsse:Session>awsse:SessionId', $arrAll );
        $strResultBase64 = base64_encode( $nResult );
        $strExpectedBase64 = 'MFJGR1NNVEo4SQ==';
        if ( self::err( __LINE__, $strArrPrintR, "findFirstValue( '>Envelope>soap:Header>awsse:Session>awsse:SessionId', arr )", $strResultBase64, $strExpectedBase64 ) ) {
            return false;
        }

        // findFirstValue
        $nResult = AlienXml2Array::findFirstValue( '.*>soap:Header>.*>awsse:SessionId', $arrAll );
        $strResultBase64 = base64_encode( $nResult );
        $strExpectedBase64 = 'MFJGR1NNVEo4SQ==';
        if ( self::err( __LINE__, $strArrPrintR, "findFirstValue( '.*>soap:Header>.*>awsse:SessionId', arr )", $strResultBase64, $strExpectedBase64 ) ) {
            return false;
        }

        // findFirstValue
        $nResult = AlienXml2Array::findFirstValue( '>Success>ResponseData>Attachment<Encoding', $arrAll );
        $strResultBase64 = base64_encode( $nResult );
        $strExpectedBase64 = 'UERG';
        if ( self::err( __LINE__, $strArrPrintR, "findFirstValue( '>Success>ResponseData>Attachment<Encoding', arr )", $strResultBase64, $strExpectedBase64 ) ) {
            return false;
        }

        // findFirstValue
        $nResult = AlienXml2Array::findFirstValue( '>y<attrDoesNotExist', $arrAll );
        $strResultBase64 = base64_encode( $nResult );
        $strExpectedBase64 = '';
        if ( self::err( __LINE__, $strArrPrintR, "findFirstValue( '>y<attrDoesNotExist', arr )", $strResultBase64, $strExpectedBase64 ) ) {
            return false;
        }

        // findFirstValue
        $nResult = AlienXml2Array::findFirstValue( '>elementDoesNotExist<attrDoesNotExist', $arrAll );
        $strResultBase64 = base64_encode( $nResult );
        $strExpectedBase64 = '';
        if ( self::err( __LINE__, $strArrPrintR, "findFirstValue( '>elementDoesNotExist<attrDoesNotExist', arr )", $strResultBase64, $strExpectedBase64 ) ) {
            return false;
        }

        // ----- findFirstKey ---- START ----

        // findFirstValue
        $nResult = AlienXml2Array::findFirstKey( ':SessionId', $arrAll );
        $strResultBase64 = base64_encode( $nResult );
        $strExpectedBase64 = 'PkVudmVsb3BlPnNvYXA6SGVhZGVyPmF3c3NlOlNlc3Npb24+YXdzc2U6U2Vzc2lvbklk';
        if ( self::err( __LINE__, $strArrPrintR, "findFirstKey( ':SessionId', arr )", $strResultBase64, $strExpectedBase64 ) ) {
            return false;
        }

        // ----- count ---- START ----
        
        // count list values
        $nResult = AlienXml2Array::getCount( '>doesNotExist', $arrAll );
        $strResultBase64 = base64_encode( $nResult );
        $strExpectedBase64 = 'MA==';
        if ( self::err( __LINE__, $strArrPrintR, "getCount( '>doesNotExist', arr )", $strResultBase64, $strExpectedBase64 ) ) {
            return false;
        }

        // count list values
        $nResult = AlienXml2Array::getCount( '>Envelope>soap:Header>wsa:To', $arrAll );
        $strResultBase64 = base64_encode( $nResult );
        $strExpectedBase64 = 'Mg==';
        if ( self::err( __LINE__, $strArrPrintR, "getCount( '>Envelope>soap:Header>wsa:To', arr )", $strResultBase64, $strExpectedBase64 ) ) {
            return false;
        }
        // count list values
        $nResult = AlienXml2Array::getCount( '>wsa:To', $arrAll );
        $strResultBase64 = base64_encode( $nResult );
        $strExpectedBase64 = 'Mg==';
        if ( self::err( __LINE__, $strArrPrintR, "getCount( '>wsa:To', arr )", $strResultBase64, $strExpectedBase64 ) ) {
            return false;
        }

        /// ----- getting list ---- START ----

        // getting list 
        $arrResult = AlienXml2Array::getCountKeys( '>wsa:To', $arrAll );
        $strResultBase64 = base64_encode( print_r( $arrResult, true ) );
        $strExpectedBase64 = 'QXJyYXkKKAogICAgWzBdID0+ID5FbnZlbG9wZT5zb2FwOkhlYWRlcj53c2E6VG8tMAogICAgWzFdID0+ID5FbnZlbG9wZT5zb2FwOkhlYWRlcj53c2E6VG8tMQopCg==';
        if ( self::err( __LINE__, $strArrPrintR, "getCountKeys( '>wsa:To', arr )", $strResultBase64, $strExpectedBase64 ) ) {
            return false;
        }

        // getting list 
        $arrResult = AlienXml2Array::getCountKeys( '>Success', $arrAll );
        $strResultBase64 = base64_encode( print_r( $arrResult, true ) );
        $strExpectedBase64 = 'QXJyYXkKKAogICAgWzBdID0+ID5FbnZlbG9wZT5zb2FwOkJvZHk+R2V0RG9jdW1lbnRSUz5TdWNjZXNzCikK';
        if ( self::err( __LINE__, $strArrPrintR, "getCountKeys( '>Success', arr )", $strResultBase64, $strExpectedBase64 ) ) {
            return false;
        }        

        return true;
    }
    
    
    static function main() {
        self::simple1();
        self::list1();
        self::list2();
        self::attribute1();
        self::soap1();
    }
}

TestAll::main();

die();