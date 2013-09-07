<div class="watches index">
	<h2><?php echo __('Watches'); ?></h2>
    <?php echo $this->Html->link('All Watches', array('controller' => 'watches', 'action' => 'index'), array('class' => 'btn btn-default', 'admin' => true)); ?>
    <?php echo $this->Html->link('Sold Watches', array('controller' => 'watches', 'action' => 'index', 'sold' => '1'), array('class' => 'btn btn-default', 'admin' => true)); ?>
    <?php echo $this->Html->link('Unsold Watches', array('controller' => 'watches', 'action' => 'index', 'sold' => '0'), array('class' => 'btn btn-default', 'admin' => true)); ?>
    <?php echo $this->Html->link('Active Watches', array('controller' => 'watches', 'action' => 'index', 'active' => '1'), array('class' => 'btn btn-default', 'admin' => true)); ?>
    <?php echo $this->Html->link('Inactive Watches', array('controller' => 'watches', 'action' => 'index', 'active' => '0'), array('class' => 'btn btn-default', 'admin' => true)); ?>
    <div class="table">
        <div class="table-row">
			<span class="table-head">Image</span>
			<span class="table-head"><?php echo $this->Paginator->sort('stockId'); ?></span>
			<span class="table-head"><?php echo $this->Paginator->sort('price'); ?></span>
			<span class="table-head"><?php echo $this->Paginator->sort('name'); ?></span>
			<span class="table-head"><?php echo $this->Paginator->sort('description'); ?></span>
			<span class="table-head"><?php echo $this->Paginator->sort('created'); ?></span>
			<span class="table-head"><?php echo $this->Paginator->sort('modified'); ?></span>
        </div>
        <?php foreach ($watches as $watch): ?>
            <?php $row = ''; ?>
            <?php $row .= $this->Html->tag('span', $this->Html->thumbImagePrimary($watch)); ?>
            <?php $row .= $this->Html->tag('span', h($watch['Watch']['stockId']), array('class' => 'table-cell')); ?>
            <?php $row .= $this->Html->tag('span', h($watch['Watch']['price']), array('class' => 'table-cell')); ?>
            <?php $row .= $this->Html->tag('span', h($watch['Watch']['name']), array('class' => 'table-cell')); ?>
            <?php $row .= $this->Html->tag('span', $this->Watch->shortDescription($watch['Watch']['description'], null, 10), array('class' => 'table-cell')); ?>
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
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('New Watch'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Orders'), array('controller' => 'orders', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Order'), array('controller' => 'orders', 'action' => 'add')); ?> </li>
	</ul>
</div>
