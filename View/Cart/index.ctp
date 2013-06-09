<div class="cart index">
    <h2>Checkout</h2>
    <section>
            <h3>Review Cart</h3>
            <table class="table-bordered">
                <tr>
                    <th></th>
                    <th>Stock ID</th>
                    <th>Name</th>
                    <th>Price</th>
                </tr>
                <?php foreach($watches as $watch): ?>
                    <tr>
                        <td><?php echo $this->Html->link('<i class="icon-trash"></i>', array('action' => 'remove', $watch['Watch']['id']), array('escape' => false)); ?></td>
                        <td><?php echo h($watch['Watch']['stock_id']); ?></td>
                        <td><?php echo h($watch['Watch']['name']); ?></td>
                        <td class="text-right"><?php echo h($this->Number->currency($watch['Watch']['price'], 'USD')); ?></td>
                    </tr>
                <?php endforeach; ?>
                <tr class="total-row"><td></td><td></td><td class="text-right">Total</td><td class="text-right"><?php echo $this->Number->currency($total, 'USD'); ?></td></tr>
            </table>
    </section>
    <section>
        <h3>Credit Card</h3>
        <?php echo $this->Form->create(false, array('id' => 'payment-form', 'action' => 'checkout', 'class' => 'form-horizontal')); ?>
            <div class="payment-errors alert alert-error"></div>
            <?php echo $this->Form->input('Cart.total', array('type' => 'hidden', 'value' => $total)); ?>
            <?php echo $this->Form->input('Cart.number', array('name' => false,
                                                    'size' => 20,
                                                    'data-stripe' => 'number',
                                                    'autocomplete' => 'off',
                                                    'placeHolder' => 'Card Number',
                                                    'class' => 'card-number input-large',
                                                    'label' => array('text' => '<span>Card Number</span><br /><small>(no spaces or hypens)</small>',
                                                                     'class' => 'control-label'),
                                                    'div' => array('class' => 'card-number-div')
                                                )
                                            ); ?>
            <?php echo $this->Form->input('Cart.cvc', array('name' => false,
                                                    'size' => 4,
                                                    'data-stripe' => 'cvc',
                                                    'autocomplete' => 'off',
                                                    'placeHolder' => 'CVC',
                                                    'class' => 'card-cvc input-small',
                                                    'label' => array('text' => 'CVC', 'class' => 'control-label'))); ?>
            <label for="CartMonth" class="control-label">Expiration (MM/YYYY)</label>
            <?php echo $this->Form->input('Cart.month', array('name' => false,
                                                    'empty' => 'MM',
                                                    'options' => $months,
                                                    'data-stripe' => 'exp-month',
                                                    'class' => 'card-expiry-month input-mini',
                                                    'label' => false)); ?>
            <?php echo $this->Form->input('Cart.year', array('name' => false,
                                                    'empty' => 'Year',
                                                    'options' => $years,
                                                    'data-stripe' => 'exp-year',
                                                    'placeHolder' => 'Year',
                                                    'class' => 'card-expiry-year input-small',
                                                    'label' => false)); ?>
        <?php echo $this->Form->end(array('class' => 'btn btn-primary', 'label' => 'Submit Payment')); ?>
    </section>
</div>