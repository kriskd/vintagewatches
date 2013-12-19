<div class="invoices admin-index">
    <div class="row">
        <div class="col-lg-10">
            <h1>Invoice Admin</h1>
        </div>
        <div class="col-lg-2">
            <?php echo $this->Html->link('Add an Invoice', array(
                                                        'action' => 'add', 'admin' => 'true'
                                                    ),
                                                        array(
                                                        'class' => 'btn btn-primary'
                                                    )
                                        ); ?>
        </div>
    </div>
    <div class="table">
        <div class="table-row">
            <span class="table-head"><?php echo $this->Paginator->sort('id'); ?></span>
            <span class="table-head"><?php echo $this->Paginator->sort('email'); ?></span>
            <span class="table-head"><?php echo $this->Paginator->sort('total'); ?></span>
            <span class="table-head"><?php echo $this->Paginator->sort('created'); ?></span>
            <span class="table-head"><?php echo $this->Paginator->sort('modified'); ?></span>
        </div>
        <?php foreach($invoices as $invoice): ?>
            <?php $row = $this->Html->tag('span', $invoice['Invoice']['id'], array('class' => 'table-cell')); ?>
            <?php $row .= $this->Html->tag('span', $invoice['Invoice']['email'], array('class' => 'table-cell')); ?>
            <?php $row .= $this->Html->tag('span', '', array('class' => 'table-cell')); ?>
            <?php $row .= $this->Html->tag('span', $invoice['Invoice']['created'], array('class' => 'table-cell text-center')); ?>
            <?php $row .= $this->Html->tag('span', $invoice['Invoice']['modified'], array('class' => 'table-cell text-center')); ?>
            <?php echo $this->Html->link($row, array('action' => 'admin_view', $invoice['Invoice']['id'], 'admin' => true), array('class' => 'table-row', 'escape' => false)); ?>
        <?php endforeach; ?>
    </div>
    <?php echo $this->Element('paginator'); ?>
</div>