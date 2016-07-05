<?php
App::uses('AppController', 'Controller');
/**
 * Watches Controller
 *
 * @property Watch $Watch
 */
class WatchesController extends AppController {

    public $components = [
        'Cart',
    ];

	public $paginate = array(
				'limit' => 10,
				'order' => 'Watch.created desc',
                'contain' => array(
					'Image',
					'Brand' => array(
					    'fields' => array(
                            'id', 'name'
					    ),
                    ),
                ),
				'paramType' => 'querystring',
			);

	public function beforeFilter() {
		$storeOpen = $this->Watch->storeOpen();
		//Redirect if store is closed and going to a non-watch order page
		if ($storeOpen == false && empty($this->request->params['admin'])) {
			$this->redirect(array('controller' => 'pages', 'action' => 'home', 'admin' => false));
		}
        $acquisitions = array(
            //'' => 'Clear',
            'consignment' => 'Consignment',
            'purchase' => 'Self',
        );
        $owners = $this->Watch->Consignment->Owner->find('list');
        $sources = $this->Watch->Purchase->Source->find('list');
        $this->set(compact('acquisitions', 'owners', 'sources'));
		parent::beforeFilter();
	}

/**
 * index method
 *
 * @return void
 */
	public function index($brand_slug = null) {
		//Get only active and unsold watches
		$this->paginate['conditions'] = $this->Watch->getWatchesConditions(1, 0);
		if (!empty($brand_slug)) {
			$brand = array_filter($this->brandsWithWatches, function($item) use ($brand_slug) {
				return strcasecmp(Inflector::slug($item, '-'), $brand_slug)==0;
			});
			if (is_array($brand)) {
				$brand_id = key($brand);
				$brand = current($brand);
				if ($brand_id = $this->Brand->field('id', array('name' => $brand))) {
					$this->paginate['conditions']['brand_id'] = $brand_id;
					$this->set(compact('brand'));
				}
			}
		}
		$this->paginate['fields'] = array('id', 'stockId', 'price', 'name', 'description');
		if (!empty($this->request->query['sortby'])) {
			$this->request->data['Watch']['sortby'] = $this->request->query['sortby'];
			list($field, $direction) = explode('-', $this->request->query['sortby']);
			$this->paginate['order'] = $field .' ' . $direction;
		}
		try {
            $this->Paginator->settings = $this->paginate;
			$this->set(['watches' => $this->paginate()]);
		} catch (NotFoundException $e) {
			//Redirect to previous page
			$query = $this->request->query;
			$query['page']--;
            extract(Router::parse($this->request->here));
            $pass = empty($pass) ? '' : $pass[0];
            $this->redirect(array_merge(array('action' => $action, $pass), array('?' => $query)));
		}
		$title = empty($brand) ? 'Store' : $brand . ' Watches';
		$this->set('title', $title);
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
        if (empty($id)) {
			$this->redirect(array('controller' => 'pages', 'action' => 'home', 'display'));
        }

        $watch = $this->Watch->getWatch($id);

        if (!$watch) {
            $this->Session->setFlash('This watch is not available.', 'info');
			$this->redirect(array('controller' => 'pages', 'action' => 'home', 'display'));
        }

		$this->set('watch', $watch);
		$this->set(array('title' => $watch['Watch']['name']));
	}

    /**
     * Get a watch on customer's order
     * @param $id int Id of the watch to get
     */
    public function order($id = null) {
        if (empty($id)) {
			return $this->redirect(array('controller' => 'pages', 'action' => 'home', 'display'));
        }

        $email = $this->Session->check('Watch.Order.email') ? $this->Session->read('Watch.Order.email') : '';
        $postalCode = $this->Session->check('Watch.Address.postalCode') ? $this->Session->read('Watch.Address.postalCode') : '';

        if (empty($email) || empty($postalCode)) {
            $this->Session->setFlash('Please enter your email and billing postal code to view your orders.', 'info');
			return $this->redirect(array('controller' => 'orders'));
        }

        $watch = $this->Watch->getWatch($id, $email, $postalCode);

        if (!$watch) {
            $this->Session->setFlash('This watch was not found on any of your orders.', 'info');
			return $this->redirect(array('controller' => 'orders'));
        }

		$this->set('watch', $watch);
        $this->render('view');
		$this->set(array('title' => $watch['Watch']['name']));
    }

    public function xml()
    {
        App::import('Vendor', 'zeroasterisk/CakePHP-ArrayToXml-Lib/libs/array_to_xml');
        $watches = $this->Watch->getWatches();

        foreach($watches as $i => $watch) {
            $xmlArray[$i] = array(
                            'watch' => array(
                                'g:id' => $watch['Watch']['stockId'],
                                'title' => h($watch['Watch']['name']),
                                'description' => h(strip_tags($watch['Watch']['description'])),
                                'g:google_product_category' => h('Apparel & Accessories > Jewelry > Watches > Analog Watches'),
                                'g:brand' => $watch['Brand']['name'],
                                'g:price' => $watch['Watch']['price'] . ' USD',
                            )
                        );
            $image = $this->Watch->imagePrimaryUrl($watch);
            if (!empty($image)) {
                $xmlArray[$i]['watch']['image'] = $image;
            }
        }

        $xmlString = ArrayToXml::simplexml($xmlArray, 'watches');

        $this->set('xml', $xmlString);
        $this->layout = 'xml';
    }

/**
 * admin_index method
 *
 * @return void
 */
	public function admin_index() {
		//Send to the view as they are received for comparison against buttons array to set class to active
		$this->set(array('active' => $this->params->query('active'), 'sold' => $this->params->query('sold')));

		//Cast '00' into integer 0
		//Cake make the query param 0 into nothing so use '00' to get a valid param that gets passed
		$active = isset($this->params->query['active']) ? (int)$this->params->query['active'] : null;
		$sold = isset($this->params->query['sold']) ? (int)$this->params->query['sold'] : null;

		$brand_id = empty($this->params->query['brand_id']) ? '' : $this->params->query['brand_id'];
        $source_id = '';
        $owner_id = '';
        if ($brand_id) {
            $this->paginate['conditions']['brand_id'] = $brand_id;
        }
		$type = empty($this->params->query['type']) ? '' : $this->params->query['type'];
        if ($type == 'purchase' && !empty($this->params->query['source_id'])) {
		    $source_id = $this->params->query['source_id'];
        }
        if ($type == 'consignment' && !empty($this->params->query['owner_id'])) {
            $owner_id = $this->params->query['owner_id'];
        }
        if ($type && in_array($type, ['consignment', 'purchase']) && empty($owner_id) && empty($source_id)) {
            $watchIds = $this->Watch->{ucfirst($type)}->getWatchIds();
        }
        if ($owner_id) {
            $watchIds = $this->Watch->Consignment->getWatchIds('owner_id', $owner_id);
        }
        if ($source_id) {
            $watchIds = $this->Watch->Purchase->getWatchIds('source_id', $source_id);
        }

        if (isset($watchIds) && is_array($watchIds)) {
            $this->paginate['conditions'][] = [
                'Watch.id' => $watchIds,
            ];
        }
		$this->paginate['conditions'][] = $this->Watch->getWatchesConditions($active, $sold);
		$this->paginate['fields'] = array('id', 'order_id', 'stockId', 'price', 'name', 'active', 'created', 'modified');

		$this->Paginator->settings = $this->paginate;

		$brands = $this->Watch->Brand->find('list', array('order' => 'name'));

		$buttons = array(
			'All Watches' => array('active' => null, 'sold' => null),
			'Sold Watches' => array('active' => null, 'sold' => 1),
			'Unsold Watches' => array('active' => null, 'sold' => '00'),
			'Active Watches' => array('active' => 1, 'sold' => null),
			'Inactive Watches' => array('active' => '00', 'sold' => null)
		);

		try {
			$watches = $this->paginate();
		} catch (NotFoundException $e) {
			//Redirect to previous page
			$query = $this->request->query;
			$query['page']--;
			return $this->redirect(array_merge(Router::parse($this->request->here), array('?' => $query)));
		}

		$this->set(compact('watches', 'buttons', 'brands', 'brand_id', 'acquisitions', 'type', 'sources', 'source_id', 'owner_id'));
	}

/**
 * admin_view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		if (!$this->Watch->exists($id)) {
			throw new NotFoundException(__('Invalid watch'));
		}

		$options = array(
			'conditions' => array(
				'Watch.' . $this->Watch->primaryKey => $id
			),
			'contain' => array(
				'Image', 'Order',
				'Brand' => array(
				    'fields' => array(
                        'id', 'name'
				    )
                ),
                'Consignment' => ['Owner'],
                'Purchase' => ['Source'], 
            ),
		);

        $watch = $this->Watch->find('first', $options);
        $sold = !empty($watch['Watch']['order_id']);
        $status = $sold ? 'Sold' : ($watch['Watch']['active'] ? 'For Sale' : 'Inactive');
		$this->set(compact('watch', 'sold', 'status'));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->Watch->create();
			if ($this->Watch->saveAssociated($this->request->data)) {
				$this->Session->setFlash('Watch ' . $this->Watch->getInsertID() . ' has been created', 'success');
				$this->redirect(array('action' => 'edit', $this->Watch->getInsertID(), 'admin' => true));
			} else {
				$this->Session->setFlash(__('The watch could not be saved. Please, try again.'), 'danger');
			}
		}
        $this->set([
            'brands' => $this->Watch->Brand->brandList(false),
        ]);
	}

/**
 * admin_edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_edit($id = null) {
		if (!$this->Watch->exists($id)) {
            $this->Session->setFlash('Invalid Watch', 'danger');
            return $this->redirect(['action' => 'index']);
		}
        $this->set([
            'brands' => $this->Watch->Brand->brandList(false),
        ]);
        $options = array(
            'conditions' => array(
                'Watch.' . $this->Watch->primaryKey => $id,
            ),
            'contain' => array(
                'Image',
                'Consignment' => ['Owner'],
                'Purchase' => ['Source'],
            )
        );
        $watch = $this->Watch->find('first', $options);
        $sold = !empty($watch['Watch']['order_id']);
        $fieldList = [
            'Watch' => [
                'cost', 'notes', 'repair_date', 'repair_cost', 'repair_notes',
            ],
            'Consignment' => [
                'watch_id', 'owner_id', 'paid', 'returned',
            ],
            'Purchase' => [
                'source_id'
            ]
        ];
        if (!$sold) {
            $fieldList = array_merge_recursive($fieldList, ['Watch' => ['name', 'stockId', 'brand_id', 'price', 'description', 'active']]);
        }
		if ($this->request->is('post') || $this->request->is('put')) {
			$this->request->data['Watch']['id'] = $id;
            if ($this->Watch->saveAssociated($this->request->data, ['fieldList' => $fieldList])) {
				$this->Session->setFlash(__('The watch has been saved'), 'success');
        	    return $this->redirect(array('action' => 'view', $id));
			} else {
				$this->Session->setFlash(__('The watch could not be saved. Please, try again.'), 'danger');
			}
		} else {
            $this->request->data = $watch;
        }
        $this->set(compact('watch', 'sold'));
	}

/**
 * admin_delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_delete($id = null) {
		$this->Watch->id = $id;
		if (!$this->Watch->exists()) {
			throw new NotFoundException(__('Invalid watch'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Watch->delete()) {
			$this->Session->setFlash(__('Watch deleted'), 'success');
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Watch was not deleted'), 'danger');
		$this->redirect(array('action' => 'index'));
	}

	public function admin_close()
	{
		$this->Watch->updateAll(array('active' => 0));
		$this->Session->setFlash(__('The store is closed.'), 'success');
		$this->redirect(array('action' => 'index', 'admin' => true));
	}

	public function admin_open()
	{
		$this->Watch->updateAll(array('active' => 1),
					array('order_id' => null));
		$this->Session->setFlash(__('The store is open.'), 'success');
		$this->redirect(array('action' => 'index', 'admin' => true));
	}

	public function admin_active() {
		if($this->request->is('ajax')){
			$data = $this->request->data;
			$active = $data['active'];
			$watchid = $data['watchid'];
			$this->Watch->id = $watchid;
			$this->Watch->saveField('active', $active);
		}
		$this->autoRender = false;
	}

    /**
     * Remove a Watch from the Cart.
     *
     * @param int $id The Id of the Watch
     * @return array
     */
    public function remove($id = null) {
        if (!$this->Watch->exists($id)) {
            throw new NotFoundException(__('Invalid watch'));
        }

        $this->Cart->remove('Watch', $id);

        return $this->redirect(array(
            'controller' => 'orders',
            'action' => 'checkout',
        ));
    }
}
