<?php
App::uses('AppModel', 'Model');
/**
 * OrdersWatch Model
 *
 * @property Order $Order
 * @property Watch $Watch
 */
class OrdersWatch extends AppModel {

	//The Associations below have been created with all possible keys, those that are not needed can be removed

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
		'Watch' => array(
			'className' => 'Watch',
			'foreignKey' => 'watch_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
