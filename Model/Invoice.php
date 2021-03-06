<?php
App::uses('AppModel', 'Model');
/**
 * Invoice Model
 *
 * @property InvoiceItem $InvoiceItem
 */
class Invoice extends AppModel {

    /**
     * Validation rules
     *
     * @var array
     */
    public $validate = array(
        'email' => array(
            'notBlank' => array(
                'rule' => array('notBlank'),
                'message' => 'Please enter your email.'),
            'email' => array(
                'rule' => array('email'),
                'message' => 'Please supply a valid email address.',
                'required' => false,
                'allowEmpty' => true
            )
        ),
        'slug' => array(
            'alphaNumeric' => array(
                'rule' => array('alphaNumeric'),
                //'message' => 'Your custom message here',
                //'allowEmpty' => false,
                //'required' => false,
                //'last' => false, // Stop validation after this rule
                //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
        'shippingAmount' => array(
            'decimal' => array(
                'rule' => array('decimal'),
                'message' => 'Please enter a valid number.',
                'allowEmpty' => true,
                //'required' => false,
                //'last' => false, // Stop validation after this rule
                //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
            'not_negative' => array(
                'rule'    => array('comparison', '>=', 0),
                'message' => 'This can not be a negative number.',
                //'on' => 'create'
            )
        ),
        'expiration' => array(
            'date' => array(
                'rule' => array('date'),
                'message' => 'Please enter a valid date in the form yyyy-mm-dd',
            ),
            'notBlank' => array(
                'rule' => array('notBlank'),
                'message' => 'Please enter a date.'
            ),
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
        'InvoiceItem' => array(
            'className' => 'InvoiceItem',
            'foreignKey' => 'invoice_id',
            'dependent' => true,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        ),
        'Address' => array(
            'className' => 'Address',
            'foreignKey' => 'foreign_id',
            'dependent' => true, //Delete associated addreseses
            'conditions' => array('Address.class' => 'Invoice'),
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        )
    );

    public $hasOne = array(
        'Payment' => array(
            'className' => 'Payment',
            'foreignKey' => 'foreign_id',
            'conditions' => array('Payment.class' => 'Invoice'),
            'dependent' => true
        )
    );

    public function beforeValidate($options = array())
    {
        if (isset($this->data['Address'])) {
            $addresses = $this->data['Address'];
            $this->data['Address'] = array_map(function($item) {
                $item['class'] = 'Invoice';
                return $item;
            }, $addresses);
        }
        if (isset($this->data['Payment'])) {
            $this->data['Payment']['class'] = 'Invoice';
        }
        return true;
    }

    public function beforeSave($options = array())
    {   
        // Create slug on add only.
        if (!$this->id && !isset($this->data[$this->alias][$this->primaryKey])) {
            $slugChars = 'abcdefghijklmnopqrstuvwxyz0123456789';
            $slug = substr(str_shuffle($slugChars), 0, 32);
            $this->data['Invoice']['slug'] = $slug;
        }
        if (isset($this->data['Invoice']['shippingAmount']) && !is_numeric($this->data['Invoice']['shippingAmount'])) {
            $this->data['Invoice']['shippingAmount'] = 0;
        } 
        if (empty($this->data['InvoiceItem'])) { 
            return false;
        }
        return true;
    }

    public function removeRequiredEmail()
    {
        $this->validator()->remove('email', 'notBlank');
    }
}
