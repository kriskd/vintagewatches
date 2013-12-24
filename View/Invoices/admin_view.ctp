<div class="invoices view">
    <div class="row">
        <div class="col-lg-9">
            <?php echo $this->Form->input(false, array(
                                            'value' => 'http://' . env('SERVER_NAME') . DS . $invoice['Invoice']['slug'],
                                            'readonly' => 'readonly',
                                            'class' => 'form-control invoice-url',
                                            'label' => 'URL')); ?>
        </div>
        <div class="col-lg-3 buttons">
            <?php echo $this->Html->link('Edit Invoice', array('action' => 'edit', $invoice['Invoice']['id'], 'admin' => true), array('class' => 'btn btn-primary')); ?>
            <?php echo $this->Html->link('Delete Invoice', '#', array('class' => 'btn btn-danger',
                                                                      'data-target' => '#delete-invoice',
                                                                      'data-toggle' => 'modal')); ?>
        </div>
    </div>
    <?php echo $this->Element('invoice_top'); ?>
    <?php if (empty($invoice['Payment']['stripe_paid'])): ?>
        <h4 class="red"><span class="glyphicon glyphicon-minus-sign"></span> Not Paid</h4>
    <?php else: ?>
        <div class="invoice-payment">
            <div class="row head">
                <div class="col-lg-3">
                    
                </div>
                <div class="col-lg-2">
                    <p>Stripe ID</p>
                </div>
                <div class="col-lg-1">
                    <p>Last 4</p>
                </div>
                <div class="col-lg-2">
                    <p>Address Check</p>
                </div>
                <div class="col-lg-2">
                    <p>Zip Check</p>
                </div>
                <div class="col-lg-2">
                    <p>Stripe Amount</p>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3">
                    <h4 class="green"><span class="glyphicon glyphicon-check"></span>Paid <?php echo date('M j, Y', strtotime($invoice['Payment']['created'])); ?></h4>        
                </div>
                <div class="col-lg-2">
                    <p><?php echo $invoice['Payment']['stripe_id']; ?></p>
                </div>
                <div class="col-lg-1">
                    <p><?php echo $invoice['Payment']['stripe_last4']; ?></p>
                </div>
                <div class="col-lg-2">
                    <p><?php echo $invoice['Payment']['stripe_address_check']; ?></p>
                </div>
                <div class="col-lg-2">
                    <p><?php echo $invoice['Payment']['stripe_zip_check']; ?></p>
                </div>
                <div class="col-lg-2">
                    <p><?php echo $this->Number->stripe($invoice['Payment']['stripe_amount']); ?></p>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <?php echo $this->Element('invoice_customer'); ?>
    <?php echo $this->Element('invoice_detail'); ?>
</div>

<div class="modal fade" id="delete-invoice" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
	<div class="modal-content">
	    <div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h4 class="modal-title">Line Item Delete</h4>
	    </div>
	    <div class="modal-body">
		<p>
                    Are you sure you want to delete invoice
                    <?php echo $invoice['Invoice']['id']; ?>?
                </p>
	    </div>
	    <div class="modal-footer">
		<?php echo $this->Html->link('Close', '#', array('data-dismiss' => 'modal',
								 'class' => 'btn btn-default btn-lg')); ?>
		<?php echo $this->Form->postLink('Delete', array('action' => 'delete', $invoice['Invoice']['id'], 'admin' => true),
							array('class' => 'btn btn-danger btn-lg')); ?>                                    
	    </div>
	</div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->