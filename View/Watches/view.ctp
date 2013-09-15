<div class="watches view">
	<h2><?php  echo h($watch['Watch']['name']); ?></h2>
    <div class="row">
        <div class="col-md-10">
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
        </div>
        <div class="col-md-2">
            <?php echo $this->Element('add_to_cart', compact('watch', 'controller') + array('class' => 'btn btn-gold btn-lg')); ?>
        </div>
    </div>
	<?php echo $this->Element('carousel', compact('watch')); ?>
</div>
