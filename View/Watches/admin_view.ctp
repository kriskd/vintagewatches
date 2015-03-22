<div class="watches admin view">
    <h2><?php  echo h($watch['Watch']['name']); ?></h2>
    <?php echo $this->Html->link('View Images', '#', array(
        'data-toggle' => 'modal',
        'data-target' => '#watch-carousel', 
    )); ?>
    <div class="row">
        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
            <h4><?php echo $status; ?></h4>
            <dl class="prices">
                <dt>Price</dt>
                <dd><?php echo h($this->Number->currency($watch['Watch']['price'], 'USD')); ?></dd>
                <dt>Cost</dt>
                <dd><?php echo h($this->Number->currency($watch['Watch']['cost'], 'USD')); ?></dd>
                <dt>Repair</dt>
                <dd><?php echo h($this->Number->currency($watch['Watch']['repair_cost'], 'USD')); ?></dd>
                <?php if ($sold): ?>
                    <dt>Profile/(Loss)</dt>
                    <dd><?php echo h($this->Number->currency(($watch['Watch']['price'] - $watch['Watch']['cost'] - $watch['Watch']['repair_cost']), 'USD')); ?>
                <?php endif; ?>
            </dl>
            <?php if (!empty($watch['Watch']['repair_date']) || !empty($watch['Watch']['repair_notes'])): ?>
                <h4>Repair Notes</h4>
                <p><strong>Date: </strong><?php echo $watch['Watch']['repair_date']; ?></p>
                <p><?php echo $watch['Watch']['repair_notes']; ?></p>
            <?php endif; ?>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
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
        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 info">
            <div class="info-inner">
                <div class="row body">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 description">
                        <?php echo $watch['Watch']['description']; ?>
                        <dl>
                            <dt><?php echo __('Status'); ?></dt>
                            <dd>
                            <?php echo $status; ?> 
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
                        <?php if (!$sold): ?>
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
    <div class="modal fade" id="watch-carousel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <?php echo $watch['Watch']['name']; ?>
                </div>
                <div class="modal-body">
                    <?php echo $this->Element('carousel', compact('watch')); ?>
                </div>
                <div class="modal-footer">
                    <?php echo $this->Html->link('Close', '#', array(
                        'data-dismiss' => 'modal',
                        'class' => 'btn btn-default btn-lg'
                    )); ?>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
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
                <?php echo $this->Html->link('Close', '#', array(
                    'data-dismiss' => 'modal',
                    'class' => 'btn btn-default btn-lg'
                )); ?>
                <?php echo $this->Form->postLink('Delete', array(
                    'action' => 'delete', $watch['Watch']['id'], 
                    'admin' => true
                ),
                array(
                    'class' => 'btn btn-danger btn-lg'
                )); ?>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
