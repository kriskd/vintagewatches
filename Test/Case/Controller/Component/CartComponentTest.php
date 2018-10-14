<?php
App::uses('Controller', 'Controller');
App::uses('ComponentCollection', 'Controller');
App::uses('CakeRequest', 'Network');
App::uses('CakeResponse', 'Network');
App::uses('Component', 'Controller');
App::uses('CartComponent', 'Controller/Component');
App::uses('SessionComponent', 'Controller/Component');
App::uses('Watch', 'Model');

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
		'app.consignment',
		'app.purchase',
		'app.brand',
        'app.cake_session',
    );

    public $watches = array(
        0 => array(
            'Watch' => array(
                'price' => 100,
                'brand_id' => 1,
            ),
            'Brand' => array(
                'name' => 'foo',
            ),
        ),
        1 => array(
            'Watch' => array(
                'price' => 125,
                'brand_id' => 2,
            ),
            'Brand' => array(
                'name' => 'bar',
            ),
        ),
    );

    public $items = [];

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
        $this->items = [
            [
                'Item' => [
                    'price' => '29.95',
                    'subtotal' => '29.95'
                ],
            ],
        ];
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
        $this->Controller->Session->write('Cart.watches', [3,5]);
        $this->Cart->initialize($this->Controller);
        $this->assertEquals([3,5], $this->Cart->watches);
        $this->assertEquals([3,5], $this->Controller->Session->read('Cart.watches'));
        $this->Cart->emptyCart();
        $this->assertEmpty($this->Cart->watches);
        $this->assertEmpty($this->Controller->Session->read('Cart'));
	}

/**
 * testCartEmpty method
 *
 * @return void
 */
	public function testCartNotEmpty() {
        $this->Controller->Session->write('Cart.watches', [3,5]);
        $this->Cart->initialize($this->Controller);
        $result = $this->Cart->cartEmpty();
        $this->assertFalse($result);
	}

	public function testCartEmpty() {
        $this->Controller->Session->write('Cart.watches', []);
        $this->Cart->initialize($this->Controller);
        $result = $this->Cart->cartEmpty();
        $this->assertTrue($result);
	}

/**
 * testCartWatchCount method
 *
 * @return void
 */
	public function testCartWatchCount() {
        $this->Controller->Session->write('Cart.watches', [3,5]);
        $this->Cart->initialize($this->Controller);
        $result = $this->Cart->cartWatchCount();
        $this->assertEquals(2, $result);
	}

	public function testCartWatchCountEmpty() {
        $this->Controller->Session->write('Cart.watches', null);
        $this->Cart->initialize($this->Controller);
        $result = $this->Cart->cartWatchCount();
        $this->assertEmpty($result);
	}

/**
 * testCartWatchIds method
 *
 * @return void
 */
	public function testCartWatchIds() {
        $this->Controller->Session->write('Cart.watches', [3,5]);
        $this->Cart->initialize($this->Controller);
        $result = $this->Cart->cartWatchIds();
        $this->assertEquals([3,5], $result);
	}

/**
 * testAdd method
 *
 * @return void
 */
	public function testAdd() {
	    $this->Cart->add(3);
        $this->assertEquals([3], $this->Controller->Session->read('Cart.watches'));
	}

/**
 * testRemove method
 *
 * @return void
 */
	public function testRemove() {
        $this->Controller->Session->write('Cart.watches', [3,5]);
        $this->Cart->initialize($this->Controller);
        $this->Cart->remove('Watch', 3);
        $this->assertEquals([5], $this->Controller->Session->read('Cart.watches'));
	}

    public function testRemoveFail() {
        $this->Controller->Session->destroy('Cart');
        $this->Cart->initialize($this->Controller);
        $this->Cart->remove('Watch', 3);
        $this->assertEmpty($this->Controller->Session->read('Cart.watches'));
    }

/**
 * testInCart method
 * ./Console/cake test app Controller/Component/CartComponent --stderr --filter testInCart
 *
 * @return void
 */
	public function testInCart() {
        $this->Controller->Session->write('Cart.watches', [3,5]);
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
        $this->Controller->Session->write('Cart.watches', [3,5]);
        $this->Cart->initialize($this->Controller);
	    $result = $this->Cart->getShippingAmount('us');
        $this->assertEquals(8, $result);
	    $result = $this->Cart->getShippingAmount('ca');
        $this->assertEquals(38, $result);
	    $result = $this->Cart->getShippingAmount('');
        $this->assertEmpty($result);
	    $result = $this->Cart->getShippingAmount('other');
        $this->assertEquals(45, $result);
	}

    public function testGetSecondaryCountry() {
        $result = $this->Cart->getSecondaryCountry('US');
        $this->assertEquals('CA', $result);
        $result = $this->Cart->getSecondaryCountry('CA');
        $this->assertEquals('US', $result);
        $result = $this->Cart->getSecondaryCountry('OTHER');
        $this->assertEquals('OTHER', $result);
        $result = $this->Cart->getSecondaryCountry('foo');
        $this->assertEmpty($result);
    }

/**
 * testTotalCart method
 *
 * @return void
 */
	public function testTotalCart() {
        $result = $this->Cart->totalCart(850, 8, 85);
        $this->assertEquals(773, $result);
	}

/**
 * testGetSubTotal method
 *
 * @return void
 */
	public function testGetSubTotal() {
        $result = $this->Cart->getSubTotal($this->watches);
        $this->assertEqual($result, 225);
	}

    public function testGetSubTotalBrand() {
        $result = $this->Cart->getSubTotal($this->watches, 1);
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
        $result = $this->Cart->couponAmount($this->watches, $this->items, $shipping);
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
        $result = $this->Cart->couponAmount($this->watches, [], $shipping, $coupon);
        $this->assertEquals($result, 10);
    }

    public function testCouponAmountFixedItems() {
        $couponAmount = 30;
        $coupon = array(
            'Coupon' => array(
                'type' => 'fixed',
                'amount' => $couponAmount,
                'brand_id' => null,
            )
        );
        $result = $this->Cart->couponAmount([], $this->items, 3, $coupon);
        $this->assertEquals($result, $couponAmount);
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
        $result = $this->Cart->couponAmount($this->watches, [], $shipping, $coupon);
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
        $result = $this->Cart->couponAmount($this->watches, [], $shipping, $coupon);
        $this->assertEquals($result, 22.5);
    }

    public function testCouponAmountPercentageItems() {
        $coupon = array(
            'Coupon' => array(
                'type' => 'percentage',
                'amount' => .15,
                'brand_id' => null,
            )
        );
        $result = $this->Cart->couponAmount([], $this->items, 6, $coupon);
        $this->assertEquals($result, 4.49);
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
        $result = $this->Cart->couponAmount($this->watches, [], $shipping, $coupon);
        $this->assertEquals($result, 10);
    }

    public function testCouponAmountPercentageBrandItems() {
        $coupon = array(
            'Coupon' => array(
                'type' => 'percentage',
                'amount' => .1,
                'brand_id' => 1,
            )
        );
        $result = $this->Cart->couponAmount([], $this->items, 6, $coupon);
        $this->assertEquals($result, 0);
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
        $result = $this->Cart->couponAmount($this->watches, [], $shipping, $coupon);
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
        $result = $this->Cart->couponAmount($this->watches, [], $shipping, $coupon);
        $this->assertEquals($result, 110);
    }

    /**
     * Test bad coupon type
     */
    public function testCouponAmountFixedType() {
        $coupon = array(
            'Coupon' => array(
                'type' => 'foo',
                'amount' => 10,
                'brand_id' => null,
            )
        );
        $shipping = 8;
        $result = $this->Cart->couponAmount($this->watches, [], $shipping, $coupon);
        $this->assertEmpty($result);
    }

    public function testStripeDescription() {
        $result = $this->Cart->stripeDescription($this->watches, []);
        $this->assertEquals('foo,bar', $result);
    }

    public function testFormatAddress() {
        $address = array(
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
        );
        $expected = array(
            array(
                'firstName' => 'Sandra',
                'lastName' => 'Irwin',
                'company' => '',
                'address1' => '2215 Gateway Road',
                'address2' => '',
                'city' => 'Portland',
                'state' => 'OR',
                'postalCode' => '97205',
                'country' => 'US',
                'type' => 'billing'
            )
        );
        $result = $this->Cart->formatAddresses($address);
        $this->assertEquals($expected, $result);
    }
    
    public function testFormatAddressShipping() {
        $address = array(
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
            ),
            'shipping' => array(
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
        );
        $expected = array(
            array(
                'firstName' => 'Sandra',
                'lastName' => 'Irwin',
                'company' => '',
                'address1' => '2215 Gateway Road',
                'address2' => '',
                'city' => 'Portland',
                'state' => 'OR',
                'postalCode' => '97205',
                'country' => 'US',
                'type' => 'billing'
            ),
            array(
                'firstName' => 'Sandra',
                'lastName' => 'Irwin',
                'company' => '',
                'address1' => '2215 Gateway Road',
                'address2' => '',
                'city' => 'Portland',
                'state' => 'OR',
                'postalCode' => '97205',
                'country' => 'US',
                'type' => 'shipping'
            )
        );
        $result = $this->Cart->formatAddresses($address);
        $this->assertEquals($expected, $result);
    }

    public function testSetCheckoutData() {
        $data = array(
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
                    'country' => 'US',
                ),
            ),
            'Coupon' => array(
                'email' => '',
                'code' => ''
            ),
            'Shipping' => array(
                'option' => 'billing'
            ),
            'Order' => array(
                'email' => 'sandra@mailinator.com',
                'phone' => '',
                'notes' => ''
            )
        );
        $this->Cart->setCheckoutData($data);
        $this->assertEquals($data['Address'], $this->Controller->Session->read('Address.data'));
        $this->assertEquals($data['Shipping']['option'], $this->Controller->Session->read('Shipping.option'));
        $this->assertEquals($data['Order'], $this->Controller->Session->read('Order'));
    }

    public function testSetCheckoutDataErrors() {
        $data = array(
            'Address' => array(
                'select-country' => 'us',
                'billing' => array(
                    'firstName' => 'Sandra',
                    'lastName' => '',
                    'company' => '',
                    'address1' => '2215 Gateway Road',
                    'address2' => '',
                    'city' => 'Portland',
                    'state' => 'OR',
                    'postalCode' => '97205',
                    'country' => 'US',
                ),
            ),
            'Coupon' => array(
                'email' => '',
                'code' => ''
            ),
            'Shipping' => array(
                'option' => 'billing'
            ),
            'Order' => array(
                'email' => 'sandra@mailinator.com',
                'phone' => '',
                'notes' => ''
            )
        );
        $errors = array(
            'billing' => array(
                'lastName' => 'Please enter your last name.'
            ),
        );
        $this->Cart->setCheckoutData($data, $errors);
        $this->assertEquals($data['Address'], $this->Controller->Session->read('Address.data'));
        $this->assertEquals($errors, $this->Controller->Session->read('Address.errors'));
    }

	public function testCheckActive() {
        $this->Controller->Session->write('Cart.items', [3,5]);
        $this->Cart->initialize($this->Controller);
        $this->Watch = ClassRegistry::init('Watch');
		$watches = $this->Watch->getCartWatches([3,5]);
		$result = $this->Cart->checkActive($watches);
		$this->assertTrue($result);
	}

	public function testCheckActiveFail() {
        $this->Controller->Session->write('Cart.items', [3,4]);
        $this->Cart->initialize($this->Controller);
        $this->Watch = ClassRegistry::init('Watch');
		$watches = $this->Watch->getCartWatches([3,4]);
		$result = $this->Cart->checkActive($watches);
		$this->assertFalse($result);
	}
}
