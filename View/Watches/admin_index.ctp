<div class="watches index">
	<h2><?php echo __('Watches'); ?></h2>
    <section class="header">
        <?php echo $this->Html->link('Add Watch', array('action' => 'add'), array('class' => 'btn btn-success add-watch')); ?>
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
            <span class="table-head">Order</span>
            <span class="table-head"><?php echo $this->Paginator->sort('stockId'); ?></span>
            <span class="table-head"><?php echo $this->Paginator->sort('price'); ?></span>
            <span class="table-head"><?php echo $this->Paginator->sort('name'); ?></span>
            <span class="table-head"><?php echo $this->Paginator->sort('description'); ?></span>
            <span class="table-head"><?php echo $this->Paginator->sort('created'); ?></span>
            <span class="table-head"><?php echo $this->Paginator->sort('modified'); ?></span>
        </div>
        <?php foreach ($watches as $watch): ?>
            <?php $orderId = isset($watch['Order']['id']) ? $watch['Order']['id'] : ''; ?>
            <?php $row = ''; ?>
            <?php $row .= $this->Html->tag('span', $this->Html->thumbImagePrimary($watch), array('class' => 'table-cell')); ?>
            <?php $row .= $this->Html->tag('span', $orderId, array('class' => 'table-cell')); ?>
            <?php $row .= $this->Html->tag('span', h($watch['Watch']['stockId']), array('class' => 'table-cell')); ?>
            <?php $row .= $this->Html->tag('span', h($watch['Watch']['price']), array('class' => 'table-cell')); ?>
            <?php $row .= $this->Html->tag('span', h($watch['Watch']['name']), array('class' => 'table-cell')); ?>
            <?php $row .= $this->Html->tag('span', $this->Watch->shortDescription($watch['Watch']['description'], null, 10), array('class' => 'table-cell')); ?>
            <?php //$row .= $this->Html->tag('span', $watch['Watch']['description'], array('class' => 'table-cell')); ?>
            <?php $row .= $this->Html->tag('span', h($watch['Watch']['created']), array('class' => 'table-cell')); ?>
            <?php $row .= $this->Html->tag('span', h($watch['Watch']['modified']), array('class' => 'table-cell')); ?>
            <?php echo $this->Html->link($row, array('action' => 'admin_view', $watch['Watch']['id'], 'admin' => true), array('class' => 'table-row', 'escape' => false)); ?>
        <?php endforeach; ?>
	</div>
    
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
	));
	?>	</p>
	<div class="paging">
	<?php
		echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
		echo $this->Paginator->numbers(array('separator' => ''));
		echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
	?>
	</div>
</div>
