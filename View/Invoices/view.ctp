<div class="invoices view">
    <?php echo $this->Element('header'); ?>
    <div class="row">
        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
            <?php echo $this->Element('invoice_top'); ?>
            <?php echo $this->Element('invoice_detail'); ?>
            <?php echo $this->Form->create('Invoice', array('class' => 'payment-form', 'inputDefaults' => array('class' => 'form-control'))); ?>
            <?php foreach ($invoice['Address'] as $i => $address): ?>
                <div class="invoice-address">
                    <h3><?php echo ucfirst($address['type']); ?> Address</h3>
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <?php echo $this->Form->input('Address.'.$i.'.firstName'); ?>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <?php echo $this->Form->input('Address.'.$i.'.lastName'); ?>
                        </div>
                    </div>
                    <?php echo $this->Form->input('Invoice.email'); ?>
                    <?php echo $this->Form->input('Address.'.$i.'.address1', array('label' => 'Address 1')); ?>
                    <?php echo $this->Form->input('Address.'.$i.'.address2', array('label' => 'Address 2')); ?>
                    <?php echo $this->Form->input('Address.'.$i.'.city'); ?>
                    <?php if (strcasecmp($invoice['Address'][$i]['country'], 'US')==0): ?>
                        <?php echo $this->Form->input('Address.'.$i.'.state', array('options' => array_merge(array('' => 'Select One'), $statesUS))); ?>
                    <?php endif; ?>
                        <?php if (strcasecmp($invoice['Address'][$i]['country'], 'CA')==0): ?>
                        <?php echo $this->Form->input('Address.'.$i.'.state', array('options' => array_merge(array('' => 'Select One'), $statesCA))); ?>
                    <?php endif; ?>
                    <?php echo $this->Form->input('Address.'.$i.'.postalCode'); ?>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
            <h3>Additional Information</h3>
            <p><?php echo $invoice['Invoice']['invoiceNotes']; ?></p>
            <?php echo $this->Form->input('Invoice.notes',
                                      array('label' => '<h3>Special Order Instructions <small>' . $this->Html->link(
                                                        '<span class="glyphicon glyphicon-question-sign"></span>', '#',
                                                        array('class' => 'launch-tooltip',
                                                                 'data-toggle' => 'tooltip',
                                                                 'data-placement' => 'top',
                                                                 'title' => 'Please add any special instructions here or information
                                                                            you need to pass along to me.',
                                                                 'escape' => false)) . '</small></h3>',
                                            'class' => 'form-control')
                                            ); ?>
            <section class="credit-card credit-card-invoice">
                <?php echo $this->Element('checkout_credit_card', array('payment_type' => 'invoice')); ?>
            </section>
            <?php //Display this after payment submitted ?>
            <?php //echo $this->Element('recent_watches'); ?>
        </div>
    </div>
</div>