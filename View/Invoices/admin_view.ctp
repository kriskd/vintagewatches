<div class="invoices view">
    <div class="row">
        <div class="col-lg-4 col-lg-push-8 col-md-5 col-md-push-7 col-sm-12col-xs-12 buttons">
            <?php if ($paid == false && $invoice['Invoice']['active']==1): ?>
                <?php echo $this->Html->link('Go To Invoice', DS . $invoice['Invoice']['slug'], array('class' => 'btn btn-success')); ?>
            <?php endif; ?>
            <?php echo $this->Html->link('Edit Invoice', array('action' => 'edit', $invoice['Invoice']['id'], 'admin' => true), array('class' => 'btn btn-primary')); ?>
            <?php echo $this->Html->link('Delete Invoice', '#', array('class' => 'btn btn-danger',
                                                                      'data-target' => '#delete-invoice',
                                                                      'data-toggle' => 'modal')); ?>
        </div>
        <div class="col-lg-8 col-lg-pull-4 col-md-7 col-md-pull-5 col-sm-12 col-xs-12">
            <?php $scheme = prod() == true ? 'https://' : 'http://'; ?>
            <?php echo $this->Form->input(false, array(
                                            'value' => $scheme . env('SERVER_NAME') . DS . $invoice['Invoice']['slug'],
                                            'readonly' => 'readonly',
                                            'class' => 'form-control invoice-url',
                                            'label' => 'URL')); ?>
        </div>
    </div>
    <?php echo $this->Element('invoice_top'); ?>
    <?php if ($paid == false): ?>
        <h4 class="red"><span class="glyphicon glyphicon-minus-sign"></span> Not Paid</h4>
    <?php else: ?>
        <div class="invoice-payment">
            
            <dl>
                <dt>Stripe ID</dt>
                <dd><?php echo $invoice['Payment']['stripe_id']; ?></dd>
                <dt>Last 4 CCD Digits</dt>
                <dd><?php echo $invoice['Payment']['stripe_last4']; ?></dd>
                <dt>Address Check</dt>
                <dd><?php echo $invoice['Payment']['stripe_address_check']; ?></dd>
                <dt>Zip Check</dt>
                <dd><?php echo $invoice['Payment']['stripe_zip_check']; ?></dd>
                <dt>CVC Check</dt>
                <dd><?php echo $invoice['Payment']['stripe_cvc_check']; ?></dd>
            </dl>
            <?php /*<div class="row head">
                <div class="col-lg-3 col-md-3">
                    
                </div>
                <div class="col-lg-2 col-md-2">
                    <p>Stripe ID</p>
                </div>
                <div class="col-lg-1 col-md-1">
                    <p>Last 4</p>
                </div>
                <div class="col-lg-2 col-md-2">
                    <p>Address Check</p>
                </div>
                <div class="col-lg-2 col-md-2">
                    <p>Zip Check</p>
                </div>
                <div class="col-lg-2 col-md-2">
                    <p>Stripe Amount</p>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3 col-md-3">
                    <h4 class="green"><span class="glyphicon glyphicon-check"></span>Paid <?php echo date('M j, Y', strtotime($invoice['Payment']['created'])); ?></h4>        
                </div>
                <div class="col-lg-2 col-md-2">
                    <p><?php echo $invoice['Payment']['stripe_id']; ?></p>
                </div>
                <div class="col-lg-1 col-md-1">
                    <p><?php echo $invoice['Payment']['stripe_last4']; ?></p>
                </div>
                <div class="col-lg-2 col-md-2">
                    <p><?php echo $invoice['Payment']['stripe_address_check']; ?></p>
                </div>
                <div class="col-lg-2 col-md-2">
                    <p><?php echo $invoice['Payment']['stripe_zip_check']; ?></p>
                </div>
                <div class="col-lg-2 col-md-2">
                    <p><?php echo $this->Number->stripe($invoice['Payment']['stripe_amount']); ?></p>
                </div>
            </div>*/ ?>
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