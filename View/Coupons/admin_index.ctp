<div class="coupons admin-index">
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <h2><?php echo __('Coupons'); ?></h2>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 admin-btns text-right">
            <?php echo $this->Html->link('Add Coupon', array('action' => 'add'), array('class' => 'btn btn-primary add-coupon')); ?>
        </div>
    </div>
    <?php echo $this->Form->create('Order', array('type' => 'get', 'url' => array('controller' => 'orders', 'action' => 'view', 'admin' => true))); ?>
    <div class="table">
        <div class="table-row">
            <span class="table-head">Archived</span>
			<span class="table-head"><?php echo $this->Paginator->sort('code'); ?></span>
            <span class="table-head"><?php echo $this->Paginator->sort('total'); ?></span>
            <span class="table-head"><?php echo $this->Paginator->sort('available'); ?></span>
			<span class="table-head"><?php echo $this->Paginator->sort('type'); ?></span>
			<span class="table-head"><?php echo $this->Paginator->sort('amount'); ?></span>
			<span class="table-head"><?php echo $this->Paginator->sort('assigned_to'); ?></span>
			<span class="table-head"><?php echo $this->Paginator->sort('minimum_order'); ?></span>
			<span class="table-head"><?php echo $this->Paginator->sort('expire_date'); ?></span>
        </div>
        <?php foreach ($coupons as $coupon): ?>
            <?php $row = ''; ?>	
            <?php $row .= $this->Form->inputSpan('Coupon.archived', array(
                'data-couponid' => $coupon['Coupon']['id'], 
                'data-couponcode' => $coupon['Coupon']['code'],
                'div' => array(
                    'class' => 'table-cell text-center'
                ), 
                'label' => false, 
                'checked' => $coupon['Coupon']['archived'],
                'id' => 'CouponArchived'.$coupon['Coupon']['id'],
                'class' => 'archive-coupon',
            )); ?>
            <?php $row .= $this->Html->tag('span',  h($coupon['Coupon']['code']), array('class' => 'table-cell')); ?>
            <?php $row .= $this->Html->tag('span', h($coupon['Coupon']['total']), array('class' => 'text-center table-cell')); ?>
            <?php $row .= $this->Html->tag('span', $coupon['Coupon']['available'], array('class' => 'text-center table-cell')); ?>
            <?php $row .= $this->Html->tag('span',  h($coupon['Coupon']['type']), array('class' => 'table-cell')); ?>
            <?php $row .= $this->Html->tag('span',  h($coupon['Coupon']['amount']), array('class' => 'table-cell text-right')); ?>
            <?php $row .= $this->Html->tag('span',  h($coupon['Coupon']['assigned_to']), array('class' => 'table-cell')); ?>
            <?php $row .= $this->Html->tag('span',  h($coupon['Coupon']['minimum_order']), array('class' => 'table-cell text-right')); ?>
            <?php $row .= $this->Html->tag('span',  h($coupon['Coupon']['expire_date']), array('class' => 'text-center table-cell')); ?>
            <?php echo $this->Html->link($row, array('action' => 'admin_view', $coupon['Coupon']['id'], 'admin' => true), array('class' => 'table-row', 'escape' => false)); ?>
        <?php endforeach; ?>
    </div>
    <?php echo $this->Form->end(); ?>
    <?php echo $this->Element('paginator'); ?>
</div>
