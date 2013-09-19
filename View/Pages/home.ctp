<div class="page">
    <div class="row header">
        <div class="col-sm-8">
            <h1>Bruce's Vintage Watches</h1>
            <h4>Fine timepieces at reasonable prices from a name you trust.</h4>
        </div>
        <div class="col-sm-4">
            In business since 1989 and offering medium- to high-grade watches
            with an unconditional seven-day money back guarantee.
        </div>
    </div>
    <div class="row">
        <div class="col-sm-8">
            <?php foreach ($page['Content'] as $content): ?>
                <?php echo $content['value']; ?>
            <?php endforeach; ?>
        </div>
        <div class="col-sm-4">
            <?php echo $this->Element('recent_watches'); ?>
        </div>
    </div>
</div>