<?php

class ImagesController extends Controller
{
    public $components = array('ImageUploader');
    
    public function admin_picture($id = null)
	{
        if ($this->request->is('post') || $this->request->is('put')) {
            App::uses('Sanitize', 'Utility');
            $data = Sanitize::clean($this->request->data);
            $image = $data[$this->modelClass]['image'];
            $imagePath = WWW_ROOT . 'files/';
            $thumbPath = $imagePath . 'thumbs/';
            
            $options = array('thumbnail' =>
                            array('max_width' => 180,
                                  'max_height' => 100,
                                  'path' => $thumbPath,
                                  ),
                            'max_width' => 700,
            );
            
            try {
                $output = $this->ImageUploader->upload($image, $imagePath, $options);
                $fileName = substr($output['file_path'], strrpos($output['file_path'], '/')+1, strlen($output['file_path']));
                $thumbFileName = substr($output['thumb_path'], strrpos($output['thumb_path'], '/')+1, strlen($output['thumb_path']));
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
    
    public function admin_primary()
    {
        
    }
}
