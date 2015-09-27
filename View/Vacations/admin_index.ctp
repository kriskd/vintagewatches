<div class="vacations index">
	<h2><?php echo __('Vacations'); ?></h2>
	<table class="table">
	<thead>
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('start'); ?></th>
			<th><?php echo $this->Paginator->sort('end'); ?></th>
			<th><?php echo $this->Paginator->sort('message'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($vacations as $vacation): ?>
	<tr>
		<td><?php echo h($vacation['Vacation']['id']); ?>&nbsp;</td>
		<td><?php echo h($vacation['Vacation']['start']); ?>&nbsp;</td>
		<td><?php echo h($vacation['Vacation']['end']); ?>&nbsp;</td>
		<td><?php echo h($vacation['Vacation']['message']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $vacation['Vacation']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $vacation['Vacation']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $vacation['Vacation']['id']), array(), __('Are you sure you want to delete # %s?', $vacation['Vacation']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</tbody>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
	));
	?>	</p>
	<div class="paging">
	<?php
		echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
		echo $this->Paginator->numbers(array('separator' => ''));
		echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
	?>
	</div>
</div>
