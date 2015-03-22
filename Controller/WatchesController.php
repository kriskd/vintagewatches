<?php
App::uses('AppController', 'Controller');
/**
 * Watches Controller
 *
 * @property Watch $Watch
 */
class WatchesController extends AppController {

	public $paginate = array(
				'limit' => 10,
				'order' => array(
					'Watch.id' => 'desc'
				),
				'contain' => array(
					'Image',
					'Brand' => array(
					    'fields' => array(
						'id', 'name'
					    )
					)
				)
			);

	public function beforeFilter() {
		$storeOpen = $this->Watch->storeOpen(); 
		//Redirect if store is closed and going to a non-watch order page
		if ($storeOpen == false && empty($this->request->params['admin'])) {
			$this->redirect(array('controller' => 'pages', 'action' => 'home', 'admin' => false));
		}
		parent::beforeFilter();
	}

/**
 * index method
 *
 * @return void
 */
	public function index($brand_slug = null) { 
		$this->paginate['paramType'] = 'querystring'; 

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
		try {
            $this->Paginator->settings = $this->paginate;
			$this->paginate();
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

		$this->set('watches', $this->Paginator->paginate('Watch'));
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
    public function order($id) {
        if (empty($id)) {
			$this->redirect(array('controller' => 'pages', 'action' => 'home', 'display'));
        }

        $email = $this->Session->check('Watch.Order.email') ? $this->Session->read('Watch.Order.email') : '';
        $postalCode = $this->Session->check('Watch.Address.postalCode') ? $this->Session->read('Watch.Address.postalCode') : ''; 

        if (empty($email) || empty($postalCode)) {
            $this->Session->setFlash('Please enter your email and billing postal code to view your orders.', 'info');
			$this->redirect(array('controller' => 'orders'));
        }

        $watch = $this->Watch->getWatch($id, $email, $postalCode);

        if (!$watch) {
            $this->Session->setFlash('This watch was not found on any of your orders.', 'info');
			$this->redirect(array('controller' => 'orders'));
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
		$this->paginate['paramType'] = 'querystring';

		//Send to the view as they are received for comparison against buttons array to set class to active
		$this->set(array('active' => $this->params->query('active'), 'sold' => $this->params->query('sold')));

		//Cast '00' into integer 0
		//Cake make the query param 0 into nothing so use '00' to get a valid param that gets passed
		$active = isset($this->params->query['active']) ? (int)$this->params->query['active'] : null;
		$sold = isset($this->params->query['sold']) ? (int)$this->params->query['sold'] : null;

		$brand_id = empty($this->params->query['brand_id']) ? '' : $this->params->query['brand_id'];
		$source_id = empty($this->params->query['source_id']) ? '' : $this->params->query['source_id'];
		$acquisition_id = empty($this->params->query['acquisition_id']) ? '' : $this->params->query['acquisition_id'];
        if ($brand_id) {
            $this->paginate['conditions']['brand_id'] = $brand_id;
        }
        if ($source_id) {
            $this->paginate['conditions']['source_id'] = $source_id;
        }
        if ($acquisition_id) {
            $this->paginate['conditions']['acquisition_id'] = $acquisition_id;
        }

		$this->paginate['conditions'][] = $this->Watch->getWatchesConditions($active, $sold);
		$this->paginate['fields'] = array('id', 'order_id', 'stockId', 'price', 'name', 'active', 'created', 'modified');

		$this->Paginator->settings = $this->paginate;

		$brands = $this->Watch->Brand->find('list', array('order' => 'name'));
        $acquisitions = $this->Watch->Acquisition->find('list');
        $sources = $this->Watch->Source->find('list');

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
			$this->redirect(array_merge(Router::parse($this->here), array('?' => $query)));
		}

		$this->set(compact('watches', 'buttons', 'brands', 'brand_id', 'acquisitions', 'acquisition_id', 'sources', 'source_id'));
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
				'Image',
				'Brand' => array(
				    'fields' => array(
                        'id', 'name'
				    )
                ),
                'Source', 'Acquisition'
			)
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
			if ($this->Watch->save($this->request->data)) {
				$this->Session->setFlash('Watch ' . $this->Watch->getInsertID() . ' has been created', 'success');
				$this->redirect(array('action' => 'edit', $this->Watch->getInsertID(), 'admin' => true));
			} else {
				$this->Session->setFlash(__('The watch could not be saved. Please, try again.'), 'danger');
			}
		}
        $this->set([
            'brands' => $this->Watch->Brand->brandList(false), 
            'acquisitions' => $this->Watch->Acquisition->find('list'),
            'sources' => $this->Watch->Source->find('list'),
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
			throw new NotFoundException(__('Invalid watch'));
		}
        $this->set([
            'brands' => $this->Watch->Brand->brandList(false), 
            'acquisitions' => $this->Watch->Acquisition->find('list'),
            'sources' => $this->Watch->Source->find('list'),
        ]);
        $options = array(
            'conditions' => array(
                'Watch.' . $this->Watch->primaryKey => $id,
            ),
            'contain' => array(
                'Image',
                'Brand' => array(
                    'fields' => array(
                        'id', 'name'
                    )
                ),
            )
        );
        $watch = $this->Watch->find('first', $options);
        $sold = !empty($watch['Watch']['order_id']);
        $fieldList = ['acquisition_id', 'source_id', 'cost', 'notes', 'repair_date', 'repair_cost', 'repair_notes'];
        if (!$sold) {
            $fieldList = array_merge($fieldList, ['name', 'stockId', 'brand_id', 'price', 'description', 'active']);
        }
		if ($this->request->is('post') || $this->request->is('put')) {
			$this->request->data['Watch']['id'] = $id;
			if ($this->Watch->save($this->request->data, ['fieldList' => $fieldList])) {
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
}
