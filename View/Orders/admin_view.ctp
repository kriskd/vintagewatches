<div class="orders admin-view">
    <h1>
        Order <?php echo $order['Order']['id']; ?> Detail
        <?php echo $this->Html->link('Resend', array(
                                                    'action' => 'resend', $order['Order']['id'],
                                                    'admin' => true
                                                ),
                                                array(
                                                    'class' => 'btn btn-primary launch-tooltip',
                                                    'data-toggle' => 'tooltip',
                                                    'data-placement' => 'top',
                                                    'title' => 'Resend customer\'s order info, will include ship date if available.'
                                                )
                                     ); ?>
        <?php echo $this->Html->link('Edit', array(
                                                'action' => 'edit', $order['Order']['id'],
                                                'admin' => true
                                                ),
                                           array(
                                                 'class' => 'btn btn-success'
                                                 )
                                           ); ?>
        <?php echo $this->Html->link('Delete', '#delete-order',
                                                array(
                                                    'class' => 'btn btn-danger',
                                                    'data-toggle' => 'modal'
                                                )
                                     ); ?>
    </h1>
    <div class="modal fade" id="delete-order" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Confirm Order Delete</h4>
                </div>
                <div class="modal-body">
                    <p>
                        Are you sure you want to delete Order <?php echo $order['Order']['id']; ?>
                        from <?php echo $order['Address'][0]['firstName']; ?> <?php echo $order['Address'][0]['lastName']; ?>
                        for <?php echo $this->Number->stripe($order['Payment']['stripe_amount']); ?>?
                    </p>
                </div>
                <div class="modal-footer">
                    <?php echo $this->Html->link('Close', '#', array('data-dismiss' => 'modal',
                                                                     'class' => 'btn btn-default btn-lg')); ?>
                    <?php echo $this->Form->postLink('Delete', array('action' => 'delete', $order['Order']['id'], 'admin' => true),
                                                            array('class' => 'btn btn-danger btn-lg')); ?>                                    
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <h2>Purchases</h2>
    <dl>
        <dt>Date</dt>
        <dd><?php echo date('n-j-Y g:i a', strtotime($order['Order']['created'] .  "+2 hour")); ?></dd>
    </dl>
    <table class="table table-bordered">
        <tr><th></th><th></th><th>Stock ID</th><th>Name</th><th>Price</th></tr>
        <?php foreach($order['Watch'] as $watch): ?>
            <tr>
                <td>
                    <?php echo $this->Html->link('Go To Watch', array('controller' => 'watches', 'action' => 'view', $watch['id']), array('class' => 'btn btn-primary')); ?>
                </td>
                <td>
                    <?php echo $this->Html->thumbPrimary($watch); ?>
                </td>
                <td><?php echo $watch['stockId']; ?></td>
                <td><?php echo $watch['name']; ?></td>
                <td class="text-right"><?php echo $this->Number->currency($watch['price'], 'USD'); ?></td>
            </tr>
        <?php endforeach; ?>
        <tr class="total-row">
            <td></td>
            <td></td>
            <td></td>
            <td class="text-right">Shipping</td>
            <td class="total-formatted-amount text-right"><?php echo $this->Number->currency($order['Order']['shippingAmount'], 'USD'); ?></td>
        </tr>
        <?php if (isset($order['Coupon'])): ?>
            <tr class="total-row">
                <td></td>
                <td></td>
                <td></td>
                <td class="text-right">
                    Coupon code <?php echo strtoupper($order['Coupon']['code']); ?> 
                </td>
                <td class="total-formatted-amount text-right">
                    <?php echo $this->Order->couponValue($order); ?>
                </td>
            </tr>
        <?php endif; ?>
        <tr class="total-row">
            <td></td>
            <td></td>
            <td></td>
            <td class="text-right">Total</td>
            <td class="total-formatted-amount text-right"><?php echo $this->Order->totalFormatted($order); ?></td>
        </tr>
    </table>
    <h2>Contact Information</h2>
    <dl>
        <dt>Email</dt>
        <dd><?php echo $this->Text->autoLinkEmails($order['Order']['email']); ?></dd>
        <?php if(!empty($order['Order']['phone'])): ?>
            <dt>Phone</dt>
            <dd><?php echo $order['Order']['phone']; ?></dd>
        <?php endif; ?>
    </dl>
    <?php if(!empty($order['Order']['notes'])): ?>
        <h2>Special Order Instructions</h2>
        <div class="notes">
            <?php echo nl2br($order['Order']['notes']); ?>
        </div>
    <?php endif; ?>
    <?php foreach($order['Address'] as $address): ?>
        <h3><?php echo ucfirst($address['type']); ?> Address</h3>
        <dl>
            <dt>Name</dt>
            <dd><?php echo $address['firstName']; ?> <?php echo $address['lastName']; ?></dd>
            <?php if(!empty($address['company'])): ?>
                <dt>Company</dt>
                <dd><?php echo $address['company']; ?></dd>
            <?php endif; ?>
            <dt>Address</dt>
            <dd><?php echo $address['address1']; ?></dd>
            <?php if(!empty($address['address2'])): ?>
                <dt></dt>
                <dd><?php echo $address['address2']; ?></dd>
            <?php endif; ?>
            <dt>City</dt>
            <dd><?php echo $address['city']; ?></dd>
            <?php if(!empty($address['state'])): ?>
                <dt>State or Province</dt>
                <dd><?php echo $address['state']; ?></dd>
            <?php endif; ?>
            <dt>Postal Code</dt>
            <dd><?php echo $address['postalCode']; ?></dd>
            <dt>Country</dt>
            <dd><?php echo $address['country']; ?></dd>
        </dl>
    <?php endforeach; ?>
    <h2>Payment Details</h2>
    <dl>
        <dt>Stripe ID</dt>
        <dd><?php echo $order['Payment']['stripe_id']; ?></dd>
        <dt>Last 4 CCD Digits</dt>
        <dd><?php echo $order['Payment']['stripe_last4']; ?></dd>
        <dt>Address Check</dt>
        <dd><?php echo $order['Payment']['stripe_address_check']; ?></dd>
        <dt>Zip Check</dt>
        <dd><?php echo $order['Payment']['stripe_zip_check']; ?></dd>
        <dt>CVC Check</dt>
        <dd><?php echo $order['Payment']['stripe_cvc_check']; ?></dd>
    </dl>
    <?php if(!empty($order['Order']['shipDate'])): ?>
        <h2>Ship Date</h2>
        <dl>
            <dt>Date</dt>
            <dd><?php echo date('M j, Y', strtotime($order['Order']['shipDate'])); ?></dd>
        </dl>
    <?php endif; ?>
</div>
