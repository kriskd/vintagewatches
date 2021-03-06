<?php
App::uses('AppController', 'Controller');
/**
 * Items Controller
 *
 * @property Item $Item
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 */
class ItemsController extends AppController {

    /**
     * Components
     *
     * @var array
     */
	public $components = array('Paginator', 'Session', 'Cart');

    /**
     * View an Item and add to cart.
     *
     * @param $int The Item ID.
     * @return void
     */
    public function add($id = null) {
        if (empty($id)) {
			return $this->redirect(array('controller' => 'pages', 'action' => 'home', 'display'));
        }

        $item = $this->Item->findById($id);

        if (!$item || $item['Item']['active'] == 0) {
            $this->Flash->info('This item is not available.');
			return $this->redirect(array('controller' => 'pages', 'action' => 'home', 'display'));
        }

		if ($this->request->is('post')) {
            $this->Cart->addItem($id, $this->request->data['Item']['quantity']);

            return $this->redirect(array(
                'controller' => 'orders',
                'action' => 'checkout',
            ));
        }

        if ($item['Item']['quantity'] < 10) {
            for ($i = 1; $i <= $item['Item']['quantity']; $i++) {
                $options[$i] = $i;
            }
        } else {
            $options = array_combine(range(1, 10), range(1, 10));
        }

		$this->set(compact('item', 'options'));
		$this->set(array('title' => $item['Item']['name']));
    }

    /**
     * admin_index method
     *
     * @return void
     */
	public function admin_index() {
		$this->Item->recursive = 0;
		$this->set('items', $this->Paginator->paginate());
	}

    /**
     * admin_view method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
	public function admin_view($id = null) {
		if (!$this->Item->exists($id)) {
			throw new NotFoundException(__('Invalid item'));
		}
		$options = array('conditions' => array('Item.' . $this->Item->primaryKey => $id));
		$this->set('item', $this->Item->find('first', $options));
	}

    /**
     * admin_add method
     *
     * @return void
     */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->Item->create();
			if ($this->Item->save($this->request->data)) {
				$this->Flash->success(__('The item has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->danger(__('The item could not be saved. Please, try again.'));
			}
		}

		$shippings = $this->Item->Shipping->find('list');
		$this->set(compact('shippings'));
	}

    /**
     * admin_edit method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
	public function admin_edit($id = null) {
		if (!$this->Item->exists($id)) {
			throw new NotFoundException(__('Invalid item'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Item->save($this->request->data)) {
				$this->Flash->success(__('The item has been saved.'));

				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->danger(__('The item could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Item.' . $this->Item->primaryKey => $id));
			$this->request->data = $this->Item->find('first', $options);
		}
		$shippings = $this->Item->Shipping->find('list');
		$this->set(compact('shippings'));
	}

    /**
     * admin_delete method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
	public function admin_delete($id = null) {
		$this->Item->id = $id;
		if (!$this->Item->exists()) {
			throw new NotFoundException(__('Invalid item'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Item->delete()) {
			$this->Flash->success(__('The item has been deleted.'));
		} else {
			$this->Flash->danger(__('The item could not be deleted. Please, try again.'));
		}

		return $this->redirect(array('action' => 'index'));
	}

    /**
     * Remove a Item from the Cart.
     *
     * @param int $id The Id of the Watch
     * @return array
     */
    public function remove($id = null) {
        if (!$this->Item->exists($id)) {
            throw new NotFoundException(__('Invalid item'));
        }

        $this->Cart->remove('Item', $id);

        return $this->redirect(array(
            'controller' => 'orders',
            'action' => 'checkout',
        ));
    }
}
