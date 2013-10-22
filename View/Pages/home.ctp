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
            <?php $watches = $this->Watch->getWatches();  ?>
            <?php if (!empty($watches)): ?>
                <div id="carousel-home" class="carousel slide"> 
                    <ol class="carousel-indicators">
                        <?php for ($i=0; $i<count($watches); $i++): ?>
                            <?php echo $this->Html->tag('li', '', array(
                                                                            'data-target' => '#carousel-watch',
                                                                            'data-slide-to' => $i,
                                                                            'class' => $i == 0 ? 'active' : ''
                                                                        )
                                                                  ); ?>
                            <li data-target="#carousel-watch" data-slide-to="<?php echo $i; ?>" class="<?php echo $i == 0 ? 'active' : ''; ?>"></li>
                        <?php endfor; ?>
                    </ol>
                    <div class="carousel-inner">
                        <?php $first = true; ?>
                        <?php foreach ($watches as $watch): ?>
                            <div class="item <?php echo $first == true ? 'active' : ''; ?>">
                                <?php $first = false; ?>
                                <?php echo $this->Html->mediumPrimary($watch, array('class' => 'img-responsive', 'url' => array('controller' => 'watches', 'action' => 'view', $watch['Watch']['id']))); ?>
                                <div class="watch-details">
                                    <div class="row">
                                        <div class="col-lg-10">
                                            <h4><?php echo $this->Html->link($watch['Watch']['name'], array('controller' => 'watches', 'action' => 'view', $watch['Watch']['id'])); ?></h4>
                                        </div>
                                        <div class="col-lg-2 right">
                                            <h4><?php echo $this->Number->currency($watch['Watch']['price'], 'USD'); ?></h4>
                                        </div>
                                    </div>
                                    <p>
                                        <?php echo $this->Text->truncate($watch['Watch']['description'], 1500, array('exact' => false, 'html' => false)); ?>
                                    </p>
                                    <h4 class="text-center see-all">
                                        <?php echo $this->Html->link('See All Watches', array('controller' => 'watches', 'action' => 'index')); ?>
                                    </h4>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <?php echo $this->Html->link($this->Html->tag('span', '', array('class' => 'glyphicon glyphicon-chevron-left')),
                                                 '#carousel-home', array('class' => 'left carousel-control',
                                                                          'data-slide' => 'prev',
                                                                          'escape' => false)); ?>
                    <?php echo $this->Html->link($this->Html->tag('span', '', array('class' => 'glyphicon glyphicon-chevron-right')),
                                                 '#carousel-home', array('class' => 'right carousel-control',
                                                                          'data-slide' => 'next',
                                                                          'escape' => false)); ?>
                </div>
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