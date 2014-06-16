<?php
App::uses('FakerTestCase', 'CakeFaker.Lib');
App::Uses('Watch', 'Model');

class WatchTest extends FakerTestCase {
    
    public $fixtures = array(
        'app.order',
        'app.watch',
        'app.brand',
        'app.image',
        'app.payment',
        'app.address',
    );

    public function setUp() {
        parent::setUp();
        $this->Order = ClassRegistry::init('Watch');
    }
}
