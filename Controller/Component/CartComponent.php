<?php
App::uses('Component', 'Controller');

class CartComponent extends Component
{
    public $components = array('Session');
    
    protected $items = null;
    
    public function initialize(Controller $controller)
    {
        if($this->Session->check('Cart.items') == true){
            $this->items = $this->Session->read('Cart.items');  
        }
    }
    
    public function cartEmpty()
    {
        if(!empty($this->items)){
            return false;
        }
        return true;
    }
    
    public function cartItemCount()
    {
        if(!empty($this->items)){
            return count($this->items);
        }
        return null;
    }
    
    public function inCart($id = null)
    {
        if(is_array($this->items) && in_array($id, $this->items)){
            return true;
        }
        return false;
    }
    
    public function getCartItems()
    {   
        return ClassRegistry::init('Watch')->getCartWatches($this->items);
    }
    
    public function getSubTotal()
    {
        $cartItems = $this->getCartItems(); 
        if(!empty($cartItems)){
            return  array_reduce($cartItems, function($return, $item){ 
                        if(isset($item['Watch']['price'])){
                            $return += $item['Watch']['price'];
                            return $return;
                        }
                });
        }
        return null;
    }
}
