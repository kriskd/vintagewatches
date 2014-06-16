<?php
App::uses('FakerTestCase', 'CakeFaker.Lib');
App::Uses('Order', 'Model');

class OrderTest extends FakerTestCase {
    
    public $fixtures = array(
        'app.order',
        'app.watch',
        'app.image',
        'app.payment',
        'app.address',
        'app.detect',
        'app.detectsorder',
    );

    public function setUp() {
        parent::setUp();
        $this->Order = ClassRegistry::init('Order');
    }
    
    public function testGetSubTotal() {
        $result = $this->Order->getSubTotal(array(
            array('Watch' => array(
                'price' => 250
            )),
            array('Watch' => array(
                'price' => 175
            )),
        ));
        $expected = 425;
        $this->assertEquals($result, $expected);
    }

    public function testGetOrder() {
        
        $result = $this->Order->getOrder(1);

        $expected = array(
            'Order' => array(
                'id' => 1, 
            ),
        );
        $this->assertEquals($result['Order']['id'], $expected['Order']['id']);
    }
}
