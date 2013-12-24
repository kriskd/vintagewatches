<div class="row line-item">
    <?php if (isset($action) && strcasecmp($action, 'edit')==0): ?>
        <?php echo $this->Form->input('InvoiceItem.'.$i.'.id', array('type' => 'hidden')); ?>
        <?php echo $this->Form->input('InvoiceItem.'.$i.'.invoice_id', array('type' => 'hidden')); ?>
    <?php endif; ?>
    <div class="delete-icon-item-id">
        <div class="col-lg-1 col-md-1 col-sm-1 col-sm-push-11 col-xs-12 col-lg-push-0 col-md-push-0 delete-icon">
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
            <?php if (!isset($this->request->data['Payment']) || $this->request->data['Payment']['stripe_paid'] != 1): ?>
                <?php echo $this->Html->link($this->Html->tag('span', '', array('class' => 'glyphicon glyphicon-remove-sign remove-line-item')),
                                                            '#',
                                                            $options
                                                        ); ?>
            <?php endif; ?>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-11 col-sm-pull-1 col-xs-12 col-lg-pull-0 col-md-pull-0">
            <?php echo $this->Form->invoiceItem('InvoiceItem.'.$i.'.itemid', array('label' => 'Item Id/Code', 'class' => 'form-control'), $this->request->data); ?>
        </div>
    </div>
    <div class="clear-me">
        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 item-description">
            <?php echo $this->Form->invoiceItem('InvoiceItem.'.$i.'.description', array('class' => 'form-control'), $this->request->data); ?>
        </div>
        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
            <?php echo $this->Form->invoiceItem('InvoiceItem.'.$i.'.amount', array('min' => 0, 'class' => 'form-control'), $this->request->data); ?>
        </div>
    </div>
</div>