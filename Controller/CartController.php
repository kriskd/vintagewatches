<?php

class CartController extends AppController
{
    public $uses = array('Cart', 'Watch');
    
    public function index()
    {
        if($this->Cart->cartEmpty() == true){
            $this->redirect(array('controller' => 'watches', 'action' => 'index'));
        }
	
        $watches = $this->Cart->getCartItems(); 
	$total = 0;
        $months = array_combine(range(1,12), range(1,12));
        $year = date('Y'); 
        for($i=date('Y'); $i<=date('Y')+10; $i++){
            $years[$i] = $i;
        }
	
	//Handle ajax request for autocomplete
        if($this->request->is('ajax')){
            $query = $this->request->query; 
            $search = $query['term'];
            if(!$search){ 
                throw new NotFoundException('Search term required');
            }
	    
	    $filtered = array();
	    $countries = $this->_getCountries();
	    foreach($countries as $key => $country){
		if(stripos($country, htmlentities($search)) !== false){
		    $filtered[] = array('id' => $key, 'value' => html_entity_decode($country, ENT_COMPAT, 'UTF-8'));
		}
	    }

	    $this->set(compact('filtered'));
	    $this->layout = 'ajax';
        }
	
        $this->set(compact('watches', 'months', 'years', 'total'));
    }
    
    public function add($id = null)
    {   
        if (!$this->Watch->exists($id)) {
            throw new NotFoundException(__('Invalid watch'));
        }
        $items = array();
        if($this->Session->check('Cart.items') == true){
            $items = $this->Session->read('Cart.items');
            if(in_array($id, $items)){
                $this->Session->setFlash('That item is already in your cart.');
                $this->redirect(array('controller' => 'watches', 'action' => 'index'));
            }
        }

        $items[] = $id; 
        $this->Session->write('Cart.items', $items);
        
        $this->redirect(array('action' => 'index'));
    }
    
    public function checkout()
    {
        if($this->request->is('post')){
	    if($this->Session->check('Cart.total') == true){
		$amount = $this->Session->read('Cart.total'); 
		if($amount > 0){
		    $stripeToken = $this->request->data['stripeToken'];
		    $data = array('amount' => $amount,
				  'stripeToken' => $stripeToken);
		    $result = $this->Stripe->charge($data); 
		    if($result['stripe_paid'] == true){
			$items = $this->Session->read('Cart.items');
			foreach($items as $id){
			    $this->Watch->id = $id;
			    $this->Watch->saveField('active', 0);
			}
			$this->Session->delete('Cart');
			$this->Session->setFlash('Payment Received');
			$this->redirect(array('controller' => 'watches', 'action' => 'index'));
		    }
		}
	    } 
	    $this->Session->setFlash('Please select your country.');
        }
        $this->redirect(array('action' => 'index')); 
    }
    
    public function remove($id = null)
    {
        if (!$this->Watch->exists($id)) {
            throw new NotFoundException(__('Invalid watch'));
        }

        if($this->Session->check('Cart.items') == true){
            $items = $this->Session->read('Cart.items'); 
            if(in_array($id, $items)){
                $key = array_search($id, $items);
                unset($items[$key]); 
                $this->Session->write('Cart.items', $items);
                $this->redirect(array('action' => 'index'));
            }
        }
    }
    
    /**
     * Get country specific data
     */
    public function getCountry()
    {	
	if($this->request->is('ajax')){
	    $shipping = null;
	    $states = null;
	    $provinces = null;
	    $query = $this->request->query; 
	    $country = $query['country'];
	    switch($country){
		case 'us':
		    $shipping = '8';
		    $states = $this->_getStates();
		    break;
		case 'ca':
		    $shipping = '38';
		    $provinces = $this->_getCanadianProvinces();
		    break;
		case 'other';
		    $shipping = '45';
		    break;
	    }

	    $subTotal = $this->Cart->getCartSubTotal(); 
	    $total = $subTotal + $shipping; 
	    $this->Session->write('Cart.total', $total);
	    
	    $this->set(array('data' => compact('shipping', 'total', 'country', 'states', 'provinces')));
	    $this->layout = 'ajax';
	}
    }
    
    protected function _getStates()
    {
        return array('AL'=>'Alabama',  
		'AK'=>'Alaska',  
                'AZ'=>'Arizona',  
                'AR'=>'Arkansas',  
                'CA'=>'California',  
                'CO'=>'Colorado',  
                'CT'=>'Connecticut',  
                'DE'=>'Delaware',  
                'DC'=>'District Of Columbia',  
                'FL'=>'Florida',  
                'GA'=>'Georgia',  
                'HI'=>'Hawaii',  
                'ID'=>'Idaho',  
                'IL'=>'Illinois',  
                'IN'=>'Indiana',  
                'IA'=>'Iowa',  
                'KS'=>'Kansas',  
                'KY'=>'Kentucky',  
                'LA'=>'Louisiana',  
                'ME'=>'Maine',  
                'MD'=>'Maryland',  
                'MA'=>'Massachusetts',  
                'MI'=>'Michigan',  
                'MN'=>'Minnesota',  
                'MS'=>'Mississippi',  
                'MO'=>'Missouri',  
                'MT'=>'Montana',
                'NE'=>'Nebraska',
                'NV'=>'Nevada',
                'NH'=>'New Hampshire',
                'NJ'=>'New Jersey',
                'NM'=>'New Mexico',
                'NY'=>'New York',
                'NC'=>'North Carolina',
                'ND'=>'North Dakota',
                'OH'=>'Ohio',  
                'OK'=>'Oklahoma',  
                'OR'=>'Oregon',  
                'PA'=>'Pennsylvania',  
                'RI'=>'Rhode Island',  
                'SC'=>'South Carolina',  
                'SD'=>'South Dakota',
                'TN'=>'Tennessee',  
                'TX'=>'Texas',  
                'UT'=>'Utah',  
                'VT'=>'Vermont',  
                'VA'=>'Virginia',  
                'WA'=>'Washington',  
                'WV'=>'West Virginia',  
                'WI'=>'Wisconsin',  
                'WY'=>'Wyoming');
    }
    
    protected function _getCanadianProvinces()
    {
        return array(
	    'AB'=>'Alberta', 
            'BC'=>'British Columbia',
	    'MB'=>'Manitoba',
	    'NB'=>'New Brunswick',
	    'NL'=>'Newfoundland and Labrador',
	    'NT'=>'Northwest Territories',
	    'NS'=>'Nova Scotia',
	    'NU'=>'Nunavut',
            'ON'=>'Ontario', 
            'PE'=>'Prince Edward Island', 
            'QC'=>'Quebec', 
            'SK'=>'Saskatchewan', 
            'YT'=>'Yukon Territory');
    }
    
    protected function _getCountries()
    {
	return array(
	    'AF' => 'Afghanistan',
	    'AX' => '&Aring;land Islands',
	    'AL' => 'Albania',
	    'DZ' => 'Algeria',
	    'AS' => 'American Samoa',
	    'AD' => 'Andorra',
	    'AO' => 'Angola',
	    'AI' => 'Anguilla',
	    'AQ' => 'Antarctica',
	    'AG' => 'Antigua and Barbuda',
	    'AR' => 'Argentina',
	    'AM' => 'Armenia',
	    'AW' => 'Aruba',
	    'AU' => 'Australia',
	    'AT' => 'Austria',
	    'AZ' => 'Azerbaijan',
	    'BS' => 'Bahamas',
	    'BH' => 'Bahrain',
	    'BD' => 'Bangladesh',
	    'BB' => 'Barbados',
	    'BY' => 'Belarus',
	    'BE' => 'Belgium',
	    'BZ' => 'Belize',
	    'BJ' => 'Benin',
	    'BM' => 'Bermuda',
	    'BT' => 'Bhutan',
	    'BO' => 'Bolivia, Plurinational State of',
	    'BQ' => 'Bonaire, Sint Eustatius and Saba',
	    'BA' => 'Bosnia and Herzegovina',
	    'BW' => 'Botswana',
	    'BV' => 'Bouvet Island',
	    'BR' => 'Brazil',
	    'IO' => 'British Indian Ocean Territory',
	    'BN' => 'Brunei Darussalam',
	    'BG' => 'Bulgaria',
	    'BF' => 'Burkina Faso',
	    'BI' => 'Burundi',
	    'KH' => 'Cambodia',
	    'CM' => 'Cameroon',
	    'CA' => 'Canada',
	    'CV' => 'Cape Verde',
	    'KY' => 'Cayman Islands',
	    'CF' => 'Central African Republic',
	    'TD' => 'Chad',
	    'CL' => 'Chile',
	    'CN' => 'China',
	    'CX' => 'Christmas Island',
	    'CC' => 'Cocos (Keeling) Islands',
	    'CO' => 'Colombia',
	    'KM' => 'Comoros',
	    'CG' => 'Congo',
	    'CD' => 'Congo, the Democratic Republic of the',
	    'CK' => 'Cook Islands',
	    'CR' => 'Costa Rica',
	    'CI' => 'C&ocirc;te d\'Ivoire',
	    'HR' => 'Croatia',
	    'CU' => 'Cuba',
	    'CW' => 'Cura&ccedil;ao',
	    'CY' => 'Cyprus',
	    'CZ' => 'Czech Republic',
	    'DK' => 'Denmark',
	    'DJ' => 'Djibouti',
	    'DM' => 'Dominica',
	    'DO' => 'Dominican Republic',
	    'EC' => 'Ecuador',
	    'EG' => 'Egypt',
	    'SV' => 'El Salvador',
	    'GQ' => 'Equatorial Guinea',
	    'ER' => 'Eritrea',
	    'EE' => 'Estonia',
	    'ET' => 'Ethiopia',
	    'FK' => 'Falkland Islands (Malvinas)',
	    'FO' => 'Faroe Islands',
	    'FJ' => 'Fiji',
	    'FI' => 'Finland',
	    'FR' => 'France',
	    'GF' => 'French Guiana',
	    'PF' => 'French Polynesia',
	    'TF' => 'French Southern Territories',
	    'GA' => 'Gabon',
	    'GM' => 'Gambia',
	    'GE' => 'Georgia',
	    'DE' => 'Germany',
	    'GH' => 'Ghana',
	    'GI' => 'Gibraltar',
	    'GR' => 'Greece',
	    'GL' => 'Greenland',
	    'GD' => 'Grenada',
	    'GP' => 'Guadeloupe',
	    'GU' => 'Guam',
	    'GT' => 'Guatemala',
	    'GG' => 'Guernsey',
	    'GN' => 'Guinea',
	    'GW' => 'Guinea-Bissau',
	    'GY' => 'Guyana',
	    'HT' => 'Haiti',
	    'HM' => 'Heard Island and McDonald Islands',
	    'VA' => 'Holy See (Vatican City State)',
	    'HN' => 'Honduras',
	    'HK' => 'Hong Kong',
	    'HU' => 'Hungary',
	    'IS' => 'Iceland',
	    'IN' => 'India',
	    'ID' => 'Indonesia',
	    'IR' => 'Iran, Islamic Republic of',
	    'IQ' => 'Iraq',
	    'IE' => 'Ireland',
	    'IM' => 'Isle of Man',
	    'IL' => 'Israel',
	    'IT' => 'Italy',
	    'JM' => 'Jamaica',
	    'JP' => 'Japan',
	    'JE' => 'Jersey',
	    'JO' => 'Jordan',
	    'KZ' => 'Kazakhstan',
	    'KE' => 'Kenya',
	    'KI' => 'Kiribati',
	    'KP' => 'Korea, Democratic People\'s Republic of',
	    'KR' => 'Korea, Republic of',
	    'KW' => 'Kuwait',
	    'KG' => 'Kyrgyzstan',
	    'LA' => 'Lao People\'s Democratic Republic',
	    'LV' => 'Latvia',
	    'LB' => 'Lebanon',
	    'LS' => 'Lesotho',
	    'LR' => 'Liberia',
	    'LY' => 'Libya',
	    'LI' => 'Liechtenstein',
	    'LT' => 'Lithuania',
	    'LU' => 'Luxembourg',
	    'MO' => 'Macao',
	    'MK' => 'Macedonia, The Former Yugoslav Republic of',
	    'MG' => 'Madagascar',
	    'MW' => 'Malawi',
	    'MY' => 'Malaysia',
	    'MV' => 'Maldives',
	    'ML' => 'Mali',
	    'MT' => 'Malta',
	    'MH' => 'Marshall Islands',
	    'MQ' => 'Martinique',
	    'MR' => 'Mauritania',
	    'MU' => 'Mauritius',
	    'YT' => 'Mayotte',
	    'MX' => 'Mexico',
	    'FM' => 'Micronesia, Federated States of',
	    'MD' => 'Moldova, Republic of',
	    'MC' => 'Monaco',
	    'MN' => 'Mongolia',
	    'ME' => 'Montenegro',
	    'MS' => 'Montserrat',
	    'MA' => 'Morocco',
	    'MZ' => 'Mozambique',
	    'MM' => 'Myanmar',
	    'NA' => 'Namibia',
	    'NR' => 'Nauru',
	    'NP' => 'Nepal',
	    'NL' => 'Netherlands',
	    'NC' => 'New Caledonia',
	    'NZ' => 'New Zealand',
	    'NI' => 'Nicaragua',
	    'NE' => 'Niger',
	    'NG' => 'Nigeria',
	    'NU' => 'Niue',
	    'NF' => 'Norfolk Island',
	    'MP' => 'Northern Mariana Islands',
	    'NO' => 'Norway',
	    'OM' => 'Oman',
	    'PK' => 'Pakistan',
	    'PW' => 'Palau',
	    'PS' => 'Palestine, State of',
	    'PA' => 'Panama',
	    'PG' => 'Papua New Guinea',
	    'PY' => 'Paraguay',
	    'PE' => 'Peru',
	    'PH' => 'Philippines',
	    'PN' => 'Pitcairn',
	    'PL' => 'Poland',
	    'PT' => 'Portugal',
	    'PR' => 'Puerto Rico',
	    'QA' => 'Qatar',
	    'RE' => 'R&eacute;union',
	    'RO' => 'Romania',
	    'RU' => 'Russian Federation',
	    'RW' => 'Rwanda',
	    'BL' => 'Saint Barth&eacute;lemy',
	    'SH' => 'Saint Helena, Ascension and Tristan da Cunha',
	    'KN' => 'Saint Kitts and Nevis',
	    'LC' => 'Saint Lucia',
	    'MF' => 'Saint Martin (French part)',
	    'PM' => 'Saint Pierre and Miquelon',
	    'VC' => 'Saint Vincent and the Grenadines',
	    'WS' => 'Samoa',
	    'SM' => 'San Marino',
	    'ST' => 'Sao Tome and Principe',
	    'SA' => 'Saudi Arabia',
	    'SN' => 'Senegal',
	    'RS' => 'Serbia',
	    'SC' => 'Seychelles',
	    'SL' => 'Sierra Leone',
	    'SG' => 'Singapore',
	    'SX' => 'Sint Maarten (Dutch part)',
	    'SK' => 'Slovakia',
	    'SI' => 'Slovenia',
	    'SB' => 'Solomon Islands',
	    'SO' => 'Somalia',
	    'ZA' => 'South Africa',
	    'GS' => 'South Georgia and the South Sandwich Islands',
	    'SS' => 'South Sudan',
	    'ES' => 'Spain',
	    'LK' => 'Sri Lanka',
	    'SD' => 'Sudan',
	    'SR' => 'Suriname',
	    'SJ' => 'Svalbard and Jan Mayen',
	    'SZ' => 'Swaziland',
	    'SE' => 'Sweden',
	    'CH' => 'Switzerland',
	    'SY' => 'Syrian Arab Republic',
	    'TW' => 'Taiwan, Province of China',
	    'TJ' => 'Tajikistan',
	    'TZ' => 'Tanzania, United Republic of',
	    'TH' => 'Thailand',
	    'TL' => 'Timor-Leste',
	    'TG' => 'Togo',
	    'TK' => 'Tokelau',
	    'TO' => 'Tonga',
	    'TT' => 'Trinidad and Tobago',
	    'TN' => 'Tunisia',
	    'TR' => 'Turkey',
	    'TM' => 'Turkmenistan',
	    'TC' => 'Turks and Caicos Islands',
	    'TV' => 'Tuvalu',
	    'UG' => 'Uganda',
	    'UA' => 'Ukraine',
	    'AE' => 'United Arab Emirates',
	    'GB' => 'United Kingdom',
	    'US' => 'United States',
	    'UM' => 'United States Minor Outlying Islands',
	    'UY' => 'Uruguay',
	    'UZ' => 'Uzbekistan',
	    'VU' => 'Vanuatu',
	    'VE' => 'Venezuela, Bolivarian Republic of',
	    'VN' => 'Viet Nam',
	    'VG' => 'Virgin Islands, British',
	    'VI' => 'Virgin Islands, U.S.',
	    'WF' => 'Wallis and Futuna',
	    'EH' => 'Western Sahara',
	    'YE' => 'Yemen',
	    'ZM' => 'Zambia',
	    'ZW' => 'Zimbabwe'
	);
    }
}
