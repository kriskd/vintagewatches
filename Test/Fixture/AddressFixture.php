<?php
App::uses('MyTestFixture', 'Test/Fixture');
/**
 * AddressFixture
 *
 */
class AddressFixture extends MyTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => true, 'key' => 'primary'),
		'class' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 30, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'foreign_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'type' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 50, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'firstName' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 50, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'lastName' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 50, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'company' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 200, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'address1' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'address2' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'city' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 50, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'state' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 2, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'postalCode' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 20, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'country' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 2, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

    public $records = array(
        array(
            'id' => 1,
            'class' => 'Order',
            'foreign_id' => 1,
            'type' =>'billing',
            'firstName' => 'Peter',
            'lastName' => 'Harris',
            'company' => '',
            'address1' => '2042 Matthews Street',
            'address2' => '',
            'city' => 'Peoria',
            'state' => 'IL',
            'postalCode' => '61602',
            'country' => 'US',
        ),
        array(
            'id' => 2,
            'class' => 'Order',
            'foreign_id' => 2,
            'type' =>'billing',
            'firstName' => 'Peter',
            'lastName' => 'Harris',
            'company' => '',
            'address1' => '2042 Matthews Street',
            'address2' => '',
            'city' => 'Peoria',
            'state' => 'IL',
            'postalCode' => '61602',
            'country' => 'US',
        ),
        array(
            'id' => 3,
            'class' => 'Order',
            'foreign_id' => 6,
            'type' =>'billing',
            'firstName' => 'Kristi',
            'lastName' => 'Dooley',
            'company' => '',
            'address1' => '2663 Bassell Avenue',
            'address2' => '',
            'city' => 'Little Rock',
            'state' => 'AR',
            'postalCode' => '72211',
            'country' => 'US',
        ),
    );

}
