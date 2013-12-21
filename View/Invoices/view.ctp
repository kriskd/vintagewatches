<div class="invoices view">
    <?php echo $this->Element('header'); ?>
    <div class="row">
        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
            <?php echo $this->Element('invoice_top'); ?>
            <?php echo $this->Element('invoice_detail'); ?>
            <section class="credit-card credit-card-invoice">
                <?php echo $this->Element('checkout_credit_card'); ?>
            </section>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
            <?php //Display this after payment submitted ?>
            <?php //echo $this->Element('recent_watches'); ?>
        </div>
    </div>
</div>