<div class="page">
    <?php echo $this->Element('header'); ?>
    <div class="row">
        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
            <?php echo $this->Element('carousel_home', compact('watches')); ?>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
            <?php foreach ($page['Content'] as $content): ?>
                <?php echo $this->Text->truncate($content['value'], 800, array('exact' => false, 'html' => true)); ?>
                <?php echo $this->Html->link('<strong>More...</strong>', array('controller' => 'pages', 'action' => 'display', 'welcome'), array('escape' => false)); ?>
            <?php endforeach; ?>
            <?php echo $this->Element('book'); ?>
        </div>
    </div>
</div>
