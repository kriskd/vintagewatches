<?php
class WatchHelper extends AppHelper
{
    public $helpers = array('Html');
    
    public function shortDescription($description, $more = null, $limit = 50)
    {
        //Not going to allow html tags in short descriptions
        $cleanDescription = strip_tags($description);
        //Numbers are considered words
        $words = str_word_count($cleanDescription, 1, '0123456789'); 
        if(count($words) > $limit){
            $slice = array_slice($words, 0, $limit);
            $ret = implode(' ', $slice);
            if($more != null){
                $ret .= '... ' . $more;
            }
            return $ret;
        }
        return $cleanDescription;
    }
    
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
