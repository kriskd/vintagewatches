<?php
App::uses('Component', 'Controller');

class NavigationComponent extends Component
{
    public function getNavigation()
    {
        return ClassRegistry::init('Page')->getNavigation();
    }
}