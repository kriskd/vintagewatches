<?php
App::uses('AppModel', 'Model');
/**
 * Consignment Model
 *
 * @property Watch $Watch
 * @property Owner $Owner
 */
class Consignment extends AppModel {

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
        'owner_id' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'allowEmpty' => false,
                'message' => 'Please choose an owner.'
            ),
        ),
		'paid' => array(
			'date' => array(
				'rule' => array('date'),
				'message' => 'Please enter a valid date',
				'allowEmpty' => true,
				'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'returned' => array(
			'date' => array(
				'rule' => array('date'),
				'message' => 'Please enter a valid date',
				'allowEmpty' => true,
				'required' => false,
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
		'Owner' => array(
			'className' => 'Owner',
			'foreignKey' => 'owner_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
