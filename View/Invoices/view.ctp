<div class="invoices view">
    <?php echo $this->Element('header'); ?>
    <div class="row">
        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
            <?php echo $this->Element('invoice_top'); ?>
            <?php echo $this->Element('invoice_customer', compact('invoice')); ?>
            <?php echo $this->Element('invoice_detail'); ?>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
            <?php echo $this->Element('recent_watches'); ?>
        </div>
    </div>
</div>