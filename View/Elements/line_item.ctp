<div class="row line-item">
    <?php if (isset($action) && strcasecmp($action, 'edit')==0): ?>
        <?php echo $this->Form->input('InvoiceItem.'.$i.'.id', array('type' => 'hidden')); ?>
        <?php echo $this->Form->input('InvoiceItem.'.$i.'.invoice_id', array('type' => 'hidden')); ?>
    <?php endif; ?>
    <div class="col-lg-1">
        <?php $options['escape'] = false; ?>
        <?php $options['class'] = 'launch-tooltip'; ?>
        <?php $options['data-toggle'] = 'tooltip'; ?>
        <?php $options['data-placement'] = 'top'; ?>
        <?php $options['title'] = 'Delete Line Item'; ?>
        <?php if (strcasecmp($action, 'edit')==0): ?>
            <?php $options['data-count'] = $i; ?>
            <?php $options['data-description'] = $this->request->data['InvoiceItem'][$i]['description']; ?>
            <?php $options['data-invoice_id'] = $this->request->data['Invoice']['id']; ?>
            <?php $options['data-item_id'] = $this->request->data['InvoiceItem'][$i]['id']; ?>
        <?php endif; ?>
        <?php echo $this->Html->link($this->Html->tag('span', '', array('class' => 'glyphicon glyphicon-remove-sign remove-line-item')),
                                                    '#',
                                                    $options
                                                ); ?>
        
        
    </div>
    <div class="col-lg-3">
        <?php echo $this->Form->input('InvoiceItem.'.$i.'.itemid', array('label' => 'Item Id/Code', 'class' => 'form-control')); ?>
    </div>
    <div class="col-lg-6">
        <?php echo $this->Form->input('InvoiceItem.'.$i.'.description', array('class' => 'form-control')); ?>
    </div>
    <div class="col-lg-2">
        <?php echo $this->Form->input('InvoiceItem.'.$i.'.amount', array('class' => 'form-control', 'min' => 0)); ?>
    </div>
</div>