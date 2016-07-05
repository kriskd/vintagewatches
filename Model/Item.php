<?php
App::uses('AppModel', 'Model');
/**
 * Item Model
 *
 * @property Shipping $Shipping
 */
class Item extends AppModel {

/**
 * Use database config
 *
 * @var string
 */
	public $useDbConfig = 'development';

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'description';

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'description' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'quantity' => array(
			'numeric' => array(
				'rule' => array('numeric'),
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
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed

    /**
     * hasMany associations
     *
     * @var array
     */
	public $hasMany = array(
		'OrderExtra' => array(
			'className' => 'OrderExtra',
			'foreignKey' => 'order_id',
			'dependent' => false, // Don't Delete associated order_extra
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
	);

/**
 * hasAndBelongsToMany associations
 *
 * @var array
 */
	public $hasAndBelongsToMany = array(
		'Shipping' => array(
			'className' => 'Shipping',
			'joinTable' => 'items_shippings',
			'foreignKey' => 'item_id',
			'associationForeignKey' => 'shipping_id',
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
     * Get an array of Items in the Cart with `quantity` of each item.
     *
     * @return array
     */
    public function getCartItems($cartItemIds) {
        $items = $this->findAllById($cartItemIds);
        $counts = array_count_values($cartItemIds);
        foreach ($items as $key => $item) {
            $items[$key]['Item']['ordered'] = $counts[$item['Item']['id']];
            $items[$key]['Item']['subtotal'] = $counts[$item['Item']['id']] * $item['Item']['price'];
        }

        return $items;
    }
}
