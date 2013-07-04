<?php $shipping = $this->Html->link($this->Number->currency($data['shipping'], 'USD'), '#',
                                    array('class' => 'launch-tooltip',
                                          'data-toggle' => 'tooltip',
                                          'data-placement' => 'top',
                                          'title' => 'Shipping Fee',
                                          'escape' => false)); ?>
<?php $totalFormatted = $this->Number->currency($data['total']); ?>
<?php echo json_encode(compact('shipping', 'totalFormatted')); ?>
