<?php
require_once( 'AlienXml2Array.php' );

class TestAll {

    static function err( $strLine, $strArrPrintR, $strCommand, $strResultBase64, $strExpectedBase64 ) {
        if ( $strResultBase64 == $strExpectedBase64 ) return false;
        echo "Line #$strLine ERROR:\n";
        echo "$strArrPrintR\n";
        echo "Cmd: $strCommand\n";
        echo "Result Base64: $strResultBase64\n";
        echo "Result:\n" . base64_decode( $strResultBase64 ) . "\n";
        echo "Expected:\n" . base64_decode( $strExpectedBase64 ) . "\n\n----------------------\n";
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

        // CREATION OF ARRAY
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
        for ( $i = 0; $i < count( $arrElementsH ); $i++ ) {
            $strKey = $arrElementsH[ $i ];
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
        return true;
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
            <item/>
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
    xmlns:Rocket="http://xml.amadeus.com/2010/06/Session_v3" 
    xmlns:UFO="http://www.w3.org/2005/08/addressing">
    <soap:Header>
      <UFO:To>http://www.w3.org/2005/08/addressing/anonymous</UFO:To>
      <UFO:To>https://alien.de</UFO:To>
      <Rocket:To>https://spacex.com</Rocket:To>
      <UFO:From>
        <UFO:Address>https://alien.de</UFO:Address>
      </UFO:From>
      <UFO:Action>https://alien.de/GetDocument</UFO:Action>
      <Rocket:Session TransactionStatusCode="End">
        <Rocket:SessionId>Seance</Rocket:SessionId>
      </Rocket:Session>
    </soap:Header>
    <soap:Body>
      <GetDocumentRS xmlns="http://xml/Document_v1" Version="1.0">
        <Success>
          <ResponseData>
            <StatusMessage Code="K225">
              <Text>Nice UFO Sighting</Text>
            </StatusMessage>
            <Attachment Encoding="PDF" Name="confirmation">
              <Content>PDF data</Content>
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
        $strExpectedBase64 = 'QXJyYXkKKAogICAgWz5FbnZlbG9wZV0gPT4gCiAgICBbPkVudmVsb3BlL25hbWVzcGFjZS8wXSA9PiBzb2FwCiAgICBbPkVudmVsb3BlPnNvYXA6Qm9keT5HZXREb2N1bWVudFJTPFZlcnNpb25dID0+IDEuMAogICAgWz5FbnZlbG9wZT5zb2FwOkJvZHk+R2V0RG9jdW1lbnRSUz5TdWNjZXNzPlJlc3BvbnNlRGF0YT5BdHRhY2htZW50PEVuY29kaW5nXSA9PiBQREYKICAgIFs+RW52ZWxvcGU+c29hcDpCb2R5PkdldERvY3VtZW50UlM+U3VjY2Vzcz5SZXNwb25zZURhdGE+QXR0YWNobWVudDxOYW1lXSA9PiBjb25maXJtYXRpb24KICAgIFs+RW52ZWxvcGU+c29hcDpCb2R5PkdldERvY3VtZW50UlM+U3VjY2Vzcz5SZXNwb25zZURhdGE+QXR0YWNobWVudD5Db250ZW50XSA9PiBQREYgZGF0YQogICAgWz5FbnZlbG9wZT5zb2FwOkJvZHk+R2V0RG9jdW1lbnRSUz5TdWNjZXNzPlJlc3BvbnNlRGF0YT5PcGVyYXRvcl0gPT4gVUZPCiAgICBbPkVudmVsb3BlPnNvYXA6Qm9keT5HZXREb2N1bWVudFJTPlN1Y2Nlc3M+UmVzcG9uc2VEYXRhPlJlc2VydmF0aW9uUmVmZXJlbmNlPElEXSA9PiA1NzU2NjY5OTkKICAgIFs+RW52ZWxvcGU+c29hcDpCb2R5PkdldERvY3VtZW50UlM+U3VjY2Vzcz5SZXNwb25zZURhdGE+UmVzZXJ2YXRpb25SZWZlcmVuY2U8UmVzZXJ2YXRpb25OdW1iZXJNb2R1bGVdID0+IDAxCiAgICBbPkVudmVsb3BlPnNvYXA6Qm9keT5HZXREb2N1bWVudFJTPlN1Y2Nlc3M+UmVzcG9uc2VEYXRhPlN0YXR1c01lc3NhZ2U8Q29kZV0gPT4gSzIyNQogICAgWz5FbnZlbG9wZT5zb2FwOkJvZHk+R2V0RG9jdW1lbnRSUz5TdWNjZXNzPlJlc3BvbnNlRGF0YT5TdGF0dXNNZXNzYWdlPlRleHRdID0+IE5pY2UgVUZPIFNpZ2h0aW5nCiAgICBbPkVudmVsb3BlPnNvYXA6SGVhZGVyXSA9PiAKICAgIFs+RW52ZWxvcGU+c29hcDpIZWFkZXI+Um9ja2V0OlNlc3Npb248VHJhbnNhY3Rpb25TdGF0dXNDb2RlXSA9PiBFbmQKICAgIFs+RW52ZWxvcGU+c29hcDpIZWFkZXI+Um9ja2V0OlNlc3Npb24+Um9ja2V0OlNlc3Npb25JZF0gPT4gU2VhbmNlCiAgICBbPkVudmVsb3BlPnNvYXA6SGVhZGVyPlJvY2tldDpUb10gPT4gaHR0cHM6Ly9zcGFjZXguY29tCiAgICBbPkVudmVsb3BlPnNvYXA6SGVhZGVyPlVGTzpBY3Rpb25dID0+IGh0dHBzOi8vYWxpZW4uZGUvR2V0RG9jdW1lbnQKICAgIFs+RW52ZWxvcGU+c29hcDpIZWFkZXI+VUZPOkZyb21dID0+IAogICAgWz5FbnZlbG9wZT5zb2FwOkhlYWRlcj5VRk86RnJvbT5VRk86QWRkcmVzc10gPT4gaHR0cHM6Ly9hbGllbi5kZQogICAgWz5FbnZlbG9wZT5zb2FwOkhlYWRlcj5VRk86VG8tMF0gPT4gaHR0cDovL3d3dy53My5vcmcvMjAwNS8wOC9hZGRyZXNzaW5nL2Fub255bW91cwogICAgWz5FbnZlbG9wZT5zb2FwOkhlYWRlcj5VRk86VG8tMV0gPT4gaHR0cHM6Ly9hbGllbi5kZQogICAgWz5FbnZlbG9wZT5zb2FwOkhlYWRlcj5VRk86VG8tY291bnRdID0+IDIKKQo=';
        if ( $strExpectedBase64 != $strArrBase64 ) {
            echo 'Line #' . __LINE__ . " ERROR:\n";
            echo "XML: \n$strXml\nBase64: $strArrBase64\n$strArrPrintR\n";
            echo "Expected was \n" . base64_decode( $strExpectedBase64 ) . "\n\n----------------------\n";
            return false;
        }

        // ----- findFirstValue ---- START ----

        // findFirstValue
        $nResult = AlienXml2Array::findFirstValue( '>Envelope>soap:Header>Rocket:Session>Rocket:SessionId', $arrAll );
        $strResultBase64 = base64_encode( $nResult );
        $strExpectedBase64 = 'U2VhbmNl';
        if ( self::err( __LINE__, $strArrPrintR, "findFirstValue( '>Envelope>soap:Header>Rocket:Session>Rocket:SessionId', arr )", $strResultBase64, $strExpectedBase64 ) ) {
            return false;
        }

        // findFirstValue
        $nResult = AlienXml2Array::findFirstValue( '.*>soap:Header>.*>Rocket:SessionId', $arrAll );
        $strResultBase64 = base64_encode( $nResult );
        $strExpectedBase64 = 'U2VhbmNl';
        if ( self::err( __LINE__, $strArrPrintR, "findFirstValue( '.*>soap:Header>.*>Rocket:SessionId', arr )", $strResultBase64, $strExpectedBase64 ) ) {
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

        // key
        $nResult = AlienXml2Array::findFirstKey( ':SessionId', $arrAll );
        $strResultBase64 = base64_encode( $nResult );
        $strExpectedBase64 = 'PkVudmVsb3BlPnNvYXA6SGVhZGVyPlJvY2tldDpTZXNzaW9uPlJvY2tldDpTZXNzaW9uSWQ=';
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
        $nResult = AlienXml2Array::getCount( '>Envelope>soap:Header>UFO:To', $arrAll );
        $strResultBase64 = base64_encode( $nResult );
        $strExpectedBase64 = 'Mg==';
        if ( self::err( __LINE__, $strArrPrintR, "getCount( '>Envelope>soap:Header>UFO:To', arr )", $strResultBase64, $strExpectedBase64 ) ) {
            return false;
        }

        // count list values
        $nResult = AlienXml2Array::getCount( '>UFO:To', $arrAll );
        $strResultBase64 = base64_encode( $nResult );
        $strExpectedBase64 = 'Mg==';
        if ( self::err( __LINE__, $strArrPrintR, "getCount( '>UFO:To', arr )", $strResultBase64, $strExpectedBase64 ) ) {
            return false;
        }

        /// ----- getting list ---- START ----

        // getting list 
        $arrResult = AlienXml2Array::getCountKeys( '>UFO:To', $arrAll );
        $strResultBase64 = base64_encode( print_r( $arrResult, true ) );
        $strExpectedBase64 = 'QXJyYXkKKAogICAgWzBdID0+ID5FbnZlbG9wZT5zb2FwOkhlYWRlcj5VRk86VG8tMAogICAgWzFdID0+ID5FbnZlbG9wZT5zb2FwOkhlYWRlcj5VRk86VG8tMQopCg==';
        if ( self::err( __LINE__, $strArrPrintR, "getCountKeys( '>UFO:To', arr )", $strResultBase64, $strExpectedBase64 ) ) {
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
