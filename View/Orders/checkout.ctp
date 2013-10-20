<?php echo $this->Form->create('Order', array('class' => 'form-horizontal payment-form')); ?>
<div class="orders checkout col-lg-6 col-md-6 col-sm-12 col-xs-12">
    <?php echo $this->Element('checkout_review_cart', compact('watch')); ?>
    <?php echo $this->Element('checkout_shipping'); ?>
    <?php echo $this->Element('checkout_address'); ?>
    <?php echo $this->Element('checkout_credit_card'); ?>  
</div>

<?php $this->append('script'); ?>
    <?php echo $this->Html->script('https://js.stripe.com/v2/'); ?>
    <?php echo '<script type="text/javascript">Stripe.setPublishableKey("' . Configure::read('Stripe.TestPublishable') . '");</script>'; ?>
    <script type="text/javascript">
        $(document).ready(function(){
            Stripe.setPublishableKey("<?php echo Configure::read('Stripe.TestPublishable'); ?>")
        });
    </script>
<?php $this->end(); ?>