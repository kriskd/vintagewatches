<?php
App::uses('AppModel', 'Model');
App::uses('CakeTime', 'Utility');

/**
 * Vacation Model
 *
 */
class Vacation extends AppModel {

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'start' => array(
			'datetime' => array(
				'rule' => array('datetime'),
				'message' => 'Please enter the start date of the vacation',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
			'notEmpty' => array(
				'rule' => array('notEmpty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'end' => array(
			'datetime' => array(
				'rule' => array('datetime'),
				'message' => 'Please enter the end date of the vacation',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
			'notEmpty' => array(
				'rule' => array('notEmpty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
			'future' => array(
				'rule' => array('checkFutureDate'),
				'message' => 'End date must be greater than start date.',
			),
		),
		'message' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);

	public function checkFutureDate($check) {
		$start = $this->data['Vacation']['start'];
		$end = $this->data['Vacation']['end'];
		return CakeTime::fromString($end) > CakeTime::fromString($start);
	}
}
