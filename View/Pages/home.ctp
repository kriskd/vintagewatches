<div class="page">
    <?php echo $this->Element('header'); ?>
    <div class="row">
        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
            <?php $watches = $this->Watch->getWatches(null, true); ?>
            <?php if (!empty($watches)): ?>
               <?php echo $this->Element('carousel_home', compact('watches')); ?>
            <?php endif; ?>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
            <?php $page = ClassRegistry::init('Page')->find('first', array('conditions' => array('homepage' => 1))); ?>
            <?php foreach ($page['Content'] as $content): ?>
                <?php echo $this->Text->truncate($content['value'], 1500, array('exact' => false, 'html' => false)); ?>
                <?php echo $this->Html->link('<strong>More...</strong>', array('controller' => 'pages', 'action' => 'display', 'welcome'), array('escape' => false)); ?>
            <?php endforeach; ?>
        </div>
    </div>
</div>