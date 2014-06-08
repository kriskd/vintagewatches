<?php
App::Uses('Order', 'Model');

class OrderTest extends CakeTestCase {
    
    public $fixtures = array(
        'app.order',
        'app.watch',
        'app.image',
        'app.payment',
        'app.address'
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
        $result = $this->Order->getOrder(28);
        $expected = array(
            'Order' => array(
                'id' => 28, 
            ),
            'Address' => array(
                    array(
                        'id' => 94,
                )
            ),
            'Watch' => array(
                'id' => 44,
                'Image' => array(
                    array(
                        'id' => 289,
                    ),
                    array(
                        'id' => 290,
                    ),
                    array(
                        'id' => 291,
                    ),
                    array(
                        'id' => 292,
                    ),
                    array(
                        'id' => 293,
                    ),
                    array(
                        'id' => 294,
                    ),
                    array(
                        'id' => 295,
                    ),    
                ),
            ),
            'Payment' => array(
                'id' => 60,
            ),
        );
        $this->assertEquals($result['Watch']['id'], $expected['Watch']['id']);
    }
}
