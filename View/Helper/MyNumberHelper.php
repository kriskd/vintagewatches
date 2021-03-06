<?php
App::uses('NumberHelper', 'View/Helper');

class MyNumberHelper extends NumberHelper
{
    public function stripe($stripe, $currency = 'USD', $options = array()) {
        return $this->currency($stripe/100, $currency, $options);
    }

    public function couponValue($order) {
        if (!isset($order['Coupon'])) {
            return 0;
        }
        if (strcasecmp($order['Coupon']['type'], 'fixed')==0) {
            return $this->currency(-$order['Coupon']['amount'], 'USD');
        }
        if (strcasecmp($order['Coupon']['type'], 'percentage')==0 && !empty($order['Coupon']['brand_id'])) {
            $totalForBrand = array_reduce($order['Watch'], function($total, $item) use($order) {
                if ($item['brand_id'] == $order['Coupon']['brand_id']) {
                    $total += $item['price'];
                }
                return $total;
            });
            return $this->currency(-($totalForBrand * $order['Coupon']['amount']));
        }
        if (strcasecmp($order['Coupon']['type'], 'fixed')==0) {
            return $this->currency(-$order['Coupon']['amount'], 'USD');
        }
        if (strcasecmp($order['Coupon']['type'], 'percentage')==0) {
            return $this->currency(
                -(($order['Payment']['stripe_amount']/100-$order['Order']['shippingAmount'])/(1-$order['Coupon']['amount'])*$order['Coupon']['amount'])
                , 'USD');
        }
        return 0;
    }
}
