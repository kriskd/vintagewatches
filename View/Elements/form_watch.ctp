<?php echo $this->Form->create('Watch', array('class' => 'form-horizontal')); ?>
	<fieldset>
		<legend><?php echo $action; ?> <?php echo __('Watch'); ?></legend>
	<?php
		echo $this->Form->input('stockId', array('label' => array('class' => 'control-label'),
							 'class' => 'form-control',
							 'type' => 'text',
							 'error' => false));
		echo $this->Form->error('stockId', null, array('class' => 'label label-danger'));
		echo $this->Form->input('price', array('label' => array('class' => 'control-label'),
							 'class' => 'form-control',
							 'min' => 0,
							 'error' => false));
		echo $this->Form->error('price', null, array('class' => 'label label-danger'));
		echo $this->Form->input('name', array('label' => array('class' => 'control-label'),
							 'class' => 'form-control',
							 'error' => false));
		echo $this->Form->error('name', null, array('class' => 'label label-danger'));
		echo $this->Form->input('description', array('label' => array('class' => 'control-label'),
							 'class' => 'form-control'));
		echo $this->Form->input('active', array('label' => array('class' => 'control-label'),
							 'div' => "checkbox-inline",
                                                         'separator' => '</div><div class="checkbox-inline">')
					);
	?>
	</fieldset>
<?php echo $this->Form->end(array('label' => __('Save Watch Text'), 'class' => 'btn btn-primary')); ?>