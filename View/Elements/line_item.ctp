<div class="row line-item">
    <?php if (isset($action) && strcasecmp($action, 'edit')==0): ?>
        <?php echo $this->Form->input('InvoiceItem.'.$i.'.id', array('type' => 'hidden')); ?>
        <?php echo $this->Form->input('InvoiceItem.'.$i.'.invoice_id', array('type' => 'hidden')); ?>
    <div class="col-lg-1">
        <?php echo $this->Html->link($this->Html->tag('span', '', array('class' => 'glyphicon glyphicon-remove-sign',
                                                                    'data-id' => $this->request->data['InvoiceItem'][$i]['id']
                                                                    )
                                                     ),
                                     '#delete-line-item-'.$i,
                                     array('data-toggle' => 'modal',
                                           'escape' => false)
                                    ); ?>
    </div>
    <?php endif; ?>
    <div class="col-lg-3">
        <?php echo $this->Form->input('InvoiceItem.'.$i.'.itemid', array('label' => 'Item Id/Code', 'class' => 'form-control')); ?>
    </div>
    <div class="col-lg-6">
        <?php echo $this->Form->input('InvoiceItem.'.$i.'.description', array('class' => 'form-control')); ?>
    </div>
    <?php if (isset($action) && strcasecmp($action, 'edit')==0): ?>
        <div class="col-lg-2">
    <?php else: ?>
        <div class="col-lg-3">
    <?php endif; ?>
        <?php echo $this->Form->input('InvoiceItem.'.$i.'.amount', array('class' => 'form-control', 'min' => 0)); ?>
    </div>
</div>
        
<div class="modal fade" id="delete-line-item-<?php echo $i; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
	<div class="modal-content">
	    <div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h4 class="modal-title">Line Item Delete</h4>
	    </div>
	    <div class="modal-body">
		<p>
                    Are you sure you want to delete
                    <?php echo $this->request->data['InvoiceItem'][$i]['description']; ?>
                    ?
                </p>
	    </div>
	    <div class="modal-footer">
		<?php echo $this->Html->link('Close', '#', array('data-dismiss' => 'modal',
								 'class' => 'btn btn-default btn-lg')); ?>
		<?php echo $this->Form->postLink('Delete', array('action' => 'delete', $this->request->data['InvoiceItem'][$i]['id'], 'admin' => true),
							array('class' => 'btn btn-danger btn-lg')); ?>                                    
	    </div>
	</div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->