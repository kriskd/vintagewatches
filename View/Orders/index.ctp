<?php echo $this->Form->create('Order', array('class' => 'form-horizontal payment-form')); ?>
<div class="orders index">
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
                    <td><?php echo h($watch['Watch']['stockId']); ?></td>
                    <td><?php echo $this->Html->link(h($watch['Watch']['name']), array('controller' => 'watches', 'action' => 'view', $watch['Watch']['id'])); ?></td>
                    <td class="text-right"><?php echo h($this->Number->currency($watch['Watch']['price'], 'USD')); ?></td>
                </tr>
            <?php endforeach; ?>
            <tr>
                <td></td>
                <td></td>
                <td>
                    <h6 class="text-left"><a class="launch-tooltip"
                                             data-toggle="tooltip"
                                             data-placement="top"
                                             title="Once you choose your country, the appropriate shipping
                                             charge will be added, and the shipping bar below will expand
                                             where you can indicate shipping options. Then, the address
                                             bar will expand so you can enter the appropriate information.">
                                             Choose the Country Where You Want Your Order Shipped <i class="icon-question-sign icon-large"></i>
                                          </a></h6>
                        <div class="select-country">
                        <?php echo $this->Form->input('Address.select-country', array('type' => 'radio',
                                                                             'options' => array('us' => 'U.S.', 'ca' => 'Canada', 'other' => 'Other'),
                                                                             'legend' => false,
                                                                             //'hiddenField' => false,
                                                                             'div' => "radio inline",
                                                                             'separator' => '</div><div class="radio inline">',
                                                                                )); ?>
                        </div>
                </td>
                <td class="shipping-amount text-right"></td>
            </tr>
            <tr class="total-row"><td></td><td></td><td class="text-right">Total</td><td class="total-formatted-amount text-right"></td></tr>
        </table>
    </section>
    
    <section class="shipping">
        <h3>Shipping</h3>
            <div class="shipping-inner">
            <h4>Choose one of the following shipping options:</h4>
            <?php echo $this->Form->input('Shipping.option', array('type' => 'radio',
                                                         'options' => array('billing' => 'Shipping Address Will be the Same as my ' .
                                                                            $this->Html->link('Billing Address <i class="icon-question-sign icon-large"></i>', '#',
                                                                                array('class' => 'launch-tooltip',
                                                                                 'data-toggle' => 'tooltip',
                                                                                 'data-placement' => 'top',
                                                                                 'title' => 'This Is the address that appears on your billing statement.',
                                                                                 'escape' => false)),
                                                                            'shipping' => 'Shipping Address Will be Different From my Billing Address'),
                                                         'legend' => false,
                                                         //'hiddenField' => false,
                                                         //'div' => "radio inline",
                                                         //'separator' => '</div><div class="radio inline">',
                                                            )); ?>
        </div>
    </section>
    <section class="address">
        <h3>Address</h3>
        <div class="address-forms"></div>
        <div class="shipping-instructions">
            <h5>Email and Phone Number</h5>
            <div class="checkout-form">
                <?php echo $this->Form->input('Order.email', array('label' => array('class' => 'control-label'))); ?>
                <?php echo $this->Form->input('Order.phone', array('label' => array('class' => 'control-label'))); ?>
            </div>
            <?php echo $this->Form->input('Order.notes',
                                          array('label' => '<h5>' . $this->Html->link('Special Order Instructions
                                                            <i class="icon-question-sign icon-large"></i>', '#',
                                                            array('class' => 'launch-tooltip',
                                                                     'data-toggle' => 'tooltip',
                                                                     'data-placement' => 'top',
                                                                     'title' => 'Please add any special instructions here or information
                                                                                you need to pass along to me.',
                                                                     'escape' => false)) . '</h5>')); ?>
        </div>
    </section>
    <section>
        <h3>Credit Card</h3>
        <?php echo $this->Element('credit_card'); ?>
    </section>
    
</div>