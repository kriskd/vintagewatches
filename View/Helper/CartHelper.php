<?php
App::uses('CartComponent', 'Controller/Component');

class CartHelper extends AppHelper
{
    public function cartCount($before = null, $after = null, $controller = null)
    {
        $cart = new CartComponent(new ComponentCollection());
        $cart->initialize($controller);
        if($cart->cartItemCount() == null){
            return null;
        }
        return $before . $cart->cartItemCount() . $after;
    }
    
    public function inCart($id = null, $controller = null)
    {
        $cart = new CartComponent(new ComponentCollection());
        $cart->initialize($controller);
        if($cart->inCart($id)){
            return true;
        }
        return false;
    }
}