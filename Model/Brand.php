<?php
App::uses('AppModel', 'Model');
/**
 * Brand Model
 *
 * @property Watch $Watch
 */
class Brand extends AppModel {

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
                        'message' => 'This brand has watches associated with it and can\'t be deleted.',
                        //'allowEmpty' => false,
                        //'required' => true,
                        //'last' => false, // Stop validation after this rule
                        'on' => 'update', // Limit validation to 'create' or 'update' operations
                    )
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
			'foreignKey' => 'brand_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
        ),
        'Coupon' => array(
            'className' => 'Coupon',
            'foreignKey' => 'brand_id',
            'dependent' => false,
        ),
	);

        public function checkDelete($check)
        {   
            if (!empty($check['name'])) {
                return true;
            }
            $data = $this->data; 
            $id = $data['Brand']['id'];
            $count = $this->Watch->find('count', array(
                                                    'conditions' => array(
                                                                        'brand_id' => $id
                                                                        )
                                                    )
                                        ); 
            if ($count == 0) { 
                $this->delete($id); 
                return '';
            }
            return false;
        }

        /**
         * Return a brand list
         * @param $activeWatches Include only brands with active watches
         *
         * @return array 
         */
        public function brandList($activeWatches = true) {
            if ($activeWatches) {
                $options['joins'] = array(
                            array(
                                'table' => 'watches',
                                'alias' => 'Watch',
                                'type' => 'INNER',
                                'conditions' => array(
                                    'Watch.brand_id = Brand.id',
                                    'Watch.active' => 1,
                                    'Watch.order_id' => null
                            )
                        )
                    );
                $options['group'] = array('Brand.id');
            }
            $options['order'] = array('Brand.name');

            return $this->find('list', $options);
        }
}
