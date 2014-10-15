<div class="watches admin-index">
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <h2><?php echo __('Watches'); ?></h2>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 admin-btns text-right">
            <?php echo $this->Html->link('Add Watch', array('action' => 'add'), array('class' => 'btn btn-primary add-watch')); ?>
            <?php echo $this->Watch->closeOpenStore($storeOpen); ?>
        </div>
    </div>
    <section class="header">
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <?php echo $this->Form->create('Watch', array('type' => 'get')); ?>
                    <?php foreach($this->params->query as $key => $value): ?>
                        <?php if (strcasecmp($key, 'page')!=0): ?>
                            <?php echo $this->Form->input('Watch.'.$key, array('type' => 'hidden', 'value' => $value)); ?>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    <?php echo $this->Form->input('Brand.id', array(
                                                                'options' => $brands,
                                                                'class' => 'form-control',
                                                                'label' => false,
                                                                'selected' => $brand_id
                                                            )
                                                  ); ?>
                <?php echo $this->Form->end(); ?>
            </div>
            <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 btn-group">
                <?php foreach ($buttons as $button => $attrs): ?>
                    <?php $class = array('btn', 'btn-default'); ?>
                    <?php if ($sold == $attrs['sold'] && $active == $attrs['active']): ?>
                        <?php $class[] = 'active'; ?>
                    <?php endif; ?>
                    <?php echo $this->Html->link($button, array(
                                        'action' => 'index',
                                        '?' => array_merge($this->params->query, array('active' => $attrs['active'], 'sold' => $attrs['sold'])),
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
    <?php echo $this->Form->create('Order', array('type' => 'get', 'url' => array('controller' => 'orders', 'action' => 'view', 'admin' => true))); ?>
    <?php echo $this->Form->input('orderId', array('value' => '', 'type' => 'hidden')); ?>
    <div class="table">
        <div class="table-row">
            <span class="table-head">Image</span>
            <span class="table-head">Order/Active</span>
            <span class="table-head"><?php echo $this->Paginator->sort('Brand.name', 'Brand'); ?></span>
            <span class="table-head"><?php echo $this->Paginator->sort('stockId'); ?></span>
            <span class="table-head"><?php echo $this->Paginator->sort('price'); ?></span>
            <span class="table-head"><?php echo $this->Paginator->sort('name'); ?></span>
            <span class="table-head"><?php echo $this->Paginator->sort('created'); ?></span>
            <span class="table-head"><?php echo $this->Paginator->sort('modified'); ?></span>
        </div>
        <?php foreach ($watches as $watch): ?>
            <?php $row = ''; ?>
            <?php $row .= $this->Html->tag('span', $this->Html->thumbPrimary($watch), array('class' => 'table-cell')); ?>
        <?php $orderButton = $this->Form->button('Order', array('data-orderid' => $watch['Watch']['order_id'], 'class' => 'btn btn-primary goto-order')); ?>
        <?php $activeCheckbox = $this->Form->input('Watch.'.$watch['Watch']['id'].'.active', array('data-watchid' => $watch['Watch']['id'], 'class' => '', 'div' => array('class' => 'active-checkbox text-center'), 'label' => false, 'checked' => $watch['Watch']['active'])); ?>
        <?php $row .= $this->Html->tag('span', empty($watch['Watch']['order_id']) ? $activeCheckbox : $orderButton, array('class' => 'table-cell text-center', 'escape' => false)); ?>
            <?php $brand = isset($watch['Brand']['name']) ? $watch['Brand']['name'] : ''; ?>
            <?php $row .= $this->Html->tag('span', $brand, array('class' => 'table-cell')); ?>
            <?php $row .= $this->Html->tag('span', h($watch['Watch']['stockId']), array('class' => 'text-center table-cell')); ?>
            <?php $row .= $this->Html->tag('span', h($this->Number->currency($watch['Watch']['price'], 'USD')), array('class' => 'text-right table-cell')); ?>
            <?php $row .= $this->Html->tag('span', h($watch['Watch']['name']), array('class' => 'table-cell')); ?>
            <?php $row .= $this->Html->tag('span', date('m-d-y', strtotime($watch['Watch']['created'])), array('class' => 'table-cell')); ?>
            <?php $row .= $this->Html->tag('span', date('m-d-y', strtotime($watch['Watch']['modified'])), array('class' => 'table-cell')); ?>
            <?php echo $this->Html->link($row, array('action' => 'admin_view', $watch['Watch']['id'], 'admin' => true), array('class' => 'table-row', 'escape' => false)); ?>
        <?php endforeach; ?>
    </div>
    <?php echo $this->Form->end(); ?>
<?php $this->Paginator->options(array('url' => $this->params->query,
    'convertKeys' => array('id', 'sold', 'active'))); ?>
    <?php echo $this->Element('paginator'); ?>
</div>
