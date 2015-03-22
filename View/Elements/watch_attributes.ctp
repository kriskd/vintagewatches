<fieldset>
    <legend>
        Attributes
    </legend>
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <?php
                    echo $this->Form->input('name', array(
                        'label' => array(
                            'class' => 'control-label'
                        ),
                        'class' => 'form-control',
                     ));
                ?>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <?php
                    echo $this->Form->input('stockId', array(
                        'label' => array(
                            'class' => 'control-label'
                        ),
                        'class' => 'form-control',
                        'type' => 'text',
                    ));
                ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <?php
                    echo $this->Form->input('brand_id', array(
                        'class' => 'form-control',
                        'options' => $brands,
                        'empty' => 'Select One', 
                    ));
                ?>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <?php
                    echo $this->Form->input('price', array(
                        'label' => array(
                            'class' => 'control-label'
                        ),
                        'class' => 'form-control',
                        'min' => 0,
                    ));
                ?>
            </div>
        </div>
    <?php
        echo $this->Form->input('description', array('label' => array('class' => 'control-label'),
                             'class' => 'form-control'));
        //$watch isn't defined on add action, so give it a null value to avoid notices
        $watch = empty($watch) ? null : $watch;
        echo $this->Watch->active($watch);
    ?>
</fieldset>
