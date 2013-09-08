<div class="watches view">
<h2><?php  echo __('Watch'); ?></h2>
	<dl>
		<dt><?php echo __('Stock Id'); ?></dt>
		<dd>
			<?php echo h($watch['Watch']['stockId']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Price'); ?></dt>
		<dd>
			<?php echo h($watch['Watch']['price']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Name'); ?></dt>
		<dd>
			<?php echo h($watch['Watch']['name']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Description'); ?></dt>
		<dd>
			<?php echo h($watch['Watch']['description']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Active'); ?></dt>
		<dd>
			<?php echo h($watch['Watch']['active']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($watch['Watch']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified'); ?></dt>
		<dd>
			<?php echo h($watch['Watch']['modified']); ?>
			&nbsp;
		</dd>
		<?php if(!empty($watch['Image'])): ?>
			<?php $images = $watch['Image']; ?>
			<?php foreach($images as $image): ?>
				<?php if($image['type'] == 'thumb'): ?>
					<dt>Image</dt>
					<dd>
						<?php echo $this->Html->image('/files/thumbs/' . $image['filename']); ?>
					</dd>
				<?php endif; ?>
			<?php endforeach; ?>
		<?php endif; ?>
		<?php if(!empty($watch['Order'])): ?>
			<dt>Order ID</dt>
			<dd>
				<?php echo $this->Html->link($watch['Order']['id'],
							     array('controller' => 'orders', 'action' => 'view',
								   $watch['Order']['id'],
								   'admin' => true));
				?>
			</dd>
		<?php endif; ?>
	</dl>
	<?php echo $this->Element('carousel', compact('watch')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Watch'), array('action' => 'edit', $watch['Watch']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Watch'), array('action' => 'delete', $watch['Watch']['id']), null, __('Are you sure you want to delete # %s?', $watch['Watch']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Watches'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Watch'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Orders'), array('controller' => 'orders', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Order'), array('controller' => 'orders', 'action' => 'add')); ?> </li>
	</ul>
</div>
