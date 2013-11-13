<?php
App::uses('Folder', 'Utility');
App::uses('File', 'Utility');
class ImagesController extends Controller
{
    /*
     * $this->request->data
     *
     * array(
            'Image' => array(
                    'watch_id' => '10',
                    'filename' => array(
                            'name' => 'croton5365.jpg',
                            'type' => 'image/jpeg',
                            'tmp_name' => '/tmp/php7tZj4P',
                            'error' => (int) 0,
                            'size' => (int) 72725
                    )
            )
        )
     */
    public function admin_upload($id = null)
    {
        if (!$this->Image->Watch->exists($id)) {
            $this->redirect(array('controller' => 'watches', 'action' => 'index', $id, 'admin' => true));
        }
        if ($this->request->is('post') || $this->request->is('put')) { 
            $files = $this->request->data[$this->modelClass]['filename']; 
            foreach ($files as $file) {
                $save[] = array('watch_id' => $id, 'filename' => $file);
            }
            $this->Image->saveMany($save);
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
    
    public function admin_delete($imageId)
    {   
        if (!$this->Image->exists($imageId)) {
            throw new NotFoundException(__('Invalid image'));
        }

        $this->Image->delete($imageId);
        
        $this->redirect($this->referer());
    }
}
