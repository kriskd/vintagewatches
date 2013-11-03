<?php
App::uses('Presenter', 'CakePHP-GiftWrap.Presenter');

class NavigationPresenter extends Presenter
{
    public function storeLink()
    {
        $ret = '';
        if ($this->storeOpen) {
            $ret = $this->Navigation->storeLink('<i class="glyphicon glyphicon-tags"></i>&nbsp;&nbsp;Store',
                                             array('controller' => 'watches', 'action' => 'index'),
                                             array('escape' => false),
                                             false);
        }
        return $ret;
    }
}
