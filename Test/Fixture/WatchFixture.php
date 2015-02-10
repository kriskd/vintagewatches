<?php
/**
 * WatchFixture
 *
 */
class WatchFixture extends CakeTestFixture {

/**
 * Import
 *
 * @var array
 */
	public $import = array('records' => true);

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => true, 'key' => 'primary'),
		'order_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'brand_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'stockId' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'price' => array('type' => 'decimal', 'null' => true, 'default' => null, 'length' => '6,2', 'unsigned' => false),
		'name' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 100, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'description' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'active' => array('type' => 'boolean', 'null' => false, 'default' => null),
		'tweeted_at' => array('type' => 'datetime', 'null' => true, 'default' => '0000-00-00 00:00:00'),
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
    public $records = array();

    public $chars = 'abcdefghijklmnopqrstuvwxyz';

    public function init() {
        $this->records = array(
            array(
                'id' => '1',
                'order_id' => '1',
                'brand_id' => '16',
                'stockId' => rand(1000, 9999),
                'price' => '695.00',
                'name' => ucfirst(substr(str_shuffle($this->chars), 0, 8)).' '.ucfirst(substr(str_shuffle($this->chars), 0, 6)), 
                'description' => 'Loreum ipsum',
                'active' => 0,
                'tweeted_at' => '0000-00-00 00:00:00',
                'created' => date('Y-m-d H:i:s', strtotime('-10 day')),
                'modified' => date('Y-m-d H:i:s', strtotime('-10 day')),
            ),
            array(
                'id' => '2',
                'order_id' => '6',
                'brand_id' => '6',
                'stockId' => rand(1000, 9999),
                'price' => '225.00',
                'name' => ucfirst(substr(str_shuffle($this->chars), 0, 8)).' '.ucfirst(substr(str_shuffle($this->chars), 0, 6)), 
                'description' => 'Loreum ipsum',
                'active' => 0,
                'tweeted_at' => '0000-00-00 00:00:00',
                'created' => date('Y-m-d H:i:s', strtotime('-10 day')),
                'modified' => date('Y-m-d H:i:s', strtotime('-10 day')),
            ),
            array(
                'id' => '3',
                'order_id' => null,
                'brand_id' => '13',
                'stockId' => rand(1000, 9999),
                'price' => '175.00',
                'name' => ucfirst(substr(str_shuffle($this->chars), 0, 8)).' '.ucfirst(substr(str_shuffle($this->chars), 0, 6)), 
                'description' => 'Lorem ipsum',
                'active' => 1,
                'tweeted_at' => '0000-00-00 00:00:00',
                'created' => date('Y-m-d H:i:s', strtotime('-10 day')),
                'modified' => date('Y-m-d H:i:s', strtotime('-10 day')),
            ),
            array(
                'id' => '4',
                'order_id' => '5',
                'brand_id' => '21',
                'stockId' => rand(1000, 9999),
                'price' => '395.00',
                'name' => ucfirst(substr(str_shuffle($this->chars), 0, 8)).' '.ucfirst(substr(str_shuffle($this->chars), 0, 6)), 
                'description' => 'Lorem ipsum',
                'active' => 0,
                'tweeted_at' => '0000-00-00 00:00:00',
                'created' => date('Y-m-d H:i:s', strtotime('-10 day')),
                'modified' => date('Y-m-d H:i:s', strtotime('-10 day')),
            ),
            array(
                'id' => '5',
                'order_id' => null,
                'brand_id' => '3',
                'stockId' => rand(1000, 9999),
                'price' => '795.00',
                'name' => ucfirst(substr(str_shuffle($this->chars), 0, 8)).' '.ucfirst(substr(str_shuffle($this->chars), 0, 6)), 
                'description' => 'Lorem ipsum',
                'active' => 1,
                'tweeted_at' => '0000-00-00 00:00:00',
                'created' => date('Y-m-d H:i:s', strtotime('-10 day')),
                'modified' => date('Y-m-d H:i:s', strtotime('-10 day')),
            ),
            array(
                'id' => '6',
                'order_id' => null,
                'brand_id' => '1',
                'stockId' => rand(1000, 9999),
                'price' => '750.00',
                'name' => ucfirst(substr(str_shuffle($this->chars), 0, 8)).' '.ucfirst(substr(str_shuffle($this->chars), 0, 6)), 
                'active' => 1,
                'tweeted_at' => '0000-00-00 00:00:00',
                'created' => date('Y-m-d H:i:s', strtotime('-10 day')),
                'modified' => date('Y-m-d H:i:s', strtotime('-10 day')),
            ),
            array(
                'id' => '7',
                'order_id' => null,
                'brand_id' => '5',
                'stockId' => rand(1000, 9999),
                'price' => '295.00',
                'name' => ucfirst(substr(str_shuffle($this->chars), 0, 8)).' '.ucfirst(substr(str_shuffle($this->chars), 0, 6)), 
                'description' => 'Loreum ipsum',
                'active' => 1,
                'tweeted_at' => '0000-00-00 00:00:00',
                'created' => date('Y-m-d H:i:s', strtotime('-10 day')),
                'modified' => date('Y-m-d H:i:s', strtotime('-10 day')),
            ),
            array(
                'id' => '8',
                'order_id' => null,
                'brand_id' => '2',
                'stockId' => rand(1000, 9999),
                'price' => '395.00',
                'name' => ucfirst(substr(str_shuffle($this->chars), 0, 8)).' '.ucfirst(substr(str_shuffle($this->chars), 0, 6)), 
                'active' => 1,
                'tweeted_at' => '0000-00-00 00:00:00',
                'created' => date('Y-m-d H:i:s', strtotime('-10 day')),
                'modified' => date('Y-m-d H:i:s', strtotime('-10 day')),
            ),
            array(
                'id' => '9',
                'order_id' => null,
                'brand_id' => '3',
                'stockId' => rand(1000, 9999),
                'price' => '250.00',
                'name' => ucfirst(substr(str_shuffle($this->chars), 0, 8)).' '.ucfirst(substr(str_shuffle($this->chars), 0, 6)), 
                'description' => 'Lorem ipsum',
                'active' => 1,
                'tweeted_at' => '0000-00-00 00:00:00',
                'created' => date('Y-m-d H:i:s', strtotime('-10 day')),
                'modified' => date('Y-m-d H:i:s', strtotime('-10 day')),
            ),
            array(
                'id' => '10',
                'order_id' => null,
                'brand_id' => '5',
                'stockId' => rand(1000, 9999),
                'price' => '225.00',
                'name' => ucfirst(substr(str_shuffle($this->chars), 0, 8)).' '.ucfirst(substr(str_shuffle($this->chars), 0, 6)), 
                'description' => 'Loreum ipsum',
                'active' => 1,
                'tweeted_at' => '0000-00-00 00:00:00',
                'created' => date('Y-m-d H:i:s', strtotime('-10 day')),
                'modified' => date('Y-m-d H:i:s', strtotime('-10 day')),
            ),
        );
        parent::init();
    }
}
