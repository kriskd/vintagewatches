<div class="watches view">
	<h2><?php  echo h($watch['Watch']['name']); ?></h2>
	<dl>
		<dt><?php echo __('Stock Id'); ?></dt>
		<dd>
			<?php echo h($watch['Watch']['stockId']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Price'); ?></dt>
		<dd>
			<?php echo h($watch['Watch']['price']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Description'); ?></dt>
		<dd>
			<?php echo nl2br(h($watch['Watch']['description'])); ?>
			&nbsp;
		</dd>
	</dl>
	
    <?php $images = $watch['Image']; ?>
    <?php if (!empty($images)): ?>
        <div id="carousel-watch" class="carousel slide">
            <ol class="carousel-indicators">
                <?php for ($i=0; $i<count($images); $i++): ?>
                    <li data-target="#carousel-watch" data-slide-to="<?php echo $i; ?>" class="<?php echo $i == 0 ? 'active' : ''; ?>"></li>
                <?php endfor; ?>
            </ol>
            <div class="carousel-inner">
                <?php $first = true; ?>
                <?php foreach ($images as $image): ?>
                    <div class="item <?php echo $first == true ? 'active' : ''; ?>">
                        <?php $first = false; ?>
                        <?php echo $this->Html->watchImage($watch['Watch']['id'], $image['filename']); ?>
                    </div>
                <?php endforeach; ?>
            </div>
            <?php echo $this->Html->link($this->Html->tag('span', '', array('class' => 'glyphicon glyphicon-chevron-left')),
                                         '#carousel-watch', array('class' => 'left carousel-control',
                                                                  'data-slide' => 'prev',
                                                                  'escape' => false)); ?>
            <?php echo $this->Html->link($this->Html->tag('span', '', array('class' => 'glyphicon glyphicon-chevron-right')),
                                         '#carousel-watch', array('class' => 'right carousel-control',
                                                                  'data-slide' => 'next',
                                                                  'escape' => false)); ?>
        </div>
    <?php endif; ?>
</div>
