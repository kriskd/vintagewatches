<?php $this->Form->inputDefaults(array('label' => array('class' => 'control-label'))); ?>
<?php $form = $this->Form->input('firstName'); ?>
<?php $form .= $this->Form->input('lastName', array('data-stripe' => 'name')); ?>
<?php $form .= $this->Form->input('address1', array('label' => array('text' => 'Address 1'),
                                                     'data-stripe' => 'address_line1')); ?>                                      
<?php $form .= $this->Form->input('address2', array('label' => array('text' => 'Address 2'),
                                                     'data-stripe' => 'address_line2')); ?>
<?php $form .= $this->Form->input('city', array('label' => array('text' => 'City'),
                                                    'data-stripe' => 'address_city')); ?>
<?php switch ($data['country']): ?>
<?php case 'us': ?>
    <?php $form .= $this->Form->input('state', array('label' => array('text' => 'State'),
                                                     'data-stripe' => 'address_state',
                                                        'options' => $states, 'empty' => 'Choose One')); ?>
    <?php $form .= $this->Form->input('postalCode', array('label' => array('text' => 'Zip Code'),
                                                      'class' => 'input-small', 'size' => '5',
                                                      'data-stripe' => 'address_zip')); ?>
    <?php $form .= $this->Form->input('country', array('type' => 'hidden', 'value' => 'US',
                                                          'data-stripe' => 'address_country')); ?>
    <?php break; ?>
<?php case 'ca': ?>
    <?php $form .= $this->Form->input('state', array('label' => array('text' => 'Province'),
                                                     'data-stripe' => 'address_state',
                                                     'options' => $provinces, 'empty' => 'Choose One')); ?>
    <?php $form .= $this->Form->input('postalCode', array('label' => array('text' => 'Postal Code'),
                                                       'class' => 'input-small', 'size' => '7',
                                                       'data-stripe' => 'address_zip')); ?>
    <?php $form .= $this->Form->input('country', array('type' => 'hidden', 'value' => 'CA',
                                                           'data-stripe' => 'address_country')); ?>
    <?php break; ?>
<?php case 'other': ?>
    <?php $form .= $this->Form->input('postalCode', array('label' => array('text' => 'Postal Code'),
                                                   'class' => 'input-small', 'size' => '7',
                                                   'data-stripe' => 'address_zip')); ?>
    <?php $form .= $this->Form->input('countryName', array('label' => array('text' => 'Country'))); ?>
    <?php $form .= $this->Form->input('country', array('type' => 'hidden', 'value' => '',
                                                       'data-stripe' => 'address_country')); ?>
<?php break; ?>
<?php endswitch; ?>

<?php $shipping = $this->Number->currency($data['shipping'], 'USD'); ?>
<?php $totalFormatted = $this->Number->currency($data['total']); ?>
<?php echo json_encode(compact('shipping', 'totalFormatted', 'form')); ?>
