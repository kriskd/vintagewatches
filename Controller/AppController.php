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
                            'Number',
                            'Session',
                            'Html' => array('className' => 'MyHtml'),
                            'Form' => array('className' => 'MyForm'),
                            'Cart', 'Watch');
    
    public $components = array('Stripe' => array('className' => 'Stripe.Stripe'),
                               'DebugKit.Toolbar', 'Session', 'Cart', 'RequestHandler',
                               'Cookie',
                               'Auth' => array('authorize' => 'Controller',
                                    'loginAction' => array('controller' => 'users', 'action' => 'login', 'admin' => false),
                                    'loginRedirect' => array('controller' => 'orders', 'action' => 'index', 'admin' => true),
                                    'logoutRedirect' => array('controller' => 'users', 'action' => 'login', 'admin' => false),
                                    )
                            );
    
    /**
     * Compile LESS
     * Send the Controller object to the View so Helpers can initialize a Component with it
     */
    public function beforeRender()
    {   
        if(Configure::read('debug') > 0){
            App::import('Vendor', 'leafo/lessphp/lessc.inc');
            $lessc = new lessc();
            $less = getcwd() . DS . 'css' . DS . 'styles.less'; 
            $css = getcwd() . DS . 'css' . DS . 'styles.css'; 
            $lessc->checkedCompile($less,$css);
        }
        
        if($this->Auth->loggedIn()){
            $this->set('loggedIn', true);
        }
        
        $this->set('controller', $this);
        parent::beforeRender();
    }
    
    public function beforeFilter()
    {   
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
