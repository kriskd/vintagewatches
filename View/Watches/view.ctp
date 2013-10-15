<div class="watches view">
	<h2><?php  echo h($watch['Watch']['name']); ?></h2>
    <div class="row">
	<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 carousel">
		<?php echo $this->Element('carousel', compact('watch')); ?>
	</div>
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 info">
            <div class="row head">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                    <h4><?php echo $watch['Brand']['name']; ?> 
                    <?php echo h($watch['Watch']['stockId']); ?></h4>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 right">
                    <h4><?php echo h($this->Number->currency($watch['Watch']['price'], 'USD')); ?></h4>
                </div>
            </div>
            <div class="row body">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 description">
                    <?php echo $watch['Watch']['description']; ?>
                </div>
            </div>
            <p class="text-center">
                <?php echo $this->Element('add_to_cart', compact('watch', 'controller') + array('class' => 'btn btn-gold btn-lg')); ?>
            </p>
        </div>
    </div>
	
</div>
