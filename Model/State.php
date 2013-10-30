<?php
App::uses('AppModel', 'Model');
App::uses('ArraySource', 'Datasources.Model/Datasource');
App::uses('ConnectionManager', 'Model');

// Add new db config
ConnectionManager::create('state', array('datasource' => 'Datasources.ArraySource'));

/**
 * Regex Search & Replace to format data
   \'(\w\w)\'\=\>\'([A-Za-z ]+)\'\,?
   array('abbreviation' => '\1', 'name' => '\2'),
*/

/**
 * State Model
 */
class State extends AppModel {
    
    /**
    * Database Configuration
    *
    * @var string
    */
    public $useDbConfig = 'state';
    
    public $useTable = false;
    
    public $fields = array(
            'id' => array('type' => 'integer', 'key' => 'primary'),
            'abbreviation' => array('type' => 'string', 'null' => false),
            'name' => array('type' => 'string', 'null' => false)
    );
    
    public $records = array(
            array('abbreviation' => 'AL', 'name' => 'Alabama'),  
            array('abbreviation' => 'AK', 'name' => 'Alaska'),  
            array('abbreviation' => 'AZ', 'name' => 'Arizona'),  
            array('abbreviation' => 'AR', 'name' => 'Arkansas'),  
            array('abbreviation' => 'CA', 'name' => 'California'),  
            array('abbreviation' => 'CO', 'name' => 'Colorado'),  
            array('abbreviation' => 'CT', 'name' => 'Connecticut'),  
            array('abbreviation' => 'DE', 'name' => 'Delaware'),  
            array('abbreviation' => 'DC', 'name' => 'District Of Columbia'),  
            array('abbreviation' => 'FL', 'name' => 'Florida'),  
            array('abbreviation' => 'GA', 'name' => 'Georgia'),  
            array('abbreviation' => 'HI', 'name' => 'Hawaii'),  
            array('abbreviation' => 'ID', 'name' => 'Idaho'),  
            array('abbreviation' => 'IL', 'name' => 'Illinois'),  
            array('abbreviation' => 'IN', 'name' => 'Indiana'),  
            array('abbreviation' => 'IA', 'name' => 'Iowa'),  
            array('abbreviation' => 'KS', 'name' => 'Kansas'),  
            array('abbreviation' => 'KY', 'name' => 'Kentucky'),  
            array('abbreviation' => 'LA', 'name' => 'Louisiana'),  
            array('abbreviation' => 'ME', 'name' => 'Maine'),  
            array('abbreviation' => 'MD', 'name' => 'Maryland'),  
            array('abbreviation' => 'MA', 'name' => 'Massachusetts'),  
            array('abbreviation' => 'MI', 'name' => 'Michigan'),  
            array('abbreviation' => 'MN', 'name' => 'Minnesota'),  
            array('abbreviation' => 'MS', 'name' => 'Mississippi'),  
            array('abbreviation' => 'MO', 'name' => 'Missouri'),  
            array('abbreviation' => 'MT', 'name' => 'Montana'),
            array('abbreviation' => 'NE', 'name' => 'Nebraska'),
            array('abbreviation' => 'NV', 'name' => 'Nevada'),
            array('abbreviation' => 'NH', 'name' => 'New Hampshire'),
            array('abbreviation' => 'NJ', 'name' => 'New Jersey'),
            array('abbreviation' => 'NM', 'name' => 'New Mexico'),
            array('abbreviation' => 'NY', 'name' => 'New York'),
            array('abbreviation' => 'NC', 'name' => 'North Carolina'),
            array('abbreviation' => 'ND', 'name' => 'North Dakota'),
            array('abbreviation' => 'OH', 'name' => 'Ohio'),  
            array('abbreviation' => 'OK', 'name' => 'Oklahoma'),  
            array('abbreviation' => 'OR', 'name' => 'Oregon'),  
            array('abbreviation' => 'PA', 'name' => 'Pennsylvania'),  
            array('abbreviation' => 'RI', 'name' => 'Rhode Island'),  
            array('abbreviation' => 'SC', 'name' => 'South Carolina'),  
            array('abbreviation' => 'SD', 'name' => 'South Dakota'),
            array('abbreviation' => 'TN', 'name' => 'Tennessee'),  
            array('abbreviation' => 'TX', 'name' => 'Texas'),  
            array('abbreviation' => 'UT', 'name' => 'Utah'),  
            array('abbreviation' => 'VT', 'name' => 'Vermont'),  
            array('abbreviation' => 'VA', 'name' => 'Virginia'),  
            array('abbreviation' => 'WA', 'name' => 'Washington'),  
            array('abbreviation' => 'WV', 'name' => 'West Virginia'),  
            array('abbreviation' => 'WI', 'name' => 'Wisconsin'),  
            array('abbreviation' => 'WY', 'name' => 'Wyoming')
        );
}