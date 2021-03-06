<?php
App::uses('AppController', 'Controller');
/**
 * Contacts Controller
 *
 * @property Contact $Contact
 * @property PaginatorComponent $Paginator
 */
class ContactsController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator', 'Captcha', 'Emailer');

	public $paginate = array(
		'order' => array(
			'id' => 'desc'
		)
	);

/**
 * index method
 *
 * @return void
 */
	public function index() {
		if ($this->request->is('post')) {
			$this->Contact->set($this->request->data);
			if(!$this->Captcha->verify()) {
				$this->Contact->invalidate('s3captcha-error');
			}
			if ($this->Contact->validates()){
				$this->Contact->create();
				if ($this->Contact->save($this->request->data)) {
					$contact = $this->request->data;
					$this->Flash->success(__('Thank you for contacting us.'));
                    $this->Emailer->contact($contact);
					return $this->redirect(array('controller' => 'pages', 'action' => 'display', 'home'));
				} else {
					$this->Flash->danger(__('Message could not be saved. Please, try again.'));
				}
			}
		}

		list($items, $selectedItem) = $this->Captcha->makeCaptcha();
		$title = 'Contact Us';
		$this->set(compact('items', 'selectedItem', 'title'));
	}


/**
 * admin_index method
 *
 * @return void
 */
	public function admin_index() {
		$this->paginate['paramType'] = 'querystring';
		$this->Contact->recursive = 0;
		$this->Paginator->settings = $this->paginate;

		try {
			$contacts = $this->Paginator->paginate();
		} catch (NotFoundException $e) {
			//Redirect to previous page
			$query = $this->request->query;
			$query['page']--;
			$this->redirect(array_merge(Router::parse($this->here), array('?' => $query))); 
		}
		
		$this->set('contacts', $contacts);
	}
	
	public function deleteModal()
	{
		if($this->request->is('ajax')){
			$data = $this->request->data;
			$this->set(array('contactId' => $data['contactId'],
					 'contactName' => $data['contactName'],
					 'query' => $data['query']
					 ));
		}
		$this->layout = 'ajax';
	}

/**
 * admin_view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		if (!$this->Contact->exists($id)) {
			throw new NotFoundException(__('Invalid contact'));
		}
		$options = array('conditions' => array('Contact.' . $this->Contact->primaryKey => $id));
		$this->set('contact', $this->Contact->find('first', $options));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->Contact->create();
			if ($this->Contact->save($this->request->data)) {
				$this->Flash->success(__('The contact has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->danger(__('The contact could not be saved. Please, try again.'));
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
		if (!$this->Contact->exists($id)) {
			throw new NotFoundException(__('Invalid contact'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Contact->save($this->request->data)) {
				$this->Flash->success(__('The contact has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->danger(__('The contact could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Contact.' . $this->Contact->primaryKey => $id));
			$this->request->data = $this->Contact->find('first', $options);
		}
	}

/**
 * admin_delete method
 * Get the query string to redirect back to it instead of default to first page
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_delete($id = null, $query = '') {
		$this->Contact->id = $id;
		if (!$this->Contact->exists()) {
			throw new NotFoundException(__('Invalid contact'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Contact->delete()) {
			$this->Flash->success(__('The contact has been deleted.'));
		} else {
			$this->Flash->danger(__('The contact could not be deleted. Please, try again.'));
		}

		return $this->redirect(array_merge(array('action' => 'index'), array('?' => $query)));
    }
}
