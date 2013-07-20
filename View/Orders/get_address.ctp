<p>All fields marked <span class=required>*</span> must be filled out.</p>
<?php if($data['shipping'] == 'billing'): ?>
    <div class="address-form-billing">
        <h5>Billing and Shipping Address</h5>
        <div class="checkout-form">
            <?php echo $this->Form->addressForm('billing', $data, true, true); ?>
        </div>
    </div>
<?php endif; ?>
<?php if($data['shipping'] == 'shipping'): ?>
    <div class="address-form-billing"></div>
        <h5>Billing Address</h5>
        <div class="checkout-form">
            <?php //Billing for shipping to US or Canada can be US or Canada ?>
            <?php $data['country'] = (strcasecmp($data['country'], 'us') == 0 ? 'us-ca' : (strcasecmp($data['country'], 'ca') == 0 ? 'ca-us' : $data['country'])); ?>
            <?php echo $this->Form->addressForm('billing', $data, true, true); ?>
        </div>
    </div>
    <div class="address-form-shipping">
        <h5>Shipping Address</h5>
        <div class="checkout-form">
            <?php echo $this->Form->addressForm('shipping', $data, false, true); ?>
            <?php if($data['country'] == 'other'): ?>
                <div class="input text">
                    <?php echo $this->Form->label('Address.shipping.' . $data['country'], 'Country', array('class' => 'control-label')); ?>
                    <div class="billing-country-name"></div>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>
