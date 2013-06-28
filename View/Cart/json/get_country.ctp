<?php $billingForm = $this->Form->addressForm('Address.billing.', $data['country'], true, true); ?>
<?php $shippingForm = $this->Form->addressForm('Address.shipping.', $data['country']); ?>
<?php $shipping = $this->Number->currency($data['shipping'], 'USD'); ?>
<?php $totalFormatted = $this->Number->currency($data['total']); ?>
<?php echo json_encode(compact('shipping', 'totalFormatted', 'billingForm', 'shippingForm')); ?>
