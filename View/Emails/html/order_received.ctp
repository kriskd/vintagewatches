<table>
    <tr>
        <td style="background: #999; border: 2px solid #665600; padding: 1em; width: 75%">
            <p style="font-size: 36px; font-weight: bold; color: #665600">Bruce's Vintage Watches</p>
            <p style="font-size: 18px; font-weight: bold; color: #665600">Fine Timepieces at a reasonable prices from a name you trust.</p>
        </td>
        <td>
            <p style="font-weight: bold; margin-left: 20px; width: 25%">
                In business since 1989 and offering medium- to high-grade watches with an unconditional seven-day money back guarantee. 
            </p>
        </td>
    </tr>
</table>
<h1>Order Summary</h1>
<table>
    <tr>
        <td colspan=4 style="background: #665600;">
            <h2 style="font-size: 30px; color: #fff">Purchases</h2>
        </td>
    </tr>
    <tr style="padding: 5px"><th></th><th>Stock ID</th><th>Name</th><th>Price</th></tr>
    <?php foreach($order['Watch'] as $watch): ?>
        <tr style="padding: 5px">
            <td>
                <?php echo $this->Html->thumbPrimary($watch, array('fullBase' => true)); ?>
            </td>
            <td><?php echo $watch['stockId']; ?></td>
            <td><?php echo $watch['name']; ?></td>
            <td style="text-align: right"><?php echo $this->Number->currency($watch['price'], 'USD'); ?></td>
        </tr>
    <?php endforeach; ?>
    <tr style="padding: 5px">
        <td></td>
        <td></td>
        <td style="text-align: right">Shipping</td>
        <td style="text-align: right"><?php echo $this->Number->currency($order['Order']['shippingAmount'], 'USD'); ?></td>
    </tr>
    <tr style="padding: 5px">
        <td></td>
        <td></td>
        <td style="text-align: right">Total</td>
        <td style="text-align: right"><?php echo $this->Number->stripe($order['Order']['stripe_amount']); ?></td>
    </tr>
    <?php if(!empty($order['Order']['notes'])): ?>
        <tr>
            <td colspan=4 style="background: #665600;">
                <h2 style="font-size: 30px; color: #fff">Special Order Instructions</h2>
            </td>
        </tr>
        <tr>
            <td colspan=4>
                <p>
                    <?php echo nl2br($order['Order']['notes']); ?>
                </p>
            </td>
        </tr>
    <?php endif; ?>
    <tr>
        <td colspan=4 style="background: #665600;">
            <h2 style="font-size: 30px; color: #fff">Customer Contact Information</h2>
        </td>
    </tr>
    <tr style="padding: 5px">
        <td colspan=2><strong>Email</strong></td>
        <td colspan=2><?php echo $order['Order']['email']; ?></td>
    </tr>
    <?php if (!empty($order['Order']['phone'])): ?>
        <tr style="padding: 5px">
            <td colspan=2><strong>Phone</strong></td>
            <td colspan=2><?php echo $order['Order']['phone']; ?></td>
        </tr>
    <?php endif; ?>
    <?php foreach($order['Address'] as $address): ?>
        <tr>
            <td colspan=4 style="background: #665600;">
                <h3 style="font-size: 24px; color: #fff"><?php echo ucfirst($address['type']); ?> Address</h3>
            </td>
        </tr>
        <tr style="padding: 5px">
            <td colspan=2><strong>Name</strong></td>
            <td colspan=2><?php echo $address['name']; ?></td>
        </tr>
        <?php if(!empty($address['company'])): ?>
        <tr style="padding: 5px">
            <td colspan=2><strong>Company</strong></td>
            <td colspan=2><?php echo $address['company']; ?></td>
        </tr>
        <?php endif; ?>
        <tr style="padding: 5px">
            <td colspan=2><strong>Address</strong></td>
            <td colspan=2><?php echo $address['address1']; ?></td>
        </tr>
        <?php if(!empty($address['address2'])): ?>
            <tr>
                <td colspan=2></td>
                <td colspan=2><?php echo $address['address2']; ?></td>
            </tr>
        <?php endif; ?>
        <tr style="padding: 5px">
            <td colspan=2><strong>City</strong></td>
            <td colspan=2><?php echo $address['city']; ?></td>
        </tr>
        <?php if(!empty($address['state'])): ?>
            <tr style="padding: 5px">
                <td colspan=2><strong>State or Province</strong></td>
                <td colspan=2><?php echo $address['state']; ?></td>
            </tr>
        <?php endif; ?>
        <tr style="padding: 5px">
            <td colspan=2><strong>Postal Code</strong></td>
            <td colspan=2><?php echo $address['postalCode']; ?></td>
        </tr>
        <tr style="padding: 5px">
            <td colspan=2><strong>Country</strong></td>
            <td colspan=2><?php echo $address['country']; ?></td>
        </tr>
    <?php endforeach; ?>
</table>