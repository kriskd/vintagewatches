<?php
App::uses('AppController', 'Controller');
App::uses('Address', 'Model');
class OrdersController extends AppController
{
    public $uses = array('Watch', 'Address', 'Order', 'State', 'Province', 'Country');

    public $paginate = array(
        'limit' => 10,
        'order' => array(
            'Order.id' => 'desc'
        )
    );

    protected $cartItemIds = array();
    protected $cartWatches = array();

    public function beforeFilter() {	
        $storeOpen = $this->Watch->storeOpen();
        //Redirect if store is closed and going to a non-admin order page and not index or view
        if ($storeOpen == false && empty($this->request->params['admin']) && !in_array($this->request->params['action'], array('index', 'view'))) {
            $this->redirect(array('controller' => 'pages', 'action' => 'home', 'admin' => false));
        }

        $this->cartItemIds = $this->Cart->cartItemIds();
        $this->cartWatches = $this->Watch->getCartWatches($this->cartItemIds);
        $this->Order->Coupon->removeRequiredCode();

        parent::beforeFilter();
    }

    /**
     * Get customer orders. Store email and postalCode in session, only fetch
     * orders that match those.
     */
    public function index($reset = false) {
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

    public function view($id = null) {
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

    public function checkout() {
        if($this->Cart->cartEmpty() == true){
            $this->redirect(array('controller' => 'watches', 'action' => 'index'));
        }

        // For return to cart when item is not available
        if ($this->Session->check('Address.select-country')) {
            $this->request->data['Address']['select-country'] = $this->Session->read('Address.select-country');
        }

        // Check if any coupons are available
        $couponsAvailable = false;
        $coupons = $this->Order->Coupon->find('count', array(
            'conditions' => array(
                'archived' => 0,
                'OR' => array(
                    'expire_date' => NULL,
                    'expire_date >' => date('Y-m-d'),
                ),
                'available >' =>  0,
            ),
            'recursive' => -1,
        ));
        
        if ($coupons > 0) {
            $couponsAvailable = true;
        }
        $this->set(compact('couponsAvailable'));
        
        //Handle ajax request for autocomplete
        if($this->request->is('ajax')){
            $query = $this->request->query; 
            $search = $query['term'];
            if(!$search){ 
                throw new NotFoundException('Search term required');
            }

            $filtered = array();
            $countries = $this->Country->getList();
            foreach($countries as $key => $country){
                if(stripos($country, htmlentities($search)) !== false){
                    $filtered[] = array('id' => $key, 'value' => html_entity_decode($country, ENT_COMPAT, 'UTF-8'));
                }
            }

            $this->set(compact('filtered'));
            $this->layout = 'ajax';
        }

        //Form submitted
        if($this->request->is('post')) {
            $data = $this->request->data;
            unset($data['Shipping']);

            $addresses = $data['Address'];

            $addressesToSave = array();
            $country = $addresses['select-country'];
            unset($addresses['select-country']);

            foreach($addresses as $type => $item){
                $address = $item;
                $address['type'] = $type;
                $addressesToSave[] = $address;
            }

            $data['Address'] = $addressesToSave;

            $checkoutData = ($this->Cart->getShipping() > 0) &&
                ($this->Cart->cartItemCount() > 0) &&
                ($this->Cart->getTotal() > 0);

            if(!$checkoutData){
                //There is no data to checkout with
                $this->redirect(array('controller' => 'watches', 'action' => 'index'));
            }
            
            // Check that watches are still active
            $activeWatches = array_filter($this->cartWatches, function($item) {
                return $item['Watch']['active'] == 1;
            });
            if (count($activeWatches) != $this->Cart->cartItemCount()) {
                $activeIds = Hash::extract($activeWatches, '{n}.Watch.id');
                $remove = array_diff($this->cartItemIds, $activeIds);
                foreach ($remove as $id) {
                    $this->Cart->remove($id);
                }
                $this->Session->write('Address', array('data' => $addresses));
                $this->Session->write('Address.select-country', $country);
                $this->Session->write('Shipping.option',  $this->request->data['Shipping']['option']);
                $this->Session->setFlash('One or more of the items in your cart is no longer available.', 'warning');
                $this->redirect(array('action' => 'checkout'));
            }
           
            //Add shipping to the order
            $data['Order']['shippingAmount'] = $this->Cart->getShipping();
            unset($data['Coupon']);
            $valid = $this->Order->validateAssociated($data); 
            if($valid == true){
                $subTotal = $this->Order->getSubTotal($this->cartWatches); 
                $amount = $this->Cart->getTotal(); 
                $stripeToken = $this->request->data['stripeToken'];

                //Create a description of brands to send to Stripe
                $watches = $this->cartWatches; 
                $brands = array();
                foreach($watches as $watch) {
                    $brands[] = $watch['Brand']['name'];
                }
                $description = implode(',', $brands);

                $stripeData = array(
                    'amount' => $amount,
                    'stripeToken' => $stripeToken,
                    'description' => $description
                );
                $result = $this->Stripe->charge($stripeData);

                if(is_array($result) && $result['stripe_paid'] == true){
                    unset($this->Order->Address->validate['foreign_id']);

                    if ($coupon_id = $this->Cart->getCoupon()) {
                        $data['Order']['coupon_id'] = $coupon_id;
                    }

                    //Add the results of stripe to the data array
                    $data['Payment'] = $result;
                    $this->Order->saveAssociated($data); 

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

                    $this->MobileDetect = $this->Components->load('MobileDetect.MobileDetect');
                    // If mobile or tablet, get device details
                    if ($this->MobileDetect->detect('isMobile') || $this->MobileDetect->detect('isTablet')) {
                        $methods = $this->Order->Detect->find('list', array('fields' => array('Detect.id', 'Detect.method')));
                        $detects = array();
                        foreach($methods as $id => $method) {
                            $detect = $this->MobileDetect->detect($method);
                            if ($detect == true) {
                                $detects[] = $id;
                            }
                        }

                        $this->Order->saveAll(array(
                            'Order' => array(
                                'id' => $order_id
                            ),
                            'Detect' => $detects
                        ));  
                    }

                    $this->Cart->emptyCart();
                    $order = $this->Order->getOrder($order_id); 
                    $this->emailOrder($order);
                    $title = 'Thank You For Your Order';
                    $this->set(compact('order', 'title'));   
                    $this->Session->setFlash('<span class="glyphicon glyphicon-ok"></span> Thank you for your order.',
                        'default', array('class' => 'alert alert-success'));
                    $hideFatFooter = false;
                    $this->set(compact('invoice', 'hideFatFooter'));
                    $this->render('confirm');
                } else {
                    //Decline
                    $this->Session->write('Address', array('data' => $addresses));
                    $this->Session->setFlash('<span class="glyphicon glyphicon-warning-sign"></span> ' . $result,
                        'default', array('class' => 'alert alert-danger'));
                }
            } else { 
                //Get Address errors if any
                $errors = $this->Address->validationErrors; 

                //Error sets have numeric keys, change to billing or shipping
                foreach(array('billing', 'shipping') as $key => $value){
                    $fixErrors[$value] = isset($errors[$key]) ? $errors[$key] : null;
                }
                $this->Address->validationErrors = $fixErrors;

                $this->Session->write('Address', array('errors' => $fixErrors, 'data' => $addresses));
                $this->Session->write('Shipping.option',  $this->request->data['Shipping']['option']);

                //Set a variable for the view to display a general error message
                $this->set(array('errors' => true));
            }
        } else {
            $couponId = $this->Cart->getCoupon();
            $email = $this->Cart->getEmail();
            if (!empty($couponId) && !empty($email)) {
                if ($coupon = $this->Order->Coupon->find('first', array(
                    'conditions' => array(
                        'id' => $couponId,
                    ),
                    'recursive' => -1,
                ))) {
                    $code = $coupon['Coupon']['code'];
                    $this->request->data['Coupon']['email'] = $email;
                    $this->request->data['Coupon']['code'] = $code;
                    $this->request->data['Order']['email'] = $email;
                }
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

        if($this->Cart->inCart($id)){
            $this->Session->setFlash('That item is already in your cart.',
                'info', array('class' => 'alert alert-info'));
            $this->redirect(array('controller' => 'watches', 'action' => 'index'));
        }

        $this->Cart->add($id);

        $this->redirect(array('action' => 'checkout'));
    }

    public function remove($id = null)
    {
        if (!$this->Watch->exists($id)) {
            throw new NotFoundException(__('Invalid watch'));
        }

        $this->Cart->remove($id);

        $this->redirect(array('action' => 'checkout'));
    }

    /**
     * Get shipping and total
     */
    public function totalCart() {	
        if($this->request->is('ajax')){
            $query = $this->request->query; 
            $country = $query['data']['Address']['select-country'];
            $email = $query['data']['Coupon']['email'];
            $code = $query['data']['Coupon']['code'];
            $shipping = $this->Order->getShippingAmount($country);
            $this->Cart->setShipping($shipping);
            //$subTotal = $this->Order->getSubTotal($this->cartWatches); 
            $couponAmount = 0;
            $coupon = $this->Order->Coupon->valid($code, $email, $shipping, $this->cartItemIds);
            if (isset($coupon['Coupon'])) {
                $couponSubTotal = $this->Order->Watch->sumWatchPrices($this->cartItemIds, $coupon['Coupon']['brand_id']);
                $couponAmount = $this->Cart->couponAmount($coupon, $couponSubTotal);
                $this->set(array(
                    'couponAmount' => $couponAmount,
                ));
                if (!empty($coupon['Coupon']) && !empty($email)) {
                    $this->Cart->setCoupon($coupon['Coupon']['id']);
                    $this->Cart->setEmail($email);
                } 
            } else {
                $this->set(compact('coupon')); // Contains error message
                $this->Cart->clearCoupon();
            }
            //$this->Cart->setTotal($subTotal, $couponAmount);
            $this->set(array('data' => array(
                    'shipping' => $this->Cart->getShipping(),
                    'total' => $this->Cart->getTotal(),
                )
            ));
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
            $statesProvinces = array('states' => $this->State->getList(), 'provinces' => $this->Province->getList());
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
    public function getCountry() {
        if($this->request->is('ajax')){
            $data = $this->request->query['data'];
            $type = key($data['Address']);
            $state = $data['Address'][$type]['state'];
            $type = ucfirst($type);

            $states = $this->State->getList();
            $provinces = $this->Province->getList();
            $country = (isset($states[$state]) ? 'US' : (isset($provinces[$state]) ? 'CA' : ''));

            $this->set(array('data' => compact('country', 'type')));
            $this->layout = 'ajax';
        }
    }

    /**
     * Block for customer to choose billing/shipping same or different
     */
    public function getShippingChoice() {
        if ($this->request->is('ajax')) {
            if ($this->Session->check('Shipping.option')) {
                $this->request->data['Shipping']['option'] = $this->Session->read('Shipping.option');
            }
            $this->layout = 'ajax';
        }
    }

    public function admin_index()
    {
        $this->paginate['paramType'] = 'querystring';

        $filter = '';
        $value = '';
        $options = array();

        $filters = array(
            '' => 'Show All',
            'Order.email' => 'Email',
            'Address.lastName' => 'Billing Last Name',
            'Address.postalCode' => 'Billing Postal Code',
            'Watch.stockId' => 'Watch Stock ID',
            'Brand.name' => 'Watch Brand'
        );

        if ($this->request->query) {
            $filter = !empty($this->request->query['filter']) ? $this->request->query['filter'] : '';
            $value = !empty($this->request->query['value']) ? $this->request->query['value'] : '';
            if (!empty($filter) && !empty($value)){
                $options['conditions'] = array($filter => $value);
                $options['contain'] =  array('Payment' => array(
                    'fields' => 'stripe_id', 'stripe_amount'
                )
            );
                switch ($filter) {
                case 'Address.lastName':
                case 'Address.postalCode':
                    $options['joins'] = array(
                        array(
                            'table' => 'addresses',
                            'alias' => 'Address',
                            'type' => 'INNER',
                            'conditions' => array(
                                'Address.foreign_id = Order.id',
                                'Address.class' => 'Order'
                            )
                        )
                    );
                    $options['contain']['Address'] = array(
                        'fields' => 'id', 'type', 'lastName', 'city', 'postalCode' 
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
                    $options['contain']['Watch'] = array(
                        'fields' => 'id', 'stockId'
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
                    $options['contain']['Watch'] = array(
                        'fields' => 'id', 'stockId',
                        'Brand'
                    );
                    break;
                }
            }
        } 

        $this->paginate['fields'] = array('id', 'email', 'Payment.stripe_id', 'Payment.stripe_amount', 'shipDate', 'created', 'modified');
        $this->Paginator->settings = array_merge($this->paginate, $options); 
        $this->set(array(
            'orders' => $this->Paginator->paginate('Order')
        ) + compact('filters', 'filter', 'value')
    );
    }

    public function admin_view($id = null)
    {	
        $id = empty($id)  ? $this->request->query('orderId') : $id;

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
                        'class' => 'Order',
                        'foreign_id' => $id,
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

            //Unset state if not US or Canada
            foreach ($this->request->data['Address'] as &$address) {
                if (!in_array($address['country'], array('US', 'CA'))) {
                    unset($address['state']);
                }
            }
            unset($address);

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

        $statesUS = array('' => 'Select One') + $this->State->getList();
        $statesCA = array('' => 'Select One') + $this->Province->getList();
        $countries = $this->Country->getList();

        $this->set(compact('addressFields', 'statesUS', 'statesCA', 'countries'));
    }

    /**
     * Resend an order to a customer
     */
    public function admin_resend($id = null)
    {
        $this->autoRender = $this->layout = false;
        $this->Order->id = $id;
        if (!$this->Order->exists()) {
            throw new NotFoundException(__('Invalid order'));
        }

        $order = $this->Order->getOrder($id);
        $this->emailOrder($order);
        $this->Session->setFlash('Order Confirmation Resent', 'success');
        $this->redirect($this->referer());
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
        $url = $this->referer(null, true);
        $route = Router::parse($url);
        $action = $route['action'];

        if (strcasecmp($action, 'checkout')==0) {
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
        }

        if (empty($order['Order']['shipDate'])) {
            $subject = 'Thank you for your order from Bruce\'s Vintage Watches';
        } else {
            $subject = 'Your order from Bruce\'s Vintage Watches was shipped on ' . date('F j, Y', strtotime($order['Order']['shipDate']));
        }

        $Email = new CakeEmail('smtp');
        $Email->template('order_received', 'default')
            ->emailFormat('html')
            ->to($order['Order']['email'])
            ->from(Configure::read('fromEmail'))
            ->subject($subject)
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
}
