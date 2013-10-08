<div class="orders form">
    <h2><?php echo __('Admin Edit Order'); ?></h2>
    <?php echo $this->Form->create('Order', array(
                                                  'inputDefaults' => array(
                                                            'class' => 'form-control'
                                                  )
                                            )
                                   ); ?>
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4">
                <fieldset>
                    <legend>Order</legend>
                    <?php
                        echo $this->Form->input('email');
                        echo $this->Form->input('phone');
                        echo $this->Form->input('shippingAmount');
                        echo $this->Form->input('notes', array('label' => 'Notes from ' . $order['Address'][0]['name']));
                        echo $this->Form->input('orderNotes', array('label' => 'Order Notes <small>(Notes by Bruce)</small>'));
                        echo $this->Form->input('shipDate', array('type' => 'text'));
                    ?>
                </fieldset>
                </div>
                <?php foreach ($order['Address'] as $i => $address): ?>
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4">
                        <fieldset>
                            <legend><?php echo ucfirst($address['type']); ?> Name and Address</legend>
                            <?php echo $this->Form->input('Address.'.$i.'.id', array('type' => 'hidden', 'value' => $address['id'])); ?>
                            <?php foreach ($addressFields as $field): ?>
                                    <?php $options = array('value' => $address[$field]); ?>
                                    <?php if (strcasecmp($field, 'state')==0 && in_array($address['country'], array('US', 'CA'))): ?>
                                            <?php if (strcasecmp($address['country'], 'US')==0): ?>
                                                    <?php $options['options'] = $statesUS; ?>
                                            <?php elseif (strcasecmp($address['country'], 'CA')==0): ?>
                                                    <?php $options['options'] = $statesCA; ?>
                                            <?php endif; ?>
                                    <?php elseif (strcasecmp($field, 'state')==0): ?>
                                            <?php continue; ?>
                                    <?php endif; ?>
                                    <?php echo $this->Form->input('Address.'.$i.'.'.$field, $options); ?>
                            <?php endforeach; ?>
                        </fieldset>
                    </div>
                <?php endforeach; ?>  
        </div>
    <?php echo $this->Form->end(array('label' => 'Save', 'class' => 'btn btn-primary')); ?>
</div>