<?php

class InvoiceHelper extends AppHelper
{
    /**
     * Pass an invoice object
     * Return invoice total
     */
    public function total($invoice)
    {
        $itemTotal = array_reduce($invoice['InvoiceItem'], function($sum, $item){
            $sum += $item['amount'];
            return $sum;
        });
        
        return $itemTotal + $invoice['Invoice']['shippingAmount'];
    }
}
