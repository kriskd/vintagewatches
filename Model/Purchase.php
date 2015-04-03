<?php
App::uses('AppModel', 'Model');
/**
 * Purchase Model
 *
 * @property Watch $Watch
 * @property Source $Source
 */
class Purchase extends AppModel {

/**
 * Use database config
 *
 * @var string
 */
	public $useDbConfig = 'development';

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'cost' => array(
			'decimal' => array(
				'rule' => array('decimal'),
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
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Watch' => array(
			'className' => 'Watch',
			'foreignKey' => 'watch_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Source' => array(
			'className' => 'Source',
			'foreignKey' => 'source_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
