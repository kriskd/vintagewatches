<?php
App::uses('CakeNumber', 'Utility');
App::uses('Component', 'Controller');

class CartComponent extends Component {
    public $components = array('Session');

    protected $watches = array();

    protected $items = array();

    public function initialize(Controller $controller) {
        if ($this->Session->check('Cart.watches') == true){
            $this->watches = $this->Session->read('Cart.watches');
        }

        if ($this->Session->check('Cart.items') == true){
            $this->items = $this->Session->read('Cart.items');
        }
    }

    /**
     * Clear the Cart session, reset $watches array
     */
    public function emptyCart() {
        $this->watches = array();
        $this->items = array();
        $this->Session->delete('Cart');
    }

    public function cartEmpty() {
        return empty($this->watches) && empty($this->items) ? true : false;
    }

    public function cartCount() {
        return $this->cartWatchCount() + $this->cartItemCount();
    }

    public function cartItemCount() {
        return count($this->items);
    }

    public function cartWatchCount() {
        return count($this->watches);
    }

    /**
     * Returns an array of Watch IDs in the cart.
     *
     * @return array
     */
    public function cartWatchIds() {
        return $this->watches;
    }

    /**
     * Returns an array of Item IDs in the cart.
     */
    public function cartItemIds() {
        return $this->items;
    }

    /**
     * Add a watch to the cart
     */
    public function add($id) {
        $this->watches[] = $id;
        return $this->Session->write('Cart.watches', $this->watches);
    }

    /**
     * Add an item to the cart.
     *
     * @param int $id The Id of the Item.
     * @param int $quantity The number of Items to add to the cart.
     */
    public function addItem($id, $quantity = 1) {
        for ($i = 1; $i <= $quantity; $i++) {
            $this->items[] = $id;
        }

        return $this->Session->write('Cart.items', $this->items);
    }

    /**
     * Remove a watch or item from the cart
     *
     * @param string $model Either Watch or Item.
     * @param int $id The Id of the Watch or Item.
     * @return bool
     */
    public function remove($model, $id) {
        $type = Inflector::pluralize(strtolower($model));
        if (in_array($id, $this->{$type})){
            $key = array_search($id, $this->{$type});
            unset($this->{$type}[$key]);
            $this->{$type} = array_values($this->{$type});
            return $this->Session->write('Cart.' . $type, $this->{$type});
        }

        return false;
    }

    public function inCart($id = null) {
        return is_array($this->watches) && in_array($id, $this->watches);
    }

    /**
     * Compute the shipping on the Order for Watches and Items
     *
     * @param string $country The shipping Country the user has selected.
     * @param bool $upgradeShipping Upgrade to priority shipping when only Items in cart.
     * @return float
     */
    public function getShippingAmount($country = '', $upgradeShipping = false) {
        if (empty($this->cartWatchIds())) {
            if (strcasecmp($country, 'us') == 0) {
                if ($upgradeShipping) {
                    return 6;
                }

                return 3;
            }

            return 25;
        }

        switch($country) {
            case '':
                return '';
                break;
            case 'us':
                return Configure::read('Shipping.us');
                break;
            case 'ca':
                return Configure::read('Shipping.ca');
                break;
            default:
                return Configure::read('Shipping.other');
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
     * $watches array Array of Watch objects
     * $brand_id int Optional brand_id
     */
    public function getSubTotal($watches = array(), $brand_id = null) {
        if (!empty($watches)){
            return  array_reduce($watches, function($return, $item) use ($brand_id) {
                if (isset($item['Watch']['price'])){
                    if (empty($brand_id) || $brand_id == $item['Watch']['brand_id']) {
                        $return += $item['Watch']['price'];
                    }
                    return $return;
                }
            });
        }

        return 0;
    }

    /**
     * Sum of all the Items in the cart.
     *
     * @param array $items Array of items in the cart with computed `ordered` and `subtotal` fields.
     * @return float
     */
    public function getItemsSubTotal($items = array()) {
        return array_sum(Hash::extract($items, '{n}.Item.subtotal'));
    }

    /**
     * Computes the value of the coupon.
     *
     * @param array $watches array Array of Watches
     * @param array $items Array of Items
     * @param int $shipping Shipping amount
     * @param array $coupon The Coupon Array
     */
    public function couponAmount($watches, $items, $shipping, $coupon = array()) {
        if (empty($coupon['Coupon'])) {
            return 0;
        }
        // Total eligible for coupon
        $itemSubTotal = empty($coupon['Coupon']['brand_id']) ? $this->getItemsSubTotal($items) : 0;
        $couponSubTotal = $itemSubTotal + $this->getSubTotal($watches, $coupon['Coupon']['brand_id']);
        switch ($coupon['Coupon']['type']) {
            case 'fixed':
                $couponAmount = $couponSubTotal + $shipping > $coupon['Coupon']['amount'] ? $coupon['Coupon']['amount'] : $couponSubTotal + $shipping;
                break;
            case 'percentage':
                $couponAmount = CakeNumber::precision($couponSubTotal * $coupon['Coupon']['amount'], 2);
                break;
            default:
                $couponAmount = 0;
        }

        return $couponAmount;
    }

    /**
     * Create string of watch brands and item descriptions to send to Stripe as description
     *
     * @param array $watches
     * @param array $items
     * @return string
     */
    public function stripeDescription($watches, $items) {
        $description = [];

        foreach($watches as $watch) {
            $description[] = $watch['Brand']['name'];
        }

        foreach($items as $item) {
            $description[] = $item['Item']['description'];
        }

        return implode(',', $description);
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
	 * @return bool
	 */
	public function checkActive($cartWatches) {
		$activeWatches = array_filter($cartWatches, function($item) {
			return $item['Watch']['active'] == 1;
		});
		if (count($activeWatches) != $this->cartWatchCount()) {
			$activeIds = Hash::extract($activeWatches, '{n}.Watch.id');
		    $cartItemIds = Hash::extract($cartWatches, '{n}.Watch.id');
			$remove = array_diff($cartItemIds, $activeIds);
			foreach ($remove as $id) {
				$this->remove('Watch', $id);
			}
			return false;
		}
		return true;
	}
}
