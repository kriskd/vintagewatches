<?php
App::uses('OrdersController', 'Controller');
App::uses('CakeRequest', 'Network');
App::uses('ComponentCollection', 'Controller');
App::uses('StripeComponent', 'Stripe.Controller/Component');
App::uses('SessionComponent', 'Controller/Component');
App::uses('Order', 'Model');

/**
 * OrdersController Test Case
 *
 */
class OrdersControllerTest extends ControllerTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.watch',
		'app.order',
		'app.coupon',
		'app.brand',
		'app.payment',
		//'app.invoice',
		//'app.invoice_item',
		'app.address',
		'app.detect',
		'app.detectsorder',
		'app.image',
		'app.state',
		'app.province',
		'app.country',
		'app.page',
        //'app.content',
        'app.region',
	);

    public $address = array(
        'firstName' => 'Sandra',
        'lastName' => 'Irwin',
        'company' => '',
        'address1' => '2215 Gateway Road',
        'address2' => '',
        'city' => 'Portland',
        'state' => 'OR',
        'postalCode' => '97205',
        'country' => 'US'
    );

    public function setUp() {
        parent::setUp();
        $this->Order = ClassRegistry::init('Order');
        $this->ComponentCollection = new ComponentCollection();
        $this->Session = new SessionComponent($this->ComponentCollection);

    }

/**
 * testIndex method
 *
 * @return void
 */
	public function testIndex() {
        $this->Session->write('Watch.Order.email', 'PeterRHarris@teleworm.us');
        $this->Session->write('Watch.Address.postalCode', '61602');
        $results = $this->testAction('/orders', array(
            'method' => 'GET',
            'return' => 'vars',
        ));
        $this->assertEquals($results['orders'][0]['Order']['email'], $this->Session->read('Watch.Order.email'));
        $this->assertEquals($results['orders'][0]['Address'][0]['postalCode'], $this->Session->read('Watch.Address.postalCode'));
	}

/**
 * testView method
 *
 * @return void
 */
	public function testView() {
        $this->Session->write('Watch.Order.email', 'PeterRHarris@teleworm.us');
        $this->Session->write('Watch.Address.postalCode', '61602');
        $this->testAction('/orders/view/1', ['method' => 'get', 'return' => 'vars']);
        $order = $this->vars['order'];
        $this->assertEquals($order['Order']['phone'], '260-423-3273');
        $this->assertEquals($order['Payment']['stripe_amount'], '69100');
        $this->assertEquals($order['Address'][0]['lastName'], 'Harris');
        $this->assertEquals($order['Watch'][0]['price'], '695.00');
	}

/**
 * testCheckout method
 *
 * @return void
 */
	public function testCheckout() {
        $order = array(
            'stripeToken' => 'tok_5dC2WijiayVQOK',
            'Address' => array(
                'select-country' => 'us',
                'billing' => $this->address,
            ),
            'Coupon' => array(
                'email' => '',
                'code' => ''
            ),
            'Shipping' => array(
                'option' => 'billing'
            ),
            'Order' => array(
                'email' => 'SandraPIrvin@armyspy.com',
                'phone' => '503-326-9436',
                'notes' => ''
            )
        );
        $Orders = $this->generate('Orders', array(
            'components' => array(
                'Session',
                'Cart' => array('cartEmpty', 'cartItemCount', 'cartItemIds'),
                'Stripe.Stripe' => array('charge'),
                'Session',
            )
        ));
        $Orders->Cart->expects($this->any())
            ->method('cartEmpty')
            ->will($this->returnValue(false));
        $Orders->Cart->expects($this->any())
            ->method('cartItemCount')
            ->will($this->returnValue(1));
        $Orders->Cart->expects($this->any())
            ->method('cartItemIds')
            ->will($this->returnValue(array(3)));
        $Orders->Stripe->expects($this->any())
            ->method('charge')
            ->will($this->returnValue(array(
                'stripe_paid' => 1,
                'stripe_id' => 'ch_5dBkC3pJMgqjkD',
                'stripe_last4' => '4242',
                'stripe_zip_check' => 'pass',
                'stripe_cvc_check' => 'pass',
                'stripe_amount' => '18300',
            )));

        $this->testAction(
            '/orders/checkout',
            array(
                'data' => $order, 
                'method' => 'post',
                'return' => 'vars', 
            )
        );
        
        $order = $this->Order->find('first', array(
            'order' => array(
                'Order.created' => 'DESC',
            )
        ));
       
        $this->assertEquals($order['Order']['email'], 'SandraPIrvin@armyspy.com');        
        $this->assertEquals($order['Address'][0]['country'], 'US');
        $this->assertEquals($order['Payment']['stripe_id'], 'ch_5dBkC3pJMgqjkD');
        $this->assertEquals($order['Watch'][0]['id'], 3);
	}

	public function testCheckoutNoState() {
        $this->address['state'] = '';
        $order = array(
            'stripeToken' => 'tok_5dC2WijiayVQOK',
            'Address' => array(
                'select-country' => 'us',
                'billing' => $this->address,
            ),
            'Coupon' => array(
                'email' => '',
                'code' => ''
            ),
            'Shipping' => array(
                'option' => 'billing'
            ),
            'Order' => array(
                'email' => 'SandraPIrvin@armyspy.com',
                'phone' => '503-326-9436',
                'notes' => ''
            )
        );
        $Orders = $this->generate('Orders', array(
            'components' => array(
                'Session',
                'Cart' => array('cartEmpty', 'cartItemCount', 'cartItemIds'),
                'Stripe.Stripe' => array('charge'),
                'Session',
            )
        ));
        $Orders->Cart->expects($this->any())
            ->method('cartEmpty')
            ->will($this->returnValue(false));
        $Orders->Cart->expects($this->any())
            ->method('cartItemCount')
            ->will($this->returnValue(1));
        $Orders->Cart->expects($this->any())
            ->method('cartItemIds')
            ->will($this->returnValue(array(3)));

        $results = $this->testAction(
            '/orders/checkout',
            array(
                'data' => $order, 
                'method' => 'post',
                'return' => 'vars', 
            )
        );
        
        $order = $this->Order->find('first', array(
            'order' => array(
                'Order.created' => 'DESC',
            )
        ));
       
        $this->assertEquals($order['Order']['email'], 'PeterRHarris@teleworm.us');        
        $this->assertEquals($order['Payment']['stripe_id'], 'ch_5dYcjsXUf5Gzy1');
        $this->assertEquals($order['Watch'][0]['id'], 1);
	}
    
    public function testCheckoutCoupon() {
        $order = array(
            'stripeToken' => 'tok_5dC2WijiayVQOK',
            'Address' => array(
                'select-country' => 'us',
                'billing' => $this->address,
            ),
            'Coupon' => array(
                'email' => 'SandraPIrvin@armyspy.com',
                'code' => 'fixedgood'
            ),
            'Shipping' => array(
                'option' => 'billing'
            ),
            'Order' => array(
                'email' => 'SandraPIrvin@armyspy.com',
                'phone' => '503-326-9436',
                'notes' => ''
            )
        );
        $Orders = $this->generate('Orders', array(
            'components' => array(
                'Session',
                'Cart' => array('cartEmpty', 'cartItemCount', 'cartItemIds'),
                'Stripe.Stripe' => array('charge'),
                'Session',
            )
        ));
        $Orders->Cart->expects($this->any())
            ->method('cartEmpty')
            ->will($this->returnValue(false));
        $Orders->Cart->expects($this->any())
            ->method('cartItemCount')
            ->will($this->returnValue(1));
        $Orders->Cart->expects($this->any())
            ->method('cartItemIds')
            ->will($this->returnValue(array(3)));
        $Orders->Stripe->expects($this->any())
            ->method('charge')
            ->will($this->returnValue(array(
                'stripe_paid' => 1,
                'stripe_id' => 'ch_5dBkC3pJMgqjkD',
                'stripe_last4' => '4242',
                'stripe_zip_check' => 'pass',
                'stripe_cvc_check' => 'pass',
                'stripe_amount' => '15300',
            )));

        $this->testAction(
            '/orders/checkout',
            array(
                'data' => $order, 
                'method' => 'post',
                'return' => 'vars', 
            )
        );
        
        $order = $this->Order->find('first', array(
            'order' => array(
                'Order.created' => 'DESC',
            )
        ));

        $this->assertEquals($order['Order']['email'], 'SandraPIrvin@armyspy.com');        
        $this->assertEquals(30, $order['Coupon']['amount']);
        $this->assertEquals($order['Address'][0]['country'], 'US');
        $this->assertEquals('15300', $order['Payment']['stripe_amount']);
        $this->assertEquals($order['Watch'][0]['id'], 3);
	}
/**
 * testAdd method
 *
 * @return void
 */
	public function testAdd() {
        $this->testAction('/orders/add/3', ['method' => 'get', 'return' => 'vars']);
        $session = $this->Session->read('Cart.items');
        $this->assertEquals(3, current($session));
        $this->assertContains('/orders/checkout', $this->headers['Location']); 
	}

    public function testAddNotSellable() {
        $this->testAction('/orders/add/1', ['method' => 'get', 'return' => 'vars']);
        $this->assertContains('/watches', $this->headers['Location']); 
    }

    public function testAddInCart() {
        $this->Session->write('Cart.items', [3]);
        $this->testAction('/orders/add/3', ['method' => 'get', 'return' => 'vars']);
        $message = $this->Session->read('Message.flash.message');
        $this->assertEquals('That item is already in your cart.', $message);
        $this->assertContains('/orders/checkout', $this->headers['Location']); 
    }

/**
 * testRemove method
 *
 * @return void
 */
	public function testRemove() {
		$this->markTestIncomplete('testRemove not implemented.');
	}

/**
 * testTotalCart method
 *
 * @return void
 */
	public function testTotalCart() {
        $_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
        $query = array(
            'data' => array(
                'Address' => array(
                    'select-country' => 'us',
                ),
                'Coupon' => array(
                    'email' => 'KeithMDecker@dayrep.com',
                    'code' => 'fixedgood',
                ),
            )
        );
        $url = Router::url(array('controller' => 'orders', 'action' => 'totalCart', 'ext' => 'json', '?' => $query));
        $options = array(
            'return' => 'vars'
        );

        $Orders = $this->generate('Orders', array(
            'components' => array(
                'Cart' => array('getSubTotal', 'couponAmount')
            )
        ));

        $Orders->Cart
            ->expects($this->once())
            ->method('getSubTotal')
            ->will($this->returnValue(400));

        $Orders->Cart
            ->expects($this->once())
            ->method('couponAmount')
            ->will($this->returnValue(60));

        $result = $this->testAction($url, $options);
        unset($_SERVER['HTTP_X_REQUESTED_WITH']);
        $this->assertEquals($result['total'], 348);        
	}

/**
 * testGetAddress method
 *
 * @return void
 */
	public function testGetUsAddress() {
        $_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
        $query = array(
            'country' => 'US', 
            'shipping' => 'billing',
        );
        $url = Router::url(array('controller' => 'orders', 'action' => 'getAddress', '?' => $query));
        $options = array(
            'return' => 'vars'
        );

        $result = $this->testAction($url, $options);
        $data = $result['data'];
        $this->assertEquals($query['shipping'], $data['shipping']);
        $this->assertEquals($query['country'], $data['country']);
        $this->assertContains('Alabama', $data['options'][$data['shipping']]);
        $this->assertContains('Wyoming', $data['options'][$data['shipping']]);
        $this->assertContains('State', $data['labels'][$data['shipping']]);
        $this->assertContains('Zip Code', $data['labels'][$data['shipping']]);
	}
    
	public function testGetUsShippingAddress() {
        $_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
        $query = array(
            'country' => 'US', 
            'shipping' => 'shipping',
        );
        $url = Router::url(array('controller' => 'orders', 'action' => 'getAddress', '?' => $query));
        $options = array(
            'return' => 'vars'
        );

        $result = $this->testAction($url, $options);
        $data = $result['data'];
        $this->assertEquals($query['shipping'], $data['shipping']);
        $this->assertEquals($query['country'], $data['country']);
        $this->assertArrayHasKey('United States', $data['options']['billing']);
        $this->assertArrayHasKey('Canada', $data['options']['billing']);
        $this->assertContains('Alabama', $data['options'][$data['shipping']]);
        $this->assertContains('Wyoming', $data['options'][$data['shipping']]);
        $this->assertContains('State or Province', $data['labels']['billing']);
        $this->assertContains('Zip/Postal Code', $data['labels']['billing']);
        $this->assertContains('State', $data['labels'][$data['shipping']]);
        $this->assertContains('Zip Code', $data['labels'][$data['shipping']]);
	}
    
	public function testGetCaAddress() {
        $_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
        $query = array(
            'country' => 'CA', 
            'shipping' => 'billing',
        );
        $url = Router::url(array('controller' => 'orders', 'action' => 'getAddress', '?' => $query));
        $options = array(
            'return' => 'vars'
        );

        $result = $this->testAction($url, $options);
        $data = $result['data'];
        $this->assertEquals($query['shipping'], $data['shipping']);
        $this->assertEquals($query['country'], $data['country']);
        $this->assertContains('Alberta', $data['options'][$data['shipping']]);
        $this->assertContains('Yukon Territory', $data['options'][$data['shipping']]);
        $this->assertContains('Province', $data['labels'][$data['shipping']]);
        $this->assertContains('Postal Code', $data['labels'][$data['shipping']]);
	}

	public function testGetCaShippingAddress() {
        $_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
        $query = array(
            'country' => 'CA', 
            'shipping' => 'shipping',
        );
        $url = Router::url(array('controller' => 'orders', 'action' => 'getAddress', '?' => $query));
        $options = array(
            'return' => 'vars'
        );

        $result = $this->testAction($url, $options);
        $data = $result['data'];
        $this->assertEquals($query['shipping'], $data['shipping']);
        $this->assertEquals($query['country'], $data['country']);
        $this->assertArrayHasKey('United States', $data['options']['billing']);
        $this->assertArrayHasKey('Canada', $data['options']['billing']);
        $this->assertContains('Alberta', $data['options'][$data['shipping']]);
        $this->assertContains('Yukon Territory', $data['options'][$data['shipping']]);
        $this->assertContains('State or Province', $data['labels']['billing']);
        $this->assertContains('Zip/Postal Code', $data['labels']['billing']);
        $this->assertContains('Province', $data['labels'][$data['shipping']]);
        $this->assertContains('Postal Code', $data['labels'][$data['shipping']]);
	}
    
    public function testGetOtherAddress() {
        $_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
        $query = array(
            'country' => 'OTHER', 
            'shipping' => 'billing',
        );
        $url = Router::url(array('controller' => 'orders', 'action' => 'getAddress', '?' => $query));
        $options = array(
            'return' => 'vars'
        );

        $result = $this->testAction($url, $options);
        $data = $result['data'];
        $this->assertEquals($query['shipping'], $data['shipping']);
        $this->assertEquals($query['country'], $data['country']);
        $this->assertEmpty($data['options']);
        $this->assertEmpty($data['labels'][$data['shipping']]['region']);
        $this->assertContains('Postal Code', $data['labels'][$data['shipping']]);
	}

    public function testGetOtherShippingAddress() {
        $_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
        $query = array(
            'country' => 'OTHER', 
            'shipping' => 'shipping',
        );
        $url = Router::url(array('controller' => 'orders', 'action' => 'getAddress', '?' => $query));
        $options = array(
            'return' => 'vars'
        );

        $result = $this->testAction($url, $options);
        $data = $result['data'];
        $this->assertEquals($query['shipping'], $data['shipping']);
        $this->assertEquals($query['country'], $data['country']);
        $this->assertEmpty($data['options']);
        $this->assertEmpty($data['labels']['billing']['region']);
        $this->assertEmpty($data['labels']['shipping']['region']);
        $this->assertContains('Postal Code', $data['labels']['shipping']);
        $this->assertContains('Postal Code', $data['labels']['billing']);
	}

    /**
     * Test that a form input value gets written to HTML
     */
    public function testGetAddressErrorsTags() {
        $_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
        $query = array(
            'country' => 'US', 
            'shipping' => 'billing',
        );
        $url = Router::url(array('controller' => 'orders', 'action' => 'getAddress', '?' => $query));
        $options = array(
            'return' => 'view',
        );

        unset($this->address['postalCode']); 
        $this->Session->write('Address.data.billing', $this->address);
        $errorMessage = 'Please enter a postal code.';
        $this->Session->write('Address.errors.billing.postalCode', array($errorMessage));
        $expectedTag = array(
            'tag' => 'input',
            'attributes' => array(
                'value' => '2215 Gateway Road',
                'id' => 'AddressBillingAddress1',
                'type' => 'text',
            ),
        );

        $result = $this->testAction($url, $options);
        $this->assertTag($expectedTag, $result);
    }

    /**
     * Test data sent to view has correct values on form error
     */
    public function testGetAddressErrorsData() {
        $_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
        $query = array(
            'country' => 'US', 
            'shipping' => 'billing',
        );
        $url = Router::url(array('controller' => 'orders', 'action' => 'getAddress', '?' => $query));

        unset($this->address['postalCode']); 
        $this->Session->write('Address.data.billing', $this->address);
        $errorMessage = 'Please enter a postal code.';
        $this->Session->write('Address.errors.billing.postalCode', array($errorMessage));
        $options = array(
            'return' => 'vars',
        );
        $result = $this->testAction($url, $options);
        $data = $result['data'];
        $this->assertEquals($query['shipping'], $data['shipping']);
        $this->assertEquals($query['country'], $data['country']);
        $this->assertEquals($errorMessage, $data['errors']['billing']['postalCode'][0]);
        $this->assertContains('Alabama', $data['options'][$data['shipping']]);
        $this->assertContains('Wyoming', $data['options'][$data['shipping']]);
        $this->assertContains('State', $data['labels'][$data['shipping']]);
        $this->assertContains('Zip Code', $data['labels'][$data['shipping']]);
	}

    public function testGetOtherShippingAddressErrorsTags() {
        $_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
        $query = array(
            'country' => 'OTHER', 
            'shipping' => 'shipping',
        );
        $url = Router::url(array('controller' => 'orders', 'action' => 'getAddress', '?' => $query));
        $options = array(
            'return' => 'view',
        );

        unset($this->address['state']); 
        unset($this->address['postalCode']); 
        $this->address['country'] = 'FR';
        $this->Session->write('Address.data.billing', $this->address);
        $this->Session->write('Address.data.shipping', $this->address);
        $errorMessage = 'Please enter a postal code.';
        $this->Session->write('Address.errors.billing.postalCode', array($errorMessage));
        $this->Session->write('Address.errors.shipping.postalCode', array($errorMessage));

        $result = $this->testAction($url, $options);
        $expectedTag = array(
            'tag' => 'input',
            'attributes' => array(
                'value' => 'Irwin',
                'id' => 'AddressBillingLastName'
            ),
        );
        $this->assertTag($expectedTag, $result);

        $expectedTag = array(
            'tag' => 'input',
            'attributes' => array(
                'value' => 'FR',
                'id' => 'AddressBillingCountry'
            ),
        );
        $this->assertTag($expectedTag, $result);
	}

    public function testGetOtherShippingAddressErrorsData() {
        $_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
        $query = array(
            'country' => 'OTHER', 
            'shipping' => 'shipping',
        );
        $url = Router::url(array('controller' => 'orders', 'action' => 'getAddress', '?' => $query));

        unset($this->address['state']); 
        unset($this->address['postalCode']); 
        $this->Session->write('Address.data.billing', $this->address);
        $this->Session->write('Address.data.shipping', $this->address);
        $errorMessage = 'Please enter a postal code.';
        $this->Session->write('Address.errors.billing.postalCode', array($errorMessage));
        $this->Session->write('Address.errors.shipping.postalCode', array($errorMessage));
        $options = array(
            'return' => 'vars'
        );

        $result = $this->testAction($url, $options);
        $data = $result['data'];
        $this->assertEquals($query['shipping'], $data['shipping']);
        $this->assertEquals($query['country'], $data['country']);
        $this->assertEquals($errorMessage, $data['errors']['billing']['postalCode'][0]);
        $this->assertEmpty($data['options']);
        $this->assertEmpty($data['labels']['billing']['region']);
        $this->assertEmpty($data['labels']['shipping']['region']);
        $this->assertContains('Postal Code', $data['labels']['shipping']);
        $this->assertContains('Postal Code', $data['labels']['billing']);
	}

/**
 * Test getCountry with US state 
 *
 * @return void
 */
	public function testGetCountryUs() {
        $_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
        $query = array(
            'data' => array(
                'Address' => array(
                    'billing' => array(
                        'state' => 'WI'
                    ),
                ),
            ),
        );
        $options = array(
            'return' => 'vars'
        );
        $url = Router::url(array('controller' => 'orders', 'action' => 'getCountry.json', '?' => $query));
        $result = $this->testAction($url, $options);
        $this->assertEquals('US', $result['data']['country']); 
        $this->assertEquals('Billing', $result['data']['type']);
	}

    /**
     * Test getCountry with Canadian province
     */
	public function testGetCountryCa() {
        $_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
        $query = array(
            'data' => array(
                'Address' => array(
                    'billing' => array(
                        'state' => 'AB'
                    ),
                ),
            ),
        );
        $options = array(
            'return' => 'vars'
        );
        $url = Router::url(array('controller' => 'orders', 'action' => 'getCountry.json', '?' => $query));
        $result = $this->testAction($url, $options);
        $this->assertEquals('CA', $result['data']['country']); 
        $this->assertEquals('Billing', $result['data']['type']);
	}

    /**
     * Test getCountry with invalid state
     */
    public function testGetCountryBad() {
        $_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
        $query = array(
            'data' => array(
                'Address' => array(
                    'billing' => array(
                        'state' => 'xxx'
                    ),
                ),
            ),
        );
        $options = array(
            'return' => 'vars'
        );
        $url = Router::url(array('controller' => 'orders', 'action' => 'getCountry.json', '?' => $query));
        $result = $this->testAction($url, $options);
        $this->assertEquals('', $result['data']['country']); 
        $this->assertEquals('Billing', $result['data']['type']);
	}
/**
 * testGetShippingChoice method
 *
 * @return void
 */
	public function testGetShippingChoice() {
		$this->markTestIncomplete('testGetShippingChoice not implemented.');
	}

/**
 * testAdminIndex method
 *
 * @return void
 */
	public function testAdminIndex() {
		$this->markTestIncomplete('testAdminIndex not implemented.');
	}

/**
 * testAdminView method
 *
 * @return void
 */
	public function testAdminView() {
		$this->markTestIncomplete('testAdminView not implemented.');
	}

/**
 * testAdminEdit method
 *
 * @return void
 */
	public function testAdminEdit() {
		$this->markTestIncomplete('testAdminEdit not implemented.');
	}

/**
 * testAdminResend method
 *
 * @return void
 */
	public function testAdminResend() {
		$this->markTestIncomplete('testAdminResend not implemented.');
	}

/**
 * testAdminDelete method
 *
 * @return void
 */
	public function testAdminDelete() {
		$this->markTestIncomplete('testAdminDelete not implemented.');
	}

/**
 * testEmailOrder method
 *
 * @return void
 */
	public function testEmailOrder() {
		$this->markTestIncomplete('testEmailOrder not implemented.');
	}

}
