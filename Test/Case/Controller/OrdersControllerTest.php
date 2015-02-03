<?php
App::uses('OrdersController', 'Controller');
App::uses('CakeRequest', 'Network');

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
		'app.detects_order',
		'app.image',
		'app.state',
		'app.province',
		'app.country',
		//'app.page',
		//'app.content'
	);

/**
 * testIndex method
 *
 * @return void
 */
	public function testIndex() {
		$this->markTestIncomplete('testIndex not implemented.');
	}

/**
 * testView method
 *
 * @return void
 */
	public function testView() {
		$this->markTestIncomplete('testView not implemented.');
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
                'billing' => array(
                    'firstName' => 'Sandra',
                    'lastName' => 'Irwin',
                    'company' => '',
                    'address1' => '2215 Gateway Road',
                    'address2' => '',
                    'city' => 'Portland',
                    'state' => 'OR',
                    'postalCode' => '97205',
                    'country' => 'US'
                )
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
                'Cart' => array('cartEmpty', 'cartItemCount', 'cartItemIds')
            )
        ));
        $Orders->Cart->staticExpects($this->any())
            ->method('cartEmpty')
            ->will($this->returnValue(false));
        $Orders->Cart->staticExpects($this->any())
            ->method('cartItemCount')
            ->will($this->returnValue(1));
        $Orders->Cart->staticExpects($this->any())
            ->method('cartItemIds')
            ->will($this->returnValue(array(1)));

        $mock = $this->getMock('Stripe');
        $mock->expects($this->any()) // Error Cannot redeclare class Stripe ??? 
            ->method('charge')
            ->will($this->returnValue(array(
                'stripe_paid' => 1,
                'stripe_id' => 'ch_5dBkC3pJMgqjkD',
                'stripe_last4' => '4242',
                'stripe_zip_check' => 'pass',
                'stripe_cvc_check' => 'pass',
                'stripe_amount' => '18300', // Bogus number for now
            )));
        $result = $this->testAction(
            '/orders/checkout',
            array('data' => $order, 'method' => 'post')
        );
        debug($result); exit;
		//$this->markTestIncomplete('testCheckout not implemented.');
	}

/**
 * testAdd method
 *
 * @return void
 */
	public function testAdd() {
		$this->markTestIncomplete('testAdd not implemented.');
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
	public function xtestTotalCart() {
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
                'RequestHandler' => array(),
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
        $this->assertEquals($result['data']['total'], 348);        
	}

/**
 * testGetAddress method
 *
 * @return void
 */
	public function testGetAddress() {
		$this->markTestIncomplete('testGetAddress not implemented.');
	}

/**
 * testGetCountry method
 *
 * @return void
 */
	public function testGetCountry() {
		$this->markTestIncomplete('testGetCountry not implemented.');
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
