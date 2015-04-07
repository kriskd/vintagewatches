<div class="owners index">
	<h2><?php echo __('Owners'); ?></h2>
    <?php echo $this->Form->create('Owner', array('autocomplete' => 'off')); ?>
    <?php if (!empty($owners)): ?>
        <p><strong>Edit/Delete Owners</strong></p>
        <?php foreach ($owners as $id => $owner): ?>
            <?php echo $this->Form->input('Owner.'.$id.'.name', array(
                                                                'value' => $owner,
                                                                'class' => 'form-control',
                                                                'label' => false
                                                                )
                                          ); ?>
            <?php echo $this->Form->input('Owner.'.$id.'.id'); ?>
        <?php endforeach; ?>
    <?php endif; ?>
    <?php echo $this->Form->input('Owner.name', array('label' => 'Add Owner', 'class' => 'form-control', 'value' => '')); ?>
    <?php echo $this->Form->end(array('label' => 'Save', 'class' => 'btn btn-gold')); ?>
</div>

