<div class="address-form-billing">
    <h5>Billing Address</h5>
    <div class="billing-country-address-form"></div>
    <?php echo $this->Form->input('Address.shipping-address', array('type' => 'checkbox', 'label' => 'Ship to a Different Address')); ?>
</div>
<div class="address-form-shipping">
    <h5>Shipping Address</h5>
    <div class="shipping-country-address-form"></div>
</div>
<div class="shipping-instructions">
    <h5>Special Shipping Instructions</h5>
    <?php echo $this->form->textarea('Order.shipping-instructions'); ?>
</div>