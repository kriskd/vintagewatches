<div class="watches admin-index">
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <h2><?php echo __('Watches'); ?></h2>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 watch-btns right">
            <?php echo $this->Html->link('Add Watch', array('action' => 'add'), array('class' => 'btn btn-primary add-watch')); ?>
            <?php echo $this->Watch->closeOpenStore($storeOpen); ?>
        </div>
    </div>
    <section class="header">
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <?php echo $this->Form->create('Watch', array('type' => 'get')); ?>
                    <?php echo $this->Form->input('Brand.id', array(
                                                                'options' => $brands,
                                                                'class' => 'form-control',
                                                                'label' => false,
                                                                'selected' => $brand_id
                                                            )
                                                  ); ?>
                <?php echo $this->Form->end(); ?>
            </div>
            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 btn-group right">
                <?php foreach ($buttons as $button => $attrs): ?>
                    <?php $class = array('btn', 'btn-default'); ?>
                    <?php if ($sold === $attrs['sold'] && $active === $attrs['active']): ?>
                        <?php $class[] = 'active'; ?>
                    <?php endif; ?>
                    <?php echo $this->Html->link($button, array(
                                        'action' => 'index',
                                        'active' => $attrs['active'],
                                        'sold' => $attrs['sold']
                                    ),
                                    array(
                                        'class' => implode(' ', $class),
                                        'admin' => true
                                    )
                                 ); ?>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <div class="table">
        <div class="table-row">
            <span class="table-head">Image</span>
            <span class="table-head"><?php echo $this->Paginator->sort('Brand.name', 'Brand'); ?></span>
            <span class="table-head"><?php echo $this->Paginator->sort('stockId'); ?></span>
            <span class="table-head"><?php echo $this->Paginator->sort('price'); ?></span>
            <span class="table-head"><?php echo $this->Paginator->sort('name'); ?></span>
            <span class="table-head"><?php echo $this->Paginator->sort('description'); ?></span>
            <span class="table-head"><?php echo $this->Paginator->sort('created'); ?></span>
            <span class="table-head"><?php echo $this->Paginator->sort('modified'); ?></span>
        </div>
        <?php foreach ($watches as $watch): ?>
            <?php $row = ''; ?>
            <?php $row .= $this->Html->tag('span', $this->Html->thumbPrimary($watch), array('class' => 'table-cell')); ?>
            <?php $brand = isset($watch['Brand']['name']) ? $watch['Brand']['name'] : ''; ?>
            <?php $row .= $this->Html->tag('span', $brand, array('class' => 'table-cell')); ?>
            <?php $row .= $this->Html->tag('span', h($watch['Watch']['stockId']), array('class' => 'table-cell')); ?>
            <?php $row .= $this->Html->tag('span', h($watch['Watch']['price']), array('class' => 'table-cell')); ?>
            <?php $row .= $this->Html->tag('span', h($watch['Watch']['name']), array('class' => 'table-cell')); ?>
            <?php $row .= $this->Html->tag('span', $this->Text->truncate($watch['Watch']['description'], 75, array('html' => true, 'exact' => false)), array('class' => 'table-cell')); ?>
            <?php $row .= $this->Html->tag('span', date('Y-m-d', strtotime($watch['Watch']['created'])), array('class' => 'table-cell')); ?>
            <?php $row .= $this->Html->tag('span', date('Y-m-d', strtotime($watch['Watch']['modified'])), array('class' => 'table-cell')); ?>
            <?php echo $this->Html->link($row, array('action' => 'admin_view', $watch['Watch']['id'], 'admin' => true), array('class' => 'table-row', 'escape' => false)); ?>
        <?php endforeach; ?>
	</div>
	<?php echo $this->Element('paginator'); ?>
</div>
