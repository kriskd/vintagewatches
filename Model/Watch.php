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
                'brand_id' => array(
			'notempty' => array(
			    'rule' => array('notempty'),
                            'allowEmpty' => false,
			    'message' => 'Please choose a brand.'
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
		),
                'Brand' => array(
                        'className' => 'Brand',
                        'foreignKey' => 'brand_id'
                )
	);

	public $hasMany = array(
		    'Image' => array(
			'className' => 'Image',
			'foreignKey' => 'watch_id'
		    ),
		);
        
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
	    if($active !== null){
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
        
        public function sellable($id)
        {
            return $this->find('first', array(
                                            'conditions' => array(
                                                'id' => $id,
                                                'order_id' => null,
                                                'active' => 1
                                            ),
                                            'contain' => 'Image'
                                        )
                               );
        }
	
        /**
         * $count Number of watches to return
         * $image If watch image is required to exist
         */
	public function getWatches($count = null, $image = false)
	{
            $options = array('conditions' => array(
                                            'active' => 1
                                        ),
                                        'order' => 'created DESC',
                                        'fields' => array('id', 'price', 'name', 'description'),
                                        'contain' => array('Image', 'Brand')
                                    );
            if ($count != null) {
                $options['limit'] = $count;
            }
            
            if ($image == true) {
                $options['joins'] = array(
                                        array(
                                            'table' => 'images',
                                            'alias' => 'Image',
                                            'type' => 'INNER',
                                            'conditions' => array(
                                                'Image.watch_id = Watch.id'
                                        )
                                    )
                                );
                $options['group'] = array('Watch.id');
            }
            
	    return $this->find('all', $options);
	}
        
        public function storeOpen()
	{
            $watchCount = $this->find('count', array(
                                                    'conditions' => array(
                                                            'active' => 1,
                                                            'order_id' => null
                                                    )
                                                )
                                            );
            
            return $watchCount > 0 ? true : false;
	}
}
