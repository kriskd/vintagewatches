<div class="watches index">
	<h2><?php echo __('Watches'); ?></h2>
	<table class="table-striped table-bordered">
	<tr>
        <th></th>
		<th>Add to Cart</th>
		<th><?php echo $this->Paginator->sort('stockId'); ?></th>
		<th><?php echo $this->Paginator->sort('price'); ?></th>
		<th><?php echo $this->Paginator->sort('name'); ?></th>
		<th class="description"><?php echo $this->Paginator->sort('description'); ?></th>
	</tr>
	<?php foreach ($watches as $watch): ?>
        <tr>
            <td>
                <?php echo $this->Html->link($this->Html->thumbImagePrimary($watch), array('action' => 'view', $watch['Watch']['id']), array('escape' => false)); ?>
            </td>
            <td>
                <?php if($this->Cart->inCart($watch['Watch']['id'], $controller)): ?>
                    <span class="label label-info">This item is in your cart</span>
                <?php else: ?>
                    <?php echo $this->Html->link('Add to Cart', array('controller' => 'orders', 'action' => 'add', $watch['Watch']['id']), array('class' => 'btn btn-primary')); ?>
                <?php endif; ?>
            </td>
            <td><?php echo h($watch['Watch']['stockId']); ?>&nbsp;</td>
            <td><?php echo h($this->Number->currency($watch['Watch']['price'], 'USD')); ?>&nbsp;</td>
            <td><?php echo h($watch['Watch']['name']); ?>&nbsp;</td>
            <?php $description = $watch['Watch']['description']; ?>
            <?php $more = $this->Html->link('More details', array('action' => 'view', $watch['Watch']['id']), array()); ?>
            <?php $description = $this->Watch->shortDescription($description, $more); ?>
            <td class="description"><?php echo $description; ?>&nbsp;</td>
        </tr>
    <?php endforeach; ?>
	</table>
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
