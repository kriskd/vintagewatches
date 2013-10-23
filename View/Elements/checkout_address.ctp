<section class="address hide">
    <h3>Address<small class="pull-right"><span class="glyphicon glyphicon-arrow-right"></span> Enter Address(es)</small></h3>
    <div class="address-forms"></div>
    <div class="shipping-instructions">
        <h5>Email and Phone Number</h5>
        <div class="checkout-form">
            <?php echo $this->Form->input('Order.email', array('label' => array('class' => 'control-label col-xs-2'),
                                                               'class' => 'form-control',
                                                               'between' => '<div class="clearfix"><div class="col-xs-11 col-sm-6 col-md-6 col-lg-6">',
                                                               'after' => '</div></div>',
                                                               'div' => 'form-group row')); ?>
            <?php echo $this->Form->input('Order.phone', array('label' => array('class' => 'control-label col-xs-2'),
                                                               'class' => 'form-control',
                                                               'between' => '<div class="clearfix"><div class="col-xs-11 col-sm-6 col-md-6 col-lg-6">',
                                                               'after' => '</div></div>',
                                                               'div' => 'form-group row')); ?>
        </div>
        <?php echo $this->Form->input('Order.notes',
                                      array('label' => '<h5>Special Order Instructions ' . $this->Html->link(
                                                        '<i class="glyphicon glyphicon-question-sign"></i>', '#',
                                                        array('class' => 'launch-tooltip',
                                                                 'data-toggle' => 'tooltip',
                                                                 'data-placement' => 'top',
                                                                 'title' => 'Please add any special instructions here or information
                                                                            you need to pass along to me.',
                                                                 'escape' => false)) . '</h5>',
                                            'class' => 'form-control')
                                            ); ?>
    </div>
</section>