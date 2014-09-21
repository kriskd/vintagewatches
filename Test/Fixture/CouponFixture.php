<?php
App::uses('MyTestFixture', 'Test/Fixture');
/**
 * CouponFixture
 *
 */
class CouponFixture extends MyTestFixture {

/**
 * Import
 *
 * @var array
 */
	public $import = array('connection' => 'development', 'records' => true, 'model' => 'Coupon');

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => true, 'key' => 'primary'),
		'code' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 50, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'type' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 20, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'amount' => array('type' => 'float', 'null' => false, 'default' => null, 'length' => '10,2', 'unsigned' => false),
		'total' => array('type' => 'integer', 'null' => false, 'default' => '1', 'unsigned' => false),
		'assigned_to' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 50, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'minimum_order' => array('type' => 'float', 'null' => true, 'default' => null, 'length' => '10,2', 'unsigned' => false),
		'expire_date' => array('type' => 'date', 'null' => true, 'default' => '0000-00-00'),
		'brand_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => true),
		'archived' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
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
	//public $records = array(
	//);

}
