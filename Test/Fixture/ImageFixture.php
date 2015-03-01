<?php
/**
 * ImageFixture
 *
 */
class ImageFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => true, 'key' => 'primary'),
		'watch_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'primary' => array('type' => 'boolean', 'null' => true, 'default' => '0'),
		'filename' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 200, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'filenameLarge' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 200, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'filenameMedium' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 200, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'filenameThumb' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 200, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
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
			'watch_id' => '1',
			'primary' => 0,
			'filename' => '/files/1/longines5428.jpg',
			'filenameLarge' => '/files/1/longines5428-lg.jpg',
			'filenameMedium' => '/files/1/longines5428-md.jpg',
			'filenameThumb' => '/files/1/longines5428-thumb.jpg'
		),
		array(
			'id' => '2',
			'watch_id' => '2',
			'primary' => 0,
			'filename' => '/files/2/longines5428back.jpg',
			'filenameLarge' => '/files/2/longines5428back-lg.jpg',
			'filenameMedium' => '/files/2/longines5428back-md.jpg',
			'filenameThumb' => '/files/2/longines5428back-thumb.jpg'
		),
		array(
			'id' => '3',
			'watch_id' => '3',
			'primary' => 0,
			'filename' => '/files/3/longines5428insideback.jpg',
			'filenameLarge' => '/files/3/longines5428insideback-lg.jpg',
			'filenameMedium' => '/files/3/longines5428insideback-md.jpg',
			'filenameThumb' => '/files/3/longines5428insideback-thumb.jpg'
		),
		array(
			'id' => '4',
			'watch_id' => '4',
			'primary' => 0,
			'filename' => '/files/4/longines5428movt.jpg',
			'filenameLarge' => '/files/4/longines5428movt-lg.jpg',
			'filenameMedium' => '/files/4/longines5428movt-md.jpg',
			'filenameThumb' => '/files/4/longines5428movt-thumb.jpg'
		),
		array(
			'id' => '5',
			'watch_id' => '5',
			'primary' => 0,
			'filename' => '/files/5/longines5428profile.jpg',
			'filenameLarge' => '/files/5/longines5428profile-lg.jpg',
			'filenameMedium' => '/files/5/longines5428profile-md.jpg',
			'filenameThumb' => '/files/5/longines5428profile-thumb.jpg'
		),
		array(
			'id' => '6',
			'watch_id' => '6',
			'primary' => 0,
			'filename' => '/files/6/longines5428side.jpg',
			'filenameLarge' => '/files/6/longines5428side-lg.jpg',
			'filenameMedium' => '/files/6/longines5428side-md.jpg',
			'filenameThumb' => '/files/6/longines5428side-thumb.jpg'
		),
		array(
			'id' => '7',
			'watch_id' => '7',
			'primary' => 0,
			'filename' => '/files/7/longines5428side2.jpg',
			'filenameLarge' => '/files/7/longines5428side2-lg.jpg',
			'filenameMedium' => '/files/7/longines5428side2-md.jpg',
			'filenameThumb' => '/files/7/longines5428side2-thumb.jpg'
		),
		array(
			'id' => '8',
			'watch_id' => '8',
			'primary' => 0,
			'filename' => '/files/8/omega5427.jpg',
			'filenameLarge' => '/files/8/omega5427-lg.jpg',
			'filenameMedium' => '/files/8/omega5427-md.jpg',
			'filenameThumb' => '/files/8/omega5427-thumb.jpg'
		),
		array(
			'id' => '9',
			'watch_id' => '9',
			'primary' => 0,
			'filename' => '/files/9/omega5427back.jpg',
			'filenameLarge' => '/files/9/omega5427back-lg.jpg',
			'filenameMedium' => '/files/9/omega5427back-md.jpg',
			'filenameThumb' => '/files/9/omega5427back-thumb.jpg'
		),
		array(
			'id' => '10',
			'watch_id' => '10',
			'primary' => 0,
			'filename' => '/files/10/omega5427insideback.jpg',
			'filenameLarge' => '/files/10/omega5427insideback-lg.jpg',
			'filenameMedium' => '/files/10/omega5427insideback-md.jpg',
			'filenameThumb' => '/files/10/omega5427insideback-thumb.jpg'
		),
	);

}
