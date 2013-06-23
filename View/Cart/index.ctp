<div class="cart index">
    <h2>Checkout</h2>
    <section class="review-cart">
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
                    <td class="text-center"><?php echo $this->Html->link('<i class="icon-trash icon-large"></i>',
                                                                         array('action' => 'remove', $watch['Watch']['id']),
                                                                         array('escape' => false, 'class' => 'launch-tooltip',
                                                                               'data-toggle' => 'tooltip',
                                                                               'data-placement' => 'top',
                                                                               'title' => 'Remove from Cart')
                                                                         ); ?>
                    </td>
                    <td><?php echo h($watch['Watch']['stock_id']); ?></td>
                    <td><?php echo $this->Html->link(h($watch['Watch']['name']), array('controller' => 'watches', 'action' => 'view', $watch['Watch']['id'])); ?></td>
                    <td class="text-right"><?php echo h($this->Number->currency($watch['Watch']['price'], 'USD')); ?></td>
                </tr>
            <?php endforeach; ?>
            <tr class="shipping">
                <td></td>
                <td><h5>Shipping</h5></td>
                <td>
                    <h6 class="text-left"><a class="launch-tooltip"
                                             data-toggle="tooltip"
                                             data-placement="top"
                                             title="Once you choose your country, the appropriate shipping charge will be added, and the address bar below will expand so that you can enter your address.">
                                             Choose Country <i class="icon-question-sign icon-large"></i>
                                          </a></h6>
                    <?php echo $this->Form->create('false', array('class' => 'select-country')); ?>
                        <?php echo $this->Form->input('Address.country', array('type' => 'radio',
                                                                             'options' => array('us' => 'U.S.', 'ca' => 'Canada', 'other' => 'Other'),
                                                                             'legend' => false,
                                                                             //'hiddenField' => false,
                                                                             'div' => "radio inline",
                                                                             'separator' => '</div><div class="radio inline">',
                                                                                )); ?>
                    <?php echo $this->Form->end(); ?>
                </td>
                <td class="shipping-amount text-right"></td>
            </tr>
            <tr class="total-row"><td></td><td></td><td class="text-right">Total</td><td class="total-formatted-amount text-right"></td></tr>
        </table>
    </section>
    
    <?php echo $this->Form->create('Cart', array('url' => '/cart/checkout', 'class' => 'form-horizontal payment-form')); ?>
    <section class="address">
        <h3>Address</h3>
        <?php echo $this->Element('address'); ?>
    </section>
    <section>
        <h3>Credit Card</h3>
        <?php echo $this->Element('credit_card'); ?>
    </section>
    
</div>