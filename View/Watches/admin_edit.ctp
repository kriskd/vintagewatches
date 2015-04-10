<div class="watches form">

	<?php echo $this->Element('form_watch', array('action' => 'Edit')); ?>

    <?php if (empty($watch['Watch']['order_id'])): ?>
        <div class="row">
            <div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
                <?php if(!empty($watch['Image'])): ?>
                    <div class="watch-images">
                        <?php $images = $watch['Image']; ?>
                        <?php foreach($images as $image): ?>
                            <div class="image-thumb">
                                <?php echo $this->Html->link($this->Html->thumbImage($watch['Watch']['id'], $image['filenameThumb']),
                                                             '#delete-' . $watch['Watch']['id'] . '-' . $image['id'],
                                                             array('escape' => false, 'data-toggle' => 'modal')
                                                             );
                                ?>
                                <div class="modal fade" id="delete-<?php echo $watch['Watch']['id']; ?>-<?php echo $image['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                <h4 class="modal-title">Confirm Image Delete</h4>
                                            </div>
                                            <div class="modal-body">
                                                <?php echo $this->Html->thumbImage($watch['Watch']['id'], $image['filenameThumb'],
                                                                                    array('escape' => false)
                                                             ); ?>
                                            </div>
                                            <div class="modal-footer">
                                                <?php echo $this->Html->link('Close', '#', array('data-dismiss' => 'modal',
                                                                                                 'class' => 'btn btn-default btn-lg')); ?>
                                                <?php echo $this->Html->link('Delete', array('controller' => 'images',
                                                                                             'action' => 'delete', $image['id']),
                                                                                        array('class' => 'btn btn-danger btn-lg')); ?>
                                            </div>
                                        </div><!-- /.modal-content -->
                                    </div><!-- /.modal-dialog -->
                                </div><!-- /.modal -->

                                <?php if ($image['primary'] == 1): ?>
                                    <p class="label label-success">Primary</p>
                                <?php else: ?>
                                    <?php echo $this->Html->link('Make Primary',
                                                 array('controller' => 'images',
                                                   'action' => 'primary', $watch['Watch']['id'], $image['id'],
                                                   'admin' => 'true')
                                                 ); ?>
                                <?php endif; ?>

                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">
                <div class="jumbotron">
                    <?php echo $this->Form->create('Image', array('type' => 'file', 'url' => array('action' => 'upload', $watch['Watch']['id']))); ?>
                    <?php echo $this->Form->label('Add Images'); ?>
                    <?php echo $this->Form->button('Choose Files', array('type' => 'button',
                                                                'class' => 'btn btn-default fake-upload',
                                                                )
                                                            ); ?>
                    <?php echo $this->Form->input('filename.', array('type' => 'file', 'multiple', 'label' => false, 'class' => 'image-upload')); ?>
                    <?php echo $this->Form->input('watch_id', array('type' => 'hidden', 'value' => $watch['Watch']['id'])); ?>
                    <?php echo $this->Form->end(array('label' => 'Upload Image', 'class' => 'btn btn-primary')); ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
