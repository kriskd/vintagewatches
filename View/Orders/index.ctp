<div class="orders index">
    <?php if (empty($orders)): ?>
        <p>Get your entire order history with Bruce's Vintage Watches by email and billing postal code.
        Both items will have to match what is on the order to be retrieved.</p>
        <?php echo $this->Form->create('Order'); ?>
        <?php echo $this->Form->input('Order.email', array('class' => 'form-control')); ?>
        <?php echo $this->Form->input('Address.postalCode', array('class' => 'form-control', 'label' => 'Billing Postal Code')); ?>
        <?php echo $this->Form->end(array('label' => 'Submit', 'class' => 'btn btn-gold')); ?>
<?php //Close out the div since no more html after return ?>
</div>
        <?php return; ?>
    <?php endif; ?>
    <h4>Orders for <?php echo $email; ?> <span>Click on order row for more details.</span></h4>
    <div class="row">
        <div class="col-lg-8 col-md-10 col-sm-12 col-xs-12">
            <div class="table">
                <div class="table-row row">
                    <span class="table-head col-lg-4">
                        <?php echo $this->Paginator->sort('created', 'Date Ordered'); ?>
                    </span>
                    <span class="table-head col-lg-2">
                        <?php echo $this->Paginator->sort('id', 'Order No.'); ?>
                    </span>
                    <span class="table-head col-lg-2">
                        <?php echo $this->Paginator->sort('stripe_amount', 'Total Paid'); ?>
                    </span>
                    <span class="table-head col-lg-4">
                        <?php echo $this->Paginator->sort('shipDate', 'Ship Date'); ?>
                    </span>
                </div>
                <?php foreach ($orders as $order): ?>
                    <?php $row = $this->Html->tag('span', date('F j, Y', strtotime($order['Order']['created'])), array('class' => 'table-cell text-center col-lg-4')); ?>
                    <?php $row .= $this->Html->tag('span', $order['Order']['id'], array('class' => 'table-cell text-center col-lg-2')); ?>
                    <?php $row .= $this->Html->tag('span', $this->Number->stripe($order['Order']['stripe_amount']), array('class' => 'table-cell text-right col-lg-2')); ?>
                    <?php if (empty($order['Order']['shipDate'])): ?>
                        <?php $date = ''; ?>
                    <?php else: ?>
                        <?php $date =date('F j, Y', strtotime($order['Order']['shipDate'])); ?>
                    <?php endif ; ?>
                    <?php $row .= $this->Html->tag('span', $date, array('class' => 'table-cell text-center col-lg-4')); ?>
                    <?php echo $this->Html->link($row, array('action' => 'view', $order['Order']['id']), array('class' => 'table-row row', 'escape' => false)); ?>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <?php echo $this->Element('paginator'); ?>
    <?php echo $this->Html->link('Search for Different Orders', array('action' => 'index', true), array('class' => 'btn btn-gold')); ?>
</div>