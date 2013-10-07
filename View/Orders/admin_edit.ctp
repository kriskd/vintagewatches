<div class="orders form">
<?php echo $this->Form->create('Order', array(
					      'inputDefaults' => array(
							'class' => 'form-control'
					      )
					)
			       ); ?>
	<fieldset>
		<legend><?php echo __('Admin Edit Order'); ?></legend>
	<?php
		echo $this->Form->input('email');
		echo $this->Form->input('phone');
		echo $this->Form->input('shippingAmount');
		echo $this->Form->input('notes');
	?>
	</fieldset>
	<fieldset>
		<?php foreach ($order['Address'] as $i => $address): ?>
			<h4><?php echo ucfirst($address['type']); ?> Address</h4>
			<?php echo $this->Form->input('Address.'.$i.'.id', array('type' => 'hidden', 'value' => $address['id'])); ?>
			<?php foreach ($addressFields as $field): ?>
				<?php $options = array('value' => $address[$field]); ?>
				<?php if (strcasecmp($field, 'state')==0 && !empty($field)): ?>
					<?php if (strcasecmp($address['country'], 'US')==0): ?>
						<?php $options['options'] = $statesUS; ?>
					<?php elseif (strcasecmp($address['country'], 'CA')==0): ?>
						<?php $options['options'] = $statesCA; ?>
					<?php endif; ?>
				<?php elseif (strcasecmp($field, 'state')==0 && empty($field)): ?>
					<?php continue; ?>
				<?php endif; ?>
				<?php echo $this->Form->input('Address.'.$i.'.'.$field, $options); ?>
			<?php endforeach; ?>
		<?php endforeach; ?>
	</fieldset>
<?php echo $this->Form->end(array('label' => 'Save', 'class' => 'btn btn-primary')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Watch.id')), null, __('Are you sure you want to delete # %s?', $this->Form->value('Watch.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Orders'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Orders'), array('controller' => 'orders', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Order'), array('controller' => 'orders', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Images'), array('controller' => 'images', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Image'), array('controller' => 'images', 'action' => 'add')); ?> </li>
	</ul>
</div>
