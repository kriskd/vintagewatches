<div class="orders index">
    <?php if (empty($orders)): ?>
        <?php echo $this->Form->create('Order', array('type' => 'get')); ?>
        <?php echo $this->Form->input('Order.email', array('class' => 'form-control')); ?>
        <?php echo $this->Form->input('Address.postalCode', array('class' => 'form-control')); ?>
        <?php echo $this->Form->end(array('label' => 'Submit', 'class' => 'btn btn-gold')); ?>
        <?php return; ?>
    <?php endif; ?>
    <?php foreach ($orders as $order): ?>
        <?php echo $this->Element('order_details', compact('order')); ?>
    <?php endforeach; ?>
</div>