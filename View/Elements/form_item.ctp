<?php echo $this->Form->create('Item'); ?>
<?php $this->Form->inputDefaults(['class' => 'form-control']); ?>
<?php if (strcasecmp($action, 'edit')==0): ?>
    <?php echo $this->Form->input('Item.id', array('type' => 'hidden')); ?>
<?php endif; ?>
	<fieldset>
		<legend><?= $action ?> <?php echo __('Item'); ?></legend>
	<?php
		echo $this->Form->input('description');
        echo $this->Form->input('active', ['class' => 'checkbox-inline']);
		echo $this->Form->input('quantity');
		echo $this->Form->input('price');
		echo $this->Form->input('Shipping');
	?>
	</fieldset>
<?php echo $this->Form->end(['text' => __('Submit'), 'class' => 'btn btn-primary']); ?>
