<?php

class NavigationHelper extends AppHelper
{
    public $helpers = array('Html');
    
    /**
     * Create nav link only when not on that page and not in admin
     */
    public function storeLink($title, $url = null, $options = array(), $confirmMessage = false)
    {
        if ($this->_storeOpen() == false) return null;
        
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
    
    protected function _storeOpen()
    {
        return ClassRegistry::init('Watch')->storeOpen();
    }
}
