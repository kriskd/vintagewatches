<div class="us address-form">
    <?php echo $this->Form->input('firstName', array('label' => array('class' => 'control-label'))); ?>
    <?php echo $this->Form->input('lastName', array('label' => array('class' => 'control-label'))); ?>
    <?php echo $this->Form->input('address_line1', array('label' => array('class' => 'control-label', 'text' => 'Address 1'))); ?>
    <?php echo $this->Form->input('address_line2', array('label' => array('class' => 'control-label', 'text' => 'Address 2'))); ?>
    <?php echo $this->Form->input('address_city', array('label' => array('class' => 'control-label', 'text' => 'City'))); ?>
    <?php echo $this->Form->input('address_state', array('label' => array('class' => 'control-label', 'text' => 'State'), 'options' => $states, 'empty' => 'Choose One')); ?>
    <?php echo $this->Form->input('address_zip', array('label' => array('class' => 'control-label'), 'text' => 'Zip Code', 'class' => 'input-small', 'size' => '5')); ?>
    <?php echo $this->Form->input('address_country', array('type' => 'hidden', 'value' => 'US')); ?>
</div>
<div class="ca address-form">
    <?php echo $this->Form->input('firstName', array('label' => array('class' => 'control-label'))); ?>
    <?php echo $this->Form->input('lastName', array('label' => array('class' => 'control-label'))); ?>
    <?php echo $this->Form->input('address_line1', array('label' => array('class' => 'control-label', 'text' => 'Address 1'))); ?>
    <?php echo $this->Form->input('address_line2', array('label' => array('class' => 'control-label', 'text' => 'Address 2'))); ?>
    <?php echo $this->Form->input('address_city', array('label' => array('class' => 'control-label', 'text' => 'City'))); ?>
    <?php echo $this->Form->input('address_state', array('label' => array('class' => 'control-label', 'text' => 'Province'), 'options' => $provinces, 'empty' => 'Choose One')); ?>
    <?php echo $this->Form->input('address_zip', array('label' => array('class' => 'control-label', 'text' => 'Postal Code'), 'class' => 'input-small', 'size' => '7')); ?>
    <?php echo $this->Form->input('address_country', array('type' => 'hidden', 'value' => 'CA')); ?>
</div>
<div class="other address-form">
    <?php echo $this->Form->input('firstName', array('label' => array('class' => 'control-label'))); ?>
    <?php echo $this->Form->input('lastName', array('label' => array('class' => 'control-label'))); ?>
    <?php echo $this->Form->input('address_line1', array('label' => array('class' => 'control-label', 'text' => 'Address 1'))); ?>
    <?php echo $this->Form->input('address_line2', array('label' => array('class' => 'control-label', 'text' => 'Address 2'))); ?>
    <?php echo $this->Form->input('address_city', array('label' => array('class' => 'control-label', 'text' => 'City/Locality'))); ?>
    <?php echo $this->Form->input('address_zip', array('label' => array('class' => 'control-label', 'text' => 'Postal Code'), 'class' => 'input-small')); ?>
    <?php echo $this->Form->input('address_country', array('label' => array('class' => 'control-label', 'text' => 'Country'))); ?>
</div>
