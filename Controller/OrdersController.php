<?php
App::uses('Address', 'Model');
class OrdersController extends AppController
{
    public $uses = array('Watch', 'Address', 'Order');
    
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
	
	//Form submitted
	if($this->request->is('post')){
	    $data = $this->request->data;
	    unset($data['Shipping']);
	    
	    $addresses = $data['Address'];
	    
	    $addressesToSave = array();
	    unset($addresses['select-country']);

	    foreach($addresses as $type => $item){
		$address = $item;
		$address['type'] = $type;
		$addressesToSave[] = $address;
	    }
	    
	    $data['Address'] = $addressesToSave;

	    $checkoutData = ($this->Session->check('Cart.shipping') == true) &&
			    ($this->Session->check('Cart.items') == true) &&
			    ($this->Session->check('Cart.total')== true) &&
			    ($this->Session->read('Cart.total') > 0);
	    
	    if(!$checkoutData){
		//There is no data to checkout with
		$this->redirect(array('controller' => 'watches', 'action' => 'index'));
	    }
	    
	    if($this->Address->saveMany($addressesToSave)){
		$insertedIds = $this->Address->insertedIds; 
		
		//Get the shipping amount from the session and add to Order
		$shippingAmount = $this->Session->read('Cart.shipping');
		$data['Order']['shippingAmount'] = $shippingAmount;
		
		//Save the Order
		$this->Order->save($data['Order']);
		
		//Get the order_id
		$order_id = $this->Order->id; 
		
		//Get the purchased items from the Session, add the order_id and
		//update the items with the order_id
		$items = $this->Session->read('Cart.items');
		$watches = $this->Watch->getCartWatches($items);
		$purchasedWatches = array_map(function($item) use ($order_id){
					$item['Watch']['order_id'] = $order_id;
					$item['Watch']['active'] = 0;
					return $item;
				    } , $watches);
		$this->Watch->saveMany($purchasedWatches);
		
		
		//Assign the addresses to an order
		$this->Address->updateAll(array('order_id' => $order_id), array('id' => $insertedIds));

		//We should run the charge first and if it's successful do everything else.
		//But we need to first have a valid billing address
		$amount = $this->Session->read('Cart.total'); 
		$stripeToken = $this->request->data['stripeToken'];
		$data = array('amount' => $amount,
			      'stripeToken' => $stripeToken);
		$result = $this->Stripe->charge($data);
				
		//Write the results of the Stripe payment processing to the table
		$this->Order->save($result);
		
		if($result['stripe_paid'] == true){
		    $this->Session->delete('Cart');
		    $this->redirect(array('action' => 'confirm', $order_id));
		}
		
		$this->Session->setFlash('There was a payment problem.');
	    }
	    else{
		//Address field(s) didn't validate, get the errors
		$errors = $this->Address->validationErrors;
		//Errors are a numeric array, give them keys for billing and shipping
		$fixErrors['billing'] = $errors[0];
		if(isset($errors[1])){
		    $fixErrors['shipping'] = $errors[1];
		}

		$this->Address->validationErrors = $fixErrors;
		$this->Address->data = $addressesToSave;

		$this->Session->write('Address', serialize($this->Address));
	    }
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
    
    public function confirm($order_id = null)
    {
	$referer = trim($this->referer(null, true), '/');
	if (strcasecmp($referer, 'orders') != 0){
	    $this->redirect(array('controller' => 'watches', 'action' => 'index'));
	}
	if (!$this->Order->exists($order_id)) {
            throw new NotFoundException(__('Invalid order'));
        }

	$this->set('order', $this->Order->read(null, $order_id));
    }
    
    /**
     * Get country shipping and total
     */
    public function getShipping()
    {	
	if($this->request->is('ajax')){ 
	    $shipping = null;
	    $query = $this->request->query; 
	    $country = $query['country'];
	    switch($country){
		case 'us':
		    $shipping = '8';
		    break;
		case 'ca':
		    $shipping = '38';
		    break;
		case 'other';
		    $shipping = '45';
		    break;
	    }

	    $subTotal = $this->Cart->getCartSubTotal(); 
	    $total = $subTotal + $shipping;
	    $this->Session->write('Cart.shipping', $shipping);
	    $this->Session->write('Cart.total', $total);
	    
	    $this->set(array('data' => compact('shipping', 'total')));
	    $this->layout = 'ajax';
	}
    }
    
    /**
     * Get address form based on country
     */
    public function getAddress()
    {
	if($this->request->is('ajax')){
	    $query = $this->request->query; 
	    $country = $query['country'];
	    $shipping = $query['shipping'];
	    $statesProvinces = array('states' => $this->_getStates(), 'provinces' => $this->_getCanadianProvinces());
	    $data = compact('shipping', 'country', 'statesProvinces');
	    $data['values'] = null;
	    $data['errors'] = null;
	    
	    //Address data and errors in the session
	    if($this->Session->check('Address') == true){
		$address = $this->Session->read('Address');
		$address = unserialize($address);
		$addresses = $address->data;
		foreach($addresses as $item){
		    $type = $item['type'];
		    unset($item['type']);
		    $values[$type] = $item;
		}
		$data['values'] = $values;
		$data['errors'] = $address->validationErrors;

		//For other countries we need to take the error message in country
		//and put it in countryName
		if(strcasecmp($country, 'other') == 0){
		    foreach($data['errors'] as $key => $errors){
			if(isset($errors['country'])){
			    $errors['countryName'] = $errors['country'];
			}
			$newErrors[$key] = $errors;
		    }
		    $data['errors'] = $newErrors;
		}

		$this->Session->delete('Address');
	    }
	    $this->set(compact('data'));
	    $this->layout = 'ajax';
	}
    }
    
    /**
     * Get country based on state or province
     */
    public function getCountry()
    {
	if($this->request->is('ajax')){
	    $query = $this->request->query;
	    $state = $query['state'];
	    $states = $this->_getStates();
	    $provinces = $this->_getCanadianProvinces();
	    $country = (isset($states[$state]) ? 'US' : (isset($provinces[$state]) ? 'CA' : ''));

	    $this->set(array('data' => compact('country')));
	    $this->layout = 'ajax';
	}
    }
    
    /**
     * Proof of concept for address validation
     */
    /*public function test()
    {
	if($this->request->is('post')){
	    $data = $this->request->data; 
	    $addresses = $data['Address'];
	    foreach($addresses as $type => $item){ 
		$address = $item;
		$address['type'] = $type;
		$addressesToSave[] = $address;
	    } 

	    $this->Address->set($addressesToSave);
	    //Grab the form data because the save attempt munges it
	    $data = $this->Address->data;
	    if($this->Address->saveMany($addressesToSave)){
		$this->Session->setFlash('valid');
	    }  
	    else{
		$errors = $this->Address->validationErrors;
		$fixErrors['billing'] = $errors[0];
		if(isset($errors[1])){
		    $fixErrors['shipping'] = $errors[1];
		}
		$this->Address->validationErrors = $fixErrors;
		$this->Address->data = $data;
	    }
	}
    }*/
    
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
