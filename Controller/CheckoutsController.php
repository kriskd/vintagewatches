<?php

class CheckoutsController extends AppController
{
    public function index()
    {
        if($this->request->is('post')){
            $stripeToken = $this->request->data['stripeToken'];
            $data = array('amount' => '7.59',
                          'stripeToken' => $stripeToken);
            $result = $this->Stripe->charge($data);
            var_dump($result);
        }
        
    }
}
