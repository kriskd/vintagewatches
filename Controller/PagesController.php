<?php
/**
 * Static content controller.
 *
 * This file will render views from views/pages/
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
App::uses('AppController', 'Controller');
App::uses('HttpSocket', 'Network/Http');

/**
 * Static content controller
 *
 * Override this controller by placing a copy in controllers directory of an application
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers/pages-controller.html
 */
class PagesController extends AppController {

/**
 * Controller name
 *
 * @var string
 */
	public $name = 'Pages';
	
	public $paginate = array(
			'limit' => 10,
			'order' => array(
				'Page.name'
			),
			'recursive' => -1
		);
    
	public $uses = array('Page', 'Content', 'Watch');
	

/**
 * Displays a view
 *
 * @param mixed What page to display
 * @return void
 */
	public function display() { 
		$path = func_get_args();
		if (empty($path)) {
			$this->redirect('/');
		}
		$slug = current($path); //var_dump(strcasecmp($slug, 'sitemap')); exit;
		
		//The router passes in the slug "home" for the homepage
		if (strcasecmp($slug, 'home')==0) { 
			$this->set('title', 'Fine timepieces at reasonable prices from a name you trust.');
			//Get the hompeage content and send to view
			$page = $this->Page->find('first', array('conditions' => array('homepage' => 1)));
			$this->set(compact('page'));
			//Get all active watches that have images
			$watches = $this->Watch->getWatches(null, true);
			//If we don't have watches, render the "blog" style homepage
			if (empty($watches)) {
				$this->render('page');
			//Otherwise send the watches to the view and render homepage carousel
			} else {
				$this->set(compact('watches'));
				$this->render('home');
			}
		} elseif (strcasecmp($slug, 'sitemap')==0) { 
			App::import('Vendor', 'zeroasterisk/CakePHP-ArrayToXml-Lib/libs/array_to_xml');
			$watchXml = $pageXml = array();
			
			$watches = $this->Watch->getWatches(); 
			foreach($watches as $watch) {
				$watchXml[] = array(
						'url' => array(
							'loc' => 'http://' . env('SERVER_NAME') . DS . 'watches' . DS . 'view' . DS . $watch['Watch']['id'],
							'lastmod' => $watch['Watch']['modified']
						)
					);
			}
			
			$pages = $this->Page->getNavigation(); 
			foreach ($pages as $page) {
				$pageXml[] = array(
					'url' => array(
						'loc' => 'http://' . env('SERVER_NAME') . DS . 'pages' . DS . $page['Page']['slug'],
						'lastmod' => $page['Page']['modified']
					)	
				);
			}
			$pageXml[] = array(
				'url' => array(
					'loc' => 'http://' . env('SERVER_NAME') . DS . 'orders'
				)
			);
			$pageXml[] = array(
				'url' => array(
					'loc' => 'http://' . env('SERVER_NAME') . DS . 'contact-us'
				)
			);
			
			$xmlString = ArrayToXml::simplexml(array_merge($watchXml, $pageXml), 'urlset', array('xmlns' => 'http://www.sitemaps.org/schemas/sitemap/0.9')); 
			
			$this->set('xml', $xmlString);
			$this->render('sitemap');
			$this->layout = 'xml';
		//Standard content page
		} else {
			$page = $this->Page->find('first', array('conditions' => array('slug' => $slug)));
			if (!empty($page)) {
				$title = $page['Page']['name'];
				$this->set(compact('page', 'title'));
			}
			$this->render('page');
		}
		/*$count = count($path);
		if (!$count) {
			$this->redirect('/');
		}
		$page = $subpage = $title_for_layout = null;

		if (!empty($path[0])) {
			$page = $path[0];
		}
		if (!empty($path[1])) {
			$subpage = $path[1];
		}
		if (!empty($path[$count - 1])) {
			$title_for_layout = Inflector::humanize($path[$count - 1]);
		}
		$this->set(compact('page', 'subpage', 'title_for_layout'));
		$this->render(implode('/', $path));*/
	}
	
	public function admin_index() {
		$pages = $this->Paginator->paginate('Page');
		$this->set('pages', $pages);
	}
	
	public function admin_edit($id = null) {
		if (!$this->Page->exists($id)) {
			throw new NotFoundException(__('Invalid page'));
		}
        
		if ($this->request->is('post') || $this->request->is('put')) { 
		    if ($this->Page->saveAssociated($this->request->data)) {
			$this->Session->setFlash(__('The page has been saved'), 'success');
			$this->redirect(array('action' => 'edit', $id));
		    } else {
			$this->Session->setFlash(__('The page could not be saved. Please, try again.'), 'danger');
		    }
		}
        
		$options = array('conditions' => array('Page.' . $this->Page->primaryKey => $id));
		$this->request->data = $this->Page->find('first', $options);
		$this->set('page', $this->Page->find('first', $options));
	}
    
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->Page->create();
			if ($this->Page->saveAssociated($this->request->data)) {
				$this->Session->setFlash('Page ' . $this->Page->getInsertID() . ' has been created', 'success');
				$this->redirect(array('action' => 'edit', $this->Page->getInsertID(), 'admin' => true));
			} else {
				$this->Session->setFlash(__('The page could not be saved. Please, try again.'), 'danger');
			}
		}
	}

    /**
     * Handle posting to non-secure mailing list url
     */
    public function mailinglist() {
        if ($this->request->is('ajax')) {
            $email = $this->request->query['data']['Page']['email'];
            $unsub = $this->request->query['data']['Page']['unsub'];
            $HttpSocket = new HttpSocket();

            $data = array(
                'list' => 'bruce',
                'domain' => 'brucesvintagewatches.com',
                'url' => $this->referer(),
                'unsuburl' => $this->referer(),
                'alreadyonurl' => $this->referer(),
                'notonurl' => $this->referer(),
                'invalidurl' => $this->referer(),
                'emailconfirmurl' => $this->referer(),
                'email' => $email,
            );

            if (!empty($unsub)) {
                $data['unsub'] = $unsub;
            }

            $results = $HttpSocket->post('http://scripts.dreamhost.com/add_list.cgi', $data);
            $url = $results->headers['Location'];
            $parse = Router::parse($url);
            $code = $parse['?']['code'];
            switch($code) {
                case 1:
                    $this->set(array(
                        'template' => 'success',
                        'message' => 'Thank you for subscribing.'
                    ));
                    break;
                case 2:
                case -2:
                    $this->set(array(
                        'template' => 'success',
                        'message' => 'You have unsubscribed from the list.'
                    ));
                    break;
                case 3:
                    $this->set(array(
                        'template' => 'info',
                        'message' => 'You are not QUITE subscribed yet. Please now check your email right now for the last step to confirm your subscription to our list!'
                    ));
                    break;
                case -1:
                    $this->set(array(
                        'template' => 'info',
                        'message' => 'You are already on the list.'
                    ));
                    break;
                case -3:
                    $this->set(array(
                        'template' => 'danger',
                        'message' => 'Invalid email address, try again.'
                    ));
                    break;
                case -4:
                    $this->set(array(
                        'template' => 'danger',
                        'message' => 'Email is required.'
                    ));
                    break;
            }
        }
        $this->layout = 'ajax';
    }
}
