<?php
App::uses('NumberHelper', 'View/Helper');

class MyNumberHelper extends NumberHelper
{
    public function stripe($stripe, $currency = 'USD', $options = array())
    {
        return $this->currency($stripe/100, $currency, $options);
    }
}
