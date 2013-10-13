<div class="orders index">
    <?php if (empty($orders)): ?>
        <p>Get your entire order history with Bruce's Vintage Watches by email and billing postal code.
        Both items will have to match what is on the order to be retrieved.</p>
        <?php echo $this->Form->create('Order'); ?>
        <?php echo $this->Form->input('Order.email', array('class' => 'form-control')); ?>
        <?php echo $this->Form->input('Address.postalCode', array('class' => 'form-control', 'label' => 'Billing Postal Code')); ?>
        <?php echo $this->Form->end(array('label' => 'Submit', 'class' => 'btn btn-gold')); ?>
        <?php return; ?>
    <?php endif; ?>
    <h4>Orders for <?php echo $email; ?></h4>
    <div class="row">
        <div class="col-lg-3">
            <?php echo $this->Paginator->sort('created', 'Date Ordered'); ?>
        </div>
        <div class="col-lg-2">
            <?php echo $this->Paginator->sort('id', 'Order No.'); ?>
        </div>
        <div class="col-lg-2">
            <?php echo $this->Paginator->sort('stripe_amount', 'Total Paid'); ?>
        </div>
        <div class="col-lg-3">
            <?php echo $this->Paginator->sort('shipDate', 'Ship Date'); ?>
        </div>
        <div class="col-lg-2">
            
        </div>
    </div>
    <?php foreach ($orders as $order): ?>
        <div class="row">
            <div class="col-lg-3">
                <?php echo date('F j, Y', strtotime($order['Order']['created'])); ?>
            </div>
            <div class="col-lg-2">
                <?php echo $order['Order']['id']; ?>
            </div>
            <div class="col-lg-2">
                <?php echo $this->Number->stripe($order['Order']['stripe_amount']); ?>
            </div>
            <div class="col-lg-3">
                <?php if (!empty($order['Order']['shipDate'])): ?>
                    <?php echo date('F j, Y', strtotime($order['Order']['shipDate'])); ?>
                <?php endif; ?>
            </div>
            <div class="col-lg-2">
                <?php echo $this->Html->link('View',
                                             array(
                                                'action' => 'view', $order['Order']['id']
                                            ),
                                             array('class' => 'btn btn-gold')); ?>
            </div>
        </div>
    <?php endforeach; ?>
    <?php echo $this->Element('paginator'); ?>
    <?php echo $this->Html->link('Search for different orders.', array('action' => 'index', true)); ?>
</div>