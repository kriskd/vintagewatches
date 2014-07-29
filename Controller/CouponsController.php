<?php
App::uses('AppController', 'Controller');
/**
 * Coupons Controller
 *
 * @property Coupon $Coupon
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 */
class CouponsController extends AppController {

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
		$this->Coupon->recursive = 0;
		$this->set('coupons', $this->Paginator->paginate());
	}

/**
 * admin_view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		if (!$this->Coupon->exists($id)) {
			throw new NotFoundException(__('Invalid coupon'));
		}
		$options = array('conditions' => array('Coupon.' . $this->Coupon->primaryKey => $id));
		$this->set('coupon', $this->Coupon->find('first', $options));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->Coupon->create();
			if ($this->Coupon->save($this->request->data)) {
				$this->Session->setFlash(__('The coupon has been saved.'), 'success');
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The coupon could not be saved. Please, try again.'), 'danger');
			}
		}
        $this->request->data['Coupon']['total'] = 1;
        $options = array(
            '' => 'Choose One', 
            'fixed' => 'Fixed', 
            'percentage' => 'Percentage'
        );
        $this->set('options', $options);
	}

/**
 * admin_edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_edit($id = null) {
		if (!$this->Coupon->exists($id)) {
			throw new NotFoundException(__('Invalid coupon'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Coupon->save($this->request->data)) {
				$this->Session->setFlash(__('The coupon has been saved.'), 'success');
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The coupon could not be saved. Please, try again.'), 'danger');
			}
		} else {
			$options = array('conditions' => array('Coupon.' . $this->Coupon->primaryKey => $id));
            $coupon = $this->Coupon->find('first', $options);
            if (!empty($coupon['Order'])) {
                $this->Session->setFlash('This coupon has at least one order associated with it and can\'t be edited.', 'info');
                $this->redirect(array(
                    'action' => 'view', $id,
                    'admin' => true,
                ));
            }
			$this->request->data = $coupon;
		}
        $options = array(
            '' => 'Choose One', 
            'fixed' => 'Fixed', 
            'percentage' => 'Percentage'
        );
        $this->set('options', $options);
	}

/**
 * admin_delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_delete($id = null) {
		$this->Coupon->id = $id;
		if (!$this->Coupon->exists()) {
			throw new NotFoundException(__('Invalid coupon'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Coupon->delete()) {
			$this->Session->setFlash(__('The coupon has been deleted.'), 'success');
		} else {
			$this->Session->setFlash(__('The coupon could not be deleted. Please, try again.'), 'danger');
		}
		return $this->redirect(array('action' => 'index'));
	}
    
    public function deleteModal()
	{
		if($this->request->is('ajax')){
			$data = $this->request->data;
            $this->set(array(
                'couponId' => $data['couponId'],
                'couponCode' => $data['couponCode'],
            ));
        }
        $this->layout = 'ajax';
    }

	public function archive()
	{
		if($this->request->is('ajax')){
			$data = $this->request->data;
			$archive = $data['archived'];
			$couponid = $data['couponid'];
			$this->Coupon->id = $couponid;
			$this->Coupon->saveField('archived', $archive);
		}
		$this->autoRender = false;
	}
}
