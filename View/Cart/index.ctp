<div class="cart index">
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
            <tr>
                <td></td>
                <td><h5>Shipping</h5></td>
                <td>
                    <h6 class="text-left"><a class="launch-tooltip"
                                             data-toggle="tooltip"
                                             data-placement="top"
                                             title="Once you choose your country, the appropriate shipping charge will be added, 
                                             then the shipping bar below will expand where you indicate shipping
                                             options which will expand the address bar to enter you address.">
                                             Choose Shipping Country <i class="icon-question-sign icon-large"></i>
                                          </a></h6>
                    <?php echo $this->Form->create(false, array('class' => 'select-country')); ?>
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
    <?php echo $this->Form->create('Order', array('url' => '/cart/checkout', 'class' => 'form-horizontal payment-form')); ?>
    <section class="shipping">
        <h3>Shipping</h3>
            <div class="shipping-inner">
            <?php echo $this->Form->input('Address.shipping', array('type' => 'radio',
                                                         'options' => array('billing' => 'Ship to my Billing Address', 'shipping' => 'Ship to a Different Address'),
                                                         'legend' => false,
                                                         //'hiddenField' => false,
                                                         //'div' => "radio inline",
                                                         //'separator' => '</div><div class="radio inline">',
                                                            )); ?>
            <div class="shipping-instructions">
                <h5><a class="launch-tooltip"
                        data-toggle="tooltip"
                        data-placement="top"
                        title="Please add any special instructions here or information
                        you need to pass along to me.">
                        Special Shipping Instructions
                        <i class="icon-question-sign icon-large"></i>
                     </a></h5>
                <?php echo $this->Form->textarea('Order.shipping-instructions'); ?>
            </div>
        </div>
    </section>
    <section class="address">
        <h3>Address</h3>
        <div class="address-forms"></div>
    </section>
    <section>
        <h3>Credit Card</h3>
        <?php echo $this->Element('credit_card'); ?>
    </section>
    
</div>