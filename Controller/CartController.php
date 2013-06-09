<?php

class CartController extends AppController
{
    public $uses = array('Watch');
        
    public function index()
    {
        if($this->Cart->cartEmpty() == true){
            $this->redirect(array('controller' => 'watches', 'action' => 'index'));
        }
        $items = $this->Session->read('Cart.items'); 
        $watches = $this->Watch->find('all', array('conditions' => array('id' => $items),
                                                   'fields' => array('id', 'stock_id', 'price', 'name')
                                                   )
                                      );
        $total = $this->Cart->getTotal($watches);
        $months = array_combine(range(1,12), range(1,12));
        $year = date('Y'); 
        for($i=date('Y'); $i<=date('Y')+10; $i++){
            $years[$i] = $i;
        }
        
        $this->set(compact('watches', 'total', 'months', 'years'));
    }
    
    public function add($id = null)
    {   
        if (!$this->Watch->exists($id)) {
            throw new NotFoundException(__('Invalid watch'));
        }
        $items = array();
        if($this->Session->check('Cart.items') == true){
            $items = $this->Session->read('Cart.items');
            if(in_array($id, $items)){
                $this->Session->setFlash('That item is already in your cart.');
                $this->redirect(array('controller' => 'watches', 'action' => 'index'));
            }
        }

        $items[] = $id; 
        $this->Session->write('Cart.items', $items);
        
        $this->redirect(array('action' => 'index'));
    }
    
    public function checkout()
    {
        if($this->request->is('post')){ 
            $amount = $this->request->data['Cart']['total'];
            $stripeToken = $this->request->data['stripeToken'];
            $data = array('amount' => $amount,
                          'stripeToken' => $stripeToken);
            $result = $this->Stripe->charge($data);
            if($result['stripe_paid'] == true){
                $items = $this->Session->read('Cart.items');
                foreach($items as $id){
                    $this->Watch->id = $id;
                    $this->Watch->saveField('active', 0);
                }
                $this->Session->delete('Cart.items');
                $this->Session->setFlash('Payment Received');
                $this->redirect(array('controller' => 'Watches', 'action' => 'index'));
            }
        }
        
    }
    
    public function remove($id = null)
    {
        if (!$this->Watch->exists($id)) {
            throw new NotFoundException(__('Invalid watch'));
        }

        if($this->Session->check('Cart.items') == true){
            $items = $this->Session->read('Cart.items'); 
            if(in_array($id, $items)){
                $key = array_search($id, $items);
                unset($items[$key]); 
                $this->Session->write('Cart.items', $items);
                $this->redirect(array('action' => 'index'));
            }
        }
    }
}
