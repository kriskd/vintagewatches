<div class="coupons view">
<h2><?php echo __('Coupon'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($coupon['Coupon']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Code'); ?></dt>
		<dd>
			<?php echo h($coupon['Coupon']['code']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Type'); ?></dt>
		<dd>
			<?php echo h($coupon['Coupon']['type']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Amount'); ?></dt>
		<dd>
			<?php echo h($coupon['Coupon']['amount']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Assigned To'); ?></dt>
		<dd>
			<?php echo h($coupon['Coupon']['assigned_to']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Minimum Order'); ?></dt>
		<dd>
			<?php echo h($coupon['Coupon']['minimum_order']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Expire Date'); ?></dt>
		<dd>
			<?php echo h($coupon['Coupon']['expire_date']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($coupon['Coupon']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified'); ?></dt>
		<dd>
			<?php echo h($coupon['Coupon']['modified']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Coupon'), array('action' => 'edit', $coupon['Coupon']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Coupon'), array('action' => 'delete', $coupon['Coupon']['id']), null, __('Are you sure you want to delete # %s?', $coupon['Coupon']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Coupons'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Coupon'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Orders'), array('controller' => 'orders', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Order'), array('controller' => 'orders', 'action' => 'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php echo __('Related Orders'); ?></h3>
	<?php if (!empty($coupon['Order'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Email'); ?></th>
		<th><?php echo __('Phone'); ?></th>
		<th><?php echo __('ShippingAmount'); ?></th>
		<th><?php echo __('ShipDate'); ?></th>
		<th><?php echo __('Notes'); ?></th>
		<th><?php echo __('OrderNotes'); ?></th>
		<th><?php echo __('Coupon Id'); ?></th>
		<th><?php echo __('Created'); ?></th>
		<th><?php echo __('Modified'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($coupon['Order'] as $order): ?>
		<tr>
			<td><?php echo $order['id']; ?></td>
			<td><?php echo $order['email']; ?></td>
			<td><?php echo $order['phone']; ?></td>
			<td><?php echo $order['shippingAmount']; ?></td>
			<td><?php echo $order['shipDate']; ?></td>
			<td><?php echo $order['notes']; ?></td>
			<td><?php echo $order['orderNotes']; ?></td>
			<td><?php echo $order['coupon_id']; ?></td>
			<td><?php echo $order['created']; ?></td>
			<td><?php echo $order['modified']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'orders', 'action' => 'view', $order['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'orders', 'action' => 'edit', $order['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'orders', 'action' => 'delete', $order['id']), null, __('Are you sure you want to delete # %s?', $order['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Order'), array('controller' => 'orders', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
