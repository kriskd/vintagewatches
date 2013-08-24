<div class="watches form">
	<?php echo $this->Form->create('Watch'); ?>
		<fieldset>
			<legend><?php echo __('Edit Watch'); ?></legend>
		<?php
			echo $this->Form->input('id');
			echo $this->Form->input('stockId');
			echo $this->Form->input('price');
			echo $this->Form->input('name');
			echo $this->Form->input('description');
			echo $this->Form->input('active');
		?>
		</fieldset>
	<?php echo $this->Form->end(array('label' => 'Submit', 'class' => 'btn btn-primary')); ?>
	
	<?php echo $this->Form->create('Image', array('type' => 'file',
						      'url' => array('action' => 'picture', $watch['Watch']['id']),
						      'class' => 'form-inline')); ?>
		<?php echo $this->Form->label('Add Image'); ?>
		<?php echo $this->Form->file('image'); ?>
	<?php echo $this->Form->end(array('label' => 'Submit', 'class' => 'btn btn-primary')); ?>
	
	<?php if(!empty($watch['Image'])): ?>
		<?php $images = $watch['Image']; ?>
		<?php foreach($images as $image): ?>
			<?php if($image['type'] == 'thumb'): ?>
				<div class="image-thumb">
					<?php echo $this->Html->image('/files/thumbs/' . $image['filename'],
								      array('url' =>
									    array('controller' => 'images',
										  'action' => 'delete_picture', $image['id'], 'admin' => 'true'),
									    array('escape' => false)));
					?>
					<?php echo $this->Html->link('Make Primary',
								     array('controller' => 'images',
									   'action' => 'primary', $image['id'],
									   'admin' => 'true')); ?>
				</div>
			<?php endif; ?>
		<?php endforeach; ?>
	<?php endif; ?>	
</div>

<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Watch.id')), null, __('Are you sure you want to delete # %s?', $this->Form->value('Watch.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Watches'), array('action' => 'index')); ?></li>
	</ul>
</div>
