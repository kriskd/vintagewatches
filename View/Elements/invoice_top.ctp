<h2>Invoice No. <?php echo $invoice['Invoice']['id']; ?></h2>
<ul>
    <li><?php echo $invoice['Invoice']['email']; ?></li>
    <li><?php echo date('F j, Y', strtotime($invoice['Invoice']['created'])); ?></li>
</ul>