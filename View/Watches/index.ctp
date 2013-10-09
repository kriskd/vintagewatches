<div class="watches index">
    <div class="watches-header">
        <h2 class="pull-left"><?php echo __('Welcome to the Watches Page'); ?></h2>
        <?php echo $this->Html->link($this->Html->tag('span', '', array(
                                                            'class' => 'glyphicon glyphicon-eye-open'
                                                            )
                                                        ),
                                            '#',
                                            array(
                                               'class' => 'launch-tooltip pull-right show-text',
                                               'data-toggle' => 'tooltip',
                                               'data-placement' => 'top',
                                               'title' => 'Show welcome text.',
                                               'escape' => false
                                            )
                             ); ?>
    </div>
    <div class="watch-index-intro">
        <?php echo $this->Html->link($this->Html->tag('span', '', array(
                                                                    'class' => 'glyphicon glyphicon-remove'
                                                                    )
                                                                ),
                                                    '#',
                                                    array(
                                                       'class' => 'launch-tooltip pull-right hide-text',
                                                       'data-toggle' => 'tooltip',
                                                       'data-placement' => 'top',
                                                       'title' => 'Hide welcome text.',
                                                       'escape' => false
                                                    )
                                     ); ?>
        <p>
            This page is kind of the heart-and-soul of the website. Here is where I showcase
            the watches I have for sale at any given moment, and is probably the page where
            you'll spend the most time. Each "frame" below presents a small photo and a
            mini-description of each watch for sale. If you know you want the watch right
            away, click the box titled "Add to Cart." To find out more about any given watch,
            click on either the highlighted text "More details" at the end of the mini-description,
            or on the small picture to the left. You'll be taken to a page containing the
            rest of the description, along with more and larger photos of the particular watch.
            From there, you can either add the watch to your shopping cart, or return to this
            page to continue viewing more watches.
        </p>
        <p>
            When someone buys a watch, it disappears from this page. So if you visit this page
            a few days (or a few hours) later, and a watch that you looked at is no longer there,
            it's because someone bought it. <strong>Watches remain "in play"</strong> to all viewers 
            of this web page until someone "checks out" and pays for the watch. So there is a small
            (but nevertheless possible) chance that a watch sitting your shopping cart might not
            be there when you check out if someone has beaten you to it. Therefore, if there is a
            watch that you really REALLY want, I would urge you to "check out" as quickly as
            possible, and then continue shopping if there are other watches of milder interest.
            Happy shopping!
        </p>
    </div>
    <?php foreach ($watches as $i => $watch): ?>
        <div class="row">
            <div class="watch">
                <div class="row watch-attrs">
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 name">
                        <?php echo h($watch['Watch']['name']); ?>
                    </div>
                    <div class="col-xs-6 col-sm-6 col-md-3 col-lg-3 stockid">
                        <?php echo h($watch['Watch']['stockId']); ?>
                    </div>
                    <div class="col-xs-6 col-sm-6 col-md-3 col-lg-3 price">
                        <?php echo h($this->Number->currency($watch['Watch']['price'], 'USD')); ?>
                    </div>
                </div>
                <div class="col-xs-6 col-sm-2 col-md-2 col-lg-2 center-block">
                    <div class="watch-thumb">
                        <?php echo $this->Html->thumbImagePrimary($watch,
                                                          array('class' => 'center-block',
                                                                'url' => array('controller' => 'watches',
                                                                               'action' => 'view', $watch['Watch']['id']))
                                                          ); ?>
                    </div>
                    <div class="watch-cart-button">
                        <?php echo $this->Element('add_to_cart', compact('watch', 'controller') + array('class' => 'btn btn-gold')); ?>
                    </div>
                </div>
                <div class="col-xs-6 col-sm-10 col-md-10 col-lg-10">
                    <div class="row">
                        <div class="col-lg-12">
                            <?php $description = $watch['Watch']['description']; ?>
                            <?php $more = $this->Html->link('More details', array('action' => 'view', $watch['Watch']['id']), array()); ?>
                            <?php $description = $this->Watch->shortDescription($description, $more); ?>
                            <?php echo $description; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
    <div class="row">
        <p>
        <?php
        echo $this->Paginator->counter(array(
        'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
        ));
        ?>	</p>
        <div class="paging">
        <?php
            echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
            echo $this->Paginator->numbers(array('separator' => ''));
            echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
        ?>
        </div>
    </div>
</div>
