<?php
App::uses('Watch', 'Model');

/**
 * Watch Test Case
 *
 */
class WatchTest extends CakeTestCase {

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
		'app.country',
		'app.region',
		//'app.detect',
		//'app.detects_order',
		'app.image'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Watch = ClassRegistry::init('Watch');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Watch);

		parent::tearDown();
	}

/**
 * testGetWatch method
 *
 * @return void
 */
	public function testGetWatch() {
		$this->markTestIncomplete('testGetWatch not implemented.');
	}

/**
 * testGetCartWatches method
 *
 * @return void
 */
	public function testGetCartWatches() {
		$this->markTestIncomplete('testGetCartWatches not implemented.');
	}

/**
 * testGetWatchesConditions method
 *
 * @return void
 */
	public function testGetWatchesConditions() {
		$this->markTestIncomplete('testGetWatchesConditions not implemented.');
	}

/**
 * testSellable method
 *
 * @return void
 */
	public function testSellable() {
		$this->markTestIncomplete('testSellable not implemented.');
	}

/**
 * testHasOrder method
 *
 * @return void
 */
	public function testHasOrder() {
		$this->markTestIncomplete('testHasOrder not implemented.');
	}

/**
 * testGetWatches method
 *
 * @return void
 */
	public function testGetWatches() {
		$this->markTestIncomplete('testGetWatches not implemented.');
	}

/**
 * testStoreOpen method
 *
 * @return void
 */
	public function testStoreOpen() {
		$this->markTestIncomplete('testStoreOpen not implemented.');
	}

/**
 * testImagePrimaryUrl method
 *
 * @return void
 */
	public function testImagePrimaryUrl() {
		$this->markTestIncomplete('testImagePrimaryUrl not implemented.');
	}

/**
 * testSumWatchPrices method
 *
 * @return void
 */
	public function testSumWatchPrices() {
        $result = $this->Watch->sumWatchPrices([3,5]);	
        $this->assertEquals(970, $result);
	}

}
