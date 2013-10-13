<div class="recent-watches">
    <?php $recentWatches = $this->Watch->getRecentWatches(); ?>
    <?php if (count($recentWatches) <= 0): ?>
        <h4>Bruce's Vintage Watches is Currently Closed.</h4>
        <?php return; ?>
    <?php endif; ?>
    <h3>Recent Watches</h3>
    <?php foreach ($recentWatches as $watch): ?>
        <hr />
        <div class="row">
            <div class="col-xs-6 col-sm-12 col-md-6">
                <?php echo $this->Html->thumbImagePrimary($watch,
                                                          array('class' => 'center-block',
                                                                'url' => array('controller' => 'watches',
                                                                               'action' => 'view', $watch['Watch']['id']))
                                                          ); ?>
            </div>
            <div class="col-xs-6 col-sm-12 col-md-6">
                <h5><?php echo $watch['Watch']['name']; ?></h5>
                <p><?php echo $this->Number->currency($watch['Watch']['price'], 'USD'); ?></p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <?php $more = $this->Html->link('<strong>More details</strong>',
                                                array('controller' => 'watches', 'action' => 'view', $watch['Watch']['id']),
                                                array('escape' => false)); ?>
                <?php $description = $this->Text->truncate($watch['Watch']['description'], 140, array('html' => true, 'exact' => false)); ?>
                <p><?php echo $description. $more; ?></p>
            </div>
        </div>
    <?php endforeach; ?>
</div>