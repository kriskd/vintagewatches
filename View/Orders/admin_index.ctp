<div class="orders admin-index">
    <h1>Order Admin</h1>
    <div class="row">
        <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
            <?php echo $this->Form->create(false, array('type' => 'get')); ?>
            <?php echo $this->Form->input('filter', $filters + array('class' => 'form-control', 'selected' => $filter)); ?>
        </div>
        <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
            <?php echo $this->Form->input('value', array('class' => 'form-control', 'value' => $value)); ?>
        </div>
        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
            <?php echo $this->Form->end(array('label' => 'Submit', 'class' => 'btn btn-primary')); ?>
        </div>
    </div>
    <div class="table">
        <div class="table-row">
            <span class="table-head"><?php echo $this->Paginator->sort('id'); ?></span>
            <span class="table-head"><?php echo $this->Paginator->sort('email'); ?></span>
            <span class="table-head"><?php echo $this->Paginator->sort('stripe_id'); ?></span>
            <span class="table-head"><?php echo $this->Paginator->sort('stripe_amount'); ?></span>
            <span class="table-head"><?php echo $this->Paginator->sort('shipDate', 'Ship Date'); ?></span>
            <span class="table-head"><?php echo $this->Paginator->sort('created'); ?></span>
            <span class="table-head"><?php echo $this->Paginator->sort('modified'); ?></span>
        </div>
        <?php foreach($orders as $order): ?>
            <?php $row = $this->Html->tag('span', $order['Order']['id'], array('class' => 'table-cell')); ?>
            <?php $row .= $this->Html->tag('span', $order['Order']['email'], array('class' => 'table-cell')); ?>
            <?php $row .= $this->Html->tag('span', $order['Payment']['stripe_id'], array('class' => 'table-cell')); ?>
            <?php $row .= $this->Html->tag('span', $this->Number->stripe($order['Payment']['stripe_amount']), array('class' => 'table-cell text-right')); ?>
            <?php $row .= (!empty($order['Order']['shipDate'])) ? $this->Html->tag('span', date('n-j-Y', strtotime($order['Order']['shipDate'])), array('class' => 'table-cell text-center')) : $this->Html->tag('span', '', array('class' => 'table-cell')); ?>
            <?php $row .= $this->Html->tag('span', date('n-j-Y g:i a', strtotime($order['Order']['created'] .  "+2 hour")), array('class' => 'table-cell text-center')); ?>
            <?php $row .= $this->Html->tag('span', date('n-j-Y g:i a', strtotime($order['Order']['modified'] . "+2 hour")), array('class' => 'table-cell text-center')); ?>
            <?php echo $this->Html->link($row, array('action' => 'admin_view', $order['Order']['id'], 'admin' => true), array('class' => 'table-row', 'escape' => false)); ?>
        <?php endforeach; ?>
    </div>
    <?php echo $this->Element('paginator'); ?>
</div>
