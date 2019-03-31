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
		if ($this->request->is('post')) {
			$data = $this->request->data;
			$newSource['Source']['name'] = array_pop($data['Source']);
			if (!empty($newSource['Source']['name'])) {
				$this->Source->create();
				if ($this->Source->save($newSource)) {
					$this->Flash->success(__('The source has been saved.'));
				} else {
					$this->Flash->danger(__('The source could not be saved. Please, try again.'));
				}
			}

            if (!empty($data['Source'])) {
                foreach($data['Source'] as $id => $item ) {
                    $saveMany[$id] = array('id' => $id, 'name' => $item['name']);
                }

                $this->Source->saveMany($saveMany);
            }
		}
        $sources = $this->Source->find('list', array(
                            'recursive' => -1,
                            'order' => 'name'
                        )
                    );
		$this->set('sources', $sources);
	}
}
