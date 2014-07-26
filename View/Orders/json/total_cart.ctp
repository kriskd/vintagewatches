<?php $shipping = $this->Html->link($this->Number->currency($data['shipping'], 'USD'), '#',
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
<?php $totalFormatted = $this->Number->currency($data['total']); ?>
<?php echo json_encode(compact('shipping', 'totalFormatted') + $arr); ?>
