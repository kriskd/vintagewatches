<div class="sources index">
	<h2><?php echo __('Sources'); ?></h2>
    <?php echo $this->Form->create('Source', array('autocomplete' => 'off')); ?>
    <?php if (!empty($sources)): ?>
        <p><strong>Edit/Delete Sources</strong></p>
        <?php foreach ($sources as $id => $source): ?>
            <?php echo $this->Form->input('Source.'.$id.'.name', array(
                                                                'value' => $source,
                                                                'class' => 'form-control',
                                                                'label' => false
                                                                )
                                          ); ?>
            <?php echo $this->Form->input('Source.'.$id.'.id'); ?>
        <?php endforeach; ?>
    <?php endif; ?>
    <?php echo $this->Form->input('Source.name', array('label' => 'Add Source', 'class' => 'form-control', 'value' => '')); ?>
    <?php echo $this->Form->end(array('label' => 'Save', 'class' => 'btn btn-gold')); ?>
</div>

