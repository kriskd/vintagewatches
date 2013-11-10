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
    
    /**
     * Send the Controller object to the View so Helpers can initialize a Component with it
     */
    public function beforeRender()
    {
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
        
        //Brands with watches
        $brands = $this->Brand->getBrandsWithWatches();
        
        $vars = compact('loggedIn', 'navigation', 'storeOpen', 'cartEmpty', 'cartCount', 'cartItemIds', 'brands');
        
        $this->set(array('controller' => $this, 'name' => $this->name) + $vars);
        parent::beforeRender();
    }
    
    public function beforeFilter()
    {
        $secure = array('orders/checkout', 'orders/totalCart.json', 'orders/getAddress.html', 'orders/getCountry.json', 'orders/checkout.json');
        
        //Redirect to non-secure if https and not on checkout
        if (prod() == true && (isset($_SERVER['HTTPS']) || $_SERVER['HTTPS']) && !in_array(trim($this->here, '/'), $secure)) {
	    $this->redirect('http://' . env('SERVER_NAME') . $this->here);
	}
        
        $this->Cookie->domain = env('HTTP_BASE');
        
        if (empty($this->params['prefix'])) {
            //Not an admin page
            $this->Auth->allow($this->action);
        } 
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
}
