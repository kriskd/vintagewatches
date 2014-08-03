<div class="order-details">
    <h1>Order Summary</h1>
    <h2>Purchases</h2>
    <div class="row head">
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
            
        </div>
        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
            Stock ID
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
            Name
        </div>
        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
            Price
        </div>
    </div>
    <?php foreach($order['Watch'] as $watch): ?>
        <div class="row watch">
            <div class="image col-lg-4 col-md-4 col-sm-4 col-xs-4">
                <?php echo $this->Html->thumbPrimary($watch, array(
                                                                    'class' => 'img-responsive'
                                                                )
                                                          ); ?>
            </div>
            <div class="text-center col-lg-2 col-md-2 col-sm-2 col-xs-2">
                <?php echo h($watch['stockId']); ?>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                <?php echo $watch['name']; ?>
            </div>
            <div class="text-right col-lg-2 col-md-2 col-sm-2 col-xs-2">
                <?php echo h($this->Number->currency($watch['price'], 'USD')); ?>
            </div>
        </div>
    <?php endforeach; ?>
    <div class="row choose-ship">
        <div class="text-right col-lg-10 col-md-10 col-sm-10 col-xs-10">
            Shipping
        </div>
        <div class="shipping-amount text-right col-lg-2 col-md-2 col-sm-2 col-xs-2">
            <?php echo $this->Number->currency($order['Order']['shippingAmount'], 'USD'); ?>
        </div>
    </div>
    <?php if (isset($order['Coupon'])): ?>
        <div class="row">
            <div class="text-right col-lg-10 col-md-10 col-sm-10 col-xs-10">
                Coupon code <?php echo strtoupper($order['Coupon']['code']); ?> 
            </div>
            <div class="shipping-amount text-right col-lg-2 col-md-2 col-sm-2 col-xs-2">
                <?php echo $this->Order->couponValue($order); ?>
            </div>
        </div>
    <?php endif; ?>
    <div class="row">
        <div class="text-right col-lg-10 col-md-10 col-sm-10 col-xs-10">
            Total
        </div>
        <div class="total-formatted-amount text-right col-lg-2 col-md-2 col-sm-2 col-xs-2">
            <?php echo isset($order['Payment']['stripe_amount']) ? $this->Number->stripe($order['Payment']['stripe_amount']) : '0.00'; ?>
        </div>
    </div>
    <?php if(!empty($order['Order']['notes'])): ?>
        <h2>Special Order Instructions</h2>
        <div class="notes">
            <?php echo nl2br($order['Order']['notes']); ?>
        </div>
    <?php endif; ?>
    <h2>Customer Contact Information</h2>
    <dl>
        <dt>Email</dt>
        <dd><?php echo $order['Order']['email']; ?></dd>
        <?php if(!empty($order['Order']['phone'])): ?>
            <dt>Phone</dt>
            <dd><?php echo $order['Order']['phone']; ?></dd>
        <?php endif; ?>
    </dl>
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
    <?php if (!empty($order['Order']['shipDate'])): ?>
        <h2>Date Shipped</h2>
        <?php echo date('F j, Y', strtotime($order['Order']['shipDate'])); ?>
    <?php endif; ?>
</div>
