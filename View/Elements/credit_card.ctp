<div class="checkout-form">
    <div class="payment-errors alert alert-error"></div>
    <?php echo $this->Form->input('Card.number', array('name' => false,
                                            'size' => 20,
                                            'data-stripe' => 'number',
                                            'autocomplete' => 'off',
                                            'placeHolder' => 'Card Number',
                                            'class' => 'card-number form-control',
                                            'label' => array('text' => 'Card Number<br /><small>(no spaces or hypens)</small>',
                                                             'class' => 'control-label'),
                                            'div' => array('class' => 'card-number-div input required'),
                                            'required' => 'required',
                                            'between' => '<div class="col-lg-6">',
                                               'after' => '</div>'
                                        )
                                    ); ?>
    <?php echo $this->Form->input('Card.cvc', array('name' => false,
                                            'size' => 4,
                                            'data-stripe' => 'cvc',
                                            'autocomplete' => 'off',
                                            'placeHolder' => 'CVC',
                                            'class' => 'card-cvc form-control',
                                            'label' => array('text' => '<a class="launch-tooltip"
                                                                        data-toggle="tooltip" data-placement="top"
                                                                        title="The CVC is the three-digit number
                                                                        that appears on the reverse side of your
                                                                        credit/debit card.">CVC
                                                                        <i class="glyphicon glyphicon-question-sign"></i>
                                                                        </a>',
                                                             'class' => 'control-label'),
                                            'div' => array('class' => 'cvc-div input required'),
                                            'required' => 'required',
                                            'between' => '<div class="col-lg-6">',
                                            'after' => '</div>'
                                        )
                                    ); ?>
    <div class="input select required">
        <?php echo $this->Form->label('Card.month', 'Expiration (MM/YYYY)',
                                      array('for' => 'CardMonth',
                                            'class' => 'control-label')); ?>
        <?php echo $this->Form->input('Card.month', array('name' => false,
                                                'empty' => 'MM',
                                                'options' => $months,
                                                'data-stripe' => 'exp-month',
                                                'class' => 'card-expiry-month form-control',
                                                'label' => false,
                                                'multiple' => false,
                                                'between' => '<div class="col-lg-3">',
                                                'after' => '</div>')); ?>
        <?php echo $this->Form->input('Card.year', array('name' => false,
                                                'empty' => 'Year',
                                                'options' => $years,
                                                'data-stripe' => 'exp-year',
                                                'placeHolder' => 'Year',
                                                'class' => 'card-expiry-year form-control',
                                                'label' => false,
                                                'multiple' => false,
                                                'between' => '<div class="col-lg-3">',
                                                'after' => '</div>')); ?>
        <?php echo $this->Form->end(array('class' => 'btn btn-primary submit-payment', 'label' => 'Submit Payment')); ?>
    </div>
</div>