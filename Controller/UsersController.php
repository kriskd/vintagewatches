<?php
App::uses('HttpSocket', 'Network/Http');

class UsersController extends AppController
{
    public $headers = array();
    public $runame;
    public $HttpSocket;
    
    public function beforeFilter()
    {
        $this->headers = array(
                    'X-EBAY-API-COMPATIBILITY-LEVEL' => '851',
                    'X-EBAY-API-DEV-NAME' => Configure::read('eBay.devid'),
                    'X-EBAY-API-APP-NAME' => Configure::read('eBay.appid'),
                    'X-EBAY-API-CERT-NAME' => Configure::read('eBay.certid'),
                    'X-EBAY-API-SITEID' => '0'
                );
        $this->runame = Configure::read('eBay.runame');
        $this->HttpSocket = new HttpSocket(['ssl_allow_self_signed' => true]);
                
        parent::beforeFilter();
    }
    
    public function login()
    { 
        if($this->Auth->loggedIn() == true){ 
            $this->redirect($this->Auth->redirectUrl());
        }
        if($this->request->is('post') || $this->request->is('put')){
            if($this->Auth->login()){
                $this->redirect($this->Auth->redirectUrl());
            }
            $this->Session->setFlash('Username or password is incorrect.');
        }
        
        $hideFatFooter = true;
        $hideAnalytics = true;
        $this->set(compact('hideFatFooter', 'hideAnalytics'));
    }
    
    public function logout()
    {
        $this->redirect($this->Auth->logout());
    }
    
    /**
     * Get eBay token and write to table
     */
    public function admin_ebay()
    {
        if ($this->Session->check('ebaySessionId')) { 
            $ebaySessionId = $this->Session->read('ebaySessionId'); 
            
            $this->headers['X-EBAY-API-CALL-NAME'] = 'FetchToken';
            
            $xml = <<<XML
<?xml version="1.0" encoding="utf-8"?>
<FetchTokenRequest xmlns="urn:ebay:apis:eBLBaseComponents">
  <SessionID>{$ebaySessionId}</SessionID>
</FetchTokenRequest>
XML;

            $results = $this->HttpSocket->request([
                'method' => 'POST',
                'uri' => Configure::read('eBay.apiUrl'),
                'header' => $this->headers,
                'body' => $xml
            ]);
            
            $xml = simplexml_load_string($results->body);
            
            if (strcasecmp($xml->Ack, 'Failure')==0) {
                $this->Session->delete('ebaySessionId');
                $this->redirect(array('action' => 'ebay', 'admin' => true));
            }
            
            $expiration = $xml->HardExpirationTime;
            $token = $xml->eBayAuthToken;
            $expiration = date('Y-m-d H:i:s', strtotime($expiration));
            $token = base64_encode(Security::rijndael($token, Configure::read('Security.cipherSeed').Configure::read('Security.cipherSeed'), 'encrypt'));

            $userid = $this->Auth->user('id');
            $this->User->updateAll(array(
                                        'ebayToken' => '"'.$token.'"',
                                        'ebayTokenExpy' => '"'.$expiration.'"'
                                        ),
                                   array(
                                        'id' => $userid
                                   )
                                );
            // How to decode
            //$token = base64_decode($token);
            //$token = Security::rijndael($token, Configure::read('Security.cipherSeed').Configure::read('Security.cipherSeed'), 'decrypt');
            //var_dump($token);
            $this->redirect(array('controller' => 'orders', 'action' => 'index', 'admin' => true));
        } else { 
            $this->headers['X-EBAY-API-CALL-NAME'] = 'GetSessionID';
    
            $xml = <<<XML
<?xml version="1.0" encoding="utf-8"?>
<GetSessionIDRequest xmlns="urn:ebay:apis:eBLBaseComponents">
<RuName>{$this->runame}</RuName>
</GetSessionIDRequest>
XML;

            $results = $this->HttpSocket->request([
                'method' => 'POST',
                'uri' => Configure::read('eBay.apiUrl'),
                'header' => $this->headers,
                'body' => $xml
            ]);
            
            $xml = simplexml_load_string($results->body);
            
            $sessionID = (string)$xml->SessionID;
            $this->Session->write('ebaySessionId', $sessionID);
            
            $this->redirect(Configure::read('eBay.signinUrl').'?SignIn&RuName='.$this->runame.'&SessID='.urlencode($sessionID));
        }
        
        $this->autoRender=false;
    }
}