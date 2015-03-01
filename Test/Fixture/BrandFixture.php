<?php
/**
 * BrandFixture
 *
 */
class BrandFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => true, 'key' => 'primary'),
		'name' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
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
			'name' => 'Favre Leuba'
		),
		array(
			'id' => '2',
			'name' => 'Leonidas'
		),
		array(
			'id' => '3',
			'name' => 'Omega'
		),
		array(
			'id' => '4',
			'name' => 'Rolex'
		),
		array(
			'id' => '5',
			'name' => 'Longines'
		),
		array(
			'id' => '6',
			'name' => 'Elgin'
		),
		array(
			'id' => '7',
			'name' => 'Wittnauer'
		),
		array(
			'id' => '8',
			'name' => 'Gruen'
		),
		array(
			'id' => '9',
			'name' => 'Lord Elgin'
		),
		array(
			'id' => '10',
			'name' => 'Bulova'
		),
		array(
			'id' => '12',
			'name' => 'Illinois'
		),
		array(
			'id' => '13',
			'name' => 'Croton'
		),
		array(
			'id' => '15',
			'name' => 'Tudor'
		),
		array(
			'id' => '16',
			'name' => 'Girard Perregaux'
		),
		array(
			'id' => '17',
			'name' => 'Juvenia'
		),
		array(
			'id' => '18',
			'name' => 'Breitling'
		),
		array(
			'id' => '19',
			'name' => 'Invicta'
		),
		array(
			'id' => '20',
			'name' => 'Rima'
		),
		array(
			'id' => '21',
			'name' => 'Bulova Accutron'
		),
		array(
			'id' => '22',
			'name' => 'Enicar'
		),
		array(
			'id' => '23',
			'name' => 'Geneve'
		),
		array(
			'id' => '24',
			'name' => 'Benrus'
		),
		array(
			'id' => '25',
			'name' => 'Tissot'
		),
		array(
			'id' => '26',
			'name' => 'Wakmann'
		),
		array(
			'id' => '27',
			'name' => 'Leatherman/Gerber'
		),
		array(
			'id' => '28',
			'name' => 'Cuervo y Sobrinos'
		),
		array(
			'id' => '29',
			'name' => 'Hamilton'
		),
		array(
			'id' => '30',
			'name' => 'Ralco'
		),
		array(
			'id' => '31',
			'name' => 'Clinton'
		),
		array(
			'id' => '32',
			'name' => 'Heuer'
		),
		array(
			'id' => '33',
			'name' => 'Movado'
		),
		array(
			'id' => '34',
			'name' => 'American pocket watch'
		),
		array(
			'id' => '35',
			'name' => 'Seiko'
		),
		array(
			'id' => '36',
			'name' => 'Cyma'
		),
		array(
			'id' => '37',
			'name' => 'Waltham'
		),
		array(
			'id' => '38',
			'name' => 'Hampden'
		),
		array(
			'id' => '39',
			'name' => 'Zodiac'
		),
		array(
			'id' => '40',
			'name' => 'Universal Geneve'
		),
		array(
			'id' => '41',
			'name' => 'Ernest Borel'
		),
		array(
			'id' => '42',
			'name' => 'Gersi'
		),
		array(
			'id' => '43',
			'name' => 'Minerva'
		),
		array(
			'id' => '44',
			'name' => 'Zenith'
		),
		array(
			'id' => '45',
			'name' => 'Doxa'
		),
		array(
			'id' => '46',
			'name' => 'LeCoultre'
		),
		array(
			'id' => '47',
			'name' => 'Certina'
		),
		array(
			'id' => '48',
			'name' => 'Eterna'
		),
		array(
			'id' => '49',
			'name' => 'Aureole'
		),
		array(
			'id' => '50',
			'name' => 'Paul Breguette'
		),
		array(
			'id' => '51',
			'name' => 'Eska'
		),
		array(
			'id' => '52',
			'name' => 'Jules Jurgenson'
		),
		array(
			'id' => '53',
			'name' => 'Nivada Grenchen'
		),
		array(
			'id' => '55',
			'name' => 'Baylor'
		),
		array(
			'id' => '56',
			'name' => 'Rotary'
		),
		array(
			'id' => '57',
			'name' => 'Rado'
		),
		array(
			'id' => '58',
			'name' => 'Tavannes'
		),
		array(
			'id' => '59',
			'name' => 'Swiss'
		),
		array(
			'id' => '60',
			'name' => 'Baume & Mercier'
		),
		array(
			'id' => '61',
			'name' => 'Pierce'
		),
	);

}
