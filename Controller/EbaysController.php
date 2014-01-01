<?php
App::uses('AppController', 'Controller');

class EbaysController extends AppController
{
    public $uses = array('Invoice');
    
    /**
     * List of recent eBay Auctions
     * Add a property for whether auction has been invoiced by checking for eBay ID in InvoiceItems and
     * add a property for whether auction can be invoiced determined by checking if it's currently invoiced and has a valid email
     */
    public function admin_index()
    {
        if (!$this->Ebay->checkToken($this->Auth->user())) {
            $this->redirect(array('controller' => 'users', 'action' => 'ebay', 'admin' => true));
        }
        $itemIds = $this->Invoice->InvoiceItem->find('list', array('fields' => 'itemid')); 
        $xml = $this->Ebay->getSellerList($this->token);
        foreach ($xml->ItemArray->Item as $item) { 
            $item->Invoiced = false;
            $ebayItemID = (string)$item->ItemID; 
            if (in_array($ebayItemID, $itemIds)) {
                $item->Invoiced = true;
            } 
            $email = (string)$item->SellingStatus->HighBidder->Email; 
            $item->AllowInvoice = false; 
            if (stristr($email, '@')==true && $item->Invoiced==0) { 
                $item->AllowInvoice = true;
            } 
        }
        $this->set('items', $xml->ItemArray->Item);
    }
    
    public function admin_view($itemId)
    {   
        $xml = $this->Ebay->getItem($this->token, $itemId);
        
        $this->set('item', $xml->Item);
    }
}