<?php
App::uses('AppModel', 'Model');
App::uses('Folder', 'Utility');

/**
 * Watch Model
 *
 */
class Watch extends AppModel {

    /**
     * Display field
     *
     * @var string
     */
    public $displayField = 'name';

    public $actsAs = array(
        'HtmlPurifier.HtmlPurifier' => array(
            'config' => 'MyPurifier',
            'fields' => array(
                'description'
            )
        ),
		'Sitemap.Sitemap' => array(
			'primaryKey' => 'id', // Default primary key field
			'loc' => 'buildUrl', // Default function called that builds a url, passes parameters (Model $Model, $primaryKey)
			'lastmod' => 'modified', // Default last modified field, can be set to FALSE if no field for this
			'changefreq' => 'daily', // Default change frequency applied to all model items of this type, can be set to FALSE to pass no value
			'priority' => '0.9', // Default priority applied to all model items of this type, can be set to FALSE to pass no value
			'conditions' => array('Watch.active' => 1), // Conditions to limit or control the returned results for the sitemap
		)
    );

    /**
     * Validation rules
     *
     * @var array
     */
    public $validate = array(
        'stockId' => array(
            'numeric' => array(
                'rule' => array('numeric'),
                'message' => 'Please enter a numeric Stock ID.',
                //'allowEmpty' => false,
                //'required' => false,
                //'last' => false, // Stop validation after this rule
                //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
        'brand_id' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'allowEmpty' => false,
                'message' => 'Please choose a brand.'
            ),
        ),
        'price' => array(
            'money' => array(
                'rule' => array('money'),
                'message' => 'Please enter a valid price.',
                //'allowEmpty' => false,
                //'required' => false,
                //'last' => false, // Stop validation after this rule
                //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
        'name' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'Please enter a watch name.'
            ),
        ),
        'active' => array(
            'boolean' => array(
                'rule' => array('boolean'),
                //'message' => 'Your custom message here',
                //'allowEmpty' => false,
                //'required' => false,
                //'last' => false, // Stop validation after this rule
                //'on' => 'create', // Limit validation to 'create' or 'update' operations
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

    /**
     * belongsTo associations
     *
     * @var array
     */
    public $belongsTo = array(
        'Order' => array(
            'className' => 'Order',
            'foreignKey' => 'order_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'Brand' => array(
            'className' => 'Brand',
            'foreignKey' => 'brand_id'
        ),
    );

    public $hasMany = array(
        'Image' => array(
            'className' => 'Image',
            'foreignKey' => 'watch_id',
            'dependent' => true, //Delete associated images
        ),
    );

	public $hasOne = array(
        'Consignment' => array(
            'className' => 'Consignment',
            'foreignKey' => 'watch_id',
            'dependent' => true //Delete associated consignment
        ),
        'Purchase' => array(
            'className' => 'Purchase',
            'foreignKey' => 'watch_id',
            'dependent' => true //Delete associated purchase
        ),
    );

    public function beforeValidate($options = array()) {
        if (isset($this->data[$this->alias]['type'])) {
            $type = $this->data[$this->alias]['type'];
            if (in_array($type, ['', 'purchase'])) {
                unset($this->data['Consignment']);
            }
            if (in_array($type, ['', 'consignment'])) {
                unset($this->data['Purchase']);
            }
        }
        return true;
    }

    /**
     * Delete associated consignment or purchase based on if watch is consignment, purchase or none.
     */
    public function afterSave($created, $options = array()) {
        if (isset($this->data[$this->alias]['type'])) {
            $type = $this->data[$this->alias]['type'];
            if (in_array($type, ['', 'purchase'])) {
                $this->Consignment->deleteAll(['watch_id' => $this->data[$this->alias]['id']]);
            }
            if (in_array($type, ['', 'consignment'])) {
                $this->Purchase->deleteAll(['watch_id' => $this->data[$this->alias]['id']]);
            }
        }
    }

    /**
     * Add watch type to data to select radio on watch form
     * Probably need validation on Consignment and Purchase requiring owner_id or source_id
     */
    public function afterFind($results, $primary = false) {
        foreach ($results as $key => $result) {
            if (isset($result[$this->alias])) {
                if (!empty($result['Consignment']['owner_id']) && empty($result['Purchase']['source_id'])) {
                    $results[$key][$this->alias]['type'] = 'consignment';
                }
                if (!empty($result['Purchase']['source_id']) && empty($result['Consignment']['owner_id'])) {
                    $results[$key][$this->alias]['type'] = 'purchase';
                }
            }
        }
        return $results;
    }

    public function beforeDelete($cascade = true) {
        $watch = $this->find('first', [
            'conditions' => [
                $this->alias.'.id' => $this->id
            ],
            'recursive' => -1,
        ]);

        if (empty($watch['Watch']['order_id'])) {
            return true;
        }

        return false;
    }

    public function afterDelete() {
        $folder = new Folder(WWW_ROOT.'files/'.$this->id);
        $folder->delete();
    }

    /**
     * Get watch for display.  Will return watch if active and no order
     * or watch belongs to a customer.
     * If params include $email and $postalCode, include those in find contain
     * @param $id ID of the watch
     * @param $email string Customer's email
     * @param $postalCode int Customer's billing postal code
     */
    public function getWatch($id, $email = '', $postalCode = '') {
        $contain = [
            'Image',
            'Brand',
        ];
        $fields = [
            'Watch.id', 'order_id', 'brand_id', 'stockId', 'price', 'Watch.name', 'description', 'active',
            'Brand.name',
        ];
        if (!empty($email) && !empty($postalCode)) {
            $contain['Order'] = [
                'Address' => [
                    'conditions' => [
                        'type' => 'billing'
                    ]
                ]
            ];
            $fields[] = 'Order.email'; 
        }
        $watch = $this->find('first', array(
            'conditions' => [
				'Watch.id' => $id,
            ],
            'contain' => $contain,
            'fields' => $fields,
        ));

        if (!$watch) return false;

        if ($watch['Watch']['active'] == 1 && !$watch['Watch']['order_id']) return $watch;

        if (isset($watch['Order']['email']) && $watch['Order']['email'] == $email && isset($watch['Order']['Address']) && $watch['Order']['Address'][0]['postalCode'] == $postalCode) return $watch;

        return false;
    }

    /**
     * $ids array Array of watch Ids
     */
    public function getCartWatches($ids)
    {
        return $this->find('all', array(
            'conditions' => array('Watch.id' => $ids),
            'fields' => array('id', 'brand_id', 'stockId', 'price', 'name', 'active'),
            'contain' => array(
                'Brand' => array('fields' => 'name'),
                'Image'
            )
        )
    );
    }

    public function getWatchesConditions($active = null, $sold = null)
    {   
        $conditions = array();
        if($active !== null){
            $conditions['Watch.active'] = $active;
        }

        if($sold !== null){
            if($sold === 1){
                $conditions['NOT']['order_id'] = null;
            }
            if($sold === 0){ 
                $conditions['order_id'] = null;
            }
        }

        return $conditions;
    }

    /**
     * Returns the watch only if active and is not on an order
     */
    public function sellable($id)
    {
        return $this->find('first', array(
            'conditions' => array(
                'id' => $id,
                'order_id' => null,
                'active' => 1
            ),
            'contain' => 'Image'
        )
    );
    }

    /**
     * Is watch part of an order
     * @return bool
     */
    public function hasOrder($id) {
        $options = array(
            'conditions' => array(
                'Watch.' . $this->primaryKey => $id,
            ),
            'contain' => array(
                'Order'	
            )
        );
        return (bool) $this->find('first', $options);
    }

    /**
     * $count Number of watches to return
     * $image If watch image is required to exist
     */
    public function getWatches($count = null, $image = false)
    {
        $options = array('conditions' => array(
            'active' => 1
        ),
        'order' => 'created DESC',
        'fields' => array('id', 'stockId', 'price', 'name', 'description', 'modified'),
        'contain' => array(
            'Image',
            'Brand' => array(
                'name'
            )
        )
    );
        if ($count != null) {
            $options['limit'] = $count;
        }

        if ($image == true) {
            $options['joins'] = array(
                array(
                    'table' => 'images',
                    'alias' => 'Image',
                    'type' => 'INNER',
                    'conditions' => array(
                        'Image.watch_id = Watch.id'
                    )
                )
            );
            $options['group'] = array('Watch.id');
        }

        return $this->find('all', $options);
    }

    public function storeOpen()
    {
        $watchCount = $this->find('count', array(
            'conditions' => array(
                'active' => 1,
                'order_id' => null
            ),
            'recursive' => -1
        )
    );

        return $watchCount > 0 ? true : false;
    }

    /**
     * Returns primary image
     * Repeated in helper, need to find better way
     */
    public function imagePrimaryUrl($watch)
    {   
        if (!empty($watch['Image'])) {
            $images = $watch['Image'];
            $primary = array_reduce($images, function($primaryExists, $item){
                if ($item['primary'] == 1) {
                    $primaryExists = $item;
                }
                return $primaryExists;
            }, null);
            $image = empty($primary) ? current($images) : $primary;
            return Router::url($image['filename'], true);
        }
        return null;
    }

}
