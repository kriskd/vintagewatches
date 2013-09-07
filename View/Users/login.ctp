<?php
//echo $session->flash('auth');
echo $this->Form->create('User', array('action' => 'login', 'role' => 'form'));
echo $this->Form->input('username', array('class' => 'form-control'));
echo $this->Form->input('password', array('class' => 'form-control'));
echo $this->Form->end(array('label' => 'Login', 'class' => 'btn btn-primary'));