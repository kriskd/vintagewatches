<?php echo $this->Form->create('Invoice', array('class' => 'form-horizontal',
                                             'inputDefaults' => array(
                                                                    'class' => 'form-control'
                                                                )
                                             )); ?>
<?php if (strcasecmp($action, 'edit')==0): ?>
    <?php echo $this->Form->input('Invoice.id', array('type' => 'hidden')); ?>
<?php endif; ?>
<fieldset>
    <legend><?php echo $action; ?> Invoice</legend>
    <div class="row">
        <div class="col-lg-11 col-md-10">
            <?php echo $this->Form->input('Invoice.email'); ?>
        </div>
        <div class="col-lg-1 col-md-2 text-right">
            <?php echo $this->Form->input('active', array(
                                                        'label' => array('class' => 'control-label'),
                                                        'div' => 'checkbox-inline',
                                                        'class' => '', //Override inputDefault
                                                        'checked' => isset($this->request->data['Invoice']['active']) ? $this->request->data['Invoice']['active'] : true
                                                    )); ?>
        </div>
    </div>
    <?php if (strcasecmp($action, 'edit')==0): ?>
        <?php echo $this->Form->input('Address.0.id', array('type' => 'hidden')); ?>
        <?php echo $this->Form->input('Address.0.foreign_id', array('type' => 'hidden')); ?>
    <?php endif; ?>
    <div class="row">
        <div class="col-lg-6">
            <?php echo $this->Form->input('Address.0.firstName'); ?>
        </div>
        <div class="col-lg-6">
            <?php echo $this->Form->input('Address.0.lastName'); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <?php echo $this->Form->input('Address.0.address1'); ?>
        </div>
        <div class="col-lg-12">
            <?php echo $this->Form->input('Address.0.address2'); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <?php echo $this->Form->input('Address.0.city'); ?>
        </div>
        <div class="col-lg-3">
            <?php echo $this->Form->input('Address.0.state', array('options' => $options)); ?>
        </div>
        <div class="col-lg-3">
            <?php echo $this->Form->input('Address.0.postalCode'); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <?php $options['options'] = $countries; ?>
            <?php if (strcasecmp($action, 'add')==0): ?>
                <?php $options['default'] = 'US'; ?>
            <?php endif; ?>
            <?php echo $this->Form->input('Address.0.country', $options); ?>
        </div>
        <div class="col-lg-6">
            <?php if (strcasecmp($action, 'edit')==0): ?>
                <?php echo $this->Form->input('Invoice.shipDate', array('type' => 'text')); ?>
            <?php endif; ?>
        </div>
    </div>
    <div class="line-items">
        <?php if (empty($this->request->data['InvoiceItem'])): ?>
            <?php echo $this->Element('line_item', array('action' => $action)); ?>
        <?php else: ?>
            <?php foreach ($this->request->data['InvoiceItem'] as $i => $item): ?>
                <?php echo $this->Element('line_item', array('i' => $i, 'action' => $action)); ?>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <div class="row">
        <div class="col-lg-10">
            <?php echo $this->Html->link('Add Line Item', '#', array('class' => 'btn btn-success add-line-item')); ?>
        </div>
        <div class="col-lg-2">
            <?php echo $this->Form->input('Invoice.shippingAmount', array('min' => 0)); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <?php echo $this->Form->input('invoiceNotes', array('label' => 'Invoice Notes <small>(Notes to customer)</small>')); ?>
        </div>
    </div>
</fieldset>

<?php echo $this->Form->end(array('label' => 'Save', 'class' => 'btn btn-primary')); ?>