<div class="watches view">
    <h2><?php  echo h($watch['Watch']['name']); ?></h2>
    <div class="row">
        <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 carousel">
            <?php echo $this->Element('carousel', compact('watch')); ?>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 info">
            <div class="info-inner">
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
                        <?php echo $this->Text->truncate($watch['Watch']['description'], 300, array('exact' => false, 'html' => false)); ?>
			<dl>
			    <dt><?php echo __('Active'); ?></dt>
			    <dd>
				<?php echo h($watch['Watch']['active'] == 1 ? 'Yes' : 'No'); ?>
			    </dd>
			    <dt><?php echo __('Created'); ?></dt>
			    <dd>
				<?php echo h($watch['Watch']['created']); ?>
			    </dd>
			    <dt><?php echo __('Modified'); ?></dt>
			    <dd>
				<?php echo h($watch['Watch']['modified']); ?>
			    </dd>
			    <?php if(isset($watch['Order']['id'])):  ?>
				<dt>Sold <?php echo date('Y-m-d', strtotime($watch['Order']['created'])); ?></dt>
				<dd>
				    <?php echo $this->Html->link('Go To Order',
						     array('controller' => 'orders', 'action' => 'view',
						       $watch['Order']['id'],
						       'admin' => true),
						     array('class' => 'btn btn-primary'));
				    ?>
				</dd>
			    <?php endif; ?>
			</dl>
                    </div>
                </div>
                <div class="row footer clearfix">
                    <p class="text-center bottom">
                        <?php echo $this->Html->link('Edit Watch', array('action' => 'edit', $watch['Watch']['id']), array('class' => 'btn btn-primary')); ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>