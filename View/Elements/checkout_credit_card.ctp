<section class="credit-card hide">
    <h3><small class="pull-right hidden-xxs"><span class="glyphicon glyphicon-arrow-right"></span> Enter Credit Card</small>Credit Card</h3>
        <div class="checkout-form">
            <div class="payment-errors alert alert-error"></div>
            <div class="row">
                <?php echo $this->Form->input('Card.number', array('name' => false,
                                                    'size' => 20,
                                                    'data-stripe' => 'number',
                                                    'autocomplete' => 'off',
                                                    'placeHolder' => 'Card Number',
                                                    'class' => 'card-number form-control',
                                                    'label' => array('text' => 'Card Number<br /><small>(no spaces or hypens)</small>',
                                                                     'class' => 'control-label col-xs-11 col-sm-4 col-md-4 col-lg-4'),
                                                    'div' => array('class' => 'card-number-div input required'),
                                                    'required' => 'required',
                                                    'between' => '<div class="col-xs-11 col-sm-7 col-md-7 col-lg-7">',
                                                       'after' => '</div>'
                                                )
                                            ); ?>
            </div>
            <div class="row">
                <?php echo $this->Form->input('Card.cvc', array('name' => false,
                                                    'size' => 4,
                                                    'data-stripe' => 'cvc',
                                                    'autocomplete' => 'off',
                                                    'placeHolder' => 'CVC',
                                                    'class' => 'card-cvc form-control',
                                                    'label' => array('text' => 'CVC <a class="launch-tooltip"
                                                                                data-toggle="tooltip" data-placement="top"
                                                                                title="The CVC is the three-digit number
                                                                                that appears on the reverse side of your
                                                                                credit/debit card.">
                                                                                <i class="glyphicon glyphicon-question-sign"></i>
                                                                                </a>',
                                                                     'class' => 'control-label col-xs-11 col-sm-4 col-md-4 col-lg-4'),
                                                    'div' => array('class' => 'cvc-div input required'),
                                                    'required' => 'required',
                                                    'between' => '<div class="col-xs-11 col-sm-7 col-md-7 col-lg-7">',
                                                    'after' => '</div>'
                                                )
                                            ); ?>
            </div>
            <div class="input select required">
                <div class="row">
                    <div class="col-xxs-12 col-xs-12 col-sm-4 col-md-4 col-lg-4">
                        <?php echo $this->Form->label('Card.month', 'Expiration (MM/YYYY)',
                                                  array(
                                                    'for' => 'CardMonth',
                                                    'class' => 'control-label'
                                                )
                                            ); ?>
                    </div>
                    <?php echo $this->Form->input('Card.month', array(
                                                        'name' => false,
                                                        'empty' => 'MM',
                                                        'options' => $months,
                                                        'data-stripe' => 'exp-month',
                                                        'class' => 'form-control',
                                                        'label' => false,
                                                        'multiple' => false,
                                                        'div' => 'col-xxs-6 col-xs-6 col-sm-3 col-md-3 col-lg-3 card-expiry-month',
                                                        //'between' => '<div class="col-xxs-6 col-xs-6 col-sm-3 col-md-3 col-lg-3">',
                                                        //'after' => '</div>'
                                                        ));
                                                    ?>
                    <?php echo $this->Form->input('Card.year', array(
                                                        'name' => false,
                                                        'empty' => 'Year',
                                                        'options' => $years,
                                                        'data-stripe' => 'exp-year',
                                                        'placeHolder' => 'Year',
                                                        'class' => 'card-expiry-year form-control',
                                                        'label' => false,
                                                        'multiple' => false,
                                                        'div' => 'col-xxs-6 col-xs-6 col-sm-3 col-md-3 col-lg-3',
                                                        //'between' => '<div class="col-xxs-6 col-xs-6 col-sm-3 col-md-3 col-lg-3">',
                                                        //'after' => '</div>'
                                                        ));
                                                    ?>
                </div>
                <div class="row">
                    <?php echo $this->Form->end(array('class' => 'btn btn-gold submit-payment',
                                                  'label' => 'Submit Payment',
                                                  'div' => 'submit col-xs-11')); ?>
                </div>
            </div>
        </div>
</section>