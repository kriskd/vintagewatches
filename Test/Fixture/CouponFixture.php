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
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => true, 'key' => 'primary'),
		'code' => array('type' => 'string', 'null' => false, 'length' => 50, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
        'type' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 20, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'amount' => array('type' => 'float', 'null' => false, 'default' => null, 'length' => '10,2', 'unsigned' => false),
		'total' => array('type' => 'integer', 'null' => false, 'default' => '1', 'unsigned' => false),
		'assigned_to' => array('type' => 'string', 'null' => true, 'length' => 50, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
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
	public $records = array();

    public function init() {
        $this->records = array(
            array(
                'id' => '1',
                'code' => 'percentagegood',
                'type' => 'percentage',
                'amount' => '0.25',
                'total' => '10',
                'assigned_to' => '',
                'minimum_order' => null,
                'expire_date' => null,
                'brand_id' => null,
                'archived' => 0,
                'created' => date('Y-m-d H:i:s', strtotime('-1 day')),
                'modified' => date('Y-m-d H:i:s', strtotime('-1 day')),
            ),
            array(
                'id' => '2',
                'code' => 'fixedgood',
                'type' => 'fixed',
                'amount' => '30',
                'total' => '10',
                'assigned_to' => '',
                'minimum_order' => null,
                'expire_date' => null,
                'brand_id' => null,
                'archived' => 0,
                'created' => date('Y-m-d H:i:s', strtotime('-1 day')),
                'modified' => date('Y-m-d H:i:s', strtotime('-1 day')),
            ),
            array(
                'id' => '3',
                'code' => 'notavailable',
                'type' => 'fixed',
                'amount' => '12.00',
                'total' => '1',
                'assigned_to' => '',
                'minimum_order' => null,
                'expire_date' => null,
                'brand_id' => null,
                'archived' => 0,
                'created' => date('Y-m-d H:i:s', strtotime('-7 day')),
                'modified' => date('Y-m-d H:i:s', strtotime('-7 day')),
            ),
            array(
                'id' => '4',
                'code' => 'assigned',
                'type' => 'percentage',
                'amount' => '0.50',
                'total' => '1',
                'assigned_to' => 'PhilipAShrum@jourrapide.com',
                'minimum_order' => null,
                'expire_date' => null,
                'brand_id' => null,
                'archived' => 0,
                'created' => date('Y-m-d H:i:s', strtotime('-1 day')),
                'modified' => date('Y-m-d H:i:s', strtotime('-1 day')),
            ),
            array(
                'id' => '5',
                'code' => 'minimum',
                'type' => 'fixed',
                'amount' => '20.00',
                'total' => '20',
                'assigned_to' => '',
                'minimum_order' => '1000',
                'expire_date' => null,
                'brand_id' => null,
                'archived' => 0,
                'created' => date('Y-m-d H:i:s', strtotime('-1 day')),
                'modified' => date('Y-m-d H:i:s', strtotime('-1 day')),
            ),
            array(
                'id' => '6',
                'code' => 'brand',
                'type' => 'percentage',
                'amount' => '0.15',
                'total' => '10',
                'assigned_to' => '',
                'minimum_order' => null,
                'expire_date' => null,
                'brand_id' => '1',
                'archived' => 0,
                'created' => date('Y-m-d H:i:s', strtotime('-1 day')),
                'modified' => date('Y-m-d H:i:s', strtotime('-1 day')),
            ),
            array(
                'id' => '7',
                'code' => 'expired',
                'type' => 'fixed',
                'amount' => '20.00',
                'total' => '5',
                'assigned_to' => '',
                'minimum_order' => '200.00',
                'expire_date' => date('Y-m-d H:i:s', strtotime('-1 day')),
                'brand_id' => null,
                'archived' => 0,
                'created' => date('Y-m-d H:i:s', strtotime('-2 day')),
                'modified' => date('Y-m-d H:i:s', strtotime('-2 day')),
            ),
            array(
                'id' => '8',
                'code' => 'archived',
                'type' => 'percentage',
                'amount' => '0.10',
                'total' => '10',
                'assigned_to' => '',
                'minimum_order' => null,
                'expire_date' => null,
                'brand_id' => null,
                'archived' => 1,
                'created' => date('Y-m-d H:i:s', strtotime('-1 day')),
                'modified' => date('Y-m-d H:i:s', strtotime('-1 day')),
            ),
            array(
                'id' => '9',
                'code' => 'brandfixed',
                'type' => 'fixed',
                'amount' => '1000',
                'total' => '10',
                'assigned_to' => '',
                'minimum_order' => null,
                'expire_date' => null,
                'brand_id' => '1',
                'archived' => 0,
                'created' => date('Y-m-d H:i:s', strtotime('-1 day')),
                'modified' => date('Y-m-d H:i:s', strtotime('-1 day')),
            ),
            array(
                'id' => '10',
                'code' => 'bigfixed',
                'type' => 'fixed',
                'amount' => '1000',
                'total' => '10',
                'assigned_to' => '',
                'minimum_order' => null,
                'expire_date' => null,
                'brand_id' => null,
                'archived' => 0,
                'created' => date('Y-m-d H:i:s', strtotime('-1 day')),
                'modified' => date('Y-m-d H:i:s', strtotime('-1 day')),
            ),
        );

        parent::init();
    }
}
