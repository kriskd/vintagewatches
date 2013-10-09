<div class="watches index">
	<h2><?php echo __('Watches'); ?></h2>
	<?php /*
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
                <?php echo $this->Element('add_to_cart', compact('watch', 'controller') + array('class' => 'btn btn-gold')); ?>
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
	*/ ?>
    <?php foreach ($watches as $i => $watch): ?>
        <?php if ($i%3==0): ?>
        <div class="row">
        <?php endif; ?>
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
                <div class="watch">
                    <div class="row">
                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6"> 
                            <?php echo $this->Html->thumbImagePrimary($watch,
                                                                      array('class' => 'center-block',
                                                                            'url' => array('controller' => 'watches',
                                                                                           'action' => 'view', $watch['Watch']['id']))
                                                                      ); ?>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                            <h5><?php echo $watch['Watch']['name']; ?></h5>
                            <p><?php echo $this->Number->currency($watch['Watch']['price'], 'USD'); ?></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <?php $more = $this->Html->link('<strong>More details</strong>',
                                                            array('controller' => 'watches', 'action' => 'view', $watch['Watch']['id']),
                                                            array('escape' => false)); ?>
                            <p><?php echo $this->Watch->shortDescription($watch['Watch']['description'], $more); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        <?php if ($i%3==2 || $i==count($watches)-1): ?>
        </div>
        <?php endif; ?>
    <?php endforeach; ?>
    <div class="row">
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
</div>
