<div class="coupons index">
	<h2><?php echo __('Coupons'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('code'); ?></th>
			<th><?php echo $this->Paginator->sort('type'); ?></th>
			<th><?php echo $this->Paginator->sort('amount'); ?></th>
			<th><?php echo $this->Paginator->sort('assigned_to'); ?></th>
			<th><?php echo $this->Paginator->sort('minimum_order'); ?></th>
			<th><?php echo $this->Paginator->sort('expire_date'); ?></th>
			<th><?php echo $this->Paginator->sort('created'); ?></th>
			<th><?php echo $this->Paginator->sort('modified'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($coupons as $coupon): ?>
	<tr>
		<td><?php echo h($coupon['Coupon']['id']); ?>&nbsp;</td>
		<td><?php echo h($coupon['Coupon']['code']); ?>&nbsp;</td>
		<td><?php echo h($coupon['Coupon']['type']); ?>&nbsp;</td>
		<td><?php echo h($coupon['Coupon']['amount']); ?>&nbsp;</td>
		<td><?php echo h($coupon['Coupon']['assigned_to']); ?>&nbsp;</td>
		<td><?php echo h($coupon['Coupon']['minimum_order']); ?>&nbsp;</td>
		<td><?php echo h($coupon['Coupon']['expire_date']); ?>&nbsp;</td>
		<td><?php echo h($coupon['Coupon']['created']); ?>&nbsp;</td>
		<td><?php echo h($coupon['Coupon']['modified']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $coupon['Coupon']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $coupon['Coupon']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $coupon['Coupon']['id']), null, __('Are you sure you want to delete # %s?', $coupon['Coupon']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
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
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('New Coupon'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Orders'), array('controller' => 'orders', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Order'), array('controller' => 'orders', 'action' => 'add')); ?> </li>
	</ul>
</div>
