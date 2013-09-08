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
        $url['admin'] = false;
        
        return parent::link($title, $url, $options, $confirmMessage);
    }
    
    /**
     * Create nav link only when not on that page and not in admin
     */
    public function navLink($title, $url = null, $options = array(), $confirmMessage = false)
    {
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
        
        return '<li>' . parent::link($title, $url, $options, $confirmMessage) . '</li>';
    }
    
    public function adminLink($title, $url = null, $options = array(), $confirmMessage = false, $controller = null)
    {
        $auth = new AuthComponent(new ComponentCollection());
        $auth->initialize($controller);
        if($this->params->prefix != 'admin' && !$auth->loggedIn()){
            return null;
        }
        $url['admin'] = true;
        
        return '<li>' . parent::link($title, $url, $options, $confirmMessage) . '</li>';
    }
    
    public function image($path, $options = array())
    {
        if (is_array($path)) {
            $path = DS . implode('/', $path);
        }
        
        return parent::image($path, $options);
    }
    
    public function thumbImage($watchId, $imageFilename, $options = array())
    {
        return $this->image(array('files', $watchId, 'thumbs', $imageFilename), $options);
    }
    
    /**
     * Returns primary image if one is designated or the first image if not
     */
    public function thumbImagePrimary($watch)
    {   
        if (!empty($watch['Image'])) {
            $images = $watch['Image'];
            $primary = array_reduce($images, function($primaryExists, $item){
                    if ($item['primary'] == 1) {
                        $primaryExists = $item;
                    }
                    return $primaryExists;
                }, null);
            $image = empty($primary) ? current($images) : $primary;
            return $this->image(array('files', $image['watch_id'], 'thumbs', $image['filename']));
        }
        return 'No Image Available';
    }
    
    public function watchImage($watchId, $imageFilename, $options = array())
    {   
        if (file_exists(WWW_ROOT . DS . 'files' . DS . $watchId . DS . 'image' . DS . $imageFilename)) { 
            return $this->image(array('files', $watchId, 'image', $imageFilename), $options);
        }
        return $this->image(array('files', $watchId, $imageFilename), $options);
    }
}
