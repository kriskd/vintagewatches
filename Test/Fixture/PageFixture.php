<?php
/**
 * PageFixture
 *
 */
class PageFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => true, 'key' => 'primary'),
		'name' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 100, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'slug' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 50, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'homepage' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'active' => array('type' => 'boolean', 'null' => false, 'default' => null),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => '1',
			'name' => 'Welcome',
			'slug' => 'welcome',
			'homepage' => 1,
			'active' => 1,
			'created' => '2013-09-15 00:00:00',
			'modified' => '2014-10-16 17:46:37'
		),
		array(
			'id' => '2',
			'name' => 'Essential Information',
			'slug' => 'essential-information',
			'homepage' => 0,
			'active' => 1,
			'created' => '2013-09-16 00:00:00',
			'modified' => '2015-01-28 20:12:21'
		),
		array(
			'id' => '3',
			'name' => 'Sell or trade your watches',
			'slug' => 'sell-trade',
			'homepage' => 0,
			'active' => 1,
			'created' => '2013-09-23 18:09:28',
			'modified' => '2013-11-08 07:45:06'
		),
		array(
			'id' => '4',
			'name' => 'About Bruce\'s Vintage Watches',
			'slug' => 'about-bruce',
			'homepage' => 0,
			'active' => 1,
			'created' => '2013-11-08 08:45:50',
			'modified' => '2013-11-08 08:52:50'
		),
	);

}
