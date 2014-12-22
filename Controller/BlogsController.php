<?php
//App::uses('HttpSocket', 'Network/Http');

class BlogsController extends AppController {

    public $paginate = array(
        'limit' => 5,
        'order' => array(
            'Blog.published' => 'desc'
        )
    );
    public function index() {
        $this->Paginator->settings = $this->paginate;
        $blogs = $this->Paginator->paginate('Blog');
        //debug($blogs); exit;
        $this->set('blogs', $blogs);
    }
}
