<div class="footer">
    <nav class="navbar navbar-default" role="navigation">
        <div class="container">
            <div class="row">
                <div class="sitemap col-lg-9">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-sm">
                            <h3>Sitemap</h3>
                        </div>
                    </div>
                    <div class="row">
                        <?php if ($storeOpen == true): ?>
                            <div class="col-lg-4">
                                <h4>All Watches</h4>
                                <ul>
                                    <li><?php echo $this->Html->link(
                                                            'Store',
                                                            array(
                                                                'controller' => 'watches',
                                                                'action' => 'index'
                                                                )
                                                            ); ?></li>
                                    <?php if ($cartEmpty == false): ?>
                                        <li>Items in Your Cart: ' . $cartCount</li>
                                        <?php echo $this->Html->link('Checkout',
                                                                         array('controller' => 'orders', 'action' => 'checkout', 'admin' => false),
                                                                         array('escape' => false),
                                                                         false); ?>
                                    <?php endif; ?>
                                </ul>
                            </div>
                            <?php if (!empty($brands)): ?>
                                <div class="col-lg-4">
                                    <h4>Watches By Brand</h4>
                                    <ul>
                                        <?php foreach ($brands as $id => $brand): ?>
                                            <?php echo $this->Html->tag('li', $this->Html->link($brand, array(
                                                                                                        'controller' => 'watches',
                                                                                                        'action' => 'index',
                                                                                                        'brand' => $id
                                                                                                    ))); ?>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                        <div class="col-lg-4">
                            <h4>Support</h4>
                            <ul>
                                <?php echo $this->Element('support'); ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="pull-right constant-contact col-lg-3">
                    <!-- BEGIN: Constant Contact Basic Opt-in Email List Form -->
                    <h5 class="constant-contact-head">Join my mailing list and be among the first to know about new watches
                    added to my website!</h5>
                    <table>
                        <tr>
                            <td class="constant-contact-title">Join My Mailing List</td>
                        </tr>
                        <tr>
                            <td class="constant-contact-body">
                                <form name="ccoptin" action="http://visitor.r20.constantcontact.com/d.jsp" target="_blank" method="post" class="form-inline">
                                    <input type="hidden" name="llr" value="9wat99bab">
                                    <input type="hidden" name="m" value="1101485671130">
                                    <input type="hidden" name="p" value="oi">
                                    <label>Email:</label>
                                    <input type="text" name="ea" size="20" value="" class="form-control input-sm">
                                    <input type="submit" name="go" value="Go" class="submit btn btn-default btn-sm">
                                </form>
                            </td>
                        </tr>
                    </table>
                    <!-- END: Constant Contact Basic Opt-in Email List Form -->
                    <!-- BEGIN: SafeSubscribe -->
                    <img src="https://imgssl.constantcontact.com/ui/images1/safe_subscribe_logo.gif" border="0" width="168" height="14" alt=""/>
                    <!-- END: SafeSubscribe -->
                </div>
            </div>
        </div>
    </nav>
    <nav class="navbar navbar-inverse" role="navigation">
        <div class="container">
            <p class="navbar-text">Bruce's Vintage Watches &bull; P.O. Box 74 &bull; Evansville, WI 53536</p>
        </div>
    </nav>
</div>