<?php

class Blog extends AppModel {

    public $useDbConfig = 'blog';

    /**
     * Overridden paginateCount method
     */
    public function paginateCount($conditions = null, $recursive = 0, $extra = array()) {
        $this->recursive = $recursive;
        $results = $this->find('all');
        return count($results);
    }
}
