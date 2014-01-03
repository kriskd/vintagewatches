<hr />
<div class="text-center">
    <p>
        <?php echo $this->Html->image('rsz_1ebay.png', array(
                                                        'url' => 'http://cgi6.ebay.com/ws/ebayISAPI.dll?ViewListedItemsLinkButtons&userid=brtime',
                                                        'alt' => 'My items on eBay'
                                                    )
                              ); ?>
        At any given time, I may have a number of watches at auction on <strong>ebay</strong>.
        <?php echo $this->Html->link('View my auctions now.', 'http://cgi6.ebay.com/ws/ebayISAPI.dll?ViewListedItemsLinkButtons&userid=brtime'); ?>
    </p>
</div>