<div class="watches view">
	<h2><?php  echo h($watch['Watch']['name']); ?></h2>
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
		<dt><?php echo __('Description'); ?></dt>
		<dd>
			<?php echo nl2br(h($watch['Watch']['description'])); ?>
			&nbsp;
		</dd>
	</dl>
	<?php echo $this->Element('carousel', compact('watch')); ?>
</div>
