<?php
/**
 * AcquisitionFixture
 *
 */
class AcquisitionFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => true, 'key' => 'primary'),
		'acquisition' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 50, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
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
			'acquisition' => 'purchase'
		),
		array(
			'id' => 2,
			'acquisition' => 'consignment'
		),
		array(
			'id' => 3,
			'acquisition' => 'self'
		),
		array(
			'id' => 4,
			'acquisition' => 'unknown'
		),
	);

}
