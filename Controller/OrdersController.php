<?php
App::uses('AppController', 'Controller');
App::uses('Address', 'Model');
class OrdersController extends AppController
{
    public $uses = array('Watch', 'Address', 'Order');
    
    public $paginate = array(
			    'limit' => 10,
			    'order' => array(
					'Order.id' => 'desc'
			    )
			);
    
    protected $cartItemIds = array();
    protected $cartWatches = array();
    
    public function beforeFilter()
    {
	$storeOpen = $this->Watch->storeOpen();
	//Redirect if store is closed and going to a non-admin order page
	if ($storeOpen == false && empty($this->request->params['admin'])) {
	    $this->redirect(array('controller' => 'pages', 'action' => 'home', 'admin' => false));
	}
	
	$this->cartItemIds = $this->Cart->cartItemIds();
        $this->cartWatches = $this->Watch->getCartWatches($this->cartItemIds);
	
	parent::beforeFilter();
    }
    
    /**
     * Get customer orders. Store email and postalCode in session, only fetch
     * orders that match those.
     */
    public function index($reset = false)
    {
	if ($reset == true) {
	    $this->Session->delete('Order');
	    $this->Session->delete('Address');
	    $this->redirect(array('action' => 'index'));
	}
	
	$email = $this->Session->read('Order.email');
	$postalCode = $this->Session->read('Address.postalCode'); 
	    
	if($this->request->is('post')){
	    $data = $this->request->data;
	    $email = $data['Order']['email'];
	    $postalCode = $data['Address']['postalCode'];
	    
	    if (empty($email) || empty($postalCode)) {
		$this->Session->setFlash('Email and postal code are required to search for orders.',
					 'danger', array('class' => 'alert alert-error'));
	    }
	    
	    $this->Session->write('Order.email', $email);
	    $this->Session->write('Address.postalCode', $postalCode);
	}
	
	$options = $this->Order->getCustomerOrderOptions($email, $postalCode); 
	$this->Paginator->settings = array_merge($this->paginate, $options); 
	$orders = $this->Paginator->paginate('Order');
	
	if (!empty($orders)) {
	    $this->set('orders', $orders);
	}

	//Set flash message if we have an email and postalCode but no orders
	if ((!(empty($email)) && !empty($postalCode)) && empty($orders)) { 
	    $this->Session->setFlash('No orders found for this email and postal code.',
			     'danger', array('class' => 'alert alert-error'));
	}
	
	$title = 'Order History';
	
	$this->set(compact('email', 'title'));
    }
    
    public function view($id = null)
    {
	$email = $this->Session->read('Order.email');
	$postalCode = $this->Session->read('Address.postalCode');
	
	if (empty($email) || empty($postalCode) || empty($id)) {
	    $this->redirect(array('action' => 'index'));
	}
	
	$options = $this->Order->getCustomerOrderOptions($email, $postalCode, $id);
	$order = $this->Order->find('first', $options);
	
	if (empty($order)) {
	    $this->Session->setFlash('Invalid Order', 'danger', array('class' => 'alert alert-error'));
	    $this->redirect(array('action' => 'index'));
	}
	
	$title = 'Order History';
	
	$this->set(compact('order', 'title'));
    }
    
    public function checkout()
    {
        if($this->Cart->cartEmpty() == true){
            $this->redirect(array('controller' => 'watches', 'action' => 'index'));
        }

        $months = array_combine(range(1,12), range(1,12));
        $year = date('Y'); 
        for($i=date('Y'); $i<=date('Y')+10; $i++){
            $years[$i] = $i;
        }
	
	//Handle ajax request for autocomplete
        if($this->request->is('ajax')){
            $query = $this->request->query; 
            $search = $query['term'];
            if(!$search){ 
                throw new NotFoundException('Search term required');
            }
	    
	    $filtered = array();
	    $countries = $this->_getCountries();
	    foreach($countries as $key => $country){
		if(stripos($country, htmlentities($search)) !== false){
		    $filtered[] = array('id' => $key, 'value' => html_entity_decode($country, ENT_COMPAT, 'UTF-8'));
		}
	    }

	    $this->set(compact('filtered'));
	    $this->layout = 'ajax';
        }
	
	//Form submitted
	if($this->request->is('post')){
	    $data = $this->request->data;
	    unset($data['Shipping']);
	    
	    $addresses = $data['Address'];
	    
	    $addressesToSave = array();
	    unset($addresses['select-country']);

	    foreach($addresses as $type => $item){
		$address = $item;
		$address['type'] = $type;
		$addressesToSave[] = $address;
	    }
	    
	    $data['Address'] = $addressesToSave;

	    $checkoutData = ($this->Session->check('Cart.shipping') == true) &&
			    ($this->Cart->cartItemCount() > 0) &&
			    ($this->Session->check('Cart.total')== true) &&
			    ($this->Session->read('Cart.total') > 0);
	    
	    if(!$checkoutData){
		//There is no data to checkout with
		$this->redirect(array('controller' => 'watches', 'action' => 'index'));
	    }
	    
	    //Get the shipping amount from the session and add to Order
	    $shippingAmount = $this->Session->read('Cart.shipping');
	    $data['Order']['shippingAmount'] = $shippingAmount;
	    
	    $valid = $this->Order->validateAssociated($data); 
	    if($valid == true){
		$amount = $this->Session->read('Cart.total'); 
		$stripeToken = $this->request->data['stripeToken'];
		$stripeData = array('amount' => $amount,
			      'stripeToken' => $stripeToken);
		$result = $this->Stripe->charge($stripeData);
		
		if(is_array($result) && $result['stripe_paid'] == true){
		    unset($this->Order->Address->validate['order_id']);
		    $this->Order->saveAssociated($data); 
		    
		    //Write the results of the Stripe payment processing to the table
		    $this->Order->save($result); 
		
		    //Get the order_id
		    $order_id = $this->Order->id;
		    
		    //Get the purchased items from the Session, add the order_id and
		    //update the items with the order_id
		    $purchasedWatches = array_map(function($item) use ($order_id){
					    $item['Watch']['order_id'] = $order_id;
					    $item['Watch']['active'] = 0;
					    return $item;
					} , $this->cartWatches);
		    $this->Watch->saveMany($purchasedWatches);
		    
		    $this->Session->delete('Cart');
		    $order = $this->Order->getOrder($order_id);
		    $this->emailOrder($order);
		    $title = 'Thank You For Your Order';
		    $this->set(compact('order', 'title'));   
		    $this->Session->setFlash('<span class="glyphicon glyphicon-ok"></span> Thank you for your order.',
					     'default', array('class' => 'alert alert-success'));
		    $this->render('confirm');
		} else {
		    //Decline
		    $this->Session->write('Address', array('data' => $addresses));
		    $this->Session->setFlash('<span class="glyphicon glyphicon-warning-sign"></span> ' . $result,
					     'default', array('class' => 'alert alert-danger'));
		}
	    }
	    else{ 
		//Get Address errors if any
		$errors = $this->Address->validationErrors; 
		
		//Error sets have numeric keys, change to billing or shipping
		foreach(array('billing', 'shipping') as $key => $value){
		    $fixErrors[$value] = isset($errors[$key]) ? $errors[$key] : null;
		}
		$this->Address->validationErrors = $fixErrors;
		
		$this->Session->write('Address', array('errors' => $fixErrors, 'data' => $addresses));
		
		//Set a variable for the view to display a general error message
		$this->set(array('errors' => true));
	    }
        }
	
	$title = 'Checkout';
        $this->set(compact('months', 'years', 'total', 'title') + array('watches' => $this->cartWatches));
    }
    
    public function add($id = null)
    {   
	if (!$this->Watch->sellable($id)) {
		$this->redirect(array('action' => 'checkout'));
	}
        $items = array();
        if($this->Session->check('Cart.items') == true){
            $items = $this->Session->read('Cart.items');
            if(in_array($id, $items)){
                $this->Session->setFlash('That item is already in your cart.');
                $this->redirect(array('controller' => 'watches', 'action' => 'index'));
            }
        }

        $items[] = $id; 
        $this->Session->write('Cart.items', $items);
        
        $this->redirect(array('action' => 'checkout'));
    }
    
    public function remove($id = null)
    {
        if (!$this->Watch->exists($id)) {
            throw new NotFoundException(__('Invalid watch'));
        }
	
        if($this->Session->check('Cart.items') == true){
            $items = $this->Session->read('Cart.items'); 
            if(in_array($id, $items)){
                $key = array_search($id, $items);
                unset($items[$key]); 
                $this->Session->write('Cart.items', $items);
            }
        }
	
	$this->redirect(array('action' => 'checkout'));
	$this->layout = false;
    }
    
    /**
     * Get country shipping and total
     */
    public function getShipping()
    {	
	if($this->request->is('ajax')){
	    $shipping = null;
	    $query = $this->request->query; 
	    $country = $query['country'];
	    switch($country){
		case 'us':
		    $shipping = '8';
		    break;
		case 'ca':
		    $shipping = '38';
		    break;
		case 'other';
		    $shipping = '45';
		    break;
	    }

	    $subTotal = $this->Order->getSubTotal($this->cartWatches); 
	    $total = $subTotal + $shipping;
	    $this->Session->write('Cart.shipping', $shipping);
	    $this->Session->write('Cart.total', $total);
	    
	    $this->set(array('data' => compact('shipping', 'total')));
	    $this->layout = 'ajax';
	}
    }
    
    /**
     * Get address form based on country
     */
    public function getAddress()
    {
	if($this->request->is('ajax')){
	    $query = $this->request->query; 
	    $country = $query['country'];
	    $shipping = $query['shipping'];
	    $statesProvinces = array('states' => $this->_getStates(), 'provinces' => $this->_getCanadianProvinces());
	    $data = compact('shipping', 'country', 'statesProvinces');

	    //Address data and errors in the session
	    if($this->Session->check('Address') == true){
		$data['errors'] = $this->Session->read('Address.errors');
		$data['values'] = $this->Session->read('Address.data');

		//For other countries we need to take the error message in country
		//and put it in countryName
		if(strcasecmp($country, 'other') == 0 && !empty($data['errors'])){
		    foreach($data['errors'] as $key => $errors){
			if(isset($errors['country'])){
			    $errors['countryName'] = $errors['country'];
			}
			$newErrors[$key] = $errors;
		    }
		    $data['errors'] = $newErrors;
		}

		$this->Session->delete('Address'); 
	    } 
	    $this->set(compact('data'));
	    $this->layout = 'ajax';
	}
    }
    
    /**
     * Get country based on state or province
     */
    public function getCountry()
    {
	if($this->request->is('ajax')){
	    $data = $this->request->query['data'];
	    $type = key($data['Address']);
	    $state = $data['Address'][$type]['state'];
	    $type = ucfirst($type);
	    
	    $states = $this->_getStates();
	    $provinces = $this->_getCanadianProvinces();
	    $country = (isset($states[$state]) ? 'US' : (isset($provinces[$state]) ? 'CA' : ''));

	    $this->set(array('data' => compact('country', 'type')));
	    $this->layout = 'ajax';
	}
    }
    
    public function admin_index()
    {
	$filter = '';
	$value = '';
	$options = array();
	
	$filters = array(
		'' => 'Show All',
		'Order.email' => 'Email',
		'Address.postalCode' => 'Billing Postal Code',
		'Watch.stockId' => 'Watch Stock ID',
		'Brand.name' => 'Watch Brand'
	    );
	
	if ($this->request->query) {
	    $filter = !empty($this->request->query['filter']) ? $this->request->query['filter'] : '';
	    $value = !empty($this->request->query['value']) ? $this->request->query['value'] : '';
	    if (!empty($filter) && !empty($value)){
		$options['conditions'] = array($filter => $value);
		switch ($filter) {
		    case 'Address.postalCode':
			$options['joins'] = array(
			    array(
				'table' => 'addresses',
				'alias' => 'Address',
				'type' => 'INNER',
				'conditions' => array(
				    'Address.order_id = Order.id'
				)
			    )
			);
			$options['contain'] = array('Address' => array(
								    'fields' => 'id', 'type', 'postalCode' 
								)
						    );
			$options['conditions']['Address.type'] = 'billing';
			break;
		    case 'Watch.stockId':
			$options['joins'] = array(
			    array(
				'table' => 'watches',
				'alias' => 'Watch',
				'type' => 'INNER',
				'conditions' => array(
				    'Watch.order_id = Order.id'
				)
			    )
			);
			$options['contain'] = array('Watch' => array(
								'fields' => 'id', 'stockId'
							    )
							);
			break;
		    case 'Brand.name':
			$options['joins'] = array(
			    array(
				'table' => 'watches',
				'alias' => 'Watch',
				'type' => 'INNER',
				'conditions' => array(
				    'Watch.order_id = Order.id'
				)
			    ),
			    array(
				'table' => 'brands',
				'alias' => 'Brand',
				'type' => 'INNER',
				'conditions' => array(
				    'Watch.brand_id = Brand.id'
				)
			    )
			);
			$options['contain'] = array(
						'Watch' => array(
								'fields' => 'id', 'stockId',
								'Brand'
							    ),
							);
			break;
		}
	    }
	} 

	$this->paginate['fields'] = array('id', 'email', 'stripe_id', 'stripe_amount', 'shipDate', 'created');
	$this->Paginator->settings = array_merge($this->paginate, $options); 
	$this->set(array(
		    'orders' => $this->Paginator->paginate('Order')
		    ) + compact('filters', 'filter', 'value')
		);
    }
    
    public function admin_view($id = null)
    {
	if (!$this->Order->exists($id)) {
		throw new NotFoundException(__('Invalid order'));
	}

	$this->set('order', $this->Order->getOrder($id));
    }
    
/**
 * admin_edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
    public function admin_edit($id = null) { 
	if (!$this->Order->exists($id)) {
	    throw new NotFoundException(__('Invalid order'));
	}
	
	if ($this->request->is('post') || $this->request->is('put')) { 
	    //Create an empty shipping address record with same country as billing
	    if (isset($this->request->data['Order']['add_shipping_address'])
		    && $this->request->data['Order']['add_shipping_address'] == 1) { 
		$billingCountry = $this->request->data['Address'][0]['country'];
		$this->Order->Address->create();
		$this->Order->Address->save(array(
						'order_id' => $id,
						'type' => 'shipping',
						'country' => $billingCountry
					    ),
					    array(
						'validate' => false
					    )
					);
	    }
	    
	    //Delete shipping address
	    if (isset($this->request->data['Order']['delete_shipping_address'])) {
		//Get the address_id and delete it
		$address_id = $this->request->data['Order']['delete_shipping_address'];
		$this->Address->delete($address_id);
		//Since it's deleted we now have to get it out of the request array for save to work
		//Get the addresses
		$addresses = $this->request->data['Address'];
		//Pluck out the shipping address
		$shippingAddress = array_filter($addresses, function($item) use ($address_id) {
		    return $item['id'] == $address_id;
		});
		//If it's an array of address info, delete it from the request data
		if (is_array($shippingAddress)) {
		    unset($this->request->data['Address'][key($shippingAddress)]); 
		}
		//Get rid of address id to delete passed in from form
		unset($this->request->data['Order']['delete_shipping_address']);
	    }
	    
	    $this->request->data['Order']['id'] = $id; 
	    if ($this->Order->saveAssociated($this->request->data)) { 
		$this->Session->setFlash(__('The order has been saved'), 'success');
		$this->redirect(array('action' => 'edit', $id));
	    } else {
		$this->Session->setFlash(__('The order could not be saved. Please, try again.'));
	    }
	}
	
	$options = array('conditions' => array('Order.' . $this->Order->primaryKey => $id));
	$this->request->data = $this->Order->find('first', $options);
	$order = $this->Order->find('first', $options); 
	
	$this->set('order', $order); 
	
	$addressFields = array('firstName', 'lastName', 'company', 'address1', 'address2', 'city', 'state',
			'postalCode', 'country');

	$statesUS = array('' => 'Select One') + $this->_getStates();
	$statesCA = array('' => 'Select One') + $this->_getCanadianProvinces();
	$countries = $this->_getCountries();
	
	$this->set(compact('addressFields', 'statesUS', 'statesCA', 'countries'));
    }

/**
 * admin_delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
    public function admin_delete($id = null) {
	$this->Order->id = $id;
	if (!$this->Order->exists()) {
		throw new NotFoundException(__('Invalid order'));
	}
	$this->request->onlyAllow('post', 'delete');
	if ($this->Order->delete()) {
		$this->Session->setFlash(__('Order deleted'), 'success');
		$this->redirect(array('action' => 'index'));
	}
	$this->Session->setFlash(__('Order was not deleted'), 'danger');
	$this->redirect(array('action' => 'index'));
    }
    
    public function emailOrder($order = null)
    {
	$Email = new CakeEmail('smtp');
	$Email->template('order_received', 'default')
	      ->emailFormat('html')
	      ->to(Configure::read('ordersEmail'))
	      ->from(Configure::read('fromEmail'))
	      ->subject('Order No. ' . $order['Order']['id'])
	      ->viewVars(array('order' => $order))
	      ->helpers(array('Html' => array('className' => 'MyHtml'),
			      'Number' => array('className' => 'MyNumber')))
	      ->send();
	
	$Email = new CakeEmail('smtp');
	$Email->template('order_received', 'default')
	      ->emailFormat('html')
	      ->to($order['Order']['email'])
	      ->from(Configure::read('fromEmail'))
	      ->subject('Thank you for your order from Bruce\'s Vintage Watches')
	      ->viewVars(array('order' => $order))
	      ->helpers(array('Html' => array('className' => 'MyHtml'),
			      'Number' => array('className' => 'MyNumber')))
	      ->send();
	      
	return;
    }
    
    /**
     * Proof of concept for address validation
     */
    /*public function test()
    {
	if($this->request->is('post')){
	    $data = $this->request->data; 
	    $addresses = $data['Address'];
	    foreach($addresses as $type => $item){ 
		$address = $item;
		$address['type'] = $type;
		$addressesToSave[] = $address;
	    } 

	    $this->Address->set($addressesToSave);
	    //Grab the form data because the save attempt munges it
	    $data = $this->Address->data;
	    if($this->Address->saveMany($addressesToSave)){
		$this->Session->setFlash('valid');
	    }  
	    else{
		$errors = $this->Address->validationErrors;
		$fixErrors['billing'] = $errors[0];
		if(isset($errors[1])){
		    $fixErrors['shipping'] = $errors[1];
		}
		$this->Address->validationErrors = $fixErrors;
		$this->Address->data = $data;
	    }
	}
    }*/
    
    protected function _getStates()
    {
        return array('AL'=>'Alabama',  
		'AK'=>'Alaska',  
                'AZ'=>'Arizona',  
                'AR'=>'Arkansas',  
                'CA'=>'California',  
                'CO'=>'Colorado',  
                'CT'=>'Connecticut',  
                'DE'=>'Delaware',  
                'DC'=>'District Of Columbia',  
                'FL'=>'Florida',  
                'GA'=>'Georgia',  
                'HI'=>'Hawaii',  
                'ID'=>'Idaho',  
                'IL'=>'Illinois',  
                'IN'=>'Indiana',  
                'IA'=>'Iowa',  
                'KS'=>'Kansas',  
                'KY'=>'Kentucky',  
                'LA'=>'Louisiana',  
                'ME'=>'Maine',  
                'MD'=>'Maryland',  
                'MA'=>'Massachusetts',  
                'MI'=>'Michigan',  
                'MN'=>'Minnesota',  
                'MS'=>'Mississippi',  
                'MO'=>'Missouri',  
                'MT'=>'Montana',
                'NE'=>'Nebraska',
                'NV'=>'Nevada',
                'NH'=>'New Hampshire',
                'NJ'=>'New Jersey',
                'NM'=>'New Mexico',
                'NY'=>'New York',
                'NC'=>'North Carolina',
                'ND'=>'North Dakota',
                'OH'=>'Ohio',  
                'OK'=>'Oklahoma',  
                'OR'=>'Oregon',  
                'PA'=>'Pennsylvania',  
                'RI'=>'Rhode Island',  
                'SC'=>'South Carolina',  
                'SD'=>'South Dakota',
                'TN'=>'Tennessee',  
                'TX'=>'Texas',  
                'UT'=>'Utah',  
                'VT'=>'Vermont',  
                'VA'=>'Virginia',  
                'WA'=>'Washington',  
                'WV'=>'West Virginia',  
                'WI'=>'Wisconsin',  
                'WY'=>'Wyoming');
    }
    
    protected function _getCanadianProvinces()
    {
        return array(
	    'AB'=>'Alberta', 
            'BC'=>'British Columbia',
	    'MB'=>'Manitoba',
	    'NB'=>'New Brunswick',
	    'NL'=>'Newfoundland and Labrador',
	    'NT'=>'Northwest Territories',
	    'NS'=>'Nova Scotia',
	    'NU'=>'Nunavut',
            'ON'=>'Ontario', 
            'PE'=>'Prince Edward Island', 
            'QC'=>'Quebec', 
            'SK'=>'Saskatchewan', 
            'YT'=>'Yukon Territory');
    }
    
    protected function _getCountries()
    {
	return array(
	    'AF' => 'Afghanistan',
	    'AX' => '&Aring;land Islands',
	    'AL' => 'Albania',
	    'DZ' => 'Algeria',
	    'AS' => 'American Samoa',
	    'AD' => 'Andorra',
	    'AO' => 'Angola',
	    'AI' => 'Anguilla',
	    'AQ' => 'Antarctica',
	    'AG' => 'Antigua and Barbuda',
	    'AR' => 'Argentina',
	    'AM' => 'Armenia',
	    'AW' => 'Aruba',
	    'AU' => 'Australia',
	    'AT' => 'Austria',
	    'AZ' => 'Azerbaijan',
	    'BS' => 'Bahamas',
	    'BH' => 'Bahrain',
	    'BD' => 'Bangladesh',
	    'BB' => 'Barbados',
	    'BY' => 'Belarus',
	    'BE' => 'Belgium',
	    'BZ' => 'Belize',
	    'BJ' => 'Benin',
	    'BM' => 'Bermuda',
	    'BT' => 'Bhutan',
	    'BO' => 'Bolivia, Plurinational State of',
	    'BQ' => 'Bonaire, Sint Eustatius and Saba',
	    'BA' => 'Bosnia and Herzegovina',
	    'BW' => 'Botswana',
	    'BV' => 'Bouvet Island',
	    'BR' => 'Brazil',
	    'IO' => 'British Indian Ocean Territory',
	    'BN' => 'Brunei Darussalam',
	    'BG' => 'Bulgaria',
	    'BF' => 'Burkina Faso',
	    'BI' => 'Burundi',
	    'KH' => 'Cambodia',
	    'CM' => 'Cameroon',
	    'CA' => 'Canada',
	    'CV' => 'Cape Verde',
	    'KY' => 'Cayman Islands',
	    'CF' => 'Central African Republic',
	    'TD' => 'Chad',
	    'CL' => 'Chile',
	    'CN' => 'China',
	    'CX' => 'Christmas Island',
	    'CC' => 'Cocos (Keeling) Islands',
	    'CO' => 'Colombia',
	    'KM' => 'Comoros',
	    'CG' => 'Congo',
	    'CD' => 'Congo, the Democratic Republic of the',
	    'CK' => 'Cook Islands',
	    'CR' => 'Costa Rica',
	    'CI' => 'C&ocirc;te d\'Ivoire',
	    'HR' => 'Croatia',
	    'CU' => 'Cuba',
	    'CW' => 'Cura&ccedil;ao',
	    'CY' => 'Cyprus',
	    'CZ' => 'Czech Republic',
	    'DK' => 'Denmark',
	    'DJ' => 'Djibouti',
	    'DM' => 'Dominica',
	    'DO' => 'Dominican Republic',
	    'EC' => 'Ecuador',
	    'EG' => 'Egypt',
	    'SV' => 'El Salvador',
	    'GQ' => 'Equatorial Guinea',
	    'ER' => 'Eritrea',
	    'EE' => 'Estonia',
	    'ET' => 'Ethiopia',
	    'FK' => 'Falkland Islands (Malvinas)',
	    'FO' => 'Faroe Islands',
	    'FJ' => 'Fiji',
	    'FI' => 'Finland',
	    'FR' => 'France',
	    'GF' => 'French Guiana',
	    'PF' => 'French Polynesia',
	    'TF' => 'French Southern Territories',
	    'GA' => 'Gabon',
	    'GM' => 'Gambia',
	    'GE' => 'Georgia',
	    'DE' => 'Germany',
	    'GH' => 'Ghana',
	    'GI' => 'Gibraltar',
	    'GR' => 'Greece',
	    'GL' => 'Greenland',
	    'GD' => 'Grenada',
	    'GP' => 'Guadeloupe',
	    'GU' => 'Guam',
	    'GT' => 'Guatemala',
	    'GG' => 'Guernsey',
	    'GN' => 'Guinea',
	    'GW' => 'Guinea-Bissau',
	    'GY' => 'Guyana',
	    'HT' => 'Haiti',
	    'HM' => 'Heard Island and McDonald Islands',
	    'VA' => 'Holy See (Vatican City State)',
	    'HN' => 'Honduras',
	    'HK' => 'Hong Kong',
	    'HU' => 'Hungary',
	    'IS' => 'Iceland',
	    'IN' => 'India',
	    'ID' => 'Indonesia',
	    'IR' => 'Iran, Islamic Republic of',
	    'IQ' => 'Iraq',
	    'IE' => 'Ireland',
	    'IM' => 'Isle of Man',
	    'IL' => 'Israel',
	    'IT' => 'Italy',
	    'JM' => 'Jamaica',
	    'JP' => 'Japan',
	    'JE' => 'Jersey',
	    'JO' => 'Jordan',
	    'KZ' => 'Kazakhstan',
	    'KE' => 'Kenya',
	    'KI' => 'Kiribati',
	    'KP' => 'Korea, Democratic People\'s Republic of',
	    'KR' => 'Korea, Republic of',
	    'KW' => 'Kuwait',
	    'KG' => 'Kyrgyzstan',
	    'LA' => 'Lao People\'s Democratic Republic',
	    'LV' => 'Latvia',
	    'LB' => 'Lebanon',
	    'LS' => 'Lesotho',
	    'LR' => 'Liberia',
	    'LY' => 'Libya',
	    'LI' => 'Liechtenstein',
	    'LT' => 'Lithuania',
	    'LU' => 'Luxembourg',
	    'MO' => 'Macao',
	    'MK' => 'Macedonia, The Former Yugoslav Republic of',
	    'MG' => 'Madagascar',
	    'MW' => 'Malawi',
	    'MY' => 'Malaysia',
	    'MV' => 'Maldives',
	    'ML' => 'Mali',
	    'MT' => 'Malta',
	    'MH' => 'Marshall Islands',
	    'MQ' => 'Martinique',
	    'MR' => 'Mauritania',
	    'MU' => 'Mauritius',
	    'YT' => 'Mayotte',
	    'MX' => 'Mexico',
	    'FM' => 'Micronesia, Federated States of',
	    'MD' => 'Moldova, Republic of',
	    'MC' => 'Monaco',
	    'MN' => 'Mongolia',
	    'ME' => 'Montenegro',
	    'MS' => 'Montserrat',
	    'MA' => 'Morocco',
	    'MZ' => 'Mozambique',
	    'MM' => 'Myanmar',
	    'NA' => 'Namibia',
	    'NR' => 'Nauru',
	    'NP' => 'Nepal',
	    'NL' => 'Netherlands',
	    'NC' => 'New Caledonia',
	    'NZ' => 'New Zealand',
	    'NI' => 'Nicaragua',
	    'NE' => 'Niger',
	    'NG' => 'Nigeria',
	    'NU' => 'Niue',
	    'NF' => 'Norfolk Island',
	    'MP' => 'Northern Mariana Islands',
	    'NO' => 'Norway',
	    'OM' => 'Oman',
	    'PK' => 'Pakistan',
	    'PW' => 'Palau',
	    'PS' => 'Palestine, State of',
	    'PA' => 'Panama',
	    'PG' => 'Papua New Guinea',
	    'PY' => 'Paraguay',
	    'PE' => 'Peru',
	    'PH' => 'Philippines',
	    'PN' => 'Pitcairn',
	    'PL' => 'Poland',
	    'PT' => 'Portugal',
	    'PR' => 'Puerto Rico',
	    'QA' => 'Qatar',
	    'RE' => 'R&eacute;union',
	    'RO' => 'Romania',
	    'RU' => 'Russian Federation',
	    'RW' => 'Rwanda',
	    'BL' => 'Saint Barth&eacute;lemy',
	    'SH' => 'Saint Helena, Ascension and Tristan da Cunha',
	    'KN' => 'Saint Kitts and Nevis',
	    'LC' => 'Saint Lucia',
	    'MF' => 'Saint Martin (French part)',
	    'PM' => 'Saint Pierre and Miquelon',
	    'VC' => 'Saint Vincent and the Grenadines',
	    'WS' => 'Samoa',
	    'SM' => 'San Marino',
	    'ST' => 'Sao Tome and Principe',
	    'SA' => 'Saudi Arabia',
	    'SN' => 'Senegal',
	    'RS' => 'Serbia',
	    'SC' => 'Seychelles',
	    'SL' => 'Sierra Leone',
	    'SG' => 'Singapore',
	    'SX' => 'Sint Maarten (Dutch part)',
	    'SK' => 'Slovakia',
	    'SI' => 'Slovenia',
	    'SB' => 'Solomon Islands',
	    'SO' => 'Somalia',
	    'ZA' => 'South Africa',
	    'GS' => 'South Georgia and the South Sandwich Islands',
	    'SS' => 'South Sudan',
	    'ES' => 'Spain',
	    'LK' => 'Sri Lanka',
	    'SD' => 'Sudan',
	    'SR' => 'Suriname',
	    'SJ' => 'Svalbard and Jan Mayen',
	    'SZ' => 'Swaziland',
	    'SE' => 'Sweden',
	    'CH' => 'Switzerland',
	    'SY' => 'Syrian Arab Republic',
	    'TW' => 'Taiwan, Province of China',
	    'TJ' => 'Tajikistan',
	    'TZ' => 'Tanzania, United Republic of',
	    'TH' => 'Thailand',
	    'TL' => 'Timor-Leste',
	    'TG' => 'Togo',
	    'TK' => 'Tokelau',
	    'TO' => 'Tonga',
	    'TT' => 'Trinidad and Tobago',
	    'TN' => 'Tunisia',
	    'TR' => 'Turkey',
	    'TM' => 'Turkmenistan',
	    'TC' => 'Turks and Caicos Islands',
	    'TV' => 'Tuvalu',
	    'UG' => 'Uganda',
	    'UA' => 'Ukraine',
	    'AE' => 'United Arab Emirates',
	    'GB' => 'United Kingdom',
	    'US' => 'United States',
	    'UM' => 'United States Minor Outlying Islands',
	    'UY' => 'Uruguay',
	    'UZ' => 'Uzbekistan',
	    'VU' => 'Vanuatu',
	    'VE' => 'Venezuela, Bolivarian Republic of',
	    'VN' => 'Viet Nam',
	    'VG' => 'Virgin Islands, British',
	    'VI' => 'Virgin Islands, U.S.',
	    'WF' => 'Wallis and Futuna',
	    'EH' => 'Western Sahara',
	    'YE' => 'Yemen',
	    'ZM' => 'Zambia',
	    'ZW' => 'Zimbabwe'
	);
    }
}
