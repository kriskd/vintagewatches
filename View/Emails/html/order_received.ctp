<h1>Order Summary</h1>
<h2>Purchases</h2>
<table style="padding: 5px">
    <tr><th></th><th>Stock ID</th><th>Name</th><th>Price</th></tr>
    <?php foreach($order['Watch'] as $watch): ?>
        <tr>
            <td>
                <?php echo $this->Html->thumbPrimary($watch, array('fullBase' => true)); ?>
            </td>
            <td><?php echo $watch['stockId']; ?></td>
            <td><?php echo $watch['name']; ?></td>
            <td style="text-align: right"><?php echo $this->Number->currency($watch['price'], 'USD'); ?></td>
        </tr>
    <?php endforeach; ?>
    <tr>
        <td></td>
        <td></td>
        <td style="text-align: right">Shipping</td>
        <td style="text-align: right"><?php echo $this->Number->currency($order['Order']['shippingAmount'], 'USD'); ?></td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td style="text-align: right">Total</td>
        <td style="text-align: right"><?php echo $this->Number->stripe($order['Order']['stripe_amount']); ?></td>
    </tr>
</table>
<hr />
<?php if(!empty($order['Order']['notes'])): ?>
    <h2>Special Order Instructions</h2>
    <p>
        <?php echo nl2br($order['Order']['notes']); ?>
    </p>
    <hr />
<?php endif; ?>
<h2>Customer Contact Information</h2>
<table style="padding: 5px">
    <tr>
        <td><strong>Email</strong></td>
        <td><?php echo $order['Order']['email']; ?></td>
    </tr>
    <tr>
        <td><strong>Phone</strong></td>
        <td><?php echo $order['Order']['phone']; ?></td>
    </tr>
</table>
<hr />
<?php foreach($order['Address'] as $address): ?>
    <h3><?php echo ucfirst($address['type']); ?> Address</h3>
    <table style="padding: 5px">
        <tr>
            <td><strong>Name</strong></td>
            <td><?php echo $address['name']; ?></td>
        </tr>
        <?php if(!empty($address['company'])): ?>
        <tr>
            <td><strong>Company</strong></td>
            <td><?php echo $address['company']; ?></td>
        </tr>
        <?php endif; ?>
        <tr>
            <td><strong>Address</strong></td>
            <td><?php echo $address['address1']; ?></td>
        </tr>
        <?php if(!empty($address['address2'])): ?>
            <tr>
                <td></td>
                <td><?php echo $address['address2']; ?></td>
            </tr>
        <?php endif; ?>
        <tr>
            <td><strong>City</strong></td>
            <td><?php echo $address['city']; ?></td>
        </tr>
        <?php if(!empty($address['state'])): ?>
            <tr>
                <td><strong>State or Province</strong></td>
                <td><?php echo $address['state']; ?></td>
            </tr>
        <?php endif; ?>
        <tr>
            <td><strong>Postal Code</strong></td>
            <td><?php echo $address['postalCode']; ?></td>
        </tr>
        <tr>
            <td><strong>Country</strong></td>
            <td><?php echo $address['country']; ?></td>
        </tr>
    </table>
<?php endforeach; ?>