<div class="watches admin view">
    <h2><?php  echo h($watch['Watch']['name']); ?></h2>
    <div class="row same-height">
        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 well">
            <h4 class="text-center">General</h4>
            <dl>
                <dt>Acquisition Type</dt>
                <dd><?php echo ucwords($watch['Acquisition']['acquisition']); ?></dd>
                <dt>Source</dt>
                <dd><?php echo $watch['Source']['name']; ?></dd>
                <dt>Cost</dt>
                <dd><?php echo $this->Number->currency($watch['Watch']['cost'], 'USD'); ?>
                <dt>Returned Date</dt>
                <dd><?php echo $this->Watch->date($watch['Watch']['returned_date']); ?>
                <dt class="no-float">Notes</dt>
                <dd><?php echo $watch['Watch']['notes']; ?></dd>
            </dl>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 well">
            <h4 class="text-center">Repair</h4>
            <dl>
                <dt>Date</dt>
                <dd><?php echo $this->Watch->date($watch['Watch']['repair_date']); ?>
                <dt>Cost</dt>
                <dd><?php echo $watch['Watch']['repair_cost']; ?></dd>
                <dt class="no-float">Notes</dt>
                <dd><?php echo $watch['Watch']['repair_notes']; ?></dd>
            </dl>
        </div>
    </div>
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
                <dl>
                    <dt><?php echo __('Active'); ?></dt>
                    <dd>
                    <?php echo h($watch['Watch']['active'] == 1 ? 'Yes' : 'No'); ?>
                    </dd>
                    <dt><?php echo __('Created'); ?></dt>
                    <dd>
                    <?php echo $this->Watch->date($watch['Watch']['created']); ?>
                    </dd>
                    <dt><?php echo __('Modified'); ?></dt>
                    <dd>
                    <?php echo $this->Watch->date($watch['Watch']['modified']); ?>
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
                <div class="row">
                    <p class="text-center bottom">
                        <?php echo $this->Html->link('Edit Watch', array('action' => 'edit', $watch['Watch']['id']), array('class' => 'btn btn-primary', 'admin' => true)); ?>
                        <?php if ($watch['Watch']['order_id'] == null): ?>
                            <?php echo $this->Html->link('Delete Watch', '#delete-watch',
                                        array(
                                            'class' => 'btn btn-danger',
                                            'data-toggle' => 'modal'
                                        )
                                        ); ?>
                        <?php endif; ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="delete-watch" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
	<div class="modal-content">
	    <div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h4 class="modal-title">Confirm Watch Delete</h4>
	    </div>
	    <div class="modal-body">
		<p>Are you sure you want to delete <?php echo $watch['Watch']['name']; ?>?</p>
		<?php echo $this->Html->thumbPrimary($watch); ?>
	    </div>
	    <div class="modal-footer">
		<?php echo $this->Html->link('Close', '#', array('data-dismiss' => 'modal',
								 'class' => 'btn btn-default btn-lg')); ?>
		<?php echo $this->Form->postLink('Delete', array('action' => 'delete', $watch['Watch']['id'], 'admin' => true),
							array('class' => 'btn btn-danger btn-lg')); ?>                                    
	    </div>
	</div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
