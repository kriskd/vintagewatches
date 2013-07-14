<?php
class UsersController extends AppController
{    
    public function login()
    {
        if($this->Auth->loggedIn() == true){ 
            $this->redirect($this->Auth->redirectUrl());
        }
        if($this->request->is('post') || $this->request->is('put')){
            if($this->Auth->login()){
                $this->redirect($this->Auth->redirectUrl());
            }
            $this->Session->setFlash('Username or password is incorrect.');
        }  
    }
    
    public function logout()
    {
        $this->redirect($this->Auth->logout());
    }
}