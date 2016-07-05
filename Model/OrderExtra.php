<?php
App::uses('AppModel', 'Model');
/**
 * OrderExtra Model
 *
 * @property Order $Order
 * @property Item $Item
 */
class OrderExtra extends AppModel {

/**
 * Use database config
 *
 * @var string
 */
	public $useDbConfig = 'development';

/**
 * Use table
 *
 * @var mixed False or table name
 */
	public $useTable = 'order_extra';


	// The Associations below have been created with all possible keys, those that are not needed can be removed

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
		'Item' => array(
			'className' => 'Item',
			'foreignKey' => 'item_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
