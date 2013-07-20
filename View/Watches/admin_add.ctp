<div class="watches form">
<?php echo $this->Form->create('Watch'); ?>
	<fieldset>
		<legend><?php echo __('Add Watch'); ?></legend>
	<?php
		echo $this->Form->input('stockId');
		echo $this->Form->input('price');
		echo $this->Form->input('name');
		echo $this->Form->input('description');
		echo $this->Form->input('active');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Watches'), array('action' => 'index')); ?></li>
	</ul>
</div>
