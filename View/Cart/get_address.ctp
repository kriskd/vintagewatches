<?php if($data['shipping'] == 'billing-change'): ?>
    <?php echo $this->Form->addressForm('Address.billing.', $data['country'], true, true); ?>
<?php endif; ?>
<?php if($data['shipping'] == 'billing'): ?>
    <div class="address-form-billing">
        <h5>Billing and Shipping Address</h5>
        <div class="billing-country-address-form">
            <?php echo $this->Form->addressForm('Address.billing.', $data['country'], true, true); ?>
        </div>
    </div>
<?php endif; ?>
<?php if($data['shipping'] == 'shipping'): ?>
    <div class="address-form-billing">
        <div class="navbar">
            <div class="navbar-inner">
            <span class="brand" href="#">Choose Billing Country <i class="icon-angle-right"></i></span>
                <ul class="nav">
                    <li class="<?php echo $data['country'] == 'us' ? 'active' : ''; ?>">
                        <?php echo $this->Html->link('U.S.', array('controller' => 'cart', 'action' => 'getAddress.html', '?' => array('shipping' => 'billing-change', 'country' => 'us'))); ?>
                    </li>
                    <li class="<?php echo $data['country'] == 'ca' ? 'active' : ''; ?>">
                        <?php echo $this->Html->link('Canada', array('controller' => 'cart', 'action' => 'getAddress.html', '?' => array('shipping' => 'billing-change', 'country' => 'ca'))); ?>
                    </li>
                    <li class="<?php echo $data['country'] == 'other' ? 'active' : ''; ?>">
                        <?php echo $this->Html->link('Other', array('controller' => 'cart', 'action' => 'getAddress.html', '?' => array('shipping' => 'billing-change', 'country' => 'other'))); ?>
                    </li>
                </ul>
            </div>
        </div>
        <h5>Billing Address</h5>
        <div class="billing-country-address-form">
            <?php echo $this->Form->addressForm('Address.billing.', $data['country'], true, true); ?>
        </div>
    </div>
    <div class="address-form-shipping">
        <h5>Shipping Address</h5>
        <div class="shipping-country-address-form">
            <?php echo $this->Form->addressForm('Address.shipping.', $data['country']); ?>
        </div>
    </div>
<?php endif; ?>
