<?php
App::uses('AppModel', 'Model');
App::uses('ArraySource', 'Datasources.Model/Datasource');
App::uses('ConnectionManager', 'Model');

// Add new db config
ConnectionManager::create('region', array('datasource' => 'Datasources.ArraySource'));

/**
 * Regex Search & Replace to format data
   \'(\w\w)\'\=\>\'([A-Za-z ]+)\'\,?
   array('abbreviation' => '\1', 'name' => '\2'),
*/

/**
 * Region Model
 */
class Region extends AppModel {
    
    /**
    * Database Configuration
    *
    * @var string
    */
    public $useDbConfig = 'region';
    
    public $useTable = false;
    
    public $fields = array(
            'id' => array('type' => 'integer', 'key' => 'primary'),
            'abbreviation' => array('type' => 'string', 'null' => false),
            'name' => array('type' => 'string', 'null' => false),
            'country' => array('type' => 'string', 'null' => false),
    );
    
    public $records = array(
            array('abbreviation' => 'AL', 'name' => 'Alabama', 'country' => 'US'),  
            array('abbreviation' => 'AK', 'name' => 'Alaska', 'country' => 'US'),  
            array('abbreviation' => 'AZ', 'name' => 'Arizona', 'country' => 'US'),  
            array('abbreviation' => 'AR', 'name' => 'Arkansas', 'country' => 'US'),  
            array('abbreviation' => 'CA', 'name' => 'California', 'country' => 'US'),  
            array('abbreviation' => 'CO', 'name' => 'Colorado', 'country' => 'US'),  
            array('abbreviation' => 'CT', 'name' => 'Connecticut', 'country' => 'US'),  
            array('abbreviation' => 'DE', 'name' => 'Delaware', 'country' => 'US'),  
            array('abbreviation' => 'DC', 'name' => 'District Of Columbia', 'country' => 'US'),  
            array('abbreviation' => 'FL', 'name' => 'Florida', 'country' => 'US'),  
            array('abbreviation' => 'GA', 'name' => 'Georgia', 'country' => 'US'),  
            array('abbreviation' => 'HI', 'name' => 'Hawaii', 'country' => 'US'),  
            array('abbreviation' => 'ID', 'name' => 'Idaho', 'country' => 'US'),  
            array('abbreviation' => 'IL', 'name' => 'Illinois', 'country' => 'US'),  
            array('abbreviation' => 'IN', 'name' => 'Indiana', 'country' => 'US'),  
            array('abbreviation' => 'IA', 'name' => 'Iowa', 'country' => 'US'),  
            array('abbreviation' => 'KS', 'name' => 'Kansas', 'country' => 'US'),  
            array('abbreviation' => 'KY', 'name' => 'Kentucky', 'country' => 'US'),  
            array('abbreviation' => 'LA', 'name' => 'Louisiana', 'country' => 'US'),  
            array('abbreviation' => 'ME', 'name' => 'Maine', 'country' => 'US'),  
            array('abbreviation' => 'MD', 'name' => 'Maryland', 'country' => 'US'),  
            array('abbreviation' => 'MA', 'name' => 'Massachusetts', 'country' => 'US'),  
            array('abbreviation' => 'MI', 'name' => 'Michigan', 'country' => 'US'),  
            array('abbreviation' => 'MN', 'name' => 'Minnesota', 'country' => 'US'),  
            array('abbreviation' => 'MS', 'name' => 'Mississippi', 'country' => 'US'),  
            array('abbreviation' => 'MO', 'name' => 'Missouri', 'country' => 'US'),  
            array('abbreviation' => 'MT', 'name' => 'Montana', 'country' => 'US'),
            array('abbreviation' => 'NE', 'name' => 'Nebraska', 'country' => 'US'),
            array('abbreviation' => 'NV', 'name' => 'Nevada', 'country' => 'US'),
            array('abbreviation' => 'NH', 'name' => 'New Hampshire', 'country' => 'US'),
            array('abbreviation' => 'NJ', 'name' => 'New Jersey', 'country' => 'US'),
            array('abbreviation' => 'NM', 'name' => 'New Mexico', 'country' => 'US'),
            array('abbreviation' => 'NY', 'name' => 'New York', 'country' => 'US'),
            array('abbreviation' => 'NC', 'name' => 'North Carolina', 'country' => 'US'),
            array('abbreviation' => 'ND', 'name' => 'North Dakota', 'country' => 'US'),
            array('abbreviation' => 'OH', 'name' => 'Ohio', 'country' => 'US'),  
            array('abbreviation' => 'OK', 'name' => 'Oklahoma', 'country' => 'US'),  
            array('abbreviation' => 'OR', 'name' => 'Oregon', 'country' => 'US'),  
            array('abbreviation' => 'PA', 'name' => 'Pennsylvania', 'country' => 'US'),  
            array('abbreviation' => 'RI', 'name' => 'Rhode Island', 'country' => 'US'),  
            array('abbreviation' => 'SC', 'name' => 'South Carolina', 'country' => 'US'),  
            array('abbreviation' => 'SD', 'name' => 'South Dakota', 'country' => 'US'),
            array('abbreviation' => 'TN', 'name' => 'Tennessee', 'country' => 'US'),  
            array('abbreviation' => 'TX', 'name' => 'Texas', 'country' => 'US'),  
            array('abbreviation' => 'UT', 'name' => 'Utah', 'country' => 'US'),  
            array('abbreviation' => 'VT', 'name' => 'Vermont', 'country' => 'US'),  
            array('abbreviation' => 'VA', 'name' => 'Virginia', 'country' => 'US'),  
            array('abbreviation' => 'WA', 'name' => 'Washington', 'country' => 'US'),  
            array('abbreviation' => 'WV', 'name' => 'West Virginia', 'country' => 'US'),  
            array('abbreviation' => 'WI', 'name' => 'Wisconsin', 'country' => 'US'),  
            array('abbreviation' => 'WY', 'name' => 'Wyoming', 'country' => 'US'),
            array('abbreviation' => 'AB', 'name' => 'Alberta', 'country' => 'CA'), 
            array('abbreviation' => 'BC', 'name' => 'British Columbia', 'country' => 'CA'),
            array('abbreviation' => 'MB', 'name' => 'Manitoba', 'country' => 'CA'),
            array('abbreviation' => 'NB', 'name' => 'New Brunswick', 'country' => 'CA'),
            array('abbreviation' => 'NL', 'name' => 'Newfoundland and Labrador', 'country' => 'CA'),
            array('abbreviation' => 'NT', 'name' => 'Northwest Territories', 'country' => 'CA'),
            array('abbreviation' => 'NS', 'name' => 'Nova Scotia', 'country' => 'CA'),
            array('abbreviation' => 'NU', 'name' => 'Nunavut', 'country' => 'CA'),
            array('abbreviation' => 'ON', 'name' => 'Ontario', 'country' => 'CA'), 
            array('abbreviation' => 'PE', 'name' => 'Prince Edward Island', 'country' => 'CA'), 
            array('abbreviation' => 'QC', 'name' => 'Quebec', 'country' => 'CA'), 
            array('abbreviation' => 'SK', 'name' => 'Saskatchewan', 'country' => 'CA'), 
            array('abbreviation' => 'YT', 'name' => 'Yukon Territory', 'country' => 'CA')
        );

    public $labels = array(
        'US' => array(
            'region' => 'State', 
            'postal' => 'Zip Code',
        ),
        'CA' => array(
            'region' => 'Province', 
            'postal' => 'Postal Code'
        ),
        'OTHER' => array(
            'region' => '', 
            'postal' => 'Postal Code'
        ),
    );

    public $belongsTo = array(
        'Country' => array(
            'className' => 'Country',
            'foreignKey' => 'abbreviation',
        ));

    /**
     * Get options list for states/provinces
     * @param $country country code
     * @param $secondary The secondary country to fetch for different billing/shipping
     *
     */
    public function options($country, $secondary = '') {
        $countries = array($country);
        $fields = ['abbreviation', 'name'];

        if (!empty($secondary)) {
            $countries[] = $secondary;
            $fields[] = 'country';
        }

        $options = $this->find('list', array(
            'conditions' => array(
                'country' => $countries,
            ),
            'fields' => $fields,
        ));

        if (empty($secondary)) {
            return array('billing' => $options);
        }

        $countryNames = $this->Country->names($countries);

        return array(
            'billing' => array(
                $countryNames[$country] => $options[$country], 
                $countryNames[$secondary] => $options[$secondary],
            ),
            'shipping' => $options[$country]
        );
    }

    /**
     * Return form labels for state/region and zip/postal code
     * @param $country country code
     * @param $secondary The secondary country 
     * @return array
     */
    public function labels($country, $secondary = '') {
        if (empty($secondary)) {
            return array(
                'billing' => $this->labels[$country]
            );
        } 

        return array(
            'billing' => array(
                'region' => 'State or Province', 
                'postal' => 'Zip/Postal Code',
            ),
            'shipping' => $this->labels[$country],
        );
    }
}
