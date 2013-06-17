<?php
App::uses('AppModel', 'Model');

class Cart extends AppModel
{
    public $useTable = false;
    
    /**
     * $items array Array of Watch objects
     */
    public function getSubTotal($items)
    {
        if(!empty($items)){
            return  array_reduce($items, function($return, $item){ 
                        if(isset($item['Watch']['price'])){
                            $return += $item['Watch']['price'];
                            return $return;
                        }
                });
        }
        return null;
    }
}
