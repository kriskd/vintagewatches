<div class="orders admin-index">
    <h1>Order Admin</h1>
    <?php echo $this->Form->create(false, array('type' => 'get')); ?>
        <?php echo $this->Form->input('filter', $filters + array('class' => 'form-control', 'selected' => $filter)); ?>
        <?php echo $this->Form->input('value', array('class' => 'form-control', 'value' => $value)); ?>
    <?php echo $this->Form->end(array('label' => 'Submit', 'class' => 'btn btn-primary')); ?>
    <div class="table">
        <div class="table-row">
            <span class="table-head"><?php echo $this->Paginator->sort('id'); ?></span>
            <span class="table-head"><?php echo $this->Paginator->sort('email'); ?></span>
            <span class="table-head"><?php echo $this->Paginator->sort('stripe_id'); ?></span>
            <span class="table-head"><?php echo $this->Paginator->sort('stripe_amount'); ?></span>
            <span class="table-head"><?php echo $this->Paginator->sort('shipDate', 'Ship Date'); ?></span>
            <span class="table-head"><?php echo $this->Paginator->sort('created'); ?></span>
        </div>
        <?php foreach($orders as $order): ?>
            <?php $row = $this->Html->tag('span', $order['Order']['id'], array('class' => 'table-cell')); ?>
            <?php $row .= $this->Html->tag('span', $order['Order']['email'], array('class' => 'table-cell')); ?>
            <?php $row .= $this->Html->tag('span', $order['Order']['stripe_id'], array('class' => 'table-cell')); ?>
            <?php $row .= $this->Html->tag('span', $this->Number->stripe($order['Order']['stripe_amount']), array('class' => 'table-cell')); ?>
            <?php $row .= (isset($order['Order']['shipDate'])) ? $this->Html->tag('span', $order['Order']['shipDate'], array('class' => 'table-cell')) : $this->Html->tag('span', '', array('class' => 'table-cell')); ?>
            <?php $row .= $this->Html->tag('span', $order['Order']['created'], array('class' => 'table-cell')); ?>
            <?php echo $this->Html->link($row, array('action' => 'admin_view', $order['Order']['id'], 'admin' => true), array('class' => 'table-row', 'escape' => false)); ?>
        <?php endforeach; ?>
    </div>
    <?php echo $this->Element('paginator'); ?>
</div>