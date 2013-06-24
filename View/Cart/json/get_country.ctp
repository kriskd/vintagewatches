<?php $form = $this->Form->addressForm('billing', $data['country'], $data['states'], $data['provinces']); ?>
<?php $shipping = $this->Number->currency($data['shipping'], 'USD'); ?>
<?php $totalFormatted = $this->Number->currency($data['total']); ?>
<?php echo json_encode(compact('shipping', 'totalFormatted', 'form')); ?>
