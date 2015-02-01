<div class="watches view">
	<h2><?php  echo h($watch['Watch']['name']); ?></h2>
    <div class="row">
        <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 carousel">
            <?php echo $this->Element('carousel', compact('watch')); ?>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 info">
            <div class="info-inner">
                <?php echo $this->Element('watch_view_head', compact('watch')); ?>
                <div class="row body">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 description">
                        <?php echo $watch['Watch']['description']; ?>
                    </div>
                </div>
                <div class="row">
                    <p class="text-center bottom">
                        <?php if ($watch['Watch']['active'] == 1 && !$watch['Watch']['order_id']): ?>
                            <?php echo $this->Element('add_to_cart', compact('watch') + array('class' => 'btn btn-gold btn-lg')); ?>
                        <?php endif; ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
