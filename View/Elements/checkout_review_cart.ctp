    <section class="review-cart">
        <?php if(isset($errors) && $errors == true): ?>
            <div class="error-message">
                Sorry, your order could not be completed because one or more required
                fields was not filled out. Please scroll down the form, and missing field
                or fields will be highlighted in red. Fill them out, and then resubmit
                the form.
            </div>
        <?php endif; ?>
        <h3>Review Cart<small class="pull-right hidden-xxs"><span class="glyphicon glyphicon-arrow-right"></span> Choose Ship to Country</small></h3>
        <div class="cart-details">
            <div class="row head hidden-xxs">
                <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
                    
                </div>
                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                    Stock ID
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                    Name
                </div>
                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                    Price
                </div>
            </div>
            <?php foreach($watches as $watch): ?>
                <div class="row hidden-xs visible-xxs">
                    <div class="col-xxs-8">
                        <strong>
                            <?php echo $this->Html->link($watch['Brand']['name'] . ' - Stock Number ' . $watch['Watch']['stockId'], array(
                                                           'controller' => 'watches',
                                                           'action' => 'view', $watch['Watch']['id']
                                                           )); ?>
                        </strong>
                    </div>
                    <div class="hidden-xs visible-xxs col-xxs-4">
                        
                    </div>
                </div>
                <div class="row watch">
                    <div class="text-center col-lg-1 col-md-1 col-sm-1 col-xs-1 col-xxs-1">
                        <?php echo $this->Html->link('<i class="glyphicon glyphicon-trash"></i>',
                                                                             array('action' => 'remove', $watch['Watch']['id']),
                                                                             array('escape' => false, 'class' => 'launch-tooltip',
                                                                                   'data-toggle' => 'tooltip',
                                                                                   'data-placement' => 'top',
                                                                                   'title' => 'Remove from Cart')
                                                                             ); ?>
                    </div>
                    <div class="image col-lg-4 col-md-4 col-sm-4 col-xs-4 col-xxs-7">
                        <?php echo $this->Html->thumbPrimary($watch, array(
                                                                            'class' => 'img-responsive',
                                                                            'url' => array(
                                                                                'controller' => 'watches',
                                                                                'action' => 'view', $watch['Watch']['id']
                                                                            )
                                                                        )
                                                                  ); ?>
                    </div>
                    <div class="text-center col-lg-2 col-md-2 col-sm-2 col-xs-2 hidden-xxs">
                        <?php echo h($watch['Watch']['stockId']); ?>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 hidden-xxs">
                        <?php echo $this->Html->link(htmlspecialchars($watch['Watch']['name'], ENT_NOQUOTES, 'UTF-8'),
                                                     array(
                                                           'controller' => 'watches',
                                                           'action' => 'view', $watch['Watch']['id']
                                                           )
                                                     ); ?>
                    </div>
                    <div class="text-right col-lg-2 col-md-2 col-sm-2 col-xs-2 col-xxs-4">
                        <?php echo h($this->Number->currency($watch['Watch']['price'], 'USD')); ?>
                    </div>
                </div>
            <?php endforeach; ?>
            <div class="row choose-ship">
                <div class="text-right col-lg-10 col-md-10 col-sm-10 col-xs-10 col-xxs-8">
                    <?php $tooltip = $this->Html->link('<span class="glyphicon glyphicon-question-sign"></span>', '#',
                                                       array(
                                                            'class' => 'launch-tooltip',
                                                            'data-toggle' => 'tooltip',
                                                            'data-placement' => 'top',
                                                            'escape' => false,
                                                            'title' => trim('Once you choose your country, the appropriate shipping
                                                                        charge will be added, and the shipping bar below will expand
                                                                        where you can indicate shipping options. Then, the address
                                                                        bar will expand so you can enter the appropriate information.')
                                                        )
                                                    ); ?>
                    <h6>
                        Choose the Country Where You Want Your Order Shipped
                        <?php echo $tooltip; ?>
                    </h6>
                    <div class="select-country">
                        <?php echo $this->Form->input('Address.select-country', array(
                            'type' => 'radio',
                            'options' => array(
                                'us' => 'U.S.',
                                'ca' => 'Canada', 
                                'other' => 'Other'
                            ),
                            'legend' => false,
                            //'hiddenField' => false,
                            'div' => "radio-inline",
                            'separator' => '</div><div class="radio-inline">',
                       )); ?>
                    </div>
                </div>
                <div class="shipping-amount text-right col-lg-2 col-md-2 col-sm-2 col-xs-2 col-xxs-4">
                    
                </div>
            </div>
            <div class="row">
                <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10 col-xxs-8">
                    <h5 class="text-center <?php echo $couponsAvailable == false ? 'hidden' : ''; ?>">To redeem a coupon enter your email and coupon code. Your new balance will be reflected.</h5>
                </div>
            </div>
            <div class="row coupon-fields <?php echo $couponsAvailable == false ? 'hidden' : ''; ?>">
                <div class="col-lg-6 col-md-6 col-sm-10 col-xs-10 col-xxs-8">
                    <?php echo $this->Form->input('Coupon.email', array(
                        'class' => 'form-control input-sm'
                    )); ?>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-10 col-xs-10 col-xxs-8">
                    <?php echo $this->Form->input('Coupon.code', array(
                        'class' => 'form-control input-sm',
                    )); ?> 
                </div>
                <div class="coupon-amount text-right col-lg-2 col-md-2 col-sm-2 col-xs-2 col-xxs-4">
                </div>
            </div>
            <div class="row">
                <div class="coupon-alert col-lg-8 col-md-8 col-sm-8 col-xs-8 col-xxs-4">

                </div>
                <div class="text-right col-lg-2 col-md-2 col-sm-2 col-xs-2 col-xxs-4">
                    Total
                </div>
                <div class="total-formatted-amount text-right col-lg-2 col-md-2 col-sm-2 col-xs-2 col-xxs-4">
                    
                </div>
            </div>
        </div>
    </section>
