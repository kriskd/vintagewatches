<?php

class BlogsController extends AppController {

    public $helpers = array('Cache');

    public $cacheAction = "1 day";

    public function view($id = null) {
        if (empty($id)) {
            $blog = Cache::read('blog', 'blog');
            if (!$blog) {
                $blog = $this->Blog->find('first', array(
                    'order' => 'id DESC',
                ));
                Cache::write('blog', $blog, 'blog');
            }
        } else {
            $blog = Cache::read('blog_'.$id, 'blog');
            if (!$blog) {
                $blog = $this->Blog->find('first', array(
                    'conditions' => array(
                        'id' => $id,
                    )
                ));
                Cache::write('blog_'.$id, $blog, 'blog');
            }
        }

        $blogIndex = Cache::read('blog_index', 'blog');
        if (!$blogIndex) {
            $blogIndex = $this->Blog->blogIndex();
            Cache::write('blog_index', $blogIndex, 'blog');
        }

        if (empty($blog) || empty($blogIndex)) {
            $blogRetryCount = $this->Session->check('BlogRetryCount') ? $this->Session->read('BlogRetryCount') : 0;
            ++$blogRetryCount;
            $this->log($blogRetryCount, 'error');
            $this->Session->write('BlogRetryCount', $blogRetryCount);
            if ($blogRetryCount > 5) {
                $this->Flash->info('Blog temporarily unavailable.');
                $this->Session->delete('BlogRetryCount');
                return $this->redirect('/');
            }

            return $this->redirect($this->here);
        }
        $this->set('title', $blog['Blog']['title']);
        $this->set(compact('blog', 'blogIndex'));
    }

}
