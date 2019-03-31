<?php
App::uses('AppController', 'Controller');
/**
 * Owners Controller
 *
 * @property Owner $Owner
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 */
class OwnersController extends AppController {

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
		if ($this->request->is('post')) {
			$data = $this->request->data;
			$newOwner['Owner']['name'] = array_pop($data['Owner']);
			if (!empty($newOwner['Owner']['name'])) {
				$this->Owner->create();
				if ($this->Owner->save($newOwner)) {
					$this->Flash->success(__('The owner has been saved.'));
				} else {
					$this->Flash->danger(__('The owner could not be saved. Please, try again.'));
				}
			}

            if (!empty($data['Owner'])) {
                foreach($data['Owner'] as $id => $item ) {
                    $saveMany[$id] = array('id' => $id, 'name' => $item['name']);
                }

                $this->Owner->saveMany($saveMany);
            }
		}
        $owners = $this->Owner->find('list', array(
                            'recursive' => -1,
                            'order' => 'name'
                        )
                    );
		$this->set('owners', $owners);
	}
}
