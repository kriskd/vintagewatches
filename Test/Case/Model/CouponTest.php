<?php
App::uses('Coupon', 'Model');

/**
 * Coupon Test Case
 *
 */
class CouponTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.coupon',
		'app.order',
		'app.payment',
		//'app.invoice',
		//'app.invoice_item',
		'app.address',
		'app.watch',
		'app.brand',
		'app.image',
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
		$this->Coupon = ClassRegistry::init('Coupon');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Coupon);

		parent::tearDown();
	}

    public function testValid() {
        $result = $this->Coupon->valid('third', 'ClarenceAMartinez@teleworm.us', 8, array(1,2));
        $this->assertArrayHasKey('Coupon', $result);
    }
}
