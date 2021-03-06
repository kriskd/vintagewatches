<table style="width: 600px;">
    <tr>
        <td bgcolor="#999999" style="border: 2px solid #665600; padding: 0px 10px; width: 450px">
            <p style="font-size: 36px; font-weight: bold; color: #665600">Bruce's Vintage Watches</p>
            <p style="font-size: 18px; font-weight: bold; color: #665600">Fine timepieces at reasonable prices from a name you trust.</p>
        </td>
        <td>
            <p style="font-weight: bold; margin-left: 20px; width: 150px">
                In business since 1989 and offering medium- to high-grade watches with an unconditional seven-day money back guarantee.
            </p>
        </td>
    </tr>
</table>
<h1 style="font-size: 36px;">Order Summary</h1>
<table style="width: 600px;">
    <tr>
        <td colspan=4 style="padding: 0px 10px;">
            <p style="font-size: 30px; color: #665600">Purchases</p>
        </td>
    </tr>
    <tr style="padding: 5px"><th></th><th>Stock ID</th><th>Name</th><th>Price</th></tr>
    <?php foreach($order['Watch'] as $watch): ?>
        <tr style="padding: 5px">
            <td>
                <?php echo $this->Html->thumbPrimary($watch, array(
                    'fullBase' => true,
                    'url' => array(
                        'controller' => 'watches',
                        'action' => 'order', $watch['id'],
                        'full_base' => true,
                        'admin' => false,
                    ),
                )); ?>
            </td>
            <td><?php echo $watch['stockId']; ?></td>
            <td><?php echo $this->Html->link($watch['name'], array(
                'controller' => 'watches',
                'action' => 'order', $watch['id'],
                'full_base' => true,
                'admin' => false,
            )); ?></td>
            <td style="text-align: right"><?php echo $this->Number->currency($watch['price'], 'USD'); ?></td>
        </tr>
    <?php endforeach; ?>
    <?php foreach($order['OrderExtra'] as $extra): ?>
        <tr style="padding: 5px">
            <td>
                <?php if (!empty($extra['Item']['filenameThumb'])): ?>
                    <?php echo $this->Html->image($extra['Item']['filenameThumb']); ?>
                <?php endif; ?>
            </td>
            <td></td>
            <td><?php echo $extra['quantity']; ?> <?php echo $extra['Item']['name'] ?></td>
            <td style="text-align: right"><?php echo $this->Number->currency($extra['quantity'] * $extra['price'], 'USD'); ?></td>
        </tr>
    <?php endforeach; ?>
    <tr style="padding: 5px">
        <td></td>
        <td></td>
        <td style="text-align: right">Shipping</td>
        <td style="text-align: right"><?php echo $this->Number->currency($order['Order']['shippingAmount'], 'USD'); ?></td>
    </tr>
    <?php if (isset($order['Coupon'])): ?>
        <tr style="padding: 5px">
            <td></td>
            <td></td>
            <td style="text-align: right">
                Coupon code <?php echo strtoupper($order['Coupon']['code']); ?>
            </td>
            <td style="text-align: right">
                <?php echo $this->Number->couponValue($order); ?>
            </td>
        </tr>
    <?php endif; ?>
    <tr style="padding: 5px">
        <td></td>
        <td></td>
        <td style="text-align: right">Total</td>
        <td style="text-align: right"><?php echo $this->Number->stripe($order['Payment']['stripe_amount']); ?></td>
    </tr>
    <?php if(!empty($order['Order']['notes'])): ?>
        <tr>
            <td colspan=4 style="padding: 0px 10px;">
                <p style="font-size: 30px; color: #665600">Special Order Instructions</p>
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
        <td colspan=4 style="padding: 0px 10px;">
            <p style="font-size: 30px; color: #665600">Customer Contact Information</p>
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
        <?php echo $this->Element('email_address', compact('address')); ?>
    <?php endforeach; ?>
    <?php if (!empty($order['Order']['shipDate'])): ?>
        <tr>
            <td colspan=4 style="padding: 0px 10px;">
                <p style="font-size: 24px; color: #665600">Ship Date</p>
            </td>
        </tr>
        <tr>
            <td colspan=2>
                <strong>Date Shipped</strong>
            </td>
            <td colspan=2>
                <?php echo date('F j, Y', strtotime($order['Order']['shipDate'])); ?>
            </td>
        </tr>
    <?php endif; ?>
    <tr>
        <td colspan=4>
            <p>
                Visit <?php echo $this->Html->link('brucesvintagewatches.com/orders', 'http://brucesvintagewatches.com/orders'); ?> to get your entire order history.
            </p>
        </td>
    </tr>
</table>
