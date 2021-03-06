<?php
App::uses('AppModel', 'Model');
/**
 * Address Model
 *
 * @property address $address
 */
class Address extends AppModel {
    
    public $virtualFields = array(
        'name' => 'CONCAT(Address.firstName, " ", Address.lastName)',
        'cityStZip' => 'CONCAT(Address.city, ", ", Address.state, " ", Address.postalCode)'
    );
    
    public $actsAs = array(
        'HtmlPurifier.HtmlPurifier' => array( 
            'config' => 'StripAll',
            'fields' => array(
            'firstName', 'lastName', 'company', 'address1',
            'address2', 'city', 'state', 'postalCode', 'country'
            )
        )
    );

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'id' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'class' => array(
			'alphanumeric' => array(
				'rule' => array('alphanumeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
			'notBlank' => array(
				'rule' => array('notBlank'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
                'foreign_id' => array(
                        'notBlank' => array(
				'rule' => array('notBlank'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			), 
                ),
		'type' => array(
			'alphanumeric' => array(
				'rule' => array('alphanumeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
			'notBlank' => array(
				'rule' => array('notBlank'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'firstName' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message' => 'Please enter your first name.',
				//'allowEmpty' => false,
				//'required' => true,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'lastName' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message' => 'Please enter your last name.',
				//'allowEmpty' => false,
				//'required' => true,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'address1' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message' => 'Please enter your address.',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'city' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message' => 'Please enter a city.',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
        //Set required to false since country doesn't have a state
        'state' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message' => 'Please select your state or province.',
				//'allowEmpty' => false,
				'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'postalCode' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message' => 'Please enter a postal code.',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'country' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message' => 'Country must be selected from dropdown options. Type any portion of the country name and choose your country from the options that appear.',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
                'countryName' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message' => 'Please enter a country.',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);
        
        /*public $insertedIds = array();
        
        public function afterSave($created, $options = array()){
            if($created){
                $this->insertedIds[] = $this->getInsertID();
            }
            return true;
        }*/
	
	/**
	 * Remove state validation if not US or CA
	 */
	public function beforeValidate($options = array()) {
        if (isset($this->data['Address']['country']) && !in_array(strtoupper($this->data['Address']['country']), array('US', 'CA'))) {
            $this->removeStateValidation();
        }
        return true;
	}
        
    /**
     * Set the state to null if not US or CA
     */
    public function beforeSave($options = array()) {
        if (isset($this->data['Address']['country']) && !in_array(strtoupper($this->data['Address']['country']), array('US', 'CA'))) {
            $this->data['Address']['state'] = '';
        }
        return true;
    }
	
	/**
	 * Include full country name in results
	 * cityStZip virtual field is null if state is null so fix it
	 */
	public function afterFind($results, $primary = false)
	{
        $name = $this->name;
        $countries = $this->Country->getList();
	    
	    $results = array_map(function($item) use ($countries, $name) {
		if (!empty($item[$name]['country'])) {
		    $item[$name]['countryName'] = $countries[$item[$name]['country']];
		}
            return $item;
	    }, $results);
	    
	    $results = array_map(function($item) use ($name) { 
		if (empty($item[$name]['state']) && isset($item[$name]['city']) && isset($item[$name]['postalCode'])) {
		    $item[$name]['cityStZip'] = $item[$name]['city'] . ', ' . $item[$name]['postalCode'];
		}
            return $item;
	    }, $results);
	    
	    return $results;
	}

//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Order' => array(
			'className' => 'Order',
			'foreignKey' => 'foreign_id',
			'conditions' => array('Address.class' => 'Order'),
			'fields' => '',
			'order' => ''
		),
		'Invoice' => array(
			'className' => 'Invoice',
			'foreignKey' => 'foreign_id',
			'conditions' => array('Address.class' => 'Invoice'),
			'fields' => '',
			'order' => ''
        ),
        'Country' => array(
            'className' => 'Country',
            'foreignKey' => 'abbreviation'
        ),
	);
	
	/**
	 * Remove all validation rules. Used when creating an invoice since user might not be known.
	 */
	public function removeAllButCountry()
	{
	    $this->validator()->remove('firstName');
	    $this->validator()->remove('lastName');
	    $this->validator()->remove('address1');
	    $this->validator()->remove('city');
	    $this->validator()->remove('state');
	    $this->validator()->remove('postalCode');
	}
	
	public function removeStateValidation()
	{
	    $this->validator()->remove('state');
	}
	
	public function removeCountryValidation()
	{
	    $this->validator()->remove('country');
	}
}
