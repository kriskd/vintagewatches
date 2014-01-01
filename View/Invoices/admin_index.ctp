<div class="invoices admin-index">
    <div class="row">
        <div class="col-lg-2 col-lg-push-10 col-md-2 col-md-push-10 col-sm-2 col-sm-push-10 col-xs-12">
            <?php echo $this->Html->link('Add an Invoice', array(
                                                        'action' => 'add', 'admin' => 'true'
                                                    ),
                                                        array(
                                                        'class' => 'btn btn-primary add-invoice'
                                                    )
                                        ); ?>
        </div>
        <div class="col-lg-10 col-lg-pull-2 col-md-10 col-md-pull-2 col-sm-10 col-sm-pull-2 col-xs-12">
            <h1>Invoice Admin</h1>
        </div>
    </div>
    <div class="table">
        <div class="table-row">
            <span class="table-head"><?php echo $this->Paginator->sort('active'); ?></span>
            <span class="table-head"><?php echo $this->Paginator->sort('id'); ?></span>
            <span class="table-head"><?php echo $this->Paginator->sort('email'); ?></span>
            <span class="table-head"><?php echo $this->Paginator->sort('total'); ?></span>
            <span class="table-head"><?php echo $this->Paginator->sort('ship Date'); ?></span>
            <span class="table-head"><?php echo $this->Paginator->sort('paid'); ?></span>
            <span class="table-head"><?php echo $this->Paginator->sort('created'); ?></span>
        </div>
        <?php foreach($invoices as $invoice): ?>
            <?php $row = $this->Html->tag('span', $invoice['Invoice']['active']==true ? '<span class="glyphicon glyphicon-ok green"></span>' : '', array('class' => 'table-cell text-center')); ?>
            <?php $row .= $this->Html->tag('span', $invoice['Invoice']['id'], array('class' => 'table-cell')); ?>
            <?php $row .= $this->Html->tag('span', $invoice['Invoice']['email'], array('class' => 'table-cell')); ?>
            <?php $row .= $this->Html->tag('span', $this->Number->currency($this->Invoice->total($invoice), 'USD'), array('class' => 'table-cell text-right')); ?>
            <?php $row .= isset($invoice['Invoice']['shipDate']) ? $this->Html->tag('span', $invoice['Invoice']['shipDate'], array('class' => 'table-cell text-center')) : $this->Html->tag('span', '', array('class' => 'table-cell text-center')); ?>
            <?php $row .= isset($invoice['Payment']['created']) ? $this->Html->tag('span', $invoice['Payment']['created'], array('class' => 'table-cell text-center')) : $this->Html->tag('span', '', array('class' => 'table-cell text-center')); ?>
            <?php $row .= $this->Html->tag('span', $invoice['Invoice']['created'], array('class' => 'table-cell text-center')); ?>
            <?php echo $this->Html->link($row, array('action' => 'admin_view', $invoice['Invoice']['id'], 'admin' => true), array('class' => 'table-row', 'escape' => false)); ?>
        <?php endforeach; ?>
    </div>
    <?php echo $this->Element('paginator'); ?>
</div>