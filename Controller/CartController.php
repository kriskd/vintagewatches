<?php

class CartController extends AppController
{
    public $uses = array('Watch');
    
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
        
        $this->redirect(array('action' => 'view'));
    }
    
    public function view()
    {
        if($this->Session->check('Cart.items') == false){
            $this->Session->setFlash('Your cart is empty.');
            $this->redirect(array('controller' => 'watches', 'action' => 'index'));
        }
        $items = $this->Session->read('Cart.items'); 
        $watches = $this->Watch->find('all', array('conditions' => array('id' => $items),
                                                   'fields' => array('stock_id', 'price', 'name')
                                                   )
                                      );
        $total = $this->_getTotal($watches); 
        $this->set(compact('watches', 'total'));
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
                $this->Session->delete('Cart.items');
                $this->Session->setFlash('Payment Received');
                $this->redirect(array('controller' => 'Watches', 'action' => 'index'));
            }
        }
        
    }
    
    protected function _getTotal($watches = null)
    {   
        if(empty($watches)){
            return null;
        } 
        return  array_reduce($watches, function($return, $item){ 
                                    if(isset($item['Watch']['price'])){
                                        $return += $item['Watch']['price'];
                                        return $return;
                                    }
                            });
    }
}
