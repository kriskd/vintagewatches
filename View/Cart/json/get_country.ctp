<?php $billingForm = $this->Form->addressForm('billing', $data['country']); ?>
<?php $shippingForm = $this->Form->addressForm('shipping', $data['country']); ?>
<?php $shipping = $this->Number->currency($data['shipping'], 'USD'); ?>
<?php $totalFormatted = $this->Number->currency($data['total']); ?>
<?php echo json_encode(compact('shipping', 'totalFormatted', 'billingForm', 'shippingForm')); ?>
