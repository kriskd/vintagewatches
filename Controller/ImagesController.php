<?php
App::uses('Folder', 'Utility');
App::uses('File', 'Utility');
class ImagesController extends Controller
{
    public $components = array('ImageUploader' => array('scaledImageTimeStampName' => false));
    
    public function admin_picture($id = null)
    {    
        if ($this->request->is('post') || $this->request->is('put')) {
            App::uses('Sanitize', 'Utility');
            $data = Sanitize::clean($this->request->data); 
            $image = $data[$this->modelClass]['image'];
            $imagePath = WWW_ROOT . 'files' . DS . $id;
            $thumbPath = $imagePath . DS . 'thumbs' . DS; 
            
            if (!file_exists($imagePath)) {
                $folder = new Folder($imagePath, true);
                $thumbFolder = new Folder($thumbPath, true);
            }
            
            $options = array('thumbnail' =>
                            array('max_width' => 180,
                                  'max_height' => 100,
                                  'path' => $thumbPath,
                                  'custom_name' => $data['Image']['image']['name']
                                  ),
                            'max_width' => 700,
            );
            
            try {
                $output = $this->ImageUploader->upload($image, $imagePath, $options); 
                $fileName = $this->_getFileNameFromPath($output['file_path']);
                $data = array('Image' => array('watch_id' => $id, 'filename' => $fileName));
                $this->Image->save($data);
                
            } catch(Exception $e) {
                $output = array('bool' => FALSE, 'error_message' => $e->getMessage());
            }
        }
        $this->redirect(array('controller' => 'watches', 'action' => 'edit', $id, 'admin' => true));
    }
    
    public function admin_primary($watchId, $imageId)
    {   
        if (!$this->Image->exists($imageId)) {
            throw new NotFoundException(__('Invalid image'));
        }
        
        //Set all primary to 0 for all images
        $this->Image->updateAll(array('primary' => 0), array('watch_id' => $watchId));
        
        //Make the selected impage primary
        $this->Image->id = $imageId;
        $this->Image->saveField('primary', 1);
        
        $this->redirect($this->referer());
    }
    
    public function admin_delete($watchId, $imageId)
    {
        if (!$this->Image->exists($imageId)) {
            throw new NotFoundException(__('Invalid image'));
        }
        $image = $this->Image->find('first', array('conditions' => array('id' => $imageId), 'recursive' => -1));
        $filename = $image['Image']['filename'];
        $imagePath = WWW_ROOT . 'files' . DS . $watchId;
        $thumbPath = $imagePath . DS . 'thumbs';
        $resizedPath = $imagePath . DS . 'image';
        foreach (array($imagePath, $thumbPath, $resizedPath) as $path) {
            $file = new File($path . DS . $filename, false);
            if ($file->exists()) {
                $file->delete();
            }
        }
        $this->Image->delete($imageId, false);
        
        $this->redirect($this->referer());
    }
    
    public function admin_upload($id = null)
    {
        $this->Image->save($this->request->data);
        $this->redirect(array('controller' => 'watches', 'action' => 'edit', $id, 'admin' => true));
    }
    protected function _getFileNameFromPath($path)
    {
        return substr($path, strrpos($path, '/')+1, strlen($path));
    }
}
