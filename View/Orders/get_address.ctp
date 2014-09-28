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
            <?php //Reassign $data to a different var since billing can be either US or Canada, ?>
            <?php //Otherwise shipping options will be US and Canada ?>
            <?php $billingData = $data; ?>
            <?php //Billing for shipping to US or Canada can be US or Canada ?>
            <?php $billingData['country'] = (strcasecmp($billingData['country'], 'us') == 0 ? 'us-ca' : (strcasecmp($billingData['country'], 'ca') == 0 ? 'ca-us' : $billingData['country'])); ?>
            <?php echo $this->Form->addressForm('billing', $billingData, true, true); ?>
        </div>
    </div>
    <div class="address-form-shipping">
        <h5>Shipping Address</h5>
        <?php if (strcasecmp($billingData['country'], 'other')==0): ?>
            <p class="country-ship-rule">
                Orders must be shipped with in the same country as the billing
                address. <?php echo $this->Html->link('Contact me', array(
                                                                        'controller' => 'contacts',
                                                                        'action' => 'index'
                                                                    )); ?> 
                about options to ship your order outside of your billing country.
            </p>
        <?php endif; ?>
        <div class="checkout-form">
            <?php echo $this->Form->addressForm('shipping', $data, false, true); ?>
            <?php if($data['country'] == 'other'): ?>
                <div class="form-group">
                    <?php echo $this->Form->label('Address.shipping.' . $data['country'], 'Country', array('class' => 'control-label col-xs-12 col-sm-4 col-md-4 col-lg-4')); ?>
                    <div class="clearfix">
                        <div class="col-xs-11 col-sm-7 col-md-7 col-lg-7">
                            <div class="billing-country-name">
                                <?php echo isset($this->request->data['Address']['billing']['countryName']) ? $this->request->data['Address']['billing']['countryName'] : ''; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>
