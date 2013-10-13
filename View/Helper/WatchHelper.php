<?php
class WatchHelper extends AppHelper
{
    public $helpers = array('Html');
    
    public function getRecentWatches($count = 3)
    {
        return ClassRegistry::init('Watch')->getRecentWatches();
    }
    
    public function closeOpenStore()
    {
        $storeOpen = ClassRegistry::init('Watch')->storeOpen();
        
        if ($storeOpen === true) {
            return $this->Html->link('Close Store', array('action' => 'close'), array('class' => 'btn btn-danger'));
        }
        return $this->Html->link('Open Store', array('action' => 'open'), array('class' => 'btn btn-success'));
    }
}
