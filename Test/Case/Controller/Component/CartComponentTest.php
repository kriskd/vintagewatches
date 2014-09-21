<?php
App::uses('Controller', 'Controller');
App::uses('ComponentCollection', 'Controller');
App::uses('CakeRequest', 'Network');
App::uses('CakeResponse', 'Network');
App::uses('Component', 'Controller');
App::uses('CartComponent', 'Controller/Component');

// A fake controller to test against
class FakeControllerTest extends Controller {
     public $paginate = null;
}

/**
 * CartComponent Test Case
 *
 */
class CartComponentTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
    public $fixtures = array(
        'app.order',
        'app.watch',
        'app.image',
        'app.payment',
        'app.address',
        'app.detect',
        'app.detectsorder',
        'app.coupon',
    );

    public $items = array(
        0 => array(
            'Watch' => array(
                'price' => 100,
                'brand_id' => 1,
            ), 
        ),
        1 => array(
            'Watch' => array(
                'price' => 125,
                'brand_id' => 2,
            ),
        ),
    );

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$Collection = new ComponentCollection();
		$this->Cart = new CartComponent($Collection);
        $CakeRequest = new CakeRequest();
        $CakeResponse = new CakeResponse();
        $this->Controller = new FakeControllerTest($CakeRequest, $CakeResponse);
        $this->Cart->startup($this->Controller);
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Cart);

		parent::tearDown();
	}

/**
 * testEmptyCart method
 *
 * @return void
 */
	public function testEmptyCart() {
		$this->markTestIncomplete('testEmptyCart not implemented.');
	}

/**
 * testCartEmpty method
 *
 * @return void
 */
	public function testCartEmpty() {
		$this->markTestIncomplete('testCartEmpty not implemented.');
	}

/**
 * testCartItemCount method
 *
 * @return void
 */
	public function testCartItemCount() {
		$this->markTestIncomplete('testCartItemCount not implemented.');
	}

/**
 * testCartItemIds method
 *
 * @return void
 */
	public function testCartItemIds() {
		$this->markTestIncomplete('testCartItemIds not implemented.');
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
 * testInCart method
 *
 * @return void
 */
	public function testInCart() {
		$this->markTestIncomplete('testInCart not implemented.');
	}

/**
 * testGetShippingAmount method
 *
 * @return void
 */
	public function testGetShippingAmount() {
		$this->markTestIncomplete('testGetShippingAmount not implemented.');
	}

/**
 * testTotalCart method
 *
 * @return void
 */
	public function testTotalCart() {
		$this->markTestIncomplete('testTotalCart not implemented.');
	}

/**
 * testGetSubTotal method
 *
 * @return void
 */
	public function testGetSubTotal() {
        $result = $this->Cart->getSubTotal($this->items);
        $this->assertEqual($result, 225);
	}
    
    public function testGetSubTotalBrand() {
        $result = $this->Cart->getSubTotal($this->items, 1);
        $expected = 100;
        $this->assertEquals($result, $expected);
    }

/**
 * testCouponAmount method
 *
 * @return void
 */
	public function testCouponAmount() {
        $shipping = 8;
        $result = $this->Cart->couponAmount($this->items, $shipping);
        $this->assertEquals($result, 0);
	}

    public function testCouponAmountFixed() {
        $coupon = array(
            'Coupon' => array(
                'type' => 'fixed',
                'amount' => 10,
                'brand_id' => null,
            )
        );
        $shipping = 8;
        $result = $this->Cart->couponAmount($this->items, $shipping, $coupon);
        $this->assertEquals($result, 10);
    }

    public function testCouponAmountBigFixed() {
        $coupon = array(
            'Coupon' => array(
                'type' => 'fixed',
                'amount' => 1000,
                'brand_id' => null,
            )
        );
        $shipping = 8;
        $result = $this->Cart->couponAmount($this->items, $shipping, $coupon);
        $this->assertEquals($result, 233);
    }
    
    public function testCouponAmountPercentage() {
        $coupon = array(
            'Coupon' => array(
                'type' => 'percentage',
                'amount' => .1,
                'brand_id' => null,
            )
        );
        $shipping = 8;
        $result = $this->Cart->couponAmount($this->items, $shipping, $coupon);
        $this->assertEquals($result, 22.5);
    }
    
    public function testCouponAmountPercentageBrand() {
        $coupon = array(
            'Coupon' => array(
                'type' => 'percentage',
                'amount' => .1,
                'brand_id' => 1,
            )
        );
        $shipping = 8;
        $result = $this->Cart->couponAmount($this->items, $shipping, $coupon);
        $this->assertEquals($result, 10);
    }
    
    public function testCouponAmountFixedBrand1() {
        $coupon = array(
            'Coupon' => array(
                'type' => 'fixed',
                'amount' => 110,
                'brand_id' => 1,
            )
        );
        $shipping = 8;
        $result = $this->Cart->couponAmount($this->items, $shipping, $coupon);
        $this->assertEquals($result, 108);
    }
    
    public function testCouponAmountFixedBrand2() {
        $coupon = array(
            'Coupon' => array(
                'type' => 'fixed',
                'amount' => 110,
                'brand_id' => 2,
            )
        );
        $shipping = 8;
        $result = $this->Cart->couponAmount($this->items, $shipping, $coupon);
        $this->assertEquals($result, 110);
    }
}
