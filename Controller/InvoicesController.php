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
			),
			'contain' => array('InvoiceItem')
		);
	
	public $helpers = array('Invoice');
	
	public $uses = array('Invoice', 'State', 'Province', 'Country');
	
	public function beforeRender()
	{
		$statesUS = $this->State->getList();
		$statesCA = $this->Province->getList();
		$countries = $this->Country->getList();
		
		$options = array_merge(array('' => 'Select One'), array('U.S.' => $statesUS), array('Canada' => $statesCA));
		$this->set(compact('options', 'statesUS', 'statesCA', 'countries'));
		
		parent::beforeRender();
	}

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
                
                $this->set('invoice', $invoice);
	}

/**
 * pay method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function pay($slug = null) {
		
		$invoice = $this->Invoice->find('first', array(
								'conditions' => compact('slug'),
								'contain' => array('InvoiceItem', 'Address')
							)
						); 
		if (empty($invoice) || !$invoice['Invoice']['active']) {
			$this->redirect(array('controller' => 'pages', 'action' => 'display', 'home'));
		}
		$this->Invoice->Address->removeCountryValidation();
		if ($this->request->is(array('post', 'put'))) { 
			$this->request->data = $this->array_merge_recursive_distinct($invoice, $this->request->data);
			if ($this->Invoice->saveAssociated($this->request->data)) { 
				$this->Session->setFlash(__('Thank you for your payment.'), 'success');
				return $this->redirect(array('action' => 'view', $slug));
			} else {
				$this->Session->setFlash(__('Payment error, please try again.'), 'danger');
				return $this->redirect(array('action' => 'pay', $slug));
			}
		} else {
			$this->request->data = $invoice; 
			$this->set('invoice', $invoice);
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
		$this->Invoice->Address->removeAllButCountry();
		if ($this->request->is('post')) {
			$this->Invoice->create();
			$slugChars = 'abcdefghijklmnopqrstuvwxyz0123456789';
			$slug = substr(str_shuffle($slugChars), 0, 32);
			$this->request->data['Invoice']['slug'] = $slug;
			$this->request->data['Address'][0]['type'] = 'billing'; 
			if ($this->Invoice->saveAssociated($this->request->data)) {
				$this->Session->setFlash(__('The invoice has been saved.'), 'success');
				return $this->redirect(array('action' => 'view', $this->Invoice->getInsertID()));
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
		$this->Invoice->Address->removeAllButCountry();
		if ($this->request->is(array('post', 'put'))) { 
			if ($this->Invoice->saveAssociated($this->request->data)) { 
				$this->Session->setFlash(__('The invoice has been saved.'), 'success');
				return $this->redirect(array('action' => 'view', $id));
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
			$this->Session->setFlash(__('The invoice has been deleted.'), 'success');
		} else {
			$this->Session->setFlash(__('The invoice could not be deleted. Please, try again.'), 'danger');
		}
		return $this->redirect(array('action' => 'index'));
	}
	
	public function admin_delete_item($invoice_id = null, $id = null) { 
		$this->Invoice->InvoiceItem->id = $id;
		$this->Invoice->InvoiceItem->invoice_id = $invoice_id;
		if (!$this->Invoice->InvoiceItem->exists()) {
			throw new NotFoundException(__('Invalid invoice'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Invoice->InvoiceItem->delete()) {
			$this->Session->setFlash(__('The invoice item has been deleted.'), 'success');
		} else {
			$this->Session->setFlash(__('The invoice item could not be deleted, invoices must have at least one line item.'), 'danger');
		}
		return $this->redirect(array('action' => 'edit', $invoice_id));
	}
	
	public function getLineItem($count = 0)
	{
		if(!$this->request->is('ajax')){
			$this->redirect(array('controller' => 'pages', 'action' => 'display', 'home'));	
		}
		$this->set(array('i' => $count, 'action' => 'add'));
		$this->layout = 'ajax';
	}
	
	/**
	 * Build the delete line item modal and append to the body so the postLink isn't inside another form
	 */
	public function deleteModal()
	{
		if($this->request->is('ajax')){
			$data = $this->request->data;
			$this->set(array('description' => $data['description'],
					 'invoice_id' => $data['invoice_id'],
					 'item_id' => $data['item_id']
					 ));
		}
		$this->layout = 'ajax';
	}
	
}
