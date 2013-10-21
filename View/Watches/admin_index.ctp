<div class="watches index">
	<h2><?php echo __('Watches'); ?></h2>
    <section class="header">
        <?php echo $this->Html->link('Add Watch', array('action' => 'add'), array('class' => 'btn btn-primary add-watch')); ?>
        <?php echo $this->Watch->closeOpenStore(); ?>
        <div class="btn-group">
            <?php foreach ($buttons as $button => $attrs): ?>
                <?php $class = array('btn', 'btn-default'); ?>
                <?php if ($sold === $attrs['sold'] && $active === $attrs['active']): ?>
                    <?php $class[] = 'active'; ?>
                <?php endif; ?>
                <?php echo $this->Html->link($button, array(
                                    'action' => 'index',
                                    'active' => $attrs['active'],
                                    'sold' => $attrs['sold']
                                ),
                                array(
                                    'class' => implode(' ', $class),
                                    'admin' => true
                                )
                             ); ?>
            <?php endforeach; ?>
        </div>
    </section>
    <div class="table">
        <div class="table-row">
            <span class="table-head">Image</span>
            <span class="table-head"><?php echo $this->Paginator->sort('Brand.name', 'Brand'); ?></span>
            <span class="table-head"><?php echo $this->Paginator->sort('stockId'); ?></span>
            <span class="table-head"><?php echo $this->Paginator->sort('price'); ?></span>
            <span class="table-head"><?php echo $this->Paginator->sort('name'); ?></span>
            <span class="table-head"><?php echo $this->Paginator->sort('description'); ?></span>
            <span class="table-head"><?php echo $this->Paginator->sort('created'); ?></span>
            <span class="table-head"><?php echo $this->Paginator->sort('modified'); ?></span>
        </div>
        <?php foreach ($watches as $watch): ?>
            <?php $row = ''; ?>
            <?php $row .= $this->Html->tag('span', $this->Html->thumbPrimary($watch), array('class' => 'table-cell')); ?>
            <?php $brand = isset($watch['Brand']['name']) ? $watch['Brand']['name'] : ''; ?>
            <?php $row .= $this->Html->tag('span', $brand, array('class' => 'table-cell')); ?>
            <?php $row .= $this->Html->tag('span', h($watch['Watch']['stockId']), array('class' => 'table-cell')); ?>
            <?php $row .= $this->Html->tag('span', h($watch['Watch']['price']), array('class' => 'table-cell')); ?>
            <?php $row .= $this->Html->tag('span', h($watch['Watch']['name']), array('class' => 'table-cell')); ?>
            <?php $row .= $this->Html->tag('span', $this->Text->truncate($watch['Watch']['description'], 75, array('html' => true, 'exact' => false)), array('class' => 'table-cell')); ?>
            <?php $row .= $this->Html->tag('span', date('Y-m-d', strtotime($watch['Watch']['created'])), array('class' => 'table-cell')); ?>
            <?php $row .= $this->Html->tag('span', date('Y-m-d', strtotime($watch['Watch']['modified'])), array('class' => 'table-cell')); ?>
            <?php echo $this->Html->link($row, array('action' => 'admin_view', $watch['Watch']['id'], 'admin' => true), array('class' => 'table-row', 'escape' => false)); ?>
        <?php endforeach; ?>
	</div>
	<?php echo $this->Element('paginator'); ?>
</div>
