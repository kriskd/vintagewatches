<div class="watches form">
	<?php echo $this->Element('form_watch', array('action' => 'Add')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('List Watches'), array('action' => 'index')); ?></li>
	</ul>
</div>
