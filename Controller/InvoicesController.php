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
					$this->Flash->success('<span class="glyphicon glyphicon-ok"></span>' . __('Thank you for your payment.'));

					// Get a brand new invoice to ensure up to date info
					$invoice = $this->Invoice->find('first', array(
							'conditions' => compact('slug'),
							'contain' => array('InvoiceItem', 'Address')
						)
					);

                    $this->Emailer->invoice($invoice);

					$hideFatFooter = false;
					$this->set(compact('invoice', 'hideFatFooter'));

					$this->render('view');
				} else {
					$this->Invoice->saveAssociated($data);
					$this->Flash->danger('<span class="glyphicon glyphicon-warning-sign"></span> ' . h($result));
					return $this->redirect(array('action' => 'pay', $slug));
				}
			} else {
				$this->Flash->danger(__('Sorry, your invoice payment could not be completed because
							    one or more required fields was not filled out. Please scroll
							    down the form, complete the required fields, and then resubmit
							    the form.'));
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
			$this->Flash->success(__('The invoice has been deleted.'));
		} else {
			$this->Flash->danger(__('The invoice could not be deleted. Please, try again.'));
		}

		return $this->redirect(array('action' => 'index'));
	}

/**
 * admin_index method
 *
 * @return void
 */
	public function admin_index() {
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
				$this->Flash->success(__('The invoice has been saved.'));
				return $this->redirect(array('action' => 'view', $this->Invoice->getInsertID()));
			} else {
				$this->Flash->danger(__('The invoice could not be saved. Please, try again.'));
			}
        } else {
            $this->request->data['Invoice']['expiration'] = date('Y-m-d', strtotime('+30 day'));
        }
		// Count of line items starting at 0
		$i=0;
		$this->set('i', $i);
	}

	/**
	 * Add eBay invoice
	 * ebayItemID is received via GET form submitted with JS
	 * Check if invoice was previously created, valid XML returned and valid email exists
	 */
	public function admin_ebay() {
		if (!$this->Ebay->checkToken($this->Auth->user())) {
			return $this->redirect(array('controller' => 'users', 'action' => 'ebay', 'admin' => true));
		}

		$ebayItemID = $this->request->query['ebayItemId'];
		$itemIds = $this->Invoice->InvoiceItem->find('list', array('fields' => 'itemid'));
		if (in_array($ebayItemID, $itemIds)) {
			$this->redirect(array('action' => 'index', 'admin' => true));
		}
		$ebayItem = $this->Ebay->getItem($this->token, $ebayItemID);

		if (strcasecmp($ebayItem->Ack, 'Failure')==0) {
			$this->Flash->danger((string)$ebayItem->Errors->ShortMessage);
			$this->redirect(array('action' => 'index', 'admin' => true));
		}

		$email = (string)$ebayItem->Item->SellingStatus->HighBidder->Email;
		if (stristr($email, '@')==false) {
			$this->Flash->danger('No valid email.');
			$this->redirect(array('action' => 'index', 'admin' => true));
		}

		$title = (string)$ebayItem->Item->Title;
		$url = (string)$ebayItem->Item->ListingDetails->ViewItemURL;
		$userID = (string)$ebayItem->Item->SellingStatus->HighBidder->UserID;
		$country = (string)$ebayItem->Item->SellingStatus->HighBidder->BuyerInfo->ShippingAddress->Country;
		$postalCode = (string)$ebayItem->Item->SellingStatus->HighBidder->BuyerInfo->ShippingAddress->PostalCode;
		$price = (string)$ebayItem->Item->SellingStatus->CurrentPrice;
		$shipping = (string)$ebayItem->Item->ShippingDetails->ShippingServiceOptions->ShippingServiceCost;

		$formattedShipping = number_format($shipping, 2);
		$discountShipping = number_format($shipping/2, 2);
		$link = '<a target="_blank" href="' . $url . '">' . $url . '</a>';
		$notes = <<<NOTES
Thank you for your bid. You should note I have reduced the shipping fee from \${$formattedShipping} to \${$discountShipping}
as an incentive to use my checkout versus Paypal. You may view the eBay auction at {$link}.
NOTES;
		$data = array(
			'Invoice' => array(
				'email' => $email,
				'ebayId' => $userID,
				'shippingAmount' => $shipping/2,
				'invoiceNotes' => $notes,
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
		$this->redirect(array('action' => 'index', 'admin' => true));
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
				$this->Flash->success(__('The invoice has been saved.'));
				return $this->redirect(array('action' => 'view', $id));
			} else {
				$this->Flash->danger(__('The invoice could not be saved. Please, try again.'));
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
			$this->Flash->success(__('The invoice has been deleted.'));
		} else {
			$this->Flash->danger(__('The invoice could not be deleted. Please, try again.'));
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
			$this->Flash->success(__('The invoice item has been deleted.'));
		} else {
			$this->Flash->danger(__('The invoice item could not be deleted, invoices must have at least one line item.'));
		}
		return $this->redirect(array('action' => 'edit', $invoice_id));
	}

	public function getLineItem($count = 0) {
		if(!$this->request->is('ajax')){
			return $this->redirect(array('controller' => 'pages', 'action' => 'display', 'home'));
		}

		$this->set(array('i' => $count, 'action' => 'add'));
		$this->layout = 'ajax';
	}

	/**
	 * Build the delete line item modal and append to the body so the postLink isn't inside another form
	 */
	public function deleteModal() {
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
