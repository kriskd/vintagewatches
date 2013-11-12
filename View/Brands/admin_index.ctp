<div class="brands index">
	<h2><?php echo __('Brands'); ?></h2>
    <?php echo $this->Form->create('Brand', array('autocomplete' => 'off')); ?>
    <?php if (!empty($brands)): ?>
        <p><strong>Edit/Delete Brands</strong></p>
        <?php foreach ($brands as $id =>$brand): ?>
            <?php echo $this->Form->input('Brand.'.$id.'.name', array(
                                                                'value' => $brand,
                                                                'class' => 'form-control',
                                                                'label' => false
                                                                )
                                          ); ?>
            <?php echo $this->Form->input('Brand.'.$id.'.id'); ?>
        <?php endforeach; ?>
    <?php endif; ?>
    <?php echo $this->Form->input('Brand.name', array('label' => 'Add Brand', 'class' => 'form-control', 'value' => '')); ?>
    <?php echo $this->Form->end(array('label' => 'Save', 'class' => 'btn btn-gold')); ?>
</div>

