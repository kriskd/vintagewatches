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
    
    protected $nameToOptionsMap;
    
    protected function setNameToOptionsMap()
    {
        $this->nameToOptionsMap = array('firstName' => array('label' => 'First Name'),
                                  'lastName' => array('label' => 'Last Name', 'stripe' => 'name'),
                                  'address1' => array('label' => 'Address 1', 'stripe' => 'address_line1'),
                                  'address2' => array('label' => 'Address 2', 'stripe' =>'address_line2'),
                                  'city' => array('label' => 'City', 'stripe' => 'address_city'),
                                 );
    }
    
    /**
     * @param enum $type 'billing or 'shipping'
     * @param array $data Form data: country, statesProvinces, values, errors
     */
    public function addressForm($type, $data, $stripe = false, $required = false)
    {   
        $country = $data['country'];
        $statesProvinces = $data['statesProvinces'];
        $values = isset($data['values'][$type]) ? $data['values'][$type] : null; 
        $errors = isset($data['errors'][$type]) ? $data['errors'][$type] : null; 
        
        $this->setNameToOptionsMap();
        $requiredAttrs = array('firstName', 'lastName', 'address1', 'city', 'state', 'postalCode', 'countryName', 'country');
        $this->inputDefaults(array('label' => array('class' => 'control-label col-xs-2'),
                                    'div' => 'form-group',
                                    'class' => 'form-control',
                                    'between' => '<div class="clearfix"><div class="col-xs-6">',
                                    'after' => '</div></div>'
                                    )
                            );

        $states = $statesProvinces['states'];
        $provinces = $statesProvinces['provinces'];
        switch ($country){
            case 'us':
                $this->nameToOptionsMap['state'] = array('label' => 'State',
                                                         'stripe' => 'address_state',
                                                         'class' => 'form-control',
                                                         'options' => $states,
                                                         'empty' => 'Choose One',
                                                         );
                $this->nameToOptionsMap['postalCode'] = array('label' => 'Zip Code',
                                                              'stripe' => 'address_zip',
                                                              'class' => 'form-control',
                                                              'size' => '5',
                                                              );
                $this->nameToOptionsMap['country'] = array('type' => 'hidden', 'stripe' => 'address_country', 'value' => 'US');
                break;
            case 'ca':
                $this->nameToOptionsMap['state'] = array('label' => 'Province',
                                                         'stripe' => 'address_state',
                                                         'class' => 'form-control',
                                                         'options' => $provinces,
                                                         'empty' => 'Choose One',
                                                         );
                $this->nameToOptionsMap['postalCode'] = array('label' => 'Postal Code',
                                                              'stripe' => 'address_zip',
                                                              'class' => 'form-control',
                                                              'size' => '7',
                                                              );
                $this->nameToOptionsMap['country'] = array('type' => 'hidden', 'stripe' => 'address_country', 'value' => 'CA');
                break;
            case 'us-ca':
                $options = array('U.S.' => $states, 'Canada' => $provinces);
                $this->_getCommonFields($options);
                break;
            case 'ca-us':
                $options = array('Canada' => $provinces, 'U.S.' => $states);
                $this->_getCommonFields($options);
                break;
            case 'other':
                $this->nameToOptionsMap['postalCode'] = array('label' => 'Postal Code',
                                                              'stripe' => 'address_zip',
                                                              'class' => 'form-control',
                                                              'size' => '7',
                                                              );
                $this->nameToOptionsMap['countryName'] = array('label' => 'Country');
                $this->nameToOptionsMap['country'] = array('type' => 'hidden', 'stripe' => 'address_country', 'value' => '');
                break;
        }
        
        $form = '';
        foreach($this->nameToOptionsMap as $name => $attrs){
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
            //Values
            if(!empty($values)){
                foreach($values as $key => $value){
                    if(strcasecmp($name, $key)==0){
                        $options['value'] = $value;
                    }
                }
            }
            
            if(isset($errors[$name])){
                foreach($errors[$name] as $error){
                    //Add errors manually since form is loaded via ajax
                    $errorClass = '<div class="error-message">' . $error . '</div>';
                    //Add 2 closing divs since this will over write the after key in inputDefaults
                    $options['after'] = '</div></div>' . $errorClass;
                }
            }
            $form .= $this->input('Address.' . $type . '.' . $name, $options); 
        }
 
        return $form;
    }
    
    protected function _getCommonFields($options)
    {
        $this->nameToOptionsMap['state'] = array('label' => 'State or Province',
                                                 'stripe' => 'address_state',
                                                 'options' => $options,
                                                 'empty' => 'Choose One',
                                                 'class' => 'us-ca form-control');
        $this->nameToOptionsMap['postalCode'] = array('label' => 'Zip/Postal Code',
                                                      'stripe' => 'address_zip',
                                                      'class' => 'form-control',
                                                      );
        $this->nameToOptionsMap['country'] = array('type' => 'hidden', 'stripe' => 'address_country', 'value' => '');
    }
}
