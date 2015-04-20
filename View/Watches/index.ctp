<div class="watches index">
    <div class="watches-header row">
        <?php if (isset($brand)): ?>
            <p class="current-view">Currently viewing <?php echo $brand; ?> watches.
            <?php echo $this->Html->link('View all watch brands', array('action' => 'index')); ?>.</p>
        <?php endif; ?>
		<div class="row">
			<div class="col-lg-8 col-md-8 col-sm-8 col-sm-8 col-xs-10">
				<h2>
					<?php echo __('Bruce\'s Vintage Watches'); ?>
					<?php if (isset($brand)): ?>
						- <?php echo $brand; ?>
					<?php endif; ?>
				</h2>
			</div>
			<div class="col-lg-1 col-lg-push-3 col-md-1 col-md-push-3 col-sm-1 col-sm-push-3 col-xs-2 show-text">
				<?php echo $this->Html->link($this->Html->tag('span', '', array(
																'class' => 'glyphicon glyphicon-eye-open'
																)
															),
												'#',
												array(
												   'class' => 'launch-tooltip pull-right',
												   'data-toggle' => 'tooltip',
												   'data-placement' => 'top',
												   'title' => 'Show welcome text.',
												   'escape' => false
												)
								 ); ?>
			</div>
			<div class="col-lg-3 col-lg-pull-1 col-md-3 col-md-pull-1 col-sm-3 col-sm-pull-1 col-xs-12">
				<?php echo $this->Form->create('Watch', [
					'type' => 'get',
					'class' => 'form-inline',
				]); ?>
				<?php echo $this->Form->input('sortby', [
					'options' => [
						'created-desc' => 'Most Recent',
						'Brand.name-asc' => 'Brand',
						'price-asc' => 'Price Low to High',
						'price-desc' => 'Price High to Low',
					],
					'class' => 'form-control',
					'label' => [
						'text' => 'Sort by&nbsp;',
						'class' => 'hidden-sm hidden-xs',
					],
				]); ?>
				<?php echo $this->Form->end(); ?>
			</div>
		</div>
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
    <?php if (!empty($watches)): ?>
        <?php foreach ($watches as $watch): ?>
            <div class="row">
                <div class="watch">
                    <div class="row watch-attrs">
                        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 name">
                            <?php echo $this->Html->link(h($watch['Watch']['name']), array('action' => 'view', $watch['Watch']['id']), array('escape' => false)); ?>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-4 col-lg-4 stockid">
                            Stock Number <?php echo h($watch['Watch']['stockId']); ?>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-4 col-lg-4 price">
                            <?php echo h($this->Number->currency($watch['Watch']['price'], 'USD')); ?>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-2 col-md-2 col-lg-2 watch-details">
                        <div class="watch-thumb">
                            <?php echo $this->Html->thumbPrimary($watch,
                                                                array(
                                                                    'url' => array(
                                                                                'controller' => 'watches',
                                                                                'action' => 'view', $watch['Watch']['id']
                                                                            ),
                                                                    'alt' => $watch['Watch']['name']
                                                                )
                                                            ); ?>
                        </div>
                        <div class="watch-cart-button">
                            <?php echo $this->Element('add_to_cart', compact('watch', 'controller') + array('class' => 'btn btn-gold')); ?>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-10 col-md-10 col-lg-10">
                        <div class="row">
                            <div class="col-lg-12">
                                <?php $description = $this->Text->truncate($watch['Watch']['description'], 350, array('html' => true, 'exact' => false)); ?>
                                <?php $more = $this->Html->link('<strong>More details</strong>', array('action' => 'view', $watch['Watch']['id']), array('escape' => false)); ?>
                                <?php echo $description . $more; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>There are currently no <?php echo isset($brand) ? $brand . ' ' : ''; ?>watches available.</p>
    <?php endif; ?>
    <div class="row">
        <?php echo $this->Element('paginator'); ?>
    </div>
</div>
