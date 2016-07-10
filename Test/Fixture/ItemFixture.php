<?php
/**
 * Item Fixture
 */
class ItemFixture extends CakeTestFixture {

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
		'active' => array('type' => 'boolean', 'null' => false, 'default' => null),
		'description' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'quantity' => array('type' => 'integer', 'null' => false, 'default' => '1', 'length' => 10, 'unsigned' => true),
		'price' => array('type' => 'float', 'null' => false, 'default' => null, 'length' => '6,2', 'unsigned' => false),
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
			'id' => '1',
			'active' => 1,
			'description' => 'Book',
			'quantity' => '99',
			'price' => '49.95',
			'created' => '2016-07-04 18:13:04',
			'modified' => '2016-07-04 19:04:48'
		),
		array(
			'id' => '2',
			'active' => 1,
			'description' => 'Knives',
			'quantity' => '99',
			'price' => '29.99',
			'created' => '2016-07-04 23:57:25',
			'modified' => '2016-07-05 00:01:23'
		),
	);

}
