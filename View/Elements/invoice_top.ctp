<h2 class="invoice-top">
    <div class="row">
        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
            Invoice No. <?php echo $invoice['Invoice']['id']; ?>
            <?php echo isset($paid) && $paid == true ? '<span class="green smaller"><span class="glyphicon glyphicon-check"></span>Paid ' . date('M j, Y', strtotime($invoice['Payment']['created'])) . '</span>' : ''; ?>        
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 invoice-date">
            <small><?php echo date('F j, Y', strtotime($invoice['Invoice']['created'])); ?></small>
        </div>
    </div>
</h2>