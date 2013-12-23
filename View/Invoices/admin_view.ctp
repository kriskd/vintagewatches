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
        <h4 class="green"><span class="glyphicon glyphicon-check"></span>Paid <?php echo date('M j, Y', strtotime($invoice['Payment']['created'])); ?></h4>
    <?php endif; ?>
    <div class="row">
        <div class="col-lg-6">
            <h4>Notes From Customer</h4>
            <?php echo $invoice['Invoice']['notes']; ?>
        </div>
        <div class="col-lg-6">
            <h4>Invoice Notes</h4>
            <?php echo $invoice['Invoice']['invoiceNotes']; ?>
        </div>
    </div>
    <?php foreach ($invoice['Address'] as $address): ?>
        <h3><?php echo ucfirst($address['type']); ?> Address</h3>
        <ul>
            <li><?php echo $address['name']; ?></li>
            <li><?php echo $invoice['Invoice']['email']; ?></li>
            <li><?php echo $address['company']; ?></li>
            <li><?php echo $address['address1']; ?></li>
            <li><?php echo $address['address2']; ?></li>
            <li><?php echo $address['cityStZip']; ?></li>
        </ul>
    <?php endforeach; ?>
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