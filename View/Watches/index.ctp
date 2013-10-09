<div class="watches index">
	<h2><?php echo __('Watches'); ?></h2>
    <?php foreach ($watches as $i => $watch): ?>
        <div class="row">
            <div class="watch">
                <div class="row watch-attrs">
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 name">
                        <?php echo h($watch['Watch']['name']); ?>
                    </div>
                    <div class="col-xs-6 col-sm-6 col-md-3 col-lg-3 stockid">
                        <?php echo h($watch['Watch']['stockId']); ?>
                    </div>
                    <div class="col-xs-6 col-sm-6 col-md-3 col-lg-3 price">
                        <?php echo h($this->Number->currency($watch['Watch']['price'], 'USD')); ?>
                    </div>
                </div>
                <div class="col-xs-6 col-sm-2 col-md-2 col-lg-2 center-block">
                    <div class="watch-thumb">
                        <?php echo $this->Html->thumbImagePrimary($watch,
                                                          array('class' => 'center-block',
                                                                'url' => array('controller' => 'watches',
                                                                               'action' => 'view', $watch['Watch']['id']))
                                                          ); ?>
                    </div>
                    <div class="watch-cart-button">
                        <?php echo $this->Element('add_to_cart', compact('watch', 'controller') + array('class' => 'btn btn-gold')); ?>
                    </div>
                </div>
                <div class="col-xs-6 col-sm-10 col-md-10 col-lg-10">
                    <div class="row">
                        <div class="col-lg-12">
                            <?php $description = $watch['Watch']['description']; ?>
                            <?php $more = $this->Html->link('More details', array('action' => 'view', $watch['Watch']['id']), array()); ?>
                            <?php $description = $this->Watch->shortDescription($description, $more); ?>
                            <?php echo $description; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
