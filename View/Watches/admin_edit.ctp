<div class="watches form">
<?php echo $this->Form->create('Watch'); ?>
	<fieldset>
		<legend><?php echo __('Edit Watch'); ?></legend>
	<?php
		echo $this->Form->input('id');
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

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Watch.id')), null, __('Are you sure you want to delete # %s?', $this->Form->value('Watch.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Watches'), array('action' => 'index')); ?></li>
	</ul>
</div>
