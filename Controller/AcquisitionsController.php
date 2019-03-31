<?php
App::uses('AppController', 'Controller');
/**
 * Acquisitions Controller
 *
 * @property Acquisition $Acquisition
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 */
class AcquisitionsController extends AppController {

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
		$this->Acquisition->recursive = 0;
		$this->set('acquisitions', $this->Paginator->paginate());
	}

/**
 * admin_view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		if (!$this->Acquisition->exists($id)) {
			throw new NotFoundException(__('Invalid acquisition'));
		}
		$options = array('conditions' => array('Acquisition.' . $this->Acquisition->primaryKey => $id));
		$this->set('acquisition', $this->Acquisition->find('first', $options));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->Acquisition->create();
			if ($this->Acquisition->save($this->request->data)) {
				$this->Flash->success(__('The acquisition has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->danger(__('The acquisition could not be saved. Please, try again.'));
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
		if (!$this->Acquisition->exists($id)) {
			throw new NotFoundException(__('Invalid acquisition'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Acquisition->save($this->request->data)) {
				$this->Flash->success(__('The acquisition has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->danger(__('The acquisition could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Acquisition.' . $this->Acquisition->primaryKey => $id));
			$this->request->data = $this->Acquisition->find('first', $options);
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
		$this->Acquisition->id = $id;
		if (!$this->Acquisition->exists()) {
			throw new NotFoundException(__('Invalid acquisition'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Acquisition->delete()) {
			$this->Flash->success(__('The acquisition has been deleted.'));
		} else {
			$this->Flash->danger(__('The acquisition could not be deleted. Please, try again.'));
		}

		return $this->redirect(array('action' => 'index'));
	}
}
