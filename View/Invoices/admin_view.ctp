<div class="invoices view">
    <h2>Invoice No. <?php echo $invoice['Invoice']['id']; ?>
        <?php echo $this->Html->link('Edit', array('action' => 'edit', $invoice['Invoice']['id'], 'admin' => true), array('class' => 'btn btn-primary')); ?>
    </h2>
    <ul>
        <li><?php echo $invoice['Invoice']['email']; ?></li>
        <li><?php echo date('F j, Y', strtotime($invoice['Invoice']['created'])); ?></li>
    </ul>
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
    <div class="row head">
        <div class="col-lg-2 col-md-3 col-sm-3 col-xs-3 text-center">
            Item Id/Code
        </div>
        <div class="col-lg-4 col-md-6 col-sm-6 col-xs-6 text-center">
            Description
        </div>
        <div class="col-lg-2 col-md-3 col-sm-3 col-xs-3 text-center">
            Amount
        </div>
    </div>
    <?php foreach ($invoice['InvoiceItem'] as $item): ?>
        <div class="row items">
            <div class="col-lg-2 col-md-3 col-sm-3 col-xs-3">
                <?php echo $item['itemid']; ?>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-6 col-xs-6">
                <?php echo $item['description']; ?>
            </div>
            <div class="col-lg-2 col-md-3 col-sm-3 col-xs-3 text-right">
                <?php echo $this->Number->currency($item['amount'], 'USD'); ?>
            </div>
        </div>
    <?php endforeach; ?>
    <div class="row shipping">
        <div class="col-lg-6 col-md-9 col-sm-9 col-xs-9 text-right">
            Shipping
        </div>
        <div class="col-lg-2 col-md-3 col-sm-3 col-xs-3 text-right">
            <?php echo $this->Number->currency($invoice['Invoice']['shippingAmount'], 'USD'); ?>
        </div>
    </div>
    <div class="row total">
        <div class="col-lg-6 col-md-9 col-sm-9 col-xs-9 text-right">
            Total
        </div>
        <div class="col-lg-2 col-md-3 col-sm-3 col-xs-3 text-right">
            <?php echo $this->Number->currency($this->Invoice->total($invoice), 'USD'); ?>
        </div>
    </div>
</div>