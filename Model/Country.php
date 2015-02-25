<?php
App::uses('AppModel', 'Model');
App::uses('ArraySource', 'Datasources.Model/Datasource');
App::uses('ConnectionManager', 'Model');

// Add new db config
ConnectionManager::create('country', array('datasource' => 'Datasources.ArraySource'));

/**
 * Regex Search & Replace to format data
   \'(\w\w)\' \=\> \'([A-Za-z()-., ]+)\'\,?
   array('abbreviation' => '\1', 'name' => '\2'),
*/

/**
 * Country Model
 */
class Country extends AppModel {
    
    /**
    * Database Configuration
    *
    * @var string
    */
    public $useDbConfig = 'country';
    
    public $useTable = false;
    
    public $fields = array(
            'id' => array('type' => 'integer', 'key' => 'primary'),
            'abbreviation' => array('type' => 'string', 'null' => false),
            'name' => array('type' => 'string', 'null' => false)
    );
    
    public $records = array(
	    array('abbreviation' => 'AF', 'name' => 'Afghanistan'),
	    array('abbreviation' => 'AX', 'name' => '&Aring;land Islands'),
	    array('abbreviation' => 'AL', 'name' => 'Albania'),
	    array('abbreviation' => 'DZ', 'name' => 'Algeria'),
	    array('abbreviation' => 'AS', 'name' => 'American Samoa'),
	    array('abbreviation' => 'AD', 'name' => 'Andorra'),
	    array('abbreviation' => 'AO', 'name' => 'Angola'),
	    array('abbreviation' => 'AI', 'name' => 'Anguilla'),
	    array('abbreviation' => 'AQ', 'name' => 'Antarctica'),
	    array('abbreviation' => 'AG', 'name' => 'Antigua and Barbuda'),
	    array('abbreviation' => 'AR', 'name' => 'Argentina'),
	    array('abbreviation' => 'AM', 'name' => 'Armenia'),
	    array('abbreviation' => 'AW', 'name' => 'Aruba'),
	    array('abbreviation' => 'AU', 'name' => 'Australia'),
	    array('abbreviation' => 'AT', 'name' => 'Austria'),
	    array('abbreviation' => 'AZ', 'name' => 'Azerbaijan'),
	    array('abbreviation' => 'BS', 'name' => 'Bahamas'),
	    array('abbreviation' => 'BH', 'name' => 'Bahrain'),
	    array('abbreviation' => 'BD', 'name' => 'Bangladesh'),
	    array('abbreviation' => 'BB', 'name' => 'Barbados'),
	    array('abbreviation' => 'BY', 'name' => 'Belarus'),
	    array('abbreviation' => 'BE', 'name' => 'Belgium'),
	    array('abbreviation' => 'BZ', 'name' => 'Belize'),
	    array('abbreviation' => 'BJ', 'name' => 'Benin'),
	    array('abbreviation' => 'BM', 'name' => 'Bermuda'),
	    array('abbreviation' => 'BT', 'name' => 'Bhutan'),
	    array('abbreviation' => 'BO', 'name' => 'Bolivia, Plurinational State of'),
	    array('abbreviation' => 'BQ', 'name' => 'Bonaire, Sint Eustatius and Saba'),
	    array('abbreviation' => 'BA', 'name' => 'Bosnia and Herzegovina'),
	    array('abbreviation' => 'BW', 'name' => 'Botswana'),
	    array('abbreviation' => 'BV', 'name' => 'Bouvet Island'),
	    array('abbreviation' => 'BR', 'name' => 'Brazil'),
	    array('abbreviation' => 'IO', 'name' => 'British Indian Ocean Territory'),
	    array('abbreviation' => 'BN', 'name' => 'Brunei Darussalam'),
	    array('abbreviation' => 'BG', 'name' => 'Bulgaria'),
	    array('abbreviation' => 'BF', 'name' => 'Burkina Faso'),
	    array('abbreviation' => 'BI', 'name' => 'Burundi'),
	    array('abbreviation' => 'KH', 'name' => 'Cambodia'),
	    array('abbreviation' => 'CM', 'name' => 'Cameroon'),
	    array('abbreviation' => 'CA', 'name' => 'Canada'),
	    array('abbreviation' => 'CV', 'name' => 'Cape Verde'),
	    array('abbreviation' => 'KY', 'name' => 'Cayman Islands'),
	    array('abbreviation' => 'CF', 'name' => 'Central African Republic'),
	    array('abbreviation' => 'TD', 'name' => 'Chad'),
	    array('abbreviation' => 'CL', 'name' => 'Chile'),
	    array('abbreviation' => 'CN', 'name' => 'China'),
	    array('abbreviation' => 'CX', 'name' => 'Christmas Island'),
	    array('abbreviation' => 'CC', 'name' => 'Cocos (Keeling) Islands'),
	    array('abbreviation' => 'CO', 'name' => 'Colombia'),
	    array('abbreviation' => 'KM', 'name' => 'Comoros'),
	    array('abbreviation' => 'CG', 'name' => 'Congo'),
	    array('abbreviation' => 'CD', 'name' => 'Congo, the Democratic Republic of the'),
	    array('abbreviation' => 'CK', 'name' => 'Cook Islands'),
	    array('abbreviation' => 'CR', 'name' => 'Costa Rica'),
	    array('abbreviation' => 'CI', 'name' => 'C&ocirc;te d\'Ivoire'),
	    array('abbreviation' => 'HR', 'name' => 'Croatia'),
	    array('abbreviation' => 'CU', 'name' => 'Cuba'),
	    array('abbreviation' => 'CW', 'name' => 'Cura&ccedil;ao'),
	    array('abbreviation' => 'CY', 'name' => 'Cyprus'),
	    array('abbreviation' => 'CZ', 'name' => 'Czech Republic'),
	    array('abbreviation' => 'DK', 'name' => 'Denmark'),
	    array('abbreviation' => 'DJ', 'name' => 'Djibouti'),
	    array('abbreviation' => 'DM', 'name' => 'Dominica'),
	    array('abbreviation' => 'DO', 'name' => 'Dominican Republic'),
	    array('abbreviation' => 'EC', 'name' => 'Ecuador'),
	    array('abbreviation' => 'EG', 'name' => 'Egypt'),
	    array('abbreviation' => 'SV', 'name' => 'El Salvador'),
	    array('abbreviation' => 'GQ', 'name' => 'Equatorial Guinea'),
	    array('abbreviation' => 'ER', 'name' => 'Eritrea'),
	    array('abbreviation' => 'EE', 'name' => 'Estonia'),
	    array('abbreviation' => 'ET', 'name' => 'Ethiopia'),
	    array('abbreviation' => 'FK', 'name' => 'Falkland Islands (Malvinas)'),
	    array('abbreviation' => 'FO', 'name' => 'Faroe Islands'),
	    array('abbreviation' => 'FJ', 'name' => 'Fiji'),
	    array('abbreviation' => 'FI', 'name' => 'Finland'),
	    array('abbreviation' => 'FR', 'name' => 'France'),
	    array('abbreviation' => 'GF', 'name' => 'French Guiana'),
	    array('abbreviation' => 'PF', 'name' => 'French Polynesia'),
	    array('abbreviation' => 'TF', 'name' => 'French Southern Territories'),
	    array('abbreviation' => 'GA', 'name' => 'Gabon'),
	    array('abbreviation' => 'GM', 'name' => 'Gambia'),
	    array('abbreviation' => 'GE', 'name' => 'Georgia'),
	    array('abbreviation' => 'DE', 'name' => 'Germany'),
	    array('abbreviation' => 'GH', 'name' => 'Ghana'),
	    array('abbreviation' => 'GI', 'name' => 'Gibraltar'),
	    array('abbreviation' => 'GR', 'name' => 'Greece'),
	    array('abbreviation' => 'GL', 'name' => 'Greenland'),
	    array('abbreviation' => 'GD', 'name' => 'Grenada'),
	    array('abbreviation' => 'GP', 'name' => 'Guadeloupe'),
	    array('abbreviation' => 'GU', 'name' => 'Guam'),
	    array('abbreviation' => 'GT', 'name' => 'Guatemala'),
	    array('abbreviation' => 'GG', 'name' => 'Guernsey'),
	    array('abbreviation' => 'GN', 'name' => 'Guinea'),
	    array('abbreviation' => 'GW', 'name' => 'Guinea-Bissau'),
	    array('abbreviation' => 'GY', 'name' => 'Guyana'),
	    array('abbreviation' => 'HT', 'name' => 'Haiti'),
	    array('abbreviation' => 'HM', 'name' => 'Heard Island and McDonald Islands'),
	    array('abbreviation' => 'VA', 'name' => 'Holy See (Vatican City State)'),
	    array('abbreviation' => 'HN', 'name' => 'Honduras'),
	    array('abbreviation' => 'HK', 'name' => 'Hong Kong'),
	    array('abbreviation' => 'HU', 'name' => 'Hungary'),
	    array('abbreviation' => 'IS', 'name' => 'Iceland'),
	    array('abbreviation' => 'IN', 'name' => 'India'),
	    array('abbreviation' => 'ID', 'name' => 'Indonesia'),
	    array('abbreviation' => 'IR', 'name' => 'Iran, Islamic Republic of'),
	    array('abbreviation' => 'IQ', 'name' => 'Iraq'),
	    array('abbreviation' => 'IE', 'name' => 'Ireland'),
	    array('abbreviation' => 'IM', 'name' => 'Isle of Man'),
	    array('abbreviation' => 'IL', 'name' => 'Israel'),
	    array('abbreviation' => 'IT', 'name' => 'Italy'),
	    array('abbreviation' => 'JM', 'name' => 'Jamaica'),
	    array('abbreviation' => 'JP', 'name' => 'Japan'),
	    array('abbreviation' => 'JE', 'name' => 'Jersey'),
	    array('abbreviation' => 'JO', 'name' => 'Jordan'),
	    array('abbreviation' => 'KZ', 'name' => 'Kazakhstan'),
	    array('abbreviation' => 'KE', 'name' => 'Kenya'),
	    array('abbreviation' => 'KI', 'name' => 'Kiribati'),
	    array('abbreviation' => 'KP', 'name' => 'Korea, Democratic People\'s Republic of'),
	    array('abbreviation' => 'KR', 'name' => 'Korea, Republic of'),
	    array('abbreviation' => 'KW', 'name' => 'Kuwait'),
	    array('abbreviation' => 'KG', 'name' => 'Kyrgyzstan'),
	    array('abbreviation' => 'LA', 'name' => 'Lao People\'s Democratic Republic'),
	    array('abbreviation' => 'LV', 'name' => 'Latvia'),
	    array('abbreviation' => 'LB', 'name' => 'Lebanon'),
	    array('abbreviation' => 'LS', 'name' => 'Lesotho'),
	    array('abbreviation' => 'LR', 'name' => 'Liberia'),
	    array('abbreviation' => 'LY', 'name' => 'Libya'),
	    array('abbreviation' => 'LI', 'name' => 'Liechtenstein'),
	    array('abbreviation' => 'LT', 'name' => 'Lithuania'),
	    array('abbreviation' => 'LU', 'name' => 'Luxembourg'),
	    array('abbreviation' => 'MO', 'name' => 'Macao'),
	    array('abbreviation' => 'MK', 'name' => 'Macedonia, The Former Yugoslav Republic of'),
	    array('abbreviation' => 'MG', 'name' => 'Madagascar'),
	    array('abbreviation' => 'MW', 'name' => 'Malawi'),
	    array('abbreviation' => 'MY', 'name' => 'Malaysia'),
	    array('abbreviation' => 'MV', 'name' => 'Maldives'),
	    array('abbreviation' => 'ML', 'name' => 'Mali'),
	    array('abbreviation' => 'MT', 'name' => 'Malta'),
	    array('abbreviation' => 'MH', 'name' => 'Marshall Islands'),
	    array('abbreviation' => 'MQ', 'name' => 'Martinique'),
	    array('abbreviation' => 'MR', 'name' => 'Mauritania'),
	    array('abbreviation' => 'MU', 'name' => 'Mauritius'),
	    array('abbreviation' => 'YT', 'name' => 'Mayotte'),
	    array('abbreviation' => 'MX', 'name' => 'Mexico'),
	    array('abbreviation' => 'FM', 'name' => 'Micronesia, Federated States of'),
	    array('abbreviation' => 'MD', 'name' => 'Moldova, Republic of'),
	    array('abbreviation' => 'MC', 'name' => 'Monaco'),
	    array('abbreviation' => 'MN', 'name' => 'Mongolia'),
	    array('abbreviation' => 'ME', 'name' => 'Montenegro'),
	    array('abbreviation' => 'MS', 'name' => 'Montserrat'),
	    array('abbreviation' => 'MA', 'name' => 'Morocco'),
	    array('abbreviation' => 'MZ', 'name' => 'Mozambique'),
	    array('abbreviation' => 'MM', 'name' => 'Myanmar'),
	    array('abbreviation' => 'NA', 'name' => 'Namibia'),
	    array('abbreviation' => 'NR', 'name' => 'Nauru'),
	    array('abbreviation' => 'NP', 'name' => 'Nepal'),
	    array('abbreviation' => 'NL', 'name' => 'Netherlands'),
	    array('abbreviation' => 'NC', 'name' => 'New Caledonia'),
	    array('abbreviation' => 'NZ', 'name' => 'New Zealand'),
	    array('abbreviation' => 'NI', 'name' => 'Nicaragua'),
	    array('abbreviation' => 'NE', 'name' => 'Niger'),
	    array('abbreviation' => 'NG', 'name' => 'Nigeria'),
	    array('abbreviation' => 'NU', 'name' => 'Niue'),
	    array('abbreviation' => 'NF', 'name' => 'Norfolk Island'),
	    array('abbreviation' => 'MP', 'name' => 'Northern Mariana Islands'),
	    array('abbreviation' => 'NO', 'name' => 'Norway'),
	    array('abbreviation' => 'OM', 'name' => 'Oman'),
	    array('abbreviation' => 'PK', 'name' => 'Pakistan'),
	    array('abbreviation' => 'PW', 'name' => 'Palau'),
	    array('abbreviation' => 'PS', 'name' => 'Palestine, State of'),
	    array('abbreviation' => 'PA', 'name' => 'Panama'),
	    array('abbreviation' => 'PG', 'name' => 'Papua New Guinea'),
	    array('abbreviation' => 'PY', 'name' => 'Paraguay'),
	    array('abbreviation' => 'PE', 'name' => 'Peru'),
	    array('abbreviation' => 'PH', 'name' => 'Philippines'),
	    array('abbreviation' => 'PN', 'name' => 'Pitcairn'),
	    array('abbreviation' => 'PL', 'name' => 'Poland'),
	    array('abbreviation' => 'PT', 'name' => 'Portugal'),
	    array('abbreviation' => 'PR', 'name' => 'Puerto Rico'),
	    array('abbreviation' => 'QA', 'name' => 'Qatar'),
	    array('abbreviation' => 'RE', 'name' => 'R&eacute;union'),
	    array('abbreviation' => 'RO', 'name' => 'Romania'),
	    array('abbreviation' => 'RU', 'name' => 'Russian Federation'),
	    array('abbreviation' => 'RW', 'name' => 'Rwanda'),
	    array('abbreviation' => 'BL', 'name' => 'Saint Barth&eacute;lemy'),
	    array('abbreviation' => 'SH', 'name' => 'Saint Helena, Ascension and Tristan da Cunha'),
	    array('abbreviation' => 'KN', 'name' => 'Saint Kitts and Nevis'),
	    array('abbreviation' => 'LC', 'name' => 'Saint Lucia'),
	    array('abbreviation' => 'MF', 'name' => 'Saint Martin (French part)'),
	    array('abbreviation' => 'PM', 'name' => 'Saint Pierre and Miquelon'),
	    array('abbreviation' => 'VC', 'name' => 'Saint Vincent and the Grenadines'),
	    array('abbreviation' => 'WS', 'name' => 'Samoa'),
	    array('abbreviation' => 'SM', 'name' => 'San Marino'),
	    array('abbreviation' => 'ST', 'name' => 'Sao Tome and Principe'),
	    array('abbreviation' => 'SA', 'name' => 'Saudi Arabia'),
	    array('abbreviation' => 'SN', 'name' => 'Senegal'),
	    array('abbreviation' => 'RS', 'name' => 'Serbia'),
	    array('abbreviation' => 'SC', 'name' => 'Seychelles'),
	    array('abbreviation' => 'SL', 'name' => 'Sierra Leone'),
	    array('abbreviation' => 'SG', 'name' => 'Singapore'),
	    array('abbreviation' => 'SX', 'name' => 'Sint Maarten (Dutch part)'),
	    array('abbreviation' => 'SK', 'name' => 'Slovakia'),
	    array('abbreviation' => 'SI', 'name' => 'Slovenia'),
	    array('abbreviation' => 'SB', 'name' => 'Solomon Islands'),
	    array('abbreviation' => 'SO', 'name' => 'Somalia'),
	    array('abbreviation' => 'ZA', 'name' => 'South Africa'),
	    array('abbreviation' => 'GS', 'name' => 'South Georgia and the South Sandwich Islands'),
	    array('abbreviation' => 'SS', 'name' => 'South Sudan'),
	    array('abbreviation' => 'ES', 'name' => 'Spain'),
	    array('abbreviation' => 'LK', 'name' => 'Sri Lanka'),
	    array('abbreviation' => 'SD', 'name' => 'Sudan'),
	    array('abbreviation' => 'SR', 'name' => 'Suriname'),
	    array('abbreviation' => 'SJ', 'name' => 'Svalbard and Jan Mayen'),
	    array('abbreviation' => 'SZ', 'name' => 'Swaziland'),
	    array('abbreviation' => 'SE', 'name' => 'Sweden'),
	    array('abbreviation' => 'CH', 'name' => 'Switzerland'),
	    array('abbreviation' => 'SY', 'name' => 'Syrian Arab Republic'),
	    array('abbreviation' => 'TW', 'name' => 'Taiwan, Province of China'),
	    array('abbreviation' => 'TJ', 'name' => 'Tajikistan'),
	    array('abbreviation' => 'TZ', 'name' => 'Tanzania, United Republic of'),
	    array('abbreviation' => 'TH', 'name' => 'Thailand'),
	    array('abbreviation' => 'TL', 'name' => 'Timor-Leste'),
	    array('abbreviation' => 'TG', 'name' => 'Togo'),
	    array('abbreviation' => 'TK', 'name' => 'Tokelau'),
	    array('abbreviation' => 'TO', 'name' => 'Tonga'),
	    array('abbreviation' => 'TT', 'name' => 'Trinidad and Tobago'),
	    array('abbreviation' => 'TN', 'name' => 'Tunisia'),
	    array('abbreviation' => 'TR', 'name' => 'Turkey'),
	    array('abbreviation' => 'TM', 'name' => 'Turkmenistan'),
	    array('abbreviation' => 'TC', 'name' => 'Turks and Caicos Islands'),
	    array('abbreviation' => 'TV', 'name' => 'Tuvalu'),
	    array('abbreviation' => 'UG', 'name' => 'Uganda'),
	    array('abbreviation' => 'UA', 'name' => 'Ukraine'),
	    array('abbreviation' => 'AE', 'name' => 'United Arab Emirates'),
	    array('abbreviation' => 'GB', 'name' => 'United Kingdom'),
	    array('abbreviation' => 'US', 'name' => 'United States'),
	    array('abbreviation' => 'UM', 'name' => 'United States Minor Outlying Islands'),
	    array('abbreviation' => 'UY', 'name' => 'Uruguay'),
	    array('abbreviation' => 'UZ', 'name' => 'Uzbekistan'),
	    array('abbreviation' => 'VU', 'name' => 'Vanuatu'),
	    array('abbreviation' => 'VE', 'name' => 'Venezuela, Bolivarian Republic of'),
	    array('abbreviation' => 'VN', 'name' => 'Viet Nam'),
	    array('abbreviation' => 'VG', 'name' => 'Virgin Islands, British'),
	    array('abbreviation' => 'VI', 'name' => 'Virgin Islands, U.S.'),
	    array('abbreviation' => 'WF', 'name' => 'Wallis and Futuna'),
	    array('abbreviation' => 'EH', 'name' => 'Western Sahara'),
	    array('abbreviation' => 'YE', 'name' => 'Yemen'),
	    array('abbreviation' => 'ZM', 'name' => 'Zambia'),
	    array('abbreviation' => 'ZW', 'name' => 'Zimbabwe'),
    );

    public $hasMany = array(
        'Region' => array(
            'className' => 'Region',
            'order' => 'Region.name',
            'foreignKey' => 'abbreviation',
        ),
        'Address' => array(
            'className' => 'Address',
            'foreignKey' => 'abbreviation',
        ),
    );

    /**
     * @param $counties array of country codes
     * @return array List of abbreviation/country
     */
    public function names($countries) {
        return $this->find('list', array(
            'fields' => array(
                'abbreviation', 'name'
            ),
            'conditions' => array(
                'abbreviation' => $countries, 
            ),
            'recursive' => -1,
        ));
    }
}
