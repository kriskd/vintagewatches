<?php
App::uses('AppModel', 'Model');
/**
 * Source Model
 *
 * @property Watch $Watch
 */
class Source extends AppModel {

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
			'check_delete' => array(
				'rule' => array('checkDelete'),
				'message' => 'This source has watches associated with it and can\'t be deleted.',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				'on' => 'update', // Limit validation to 'create' or 'update' operations
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
		'Purchase' => array(
			'className' => 'Purchase',
			'foreignKey' => 'source_id',
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

    // This threw an error on production
    // Can't use function return value in write context
    /*public function beforeFind($query = array()) {
        parent::beforeFind($query);
        if (empty(current($query['order']))) {
            $query['order'] = 'name';
        }
        return $query;
    }*/

    public function checkDelete($check) {
            if (!empty($check['name'])) {
                return true;
            }
            $data = $this->data;
            $id = $data[$this->alias]['id'];
            $count = $this->Source->Watch->find('count', array(
                'conditions' => array(
                    'source_id' => $id
                )
            )
        );
        if ($count == 0) {
            $this->delete($id);
            return '';
        }
        return false;
    }
}
