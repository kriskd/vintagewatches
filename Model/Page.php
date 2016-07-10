<?php
App::uses('AppModel', 'Model');
/**
 * Page Model
 *
 * @property Content $Content
 */
class Page extends AppModel {

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
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message' => 'Page name is required.',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'slug' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				//'message' => 'Your custom message here',
				'allowEmpty' => false,
				'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
                        'slug' => array(
                                'rule' => array('custom', '/^[a-z0-9\-]+$/'),
                                'message' => 'Only lowercase letters, number and dashes.',
                        ),
                        'unique' => array(
                            'rule' => 'isUnique',
                            'message' => 'This must be a unique value.'
                        )
		),
		'created' => array(
			'datetime' => array(
				'rule' => array('datetime'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'modified' => array(
			'datetime' => array(
				'rule' => array('datetime'),
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
		'Content' => array(
			'className' => 'Content',
			'foreignKey' => 'page_id',
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
	
	/**
	 * If marking a page as homepage content, set homepage to 0 for all other pages.
	 */
	public function beforeSave($options = array())
	{
        if (isset($this->data[$this->alias]['homepage']) && $this->data[$this->alias]['homepage']==1) {
            $this->updateAll(
                array('homepage' => 0)
            );
        }
    }

    public function getNavigation()
    {
        return $this->find('all', array(
                'fields' => array('slug', 'name', 'modified'),
                'recursive' => -1,
                'conditions' => array('active' => 1),
                'order' => array('homepage' => 'DESC', 'name' => 'ASC'),
            )
        );
    }

}
