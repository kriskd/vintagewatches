<?php echo $this->Form->create('Shipping'); ?>
<?php $this->Form->inputDefaults(['class' => 'form-control']); ?>
<?php if (strcasecmp($action, 'edit')==0): ?>
    <?php echo $this->Form->input('Shipping.id', array('type' => 'hidden')); ?>
<?php endif; ?>
	<fieldset>
        <legend><?= $action ?> <?php echo __('Shipping Option'); ?></legend>
	<?php
		echo $this->Form->input('description');
		echo $this->Form->input('amount');
		echo $this->Form->input('Zone');
		echo $this->Form->input('Item');
	?>
	</fieldset>
<?php echo $this->Form->end(['text' => __('Submit'), 'class' => 'btn btn-primary']); ?>
