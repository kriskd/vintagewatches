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
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Address' => array(
			'className' => 'Address',
			'foreignKey' => 'order_id',
			'dependent' => true, //Delete associated addreseses
			'conditions' => '',
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

/**
 * hasAndBelongsToMany associations
 *
 * @var array
 */
	/*public $hasAndBelongsToMany = array(
		'Watch' => array(
			'className' => 'Watch',
			'joinTable' => 'orders_watches',
			'foreignKey' => 'order_id',
			'associationForeignKey' => 'watch_id',
			'unique' => 'keepExisting',
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'finderQuery' => '',
			'deleteQuery' => '',
			'insertQuery' => ''
		)
	);*/
        
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
				    )
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
		'fields' => array('Address.order_id'),
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
				'id', 'email', 'phone', 'shippingAmount', 'stripe_amount',
				'notes', 'created', 'shipDate'
			    ),
			'contain' => array(
			    'Address',
			    'Watch' => array(
				'fields' => array('id', 'order_id', 'stockId', 'price', 'name'),
				'Image'
			    )
			),
			'order' => array(
			    'created' => 'DESC'
			)
		    );
    }
    
    public function getShippingAmount($country = null)
    {
	switch($country){
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
