<?php
App::uses('AppController', 'Controller');

class EbaysController extends AppController
{
    public $components = array('Ebay');
    
    public $uses = array('User');
    
    public function admin_items()
    {
        // Put the ebayToken in session with something like http://stackoverflow.com/questions/17267232/include-a-subset-of-fields-in-the-auth-user-session
        $encodedToken = $this->User->field('ebayToken', array('id' => $this->Auth->user('id'))); 
        $token = $this->Ebay->decodeToken($encodedToken); 
        $this->Ebay->ebayHeaders['X-EBAY-API-CALL-NAME'] = 'GetSellerList';
        $xml = $this->Ebay->getSellerListXml($token);
        
        $results = $this->Ebay->HttpSocket->request([
            'method' => 'POST',
            'uri' => Configure::read('eBay.apiUrl'),
            'header' => $this->Ebay->ebayHeaders,
            'body' => $xml
        ]);
        
        $xml = simplexml_load_string($results->body);
        
        $this->set('items', $xml->ItemArray->Item);
    }
}