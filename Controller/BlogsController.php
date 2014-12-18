<?php
App::uses('HttpSocket', 'Network/Http');

class BlogsController extends AppController {

    public function index() {
        $HttpSocket = new HttpSocket();
        $results = $HttpSocket->get('http://budgetwatchcollecting.blogspot.com/feeds/posts/default');
        $body = $results->body;
        $xml = simplexml_load_string($body);
        $this->set('xml', $xml);
    }
}
