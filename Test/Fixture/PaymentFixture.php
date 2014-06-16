<?php
App::uses('FakerTestFixture', 'CakeFaker.Lib');

class PaymentFixture extends FakerTestFixture {
    
    protected $model_name = 'Payment', $num_records = 5;
    
    protected function alterFields($generator) {
        return array(
            'stripe_last4' => function() use ($generator) { return $generator->randomNumber(4); },
            'stripe_amount' => function() use ($generator) { return $generator->randomNumber(5); },
        );
    }
}
