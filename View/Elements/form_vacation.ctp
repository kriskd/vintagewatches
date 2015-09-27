<?php echo $this->Form->create('Vacation', [
	'class' => 'form-inline',
]); ?>
<?php $this->Form->inputDefaults([
	'class' => 'form-control',
]); ?>
	<fieldset>
		<legend><?php echo $action; ?> Vacation</legend>
	<?php
		echo $this->Form->input('start', [
			'minYear' => date('Y'),
			'maxYear' => date('Y') + 1,
			'interval' => 15,
		]);
		echo $this->Form->input('end', [
			'minYear' => date('Y'),
			'maxYear' => date('Y') + 1,
			'interval' => 15,
		]);
		echo $this->Form->input('message');
	?>
	</fieldset>
<?php echo $this->Form->end([
	'label' => 'Submit',
	'class' => 'btn btn-primary',
]); ?>
