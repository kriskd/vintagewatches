<?php
App::uses('AppController', 'Controller');
/**
 * Vacations Controller
 *
 * @property Vacation $Vacation
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 */
class VacationsController extends AppController {

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
		$this->Vacation->recursive = 0;
		$this->set('vacations', $this->Paginator->paginate());
	}

/**
 * admin_view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		if (!$this->Vacation->exists($id)) {
			throw new NotFoundException(__('Invalid vacation'));
		}
		$options = array('conditions' => array('Vacation.' . $this->Vacation->primaryKey => $id));
		$this->set('vacation', $this->Vacation->find('first', $options));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->Vacation->create();
			if ($this->Vacation->save($this->request->data)) {
				$this->Session->setFlash(__('The vacation has been saved.'), 'success');
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The vacation could not be saved. Please, try again.'), 'danger');
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
		if (!$this->Vacation->exists($id)) {
			throw new NotFoundException(__('Invalid vacation'));
		}
		if ($this->request->is(array('post', 'put'))) {
			$this->request->data['Vacation']['id'] = $id;
			if ($this->Vacation->save($this->request->data)) {
				$this->Session->setFlash(__('The vacation has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The vacation could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Vacation.' . $this->Vacation->primaryKey => $id));
			$this->request->data = $this->Vacation->find('first', $options);
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
		$this->Vacation->id = $id;
		if (!$this->Vacation->exists()) {
			throw new NotFoundException(__('Invalid vacation'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Vacation->delete()) {
			$this->Session->setFlash(__('The vacation has been deleted.'));
		} else {
			$this->Session->setFlash(__('The vacation could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}
