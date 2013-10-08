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
		    'message' => 'Enter a valid date in YYY-MM-DD format.',
		    'allowEmpty' => true,
		    'required' => false
		)
	    )
        );

//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Address' => array(
			'className' => 'Address',
			'foreignKey' => 'order_id',
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
                'Watch' => array(
			'className' => 'Watch',
			'foreignKey' => 'order_id',
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
					'Image'
				    )
				);
	return $this->find('first', $options);
    }
}
