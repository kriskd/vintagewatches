<h2>Your Cart</h2>
<table class="table-striped">
    <tr>
        <th>Stock ID</th>
        <th>Name</th>
        <th>Price</th>
    </tr>
    <?php foreach($watches as $watch): ?>
        <tr>
            <td><?php echo h($watch['Watch']['stock_id']); ?>&nbsp;</td>
            <td><?php echo h($watch['Watch']['name']); ?>&nbsp;</td>
            <td><?php echo h($this->Number->currency($watch['Watch']['price'], 'USD')); ?>&nbsp;</td>
        </tr>
    <?php endforeach; ?>
    <tr><td></td><td>Total</td><td><?php echo $this->Number->currency($total, 'USD'); ?></td></tr>
</table>

<?php echo $this->Form->create(false, array('id' => 'payment-form', 'url' => array('controller' => 'checkout', 'action' => 'index'))); ?>
    <div class="payment-errors"></div>
    <?php echo $this->Form->input('Cart', array('name' => false,
                                            'size' => 20,
                                            'data-stripe' => 'number',
                                            'class' => 'card-number',
                                            'label' => 'Card Number')); ?>
    <?php echo $this->Form->input('Cart', array('name' => false,
                                            'size' => 4,
                                            'data-stripe' => 'cvc',
                                            'class' => 'card-cvc',
                                            'label' => 'CVC')); ?>
    <p>Expiration (MM/YYYY)</p>
    <?php echo $this->Form->input('Cart', array('name' => false,
                                            'size' => 2,
                                            'data-stripe' => 'exp-month',
                                            'class' => 'card-expiry-month',
                                            'label' => false)); ?>/
    <?php echo $this->Form->input('Cart', array('name' => false,
                                            'size' => 4,
                                            'data-stripe' => 'exp-year',
                                            'class' => 'card-expiry-year',
                                            'label' => false)); ?>
<?php echo $this->Form->end(array('class' => 'btn btn-primary', 'label' => 'Submit Payment')); ?>