<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
App::uses('Controller', 'Controller');
App::uses('CakeEmail', 'Network/Email');
App::uses('String', 'Utility');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {
    
    public $helpers = array('Session', 'Text',
                            'Html' => array('className' => 'MyHtml'),
                            'Form' => array('className' => 'MyForm'),
                            'Number' => array('className' => 'MyNumber'),
                            'Watch');
    
    public $components = array('Stripe' => array('className' => 'Stripe.Stripe'),
                               'DebugKit.Toolbar', 'Session', 'Cart', 'RequestHandler',
                               'Cookie',
                               'Auth' => array('authorize' => 'Controller',
                                    'loginAction' => array('controller' => 'users', 'action' => 'login', 'admin' => false),
                                    'loginRedirect' => array('controller' => 'orders', 'action' => 'index', 'admin' => true),
                                    'logoutRedirect' => array('controller' => 'users', 'action' => 'login', 'admin' => false),
                                    ),
                               'Paginator',
                               'Navigation', 'Ebay'
                            );
    
    public $uses = array('Page', 'Watch', 'Brand');
    
    public $brandsWithWatches;
    
    public $route;
    
    public $token;

    public $scheme;
    
    /**
     * Send the Controller object to the View so Helpers can initialize a Component with it
     */
    public function beforeRender() {   
        //Page navigation
        $navigation = $this->Page->getNavigation();
        
        //Bool for store opened or closed
        $storeOpen = $this->Watch->storeOpen();
        
        //Bool for cart empty
        $cartEmpty = $this->Cart->cartEmpty();
        
        //Cart count
        $cartCount = $this->Cart->cartItemCount();
        
        //Watch IDs in cart
        $cartItemIds = $this->Cart->cartItemIds();
        
        //All brands for meta keywords
        $allBrands = $this->Brand->find('list');
        $allBrands = implode(',', $allBrands);
        
        //Current Url
        $currentUrl = 'http://' . env('SERVER_NAME') . $this->here;
        
        //Recent watches
        $recentWatches = $this->Watch->getWatches(3);
        
        $vars = compact('loggedIn', 'navigation', 'storeOpen', 'cartEmpty', 'cartCount', 'cartItemIds',
                        'currentUrl', 'allBrands', 'recentWatches');
        
        $this->set(array('name' => $this->name, 'brandsWithWatches' => $this->brandsWithWatches) + $vars);
        parent::beforeRender();
    }
    
    public function beforeFilter() {
        // All pages are now secure
        $this->secure();

        $this->route = Router::parse($this->request->here); 
        $here = Router::url($this->request->here, true);
        $this->scheme = parse_url($here, PHP_URL_SCHEME);
        
        //Logged in
        $loggedIn = false;
        if($this->Auth->user()){
            $loggedIn = true;
              
            if ($this->Ebay->checkToken($this->Auth->user())) {
                $encodedToken = $this->Auth->user('ebayToken'); 
                $this->token = $this->Ebay->decodeToken($encodedToken);
            }
        }
        
        //Set var to determine if we show fat footer, set it here so it can be manually changed in controllers.
        $hideFatFooter = false;
        if (strcasecmp($this->route['controller'], 'orders')==0 && strcasecmp($this->route['action'], 'checkout')==0 ||
            strcasecmp($this->route['controller'], 'invoices')==0 && strcasecmp($this->route['action'], 'pay')==0 ||
            !empty($this->request->params['admin'])) {
            $hideFatFooter = true;
        }
        
        $hideAnalytics = false;
        if (prod() == false || $loggedIn == true ||
            strcasecmp($this->route['controller'], 'invoices')==0 && strcasecmp($this->route['action'], 'pay')==0 ||
            !empty($this->request->params['admin'])) {
            $hideAnalytics = true;
        }
        
        $this->set(compact('hideFatFooter', 'hideAnalytics', 'loggedIn'));
        
        $this->Cookie->domain = env('HTTP_BASE');
        
        if (empty($this->params['prefix'])) {
            //Not an admin page
            $this->Auth->allow($this->action);
        }
                
        //Brands with watches
        $this->brandsWithWatches = $this->Brand->getBrandsWithWatches();
    }
    
    public function isAuthorized($user)
    {    
        if(empty($this->request->params['admin'])) return true;
        
        if($user) return true;
        
        if(in_array($this->action, array('login'))){
            $this->Auth->authError = 'You are already logged in.';
            return false;
        }
        
        return false;
    }
    
    public function array_merge_recursive_distinct(array &$array1, array &$array2)
    {
        $merged = $array1;
      
        foreach ($array2 as $key => &$value) {
            if (is_array ($value) && isset($merged[$key]) && is_array($merged[$key])) {
                $merged[$key] = $this->array_merge_recursive_distinct($merged[$key], $value);
            }
            else {
                $merged[$key] = $value;
            }
        }
      
        return $merged;
    }
    
    /**
     * Pass an invoice object
     * Return invoice total
     */
    public function total($invoice)
    {
        $itemTotal = array_reduce($invoice['InvoiceItem'], function($sum, $item){
            $sum += $item['amount'];
            return $sum;
        });
        
        return $itemTotal + $invoice['Invoice']['shippingAmount'];
    }
    
    public function secure()
    {
        if (prod() == true && (!isset($_SERVER['HTTPS']) || !$_SERVER['HTTPS'])) {
            $this->redirect('https://' . env('SERVER_NAME') . $this->here);
        }
    }
}
