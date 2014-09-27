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

    public function testValidFixed() {
        $result = $this->Coupon->valid('fixedgood', 'ClarenceAMartinez@teleworm.us', 8, array(3,5));
        $this->assertArrayHasKey('Coupon', $result);
    }
    
    public function testValidPercentage() {
        $result = $this->Coupon->valid('percentagegood', 'ClarenceAMartinez@teleworm.us', 8, array(3,5));
        $this->assertArrayHasKey('Coupon', $result);
    }

    public function testValidBadCode() {
        $result = $this->Coupon->valid('foo', 'ClarenceAMartinez@teleworm.us', 8, array(3,5));
        $this->assertEquals('This coupon is not valid.', $result['message']);
    }

    public function testValidArchived() {
        $result = $this->Coupon->valid('archived', 'ClarenceAMartinez@teleworm.us', 8, array(3,5));
        $this->assertEquals('This coupon is not valid.', $result['message']);
    }

    public function testValidNotAssigned() {
        $result = $this->Coupon->valid('assigned', 'ClarenceAMartinez@teleworm.us', 8, array(3,5));
        $this->assertEquals('This coupon is not valid.', $result['message']);
    }
    
    public function testValidAssigned() {
        $result = $this->Coupon->valid('assigned', 'PhilipAShrum@jourrapide.com', 8, array(3,5));
        $this->assertArrayHasKey('Coupon', $result);
    }
    
    public function testValidNotAvailable() {
        $result = $this->Coupon->valid('notavailable', 'ClarenceAMartinez@teleworm.us', 8, array(3,5));
        $this->assertEquals('This coupon is not valid.', $result['message']);
    }
    
    public function testValidAvailable() {
        $result = $this->Coupon->valid('notavailable', 'PeterRHarris@teleworm.us', 8, array(3,5));
        $this->assertEquals('This coupon is not valid.', $result['message']);
    }
    
    public function testValidExpired() {
        $result = $this->Coupon->valid('expired', 'ClarenceAMartinez@teleworm.us', 8, array(3,5));
        $this->assertEquals('This coupon is expired.', $result['message']);
    }
    
    public function testValidBrand() {
        $result = $this->Coupon->valid('brand', 'ClarenceAMartinez@teleworm.us', 8, array(3,5));
        $this->assertContains('Order must include at least one', $result['message']);
    }

    public function testValidBrandMinimum() {
        $result = $this->Coupon->valid('brandfixed', 'ClarenceAMartinez@teleworm.us', 8, array(6));
        $this->assertContains('watch(es) must be at least', $result['message']);
    }

    public function testValidMinimum() {
        $result = $this->Coupon->valid('minimum', 'ClarenceAMartinez@teleworm.us', 8, array(6));
        $this->assertContains('You have not met the minimum order of', $result['message']);
    }
    
    public function testValidBigFixed() {
        $result = $this->Coupon->valid('bigfixed', 'ClarenceAMartinez@teleworm.us', 8, array(6));
        $this->assertContains('Order total must be at least', $result['message']);
    }
}
