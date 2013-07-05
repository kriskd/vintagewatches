<?php echo $this->Form->create(); ?>
<?php echo $this->Form->input('Address.billing.firstName', array('required' => false)); ?>
<?php echo $this->Form->input('Address.billing.lastName', array('required' => false)); ?>
<?php echo $this->Form->input('Address.shipping.firstName', array('required' => false)); ?>
<?php echo $this->Form->input('Address.shipping.lastName', array('required' => false)); ?>
<?php echo $this->Form->end('submit'); ?>