<div class="watches index">
	<h2><?php echo __('Watches'); ?></h2>
	<table class="table-striped">
	<tr>
			<th>Add to Cart</th>
			<th><?php echo $this->Paginator->sort('stock_id'); ?></th>
			<th><?php echo $this->Paginator->sort('price'); ?></th>
			<th><?php echo $this->Paginator->sort('name'); ?></th>
			<th class="description"><?php echo $this->Paginator->sort('description'); ?></th>
	</tr>
	<?php foreach ($watches as $watch): ?>
        <tr>
            <td>
                <?php if($this->Cart->inCart($watch['Watch']['id'])): ?>
                    <span class="label label-important">This item is in your cart</span>
                <?php else: ?>
                    <?php echo $this->Html->link('Add to Cart', array('controller' => 'cart', 'action' => 'add', $watch['Watch']['id']), array('class' => 'btn btn-primary')); ?>
                <?php endif; ?>
            </td>
            <td><?php echo h($watch['Watch']['stock_id']); ?>&nbsp;</td>
            <td><?php echo h($this->Number->currency($watch['Watch']['price'], 'USD')); ?>&nbsp;</td>
            <td><?php echo h($watch['Watch']['name']); ?>&nbsp;</td>
            <?php $description = $watch['Watch']['description']; ?>
            <?php $words = str_word_count($description, 1); ?>
            <?php if(count($words)> 50): ?>
                <?php $slice = array_slice($words, 0, 50); ?>
                <?php $more = $this->Html->link('More details', array('action' => 'view', $watch['Watch']['id']), array()); ?>
                <?php $description = h(implode(' ', $slice)) . '... ' . $more; ?>
            <?php endif; ?>
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
