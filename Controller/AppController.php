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
    
    public $helpers = array('TwitterBootstrap' =>
                            array('className' => 'TwitterBootstrap.TwitterBootstrap'),
                            'Session', 'Text',
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
                               'Navigation'
                            );
    
    public $uses = array('Page', 'Watch', 'Brand');
    
    public $brandsWithWatches;
    
    /**
     * Send the Controller object to the View so Helpers can initialize a Component with it
     */
    public function beforeRender()
    {   
        $announcementListReponse = $this->request->query('code');
        switch($announcementListReponse) {
            case 1:
                $this->Session->setFlash('Thank you for subscribing.', 'success');
                break;
            case 2:
            case -2:
                $this->Session->setFlash('You have unsubscribed from the list.', 'success');
                break;
            case 3:
                $this->Session->setFlash('You are not QUITE subscribed yet. Please now check your email right now for the last step to confirm your subscription to our list!', 'info');
                break;
            case -1:
                $this->Session->setFlash('You are already on the list.', 'info');
                break;
            case -3:
                $this->Session->setFlash('Invalid email address, try again.', 'danger');
                break;
            case -4:
                $this->Session->setFlash('Email is required.', 'danger');
                break;
        }
        
        //Logged in
        $loggedIn = false;
        if($this->Auth->loggedIn()){
            $loggedIn = true;
        }
        
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
        
        //Set var to determine if we show fat footer
        $route = Router::parse($this->here); 
        $hideFatFooter = false;
        if (strcasecmp($route['controller'], 'orders')==0 && strcasecmp($route['action'], 'checkout')==0 ||
            strcasecmp($route['controller'], 'invoices')==0 && strcasecmp($route['action'], 'pay')==0 ||
            !empty($this->request->params['admin'])) {
            $hideFatFooter = true;
        }
        
        $hideAnalytics = false;
        if (prod() == true || $loggedIn == true ||
            strcasecmp($route['controller'], 'invoices')==0 && strcasecmp($route['action'], 'pay')==0 ||
            !empty($this->request->params['admin'])) {
            $hideAnalytics = true;
        }
        
        $vars = compact('loggedIn', 'navigation', 'storeOpen', 'cartEmpty', 'cartCount', 'cartItemIds',
                        'hideFatFooter', 'currentUrl', 'allBrands', 'recentWatches', 'hideAnalytics');
        
        $this->set(array('name' => $this->name, 'brandsWithWatches' => $this->brandsWithWatches) + $vars);
        parent::beforeRender();
    }
    
    public function beforeFilter()
    {
        $secure = array('orders/checkout', 'orders/totalCart.json', 'orders/getAddress.html', 'orders/getCountry.json', 'orders/checkout.json');
        
        //Redirect to non-secure if https and not on checkout
        if (prod() == true && (isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS'])=='on') && !in_array(trim($this->here, '/'), $secure)) {
	    $this->redirect('http://' . env('SERVER_NAME') . $this->here);
	}
        
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
}
