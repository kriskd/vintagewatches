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
                                  'company' => array('label' => 'Company'),
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
        $this->inputDefaults(array('label' => array('class' => 'control-label col-xs-12 col-sm-4 col-md-4 col-lg-4'),
                                    'div' => 'form-group row',
                                    'class' => 'form-control',
                                    'between' => '<div class="clearfix"><div class="col-xs-11 col-sm-7 col-md-7 col-lg-7">',
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
                if ($stripe == true) {
                    $tooltip = $this->Html->link('<i class="glyphicon glyphicon-question-sign"></i>', '#', array(
                                                                    'title' => 'Enter any portion of the country name and select your country from the options that appear.',
                                                                    'class' => 'launch-tooltip',
                                                                    'data-toggle' => 'tooltip',
                                                                    'data-placement' => 'top',
                                                                    'escape' => false
                                                                )
                                               );
                    $this->nameToOptionsMap['countryName'] = array('label' => 'Country ' . $tooltip, 'placeholder' => 'Full Name, No Abbreviations.');
                }
                $this->nameToOptionsMap['country'] = array('type' => 'hidden', 'stripe' => 'address_country');
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
            $common = array('options', 'empty', 'class', 'size', 'value', 'type', 'placeholder');
            foreach($common as $item){
                if(isset($attrs[$item])){
                    $options[$item] = $attrs[$item];
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
    
    /*
     * Pass in the Order, return a checkbox to delete the shipping address
     */
    public function shippingDelete($order)
    {
        $shippingAddress = array_filter($order['Address'], function($item){
            return strcasecmp($item['type'], 'shipping')==0;
        });

        $current = current($shippingAddress);
        $id = $current['id'];
        return $this->checkbox('delete_shipping_address', array('value' => $id, 'hiddenField' => false));
    }
    
    public function ccd($payment_type = '')
    {
        $options = array(
                        'name' => false,
                        'data-stripe' => 'number',
                        'autocomplete' => 'off',
                        'placeHolder' => 'Card Number',
                        'class' => 'card-number form-control',
                        'label' => array('text' => 'Card Number<br /><small>(no spaces or hypens)</small>',
                                         'class' => 'control-label col-xs-11 col-sm-4 col-md-4 col-lg-4'
                                         ),
                        'div' => array('class' => 'card-number-div input required'),
                        'required' => 'required',
                        'between' => '<div class="col-xs-11 col-sm-7 col-md-7 col-lg-7">',
                        'after' => '</div>'
                    );
        
        switch ($payment_type) {
            case 'invoice':
                $options['label']['class'] = 'control-label col-xs-11 col-sm-11 col-md-11 col-lg-11';
                $options['between'] = '<div class="col-xs-11 col-sm-11 col-md-11 col-lg-11">';
        }
        
        return $this->input('Card.number', $options);
    }
    
    public function cvc($payment_type = '')
    {
        $options = array(
                        'name' => false,
                        'data-stripe' => 'cvc',
                        'autocomplete' => 'off',
                        'placeHolder' => 'CVC',
                        'class' => 'card-cvc form-control',
                        'label' => array('text' => 'CVC <a class="launch-tooltip"
                                                    data-toggle="tooltip" data-placement="top"
                                                    title="The CVC is the three-digit number
                                                    that appears on the reverse side of your
                                                    credit/debit card.">
                                                    <span class="glyphicon glyphicon-question-sign"></span>
                                                    </a>',
                                         'class' => 'control-label col-xs-11 col-sm-4 col-md-4 col-lg-4'
                                         ),
                        'div' => array('class' => 'cvc-div input required'),
                        'required' => 'required',
                        'between' => '<div class="col-xs-11 col-sm-7 col-md-7 col-lg-7">',
                        'after' => '</div>'
                    );
        
        switch ($payment_type) {
            case 'invoice':
                $options['label']['class'] = 'control-label col-xs-11 col-sm-11 col-md-11 col-lg-11';
                $options['between'] = '<div class="col-xs-11 col-sm-11 col-md-11 col-lg-11">';
        }

        return $this->input('Card.cvc', $options);
    }
    
    public function expy($payment_type = '')
    {
        switch ($payment_type) {
            case 'invoice':
                $div = 'col-xxs-6 col-xs-6 col-sm-6 col-md-6 col-lg-6';
                break;
            default:
                $div = 'col-xxs-6 col-xs-6 col-sm-3 col-md-3 col-lg-3';
        }
        
        $month = $this->input('Card.month', array(
                                            'name' => false,
                                            'empty' => 'MM',
                                            'options' => $this->_months(),
                                            'data-stripe' => 'exp-month',
                                            'class' => 'form-control card-expiry-month',
                                            'label' => false,
                                            'multiple' => false,
                                            'div' => $div,
                                            ));
                                        
        $year = $this->input('Card.year', array(
                                            'name' => false,
                                            'empty' => 'Year',
                                            'options' => $this->_years(),
                                            'data-stripe' => 'exp-year',
                                            'placeHolder' => 'Year',
                                            'class' => 'card-expiry-year form-control',
                                            'label' => false,
                                            'multiple' => false,
                                            'div' => $div,
                                            ));
                                            
        return $month . $year;
    }
    
    /**
     * Disable editing line item or shipping if invoice is paid.
     */
    public function invoiceItem($fieldName, $options = array(), $invoice = array())
    {
        if (isset($invoice['Payment']) && $invoice['Payment']['stripe_paid'] == 1) {
            $options['disabled'] = 'disabled';
        }
        return parent::input($fieldName, $options);
    }

    public function inputSpan($fieldName, $options) {
        $element = $this->input($fieldName, $options);
        $element = str_replace('<div', '<span', $element);
        $element = str_replace('</div>', '</span>', $element);
        return $element;
    }    

    protected function _months() {
        $formatted = array_map(function($num) { return sprintf('%02d', $num); }, range(1,12));
        return array_combine(range(1,12), $formatted);
    }
    
    protected function _years()
    {
        $year = date('Y'); 
        for($i=date('Y'); $i<=date('Y')+10; $i++){
            $years[$i] = $i;
        }
        return $years;
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
        $this->nameToOptionsMap['country'] = array('type' => 'hidden', 'stripe' => 'address_country');
    }
}
