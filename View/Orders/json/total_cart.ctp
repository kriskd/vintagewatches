<?php if (empty($shipping) || empty($total)): ?>
    <?php echo json_encode(array('alert' => '<div class="alert alert-danger"><small>There is a problem with your cart. Please return to the <a href="/watches">store</a> and add the watch to your cart again.</small></div>')); ?>
    <?php return; ?>
<?php endif; ?>
<?php $shipping = $this->Html->link($this->Number->currency($shipping, 'USD'), '#',
                                    array('class' => 'launch-tooltip',
                                          'data-toggle' => 'tooltip',
                                          'data-placement' => 'top',
                                          'title' => 'Shipping Fee',
                                          'escape' => false)); ?>
<?php $arr = array(); ?>
<?php if (isset($couponAmount)): ?> 
    <?php $couponFormatted = $this->Number->currency($couponAmount); ?>
    <?php $arr = compact('couponAmount', 'couponFormatted'); ?>
<?php endif; ?>
<?php $message = ''; ?>
<?php if (isset($coupon['alert']) && isset($coupon['message'])): ?>
    <?php $message = '
    <div class="alert alert-'.$coupon['alert'].'">
    <small>
        <span class="glyphicon glyphicon-info-sign"></span> '.$coupon['message'].' ' .
        $this->Html->link('Contact me', array(
            'controller' => 'contacts',
            'action' => 'index',
        ),
        array(
            'class' => 'alert-link',
        )).' for assistance.
    </small>'; ?>
<?php endif; ?>
<?php $totalFormatted = $this->Number->currency($total); ?>
<?php echo json_encode(compact('shipping', 'totalFormatted') + $arr + array('alert' => $message)); ?>
