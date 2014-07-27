<?php
App::uses('AppModel', 'Model');
/**
 * Order Model
 *
 * @property Address $Address
 * @property Watch $Watch
 */
class Order extends AppModel {
    
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
	    'shipDate' => array(
            'date' => array(
                'rule' => array('date', 'ymd'),
                'message' => 'Enter a valid date in YYYY-MM-DD format.',
                'allowEmpty' => true,
                'required' => false
            )
	    )
    );
    
    public $actsAs = array(
        'HtmlPurifier.HtmlPurifier' => array( 
            'config' => 'StripAll',
            'fields' => array(
            'email', 'phone', 'notes', 'orderNotes',
            )
        )
    );

    /**
     * Remove the order_id on the watch after order is deleted
     */
    public function afterDelete()
    {
        $order_id = $this->id;
        $this->Watch->updateAll(
            array('Watch.order_id' => null),
            array('Watch.order_id' => $order_id)
        );
    }
    
    /**
     * Remove any empty results
     */
    public function afterFind($results, $primary = false) {
        return Hash::filter($results);
    }
    
    public function beforeValidate($options = array())
    {	
        if (isset($this->data['Address'])) {
            $addresses = $this->data['Address'];
            $this->data['Address'] = array_map(function($item) {
                $item['class'] = 'Order';
                return $item;
            }, $addresses);
        }
        if (isset($this->data['Payment'])) {
            $this->data['Payment']['class'] = 'Order';
        }
        return true;
    }

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Address' => array(
			'className' => 'Address',
			'foreignKey' => 'foreign_id',
			'dependent' => true, //Delete associated addreseses
			'conditions' => array('Address.class' => 'Order'),
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
        'Watch' => array(
			'className' => 'Watch',
			'foreignKey' => 'order_id',
			'dependent' => false, //Don't delete associated watches
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
        'Coupon' => array(
            'className' => 'Coupon',
            'foreign_key' => 'coupon_id',
        )
    );
	
	public $hasOne = array(
        'Payment' => array(
                'className' => 'Payment',
                'foreignKey' => 'foreign_id',
                'conditions' => array('Payment.class' => 'Order'),
                'dependent' => true //Delete associated payment
        )
    );

/**
 * hasAndBelongsToMany associations
 *
 * @var array
 */
	public $hasAndBelongsToMany = array(
		'Detect' => array(
			'className' => 'Detect',
			'joinTable' => 'detects_orders',
			'foreignKey' => 'order_id',
			'associationForeignKey' => 'detect_id',
			'unique' => 'keepExisting',
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'finderQuery' => '',
		)
	);
        
    /**
     * $items array Array of Watch objects
     */
    public function getSubTotal($items)
    {
        if(!empty($items)){
            return  array_reduce($items, function($return, $item){ 
                if(isset($item['Watch']['price'])){
                    $return += $item['Watch']['price'];
                    return $return;
                }
            });
        }
        return null;
    }

    public function getOrder($id)
    {
        $options = array('conditions' => array('Order.' . $this->primaryKey => $id));
        
        $options['contain'] = array('Address',
            'Watch' => array(
                'fields' => array('id', 'order_id', 'stockId', 'price', 'name'),
                'Image'
            ),
            'Payment'
        );
        return $this->find('first', $options);
    }
    
    /**
     * Retrieve orders for a given $email and $postalCode
     * Optional $id to get specific order for the matching email & postalCode
     */
    public function getCustomerOrderOptions($email, $postalCode, $id = null)
    {	
        $conditionsSubQuery = array(
            'Address.postalCode' => $postalCode,
            'Address.type' => 'billing'
        );
        $db = $this->Address->getDataSource();
        $subQuery = $db->buildStatement(
            array(
            'fields' => array('Address.foreign_id'),
            'table' => $db->fullTableName($this->Address),
            'alias' => 'Address',
            'conditions' => $conditionsSubQuery
            ),
            $this->Address
        );
        $subQuery = ' Order.id IN (' . $subQuery . ')';
        $subQueryExpression = $db->expression($subQuery);
        $conditions[] = $subQueryExpression;
        $conditions['email'] = urldecode($email);
        
        if (!empty($id)) {
            $conditions['Order.id'] = $id;
        }

        return array('conditions' => $conditions,
            'fields' => array(
                'id', 'email', 'phone', 'shippingAmount', 
                'notes', 'created', 'shipDate'
                ),
            'contain' => array(
                'Address',
                'Watch' => array(
                    'fields' => array('id', 'order_id', 'stockId', 'price', 'name'),
                    'Image'
                ),
                'Payment' => array(
                    'fields' => array(
                        'stripe_amount'
                    )
                )
            ),
            'order' => array(
                'created' => 'DESC'
            )
        );
    }
    
    public function getShippingAmount($country = '')
    {
        switch($country){
            case '':
                return '';
                break;
            case 'us':
                return '8';
                break;
            case 'ca':
                return '38';
                break;
            default:
                return '45';
                break;
        }
    }
}
