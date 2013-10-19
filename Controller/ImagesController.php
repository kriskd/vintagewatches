<?php
App::uses('Folder', 'Utility');
App::uses('File', 'Utility');
class ImagesController extends Controller
{
    public function admin_upload($id = null)
    {
        if (!$this->Image->Watch->exists($id)) {
            $this->redirect(array('controller' => 'watches', 'action' => 'index', $id, 'admin' => true));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            $this->Image->save($this->request->data);
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

        $this->Image->delete($imageId);
        
        $this->redirect($this->referer());
    }
}
