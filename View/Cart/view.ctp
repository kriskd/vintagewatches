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

<?php echo $this->Form->create(false, array('id' => 'payment-form', 'action' => 'checkout', 'class' => 'form-horizontal')); ?>
    <div class="payment-errors"></div>
    <?php echo $this->Form->input('Cart.total', array('type' => 'hidden', 'value' => $total)); ?>
    <?php echo $this->Form->input('Cart.number', array('name' => false,
                                            'size' => 20,
                                            'data-stripe' => 'number',
                                            'autocomplete' => 'off',
                                            'placeHolder' => 'Card Number',
                                            'class' => 'card-number',
                                            'label' => array('text' => 'Card Number<br />(no spaces or hypens)', 'class' => 'control-label'))); ?>
    <?php echo $this->Form->input('Cart.cvc', array('name' => false,
                                            'size' => 4,
                                            'data-stripe' => 'cvc',
                                            'autocomplete' => 'off',
                                            'placeHolder' => 'CVC',
                                            'class' => 'card-cvc',
                                            'label' => array('text' => 'CVC', 'class' => 'control-label'))); ?>
    <p class="control-label">Expiration (MM/YYYY)</p>
    <?php echo $this->Form->input('Cart.month', array('name' => false,
                                            'size' => 2,
                                            'data-stripe' => 'exp-month',
                                            'placeHolder' => 'Month',
                                            'class' => 'card-expiry-month',
                                            'label' => false)); ?>
    <?php echo $this->Form->input('Cart.year', array('name' => false,
                                            'size' => 4,
                                            'data-stripe' => 'exp-year',
                                            'placeHolder' => 'Year',
                                            'class' => 'card-expiry-year',
                                            'label' => false)); ?>
<?php echo $this->Form->end(array('class' => 'btn btn-primary', 'label' => 'Submit Payment')); ?>