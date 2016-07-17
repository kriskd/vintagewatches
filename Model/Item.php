<?php
App::uses('AppModel', 'Model');
App::uses('Folder', 'Utility');
App::uses('File', 'Utility');
App::uses('AttachmentBehavior', 'Uploader.Model/Behavior');

/**
 * Item Model
 *
 * @property Shipping $Shipping
 */
class Item extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'description';

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'description' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'quantity' => array(
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

    public $actsAs = array(
        'Uploader.Attachment' => array(
            'filename' => array(
                //'nameCallback' => 'filename',
                'append' => '',
                'prepend' => '',
                'tempDir' => TMP,
                'uploadDir' => 'items',
                'transportDir' => '',
                'finalPath' => 'items',
                'dbColumn' => '',
                'metaColumns' => array(),
                'defaultPath' => '',
                'overwrite' => false,
                'stopSave' => true,
                'allowEmpty' => true,
                'transforms' => array(
                    'filenameLarge' => array(
                        'method' => AttachmentBehavior::FIT,
                        'width' => 700,
                        'height' => 525,
                        'fill' => array(255, 255, 255),
                        'vertical' => 'center',
                        'horizontal' => 'center',
                        'nameCallback' => 'fitName',
                        'append' => '-lg'
                    ),
                    'filenameMedium' => array(
                        'method' => AttachmentBehavior::FIT,
                        'width' => 500,
                        'height' => 375,
                        'fill' => array(255, 255, 255),
                        'vertical' => 'center',
                        'horizontal' => 'center',
                        'nameCallback' => 'fitName',
                        'append' => '-md'
                    ),
                    'filenameThumb' => array(
                        'method' => AttachmentBehavior::FIT,
                        'width' => 100,
                        'height' => 100,
                        'fill' => array(255, 255, 255),
                        'vertical' => 'center',
                        'horizontal' => 'center',
                        'nameCallback' => 'fitName',
                        'append' => '-thumb'
                    )
                ),
                'transport' => array(),
                'curl' => array()
            )
        )
    );

	//The Associations below have been created with all possible keys, those that are not needed can be removed

    /**
     * hasMany associations
     *
     * @var array
     */
	public $hasMany = array(
		'OrderExtra' => array(
			'className' => 'OrderExtra',
			'foreignKey' => 'order_id',
			'dependent' => false, // Don't Delete associated order_extra
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
	);

/**
 * hasAndBelongsToMany associations
 *
 * @var array
 */
	public $hasAndBelongsToMany = array(
		'Shipping' => array(
			'className' => 'Shipping',
			'joinTable' => 'items_shippings',
			'foreignKey' => 'item_id',
			'associationForeignKey' => 'shipping_id',
			'unique' => 'keepExisting',
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'finderQuery' => '',
		)
	);

    public function beforeUpload($options) {
        $options = $this->setUploadDir($options);

        return $options;
    }

    public function beforeTransform($options) {
        $options = $this->setUploadDir($options);
        return $options;
    }

    public function setUploadDir($options) {
        if (!empty($this->data['Item']['id'])) {
            $itemId = $this->data['Item']['id'];
            $options['finalPath'] = DS . 'items' . DS . $itemId . DS;
            $options['uploadDir'] = WWW_ROOT . $options['finalPath'];

            if (!file_exists($options['uploadDir'])) {
                new Folder($options['uploadDir'], true);
            }
        }

        return $options;
    }

    public function fitName($name, $file){
        return $this->getUploadedFile()->name();
    }

    /**
     * Get an array of Items in the Cart with `quantity` of each item.
     *
     * @return array
     */
    public function getCartItems($cartItemIds) {
        $items = $this->find('all', [
            'conditions' => [
                'id' => $cartItemIds,
            ],
            'contain' => [
                'Shipping',
            ],
        ]);
        $counts = array_count_values($cartItemIds);
        foreach ($items as $key => $item) {
            $items[$key]['Item']['ordered'] = $counts[$item['Item']['id']];
            $items[$key]['Item']['subtotal'] = $counts[$item['Item']['id']] * $item['Item']['price'];
        }

        return $items;
    }
}
