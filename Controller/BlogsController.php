<?php

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
        //debug($blogs);
        $this->set('blogs', $blogs);
    }

    public function view($id = null) {
		if (!$this->Watch->exists($id)) {
			throw new NotFoundException(__('Invalid blog'));
		}

        $blog = $this->Blog->find('first', array(
            'conditions' => array(
                'id' => $id,
            ),
        ));

        $this->set('blog', $blog);
    }
}
