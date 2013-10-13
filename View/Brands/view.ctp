<div class="brands view">
<h2><?php echo __('Brand'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($brand['Brand']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Name'); ?></dt>
		<dd>
			<?php echo h($brand['Brand']['name']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Brand'), array('action' => 'edit', $brand['Brand']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Brand'), array('action' => 'delete', $brand['Brand']['id']), null, __('Are you sure you want to delete # %s?', $brand['Brand']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Brands'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Brand'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Watches'), array('controller' => 'watches', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Watch'), array('controller' => 'watches', 'action' => 'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php echo __('Related Watches'); ?></h3>
	<?php if (!empty($brand['Watch'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Order Id'); ?></th>
		<th><?php echo __('Brand Id'); ?></th>
		<th><?php echo __('StockId'); ?></th>
		<th><?php echo __('Price'); ?></th>
		<th><?php echo __('Name'); ?></th>
		<th><?php echo __('Description'); ?></th>
		<th><?php echo __('Active'); ?></th>
		<th><?php echo __('Created'); ?></th>
		<th><?php echo __('Modified'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($brand['Watch'] as $watch): ?>
		<tr>
			<td><?php echo $watch['id']; ?></td>
			<td><?php echo $watch['order_id']; ?></td>
			<td><?php echo $watch['brand_id']; ?></td>
			<td><?php echo $watch['stockId']; ?></td>
			<td><?php echo $watch['price']; ?></td>
			<td><?php echo $watch['name']; ?></td>
			<td><?php echo $watch['description']; ?></td>
			<td><?php echo $watch['active']; ?></td>
			<td><?php echo $watch['created']; ?></td>
			<td><?php echo $watch['modified']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'watches', 'action' => 'view', $watch['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'watches', 'action' => 'edit', $watch['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'watches', 'action' => 'delete', $watch['id']), null, __('Are you sure you want to delete # %s?', $watch['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Watch'), array('controller' => 'watches', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
