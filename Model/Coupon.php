<?php
App::uses('AppModel', 'Model');
/**
 * Coupon Model
 *
 * @property Order $Order
 */
class Coupon extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'code';

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'code' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
				'message' => 'Enter a coupon code.',
				'allowEmpty' => false,
				'required' => true,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
            'unique' => array(
                'rule' => 'uniqueNotArchived',
                'message' => 'This coupon code is unavailable, choose another.',
            ),
		),
		'type' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
				//'message' => 'Your custom message here',
				'allowEmpty' => false,
				'required' => true,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
            'allowedChoice' => array(
                'rule'    => array('inList', array('percentage', 'fixed')),
                'message' => 'Enter either Percentage or Fixed.'
            ),
		),
		'amount' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
				'message' => 'Enter coupon amount.',
				'allowEmpty' => false,
				'required' => true,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
            'decimal' => array(
                'rule' => array('decimal', 2),
                'message' => 'Enter a valid amount.',
            ),
		),
        'total' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
                'message' => 'Enter the total number of coupons available.',
                'allowEmpty' => false,
                'required' => true,
            ), 
        ),
		'assigned_to' => array(
			'email' => array(
				'rule' => array('email'),
				'message' => 'Enter a valid email.',
				'allowEmpty' => true,
				'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
        // This validation not working correctly, might need to find another way
		'minimum_order' => array(
			'money' => array(
				'rule' => array('money'),
				'message' => 'Enter a valid minimum order amount.',
				'allowEmpty' => true,
				'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'expire_date' => array(
			'date' => array(
				'rule' => array('date'),
				'message' => 'Enter a valid expiration date.',
				'allowEmpty' => true,
				'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
            'future' => array(
                'rule' => 'futureDate',
                'message' => 'Please enter a future date.',
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
		'Order' => array(
			'className' => 'Order',
			'foreignKey' => 'coupon_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);

    public function beforeSave($options = array()) {
        $this->data['Coupon']['code'] = strtolower($this->data['Coupon']['code']);
        return true;
    }

    /**
     * Null values in search results are stripped out. Add them back as an emptry string by getting the
     * fields in the database and merging with Order results
     *
     * @return array
     */
    public function afterFind($results = array(), $primary = false) {
        $orderFields = $this->Order->getColumnTypes();
        foreach ($orderFields as $key => &$value) {
            $value = '';
        }
        unset($value); 
        foreach ($results as &$result) {
            foreach ($result as $key => &$orders) { 
                if (strcasecmp($key, 'Order')==0) {
                    foreach($orders as &$order) {
                       $order = array_merge($orderFields, $order); 
                    }
                }
            }
        }
        return $results;
    }

    /**
     * Has user redeemed coupon
     * @return bool
     */
    public function redeemed($email = null, $code = null) {
        $count = $this->find('count', array(
            'conditions' => array(
                'Coupon.code' => $code,
                'Order.email' => $email,
            ),
            'joins' => array(
                array(
                    'table' => 'orders',
                    'alias' => 'Order',
                    'type' => 'INNER',
                    'conditions' => array(
                        'Order.coupon_id = Coupon.id'
                    ),
                )
            ),
            'recursive' => -1,
        ));
        return (bool) $count;
    }

    /**
     * Make sure coupon code is unique among non-archived coupons
     * return bool
     */
    public function uniqueNotArchived($code) {
        $code = current($code);
        $codes = $this->find('all', array(
            'conditions' => array(
                'Coupon.code' => strtolower($code),
                'Coupon.archived' => 0,
            ),
            'recursive' => -1
        ));
        
        return !(bool) $codes;
    }

    public function futureDate($date) {
        $date = current($date); 
        return strtotime($date) > strtotime('now') ? true : false;
    }
}
