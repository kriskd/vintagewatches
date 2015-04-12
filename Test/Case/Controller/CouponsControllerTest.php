<?php
App::uses('CouponsController', 'Controller');

/**
 * CouponsController Test Case
 *
 */
class CouponsControllerTest extends ControllerTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.coupon',
		'app.brand',
		'app.watch',
		'app.order',
		'app.payment',
		//'app.invoice',
		//'app.invoice_item',
		'app.address',
		'app.country',
		'app.region',
		//'app.detect',
		//'app.detects_order',
		'app.image',
		//'app.page',
		//'app.content',
		'app.cake_session',
	);

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
        $this->testAction('/admin/coupons/view/3', ['method' => 'get', 'return' => 'vars']);
		$this->assertEquals('notavailable', $this->vars['coupon']['Coupon']['code']);
		$this->assertCount(2, $this->vars['coupon']['Order']);
		$watch2 = Hash::extract($this->vars, 'coupon.Order.{n}[id=2]');
		// This tests Order afterFind which converts nulls to empty strings
		$this->assertNotNull($watch2[0]['shipDate']);
	}

	public function testAdminViewBrand() {
        $this->testAction('/admin/coupons/view/6', ['method' => 'get', 'return' => 'vars']);
		$this->assertEquals('brand', $this->vars['coupon']['Coupon']['code']);
		$this->assertCount(0, $this->vars['coupon']['Order']);
		$this->assertEquals('Favre Leuba', $this->vars['coupon']['Brand']['name']);
	}

/**
 * testAdminAdd method
 *
 * @return void
 */
	public function testAdminAdd() {
		$this->markTestIncomplete('testAdminAdd not implemented.');
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
 * testAdminDelete method
 *
 * @return void
 */
	public function testAdminDelete() {
		$this->markTestIncomplete('testAdminDelete not implemented.');
	}

/**
 * testDeleteModal method
 *
 * @return void
 */
	public function testDeleteModal() {
		$this->markTestIncomplete('testDeleteModal not implemented.');
	}

/**
 * testArchive method
 *
 * @return void
 */
	public function testArchive() {
		$this->markTestIncomplete('testArchive not implemented.');
	}
}
