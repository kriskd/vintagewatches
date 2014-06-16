<?php
App::uses('WatchesController', 'Controller');

class WatchesControllerTest extends ControllerTestCase {
    public function setUp() {
        $Watches = $this->generate('Watches', array(
            'components' => array(
                            'Session',
                    )   
        ));
    }
    
    public function testIndex() {
        $result = $this->testAction('/watches/index');
        debug($result);
    }
}
