<div class="invoices view">
    <h2>Invoice No. <?php echo $invoice['Invoice']['id']; ?></h2>
    <ul>
        <li><?php echo $invoice['Invoice']['email']; ?></li>
        <li><?php echo date('F j, Y', strtotime($invoice['Invoice']['created'])); ?></li>
    </ul>
    <?php foreach ($invoice['Address'] as $address): ?>
        <h3><?php echo ucfirst($address['type']); ?></h3>
        <ul>
            <li><?php echo $address['name']; ?></li>
            <li><?php echo $address['company']; ?></li>
            <li><?php echo $address['address1']; ?></li>
            <li><?php echo $address['address2']; ?></li>
            <li><?php echo $address['cityStZip']; ?></li>
        </ul>
    <?php endforeach; ?>
    <?php foreach ($invoice['InvoiceItem'] as $item): ?>
        <div class="row">
            <div class="col-lg-3">
                <?php echo $item['itemid']; ?>
            </div>
            <div class="col-lg-6">
                <?php echo $item['description']; ?>
            </div>
            <div class="col-lg-3">
                <?php echo $this->Number->currency($item['amount'], 'USD'); ?>
            </div>
        </div>
    <?php endforeach; ?>
    <div class="row">
        <div class="col-lg-9">
        </div>
        <div class="col-lg-3">
            <?php echo $this->Number->currency($invoice['Invoice']['shippingAmount']); ?>
        </div>
    </div>
</div>