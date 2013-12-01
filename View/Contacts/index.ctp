<div class="contacts index">
    <p>Dear friends and customers:</p>
    <p> 
	Please use this form to contact me, Bruce Shawkey (I'm the only here at Bruce's
	Vintage Watches!) about anything watch-related that may be on your mind. I of
	course want to hear about any concerns you might be having with recent orders,
	or watches you have bought from me, or problems with the website. But I'd also
	enjoy reading happy news from you, too, like watches you'd like to sell or trade,
	or ideas you have for the website, and so forth. Whatever the news, good or
	not-so good, I'll get back to you via email within 24 hours, and often more
	quickly. Cheers, Bruce Shawkey.
    </p>
    <?php echo $this->Form->create('Contact', array('role' => 'form')); ?>
    <?php echo $this->Form->input('name', array('class' => 'form-control')); ?>
    <?php echo $this->Form->input('email', array('class' => 'form-control')); ?>
    <?php echo $this->Form->input('comment', array('class' => 'form-control', 'label' => 'Comment/Question')); ?>
    <div class="verify">
	<p>
	    <span class="glyphicon glyphicon-hand-right"></span>
	    Verify that you are a human, please choose the <strong><?php echo $selectedItem; ?></strong>.
	</p>
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
	<?php echo $this->Form->error('s3captcha-error', __('Try again, select the '. $selectedItem .'.', true)); ?>
    </div>
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