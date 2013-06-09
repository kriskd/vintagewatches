<?php
App::uses('HtmlHelper', 'View/Helper');
App::uses('CartComponent', 'Controller/Component');

class MyHtmlHelper extends HtmlHelper
{
    public function __construct(View $View, $settings = array())
    {
        parent::__construct($View, $settings);
    }
    
    /**
     * Create cart link only if items in the cart
     */
    public function cartLink($title, $url = null, $options = array(), $confirmMessage = false)
    {
        $cart = new CartComponent(new ComponentCollection());
        if($cart->cartEmpty() == true){
            return null;
        }
        return parent::link($title, $url, $options, $confirmMessage);
    }
}
