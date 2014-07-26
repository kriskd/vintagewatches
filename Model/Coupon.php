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
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
				//'last' => false, // Stop validation after this rule
			),
            'unique' => array(
                'rule' => 'uniqueNotArchived',
                'message' => 'This coupon code is unavailable, choose another.',
            ),
		),
        'email' => array(
            'validEmail' => array(
                'rule'    => array('email', true),
                'message' => 'Please enter a valid email address.',
                'allowEmtpy' => true,
                'required' => false,
            )
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
            'amount' => array(
                'rule' => array('couponAmount'),
                'message' => 'Enter valid percentage or dollar amount.',
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
			'dependent' => false, // Don't delete associated order
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
        if (isset($this->data['Coupon']['code'])) {
            $this->data['Coupon']['code'] = strtolower($this->data['Coupon']['code']);
        }
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
     * Get the number of coupons available for a code
     * @param array The coupon array 
     * @return int
     */
    public function available($coupon) {
        $count = $this->Order->find('count', array(
            'conditions' => array(
                'Order.coupon_id' => $coupon['Coupon']['id'],
            ),
            'recursive' => -1,
        ));

        return $coupon['Coupon']['total'] - $count;
    }

    /**
     * Is coupon valid for the user
     * @return bool
     */
    public function valid($code, $email, $subTotal) {
        if (empty($code) || empty($email)) return false;

        $coupon = $this->find('first', array(
            'conditions' => array(
                'Coupon.code' => $code,
                'Coupon.archived' => 0,
            ),
            'fields' => array(
                'id', 'code', 'type', 'amount', 'total', 'assigned_to', 'minimum_order', 'DATE(expire_date) AS expire_date', 'archived'
            ),
            'recursive' => -1
        ));

        // Coupon archived
        if (empty($coupon)) return false;

        // Coupon expired
        if (!empty($coupon[0]['expire_date']) && strtotime($coupon[0]['expire_date']) < strtotime('now')) return false;

        // Assigned to user
        if (!empty($coupon['Coupon']['assigned_to']) && strcasecmp($email, $coupon['Coupon']['assigned_to'])!=0) return false;

        // Coupon redeemed
        if ($this->redeemed($email, $code)) return false;

        // Coupon available
        if ($this->available($coupon) < 1) return false;

        // Minimum order met
        if ($coupon['Coupon']['minimum_order'] > 0 && $coupon['Coupon']['minimum_order'] < $subTotal) return false;

        return $coupon;
    }

    /**
     * Make sure coupon code is unique among non-archived coupons
     * return bool
     */
    public function uniqueNotArchived($code) {
        $code = current($code);
        $conditions = array(
            'Coupon.code' => strtolower($code),
            'Coupon.archived' => 0,
        );

        if (!empty($this->data['Coupon']['id'])) {
            $conditions['NOT'] = array(
                'Coupon.id' => $this->data['Coupon']['id']
            );
        }

        $codes = $this->find('all', array(
            'conditions' => $conditions,
            'recursive' => -1
        ));
        
        return !(bool) $codes;
    }

    public function futureDate($date) {
        $date = current($date); 
        return strtotime($date) > strtotime('now') ? true : false;
    }

    /**
     * Validate coupon amount based on coupon type
     */
    public function couponAmount() {
        if ($this->data[$this->name]['type'] == 'percentage') {
            return $this->data[$this->name]['amount'] > 0 && $this->data[$this->name]['amount'] < 1 ? true : false;
        }
        if ($this->data[$this->name]['type'] == 'fixed') {
            return $this->data[$this->name]['amount'] > 0 ? true : false;
        }
        return false;
    }

    public function removeRequiredCode() {
        $this->validator()->remove('code');
    }
}
