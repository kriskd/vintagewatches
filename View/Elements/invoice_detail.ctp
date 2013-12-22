<div class="table">
    <div class="row head table-row">
         <span class="col-lg-3 col-md-3 col-sm-3 col-xs-3 text-center table-head">
             Item Id/Code
         </span>
         <span class="col-lg-6 col-md-6 col-sm-6 col-xs-6 text-center table-head">
             Description
         </span>
         <span class="col-lg-3 col-md-3 col-sm-3 col-xs-3 text-center table-head">
             Amount
         </span>
     </div>
     <?php foreach ($invoice['InvoiceItem'] as $item): ?>
         <div class="row items table-row">
             <span class="col-lg-3 col-md-3 col-sm-3 col-xs-3 table-cell">
                 <?php echo $item['itemid']; ?>
             </span>
             <span class="col-lg-6 col-md-6 col-sm-6 col-xs-6 table-cell">
                 <?php echo $item['description']; ?>
             </span>
             <span class="col-lg-3 col-md-3 col-sm-3 col-xs-3 table-cell text-right">
                 <?php echo $this->Number->currency($item['amount'], 'USD'); ?>
             </span>
         </div>
     <?php endforeach; ?>
     <div class="row shipping table-row">
         <span class="col-lg-9 col-md-9 col-sm-9 col-xs-9 table-cell text-right">
                Shipping
         </span>
         <span class="col-lg-3 col-md-3 col-sm-3 col-xs-3 table-cell text-right">
                <?php echo $this->Number->currency($invoice['Invoice']['shippingAmount'], 'USD'); ?>
         </span>
     </div>
     <div class="row total table-row">
         <span class="col-lg-9 col-md-9 col-sm-9 col-xs-9 table-cell text-right">
                Total
         </span>
         <span class="col-lg-3 col-md-3 col-sm-3 col-xs-3 table-cell text-right">
                <?php echo $this->Number->currency($this->Invoice->total($invoice), 'USD'); ?>
         </span>
     </div>
 </div>