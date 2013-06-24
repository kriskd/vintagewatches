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
    
    public function addressForm($prefix, $country, $states = null, $provinces = null)
    {   
        $this->inputDefaults(array('label' => array('class' => 'control-label'))); 
        $form = $this->input($prefix . 'FirstName', array('label' => array('text' =>'First Name'))); 
        $form .= $this->input($prefix . 'LastName', array('label' => array('text' =>'Last Name',
                                                                                   'data-stripe' => 'name'))); 
        $form .= $this->input($prefix . 'Address1', array('label' => array('text' => 'Address 1'),
                                                            'data-stripe' => 'address_line1'));                                       
        $form .= $this->input($prefix . 'Address2', array('label' => array('text' => 'Address 2'),
                                                            'data-stripe' => 'address_line2')); 
        $form .= $this->input($prefix . 'City', array('label' => array('text' => 'City'),
                                                           'data-stripe' => 'address_city')); 
        switch ($country){
            case 'us': 
                $form .= $this->input($prefix . 'State', array('label' => array('text' => 'State'),
                                                                'data-stripe' => 'address_state',
                                                                   'options' => $states, 'empty' => 'Choose One')); 
                $form .= $this->input($prefix . 'PostalCode', array('label' => array('text' => 'Zip Code'),
                                                                 'class' => 'input-small', 'size' => '5',
                                                                 'data-stripe' => 'address_zip')); 
                $form .= $this->input($prefix . 'Country', array('type' => 'hidden', 'value' => 'US',
                                                                     'data-stripe' => 'address_country')); 
                break; 
            case 'ca': 
                $form .= $this->input($prefix . 'State', array('label' => array('text' => 'Province'),
                                                                'data-stripe' => 'address_state',
                                                                'options' => $provinces, 'empty' => 'Choose One')); 
                $form .= $this->input($prefix . 'PostalCode', array('label' => array('text' => 'Postal Code'),
                                                                  'class' => 'input-small', 'size' => '7',
                                                                  'data-stripe' => 'address_zip')); 
                $form .= $this->input($prefix . 'Country', array('type' => 'hidden', 'value' => 'CA',
                                                                      'data-stripe' => 'address_country')); 
                break; 
            case 'other': 
                $form .= $this->input($prefix . 'PostalCode', array('label' => array('text' => 'Postal Code'),
                                                              'class' => 'input-small', 'size' => '7',
                                                              'data-stripe' => 'address_zip')); 
                $form .= $this->input($prefix . 'CountryName', array('label' => array('text' => 'Country'),
                                                                     'class' => 'country-autocomplete')); 
                $form .= $this->input($prefix . 'Country', array('type' => 'hidden', 'value' => '',
                                                                  'data-stripe' => 'address_country')); 
            break; 
        } 
        return $form;
    }
}
