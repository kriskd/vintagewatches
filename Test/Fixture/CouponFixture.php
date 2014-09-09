<?php
/**
 * CouponFixture
 *
 */
class CouponFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'code' => array('type' => 'string', 'null' => false, 'length' => 50, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'amount' => array('type' => 'float', 'null' => false, 'default' => null, 'length' => '10,2'),
		'assigned_to' => array('type' => 'string', 'null' => true, 'length' => 50, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'minimum_order' => array('type' => 'float', 'null' => true, 'default' => null, 'length' => '10,2'),
		'expire_date' => array('type' => 'date', 'null' => true, 'default' => null),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => 1,
			'code' => 'Lorem ipsum dolor sit amet',
			'amount' => 1,
			'assigned_to' => 'Lorem ipsum dolor sit amet',
			'minimum_order' => 1,
			'expire_date' => '2014-07-19',
			'created' => '2014-07-19 13:11:20',
			'modified' => '2014-07-19 13:11:20'
		),
	);

}
