<?php
App::uses('AppHelper', 'View/Helper');
App::uses('FormHelper', 'View/Helper');

class MyFormHelper extends FormHelper
{
/**
 * Generates input options array
 * Override to merge label options set per element with inputDefaults instead of overriding
 *
 * @param type $options
 * @return array Options
 */
    protected function _parseOptions($options) {
        //Grab the inputDefaults member var put in local var for safe keeping
        $inputDefaults = $this->_inputDefaults;
        
        //If there are options for label from element and inputDefaults, merge them
        //Unset label in member var so it doesn't get overridden later
        if(isset($this->_inputDefaults['label']) && is_array($this->_inputDefaults['label'])
           && isset($options['label']) && is_array($options['label'])){
            $options['label'] = array_merge($options['label'], $this->_inputDefaults['label']);
            unset($this->_inputDefaults['label']);
        }
        
        //Original code from parent
        $options = array_merge(
                array('before' => null, 'between' => null, 'after' => null, 'format' => null),
                $this->_inputDefaults,
                $options
        );
        
        //Return original value of inputDefaults to its member var
        $this->_inputDefaults = $inputDefaults;
        
        return parent::_parseOptions($options);
    }
    
    public function addressForm($prefix, $country, $stripe = false, $required = false)
    {
        $requiredAttrs = array('firstName', 'lastName', 'address1', 'city', 'state', 'postalCode', 'countryName', 'country');
        $this->inputDefaults(array('label' => array('class' => 'control-label')));
        $nameToOptionsMap = array('firstName' => array('label' => 'First Name'),
                                  'lastName' => array('label' => 'Last Name', 'stripe' => 'name'),
                                  'address1' => array('label' => 'Address 1', 'stripe' => 'address_line1'),
                                  'address2' => array('label' => 'Address 2', 'stripe' =>'address_line2'),
                                  'city' => array('label' => 'City', 'stripe' => 'address_city'),
                                 );
        switch ($country){
            case 'us':
                $states = $this->_getStates();
                $nameToOptionsMap['state'] = array('label' => 'State', 'stripe' => 'address_state', 'options' => $states, 'empty' => 'Choose One');
                $nameToOptionsMap['postalCode'] = array('label' => 'Zip Code', 'stripe' => 'address_zip', 'class' => 'input-small', 'size' => '5');
                $nameToOptionsMap['country'] = array('type' => 'hidden', 'stripe' => 'address_country', 'value' => 'US');
                break;
            case 'ca':
                $provinces = $this->_getCanadianProvinces();
                $nameToOptionsMap['state'] = array('label' => 'Province', 'stripe' => 'address_state', 'options' => $provinces, 'empty' => 'Choose One');
                $nameToOptionsMap['postalCode'] = array('label' => 'Postal Code', 'stripe' => 'address_zip', 'class' => 'input-small', 'size' => '7',);
                $nameToOptionsMap['country'] = array('type' => 'hidden', 'stripe' => 'address_country', 'value' => 'CA');
                break;
            case 'other':
                $nameToOptionsMap['postalCode'] = array('label' => 'Postal Code', 'stripe' => 'address_zip', 'class' => 'input-small', 'size' => '7');
                $nameToOptionsMap['countryName'] = array('label' => 'Country', 'stripe' => 'address_country');
                $nameToOptionsMap['country'] = array('type' => 'hidden', 'stripe' => 'address_country', 'value' => '');
                break;
        }
        
        $form = '';
        foreach($nameToOptionsMap as $name => $attrs){
            //Label
            if(isset($attrs['label'])){
                $options = array('label' => array('text' => $attrs['label']));
            }
            //Stripe
            if(isset($attrs['stripe']) && $stripe == true){
                $options['data-stripe'] = $attrs['stripe'];
            }
            //Required
            if(in_array($name, $requiredAttrs) && $required == false){
                $options['required'] = false;
            }
            //All others
            $common = array('options', 'empty', 'class', 'size', 'value', 'type');
            foreach($common as $item){
                if(isset($attrs[$item])){
                    $options[$item] = $attrs[$item];
                }
            }

            $form .= $this->input($prefix . $name, $options);
        }
 
        return $form;
    }
        
    protected function _getStates()
    {
        return array('AL'=>'Alabama',  
		'AK'=>'Alaska',  
                'AZ'=>'Arizona',  
                'AR'=>'Arkansas',  
                'CA'=>'California',  
                'CO'=>'Colorado',  
                'CT'=>'Connecticut',  
                'DE'=>'Delaware',  
                'DC'=>'District Of Columbia',  
                'FL'=>'Florida',  
                'GA'=>'Georgia',  
                'HI'=>'Hawaii',  
                'ID'=>'Idaho',  
                'IL'=>'Illinois',  
                'IN'=>'Indiana',  
                'IA'=>'Iowa',  
                'KS'=>'Kansas',  
                'KY'=>'Kentucky',  
                'LA'=>'Louisiana',  
                'ME'=>'Maine',  
                'MD'=>'Maryland',  
                'MA'=>'Massachusetts',  
                'MI'=>'Michigan',  
                'MN'=>'Minnesota',  
                'MS'=>'Mississippi',  
                'MO'=>'Missouri',  
                'MT'=>'Montana',
                'NE'=>'Nebraska',
                'NV'=>'Nevada',
                'NH'=>'New Hampshire',
                'NJ'=>'New Jersey',
                'NM'=>'New Mexico',
                'NY'=>'New York',
                'NC'=>'North Carolina',
                'ND'=>'North Dakota',
                'OH'=>'Ohio',  
                'OK'=>'Oklahoma',  
                'OR'=>'Oregon',  
                'PA'=>'Pennsylvania',  
                'RI'=>'Rhode Island',  
                'SC'=>'South Carolina',  
                'SD'=>'South Dakota',
                'TN'=>'Tennessee',  
                'TX'=>'Texas',  
                'UT'=>'Utah',  
                'VT'=>'Vermont',  
                'VA'=>'Virginia',  
                'WA'=>'Washington',  
                'WV'=>'West Virginia',  
                'WI'=>'Wisconsin',  
                'WY'=>'Wyoming');
    }
    
    protected function _getCanadianProvinces()
    {
        return array(
	    'AB'=>'Alberta', 
            'BC'=>'British Columbia',
	    'MB'=>'Manitoba',
	    'NB'=>'New Brunswick',
	    'NL'=>'Newfoundland and Labrador',
	    'NT'=>'Northwest Territories',
	    'NS'=>'Nova Scotia',
	    'NU'=>'Nunavut',
            'ON'=>'Ontario', 
            'PE'=>'Prince Edward Island', 
            'QC'=>'Quebec', 
            'SK'=>'Saskatchewan', 
            'YT'=>'Yukon Territory');
    }
}
