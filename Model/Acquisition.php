<?php
App::uses('AppModel', 'Model');
/**
 * Acquisition Model
 *
 * @property Watch $Watch
 */
class Acquisition extends AppModel {

    public $displayField = 'acquisition';

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'acquisition' => array(
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
		'Watch' => array(
			'className' => 'Watch',
			'foreignKey' => 'acquisition_id',
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

    public function afterFind($results = array(), $primary = false) {
        foreach ($results as &$result) {
            if (isset($result[$this->name]['acquisition'])) {
                $result[$this->name]['acquisition'] = ucwords($result[$this->name]['acquisition']);
            }
        }
        return $results;
    }
}
