<div class="coupons form">
<?php echo $this->Form->create('Coupon'); ?>
<?php $this->Form->inputDefaults(array(
    'class' => 'form-control'
)); ?>
<?php if (isset($this->request->data['Coupon']['id'])): ?>
    <?php echo $this->Form->input('id', array(
        'type' => 'hidden',
    )); ?>
<?php endif; ?>
	<fieldset>
        <legend><?php echo $action; ?> Coupon <?php echo isset($this->request->data['Coupon']['code']) ?  ' - '.strtoupper($this->request->data['Coupon']['code']) : '';  ?></legend>
        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12"> 
                <?php echo $this->Form->input('code'); ?>
            </div>
            <?php $options = array(
                'options' => $select
            ); ?>
            <?php if (isset($this->request->data['Coupon']['id']) && $hasOrders): ?>
                <?php $options['disabled'] = 'disabled'; ?>
            <?php endif; ?>
            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12"> 
                <?php echo $this->Form->input('type', $options); ?>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12"> 
                <?php $options = array(
                    'label' => 'Coupon Amount or Percentage Off',
                ); ?>
                <?php if (isset($this->request->data['Coupon']['id']) && $hasOrders): ?>
                    <?php $options['disabled'] = 'disabled'; ?>
                <?php endif; ?>
                <?php echo $this->Form->input('amount', $options); ?>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12"> 
                <?php echo $this->Form->input('total', array(
                    'label' => 'Total Coupons Available'
                )); ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <?php echo $this->Form->input('assigned_to'); ?>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <?php echo $this->Form->input('minimum_order', array(
                    'label' => 'Minimum Order Amount',
                )); ?>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <?php echo $this->Form->input('expire_date', array(
                    'type' => 'text'
                )); ?>
            </div>
        </div>
        <?php echo $this->Form->input('archived', array(
            'class' => '',
            'label' => 'Archive',
        )); ?>
	</fieldset>
<?php echo $this->Form->end(array(
    'label' => __('Submit'),
    'class' => 'btn btn-primary'
)); ?>
</div>
