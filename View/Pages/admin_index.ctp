<div class="pages admin-index">
    <div class="row">
        <div class="col-lg-10">
            <h1>Pages Admin</h1>
        </div>
        <div class="col-lg-2">
            <?php echo $this->Html->link('Add a Page', array(
                                                        'action' => 'add', 'admin' => 'true'
                                                    ),
                                                        array(
                                                        'class' => 'btn btn-primary'
                                                    )
                                        ); ?>
        </div>
    </div>
    <div class="table">
        <div class="table-row">
            <span class="table-head"><?php echo $this->Paginator->sort('name'); ?></span>
            <span class="table-head"><?php echo $this->Paginator->sort('slug'); ?></span>
            <span class="table-head"><?php echo $this->Paginator->sort('created'); ?></span>
            <span class="table-head"><?php echo $this->Paginator->sort('modified'); ?></span>
        </div>
        <?php foreach($pages as $page): ?>
            <?php $row = $this->Html->tag('span', $page['Page']['name'], array('class' => 'table-cell')); ?>
            <?php $row .= $this->Html->tag('span', $page['Page']['slug'], array('class' => 'table-cell')); ?>
            <?php $row .= $this->Html->tag('span', $page['Page']['created'], array('class' => 'table-cell text-center')); ?>
            <?php $row .= $this->Html->tag('span', $page['Page']['modified'], array('class' => 'table-cell text-center')); ?>
            <?php echo $this->Html->link($row, array('action' => 'admin_edit', $page['Page']['id'], 'admin' => true), array('class' => 'table-row', 'escape' => false)); ?>
        <?php endforeach; ?>
    </div>
    <?php echo $this->Element('paginator'); ?>
</div>