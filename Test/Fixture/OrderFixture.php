<?php
App::uses('FakerTestFixture', 'CakeFaker.Lib');
/**
 * OrderFixture
 *
 */
class OrderFixture extends FakerTestFixture {

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

    public function setUp() {
        parent::setUp();
    }

    public function init() {
        parent::init();
    }

    protected $model_name = 'Order', $num_records = 5;
    protected function alterFields($generator) {
        return array(
            'email' => function() use ($generator) { return $generator->email; },
            'phone' => function() use ($generator) { return $generator->phoneNumber; },
            'shippingAmount' => function() use ($generator) { return $generator->randomDigit; },
            'shipDate' => function() use ($generator) { return $generator->date(); },
            //'notes' => function() use ($generator) { return $generator->text; },
            //'orderNotes' => function() use ($generator) { return $generator->text; },
            //'created' => function() use ($generator) { return $generator->dateTime(); },
            //'modified' => function() use ($generator) { return $generator->dateTime(); },
        );
    }
}
