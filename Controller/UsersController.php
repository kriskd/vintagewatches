<?php

class UsersController extends AppController
{
    public $runame;
    
    public $components = array('Ebay');
    
    public function beforeFilter()
    {
        $this->runame = Configure::read('eBay.runame');
                
        parent::beforeFilter();
    }
    
    public function login()
    {
        $this->secure();
        
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
            $this->Ebay->ebayHeaders['X-EBAY-API-CALL-NAME'] = 'FetchToken';
            
            $ebaySessionId = $this->Session->read('ebaySessionId'); 
            
            $xml = $this->Ebay->fetchTokenXml($ebaySessionId);

            $results = $this->Ebay->HttpSocket->request([
                'method' => 'POST',
                'uri' => Configure::read('eBay.apiUrl'),
                'header' => $this->Ebay->ebayHeaders,
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
            $this->Session->write('Auth.User.ebayToken', $token);
            $this->Session->write('Auth.User.ebayTokenExpy', $expiration);
            $this->Session->delete('ebaySessionId');
            
            $this->redirect(array('controller' => 'orders', 'action' => 'index', 'admin' => true));
        } else { 
            $this->Ebay->ebayHeaders['X-EBAY-API-CALL-NAME'] = 'GetSessionID';
            
            $xml = $this->Ebay->sessionIdXml($this->runame);

            $results = $this->Ebay->HttpSocket->request([
                'method' => 'POST',
                'uri' => Configure::read('eBay.apiUrl'),
                'header' => $this->Ebay->ebayHeaders,
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