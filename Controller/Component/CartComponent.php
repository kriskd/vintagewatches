<?php
App::uses('Component', 'Controller');

class CartComponent extends Component
{
    public $components = array('Session');

    protected $items = array();

    public function initialize(Controller $controller) {
        if($this->Session->check('Cart.items') == true){
            $this->items = $this->Session->read('Cart.items');
        }
    }

    /**
     * Clear the Cart session, reset $items array
     */
    public function emptyCart() {
        $this->items = array();
        $this->Session->delete('Cart');
    }

    public function cartEmpty() {
        return empty($this->items) ? true : false;
    }

    public function cartItemCount() {
        return empty($this->items) ? null : count($this->items);
    }

    /**
     * Returns an array of Watch IDs in the cart
     */
    public function cartItemIds() {
        return $this->items;
    }

    /**
     * Add an item to the cart
     */
    public function add($id) {
        $this->items[] = $id;
        return $this->Session->write('Cart.items', $this->items);
    }

    /**
     * Remove an item from the cart
     */
    public function remove($id) {
        if(in_array($id, $this->items)){
            $key = array_search($id, $this->items);
            unset($this->items[$key]);
            $this->items = array_values($this->items);
            return $this->Session->write('Cart.items', $this->items);
        }
        return false;
    }

    public function inCart($id = null) {
        return is_array($this->items) && in_array($id, $this->items);
    }

    public function getShippingAmount($country = '') {
        switch($country){
            case '':
                return '';
                break;
            case 'us':
                return '8';
                break;
            case 'ca':
                return '38';
                break;
            default:
                return '45';
                break;
        }
    }

    /**
     * Get allowed billing/shipping country based on ship-to country
     */
    public function getSecondaryCountry($country) {
        $countries = array(
            'US' => 'CA',
            'CA' => 'US',
            'OTHER' => 'OTHER'
        );

        if (!in_array($country, $countries)) return '';

        return $countries[$country];
    }

    public function totalCart($itemsTotal, $shipping, $couponAmount) {
        return $itemsTotal + $shipping - $couponAmount;
    }

    /**
     * $items array Array of Watch objects
     * $brand_id int Optional brand_id
     */
    public function getSubTotal($items, $brand_id = null) {
        if(!empty($items)){
            return  array_reduce($items, function($return, $item) use ($brand_id) {
                if(isset($item['Watch']['price'])){
                    if (empty($brand_id) || $brand_id == $item['Watch']['brand_id']) {
                        $return += $item['Watch']['price'];
                    }
                    return $return;
                }
            });
        }
        return null;
    }

    /**
     * $items array Array of Watch objects
     * $shipping int Shipping amount
     * $coupon object
     */
    public function couponAmount($items, $shipping, $coupon = array()) {
        if (empty($coupon['Coupon'])) {
            return 0;
        }
        // Total eligible for coupon
        $couponSubTotal = $this->getSubTotal($items, $coupon['Coupon']['brand_id']);
        switch ($coupon['Coupon']['type']) {
            case 'fixed':
                $couponAmount = $couponSubTotal + $shipping > $coupon['Coupon']['amount'] ? $coupon['Coupon']['amount'] : $couponSubTotal + $shipping;
                break;
            case 'percentage':
                $couponAmount = $couponSubTotal * $coupon['Coupon']['amount'];
                break;
            default:
                $couponAmount = 0;
        }
        return $couponAmount;
    }

    /**
     * Create string of watch brands to send to Stripe as description
     * @param array $watches
     * @return string
     */
    public function stripeDescription($watches) {
        $brands = array();
        foreach($watches as $watch) {
            $brands[] = $watch['Brand']['name'];
        }
        return implode(',', $brands);
    }

    /**
     * Reformat addresses in request data from array('type' => $address) 
     * to array(0 => $addressWithType)
     * @param array $addresses from request
     * @return array
     */
    public function formatAddresses($addresses) {
        $addressesToSave = array();
        foreach($addresses as $type => $item){
            $address = $item;
            $address['type'] = $type;
            $addressesToSave[] = $address;
        }
        return $addressesToSave;
    }

    /**
     * Save checkout data to session on cart fail so it doesn't have to be re-entered on checkout again
     * @param array the request data
     * @param array optional form errors
     * @return void
     */
    public function setCheckoutData($data, $errors = array()) {
        // data key used in getAddress method in OrdersController
        $this->Session->write('Address.data', $data['Address']);
        if (!empty($errors)) {
            $this->Session->write('Address.errors', $errors);
        }
        // Write select-country in separate Session key since we don't use data key, will set $this->request->data
        $this->Session->write('Address.select-country', $data['Address']['select-country']);
        // Used in getShippingChoice method in OrdersController, will set $this->request->data
        $this->Session->write('Shipping.option',  $data['Shipping']['option']);
        $this->Session->write('Order', $data['Order']);
    }

	/**
	 * Check that watches in the cart are still active
	 * @param $cartWatches array of watches in cart
	 * @param $cartIds array IDs of watches in cart
	 * @return bool
	 */
	public function checkActive($cartWatches, $cartItemIds) {
		$activeWatches = array_filter($cartWatches, function($item) {
			return $item['Watch']['active'] == 1;
		});
		if (count($activeWatches) != $this->cartItemCount()) {
			$activeIds = Hash::extract($activeWatches, '{n}.Watch.id');
			$remove = array_diff($cartItemIds, $activeIds);
			foreach ($remove as $id) {
				$this->remove($id);
			}
			return false;
		}
		return true;
	}
}
