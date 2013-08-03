<?php
App::uses('AppModel', 'Model');
/**
 * Image Model
 *
 * @property Watch $Watch
 */
class Image extends AppModel {

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
}
