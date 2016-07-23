<div class="row upgrade-shipping text-right">
    <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10 col-xxs-8">
        <?= $this->Form->input('Order.upgrade_shipping', [
            'type' => 'checkbox',
            'label' => 'Upgrade to Priority Shipping for $3.00,<br />otherwise, shipment is by media mail.',
        ]); ?>
    </div>
    <div class="upgrade-amount hidden text-right col-lg-2 col-md-2 col-sm-2 col-xs-2 col-xxs-4">
    </div>
</div>
