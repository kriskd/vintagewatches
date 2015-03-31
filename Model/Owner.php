<?php
App::uses('AppModel', 'Model');
/**
 * Owner Model
 *
 * @property Watch $Watch
 */
class Owner extends AppModel {

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
		'name' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
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
        'Watch'	=> array(
            'className' => 'Watch',
            'foreignKey' => 'foreign_id',
			'dependent' => false,
			'conditions' => array('Watch.class' => 'Owner'),
        )
	);
}
