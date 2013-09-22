<?php echo $this->Form->create('Page', array('class' => 'form-horizontal')); ?>
	<?php echo $this->Form->input('Page.id', array('type' => 'hidden')); ?>
	<?php echo $this->Form->input('Page.name', array('class' => 'form-control')); ?>
	<?php echo $this->Form->input('Page.slug', array('class' => 'form-control')); ?>
	<?php echo $this->Form->input('Page.active', array('label' => array('class' => 'control-label'),
														'div' => "checkbox-inline",
														)
								 ); ?>
	<?php if (isset($contents)): ?>
		<?php foreach ($contents as $i => $content): ?>
			<?php echo $this->Form->input('Content.'.$i.'.id', array('type' => 'hidden', 'value' => $content['id'])); ?>
			<?php echo $this->Form->input('Content.'.$i.'.page_id', array('type' => 'hidden', 'value' => $content['page_id'])); ?>
			<?php echo $this->Form->input('Content.'.$i.'.value', array('value' => $content['value'],
																 'label' => 'Text',
																 'class' => 'form-control')); ?>
		<?php endforeach; ?>
	<?php else: ?>
		<?php echo $this->Form->input('Content.0.value', array(
														 'label' => 'Text',
														 'class' => 'form-control')); ?>
	<?php endif; ?>
<?php echo $this->Form->end(array('label' => 'Save', 'class' => 'btn btn-primary')); ?>