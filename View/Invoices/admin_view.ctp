<div class="invoices view">
    <div class="row">
        <div class="col-lg-10">
            <?php echo $this->Form->input(false, array(
                                            'value' => 'http://' . env('SERVER_NAME') . DS . $invoice['Invoice']['slug'],
                                            'readonly' => 'readonly',
                                            'class' => 'form-control invoice-url',
                                            'label' => 'URL')); ?>
        </div>
        <div class="col-lg-2">
            <?php echo $this->Html->link('Edit Invoice', array('action' => 'edit', $invoice['Invoice']['id'], 'admin' => true), array('class' => 'btn btn-primary edit-invoice')); ?>
        </div>
    </div>
    <?php echo $this->Element('invoice_top'); ?>
    <?php echo $this->Element('invoice_detail'); ?>
</div>