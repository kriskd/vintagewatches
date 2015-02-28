<?php
App::uses('Order', 'Model');

/**
 * Order Test Case
 *
 */
class OrderTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.order',
		'app.coupon',
		'app.brand',
		'app.watch',
		'app.image',
		'app.payment',
		//'app.invoice',
		//'app.invoice_item',
		'app.address',
		'app.country',
		'app.region',
		'app.detect',
		'app.detects_order'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Order = ClassRegistry::init('Order');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Order);

		parent::tearDown();
	}

/**
 * testGetOrder method
 *
 * @return void
 */
	public function testGetOrder() {
        $result = $this->Order->getOrder(1);
        $expected = array(
            'Order' => array(
                'id' => 1, 
            ),
        );
        $this->assertEquals($result['Order']['id'], $expected['Order']['id']);
	}

/**
 * testGetCustomerOrderOptions method
 *
 * @return void
 */
	public function testGetCustomerOrderOptions() {
		$this->markTestIncomplete('testGetCustomerOrderOptions not implemented.');
	}

}
