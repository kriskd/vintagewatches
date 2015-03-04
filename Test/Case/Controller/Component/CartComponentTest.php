<?php
App::uses('Controller', 'Controller');
App::uses('ComponentCollection', 'Controller');
App::uses('CakeRequest', 'Network');
App::uses('CakeResponse', 'Network');
App::uses('Component', 'Controller');
App::uses('CartComponent', 'Controller/Component');
App::uses('SessionComponent', 'Controller/Component');

// A fake controller to test against
class FakeControllerTest extends Controller {
    public $components = array('Session');
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
        'app.cake_session',
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
        $this->Controller->Components->init($this->Controller);
        $this->Cart->startup($this->Controller);
        //$this->Controller->Session = new SessionComponent($Collection);
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
        $this->Controller->Session->write('Cart.items', [3,5]);
        $this->Cart->initialize($this->Controller);
        $this->assertEquals([3,5], $this->Cart->items);
        $this->assertEquals([3,5], $this->Controller->Session->read('Cart.items'));
        $this->Cart->emptyCart();
        $this->assertEmpty($this->Cart->items);
        $this->assertEmpty($this->Controller->Session->read('Cart'));
	}

/**
 * testCartEmpty method
 *
 * @return void
 */
	public function testCartNotEmpty() {
        $this->Controller->Session->write('Cart.items', [3,5]);
        $this->Cart->initialize($this->Controller);
        $result = $this->Cart->cartEmpty();
        $this->assertFalse($result);
	}

	public function testCartEmpty() {
        $this->Controller->Session->write('Cart.items', []);
        $this->Cart->initialize($this->Controller);
        $result = $this->Cart->cartEmpty();
        $this->assertTrue($result);
	}

/**
 * testCartItemCount method
 *
 * @return void
 */
	public function testCartItemCount() {
        $this->Controller->Session->write('Cart.items', [3,5]);
        $this->Cart->initialize($this->Controller);
        $result = $this->Cart->cartItemCount();
        $this->assertEquals(2, $result);
	}

	public function testCartItemCountEmpty() {
        $this->Controller->Session->write('Cart.items', null);
        $this->Cart->initialize($this->Controller);
        $result = $this->Cart->cartItemCount();
        $this->assertEmpty($result);
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
 * ./Console/cake test app Controller/Component/CartComponent --stderr --filter testInCart
 *
 * @return void
 */
	public function testInCart() {
        $this->Controller->Session->write('Cart.items', [3,5]);
        $this->Cart->initialize($this->Controller);
        $result = $this->Cart->inCart(3);
        $this->assertTrue($result);
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
