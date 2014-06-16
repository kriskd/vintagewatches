<?php
App::uses('WatchesController', 'Controller');

class WatchesControllerTest extends ControllerTestCase {
    
    public $fixtures = array(
        'app.order',
        'app.watch',
        'app.brand',
        'app.image',
        'app.payment',
        'app.address',
    );
    public function setUp() {
        $Watches = $this->generate('Watches', array(
            'components' => array(
                'Auth' => array('user'),
            )   
        ));

        /*$newUserData = array(
            'User' => array(
                'username' => 'user',
                'password' => 'password',
            )
        );
        $mock = $this->generate('Watches', array(
            'components' => array(
                'Session'
            )
        ));
        $mapCheck = array(
            array('NewUserData', true)
        );
        $mapRead = array(
            array('NewUserData', $newUserData)
        );
        
        $mock->Session->expects($this->any())
            ->method('check')
            ->will($this->returnValueMap($mapCheck));
        
        $mock->Session->expects($this->any())
            ->method('read')
            ->will($this->returnValueMap($mapRead));*/
    }    
    public function testIndex() {
        $result = $this->testAction('/watches/index');
        debug($result);
    }
}
