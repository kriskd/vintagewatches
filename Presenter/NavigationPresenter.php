<?php
App::uses('Presenter', 'CakePHP-GiftWrap.Presenter');

class NavigationPresenter extends Presenter
{
    public $helpers = array('Html');
    
    public function navigation()
    {
        return 'test';
    }
}
