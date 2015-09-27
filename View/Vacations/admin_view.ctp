<div class="vacations view">
<h2><?php echo __('Vacation'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($vacation['Vacation']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Start'); ?></dt>
		<dd>
			<?php echo h($vacation['Vacation']['start']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('End'); ?></dt>
		<dd>
			<?php echo h($vacation['Vacation']['end']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Message'); ?></dt>
		<dd>
			<?php echo h($vacation['Vacation']['message']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Vacation'), array('action' => 'edit', $vacation['Vacation']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Vacation'), array('action' => 'delete', $vacation['Vacation']['id']), array(), __('Are you sure you want to delete # %s?', $vacation['Vacation']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Vacations'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Vacation'), array('action' => 'add')); ?> </li>
	</ul>
</div>
