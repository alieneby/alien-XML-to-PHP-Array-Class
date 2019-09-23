<?php

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

require_once( 'AlienXml2Array.php' );

$arr = AlienXml2Array::string2array( $strXml );
print_r( $arr );

/*
RESULT:
    [>Envelope/namespace/0] => soap
    [>Envelope>soap:Header>UFO:To-0] => http://www.w3.org/2005/08/addressing/anonymous
    [>Envelope>soap:Header>UFO:To-1] => https://alien.de
    [>Envelope>soap:Header>UFO:From>UFO:Address] => https://alien.de
    [>Envelope>soap:Header>UFO:From] =>
    [>Envelope>soap:Header>UFO:Action] => https://alien.de/GetDocument
    [>Envelope>soap:Header>UFO:To-count] => 2
    [>Envelope>soap:Header>Rocket:To] => https://spacex.com
    [>Envelope>soap:Header>Rocket:Session>Rocket:SessionId] => Seance
    [>Envelope>soap:Header>Rocket:Session<TransactionStatusCode] => End
    [>Envelope>soap:Header] =>
    [>Envelope>soap:Body>GetDocumentRS>Success>ResponseData>StatusMessage>Text] => Nice UFO Sighting
    [>Envelope>soap:Body>GetDocumentRS>Success>ResponseData>StatusMessage<Code] => K225
    [>Envelope>soap:Body>GetDocumentRS>Success>ResponseData>Attachment>Content] => PDF data
    [>Envelope>soap:Body>GetDocumentRS>Success>ResponseData>Attachment<Encoding] => PDF
    [>Envelope>soap:Body>GetDocumentRS>Success>ResponseData>Attachment<Name] => confirmation
    [>Envelope>soap:Body>GetDocumentRS>Success>ResponseData>Operator] => UFO
    [>Envelope>soap:Body>GetDocumentRS>Success>ResponseData>ReservationReference<ID] => 575666999
    [>Envelope>soap:Body>GetDocumentRS>Success>ResponseData>ReservationReference<ReservationNumberModule] => 01
    [>Envelope>soap:Body>GetDocumentRS<Version] => 1.0
    [>Envelope] =>
*/
