<div class="coupons view">
<h2><?php echo __('Coupon'); ?>
        <?php echo $this->Html->link('Edit', array(
                                                'action' => 'edit', $coupon['Coupon']['id'],
                                                'admin' => true
                                                ),
                                           array(
                                                 'class' => 'btn btn-success'
                                                 )
                                           ); ?>
        <?php if (empty($coupon['Order'])): ?>
            <?php echo $this->Form->button('Delete', array(
                'data-coupon' => $coupon['Coupon']['id'], 
                'data-code' => $coupon['Coupon']['code'], 
                'class' => 'btn btn-danger delete-coupon',
            )); ?> 
        <?php endif; ?>
</h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($coupon['Coupon']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Code'); ?></dt>
		<dd>
			<?php echo h($coupon['Coupon']['code']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Type'); ?></dt>
		<dd>
			<?php echo h($coupon['Coupon']['type']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Amount'); ?></dt>
		<dd>
			<?php echo h($coupon['Coupon']['amount']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Assigned To'); ?></dt>
		<dd>
			<?php echo h($coupon['Coupon']['assigned_to']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Minimum Order'); ?></dt>
		<dd>
			<?php echo h($coupon['Coupon']['minimum_order']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Expire Date'); ?></dt>
		<dd>
			<?php echo h($coupon['Coupon']['expire_date']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($coupon['Coupon']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified'); ?></dt>
		<dd>
			<?php echo h($coupon['Coupon']['modified']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="related">
	<h3><?php echo __('Related Orders'); ?></h3>
	<?php if (!empty($coupon['Order'])): ?>
        <div class="table">
            <div class="table-row">
                <span class="table-head"><?php echo __('Id'); ?></span>
                <span class="table-head"><?php echo __('Email'); ?></span>
                <span class="table-head"><?php echo __('Phone'); ?></span>
                <span class="table-head"><?php echo __('ShippingAmount'); ?></span>
                <span class="table-head"><?php echo __('ShipDate'); ?></span>
                <span class="table-head"><?php echo __('Notes'); ?></span>
                <span class="table-head"><?php echo __('OrderNotes'); ?></span>
                <span class="table-head"><?php echo __('Created'); ?></span>
                <span class="table-head"><?php echo __('Modified'); ?></span>
            </div>
            <?php foreach ($coupon['Order'] as $order): ?>
                <?php $row = ''; ?>	
                <?php $row .= $this->Html->tag('span',  $order['id'], array('class' => 'table-cell')); ?>
                <?php $row .= $this->Html->tag('span',  $order['email'], array('class' => 'table-cell')); ?>
                <?php $row .= $this->Html->tag('span',  $order['phone'], array('class' => 'table-cell')); ?>
                <?php $row .= $this->Html->tag('span',  $order['shippingAmount'], array('class' => 'table-cell')); ?>
                <?php $row .= $this->Html->tag('span',  $order['shipDate'], array('class' => 'table-cell')); ?>
                <?php $row .= $this->Html->tag('span',  $order['notes'], array('class' => 'table-cell')); ?>
                <?php $row .= $this->Html->tag('span',  $order['orderNotes'], array('class' => 'table-cell')); ?>
                <?php $row .= $this->Html->tag('span',  $order['created'], array('class' => 'table-cell')); ?>
                <?php $row .= $this->Html->tag('span',  $order['modified'], array('class' => 'table-cell'), array('class' => 'table-cell')); ?>
                <?php echo $this->Html->link($row, array('controller' => 'orders', 'action' => 'admin_view', $order['id'], 'admin' => true), array('class' => 'table-row', 'escape' => false)); ?>	
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
