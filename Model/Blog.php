<?php
App::uses('AppModel', 'Model');
App::uses('ArraySource', 'Datasources.Model/Datasource');
App::uses('ConnectionManager', 'Model');
App::uses('HttpSocket', 'Network/Http');

// Add new db config
ConnectionManager::create('blog', array('datasource' => 'Datasources.ArraySource'));

class Blog extends AppModel {

    public $useDbConfig = 'blog';

    public $useTable = false;

    public $fields = array(
            'id' => array('type' => 'integer', 'key' => 'primary'),
    );

    public $records = array();

    public function __construct() {
        parent::__construct();
        $this->Http = new HttpSocket();
        $results = $this->Http->get('http://budgetwatchcollecting.blogspot.com/feeds/posts/default');
        $body = $results->body;
        $xml = simplexml_load_string($body);
        $json = json_encode($xml);
        $array = json_decode($json, TRUE);            
        $entries = $array['entry'];
        $count = count($entries);
        // Set a primary ID, rename blogspot ID
        foreach ($entries as $id => $entry) {
            $this->records[$id] = array('blog_id' => $entry['id']) + $entry;
            unset($this->records[$id]['id']);
            $this->records[$id] = array('id' => $count - $id) + $this->records[$id];
        }

    }
}
