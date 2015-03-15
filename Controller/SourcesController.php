<?php
App::uses('AppController', 'Controller');
/**
 * Sources Controller
 *
 * @property Source $Source
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 */
class SourcesController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator', 'Session');

/**
 * admin_index method
 *
 * @return void
 */
	public function admin_index() {
		$this->Source->recursive = 0;
		$this->set('sources', $this->Paginator->paginate());
	}

/**
 * admin_view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		if (!$this->Source->exists($id)) {
			throw new NotFoundException(__('Invalid source'));
		}
		$options = array('conditions' => array('Source.' . $this->Source->primaryKey => $id));
		$this->set('source', $this->Source->find('first', $options));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->Source->create();
			if ($this->Source->save($this->request->data)) {
				$this->Session->setFlash(__('The source has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The source could not be saved. Please, try again.'));
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
		if (!$this->Source->exists($id)) {
			throw new NotFoundException(__('Invalid source'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Source->save($this->request->data)) {
				$this->Session->setFlash(__('The source has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The source could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Source.' . $this->Source->primaryKey => $id));
			$this->request->data = $this->Source->find('first', $options);
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
		$this->Source->id = $id;
		if (!$this->Source->exists()) {
			throw new NotFoundException(__('Invalid source'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Source->delete()) {
			$this->Session->setFlash(__('The source has been deleted.'));
		} else {
			$this->Session->setFlash(__('The source could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}
