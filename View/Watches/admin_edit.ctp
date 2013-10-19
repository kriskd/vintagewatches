<div class="watches form">
	
	<?php echo $this->Element('form_watch', array('action' => 'Edit')); ?>
	
	<?php if(!empty($watch['Image'])): ?>
		<div class="watch-images">
			<?php $images = $watch['Image']; ?>
			<?php foreach($images as $image): ?>
				<div class="image-thumb">
					<?php echo $this->Html->link($this->Html->thumbImage($watch['Watch']['id'], $image['filename']),
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
                                    <?php echo $this->Html->thumbImage($watch['Watch']['id'], $image['filename'],
                                                                        array('escape' => false)
                                                 ); ?>
                                </div>
                                <div class="modal-footer">
                                    <?php echo $this->Html->link('Close', '#', array('data-dismiss' => 'modal',
                                                                                     'class' => 'btn btn-default btn-lg')); ?>
                                    <?php echo $this->Html->link('Delete', array('controller' => 'images',
                                                                                 'action' => 'delete', $watch['Watch']['id'], $image['id']),
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
				<?php /*echo $this->Html->watchImage($watch['Watch']['id'], $image['filename'],
							      array('url' =>
								    array('controller' => 'images',
									  'action' => 'delete_picture', $image['id'], 'admin' => 'true'),
								    ));*/
				?>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
    
    <div class="jumbotron">
        <?php echo $this->Form->create('Image', array('type' => 'file',
                                  'url' => array('action' => 'picture', $watch['Watch']['id']),
                                  )); ?>
            <?php echo $this->Form->label('Add Image'); ?>
            <?php echo $this->Form->button('Choose File', array('type' => 'button',
                                                                'class' => 'btn btn-default fake-upload',
                                                                )
                                                            ); ?>
            <?php echo $this->Form->input('image', array('type' => 'file',
                                     'label' => false,
                                     'class' => 'image-upload')); ?>
        <?php echo $this->Form->end(array('label' => 'Upload Image', 'class' => 'btn btn-primary')); ?>
    </div>
    
    <?php echo $this->Form->create('Image', array('type' => 'file', 'url' => array('action' => 'upload', $watch['Watch']['id']))); ?>
    <?php echo $this->Form->input('filename', array('type' => 'file')); ?>
    <?php echo $this->Form->input('watch_id', array('type' => 'hidden', 'value' => $watch['Watch']['id'])); ?>
    <?php echo $this->Form->end('Submit'); ?>
</div>