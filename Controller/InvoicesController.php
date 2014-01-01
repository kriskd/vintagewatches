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
		
		$stateOptions = array_merge(array('' => 'Select One'), array('U.S.' => $statesUS), array('Canada' => $statesCA));
		$this->set(compact('stateOptions', 'statesUS', 'statesCA', 'countries'));
		
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
		
		if (prod() == true && (!isset($_SERVER['HTTPS']) || !$_SERVER['HTTPS'])) {
			$this->redirect('https://' . env('SERVER_NAME') . $this->here);
		}
		
		$invoice = $this->Invoice->find('first', array(
								'conditions' => compact('slug'),
								'contain' => array('InvoiceItem', 'Address')
							)
						); 
		if (empty($invoice) || !$invoice['Invoice']['active']) {
			$this->redirect(array('controller' => 'pages', 'action' => 'display', 'home'));
		}
		$this->set('invoice', $invoice);
		$this->Invoice->Address->removeCountryValidation();
		if ($this->request->is(array('post', 'put'))) { 
			$this->request->data = $this->array_merge_recursive_distinct($invoice, $this->request->data);
			$data = $this->request->data; 
			if ($this->Invoice->validateAssociated($data)) {
				
				$amount = $this->total($invoice); 
				$stripeToken = $this->request->data['stripeToken'];
				
				//Invoice number for Stripe description
				$description = 'Invoice No. ' . $data['Invoice']['id'];
				
				$stripeData = array(
						'amount' => $amount,
						'stripeToken' => $stripeToken,
						'description' => $description
					    );
				$result = $this->Stripe->charge($stripeData);
				
				if (is_array($result) && $result['stripe_paid'] == true) {
					//Add the results of stripe to the data array
					$data['Payment'] = $result;
					$data['Invoice']['active'] = 0;
					$this->Invoice->saveAssociated($data); 
					$this->Session->setFlash(__('<span class="glyphicon glyphicon-ok"></span> Thank you for your payment.'),
								 'default', array('class' => 'alert alert-success'));
					
					// Get a brand new invoice to ensure up to date info
					$invoice = $this->Invoice->find('first', array(
							'conditions' => compact('slug'),
							'contain' => array('InvoiceItem', 'Address')
						)
					);
					
					$Email = new CakeEmail('smtp');
					$Email->template('invoice', 'default')
						->emailFormat('html')
						->to($invoice['Invoice']['email'])
						->bcc(Configure::read('ordersEmail'))
						->from(Configure::read('fromEmail'))
						->subject('Receipt from Bruce\'s Vintage Watches for Invoice No. ' . $invoice['Invoice']['id'])
						->viewVars(compact('invoice'))
						->helpers(array('Html' => array('className' => 'MyHtml'),
								'Number' => array('className' => 'MyNumber')))
						->send();
					
					$hideFatFooter = false;
					$this->set(compact('invoice', 'hideFatFooter'));
					
					$this->render('view');
				} else {
					$this->Invoice->saveAssociated($data);
					$this->Session->setFlash('<span class="glyphicon glyphicon-warning-sign"></span> ' . $result,
								'default', array('class' => 'alert alert-danger'));
					return $this->redirect(array('action' => 'pay', $slug));
				}	
			} else {
				$this->Session->setFlash(__('Sorry, your invoice payment could not be completed because
							    one or more required fields was not filled out. Please scroll
							    down the form, complete the required fields, and then resubmit
							    the form.'), 'danger');
			}
		} else {
			$this->request->data = $invoice; 
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
		$itemIds = $this->Invoice->InvoiceItem->find('list', array('fields' => 'itemid')); 
		$ebayAuctions = $this->Ebay->getSellerList($this->token); 
		foreach ($ebayAuctions->ItemArray->Item as $item) {
			$email = (string)$item->SellingStatus->HighBidder->Email; 
			if (stristr($email, '@')) {
				$ebayItemID = (string)$item->ItemID; 
				if (!in_array($ebayItemID, $itemIds)) {
					$title = (string)$item->Title;
					$url = (string)$item->ListingDetails->ViewItemURL;
					$userID = (string)$item->SellingStatus->HighBidder->UserID;
					$country = (string)$item->SellingStatus->HighBidder->BuyerInfo->ShippingAddress->Country;
					$postalCode = (string)$item->SellingStatus->HighBidder->BuyerInfo->ShippingAddress->PostalCode;
					$price = (string)$item->SellingStatus->CurrentPrice;
					$shipping = (string)$item->ShippingDetails->ShippingServiceOptions->ShippingServiceCost;
					$data = array(
						'Invoice' => array(
							'email' => $email,
							'ebayId' => $userID,
							'shippingAmount' => $shipping,
							'invoiceNotes' => 'View the eBay auction at ' . $url,
							'active' => 0,
						),
						'InvoiceItem' => array(
							array(
							      'itemid' => $ebayItemID,
								'description' => $title,
								'amount' => $price
							)
						),
						'Address' => array(
							array(
								'class' => 'Invoice',
								'type' => 'billing',
								'postalCode' => $postalCode,
								'country' => $country
							)
						)
					);
					$this->Invoice->saveAssociated($data);
				}
			}
		}
		
		$this->paginate['contain'][] = 'Payment';
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
		$invoice = $this->Invoice->find('first', $options);
		$paid = empty($invoice['Payment']['stripe_paid']) ? false : true;
		$this->set(compact('invoice', 'paid'));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		//$this->Invoice->removeRequiredEmail();
		$this->Invoice->Address->removeAllButCountry();
		if ($this->request->is('post')) {
			$this->Invoice->create();
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
		//$this->Invoice->removeRequiredEmail();
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
		// Don't delete if only 1 invoice item exists
		// Had to move here from beforeDelete because it interfered with full invoice delete
		$count = $this->Invoice->InvoiceItem->find('count', array('conditions' => compact('invoice_id')));
		if ($count > 1 && $this->Invoice->InvoiceItem->delete()) {
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
