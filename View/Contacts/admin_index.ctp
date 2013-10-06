<div class="Contacts index">
	<h2><?php echo __('Contacts'); ?></h2>
	<table>
		<tr>
			<th><?php echo $this->Paginator->sort('name'); ?></th>
			<th><?php echo $this->Paginator->sort('email'); ?></th>
			<th><?php echo $this->Paginator->sort('comment'); ?></th>
			<th><?php echo $this->Paginator->sort('created'); ?></th>
		</tr>
		<?php foreach ($contacts as $contact): ?>
			<tr>
				<td><?php echo $contact['Contact']['name']; ?></td>
				<td><?php echo $contact['Contact']['email']; ?></td>
				<td><?php echo $contact['Contact']['comment']; ?></td>
				<td><?php echo $contact['Contact']['created']; ?></td>
			</tr>
		<?php endforeach; ?>
	</table>
	<p>
		<?php
		echo $this->Paginator->counter(array(
		'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
		));
		?>
	</p>
	<div class="paging">
	<?php
		echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
		echo $this->Paginator->numbers(array('separator' => ''));
		echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
	?>
	</div>
</div>
