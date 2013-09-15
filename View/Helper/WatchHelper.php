<?php

class WatchHelper extends AppHelper
{
    public function shortDescription($description, $more = null, $limit = 50)
    {
        $words = str_word_count($description, 1); 
        if(count($words) > $limit){
            $slice = array_slice($words, 0, $limit);
            $ret = h(implode(' ', $slice));
            if($more != null){
                $ret .= '... ' . $more;
            }
            return $ret;
        }
        return $description;
    }
    
    public function getRecentWatches($count = 3)
    {
        return ClassRegistry::init('Watch')->getRecentWatches();
    }
}
