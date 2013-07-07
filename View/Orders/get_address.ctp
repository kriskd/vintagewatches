<p>All fields marked <span class=required>*</span> must be filled out.</p>
<?php if($data['shipping'] == 'billing'): ?>
    <div class="address-form-billing">
        <h5>Billing and Shipping Address</h5>
        <div class="billing-country-address-form">
            <?php echo $this->Form->addressForm('Address.billing.', $data['country'], $data['statesProvinces'], true, true, $data['values']['billing'], $data['errors']['billing']); ?>
        </div>
    </div>
<?php endif; ?>
<?php if($data['shipping'] == 'shipping'): ?>
    <div class="address-form-billing">
        </div>
        <h5>Billing Address</h5>
        <div class="billing-country-address-form">
            <?php //Billing for shipping to US or Canada can be US or Canada ?>
            <?php $country = (strcasecmp($data['country'], 'us') == 0 ? 'us-ca' : (strcasecmp($data['country'], 'ca') == 0 ? 'ca-us' : $data['country'])); ?>
            <?php echo $this->Form->addressForm('Address.billing.', $country, $data['statesProvinces'], true, true, $data['values']['billing'], $data['errors']['billing']); ?>
        </div>
    </div>
    <div class="address-form-shipping">
        <h5>Shipping Address</h5>
        <div class="shipping-country-address-form">
            <?php echo $this->Form->addressForm('Address.shipping.', $data['country'], $data['statesProvinces'], false, true, $data['values']['shipping'], $data['errors']['shipping']); ?>
            <?php if($data['country'] == 'other'): ?>
                <div class="input text">
                    <?php echo $this->Form->label('Address.shipping.' . $data['country'], 'Country', array('class' => 'control-label')); ?>
                    <div class="billing-country-name"></div>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>
