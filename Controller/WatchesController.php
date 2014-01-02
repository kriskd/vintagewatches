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
    
	public $uses = array('Watch', 'Image');
	
	public function beforeFilter()
	{
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
		$this->Paginator->settings = $this->paginate;
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
		if (!$this->Watch->sellable($id)) {
			$this->redirect(array('controller' => 'pages', 'action' => 'home', 'display'));
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
					)
				)
			);
		$watch = $this->Watch->find('first', $options);
		$this->set('watch', $watch);
		
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
	public function admin_index()
	{   
		$this->paginate['paramType'] = 'querystring';
		
		//Send to the view as they are received for comparison against buttons array to set class to active
		$this->set(array('active' => $this->params->query('active'), 'sold' => $this->params->query('sold')));
		
		//Cast '00' into integer 0
		//Cake make the query param 0 into nothing so use '00' to get a valid param that gets passed
		$active = isset($this->params->query['active']) ? (int)$this->params->query['active'] : null;
		$sold = isset($this->params->query['sold']) ? (int)$this->params->query['sold'] : null;
		
		$brand_id = '';
		if (!empty($this->params->query['id'])) { 
		    $brand_id = $this->params->query['id'];
		    $this->paginate['conditions']['brand_id'] = $brand_id;
		}
		
		$this->paginate['conditions'][] = $this->Watch->getWatchesConditions($active, $sold);
		$this->paginate['fields'] = array('id', 'order_id', 'stockId', 'price', 'name', 'description', 'created', 'modified');
        
		$this->Paginator->settings = $this->paginate;
        
		$brands = array('' => 'Show All') + $this->Watch->Brand->find('list', array('order' => 'name')); 
		
		$buttons = array(
			'All Watches' => array('active' => null, 'sold' => null),
			'Sold Watches' => array('active' => null, 'sold' => 1),
			'Unsold Watches' => array('active' => null, 'sold' => '00'),
			'Active Watches' => array('active' => 1, 'sold' => null),
			'Inactive Watches' => array('active' => '00', 'sold' => null)
		);
        	
		$this->set(array('watches' => $this->paginate()) + compact('buttons', 'brands', 'brand_id')); 
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
				)
			)
		);

		$this->set('watch', $this->Watch->find('first', $options));
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
        $this->brandList();
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
		if ($this->request->is('post') || $this->request->is('put')) {
			$this->request->data['Watch']['id'] = $id; 
			if ($this->Watch->save($this->request->data)) {
				$this->Session->setFlash(__('The watch has been saved'), 'success');
				$this->redirect(array('action' => 'edit', $id));
			} else {
				$this->Session->setFlash(__('The watch could not be saved. Please, try again.'));
			}
		} else {
			$this->brandList();
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
					)
				)
			);
			$this->request->data = $this->Watch->find('first', $options);
			$this->set('watch', $this->Watch->find('first', $options));
		}
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
    
	public function brandList()
	{
	    $brands = array('' => 'Select One') + $this->Watch->Brand->find('list', array('order' => 'Brand.name'));
	    $this->set(compact('brands'));
	}
}
