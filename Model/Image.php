<?php
App::uses('AppModel', 'Model');
App::uses('Folder', 'Utility');
App::uses('File', 'Utility');
App::uses('AttachmentBehavior', 'Uploader.Model/Behavior');

/**
 * Image Model
 *
 * @property Watch $Watch
 */
class Image extends AppModel {
    
    public $actsAs = array(
	'Uploader.Attachment' => array(
            'filename' => array(
                //'nameCallback' => 'filename',
                'append' => '',
                'prepend' => '',
                'tempDir' => TMP,
                'uploadDir' => 'files',
                'transportDir' => '',
                'finalPath' => 'files',
                'dbColumn' => '',
                'metaColumns' => array(),
                'defaultPath' => '',
                'overwrite' => false,
                'stopSave' => true,
                'allowEmpty' => true,
                'transforms' => array(
                    'filename' => array(
                        'method' => AttachmentBehavior::FIT,
                        'width' => 700,
                        'height' => 525,
                        'fill' => array(255, 255, 255),
                        'vertical' => 'center',
                        'horizontal' => 'center',
                        'nameCallback' => 'fitName'
                    ),
                    'filenameThumb' => array(
                        'method' => AttachmentBehavior::FIT,
                        'width' => 75,
                        'height' => 75,
                        'fill' => array(255, 255, 255),
                        'vertical' => 'center',
                        'horizontal' => 'center',
                        'nameCallback' => 'fitName',
                        'append' => '-thumb'
                    )
                ),
                'transport' => array(),
                'curl' => array()
            )
	)
    );

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
    public $belongsTo = array(
            'Watch' => array(
                    'className' => 'Watch',
                    'foreignKey' => 'watch_id',
                    'conditions' => '',
                    'fields' => '',
                    'order' => ''
            )
    );
    
    public function beforeUpload($options)
    {
        $data = $this->data;
        $watch_id = $data[$this->alias]['watch_id']; 
        $options['finalPath'] = DS . 'files' . DS . $watch_id . DS;
        $options['uploadDir'] = WWW_ROOT . $options['finalPath'];
        
        if (!file_exists($options['uploadDir'])) {
            new Folder($options['uploadDir'], true);
        }
        return $options;
    }
    
    public function beforeTransform($options)
    {
        $data = $this->data;
        $watch_id = $data[$this->alias]['watch_id']; 
        $options['finalPath'] = DS . 'files' . DS . $watch_id . DS;
        $options['uploadDir'] = WWW_ROOT . $options['finalPath'];
        
        if (!file_exists($options['uploadDir'])) {
            new Folder($options['uploadDir'], true);
        }
        return $options;
    }
    
    /*public function filename($name, $file)
    {
        //var_dump($name, $file); exit;
        return $file->name();
    }*/
    
    public function fitName($name, $file)
    {
        return $this->getUploadedFile()->name();
    }
}
