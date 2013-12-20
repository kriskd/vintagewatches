<?php echo $this->Form->create('Invoice', array('class' => 'form-horizontal',
                                             'inputDefaults' => array(
                                                                    'class' => 'form-control'
                                                                )
                                             )); ?>

<fieldset>
    <legend><?php echo $action; ?> Invoice</legend>
    <?php echo $this->Html->link('Add Line Item', '#', array('class' => 'btn btn-success add-line-item')); ?>
    <?php if (strcasecmp($action, 'edit')==0): ?>
        <?php echo $this->Form->input('Invoice.id', array('type' => 'hidden')); ?>
    <?php endif; ?>
    <?php echo $this->Form->input('active', array(
                                                    'label' => array('class' => 'control-label'),
                                                    'div' => 'checkbox-inline',
                                                    'checked' => isset($this->request->data['Invoice']['active']) ? $this->request->data['Invoice']['active'] : true
                                                )); ?>
    <div class="row">
        <?php if (strcasecmp($action, 'edit')==0): ?>
            <?php echo $this->Form->input('Address.0.id', array('type' => 'hidden')); ?>
            <?php echo $this->Form->input('Address.0.foreign_id', array('type' => 'hidden')); ?>
        <?php endif; ?>
        <div class="col-lg-6">
            <?php echo $this->Form->input('Address.0.firstName'); ?>
        </div>
        <div class="col-lg-6">
            <?php echo $this->Form->input('Address.0.lastName'); ?>
        </div>
    </div>
    <?php echo $this->Form->input('Invoice.email'); ?>
    <?php if (empty($this->request->data['InvoiceItem'])): ?>
        <?php echo $this->Element('line_item', array('action' => $action)); ?>
    <?php else: ?>
        <?php foreach ($this->request->data['InvoiceItem'] as $i => $item): ?>
            <?php echo $this->Element('line_item', array('i' => $i, 'action' => $action)); ?>
        <?php endforeach; ?>
    <?php endif; ?>
    <div class="line-items"></div>
    <div class="row">
        <div class="col-lg-9"></div>
        <div class="col-lg-3">
            <?php echo $this->Form->input('Invoice.shippingAmount', array('min' => 0)); ?>
        </div>
    </div>
</fieldset>

<?php echo $this->Form->end(array('label' => 'Save', 'class' => 'btn btn-primary')); ?>