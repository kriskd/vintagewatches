<?php
App::uses('Presenter', 'CakePHP-GiftWrap.Presenter');

class NavigationPresenter extends Presenter
{
    public $helpers = array('Html');
    
    public function test()
    {
        //$this->set('test', 'test');
        return 'test';
    }
    
    /**
     * Create nav link only when not on that page and not in admin
     */
    public function storeLink($title, $url = null, $options = array(), $confirmMessage = false)
    {
        var_dump('storeLink');
        if ($storeOpen == false) return null;
        
        if(is_string($url)){
            $url = Router::parse($this->url($url));
        } 
        $linkController = isset($url['controller']) ? $url['controller'] : null;
        $linkAction = isset($url['action']) ? $url['action'] : null;
        $here = Router::parse(Router::url($this->here));
        $currentController = $here['controller'];
        $currentAction = $here['action'];
        if(strcasecmp($linkController, $currentController)==0 &&
           strcasecmp($linkAction, $currentAction)==0 &&
           $this->params->prefix != 'admin'){
            return null;
        }
        $url['admin'] = false;
        
        return '<li>' . $this->Html->link($title, $url, $options, $confirmMessage) . '</li>';
    }
    
    /**
     * Create cart link only if items in the cart
     */
    public function cartLink($title, $url = null, $options = array(), $confirmMessage = false, $controller = null)
    {
        var_dump('cartLink');
        if ($storeOpen == false) return null;
        
        $cart = new CartComponent(new ComponentCollection());
        $cart->initialize($controller);
        if($cart->cartEmpty() == true){
            return null;
        }
        $url['admin'] = false;
        
        return $this->Html->link($title, $url, $options, $confirmMessage);
    }
}
