<div class="orders admin-index">
    <h1>Order Admin</h1>
    <div class="table">
        <div class="table-row">
            <span class="table-head"><?php echo $this->Paginator->sort('id'); ?></span>
            <span class="table-head"><?php echo $this->Paginator->sort('email'); ?></span>
            <span class="table-head"><?php echo $this->Paginator->sort('stripe_id'); ?></span>
            <span class="table-head"><?php echo $this->Paginator->sort('stripe_amount'); ?></span>
            <span class="table-head"><?php echo $this->Paginator->sort('created'); ?></span>
        </div>
        <?php foreach($orders as $order): ?>
            <?php $row = $this->Html->tag('span', $order['Order']['id'], array('class' => 'table-cell')); ?>
            <?php $row .= $this->Html->tag('span', $order['Order']['email'], array('class' => 'table-cell')); ?>
            <?php $row .= $this->Html->tag('span', $order['Order']['stripe_id'], array('class' => 'table-cell')); ?>
            <?php $row .= $this->Html->tag('span', $this->Number->currency($order['Order']['stripe_amount']/100, 'USD'), array('class' => 'table-cell')); ?>
            <?php $row .= $this->Html->tag('span', $order['Order']['created'], array('class' => 'table-cell')); ?>
            <?php echo $this->Html->link($row, array('action' => 'admin_view', $order['Order']['id'], 'admin' => true), array('class' => 'table-row', 'escape' => false)); ?>
        <?php endforeach; ?>
    </div>
    
    <p>
    <?php
    echo $this->Paginator->counter(array(
    'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
    ));
    ?></p>
    <div class="paging">
        <?php
                echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
                echo $this->Paginator->numbers(array('separator' => ''));
                echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
        ?>
    </div>
</div>