<?php 

$strXml = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
  <soap:Envelope 
    xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/" 
    xmlns:awsse="http://xml.amadeus.com/2010/06/Session_v3" 
    xmlns:wsa="http://www.w3.org/2005/08/addressing">
    <soap:Header>
      <wsa:To>http://www.w3.org/2005/08/addressing/anonymous</wsa:To>
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

require_once( 'AlienXml2Array.php');

$arr = AlienXml2Array::string2array( $strXml );
print_r( $arr );


/*
RESULT:

    [//Envelope/namespace/0] => soap
    [//Envelope/Header/namespace] => soap
    [//Envelope/Header/To/namespace] => wsa
    [//Envelope/Header/To//] => http://www.w3.org/2005/08/addressing/anonymous
    [//Envelope/Header/From/namespace] => wsa
    [//Envelope/Header/From/Address/namespace] => wsa
    [//Envelope/Header/From/Address//] => https://alien.de
    [//Envelope/Header/Action/namespace] => wsa
    [//Envelope/Header/Action//] => https://alien.de/GetDocument
    [//Envelope/Header/MessageID/namespace] => wsa
    [//Envelope/Header/MessageID//] => urn:uuid:88ba5cd2
    [//Envelope/Header/RelatesTo/namespace] => wsa
    [//Envelope/Header/RelatesTo//RelationshipType] => http://www.w3.org/2005/08/addressing/reply
    [//Envelope/Header/RelatesTo//] => 7ad4672f
    [//Envelope/Header/Session/namespace] => awsse
    [//Envelope/Header/Session/SessionId/namespace] => awsse
    [//Envelope/Header/Session/SessionId//] => 0RFGSMTJ8I
    [//Envelope/Header/Session/SequenceNumber/namespace] => awsse
    [//Envelope/Header/Session/SequenceNumber//] => 1
    [//Envelope/Header/Session/SecurityToken/namespace] => awsse
    [//Envelope/Header/Session/SecurityToken//] => 25UYOS54ETP6DISXGHXUDHJON
    [//Envelope/Header/Session//TransactionStatusCode] => End
    [//Envelope/Body/namespace] => soap
    [//Envelope/Body/GetDocumentRS/Success/ResponseData/StatusMessage/Text//] => Darst/RBE ok / S111 Druck LB
    [//Envelope/Body/GetDocumentRS/Success/ResponseData/StatusMessage//Code] => K225
    [//Envelope/Body/GetDocumentRS/Success/ResponseData/Attachment/Content//] => PDF DATA CUTTED!!!!!
    [//Envelope/Body/GetDocumentRS/Success/ResponseData/Attachment//Encoding] => PDF
    [//Envelope/Body/GetDocumentRS/Success/ResponseData/Attachment//Name] => Bestaetigung
    [//Envelope/Body/GetDocumentRS/Success/ResponseData/Operator//] => UFO
    [//Envelope/Body/GetDocumentRS/Success/ResponseData/ReservationReference//ID] => 575666999
    [//Envelope/Body/GetDocumentRS/Success/ResponseData/ReservationReference//ReservationNumberModule] => 01
    [//Envelope/Body/GetDocumentRS//Version] => 1.0
      
*/