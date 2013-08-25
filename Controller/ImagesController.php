<?php
App::uses('Folder', 'Utility');
class ImagesController extends Controller
{
    public $components = array('ImageUploader');
    
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
                $fileName = empty($output['file_path_max_width']) ?
                            $this->_getFileNameFromPath($output['file_path']) :
                            $this->_getFileNameFromPath($output['file_path_max_width']);
                $thumbFileName = $this->_getFileNameFromPath($output['thumb_path']);
                
                $data = array(array('Image' => array('watch_id' => $id, 'type' => 'image', 'filename' => $fileName)),
                              array('Image' => array('watch_id' => $id, 'type' => 'thumb', 'filename' => $thumbFileName))
                             );
                $this->Image->saveMany($data);
            } catch(Exception $e) {
                $output = array('bool' => FALSE, 'error_message' => $e->getMessage());
            }
        }
        $this->redirect(array('controller' => 'watches', 'action' => 'edit', $id, 'admin' => true));
    }
    
    /**
     * Not done, need to change how images are saved due to naming issues
     */
    public function admin_primary($watchId, $imageId)
    {
        if (!$this->Image->exists($imageid)) {
            throw new NotFoundException(__('Invalid image'));
        }
        
        $images = $this->Image->find('list', array('conditions' => array('watch_id' => $watchId),
                                                   'fields' => (array('id')
                                                    )
                                                   )
                                    );
        $this->Image->updateAll(array('primary' => 0), array('watch_id' => $watchId));
        
        $thumb = $this->Image->find('first', array('conditions' => array('id' => $imageId)));
    }
    
        
    protected function _getFileNameFromPath($path)
    {
        return substr($path, strrpos($path, '/')+1, strlen($path));
    }
}
