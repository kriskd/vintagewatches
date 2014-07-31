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
		} else {
            $this->request->data['Coupon']['total'] = 1;
        }
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

    /**
     * Ajax controller method for archiving coupon on coupon index
     * If the coupon is being unarchived check that is unique among unarchived coupons
     */
	public function archive()
	{
		if($this->request->is('ajax')){
			$data = $this->request->data;
			$archive = $data['archived'];
            $unique = true;
            if ($archive != 1) {
                $code = $data['couponcode'];
                $unique = $this->Coupon->uniqueNotArchived($code);
            }
			$couponid = $data['couponid'];
			$this->Coupon->id = $couponid;
            if ($unique) {
                $this->Coupon->saveField('archived', $archive);
            } else {
                echo json_encode(array('msg' => 'This coupon can\'t be activated because there is another coupon with code '.strtoupper($code).' that is currently active.'));
            }
		}
		$this->autoRender = false;
	}
}
