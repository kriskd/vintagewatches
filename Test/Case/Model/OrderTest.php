<?php
App::Uses('Order', 'Model');

class OrderTest extends CakeTestCase {
    
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
}
