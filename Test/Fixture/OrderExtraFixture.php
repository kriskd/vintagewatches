<?php
/**
 * OrderExtra Fixture
 */
class OrderExtraFixture extends CakeTestFixture {

/**
 * Table name
 *
 * @var string
 */
	public $table = 'order_extra';

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => true, 'key' => 'primary'),
		'order_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => true),
		'item_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => true),
		'quantity' => array('type' => 'integer', 'null' => false, 'default' => '1', 'length' => 10, 'unsigned' => true),
		'price' => array('type' => 'float', 'null' => true, 'default' => null, 'length' => '6,2', 'unsigned' => false),
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
			'order_id' => 1,
			'item_id' => 1,
			'quantity' => 1,
			'price' => 1
		),
	);

}
