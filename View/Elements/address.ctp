<div class="us address-form">
    <?php echo $this->Form->input('firstName', array('label' => array('class' => 'control-label'))); ?>
    <?php echo $this->Form->input('lastName', array('label' => array('class' => 'control-label'),
                                                    'data-stripe' => 'name')); ?>
    <?php echo $this->Form->input('address1', array('label' => array('class' => 'control-label',
                                                                          'text' => 'Address 1'),
                                                         'data-stripe' => 'address_line1')); ?>
    <?php echo $this->Form->input('address2', array('label' => array('class' => 'control-label',
                                                                          'text' => 'Address 2'),
                                                         'data-stripe' => 'address_line2')); ?>
    <?php echo $this->Form->input('city', array('label' => array('class' => 'control-label',
                                                                         'text' => 'City'),
                                                        'data-stripe' => 'address_city')); ?>
    <?php echo $this->Form->input('state', array('label' => array('class' => 'control-label',
                                                                          'text' => 'State'),
                                                         'data-stripe' => 'address_state',
                                                         'options' => $states, 'empty' => 'Choose One')); ?>
    <?php echo $this->Form->input('postalCode', array('label' => array('class' => 'control-label',
                                                                       'text' => 'Zip Code'),
                                                       'class' => 'input-small', 'size' => '5',
                                                       'data-stripe' => 'address_zip')); ?>
    <?php echo $this->Form->input('country', array('type' => 'hidden', 'value' => 'US',
                                                           'data-stripe' => 'address_country')); ?>
</div>
<div class="ca address-form">
    <?php echo $this->Form->input('firstName', array('label' => array('class' => 'control-label'))); ?>
    <?php echo $this->Form->input('lastName', array('label' => array('class' => 'control-label'),
                                                    'data-stripe' => 'name')); ?>
    <?php echo $this->Form->input('address1', array('label' => array('class' => 'control-label',
                                                                          'text' => 'Address 1'),
                                                         'data-stripe' => 'address_line1')); ?>
    <?php echo $this->Form->input('address2', array('label' => array('class' => 'control-label',
                                                                          'text' => 'Address 2'),
                                                         'data-stripe' => 'address_line2')); ?>
    <?php echo $this->Form->input('city', array('label' => array('class' => 'control-label',
                                                                         'text' => 'City'),
                                                        'data-stripe' => 'address_city')); ?>
    <?php echo $this->Form->input('state', array('label' => array('class' => 'control-label',
                                                                          'text' => 'Province'),
                                                         'data-stripe' => 'address_state',
                                                         'options' => $provinces, 'empty' => 'Choose One')); ?>
    <?php echo $this->Form->input('address_zip', array('label' => array('class' => 'control-label',
                                                                        'text' => 'Postal Code'),
                                                       'class' => 'input-small', 'size' => '7',
                                                       'data-stripe' => 'address_zip')); ?>
    <?php echo $this->Form->input('country', array('type' => 'hidden', 'value' => 'CA',
                                                           'data-stripe' => 'address_country')); ?>
</div>
<div class="other address-form">
    <?php echo $this->Form->input('firstName', array('label' => array('class' => 'control-label'))); ?>
    <?php echo $this->Form->input('lastName', array('label' => array('class' => 'control-label'))); ?>
    <?php echo $this->Form->input('address_line1', array('label' => array('class' => 'control-label', 'text' => 'Address 1'))); ?>
    <?php echo $this->Form->input('address_line2', array('label' => array('class' => 'control-label', 'text' => 'Address 2'))); ?>
    <?php echo $this->Form->input('address_city', array('label' => array('class' => 'control-label', 'text' => 'City/Locality'))); ?>
    <?php echo $this->Form->input('address_zip', array('label' => array('class' => 'control-label', 'text' => 'Postal Code'), 'class' => 'input-small')); ?>
    <?php echo $this->Form->input('countryName', array('label' => array('class' => 'control-label', 'text' => 'Country'))); ?>
    <?php echo $this->Form->input('country', array('type' => 'hidden', 'value' => 'CA',
                                                       'data-stripe' => 'address_country')); ?>
</div>
