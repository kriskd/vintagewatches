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
	
	public $components = array('ImageUploader');
    
    public $uses = array('Watch', 'Image', 'Order', 'OrdersWatch');

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->Watch->recursive = 0;
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

        if(isset($this->params['named']['sold'])){ 
            $soldIds = $this->OrdersWatch->find('list', array('fields' => 'watch_id'));
            if($this->params['named']['sold'] === '1'){
                $paginate['conditions'] = array('Watch.id' => $soldIds);
            }
            if($this->params['named']['sold'] === '0'){
                $paginate['conditions'] = array('NOT' => array('Watch.id' => $soldIds));
            }
        }
        
        if(isset($this->params['named']['active'])){
            $paginate['conditions'] = array('Watch.active' => $this->params['named']['active']);
        }
        
		$this->paginate = $paginate; 
		//$this->Watch->recursive = 2;
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
	
	public function admin_picture($id = null)
	{
        if ($this->request->is('post') || $this->request->is('put')) {
            App::uses('Sanitize', 'Utility');
            $data = Sanitize::clean($this->request->data);
            $image = $data[$this->modelClass]['image'];
            $imagePath = WWW_ROOT . 'files/';
            $thumbPath = $imagePath . 'thumbs/';
            
            $options = array('thumbnail' =>
                            array('max_width' => 180,
                                  'max_height' => 100,
                                  'path' => $thumbPath,
                                  ),
                            'max_width' => 700,
            );
            
            try {
                $output = $this->ImageUploader->upload($image, $imagePath, $options);
                $fileName = substr($output['file_path'], strrpos($output['file_path'], '/')+1, strlen($output['file_path']));
                $thumbFileName = substr($output['thumb_path'], strrpos($output['thumb_path'], '/')+1, strlen($output['thumb_path']));
                $data = array(array('Image' => array('watch_id' => $id, 'type' => 'image', 'filename' => $fileName)),
                              array('Image' => array('watch_id' => $id, 'type' => 'thumb', 'filename' => $thumbFileName))
                             );
                $this->Image->saveMany($data);
            } catch(Exception $e) {
                $output = array('bool' => FALSE, 'error_message' => $e->getMessage());
            }
        }
        $this->redirect(array('action' => 'edit', $id, 'admin' => true));
	}
}
