<?php
App::uses('AppModel', 'Model');
/**
 * Content Model
 *
 * @property Page $Page
 */
class Content extends AppModel {
    
    public $displayField = 'name';	public $actsAs = array(
                'HtmlPurifier.HtmlPurifier' => array(
                    'config' => 'MyPurifier',
                    'fields' => array(
                            'value'
                        )
                    )
                );

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'page_id' => array(
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
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Page' => array(
			'className' => 'Page',
			'foreignKey' => 'page_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
