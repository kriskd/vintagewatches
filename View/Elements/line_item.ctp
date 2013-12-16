<div class="row">
    <div class="col-lg-3">
        <?php echo $this->Form->input('InvoiceItem.itemid', array('label' => 'Item Id/Code', 'class' => 'form-control')); ?>
    </div>
    <div class="col-lg-6">
        <?php echo $this->Form->input('InvoiceItem.description', array('class' => 'form-control')); ?>
    </div>
    <div class="col-lg-3">
        <?php echo $this->Form->input('InvoiceItem.amount', array('class' => 'form-control')); ?>
    </div>
</div>