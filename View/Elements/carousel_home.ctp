 <div id="carousel-home" class="carousel slide"> 
    <ol class="carousel-indicators">
        <?php for ($i=0; $i<count($watches); $i++): ?>
            <?php echo $this->Html->tag('li', '', array(
                                                        'data-target' => '#carousel-home',
                                                        'data-slide-to' => $i,
                                                        'class' => $i == 0 ? 'active' : ''
                                                        )
                                                  ); ?>
        <?php endfor; ?>
    </ol>
    <div class="carousel-inner">
        <?php $first = true; ?>
        <?php foreach ($watches as $watch): ?>
            <div class="item <?php echo $first == true ? 'active' : ''; ?>">
                <?php $first = false; ?>
                <?php echo $this->Html->mediumPrimary($watch, array('alt' => $watch['Watch']['name'], 'class' => 'img-responsive', 'url' => array('controller' => 'watches', 'action' => 'view', $watch['Watch']['id']))); ?>
                <div class="watch-details">
                    <div class="row">
                        <div class="col-lg-10 col-md-10 col-sm-9 col-xs-9">
                            <h4><?php echo $this->Html->link($watch['Watch']['name'], array('controller' => 'watches', 'action' => 'view', $watch['Watch']['id'])); ?></h4>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-3 col-xs-3 right">
                            <h4><?php echo $this->Number->currency($watch['Watch']['price'], 'USD'); ?></h4>
                        </div>
                    </div>
                    <p class="description">
                        <?php echo $this->Text->truncate($watch['Watch']['description'], 1400, array('exact' => false, 'html' => false)); ?>
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