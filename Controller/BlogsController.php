<?php

class BlogsController extends AppController {

    public $helpers = array('Cache');

    public $cacheAction = "1 day";

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
        if (empty($id)) {
            $blog = $this->Blog->find('first', array(
                'order' => 'id DESC',
            ));
        } else {
            $blog = $this->Blog->find('first', array(
                'conditions' => array(
                    'id' => $id,
                )
            ));
        }

        $blogIndex = $this->Blog->blogIndex();

        if (empty($blog) || empty($blogIndex)) {
            $blogRetryCount = $this->Session->check('BlogRetryCount') ? $this->Session->read('BlogRetryCount') : 0;
            ++$blogRetryCount;
            $this->log($blogRetryCount, 'error');
            $this->Session->write('BlogRetryCount', $blogRetryCount); 
            if ($blogRetryCount > 5) {
                $this->Session->setFlash('Blog temporarily unavailable.', 'info');
                CakeSession::delete('BlogRetryCount');
                $this->redirect('/');
            }
            $this->redirect($this->here);
        }
        $this->set(compact('blog', 'blogIndex'));
    }
}
