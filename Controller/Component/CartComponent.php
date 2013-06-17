<?php
App::uses('Component', 'Controller');

class CartComponent extends Component
{
    public $components = array('Session');
    
    public function cartEmpty()
    {
        if($this->Session->check('Cart.items') == true){
            $items = $this->Session->read('Cart.items'); 
            if(!empty($items)){
                return false;
            }
        }
        return true;
    }
    
    public function cartItemCount()
    {
        if($this->Session->check('Cart.items') == true){
            $items = $this->Session->read('Cart.items'); 
            if(!empty($items)){
                return count($items);
            }
        }
        return null;
    }
    
    public function inCart($id = null)
    {
        if($this->Session->check('Cart.items') == true && $id != null){
            $items = $this->Session->read('Cart.items'); 
            if(in_array($id, $items)){
                return true;
            }
        }
        return false;
    }
    
    public function getSubTotal($watches = null)
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
