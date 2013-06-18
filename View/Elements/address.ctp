<h3>Address</h3>
<?php echo $this->Form->create('Address', array('class' => 'us address-form form-horizontal')); ?>
    <?php echo $this->Form->input('firstName', array('label' => array('class' => 'control-label'))); ?>
    <?php echo $this->Form->input('lastName', array('label' => array('class' => 'control-label'))); ?>
    <?php echo $this->Form->input('address 1', array('label' => array('class' => 'control-label'))); ?>
    <?php echo $this->Form->input('address 2', array('label' => array('class' => 'control-label'))); ?>
    <?php echo $this->Form->input('city', array('label' => array('class' => 'control-label'))); ?>
    <?php echo $this->Form->input('state', array('label' => array('class' => 'control-label'), 'options' => $states, 'empty' => 'Choose One')); ?>
    <?php echo $this->Form->input('zipcode', array('label' => array('class' => 'control-label'), 'class' => 'input-small', 'size' => '5')); ?>
<?php echo $this->Form->end(); ?>

<?php echo $this->Form->create('Address', array('class' => 'ca address-form form-horizontal')); ?>
    <?php echo $this->Form->input('firstName', array('label' => array('class' => 'control-label'))); ?>
    <?php echo $this->Form->input('lastName', array('label' => array('class' => 'control-label'))); ?>
    <?php echo $this->Form->input('address 1', array('label' => array('class' => 'control-label'))); ?>
    <?php echo $this->Form->input('address 2', array('label' => array('class' => 'control-label'))); ?>
    <?php echo $this->Form->input('city', array('label' => array('class' => 'control-label'))); ?>
    <?php echo $this->Form->input('province', array('label' => array('class' => 'control-label'), 'options' => $provinces, 'empty' => 'Choose One')); ?>
    <?php echo $this->Form->input('postalcode', array('label' => array('class' => 'control-label'), 'class' => 'input-small', 'size' => '7')); ?>
<?php echo $this->Form->end(); ?>
<?php echo $this->Form->create('Address', array('class' => 'other address-form form-horizontal')); ?>
    <?php echo $this->Form->input('firstName', array('label' => array('class' => 'control-label'))); ?>
    <?php echo $this->Form->input('lastName', array('label' => array('class' => 'control-label'))); ?>
    <?php echo $this->Form->input('address 1', array('label' => array('class' => 'control-label'))); ?>
    <?php echo $this->Form->input('address 2', array('label' => array('class' => 'control-label'))); ?>
    <?php echo $this->Form->input('city/locality', array('label' => array('class' => 'control-label'))); ?>
    <?php echo $this->Form->input('postalcode', array('label' => array('class' => 'control-label'), 'class' => 'input-small')); ?>
    <?php echo $this->Form->input('country', array('label' => array('class' => 'control-label'))); ?>
<?php echo $this->Form->end(); ?>