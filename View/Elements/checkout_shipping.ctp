<section class="shipping hide">
    <h3>Shipping<small class="pull-right hidden-xs"><span class="glyphicon glyphicon-arrow-right"></span> Choose Billing/Shipping Option</small></h3>
        <div class="shipping-inner">
        <h4>Choose one of the following shipping options:</h4>
        <?php echo $this->Form->input('Shipping.option', array('type' => 'radio',
                                                     'options' => array('billing' => 'Shipping Address Will be the Same as my Billing Address' .
                                                                        $this->Html->link(' <i class="glyphicon glyphicon-question-sign"></i>', '#',
                                                                            array('class' => 'launch-tooltip',
                                                                             'data-toggle' => 'tooltip',
                                                                             'data-placement' => 'top',
                                                                             'title' => 'This Is the address that appears on your billing statement.',
                                                                             'escape' => false)
                                                                            ),
                                                                        'shipping' => 'Shipping Address Will be Different From my Billing Address'),
                                                     'legend' => false,
                                                     //'hiddenField' => false,
                                                     'div' => "radio inline",
                                                     'separator' => '</div><div class="radio inline">',
                                                        )); ?>
    </div>
</section>