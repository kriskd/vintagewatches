<?php echo $this->Form->create('Item', ['type' => 'file']); ?>
<?php $this->Form->inputDefaults(['class' => 'form-control']); ?>
<?php if (strcasecmp($action, 'edit')==0): ?>
    <?php echo $this->Form->input('Item.id', array('type' => 'hidden')); ?>
<?php endif; ?>
	<fieldset>
		<legend><?= $action ?> <?php echo __('Item'); ?></legend>
	<?php
		echo $this->Form->input('active');
		echo $this->Form->input('name');
		echo $this->Form->input('description');
        echo $this->Form->input('active', ['class' => 'checkbox-inline']);
		echo $this->Form->input('quantity');
		echo $this->Form->input('price');
		//echo $this->Form->input('Shipping');
        if (!empty($this->request->data['Item']['filenameThumb'])):
            echo $this->Html->image($this->request->data['Item']['filenameThumb']);
        endif;
        echo $this->Form->input('filename', array(
            'type' => 'file',
            //'label' => false,
            'class' => 'image-upload',
        ));
	?>
	</fieldset>
<?php echo $this->Form->end(['text' => __('Submit'), 'class' => 'btn btn-primary']); ?>
