<h1>Order Summary</h1>
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
<h2>Purchases</h2>
<table>
    <tr><th>Name</th><th>Price</th></tr>
    <?php foreach($order['Watch'] as $watch): ?>
        <tr>
            <td><?php echo $watch['name']; ?></td>
            <td><?php echo $this->Number->currency($watch['price'], 'USD'); ?></td>
        </tr>
    <?php endforeach; ?>
</table>