<?php

class BlogsController extends AppController {

    public $helpers = array('Cache');

    public $cacheAction = "1 day";

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
                $this->Session->delete('BlogRetryCount');
                $this->redirect('/');
            }
            $this->redirect($this->here);
        }
        $this->set('title', $blog['Blog']['title']);
        $this->set(compact('blog', 'blogIndex'));
    }
}
