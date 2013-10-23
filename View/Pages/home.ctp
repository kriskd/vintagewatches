<div class="page">
    <div class="row header">
        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
            <div class="header-inner">
                <h1>Bruce's Vintage Watches</h1>
                <h4>Fine timepieces at reasonable prices from a name you trust.</h4>
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
            In business since 1989 and offering medium- to high-grade watches
            with an unconditional seven-day money back guarantee.
        </div>
    </div>
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