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
	
	public function beforeRender()
	{
		$recentWatches = $this->Watch->getWatches(3);
		$this->set(compact('recentWatches'));
		parent::beforeRender();
	}

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
		$slug = current($path); 
		
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
	
	public function admin_index()
	{
		$pages = $this->Paginator->paginate('Page');
		$this->set('pages', $pages);
	}
	
	public function admin_edit($id = null)
	{
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
}
