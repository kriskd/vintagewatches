<?php

class InvoiceShell extends AppShell
{
    public $uses = array('Invoice');

    public function main()
    {
        $expire = $this->Invoice->find('all', array(
            'conditions' => array(
                'Invoice.expiration < date(now())',
                'Invoice.active' => 1,
            ),
            'contain' => array('InvoiceItem'),
        ));

        if (!empty($expire)) {
            $expire = array_map(function($item){
                $item['Invoice']['active'] = 0; 
                return $item;
            }, $expire);
            $this->Invoice->saveMany($expire);
        }
    }
}
