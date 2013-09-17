<div class="pages admin-edit">
    <h1><?php echo $page['Page']['name']; ?></h1>
    <?php $contents = $page['Content']; ?>
    <?php echo $this->Form->create('Page'); ?>
        <?php echo $this->Form->input('Page.id', array('type' => 'hidden')); ?>
        <?php echo $this->Form->input('Page.name', array('class' => 'form-control')); ?>
        <?php echo $this->Form->input('Page.slug', array('class' => 'form-control')); ?>
        <?php foreach ($contents as $i => $content): //var_dump($content); ?>
            <?php echo $this->Form->input('Content.'.$i.'.id', array('type' => 'hidden', 'value' => $content['id'])); ?>
            <?php echo $this->Form->input('Content.'.$i.'.page_id', array('type' => 'hidden', 'value' => $content['page_id'])); ?>
            <?php echo $this->Form->input('Content.'.$i.'.value', array('value' => $content['value'],
                                                                 'label' => 'Text',
                                                                 'class' => 'form-control')); ?>
        <?php endforeach; ?>
    <?php echo $this->Form->end(array('label' => 'Save', 'class' => 'btn btn-primary')); ?>
</div>