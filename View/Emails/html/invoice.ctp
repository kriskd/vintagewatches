<table style="width: 600px;">
    <tr>
        <td bgcolor="#999999" style="border: 2px solid #665600; padding: 0px 10px; width: 450px">
            <p style="font-size: 36px; font-weight: bold; color: #665600">Bruce's Vintage Watches</p>
            <p style="font-size: 18px; font-weight: bold; color: #665600">Fine timepieces at reasonable prices from a name you trust.</p>
        </td>
        <td>
            <p style="font-weight: bold; margin-left: 20px; width: 150px">
                In business since 1989 and offering medium- to high-grade watches with an unconditional seven-day money back guarantee. 
            </p>
        </td>
    </tr>
</table>
<h1 style="font-size: 36px;">Invoice No. <?php echo $invoice['Invoice']['id']; ?></h1>
<table style="width: 600px;">
    <?php foreach($invoice['Address'] as $address): ?>
        <?php echo $this->Element('email_address', compact('address')); ?>
    <?php endforeach; ?>
    <tr>
        <td colspan=4 style="padding: 0px 10px;">
            <p style="font-size: 24px; color: #665600">Invoice Details</p>
        </td>
    </tr>
    <tr>
        <td>Item Id/Code</td>
        <td colspan=2>Description</td>
        <td>Amount</td>
    </tr>
    <?php foreach ($invoice['InvoiceItem'] as $item): ?>
        <tr>
            <td><?php echo $item['itemid']; ?></td>
            <td colspan=2><?php echo $item['description']; ?></td>
            <td><?php echo $this->Number->currency($item['amount'], 'USD'); ?></td>
        </tr>
    <?php endforeach; ?>
    <tr>
        <td></td>
        <td colspan=2>Shipping</td>
        <td><?php echo $this->Number->currency($invoice['Invoice']['shippingAmount']); ?></td>
    </tr>
    <tr>
        <td></td>
        <td colspan=2>Total</td>
        <td><?php echo $this->Number->currency($this->Invoice->total($invoice), 'USD'); ?></td>
    </tr>
    <?php if (!empty($invoice['Invoice']['notes'])): ?>
        <tr>
            <td colspan=4 style="padding: 0px 10px;">
                <p style="font-size: 24px; color: #665600">Notes From <?php echo empty($invoice['Address'][0]['firstName']) ? 'Customer' : $invoice['Address'][0]['firstName']; ?></p>
            </td>
        </tr>
        <tr>
            <td colspan=4>
                <?php echo $invoice['Invoice']['notes']; ?>
            </td>
        </tr>
    <?php endif; ?>
    <?php if (!empty($invoice['Invoice']['invoiceNotes'])): ?>
        <tr>
            <td colspan=4 style="padding: 0px 10px;">
                <p style="font-size: 24px; color: #665600">Invoice Notes</p>
            </td>
        <tr>
            <td colspan=4>
                <?php echo $invoice['Invoice']['invoiceNotes']; ?>
            </td>
        </tr>
    <?php endif; ?>
    <tr>
        <td colspan=4>
            <p>
                Visit <?php echo $this->Html->link('brucesvintagewatches.com/orders', 'http://brucesvintagewatches.com/orders'); ?> to get your entire order history.
            </p>
        </td>
    </tr>
</table>