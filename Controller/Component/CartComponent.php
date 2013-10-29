<?php
App::uses('Component', 'Controller');

class CartComponent extends Component
{
    public $components = array('Session');
    
    protected $items = array();
    protected $shipping = 0;
    protected $total = 0;
    
    public function initialize(Controller $controller)
    {
        if($this->Session->check('Cart.items') == true){
            $this->items = $this->Session->read('Cart.items');  
        }
        if($this->Session->check('Cart.shipping') == true){
            $this->shipping = $this->Session->read('Cart.shipping');  
        }
        if($this->Session->check('Cart.total') == true){
            $this->total = $this->Session->read('Cart.total');  
        }
    }
    
    /**
     * Clear the Cart session, reset $items array
     */
    public function emptyCart()
    {
        $this->items = array();
        $this->Session->delete('Cart');
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
    
    /**
     * Returns an array of Watch IDs in the cart
     */
    public function cartItemIds()
    {
        return $this->items;
    }
    
    /**
     * Add an item to the cart
     */
    public function add($id)
    {
        $this->items[] = $id; 
        $this->Session->write('Cart.items', $this->items);
    }
    
    /**
     * Remove an item from the cart
     */
    public function remove($id)
    {
        if(in_array($id, $this->items)){
            $key = array_search($id, $this->items);
            unset($this->items[$key]); 
            $this->Session->write('Cart.items', $this->items);
        }
    }
    
    public function inCart($id = null)
    {
        if(is_array($this->items) && in_array($id, $this->items)){
            return true;
        }
        return false;
    }
    
    public function getShipping()
    {
        return $this->shipping;
    }
    
    public function setShipping($shipping)
    {
        $this->shipping = $shipping;
        $this->Session->write('Cart.shipping', $this->shipping);
    }
    
    public function getTotal()
    {
        return $this->total;
    }
    
    public function setTotal($subTotal)
    {
        $this->total = $subTotal + $this->shipping;
        $this->Session->write('Cart.total', $this->total);
    }
}
