<div class="pages admin-edit">
    <h1><?php echo $page['Page']['name']; ?></h1>
    <?php $contents = $page['Content']; ?>
    <?php echo $this->Element('form_page', compact('contents')); ?>
</div>

<?php $this->append('css'); ?>
    <?php echo $this->Html->css('/css/tinyeditor.css'); ?>
<?php $this->end(); ?>

<?php $this->append('script'); ?>
    <?php echo $this->Html->script('/js/src/tiny.editor.packed.js'); ?> 
    <script type="text/javascript">
        $(document).ready(function(){
            var myInstance = new TINY.editor.edit('editor',{
                id:'Content0Value', // (required) ID of the textarea
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
                //toggle:{text:'source',activetext:'wysiwyg',cssclass:'toggle'}, // (optional) toggle to markup view options
                resize:{cssclass:'resize'} // (optional) display options for the editor resize
            }); 
            $(document).on('submit', '#PageAdminEditForm', function(){
                myInstance.post();
            });
        });
    </script>
<?php $this->end(); ?>