<?php
App::uses('AppModel', 'Model');
/**
 * Invoice Model
 *
 * @property InvoiceItem $InvoiceItem
 */
class Invoice extends AppModel {

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
                'email' => array(
                    'notempty' => array(
                        'rule' => array('notempty'),
                        'message' => 'Please enter your email.'),
                    'email' => array(
                        'rule' => array('email'),
                        'message' => 'Please supply a valid email address.'
                    )
                ),
		'slug' => array(
			'alphaNumeric' => array(
				'rule' => array('alphaNumeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'shipping' => array(
			'decimal' => array(
				'rule' => array('decimal'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'created' => array(
			'datetime' => array(
				'rule' => array('datetime'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'modified' => array(
			'datetime' => array(
				'rule' => array('datetime'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'InvoiceItem' => array(
			'className' => 'InvoiceItem',
			'foreignKey' => 'invoice_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
                'Address' => array(
			'className' => 'Address',
			'foreignKey' => 'foreign_id',
			'dependent' => true, //Delete associated addreseses
			'conditions' => array('Address.class' => 'Invoice'),
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);
        
        public function beforeValidate($options = array())
        {
            if (isset($this->data['Address'])) {
                $addresses = $this->data['Address'];
                $this->data['Address'] = array_map(function($item) {
                        $item['class'] = 'Invoice';
                        return $item;
                    }, $addresses);
            }
            return true;
        }

}
