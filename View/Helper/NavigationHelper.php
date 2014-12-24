<?php

class NavigationHelper extends AppHelper
{
    public $helpers = array('Html');

    /**
     * Create nav link only when not on that page and not in admin
     */
    public function storeLink($title, $url = null, $options = array(), $confirmMessage = false) {
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

        return $this->Html->tag('li', $this->Html->link($title, $url, $options, $confirmMessage));
    }

    public function adminLinks() {
        $html = '';

        $links = array(
            '<span class="glyphicon glyphicon-usd"></span> Orders' =>
                array('controller' => 'orders', 'action' => 'index', 'admin' => true),
            '<span class="glyphicon glyphicon-gift"></span> Coupons' =>
                array('controller' => 'coupons', 'action' => 'index', 'admin' => true),
            '<span class="glyphicon glyphicon-italic"></span> Invoices' =>
                array('controller' => 'invoices', 'action' => 'index', 'admin' => true),
             '<span class="glyphicon glyphicon-user"></span> Ebay' =>
                array('controller' => 'ebays', 'action' => 'index', 'admin' => true),
            '<span class="glyphicon glyphicon-cog"></span> Watches' =>
                array('controller' => 'watches', 'action' => 'index', 'admin' => true),
            '<span class="glyphicon glyphicon-star"></span> Brands' =>
                array('controller' => 'brands', 'action' => 'index', 'admin' => true),
            '<span class="glyphicon glyphicon-road"></span> Sources' =>
                array('controller' => 'sources', 'action' => 'index', 'admin' => true),
            '<span class="glyphicon glyphicon-sunglasses"></span> Owners' =>
                array('controller' => 'owners', 'action' => 'index', 'admin' => true),
            '<span class="glyphicon glyphicon-book"></span> Pages' =>
                array('controller' => 'pages', 'action' => 'index', 'admin' => true),
            '<span class="glyphicon glyphicon-envelope"></span> Contacts' =>
                array('controller' => 'contacts', 'action' => 'index', 'admin' => true),
            '<span class="glyphicon glyphicon-refresh"></span> Clear Blog Cache' =>
                array('controller' => 'blogs', 'action' => 'clear', 'admin' => true),
            '<span class="glyphicon glyphicon-off"></span> Logout' =>
                array('controller' => 'users', 'action' => 'logout', 'admin' => false),
        );

        foreach ($links as $link => $attrs) {
            $html .= $this->Html->tag('li',
                        $this->Html->link($link, $attrs, array('escape' => false))
                    );
        }

        return $html;
    }

    protected function _storeOpen() {
        return ClassRegistry::init('Watch')->storeOpen();
    }
}
