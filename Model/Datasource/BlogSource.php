<?php
App::uses('HttpSocket', 'Network/Http');

class BlogSource extends DataSource {

    public function __construct() {
        parent::__construct();
        $this->Http = new HttpSocket();
    }

    public function listSources($data = null) {
        return null;
    }

    public function calculate(Model $model, $func, $params = array()) {
        return 'COUNT';
    }

    public function read(Model $model, $queryData = array(), $recursive = null) {
        $limit = false;
        if ($queryData['fields'] === 'COUNT') {
            return array(array(array('count' => 1)));
        }
        if (is_integer($queryData['limit']) && $queryData['limit'] > 0) {
            $limit = $queryData['page'] * $queryData['limit'];
        } 
        $results = $this->Http->get('http://budgetwatchcollecting.blogspot.com/feeds/posts/default');
        $body = $results->body;
        $xml = simplexml_load_string($body);
        $json = json_encode($xml);
        $array = json_decode($json, TRUE);            
        $entries = $array['entry'];

        if (is_null($array)) {
            $error = json_last_error();
            throw new CakeException($error);
        }

        $ret = array();
        foreach ($entries as $entry) {
            $ret[][$model->alias] = $entry;
        }

        // Limit
        if ($limit !== false) {
            $ret = array_slice($ret, ($queryData['page'] - 1) * $queryData['limit'], $queryData['limit'], false);
        }

        return $ret;
    }


}
