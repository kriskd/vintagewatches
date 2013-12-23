<h3><small class="pull-right hidden-xxs"><span class="glyphicon glyphicon-arrow-right"></span> Enter Credit Card</small>Credit Card</h3>
<div class="checkout-form">
    <div class="payment-errors alert alert-error"></div>
    <?php echo $this->Form->ccd($payment_type); ?>
    <?php echo $this->Form->cvc($payment_type); ?>
    <div class="input select required">
        <?php if ($payment_type == 'invoice'): ?>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <?php else: ?>
            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
        <?php endif; ?>
            <?php echo $this->Form->label('Card.month', 'Expiration (MM/YYYY)',
                                      array(
                                        'for' => 'CardMonth',
                                        'class' => 'control-label'
                                    )
                                ); ?>
        </div>
        <?php echo $this->Form->expy($payment_type); ?>
        <?php echo $this->Form->end(array('class' => 'btn btn-gold submit-payment',
                                      'label' => 'Submit Payment',
                                      'div' => 'submit col-xs-11')); ?>
    </div>
</div>
