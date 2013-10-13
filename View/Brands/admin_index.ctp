<div class="brands index">
	<h2><?php echo __('Brands'); ?></h2>
    <?php echo $this->Form->create(); ?>
	<?php foreach ($brands as $brand): ?>
        <?php echo $this->Form->input('Brand.'.$brand['Brand']['id'].'.name', array(
                                                                                'value' => $brand['Brand']['name'],
                                                                                'class' => 'form-control',
                                                                                'label' => false
                                                                                )
                                      ); ?>
    <?php endforeach; ?>
    <?php echo $this->Form->input('Brand.name', array('label' => 'Add Brand', 'class' => 'form-control', 'value' => '')); ?>
    <?php echo $this->Form->end(array('label' => 'Save', 'class' => 'btn btn-gold')); ?>
</div>

