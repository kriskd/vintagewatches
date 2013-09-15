<div class="recent-watches">
    <?php $recentWatches = $this->Watch->getRecentWatches(); ?>
    <h3>Recent Watches</h3>
    <?php foreach ($recentWatches as $watch): ?>
        <hr />
        <div class="row">
            <div class="col-md-5">
                <?php echo $this->Html->thumbImagePrimary($watch,
                                                          array('class' => 'center-block',
                                                                'url' => array('controller' => 'watches',
                                                                               'action' => 'view', $watch['Watch']['id']))
                                                          ); ?>
            </div>
            <div class="col-md-7">
                <h5><?php echo $watch['Watch']['name']; ?></h5>
                <p><?php echo $this->Number->currency($watch['Watch']['price'], 'USD'); ?></p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <?php $more = $this->Html->link('<strong>More details</strong>',
                                                array('controller' => 'watches', 'action' => 'view', $watch['Watch']['id']),
                                                array('escape' => false)); ?>
                <p><?php echo $this->Watch->shortDescription($watch['Watch']['description'], $more, 20); ?></p>
            </div>
        </div>
    <?php endforeach; ?>
</div>