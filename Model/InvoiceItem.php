<?php
App::uses('AppModel', 'Model');
/**
 * InvoiceItem Model
 *
 * @property Invoice $Invoice
 */
class InvoiceItem extends AppModel {

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'invoice_id' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'description' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
				'message' => 'Please enter the item adescription.',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'amount' => array(
			'decimal' => array(
				'rule' => array('decimal'),
				'message' => 'Please enter a valid number.',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
                        'not_negative' => array(
                                'rule'    => array('comparison', '>=', 0),
                                'message' => 'Must be a positive number.'
                        )
		),
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Invoice' => array(
			'className' => 'Invoice',
			'foreignKey' => 'invoice_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
        
        /*public function beforeValidate($options = array())
        {   var_dump($this->data); exit;
            if (!$this->id && !isset($this->data[$this->alias][$this->primaryKey])) {
                // insert
            } else {
                // edit
            }
            return true;
        }*/
        
        /**
         * Don't delete line item if it's the only one.
         */
        public function beforeDelete($cascade = true)
        {
            $invoice_id = $this->invoice_id;
            $count = $this->find('count', array('conditions' => compact('invoice_id')));
            if ($count < 2) {
                return false;
            }
            return true;
        }
}
