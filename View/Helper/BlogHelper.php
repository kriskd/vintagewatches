<?php 
App::uses('AppHelper', 'View/Helper');

class BlogHelper extends AppHelper {

    public $helpers = array('Blog');

    public function blogCount($blogs = array()) {
        return array_reduce($blogs, function($ret, $item){
            $ret += count($item);
            return $ret;
        });
    }
}
