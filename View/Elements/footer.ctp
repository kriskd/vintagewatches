<div class="footer">
    <?php //No fat footer on checkout page ?>
    <?php if ($checkout == false): ?>
        <nav class="navbar navbar-default" role="navigation">
            <div class="container">
                <div class="row">
                    <div class="sitemap col-lg-9 col-md-8 col-sm-7 col-xs-5 col-xxs-12">
                        <h3>Sitemap</h3>
                        <div class="row">
                            <?php if ($storeOpen == true): ?>
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                    <h4>All Watches</h4>
                                    <ul>
                                        <li><?php echo $this->Html->link(
                                                                'Store',
                                                                array(
                                                                    'controller' => 'watches',
                                                                    'action' => 'index'
                                                                    )
                                                                ); ?></li>
                                    </ul>
                                    <?php if ($cartEmpty == false): ?>
                                        <h4>Items in Your Cart: <?php echo $cartCount; ?></h4>
                                        <ul>
                                            <li><?php echo $this->Html->link('Checkout',
                                                                         array('controller' => 'orders', 'action' => 'checkout', 'admin' => false),
                                                                         array('escape' => false),
                                                                         false); ?></li>
                                        </ul>
                                    <?php endif; ?>
                                    <h4>eBay</h4>
                                    <?php echo $this->Html->image('http://pics.ebay.com/aw/pics/ebay_my_button.gif', array(
                                                                                        'url' => 'http://cgi6.ebay.com/ws/ebayISAPI.dll?ViewListedItemsLinkButtons&userid=brtime',
                                                                                        'alt' => 'My items on eBay'
                                                                                    )
                                    ); ?>
                                </div>
                                <?php if (!empty($brandsWithWatches)): ?>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <h4>Brands Available Now</h4>
                                        <ul>
                                            <?php foreach ($brandsWithWatches as $id => $brand): ?>
                                                <?php echo $this->Html->tag('li', $this->Html->link($brand, array(
                                                                                                            'controller' => 'watches',
                                                                                                            'action' => 'index',
                                                                                                            'admin' => false,
                                                                                                            'brand' => $id
                                                                                                        ))); ?>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <h4>Support</h4>
                                <ul>
                                    <?php echo $this->Element('support'); ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="announcement-list col-lg-3 col-md-4 col-sm-5 col-xs-7 col-xxs-12">
                        <h3>Mailing List</h3>
                        <h5 class="announcement-list-head">Join my mailing list and be among the first to know about new watches
                        added to my website!</h5>
                        <div class="announcement-list-signup">
                            <p class="title">Join My Mailing List</p>
                            <form action="http://scripts.dreamhost.com/add_list.cgi" method="post" class="form-inline" role="form">
                                <input type="hidden" name="list" value="bruce" />
                                <input type="hidden" name="domain" value="brucesvintagewatches.com" />
                                <input type="hidden" name="url" value="http://SubscribedURL" /> 
                                <input type="hidden" name="unsuburl" value="<?php echo $currentUrl; ?>" /> 
                                <input type="hidden" name="alreadyonurl" value="<?php echo $currentUrl; ?>" /> 
                                <input type="hidden" name="notonurl" value="<?php echo $currentUrl; ?>" /> 
                                <input type="hidden" name="invalidurl" value="<?php echo $currentUrl; ?>" /> 
                                <input type="hidden" name="emailconfirmurl" value="<?php echo $currentUrl; ?>" /> 
                                <label>Email:</label>
                                <input type="text" name="email" class="form-control input-sm">
                                <div class="buttons">
                                    <input type="submit" name="submit" value="Join" class="submit btn btn-default btn-sm" />
                                    <input type="submit" name="unsub" value="Unsubscribe" class="submit btn btn-default btn-sm" />
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    <?php endif; ?>
    <nav class="navbar navbar-inverse" role="navigation">
        <div class="container">
            <p class="navbar-text">Bruce's Vintage Watches &bull; P.O. Box 74 &bull; Evansville, WI 53536</p>
        </div>
    </nav>
</div>