<div class="orders index">
    <?php if (empty($orders)): ?>
        <?php echo $this->Form->create('Order', array('type' => 'get')); ?>
        <?php echo $this->Form->input('Order.email', array('class' => 'form-control')); ?>
        <?php echo $this->Form->input('Address.postalCode', array('class' => 'form-control', 'label' => 'Billing Postal Code')); ?>
        <?php echo $this->Form->end(array('label' => 'Submit', 'class' => 'btn btn-gold')); ?>
        <?php return; ?>
    <?php endif; ?>
    <div class="row">
        <div class="col-lg-3">
            Date Ordered
        </div>
        <div class="col-lg-2">
            Order No.
        </div>
        <div class="col-lg-2">
            Total Paid
        </div>
        <div class="col-lg-3">
            Ship Date
        </div>
        <div class="col-lg-2">
            
        </div>
    </div>
    <?php foreach ($orders as $order): ?>
        <div class="row">
            <div class="col-lg-3">
                <?php echo $order['Order']['created']; ?>
            </div>
            <div class="col-lg-2">
                <?php echo $order['Order']['id']; ?>
            </div>
            <div class="col-lg-2">
                <?php echo $this->Number->stripe($order['Order']['stripe_amount']); ?>
            </div>
            <div class="col-lg-3">
                <?php echo $order['Order']['shipDate']; ?>
            </div>
            <div class="col-lg-2">
                <?php echo $this->Html->link('View', '#', array('class' => 'btn btn-gold')); ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>