<?php echo $this->Form->create(false, array('id' => 'payment-form')); ?>
<form action="" method="POST" id="payment-form">
  <span class="payment-errors"></span>

  <div class="form-row">
    <label>
      <span>Card Number</span>
      <input type="text" size="20" data-stripe="number" class="card-number" />
    </label>
  </div>

  <div class="form-row">
    <label>
      <span>CVC</span>
      <input type="text" size="4" data-stripe="cvc" class="card-cvc" />
    </label>
  </div>

  <div class="form-row">
    <label>
      <span>Expiration (MM/YYYY)</span>
      <input type="text" size="2" data-stripe="exp-month" class="card-expiry-month" />
    </label>
    <span> / </span>
    <input type="text" size="4" data-stripe="exp-year" class="card-expiry-year" />
  </div>
<?php echo $this->Form->end('Submit Payment'); ?>
<div id="payment-errors"></div>

<!--
<form action="" method="POST">
  <script
    src="https://checkout.stripe.com/v2/checkout.js" class="stripe-button"
    data-key="pk_test_4E5gc1VRX2I7HcAnomP1jxXY"
    data-amount="2000"
    data-name="Demo Site"
    data-description="2 widgets ($20.00)"
    data-image="/128x128.png">
  </script>
</form>
-->
