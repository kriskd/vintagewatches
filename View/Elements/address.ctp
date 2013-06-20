<h3>Address</h3>
<?php echo $this->Form->create(false, array('class' => 'us address-form form-horizontal')); ?>
    <?php echo $this->Form->input('firstName', array('label' => array('class' => 'control-label'))); ?>
    <?php echo $this->Form->input('lastName', array('label' => array('class' => 'control-label'))); ?>
    <?php echo $this->Form->input('address_line1', array('label' => array('class' => 'control-label', 'text' => 'Address 1'))); ?>
    <?php echo $this->Form->input('address_line2', array('label' => array('class' => 'control-label', 'text' => 'Address 2'))); ?>
    <?php echo $this->Form->input('address_city', array('label' => array('class' => 'control-label', 'text' => 'City'))); ?>
    <?php echo $this->Form->input('address_state', array('label' => array('class' => 'control-label', 'text' => 'State'), 'options' => $states, 'empty' => 'Choose One')); ?>
    <?php echo $this->Form->input('address_zip', array('label' => array('class' => 'control-label'), 'text' => 'Zip Code', 'class' => 'input-small', 'size' => '5')); ?>
    <?php echo $this->Form->input('address_country', array('type' => 'hidden', 'value' => 'US')); ?>
<?php echo $this->Form->end(); ?>

<?php echo $this->Form->create('Address', array('class' => 'ca address-form form-horizontal')); ?>
    <?php echo $this->Form->input('firstName', array('label' => array('class' => 'control-label'))); ?>
    <?php echo $this->Form->input('lastName', array('label' => array('class' => 'control-label'))); ?>
    <?php echo $this->Form->input('address_line1', array('label' => array('class' => 'control-label', 'text' => 'Address 1'))); ?>
    <?php echo $this->Form->input('address_line2', array('label' => array('class' => 'control-label', 'text' => 'Address 2'))); ?>
    <?php echo $this->Form->input('address_city', array('label' => array('class' => 'control-label', 'text' => 'City'))); ?>
    <?php echo $this->Form->input('address_state', array('label' => array('class' => 'control-label', 'text' => 'Province'), 'options' => $provinces, 'empty' => 'Choose One')); ?>
    <?php echo $this->Form->input('address_zip', array('label' => array('class' => 'control-label', 'text' => 'Postal Code'), 'class' => 'input-small', 'size' => '7')); ?>
    <?php echo $this->Form->input('address_country', array('type' => 'hidden', 'value' => 'CA')); ?>
<?php echo $this->Form->end(); ?>
<?php echo $this->Form->create('Address', array('class' => 'other address-form form-horizontal')); ?>
    <?php echo $this->Form->input('firstName', array('label' => array('class' => 'control-label'))); ?>
    <?php echo $this->Form->input('lastName', array('label' => array('class' => 'control-label'))); ?>
    <?php echo $this->Form->input('address_line1', array('label' => array('class' => 'control-label', 'text' => 'Address 1'))); ?>
    <?php echo $this->Form->input('address_line2', array('label' => array('class' => 'control-label', 'text' => 'Address 2'))); ?>
    <?php echo $this->Form->input('address_city', array('label' => array('class' => 'control-label', 'text' => 'City/Locality'))); ?>
    <?php echo $this->Form->input('address_zip', array('label' => array('class' => 'control-label', 'text' => 'Postal Code'), 'class' => 'input-small')); ?>
    <?php echo $this->Form->input('address_country', array('label' => array('class' => 'control-label', 'text' => 'Country'))); ?>
<?php echo $this->Form->end(); ?>