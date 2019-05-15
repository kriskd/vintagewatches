<?php echo $this->Form->create('Item', ['type' => 'file']); ?>
<?php $this->Form->inputDefaults(['class' => 'form-control']); ?>
<?php if (strcasecmp($action, 'edit')==0): ?>
    <?php echo $this->Form->input('Item.id', array('type' => 'hidden')); ?>
<?php endif; ?>
	<fieldset>
		<legend><?= $action ?> <?php echo __('Item'); ?></legend>
	<?php
		echo $this->Form->input('name');
		echo $this->Form->input('summary');
		echo $this->Form->input('description');
        echo $this->Form->input('active', ['class' => 'checkbox-inline']);
		echo $this->Form->input('sequence');
		echo $this->Form->input('quantity');
		echo $this->Form->input('price');
		//echo $this->Form->input('Shipping');
        if (!empty($this->request->data['Item']['filenameThumb'])):
            echo $this->Html->image($this->request->data['Item']['filenameThumb']);
        endif;
        echo $this->Form->input('filename', array(
            'type' => 'file',
            //'label' => false,
            'class' => 'image-upload',
        ));
	?>
	</fieldset>
<?php echo $this->Form->end(['text' => __('Submit'), 'class' => 'btn btn-primary']); ?>

<?php $this->append('css'); ?>
    <?php echo $this->Html->css('/css/tinyeditor.css'); ?>
<?php $this->end(); ?>
<?php $this->append('script'); ?>
    <?php echo $this->Html->script('/js/src/tiny.editor.packed.js'); ?>
    <script type="text/javascript">
        $(document).ready(function(){
            var myInstance = new TINY.editor.edit('editor',{
                id:'ItemDescription', // (required) ID of the textarea
                width:'100%', // (optional) width of the editor
                height:175, // (optional) heightof the editor
                cssclass:'tinyeditor', // (optional) CSS class of the editor
                controlclass:'tinyeditor-control', // (optional) CSS class of the buttons
                rowclass:'tinyeditor-header', // (optional) CSS class of the button rows
                dividerclass:'tinyeditor-divider', // (optional) CSS class of the button diviers
                controls:['bold', 'italic', 'underline', 'strikethrough', '|',
                          'subscript', 'superscript', '|', 'orderedlist', 'unorderedlist', '|' ,
                          'outdent' ,'indent', '|', 'leftalign', 'centeralign', 'rightalign', 'blockjustify', '|',
                          'unformat', '|', 'undo', 'redo', 'n', 'font', 'size', 'style', '|', 'image', 'hr', 'link', 'unlink', '|', 'print'], // (required) options you want available, a '|' represents a divider and an 'n' represents a new row
                footer:true, // (optional) show the footer
                fonts:['Verdana','Arial','Georgia','Trebuchet MS'],  // (optional) array of fonts to display
                xhtml:true, // (optional) generate XHTML vs HTML
                //cssfile:'style.css', // (optional) attach an external CSS file to the editor
                //content:'starting content', // (optional) set the starting content else it will default to the textarea content
                css:'body{font:12px Verdana,Arial}', // (optional) attach CSS to the editor
                bodyid:'editor', // (optional) attach an ID to the editor body
                footerclass:'tinyeditor-footer', // (optional) CSS class of the footer
                toggle:{text:'source',activetext:'wysiwyg',cssclass:'toggle'}, // (optional) toggle to markup view options
                resize:{cssclass:'resize'} // (optional) display options for the editor resize
            });
            $(document).on('submit', '#ItemAdminEditForm, #ItemAdminAddForm', function(){
                myInstance.post();
            });
        });
    </script>
<?php $this->end(); ?>
