<?php
App::uses('AppModel', 'Model');
App::uses('ArraySource', 'Datasources.Model/Datasource');
App::uses('ConnectionManager', 'Model');

// Add new db config
ConnectionManager::create('province', array('datasource' => 'Datasources.ArraySource'));

/**
 * Regex Search & Replace to format data
   \'(\w\w)\'\=\>\'([A-Za-z ]+)\'\,?
   array('abbreviation' => '\1', 'name' => '\2'),
*/

/**
 * Province Model
 */
class Province extends AppModel {
    
    /**
    * Database Configuration
    *
    * @var string
    */
    public $useDbConfig = 'province';
    
    public $useTable = false;
    
    public $fields = array(
            'id' => array('type' => 'integer', 'key' => 'primary'),
            'abbreviation' => array('type' => 'string', 'null' => false),
            'name' => array('type' => 'string', 'null' => false)
    );
    
    public $records = array(
	    array('abbreviation' => 'AB', 'name' => 'Alberta'), 
            array('abbreviation' => 'BC', 'name' => 'British Columbia'),
	    array('abbreviation' => 'MB', 'name' => 'Manitoba'),
	    array('abbreviation' => 'NB', 'name' => 'New Brunswick'),
	    array('abbreviation' => 'NL', 'name' => 'Newfoundland and Labrador'),
	    array('abbreviation' => 'NT', 'name' => 'Northwest Territories'),
	    array('abbreviation' => 'NS', 'name' => 'Nova Scotia'),
	    array('abbreviation' => 'NU', 'name' => 'Nunavut'),
            array('abbreviation' => 'ON', 'name' => 'Ontario'), 
            array('abbreviation' => 'PE', 'name' => 'Prince Edward Island'), 
            array('abbreviation' => 'QC', 'name' => 'Quebec'), 
            array('abbreviation' => 'SK', 'name' => 'Saskatchewan'), 
            array('abbreviation' => 'YT', 'name' => 'Yukon Territory')
    );
}