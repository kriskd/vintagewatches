<?php
/**
 * DetectFixture
 *
 */
class DetectFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => true, 'key' => 'primary'),
		'method' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 50, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
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
			'method' => 'isMobile'
		),
		array(
			'id' => '2',
			'method' => 'isTablet'
		),
		array(
			'id' => '3',
			'method' => 'isiPhone'
		),
		array(
			'id' => '4',
			'method' => 'isBlackBerry'
		),
		array(
			'id' => '5',
			'method' => 'isHTC'
		),
		array(
			'id' => '6',
			'method' => 'isNexus'
		),
		array(
			'id' => '7',
			'method' => 'isDell'
		),
		array(
			'id' => '8',
			'method' => 'isMotorola'
		),
		array(
			'id' => '9',
			'method' => 'isSamsung'
		),
		array(
			'id' => '10',
			'method' => 'isLG'
		),
		array(
			'id' => '11',
			'method' => 'isSony'
		),
		array(
			'id' => '12',
			'method' => 'isAsus'
		),
		array(
			'id' => '13',
			'method' => 'isMicromax'
		),
		array(
			'id' => '14',
			'method' => 'isPalm'
		),
		array(
			'id' => '15',
			'method' => 'isVertu'
		),
		array(
			'id' => '16',
			'method' => 'isPantech'
		),
		array(
			'id' => '17',
			'method' => 'isFly'
		),
		array(
			'id' => '18',
			'method' => 'isSimValley'
		),
		array(
			'id' => '19',
			'method' => 'isGenericPhone'
		),
		array(
			'id' => '20',
			'method' => 'isiPad'
		),
		array(
			'id' => '21',
			'method' => 'isNexusTablet'
		),
		array(
			'id' => '22',
			'method' => 'isSamsungTablet'
		),
		array(
			'id' => '23',
			'method' => 'isKindle'
		),
		array(
			'id' => '24',
			'method' => 'isSurfaceTablet'
		),
		array(
			'id' => '25',
			'method' => 'isHPTablet'
		),
		array(
			'id' => '26',
			'method' => 'isAsusTablet'
		),
		array(
			'id' => '27',
			'method' => 'isBlackBerryTablet'
		),
		array(
			'id' => '28',
			'method' => 'isHTCtablet'
		),
		array(
			'id' => '29',
			'method' => 'isMotorolaTablet'
		),
		array(
			'id' => '30',
			'method' => 'isNookTablet'
		),
		array(
			'id' => '31',
			'method' => 'isAcerTablet'
		),
		array(
			'id' => '32',
			'method' => 'isToshibaTablet'
		),
		array(
			'id' => '33',
			'method' => 'isLGTablet'
		),
		array(
			'id' => '34',
			'method' => 'isFujitsuTablet'
		),
		array(
			'id' => '35',
			'method' => 'isPrestigioTablet'
		),
		array(
			'id' => '36',
			'method' => 'isLenovoTablet'
		),
		array(
			'id' => '37',
			'method' => 'isYarvikTablet'
		),
		array(
			'id' => '38',
			'method' => 'isMedionTablet'
		),
		array(
			'id' => '39',
			'method' => 'isArnovaTablet'
		),
		array(
			'id' => '40',
			'method' => 'isIRUTablet'
		),
		array(
			'id' => '41',
			'method' => 'isMegafonTablet'
		),
		array(
			'id' => '42',
			'method' => 'isAllViewTablet'
		),
		array(
			'id' => '43',
			'method' => 'isArchosTablet'
		),
		array(
			'id' => '44',
			'method' => 'isAinolTablet'
		),
		array(
			'id' => '45',
			'method' => 'isSonyTablet'
		),
		array(
			'id' => '46',
			'method' => 'isCubeTablet'
		),
		array(
			'id' => '47',
			'method' => 'isCobyTablet'
		),
		array(
			'id' => '48',
			'method' => 'isMIDTablet'
		),
		array(
			'id' => '49',
			'method' => 'isSMiTTablet'
		),
		array(
			'id' => '50',
			'method' => 'isRockChipTablet'
		),
		array(
			'id' => '51',
			'method' => 'isFlyTablet'
		),
		array(
			'id' => '52',
			'method' => 'isbqTablet'
		),
		array(
			'id' => '53',
			'method' => 'isHuaweiTablet'
		),
		array(
			'id' => '54',
			'method' => 'isNecTablet'
		),
		array(
			'id' => '55',
			'method' => 'isPantechTablet'
		),
		array(
			'id' => '56',
			'method' => 'isBronchoTablet'
		),
		array(
			'id' => '57',
			'method' => 'isVersusTablet'
		),
		array(
			'id' => '58',
			'method' => 'isZyncTablet'
		),
		array(
			'id' => '59',
			'method' => 'isPositivoTablet'
		),
		array(
			'id' => '60',
			'method' => 'isNabiTablet'
		),
		array(
			'id' => '61',
			'method' => 'isKoboTablet'
		),
		array(
			'id' => '62',
			'method' => 'isDanewTablet'
		),
		array(
			'id' => '63',
			'method' => 'isTexetTablet'
		),
		array(
			'id' => '64',
			'method' => 'isPlaystationTablet'
		),
		array(
			'id' => '65',
			'method' => 'isGalapadTablet'
		),
		array(
			'id' => '66',
			'method' => 'isMicromaxTablet'
		),
		array(
			'id' => '67',
			'method' => 'isKarbonnTablet'
		),
		array(
			'id' => '68',
			'method' => 'isAllFineTablet'
		),
		array(
			'id' => '69',
			'method' => 'isPROSCANTablet'
		),
		array(
			'id' => '70',
			'method' => 'isYONESTablet'
		),
		array(
			'id' => '71',
			'method' => 'isChangJiaTablet'
		),
		array(
			'id' => '72',
			'method' => 'isGUTablet'
		),
		array(
			'id' => '73',
			'method' => 'isTelstraTablet'
		),
		array(
			'id' => '74',
			'method' => 'isGenericTablet'
		),
		array(
			'id' => '75',
			'method' => 'isAndroidOS'
		),
		array(
			'id' => '76',
			'method' => 'isBlackBerryOS'
		),
		array(
			'id' => '77',
			'method' => 'isPalmOS'
		),
		array(
			'id' => '78',
			'method' => 'isSymbianOS'
		),
		array(
			'id' => '79',
			'method' => 'isWindowsMobileOS'
		),
		array(
			'id' => '80',
			'method' => 'isWindowsPhoneOS'
		),
		array(
			'id' => '81',
			'method' => 'isiOS'
		),
		array(
			'id' => '82',
			'method' => 'isMeeGoOS'
		),
		array(
			'id' => '83',
			'method' => 'isMaemoOS'
		),
		array(
			'id' => '84',
			'method' => 'isJavaOS'
		),
		array(
			'id' => '85',
			'method' => 'iswebOS'
		),
		array(
			'id' => '86',
			'method' => 'isbadaOS'
		),
		array(
			'id' => '87',
			'method' => 'isBREWOS'
		),
		array(
			'id' => '88',
			'method' => 'isChrome'
		),
		array(
			'id' => '89',
			'method' => 'isDolfin'
		),
		array(
			'id' => '90',
			'method' => 'isOpera'
		),
		array(
			'id' => '91',
			'method' => 'isSkyfire'
		),
		array(
			'id' => '92',
			'method' => 'isIE'
		),
		array(
			'id' => '93',
			'method' => 'isFirefox'
		),
		array(
			'id' => '94',
			'method' => 'isBolt'
		),
		array(
			'id' => '95',
			'method' => 'isTeaShark'
		),
		array(
			'id' => '96',
			'method' => 'isBlazer'
		),
		array(
			'id' => '97',
			'method' => 'isSafari'
		),
		array(
			'id' => '98',
			'method' => 'isTizen'
		),
		array(
			'id' => '99',
			'method' => 'isUCBrowser'
		),
		array(
			'id' => '100',
			'method' => 'isDiigoBrowser'
		),
		array(
			'id' => '101',
			'method' => 'isPuffin'
		),
		array(
			'id' => '102',
			'method' => 'isMercury'
		),
		array(
			'id' => '103',
			'method' => 'isGenericBrowser'
		),
	);

}
