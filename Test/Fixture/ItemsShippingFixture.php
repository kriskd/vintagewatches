<?php
/**
 * ItemsShipping Fixture
 */
class ItemsShippingFixture extends CakeTestFixture {

/**
 * Import
 *
 * @var array
 */
	public $import = array('connection' => 'development');

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => true, 'key' => 'primary'),
		'item_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => true),
		'shipping_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => true),
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
			'id' => '1',
			'item_id' => '1',
			'shipping_id' => '1'
		),
		array(
			'id' => '2',
			'item_id' => '1',
			'shipping_id' => '2'
		),
		array(
			'id' => '3',
			'item_id' => '1',
			'shipping_id' => '3'
		),
		array(
			'id' => '4',
			'item_id' => '2',
			'shipping_id' => '2'
		),
	);

}
