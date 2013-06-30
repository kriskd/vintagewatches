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
    public function cartLink($title, $url = null, $options = array(), $confirmMessage = false, $controller = null)
    {
        $cart = new CartComponent(new ComponentCollection());
        $cart->initialize($controller);
        if($cart->cartEmpty() == true){
            return null;
        }
        return parent::link($title, $url, $options, $confirmMessage);
    }
    
    /**
     * Create nav link only when not on that page
     */
    public function navLink($title, $url = null, $options = array(), $confirmMessage = false, $controller = null)
    {
        if(is_string($url)){
            $url = Router::parse($this->url($url));
        }
        $urlController = isset($url['controller']) ? $url['controller'] : null;
        if(strcasecmp($controller->name, $urlController)==0){
            return null;
        }
        return '<li>' . parent::link($title, $url, $options, $confirmMessage) . '</li>';
    }
}