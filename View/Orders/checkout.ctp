<?php echo $this->Form->create('Order', array('class' => 'form-horizontal payment-form')); ?>
<div class="orders checkout col-lg-6 col-md-9 col-sm-12 col-xs-12">
    <p>If you would like to continue shopping, click the <strong>"Store"</strong> icon above.
    The item(s) below will remain in your cart. If you are done shopping,
    begin the checkout process by choosing the country where you want your
    order shipped.</p>
    <?php echo $this->Element('checkout_review_cart'); ?>
    <?php echo $this->Element('checkout_shipping'); ?>
    <?php echo $this->Element('checkout_address'); ?>
    <section class="credit-card credit-card-order hide">
        <?php echo $this->Element('checkout_credit_card', array('payment_type' => 'order')); ?>
    </section>
</div>
