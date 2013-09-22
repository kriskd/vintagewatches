<?php

class NavigationHelper extends AppHelper
{
    public function getNavigation()
    {
        return ClassRegistry::init('Page')->getNavigation();
    }
}
