<?php echo $this->Form->create('Watch', array('class' => 'form-horizontal', 'autocomplete' => 'off', 'novalidate' => true)); ?>
<fieldset>
    <legend>Information</legend>
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
            <?php echo $this->Form->input('Watch.type', array(
                'label' => array(
                    'class' => 'control-label',
                    'text' => 'Acquisition Type',
                ),
                'options' => $acquisitions,
                'class' => 'form-control',
                'type' => 'radio',
                'div' => 'input radio consignment-purchase',
            )); ?>
            <div class="purchase hidden">
                <?php echo $this->Form->input('Purchase.id', ['type' => 'hidden']); ?>
                <?php echo $this->Form->input('Purchase.source_id', array(
                    'label' => array(
                        'class' => 'control-label',
                    ),
                    'options' => $sources,
                    'class' => 'form-control',
                    'empty' => 'Choose One',
                )); ?>
            </div>
            <div class="consignment hidden">
                <?php echo $this->Form->input('Consignment.id', ['type' => 'hidden']); ?>
                <?php echo $this->Form->input('Consignment.owner_id', array(
                    'label' => array(
                        'class' => 'control-label',
                    ),
                    'options' => $owners,
                    'class' => 'form-control',
                    'empty' => 'Choose One',
                )); ?>
                <?php echo $this->Form->input('Consignment.paid', array(
                    'label' => array(
                        'class' => 'control-label',
                    ),
                    'class' => 'form-control date-picker',
                    'type' => 'text',
                )); ?>
                <?php echo $this->Form->input('Consignment.returned', array(
                    'label' => array(
                        'class' => 'control-label',
                    ),
                    'class' => 'form-control date-picker',
                    'type' => 'text',
                )); ?>
            </div>
            <?php echo $this->Form->input('cost', array(
                'label' => array(
                    'class' => 'control-label',
                ),
                'class' => 'form-control',
            )); ?>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
            <?php echo $this->Form->input('repair_date', array(
                'label' => array(
                    'class' => 'control-label',
                ),
                'class' => 'form-control date-picker',
                'type' => 'text',
            )); ?>
            <?php echo $this->Form->input('repair_cost', array(
                'label' => array(
                    'class' => 'control-label',
                ),
                'class' => 'form-control',
            )); ?>
            <?php echo $this->Form->input('repair_notes', array(
                'label' => array(
                    'class' => 'control-label',
                ),
                'class' => 'form-control',
            )); ?>
            <?php echo $this->Form->input('notes', array(
                'label' => array(
                    'class' => 'control-label',
                ),
                'class' => 'form-control',
            )); ?>
        </div>
    </div>
</fieldset>
<?php echo $this->Element('watch_attributes'); ?>
<?php echo $this->Form->end(array('label' => __('Save Watch'), 'class' => 'btn btn-primary')); ?>
<?php $action = ['action' => 'index']; ?>
<?php if (isset($watch['Watch']['id'])): ?>
    <?php $action = ['action' => 'view', $watch['Watch']['id']]; ?>
<?php endif; ?>
<?php echo $this->Html->link(__('Cancel'), $action, ['class' => 'btn btn-warning']); ?>

<?php if (!isset($sold) || !$sold): ?>
    <?php $this->append('css'); ?>
        <?php echo $this->Html->css('/css/tinyeditor.css'); ?>
    <?php $this->end(); ?>

    <?php $this->append('script'); ?>
        <?php echo $this->Html->script('/js/src/tiny.editor.packed.js'); ?>
        <script type="text/javascript">
            $(document).ready(function(){
                var myInstance = new TINY.editor.edit('editor',{
                    id:'WatchDescription', // (required) ID of the textarea
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
                $(document).on('submit', '#WatchAdminEditForm, #WatchAdminAddForm', function(){
                    myInstance.post();
                });
            });
        </script>
    <?php $this->end(); ?>
<?php endif; ?>
