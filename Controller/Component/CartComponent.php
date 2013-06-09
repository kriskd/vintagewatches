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
}
