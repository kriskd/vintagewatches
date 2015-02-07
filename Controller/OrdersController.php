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
            $this->Session->delete('Watch.Order');
            $this->Session->delete('Watch.Address');
            $this->redirect(array('action' => 'index'));
        }

        $email = $this->Session->read('Watch.Order.email');
        $postalCode = $this->Session->read('Watch.Address.postalCode'); 

        if($this->request->is('post')){
            $data = $this->request->data;
            $email = $data['Order']['email'];
            $postalCode = $data['Address']['postalCode'];

            if (empty($email) || empty($postalCode)) {
                $this->Session->setFlash('Email and postal code are required to search for orders.',
                    'danger', array('class' => 'alert alert-error'));
            }
        }

        $options = $this->Order->getCustomerOrderOptions($email, $postalCode); 
        $this->Paginator->settings = array_merge($this->paginate, $options); 
        $orders = $this->Paginator->paginate('Order');

        if (!empty($orders)) {
            $this->set('orders', $orders);
            $this->Session->write('Watch.Order.email', $email);
            $this->Session->write('Watch.Address.postalCode', $postalCode);
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
        $email = $this->Session->read('Watch.Order.email');
        $postalCode = $this->Session->read('Watch.Address.postalCode');

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
        //Form submitted
        if($this->request->is('post')) {
            if($this->Cart->cartEmpty()){
                //There is no data to checkout with
                $this->Cart->setCheckoutData($this->request->data);
                $this->Session->setFlash('There was a problem with your cart, please add your items again.', 'warning');
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
                $this->Cart->setCheckoutData($this->request->data);
                $this->Session->setFlash('One or more of the items in your cart is no longer available.', 'warning');
                $this->redirect(array('action' => 'checkout'));
            }
           
            $data = $this->request->data;
            unset($data['Shipping']);

            $addresses = $data['Address'];
            $country = $addresses['select-country'];
            unset($addresses['select-country']);

            $data['Address'] = $this->Cart->formatAddresses($addresses);
            //Add shipping to the order
            $shipping = $this->Cart->getShippingAmount($country);
            $data['Order']['shippingAmount'] = $shipping;
            $couponCode = isset($data['Coupon']['code']) ? $data['Coupon']['code'] : null;
            $couponEmail = isset($data['Coupon']['email']) ? $data['Coupon']['email'] : null;
            unset($data['Coupon']);

            $valid = $this->Order->validateAssociated($data); 
            if($valid == true){
                $couponAmount = 0;
                if (!empty($couponCode) && !empty($couponEmail)) {
                    $coupon = $this->Order->Coupon->valid($couponCode, $couponEmail, $shipping, $this->cartItemIds);
                    $couponAmount = $this->Cart->couponAmount($this->cartWatches, $shipping, $coupon);
                }
                
                $subTotal = $this->Cart->getSubTotal($this->cartWatches); 
                $stripeData = array(
                    'amount' => $this->Cart->totalCart($subTotal, $shipping, $couponAmount),
                    'stripeToken' => $this->request->data['stripeToken'],
                    'description' => $this->Cart->stripeDescription($this->cartWatches),
                );

                $result = $this->Stripe->charge($stripeData);

                if(is_array($result) && $result['stripe_paid'] == true){
                    unset($this->Order->Address->validate['foreign_id']);

                    if ($couponAmount > 0) {
                        $data['Order']['coupon_id'] = $this->Order->Coupon->getCouponId($couponCode);
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
                    // Put order email and billing postal into session
                    $email = $order['Order']['email'];
                    $this->Session->write('Watch.Order.email', $email);
                    $address = Hash::extract($order, 'Address.{n}[type=billing]');
                    $address = current($address);
                    $postalCode = $address['postalCode']; 
                    $this->Session->write('Watch.Address.postalCode', $postalCode);
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
                    $this->Cart->setCheckoutData($this->request->data);
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

                $this->Cart->setCheckoutData($this->request->data, $fixErrors);

                //Set a variable for the view to display a general error message
                $this->set(array('errors' => true));
            }
        } else {
            // This previously got the coupon id and coupon email out of session
            // In order to populate the form if user previously entered those items,
            // left checkout and returned. Those are no longer stored in session.
            // Keep or go?
            if($this->Cart->cartEmpty() == true){
                $this->redirect(array('controller' => 'watches', 'action' => 'index'));
            }
        }

        // Check if any coupons are available. This needs to live outside of `if`, needed by both.
        $this->set('couponsAvailable', $this->Order->Coupon->couponsAvailable());

        // For return to cart on item fail
        if ($this->Session->check('Order')) {
            $this->request->data['Order'] = $this->Session->read('Order');
            $this->Session->delete('Order');
        }
        if ($this->Session->check('Address.select-country')) {
            $this->request->data['Address']['select-country'] = $this->Session->read('Address.select-country');
            $this->Session->delete('Address.select-country');
        }

        $title = 'Checkout';
        $this->set(compact('title') + array('watches' => $this->cartWatches));
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
            $shipping = $this->Cart->getShippingAmount($country);
            $subTotal = $this->Cart->getSubTotal($this->cartWatches); 
            if ($subTotal <= 0) return; 
            $couponAmount = 0;
            $couponEmail = $query['data']['Coupon']['email'];
            $couponCode = $query['data']['Coupon']['code'];
            if (!empty($couponEmail) && !empty($couponCode)) {
                $coupon = $this->Order->Coupon->valid($couponCode, $couponEmail, $shipping, $this->cartItemIds);
                $couponAmount = $this->Cart->couponAmount($this->cartWatches, $shipping, $coupon);
                $this->set(array(
                    'couponAmount' => $couponAmount,
                    'coupon' => $coupon, // Contains error message if any
                ));
            }
            $this->set(array(
                'shipping' => $this->Cart->getShippingAmount($country),
                'total' => $this->Cart->totalCart($subTotal, $shipping, $couponAmount),
            ));
        }
        $this->layout = 'ajax';
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
                $this->request->data['Address'] = $this->Session->read('Address.data');
                $this->Session->delete('Address');

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

}
