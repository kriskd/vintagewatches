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
	<?php echo $this->Element('paginator'); ?>
</div>
