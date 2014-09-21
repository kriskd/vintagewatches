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
        $items = array(
            0 => array(
                'Watch' => array(
                    'price' => 100,
                ), 
            ),
            1 => array(
                'Watch' => array(
                    'price' => 125,
                ),
            ),
        );
        $result = $this->Cart->getSubTotal($items);
        $this->assertEqual($result, 225);
	}

/**
 * testCouponAmount method
 *
 * @return void
 */
	public function testCouponAmount() {
		$this->markTestIncomplete('testCouponAmount not implemented.');
	}

}
