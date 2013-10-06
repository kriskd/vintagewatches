<div class="watches view">
<h2><?php echo h($watch['Watch']['name']); ?></h2>
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
                <dt><?php echo __('Name'); ?></dt>
                <dd>
                    <?php echo h($watch['Watch']['name']); ?>
                    &nbsp;
                </dd>
                <dt><?php echo __('Description'); ?></dt>
                <dd>
                    <?php echo $watch['Watch']['description']; ?>
                    &nbsp;
                </dd>
                <dt><?php echo __('Active'); ?></dt>
                <dd>
                    <?php echo h($watch['Watch']['active'] == 1 ? 'Yes' : 'No'); ?>
                    &nbsp;
                </dd>
                <dt><?php echo __('Created'); ?></dt>
                <dd>
                    <?php echo h($watch['Watch']['created']); ?>
                    &nbsp;
                </dd>
                <dt><?php echo __('Modified'); ?></dt>
                <dd>
                    <?php echo h($watch['Watch']['modified']); ?>
                    &nbsp;
                </dd>
                <?php if(!empty($watch['Image'])): ?>
                    <?php $images = $watch['Image']; ?>
                    <?php foreach($images as $image): ?>
                        <?php if($image['type'] == 'thumb'): ?>
                            <dt>Image</dt>
                            <dd>
                                <?php echo $this->Html->image('/files/thumbs/' . $image['filename']); ?>
                            </dd>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
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
        <div class="col-md-2">
            <?php echo $this->Html->link('Edit Watch', array('action' => 'edit', $watch['Watch']['id']), array('class' => 'btn btn-primary btn-lg')); ?>
        </div>
    </div>
	<?php echo $this->Element('carousel', compact('watch')); ?>
</div>