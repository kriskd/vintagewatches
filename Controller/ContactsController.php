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
	public $components = array('Paginator', 'Captcha');
	
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
					$Email = new CakeEmail('smtp');
					$Email->template('contact', 'default')
					      ->emailFormat('html')
					      ->to(Configure::read('contactFormEmail'))
					      ->from(Configure::read('fromEmail'))
					      ->replyTo($contact['Contact']['email'])
					      ->subject('Message From ' . $contact['Contact']['name'])
					      ->viewVars(array('contact' => $contact))
					      ->send();
					$this->Session->setFlash(__('Thank you for contacting us.'), 'success');
					return $this->redirect(array('controller' => 'pages', 'action' => 'display', 'home'));
				} else {
					$this->Session->setFlash(__('Message could not be saved. Please, try again.'), 'danger');
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
		$this->Contact->recursive = 0;
		$this->Paginator->settings = $this->paginate;
		$this->set('contacts', $this->Paginator->paginate());
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
				$this->Session->setFlash(__('The contact has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The contact could not be saved. Please, try again.'));
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
				$this->Session->setFlash(__('The contact has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The contact could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Contact.' . $this->Contact->primaryKey => $id));
			$this->request->data = $this->Contact->find('first', $options);
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
		$this->Contact->id = $id;
		if (!$this->Contact->exists()) {
			throw new NotFoundException(__('Invalid contact'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Contact->delete()) {
			$this->Session->setFlash(__('The contact has been deleted.'));
		} else {
			$this->Session->setFlash(__('The contact could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}}
