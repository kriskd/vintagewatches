<div class="contacts index">
    <?php echo $this->Form->create('Contact', array('role' => 'form')); ?>
    <?php echo $this->Form->input('name', array('class' => 'form-control')); ?>
    <?php echo $this->Form->input('email', array('class' => 'form-control')); ?>
    <?php echo $this->Form->input('comment', array('class' => 'form-control', 'label' => 'Comment/Question')); ?>
    
    <p>Verify that you are a human, please choose the <strong><?php echo $selectedItem; ?></strong>.</p>
    <?php //Add hidden field so the key is always included in the data sent even without a selected value ?>
    <?php echo $this->Form->input('Contact.s3captcha', array('type' => 'hidden', 'value' => 0)); ?>
    <div id="s3captcha-images">
        <?php foreach ($items as $rand => $item): ?>
            <div class="s3captcha">
                <span>
                    <?php echo $item; ?>
                    <?php echo $this->Form->radio('Contact.s3captcha', array($rand => ''), array('hiddenField' => false,
                                                                                         'legend' => false,
                                                                                         'label' => false,
                                                                                         'div' => false)
                                                  ); ?>
                </span>
                <div class="img <?php echo $item; ?>"></div>
            </div>
        <?php endforeach; ?>
    </div>
    <?php echo $this->Form->error('s3captcha-error', __('Error with captcha.', true)); ?>
    
    <?php echo $this->Form->end(array('label' => 'Submit', 'class' => 'btn btn-gold')); ?>
</div>

<?php $this->append('script'); ?>
    <?php echo $this->Html->script('/js/src/s3Capcha.js'); ?>
    <script type="text/javascript">
	$(document).ready(function() {
            $('#s3captcha-images').s3Capcha();
        });
    </script>
<?php $this->end(); ?>