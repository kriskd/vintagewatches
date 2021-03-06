<div class="footer">
    <?php //No fat footer on checkout, invoice and admin page ?>
    <?php if ($hideFatFooter == false): ?>
        <nav class="navbar navbar-default" role="navigation">
            <div class="container">
                <div class="row">
                    <div class="sitemap col-lg-9 col-md-8 col-sm-7 col-xs-5 col-xxs-12">
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
                                    <h4>Auctions</h4>
                                    <?php echo $this->Html->image('rsz_1ebay.png', array(
                                                                    'url' => 'http://www.ebay.com/sch/brtime/m.html',
                                                                    'alt' => 'My items on eBay',
                                                                    'width' => 88,
                                                                    'height' => 38,
                                                                )
                                                            ); ?>
<h4>Twitter</h4>
<a href="https://twitter.com/bruceswatches" class="twitter-follow-button" data-show-count="false">Follow @bruceswatches</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
                                </div>
                                <?php if (!empty($brandsWithWatches)): ?>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <h4>Brands Available Now</h4>
                                        <ul>
                                            <?php foreach ($brandsWithWatches as $id => $brand): ?>
                                                <?php echo $this->Html->tag('li', $this->Html->link($brand, array(
                                                                                                            'controller' => 'watches',
                                                                                                            'action' => 'index', strtolower(Inflector::slug($brand, '-')),
                                                                                                            'admin' => false,
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
                        <h4>Mailing List</h4>
                        <p class="announcement-list-head">Join my mailing list and be among the first to know about new watches added to my website!</p>
                        <div class="announcement-list-signup">
                            <p class="title">Join My Mailing List</p>
                            <p class="announce-error">Please enter a valid email address.</p>
                            <div class="response"></div>
                            <?php echo $this->Form->create('Page', array(
                                'class' => 'form-inline',
                                'role' => 'form',
                                'url' => '/mailinglist',
                            )); ?>
                            <?php echo $this->Form->input('email', array(
                                'class' => 'form-control input-sm',
                            )); ?>
                            <?php echo $this->Form->input('unsub', array(
                                'type' => 'hidden',
                            )); ?>
                            <div class="buttons">
                                <?php echo $this->Form->button('Join', array(
                                    'class' => 'submit btn join btn-gold btn-sm',
                                )); ?>
                                <?php echo $this->Form->button('Unsubscribe', array(
                                    'class' => 'submit btn unsub btn-gold btn-sm',
                                )); ?>
                            </div>
                            <?php echo $this->Form->end(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    <?php endif; ?>
    <nav class="navbar navbar-inverse" role="navigation">
        <div class="container">
            <p class="navbar-text">Bruce's Vintage Watches &bull; P.O. Box 165 &bull; Cottage Grove, WI 53527</p>
        </div>
    </nav>
</div>
