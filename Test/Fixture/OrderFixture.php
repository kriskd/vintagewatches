<?php
/**
 * OrderFixture
 *
 */
class OrderFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'email' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 50, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'phone' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 20, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'shippingAmount' => array('type' => 'float', 'null' => false, 'default' => null, 'length' => '6,2'),
		'shipDate' => array('type' => 'date', 'null' => true, 'default' => null),
		'notes' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'orderNotes' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
        'coupon_id' => array('type' => 'integer', 'null' => true, 'default' => null),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
    );
    //public $import = array('model' => 'Order', 'connection' => 'development');

    public $records = array();

    public function setUp() {
        parent::setUp();
    }

    public function init() {
        $this->records = array(
                array(
                'id' => 1,
                'email' => 'PeterRHarris@teleworm.us',
                'phone' => '260-423-3273',
                'shippingAmount' => '8.00',
                'shipDate' => date('Y-m-d', strtotime('-3 day')),
                'notes' => 'Lorem ipsum',
                'orderNotes' => 'Loreum ipsum',
                'coupon_id' => 3,
                'created' => date('Y-m-d H:i:s', strtotime('-4 day')),
                'modified' => date('Y-m-d H:i:s', strtotime('-4 day')),
            ),
        ); 
        parent::init();
    }

}
