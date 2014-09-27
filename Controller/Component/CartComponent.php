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
        if(!empty($this->items)){
            return false;
        }
        return true;
    }
    
    public function cartItemCount() {
        if(!empty($this->items)){
            return count($this->items);
        }
        return null;
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
        $this->Session->write('Cart.items', $this->items);
    }
    
    /**
     * Remove an item from the cart
     */
    public function remove($id) {
        if(in_array($id, $this->items)){
            $key = array_search($id, $this->items);
            unset($this->items[$key]); 
            $this->Session->write('Cart.items', $this->items);
        }
    }
    
    public function inCart($id = null) {
        if(is_array($this->items) && in_array($id, $this->items)){
            return true;
        }
        return false;
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
}
