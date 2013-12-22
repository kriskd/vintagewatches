<?php
App::uses('AppController', 'Controller');
/**
 * Invoices Controller
 *
 * @property Invoice $Invoice
 * @property PaginatorComponent $Paginator
 */
class InvoicesController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator');
	
	public $paginate = array(
			'limit' => 10,
			'order' => array(
				    'Invoice.id' => 'desc'
			)
		);
	
	public $helpers = array('Invoice');

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->Invoice->recursive = 0;
		$this->set('invoices', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($slug = null) {

		$invoice = $this->Invoice->find('first', array('conditions' => compact('slug')));
		
		if (empty($invoice)) {
			$this->redirect(array('controller' => 'pages', 'action' => 'display', 'home'));
		}
		
		$this->request->data = $invoice; 
		$this->set('invoice', $invoice);
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Invoice->create();
			if ($this->Invoice->save($this->request->data)) {
				$this->Session->setFlash(__('The invoice has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The invoice could not be saved. Please, try again.'));
			}
		}
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->Invoice->exists($id)) {
			throw new NotFoundException(__('Invalid invoice'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Invoice->save($this->request->data)) {
				$this->Session->setFlash(__('The invoice has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The invoice could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Invoice.' . $this->Invoice->primaryKey => $id));
			$this->request->data = $this->Invoice->find('first', $options);
		}
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Invoice->id = $id;
		if (!$this->Invoice->exists()) {
			throw new NotFoundException(__('Invalid invoice'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Invoice->delete()) {
			$this->Session->setFlash(__('The invoice has been deleted.'));
		} else {
			$this->Session->setFlash(__('The invoice could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}

/**
 * admin_index method
 *
 * @return void
 */
	public function admin_index() {
		$this->Invoice->recursive = 0;
		$this->Paginator->settings = $this->paginate;
		$this->set('invoices', $this->Paginator->paginate());
	}

/**
 * admin_view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		if (!$this->Invoice->exists($id)) {
			throw new NotFoundException(__('Invalid invoice'));
		}
		$options = array('conditions' => array('Invoice.' . $this->Invoice->primaryKey => $id));
		$this->set('invoice', $this->Invoice->find('first', $options));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		$this->Invoice->Address->removeAllRules();
		if ($this->request->is('post')) {
			$this->Invoice->create();
			$slugChars = 'abcdefghijklmnopqrstuvwxyz0123456789';
			$slug = substr(str_shuffle($slugChars), 0, 32);
			$this->request->data['Invoice']['slug'] = $slug;
			$this->request->data['Address'][0]['type'] = 'billing'; 
			if ($this->Invoice->saveAssociated($this->request->data)) {
				$this->Session->setFlash(__('The invoice has been saved.'), 'success');
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The invoice could not be saved. Please, try again.'), 'danger');
			}
		}
		// Count of line items starting at 0
		$i=0;
		$this->set('i', $i);
	}

/**
 * admin_edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_edit($id = null) {
		if (!$this->Invoice->exists($id)) {
			throw new NotFoundException(__('Invalid invoice'));
		}
		if ($this->request->is(array('post', 'put'))) { 
			if ($this->Invoice->saveAssociated($this->request->data)) {
				$this->Session->setFlash(__('The invoice has been saved.'), 'success');
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The invoice could not be saved. Please, try again.'), 'danger');
			}
		} else {
			$options = array('conditions' => array('Invoice.' . $this->Invoice->primaryKey => $id));
			$this->request->data = $this->Invoice->find('first', $options);
		} 
	}

/**
 * admin_delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_delete($id = null) {
		$this->Invoice->id = $id;
		if (!$this->Invoice->exists()) {
			throw new NotFoundException(__('Invalid invoice'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Invoice->delete()) {
			$this->Session->setFlash(__('The invoice has been deleted.'), 'succes');
		} else {
			$this->Session->setFlash(__('The invoice could not be deleted. Please, try again.'), 'danger');
		}
		return $this->redirect(array('action' => 'index'));
	}
	
	public function getLineItem($count = 0)
	{
		if(!$this->request->is('ajax')){
			$this->redirect(array('controller' => 'pages', 'action' => 'display', 'home'));	
		}
		$this->set('i', $count);
		$this->layout = 'ajax';
	}
	
}
