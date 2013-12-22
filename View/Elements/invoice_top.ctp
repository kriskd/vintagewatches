<h2>
    Invoice No. <?php echo $invoice['Invoice']['id']; ?>
    <div class="pull-right">
        <small><?php echo date('F j, Y', strtotime($invoice['Invoice']['created'])); ?></small>
    </div>
</h2>