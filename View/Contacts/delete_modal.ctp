<div class="modal fade" id="delete-contact" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Contact Delete</h4>
            </div>
            <div class="modal-body">
                <p>
                    Are you sure you want to delete the message from
                    <?php echo $contactName; ?>?
                </p>
            </div>
            <div class="modal-footer">
                <?php echo $this->Html->link('Close', '#', array('data-dismiss' => 'modal',
                                                                 'class' => 'btn btn-default btn-lg')); ?>
                <?php echo $this->Form->postLink('Delete', array('action' => 'delete', $contactId, $query, 'admin' => true),
                                                        array('class' => 'btn btn-danger btn-lg')); ?>                                    
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->