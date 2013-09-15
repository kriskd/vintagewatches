<div class="pages admin-edit">
    <h1><?php echo $page['Page']['name']; ?></h1>
    <?php $contents = $page['Content']; ?>
    <?php echo $this->Form->create('Page'); ?>
        <?php foreach ($contents as $content): //var_dump($content); ?>
            <?php echo $this->Form->input('Content.id', array('type' => 'hidden', 'value' => $content['id'])); ?>
            <?php echo $this->Form->input('Content.value', array('value' => $content['value'],
                                                                 'label' => false,
                                                                 'class' => 'form-control')); ?>
        <?php endforeach; ?>
    <?php echo $this->Form->end(array('label' => 'Save', 'class' => 'btn btn-primary')); ?>
</div>