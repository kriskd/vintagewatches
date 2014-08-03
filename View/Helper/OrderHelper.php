<?php
App::uses('AppHelper', 'View/Helper');

class OrderHelper extends AppHelper {

    public $helpers = array('Number');

    public function couponValue($order) {
        if (!isset($order['Coupon'])) {
            return 0;
        }
        if (strcasecmp($order['Coupon']['type'], 'fixed')==0) {
            if ($order['Coupon']['amount'] < $this->total($order)) {
                return $this->Number->currency(-$order['Coupon']['amount'], 'USD');
            }
            return $this->totalFormatted(-($this->total($order)));
        }
        if (strcasecmp($order['Coupon']['type'], 'percentage')==0) {
            return $this->Number->currency(
                -(($order['Payment']['stripe_amount']/100-$order['Order']['shippingAmount'])/(1-$order['Coupon']['amount'])*$order['Coupon']['amount'])
                , 'USD');
        }
        return 0;
    }

    public function total($order) {
        if (isset($order['Payment']['stripe_amount'])) {
            return $this->Number->currency($order['Payment']['stripe_amount']/100, 'USD');
        }
        
        $subtotal = array_reduce($order['Watch'], function($sum, $item) {
            $sum += $item['price'];
            return $sum;    
        });
       
        return $subtotal + $order['Order']['shippingAmount'];
    }

    public function totalFormatted($order) {
        if (is_array($order)) {
            return $this->Number->currency($this->total($order), 'USD');
        }
        return $this->Number->currency($order, 'USD');
    }
}
