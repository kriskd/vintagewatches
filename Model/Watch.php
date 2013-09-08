<?php
App::uses('AppModel', 'Model');
/**
 * Watch Model
 *
 */
class Watch extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'name';

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'stockId' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'Please enter a numeric Stock ID.',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'price' => array(
			'money' => array(
				'rule' => array('money'),
				'message' => 'Please enter a valid price.',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'name' => array(
			'notempty' => array(
			    'rule' => array('notempty'),
			    'message' => 'Please enter a watch name.'
			),
		),
		'active' => array(
			'boolean' => array(
				'rule' => array('boolean'),
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
        
/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Order' => array(
			'className' => 'Order',
			'foreignKey' => 'order_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

	public $hasMany = array(
		    'Image' => array(
			'className' => 'Image',
			'foreignKey' => 'watch_id'
		    ),
		    /*'OrdersWatch' => array(
			'className' => 'OrdersWatch',
			'foreignKey' => 'watch_id'
		    )*/
		);
	
/**
 * hasAndBelongsToMany associations
 *
 * @var array
 */
	/*public $hasAndBelongsToMany = array(
		'Order' => array(
			'className' => 'Order',
			'joinTable' => 'orders_watches',
			'foreignKey' => 'watch_id',
			'associationForeignKey' => 'order_id',
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
     * $ids array Array of watch Ids
     */
        public function getCartWatches($ids)
        {
            return $this->find('all', array('conditions' => array('Watch.id' => $ids),
                                                   'fields' => array('id', 'stockId', 'price', 'name')
                                                   )
                                      );
        }
	
	public function getWatchesConditions($active = null, $sold = null)
	{   
	    $conditions = array();
	    if($active != null){
		$conditions['Watch.active'] = $active;
	    }
	    
	    if($sold !== null){
		if($sold === 1){
		    $conditions['NOT']['order_id'] = null;
		}
		if($sold === 0){ 
		    $conditions['order_id'] = null;
		}
	    }
	    
	    return $conditions;
	}
}
