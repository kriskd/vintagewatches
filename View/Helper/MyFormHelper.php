<?php
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
            if(isset($this->_inputDefaults['label']) && isset($options['label'])){
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

}
