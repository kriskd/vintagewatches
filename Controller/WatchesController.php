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
				'contain' => array('Image')
			);
    
	public $uses = array('Watch', 'Image');

/**
 * index method
 *
 * @return void
 */
	public function index() {
		//Get only active and unsold watches
		$this->paginate['conditions'] = $this->Watch->getWatchesConditions(1, 0);
		$this->paginate['fields'] = array('id', 'stockId', 'price', 'name', 'description');
		$this->Paginator->settings = $this->paginate;
		
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
		if (!$this->Watch->exists($id)) {
			throw new NotFoundException(__('Invalid watch'));
		}
		$options = array('conditions' => array('Watch.' . $this->Watch->primaryKey => $id));
		$this->set('watch', $this->Watch->find('first', $options));
	}

/**
 * admin_index method
 *
 * @return void
 */
	public function admin_index()
	{	
		$active = isset($this->params['named']['active']) ? (int)$this->params['named']['active'] : null;
		$sold = isset($this->params['named']['sold']) ? (int)$this->params['named']['sold'] : null;
		
		$this->paginate['conditions'] = $this->Watch->getWatchesConditions($active, $sold);
		$this->paginate['fields'] = array('id', 'stockId', 'price', 'name', 'description', 'created', 'modified');
		$this->paginate['contain'][] = 'Order';
		$this->Paginator->settings = $this->paginate;
		
		$buttons = array(
			'All Watches' => array('active' => null, 'sold' => null),
			'Sold Watches' => array('active' => null, 'sold' => 1),
			'Unsold Watches' => array('active' => null, 'sold' => 0),
			'Active Watches' => array('active' => 1, 'sold' => null),
			'Inactive Watches' => array('active' => 0, 'sold' => null)
		);
		
		$this->set(array('watches' => $this->paginate()) + compact('active', 'sold', 'buttons')); 
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
		$options = array('conditions' => array('Watch.' . $this->Watch->primaryKey => $id));
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
			$options = array('conditions' => array('Watch.' . $this->Watch->primaryKey => $id));
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
			$this->Session->setFlash(__('Watch deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Watch was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
}
