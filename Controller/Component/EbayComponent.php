<?php
App::uses('Component', 'Controller');
App::uses('HttpSocket', 'Network/Http');

class EbayComponent extends Component
{
    public $ebayHeaders = array();
    public $HttpSocket;
    
    public function initialize(Controller $controller)
    {
        $this->ebayHeaders = array(
            'X-EBAY-API-COMPATIBILITY-LEVEL' => '851',
            'X-EBAY-API-DEV-NAME' => Configure::read('eBay.devid'),
            'X-EBAY-API-APP-NAME' => Configure::read('eBay.appid'),
            'X-EBAY-API-CERT-NAME' => Configure::read('eBay.certid'),
            'X-EBAY-API-SITEID' => '0'
        );
        $this->HttpSocket = new HttpSocket(['ssl_allow_self_signed' => true]);
    }
    
    public function decodeToken($encodedToken)
    {
        $token = base64_decode($encodedToken); 
        return Security::rijndael($token, Configure::read('Security.cipherSeed').Configure::read('Security.cipherSeed'), 'decrypt');
    }
    
    public function sessionIdXml($runame)
    {
        $xml = <<<XML
<?xml version="1.0" encoding="utf-8"?>
<GetSessionIDRequest xmlns="urn:ebay:apis:eBLBaseComponents">
<RuName>{$runame}</RuName>
</GetSessionIDRequest>
XML;

        return $xml;
    }
    
    public function fetchTokenXml($ebaySessionId)
    {
        $xml = <<<XML
<?xml version="1.0" encoding="utf-8"?>
<FetchTokenRequest xmlns="urn:ebay:apis:eBLBaseComponents">
  <SessionID>{$ebaySessionId}</SessionID>
</FetchTokenRequest>
XML;
        return $xml;
    }
    
    public function getSellerListXml($token)
    {
        $endTimeFrom = date('Y-m-d H:i:s', strtotime('-30 day'));
        $endTimeTo = date('Y-m-d H:i:s');
        $xml = <<<XML
<?xml version="1.0" encoding="utf-8"?>
<GetSellerListRequest xmlns="urn:ebay:apis:eBLBaseComponents">
<RequesterCredentials>
<eBayAuthToken>{$token}</eBayAuthToken>
</RequesterCredentials>
<Pagination ComplexType="PaginationType">
<EntriesPerPage>25</EntriesPerPage>
<PageNumber>1</PageNumber>
</Pagination>
<EndTimeFrom>{$endTimeFrom}</EndTimeFrom>
<EndTimeTo>{$endTimeTo}</EndTimeTo>
<Sort>1</Sort>
<GranularityLevel>Coarse</GranularityLevel>
<DetailLevel>ReturnAll</DetailLevel>
</GetSellerListRequest>
XML;
        return $xml;
    }
    
    public function getItemXml($token, $itemId)
    {
        $xml = <<<XML
<?xml version="1.0" encoding="utf-8"?>
<GetItemRequest xmlns="urn:ebay:apis:eBLBaseComponents">
<RequesterCredentials>
<eBayAuthToken>{$token}</eBayAuthToken>
</RequesterCredentials>
<ItemID>{$itemId}</ItemID>
<IncludeWatchCount>1</IncludeWatchCount>
<IncludeCrossPromotion>1</IncludeCrossPromotion>
<IncludeItemSpecifics>1</IncludeItemSpecifics>
<IncludeTaxTable>0</IncludeTaxTable>
<DetailLevel>ItemReturnAttributes</DetailLevel>
<Version>851</Version>
</GetItemRequest>
XML;
        return $xml;
    }
}