<?php
/**
 * ConsignmentFixture
 *
 */
class ConsignmentFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => true, 'key' => 'primary'),
		'watch_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => true),
		'owner_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'paid' => array('type' => 'date', 'null' => true, 'default' => null),
		'returned' => array('type' => 'date', 'null' => true, 'default' => null),
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
			'watch_id' => 1,
			'owner_id' => 1,
			'paid' => '2015-03-31',
			'returned' => '2015-03-31'
		),
	);

}
