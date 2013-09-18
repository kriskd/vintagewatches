<div class="orders admin-view">
    <h1>Order <?php echo $order['Order']['id']; ?> Detail</h1>
    <h2>Purchases</h2>
    <dl>
        <dt>Date</dt>
        <dd><?php echo $order['Order']['created']; ?></dd>
    </dl>
    <table class="table table-bordered">
        <tr><th></th><th>Stock ID</th><th>Name</th><th>Price</th></tr>
        <?php foreach($order['Watch'] as $watch): ?>
            <tr>
                <td>
                    <?php echo $this->Html->thumbImagePrimary($watch); ?>
                </td>
                <td><?php echo $watch['stockId']; ?></td>
                <td><?php echo $watch['name']; ?></td>
                <td class="text-right"><?php echo $this->Number->currency($watch['price'], 'USD'); ?></td>
            </tr>
        <?php endforeach; ?>
        <tr class="total-row">
            <td></td>
            <td></td>
            <td class="text-right">Shipping</td>
            <td class="total-formatted-amount text-right"><?php echo $this->Number->currency($order['Order']['shippingAmount'], 'USD'); ?></td>
        </tr>
        <tr class="total-row">
            <td></td>
            <td></td>
            <td class="text-right">Total</td>
            <td class="total-formatted-amount text-right"><?php echo $this->Number->currency($order['Order']['stripe_amount']/100, 'USD'); ?></td>
        </tr>
    </table>
    <h2>Contact Information</h2>
    <dl>
        <dt>Email</dt>
        <dd><?php echo $order['Order']['email']; ?></dd>
        <?php if(!empty($order['Order']['phone'])): ?>
            <dt>Phone</dt>
            <dd><?php echo $order['Order']['phone']; ?></dd>
        <?php endif; ?>
    </dl>
    <?php if(!empty($order['Order']['notes'])): ?>
        <h2>Special Order Instrucations</h2>
        <div class="notes">
            <?php echo nl2br($order['Order']['notes']); ?>
        </div>
    <?php endif; ?>
    <?php foreach($order['Address'] as $address): ?>
        <h3><?php echo ucfirst($address['type']); ?> Address</h3>
        <dl>
            <dt>Name</dt>
            <dd><?php echo $address['firstName']; ?> <?php echo $address['lastName']; ?></dd>
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
        <dd><?php echo $order['Order']['stripe_id']; ?></dd>
        <dt>Last 4 CCD Digits</dt>
        <dd><?php echo $order['Order']['stripe_last4']; ?></dd>
        <dt>Address/Zip Check</dt>
        <dd><?php echo $order['Order']['stripe_address_zip_check']; ?></dd>
        <dt>CVC Check</dt>
        <dd><?php echo $order['Order']['stripe_cvc_check']; ?></dd>
    </dl>
</div>