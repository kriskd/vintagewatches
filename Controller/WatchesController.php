<?php
App::uses('AppController', 'Controller');
/**
 * Watches Controller
 *
 * @property Watch $Watch
 */
class WatchesController extends AppController {
	
	public $paginate = array('limit' => 10,
				 'conditions' => array('active' => 1));
    
    public $uses = array('Watch', 'Image');

/**
 * index method
 *
 * @return void
 */
	public function index() {
        //Get only active and unsold watches
        $this->paginate['Watch'] = array(
                            'limit' => 10,
                            'contain' => 'Image',
                            'conditions' => $this->Watch->getWatchesConditions(1, 0)
                        );

		$this->set('watches', $this->paginate());
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
        $paginate = array('limit' => 10,
					'order' => array('Watch.id' => 'desc'));

        $active = isset($this->params['named']['active']) ? (int)$this->params['named']['active'] : null;
        $sold = isset($this->params['named']['sold']) ? (int)$this->params['named']['sold'] : null;

        $paginate['conditions'] = $this->Watch->getWatchesConditions($active, $sold); 
		$this->paginate = $paginate; 
		
		$this->set('watches', $this->paginate()); 
        

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
				$this->Session->setFlash(__('The watch has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The watch could not be saved. Please, try again.'));
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
			if ($this->Watch->save($this->request->data)) {
				$this->Session->setFlash(__('The watch has been saved'));
				$this->redirect(array('action' => 'index'));
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
