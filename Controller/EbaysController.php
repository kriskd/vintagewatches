<?php
App::uses('AppController', 'Controller');

class EbaysController extends AppController
{
    public $components = array('Ebay');
    
    public $uses = array('User');
    
    public $token;
    
    public function beforeFilter()
    {
        // Put the ebayToken in session with something like http://stackoverflow.com/questions/17267232/include-a-subset-of-fields-in-the-auth-user-session
        $encodedToken = $this->User->field('ebayToken', array('id' => $this->Auth->user('id'))); 
        $this->token = $this->Ebay->decodeToken($encodedToken);
        
        parent::beforeFilter();
    }
    
    public function admin_index()
    {
        $this->Ebay->ebayHeaders['X-EBAY-API-CALL-NAME'] = 'GetSellerList';
        $xml = $this->Ebay->getSellerListXml($this->token);
        
        $results = $this->Ebay->HttpSocket->request([
            'method' => 'POST', 
            'uri' => Configure::read('eBay.apiUrl'),
            'header' => $this->Ebay->ebayHeaders,
            'body' => $xml
        ]);
        
        $xml = simplexml_load_string($results->body);
        //var_dump($xml->ItemArray); exit;
        foreach ($xml->ItemArray->Item as $item) {
            //var_dump($item->Title);
            //var_dump($item);
        }
        $this->set('items', $xml->ItemArray->Item);
    }
    
    public function admin_view($itemId)
    {
        $this->Ebay->ebayHeaders['X-EBAY-API-CALL-NAME'] = 'GetItem';
        $xml = $this->Ebay->getItemXml($this->token, $itemId);
        
        $results = $this->Ebay->HttpSocket->request([
            'method' => 'POST', 
            'uri' => Configure::read('eBay.apiUrl'),
            'header' => $this->Ebay->ebayHeaders,
            'body' => $xml
        ]);
        
        $xml = simplexml_load_string($results->body);
        //var_dump($xml->Item);
        $this->set('item', $xml->Item);
    }
}