<?php
App::uses('WatchesController', 'Controller');
App::uses('CakeRequest', 'Network');
App::uses('ComponentCollection', 'Controller');
App::uses('SessionComponent', 'Controller/Component');
App::uses('Watch', 'Model');

class WatchesControllerTest extends ControllerTestCase {

	public $fixtures = array(
		'app.watch',
        'app.acquisition',
        'app.source',
		'app.order',
		'app.coupon',
		'app.brand',
		'app.payment',
		//'app.invoice',
		//'app.invoice_item',
		'app.address',
		//'app.detect',
		//'app.detects_order',
		'app.image',
		'app.state',
		'app.province',
		'app.country',
		'app.page',
		//'app.content'
        'app.cake_session',
	);

    public function setUp() {
        parent::setUp();
        $this->Watch = ClassRegistry::init('Watch');
        $this->ComponentCollection = new ComponentCollection();
        $this->Session = new SessionComponent($this->ComponentCollection);
        $this->Watches = $this->generate('Watches', array(
            'components' => array(
                'Auth' => array('user')
            )
        ));
        $this->Watches->Auth->staticExpects($this->any())
            ->method('user')
            ->will($this->returnValue(true));

    }

    public function testIndex() {
		$this->markTestIncomplete('testIndex not implemented.');
        $result = $this->testAction('/watches/index');
    }

    public function testOrder() {
        $this->Session->write('Watch.Order.email', 'PeterRHarris@teleworm.us');
        $this->Session->write('Watch.Address.postalCode', '61602');

        $results = $this->testAction('/watches/order/1', array(
            'method' => 'GET',
            'return' => 'vars',
        ));

        $this->assertNotEmpty($results['title']);
    }

/**
 * testAdminIndex method
 *
 * @return void
 */
	public function testAdminIndex() {
        $this->testAction('/admin/watches', array(
            'method' => 'GET',
            'return' => 'vars',
        ));

        $this->assertCount(10, $this->vars['watches']);
	}

    public function testAdminIndexBrand() {
        $query = array(
            'brand_id' => '3', 
        );
        $url = Router::url(array('controller' => 'watches', 'action' => 'index', 'admin' => true, '?' => $query));
        $this->testAction($url, ['method' => 'get', 'return' => 'vars']);
        $this->assertCount(2, $this->vars['watches']);
        $ids = Hash::extract($this->vars['watches'], '{n}.Watch.id');
        $this->assertContains(5, $ids);
        $this->assertContains(9, $ids);
    }
}
