<?php
App::uses('AppModel', 'Model');
/**
 * Owner Model
 *
 * @property Consignment $Consignment
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
			'check_delete' => array(
				'rule' => array('checkDelete'),
				'message' => 'This owner has watches associated with it and can\'t be deleted.',
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
		'Consignment' => array(
			'className' => 'Consignment',
			'foreignKey' => 'owner_id',
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

    public function checkDelete($check) {
        if (!empty($check['name'])) {
            return true;
        }
        $data = $this->data;
        $id = $data[$this->alias]['id'];
        $count = $this->Consignment->Watch->find('count', array(
            'conditions' => array(
                'owner_id' => $id
            )
        ));
        if ($count == 0) {
            $this->delete($id);
            return '';
        }
        return false;
    }
}
