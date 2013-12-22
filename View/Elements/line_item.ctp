<div class="row line-item">
    <?php if (isset($action) && strcasecmp($action, 'edit')==0): ?>
        <?php echo $this->Form->input('InvoiceItem.'.$i.'.id', array('type' => 'hidden')); ?>
        <?php echo $this->Form->input('InvoiceItem.'.$i.'.invoice_id', array('type' => 'hidden')); ?>
    <?php endif; ?>
    <div class="col-lg-1">
        <?php if (strcasecmp($action, 'edit')==0): ?>
            <?php echo $this->Html->link($this->Html->tag('span', '', array('class' => 'glyphicon glyphicon-remove-sign remove-line-item')),
                                                        '#',
                                                        array(
                                                            'data-toggle' => 'modal',
                                                            'escape' => false,
                                                            'data-count' => $i,
                                                            'data-description' => $this->request->data['InvoiceItem'][$i]['description'],
                                                            'data-invoice_id' => $this->request->data['Invoice']['id'],
                                                            'data-item_id' => $this->request->data['InvoiceItem'][$i]['id']
                                                        )
                                                    ); ?>
        <?php elseif (strcasecmp($action, 'add')==0): ?>
            <?php echo $this->Html->link($this->Html->tag('span', '', array('class' => 'glyphicon glyphicon-remove-sign remove-line-item')),
                                                        '#',
                                                        array('escape' => false)
                                                       ); ?>
        <?php endif; ?>
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