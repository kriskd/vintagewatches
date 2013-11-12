<?php
App::uses('AppController', 'Controller');
/**
 * Brands Controller
 *
 * @property Brand $Brand
 * @property PaginatorComponent $Paginator
 */
class BrandsController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $paginate = array(
				'order' => 'name'
			);

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->Brand->recursive = 0;
		$this->set('brands', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Brand->exists($id)) {
			throw new NotFoundException(__('Invalid brand'));
		}
		$options = array('conditions' => array('Brand.' . $this->Brand->primaryKey => $id));
		$this->set('brand', $this->Brand->find('first', $options));
	}

/**
 * admin_index method
 *
 * @return void
 */
	public function admin_index() {
		if ($this->request->is('post')) { 
			$data = $this->request->data; 
			$newBrand['Brand']['name'] = array_pop($data['Brand']); 
			if (!empty($newBrand['Brand']['name'])) { 
				$this->Brand->create(); 
				if ($this->Brand->save($newBrand)) {
					$this->Session->setFlash(__('The brand has been saved.'), 'success');
				} else {
					$this->Session->setFlash(__('The brand could not be saved. Please, try again.'), 'danger');
				}
			}
			
            if (!empty($data['Brand'])) {
                foreach($data['Brand'] as $id => $item ) {
                    $saveMany[$id] = array('id' => $id, 'name' => $item['name']);
                }
                
                $this->Brand->saveMany($saveMany);
            }
		}
        $brands = $this->Brand->find('list', array(
                            'recursive' => -1,
                            'order' => 'name'
                        )
                    ); 
		$this->set('brands', $brands);
	}
}