<div class="ebays admin-view">
    <div class="row">
        <div class="col-lg-10">
            <h1><?php echo $item->Title; ?></h1>
        </div>
        <div class="col-lg-2">
            <?php echo $this->Html->link('View on eBay', $item->ListingDetails->ViewItemURL, array('class' => 'btn btn-success')); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <?php echo $this->Html->image($item->PictureDetails->GalleryURL); ?>
        </div>
        <div class="col-lg-6">
            <?php if ($item->SellingStatus->QuantitySold > 0): ?>
                <h2>Winner</h2>
                <dl>
                    <dt>User ID</dt>
                    <dd><?php echo isset($item->SellingStatus->HighBidder->UserID) ? $item->SellingStatus->HighBidder->UserID : '<em>None</em>'; ?></dd>
                    <dt>Email</dt>
                    <dd><?php echo isset($item->SellingStatus->HighBidder->Email) ? $item->SellingStatus->HighBidder->Email : '<em>None</em>'; ?></dd>
                    <dt>Price</dt>
                    <dd><?php echo $this->Number->currency($item->SellingStatus->CurrentPrice, 'USD'); ?></dd>
                    <dt>Shipping</dt>
                    <dd><?php echo $this->Number->currency($item->ShippingDetails->ShippingServiceOptions->ShippingServiceCost, 'USD'); ?></dd>
                </dl>
            <?php endif; ?>
        </div>
    </div>
</div>