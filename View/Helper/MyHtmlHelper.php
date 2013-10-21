<?php
App::uses('HtmlHelper', 'View/Helper');
App::uses('CartComponent', 'Controller/Component');

class MyHtmlHelper extends HtmlHelper
{
    public function __construct(View $View, $settings = array())
    {
        parent::__construct($View, $settings);
    }
    
    public function adminLink($title, $url = null, $options = array(), $confirmMessage = false, $controller = null)
    {
        $auth = new AuthComponent(new ComponentCollection());
        $auth->initialize($controller);
        if($this->params->prefix != 'admin' && !$auth->loggedIn()){
            return null;
        }
        $url['admin'] = true;
        
        return '<li>' . parent::link($title, $url, $options, $confirmMessage) . '</li>';
    }
    
    public function image($path, $options = array())
    {
        if (is_array($path)) {
            $path = DS . implode('/', $path);
        }
        
        return parent::image($path, $options);
    }
    
    public function thumbImage($watchId, $imageFilename, $options = array())
    {
        return $this->image($imageFilename, $options);
    }
    
    public function watchImage($watchId, $imageFilename, $options = array())
    {   
        if (file_exists(WWW_ROOT . $imageFilename)) { 
            return $this->image($imageFilename, $options);
        }
        return $this->image($imageFilename, $options);
    }
    
    public function thumbPrimary($watch, $options = array())
    {
        return $this->imagePrimary($watch, 'Thumb', $options);
    }
    
    public function mediumPrimary($watch, $options = array())
    {
        return $this->imagePrimary($watch, 'Medium', $options);
    }
    
    public function largePrimary($watch, $options = array())
    {
        return $this->imagePrimary($watch, 'Large', $options);
    }
    
    /**
     * Returns primary image if one is designated or the first image if not
     */
    protected function imagePrimary($watch, $type, $options = array())
    {   
        if (!empty($watch['Image'])) {
            $images = $watch['Image'];
            $primary = array_reduce($images, function($primaryExists, $item){
                    if ($item['primary'] == 1) {
                        $primaryExists = $item;
                    }
                    return $primaryExists;
                }, null);
            $image = empty($primary) ? current($images) : $primary;
            return $this->image($image['filename'.$type], $options);
        }
        return 'No Image Available';
    }
}
