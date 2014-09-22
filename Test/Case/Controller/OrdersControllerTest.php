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
		'app.page',
		'app.content'
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
		$this->markTestIncomplete('testCheckout not implemented.');
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
	public function testTotalCart() {
        $_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
        $query = array(
            'data' => array(
                'Address' => array(
                    'select-country' => 'us',
                ),
                'Coupon' => array(
                    'email' => 'KeithMDecker@dayrep.com',
                    'code' => 'third',
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
