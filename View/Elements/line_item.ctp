<div class="row line-item">
    <div class="col-lg-3">
        <?php echo $this->Form->input('InvoiceItem.'.$i.'.itemid', array('label' => 'Item Id/Code', 'class' => 'form-control')); ?>
    </div>
    <div class="col-lg-6">
        <?php echo $this->Form->input('InvoiceItem.'.$i.'.description', array('class' => 'form-control')); ?>
    </div>
    <div class="col-lg-3">
        <?php echo $this->Form->input('InvoiceItem.'.$i.'.amount', array('class' => 'form-control')); ?>
    </div>
</div>