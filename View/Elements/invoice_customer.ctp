<div class="row">
    <div class="col-lg-6">
        <h3>Notes From <?php echo empty($invoice['Address'][0]['firstName']) ? 'Customer' : $invoice['Address'][0]['firstName']; ?></h3>
        <?php echo $invoice['Invoice']['notes']; ?>
    </div>
    <div class="col-lg-6">
        <h3>Invoice Notes</h3>
        <?php echo $invoice['Invoice']['invoiceNotes']; ?>
    </div>
</div>
<div class="row">
    <?php foreach ($invoice['Address'] as $address): ?>
        <div class="col-lg-4">
        <h3><?php echo ucfirst($address['type']); ?> Address</h3>
            <ul>
                <li><?php echo $address['name']; ?></li>
                <li><?php echo $this->Text->autoLinkEmails($invoice['Invoice']['email']); ?></li>
                <li><?php echo $address['company']; ?></li>
                <li><?php echo $address['address1']; ?></li>
                <li><?php echo $address['address2']; ?></li>
                <li><?php echo $address['cityStZip']; ?></li>
                <li><?php echo $address['countryName']; ?></li>
            </ul>
        </div>
    <?php endforeach; ?>
    <div class="col-lg-4">
        <?php if (!empty($invoice['Invoice']['shipDate'])): ?>
        <h3>Ship Date</h3>
        <p>
            <?php echo date('M d, Y', strtotime($invoice['Invoice']['shipDate'])); ?>
        </p>
        <?php endif; ?>
    </div>
</div>