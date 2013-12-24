<div class="invoices pay">
    <?php echo $this->Element('header'); ?>
    <?php echo $this->Form->create('Invoice', array('class' => 'payment-form', 'inputDefaults' => array('class' => 'form-control'))); ?>
    <div class="row">
        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
            <?php echo $this->Element('invoice_top'); ?>
            <?php echo $this->Element('invoice_detail'); ?>
            <?php foreach ($invoice['Address'] as $i => $address): ?>
                <div class="invoice-address">
                    <h3><small class="pull-right hidden-xxs"><span class="glyphicon glyphicon-arrow-right"></span> Complete Address</small><?php echo ucfirst($address['type']); ?> Address</h3>
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <?php echo $this->Form->input('Address.'.$i.'.firstName'); ?>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <?php echo $this->Form->input('Address.'.$i.'.lastName', array('data-stripe' => 'name')); ?>
                        </div>
                    </div>
                    <?php echo $this->Form->input('Invoice.email'); ?>
                    <?php echo $this->Form->input('Address.'.$i.'.address1', array('label' => 'Address 1', 'data-stripe' => 'address_line1')); ?>
                    <?php echo $this->Form->input('Address.'.$i.'.address2', array('label' => 'Address 2', 'data-stripe' =>'address_line2')); ?>
                    <?php echo $this->Form->input('Address.'.$i.'.city', array('data-stripe' => 'address_city')); ?>
                    <?php if (strcasecmp($invoice['Address'][$i]['country'], 'US')==0): ?>
                        <?php echo $this->Form->input('Address.'.$i.'.state', array('data-stripe' => 'address_state', 'options' => array_merge(array('' => 'Select One'), $statesUS))); ?>
                    <?php endif; ?>
                        <?php if (strcasecmp($invoice['Address'][$i]['country'], 'CA')==0): ?>
                        <?php echo $this->Form->input('Address.'.$i.'.state', array('data-stripe' => 'address_state', 'options' => array_merge(array('' => 'Select One'), $statesCA))); ?>
                    <?php endif; ?>
                    <?php echo $this->Form->input('Address.'.$i.'.postalCode', array('data-stripe' => 'address_zip')); ?>
                    <?php echo $this->Form->input('Address.'.$i.'.country', array('data-stripe' => 'address_country', 'type' => 'hidden')); ?>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
            <?php if (!empty($invoice['Invoice']['invoiceNotes'])): ?>
                <h3>Additional Information</h3>
                <p><?php echo $invoice['Invoice']['invoiceNotes']; ?></p>
            <?php endif; ?>
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