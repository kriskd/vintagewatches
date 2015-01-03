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

    public $virtualFields = array(
        'available' => 'select total-count(o.coupon_id)
                        from coupons c
                        left join orders o
                        on o.coupon_id=c.id
                        where Coupon.id=c.id
                        group by c.id' 
    );
    
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
/*        'email' => array(
            'validEmail' => array(
                'rule'    => array('email', true),
                'message' => 'Please enter a valid email address.',
                'allowEmtpy' => true,
                'required' => false,
            )
        ),*/
		'type' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
				'message' => 'Please choose a coupon type.',
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
                'rule' => array('couponDiscount'),
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
			),
            'future' => array(
                'rule' => 'futureDate',
                'message' => 'Please enter a future date.',
				'on' => 'create', // Limit validation to 'create' otherwise edit might not save
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

    public $belongsTo = array(
        'Brand' => array(
            'className' => 'Brand',
            'foreignKey' => 'brand_id',
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
            if (is_array($result)) {
                foreach ($result as $key => &$orders) { 
                    if (strcasecmp($key, 'Order')==0) {
                        foreach($orders as &$order) {
                           $order = array_merge($orderFields, $order); 
                        }
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
                'Coupon.archived' => 0,
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
     * Is coupon valid for the user
     * @return bool
     */
    public function valid($code, $email, $shipping, $cartItemIds) {
        if (empty($code) || empty($email)) return false;

        $coupon = $this->find('first', array(
            'conditions' => array(
                'Coupon.code' => $code,
                'Coupon.archived' => 0,
            ),
            'fields' => array(
                'id', 'code', 'type', 'amount', 'total', 'available', 'assigned_to', 'minimum_order', 'DATE(expire_date) AS expire_date', 'brand_id', 'archived'
            ),
            'recursive' => -1
        ));

        if (
            // Coupon archived
            empty($coupon) ||
            // Not assigned to user
            (!empty($coupon['Coupon']['assigned_to']) && strcasecmp($email, $coupon['Coupon']['assigned_to'])!=0) ||
            // User already redeemed coupon
            $this->redeemed($email, $code) ||
            // Coupon not available
            $coupon['Coupon']['available'] < 1
        ) {
            return array(
                'alert' => 'danger',
                'message' => 'This coupon is not valid.' 
            );
        }

        // Coupon expired
        if (!empty($coupon[0]['expire_date']) && strtotime($coupon[0]['expire_date'].' 23:59:59') < strtotime('now')) {
            return array(
                'alert' => 'danger',
                'message' => 'This coupon is expired.'
            );
        }
       
        $subTotal = $this->Order->Watch->sumWatchPrices($cartItemIds, $coupon['Coupon']['brand_id']);
        
        // Coupon not for right brand
        if (!empty($coupon['Coupon']['brand_id'])) {
            $this->Brand->id = $coupon['Coupon']['brand_id'];
            $brandName = $this->Brand->field('name');
            if (empty($subTotal)) {
                return array(
                    'alert' => 'info',
                    'message' => 'Order must include at least one '.$brandName.' watch.',
                );
            }
            
            if (strcasecmp($coupon['Coupon']['type'], 'fixed')==0 && $coupon['Coupon']['amount'] >= $subTotal) {
                return array(
                    'alert' => 'info',
                    'message' => 'Total of '.$brandName.' watch(es) must be at least $'.number_format($coupon['Coupon']['amount'],2,'.',',').' in order to use this coupon.',
                );
            }
        }

        // Minimum order not met
        if ($coupon['Coupon']['minimum_order'] && (float)$coupon['Coupon']['minimum_order'] > $subTotal) {
            return array(
                'alert' => 'info',
                'message' => 'You have not met the minimum order of $'.number_format($coupon['Coupon']['minimum_order'],2,'.',',').'.',
            );
        }
        
        // Coupon more than total order amount
        if (strcasecmp($coupon['Coupon']['type'], 'fixed')==0 && $coupon['Coupon']['amount'] >= $subTotal + $shipping) {
            return array(
                'alert' => 'info',
                'message' => 'Order total must be at least $'.number_format($coupon['Coupon']['amount'],2,'.',',').' in order to use this coupon.',
            );
        }
            
        return $coupon;
    }

    /**
     * Make sure coupon code is unique among non-archived coupons
     * return bool
     */
    public function uniqueNotArchived($code) {
        $code = is_array($code) ? current($code) : $code;
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
    public function couponDiscount() {
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

    /**
     * Get the coupon ID based on the code
     */
    public function getCouponId($code) {
        $coupon = $this->find('first', array(
            'conditions' => array(
                'code' => $code,
                'archived' => 0,
            )
        ));
        return $coupon['Coupon']['id'];
    }

    /**
     * Check if any coupons are available to redeem
     * @return bool
     */
    public function couponsAvailable() {
        $coupons = $this->find('count', array(
            'conditions' => array(
                'archived' => 0,
                'OR' => array(
                    'expire_date' => NULL,
                    'expire_date >=' => date('Y-m-d'),
                ),
                'available >' =>  0,
            ),
            'recursive' => -1,
        ));
        
        if ($coupons > 0) {
            return true;
        }
        return false;
    }
}
