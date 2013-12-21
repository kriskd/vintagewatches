<div class="invoices view">
    <div class="row">
        <div class="col-lg-10">
            <?php echo $this->Form->input(false, array(
                                            'value' => 'http://' . env('SERVER_NAME') . DS . $invoice['Invoice']['slug'],
                                            'readonly' => 'readonly',
                                            'class' => 'form-control invoice-url',
                                            'label' => 'URL')); ?>
        </div>
        <div class="col-lg-2">
            <?php echo $this->Html->link('Edit Invoice', array('action' => 'edit', $invoice['Invoice']['id'], 'admin' => true), array('class' => 'btn btn-primary edit-invoice')); ?>
        </div>
    </div>
    <?php echo $this->Element('invoice_top'); ?>
    <?php foreach ($invoice['Address'] as $address): ?>
        <h3><?php echo ucfirst($address['type']); ?> Address</h3>
        <ul>
            <li><?php echo $address['name']; ?></li>
            <li><?php echo $address['company']; ?></li>
            <li><?php echo $address['address1']; ?></li>
            <li><?php echo $address['address2']; ?></li>
            <li><?php echo $address['cityStZip']; ?></li>
        </ul>
    <?php endforeach; ?>
    <?php echo $this->Element('invoice_detail'); ?>
</div>