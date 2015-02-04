<?php
App::uses('WatchesController', 'Controller');
App::uses('CakeRequest', 'Network');
App::uses('ComponentCollection', 'Controller');
App::uses('SessionComponent', 'Controller/Component');

class WatchesControllerTest extends ControllerTestCase {
    
	public $fixtures = array(
		'app.watch',
		'app.order',
		'app.coupon',
		'app.brand',
		'app.payment',
		//'app.invoice',
		//'app.invoice_item',
		'app.address',
		'app.detect',
		'app.detects_order',
		'app.image',
		'app.state',
		'app.province',
		'app.country',
		'app.page',
		//'app.content'
	);
    
    public function testIndex() {
        $result = $this->testAction('/watches/index');
        debug($result);
    }

    public function testOrder() {
        $this->ComponentCollection = new ComponentCollection();
        $Session = new SessionComponent($this->ComponentCollection);

        $Session->write('Order.email', 'PeterRHarris@teleworm.us');
        $Session->write('Address.postalCode', '61602');
        $results = $this->testAction('/watches/order/1', array(
            'method' => 'GET',
            'return' => 'vars',
        ));
        $this->assertNotEmpty($results['title']);
    }
}
