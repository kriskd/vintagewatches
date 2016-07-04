<?php
App::uses('AppController', 'Controller');
/**
 * Shippings Controller
 *
 * @property Shipping $Shipping
 * @property PaginatorComponent $Paginator
 */
class ShippingsController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator');

    public $helpers = array('Shipping');

/**
 * admin_index method
 *
 * @return void
 */
	public function admin_index() {
		$this->Shipping->recursive = 1;
		$this->set('shippings', $this->Paginator->paginate());
	}

/**
 * admin_view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		if (!$this->Shipping->exists($id)) {
			throw new NotFoundException(__('Invalid shipping'));
		}
		$options = array('conditions' => array('Shipping.' . $this->Shipping->primaryKey => $id));
		$this->set('shipping', $this->Shipping->find('first', $options));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->Shipping->create();
			if ($this->Shipping->save($this->request->data)) {
                $this->Session->setFlash(__('The shipping option has been saved.'), 'success');
				return $this->redirect(array('action' => 'index'));
			}
		}
		$zones = $this->Shipping->Zone->find('list');
		$items = $this->Shipping->Item->find('list');
		$this->set(compact('zones', 'items'));
	}

/**
 * admin_edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_edit($id = null) {
		if (!$this->Shipping->exists($id)) {
			throw new NotFoundException(__('Invalid shipping'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Shipping->save($this->request->data)) {
                $this->Session->setFlash(__('The shipping option has been saved.'), 'success');
				return $this->redirect(array('action' => 'index'));
			}
		} else {
			$options = array('conditions' => array('Shipping.' . $this->Shipping->primaryKey => $id));
			$this->request->data = $this->Shipping->find('first', $options);
		}
		$zones = $this->Shipping->Zone->find('list');
		$items = $this->Shipping->Item->find('list');
		$this->set(compact('zones', 'items'));
	}

/**
 * admin_delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_delete($id = null) {
		$this->Shipping->id = $id;
		if (!$this->Shipping->exists()) {
			throw new NotFoundException(__('Invalid shipping'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Shipping->delete()) {
			return $this->flash(__('The shipping has been deleted.'), array('action' => 'index'));
		} else {
			return $this->flash(__('The shipping could not be deleted. Please, try again.'), array('action' => 'index'));
		}
	}
}
