<div class="shippings admin-index">
    <div class="row">
        <div class="col-lg-2 col-lg-push-10 col-md-2 col-md-push-10 col-sm-2 col-sm-push-10 col-xs-12">
            <?php echo $this->Html->link('Add Shipping Option', array(
                                                        'action' => 'add', 'admin' => 'true'
                                                    ),
                                                        array(
                                                        'class' => 'btn btn-primary admin-add'
                                                    )
                                        ); ?>
        </div>
        <div class="col-lg-10 col-lg-pull-2 col-md-10 col-md-pull-2 col-sm-10 col-sm-pull-2 col-xs-12">
            <h1><?php echo __('Shipping Options'); ?></h1>
        </div>
    </div>
    <div class="table">
        <div class="table-row">
			<span class="table-head"><?php echo $this->Paginator->sort('description'); ?></span>
			<span class="table-head"><?php echo $this->Paginator->sort('Zone.name', 'Zones'); ?></span>
			<span class="table-head"><?php echo $this->Paginator->sort('amount'); ?></span>
        </div>
        <?php foreach ($shippings as $shipping): ?>
            <?php $row = $this->Html->tag('span', h($shipping['Shipping']['description']), array('class' => 'table-cell')); ?>
            <?php $row .= $this->Html->tag('span', $this->Shipping->getZones($shipping), array('class' => 'table-cell')); ?>
            <?php $row .= $this->Html->tag('span', h($shipping['Shipping']['amount']), array('class' => 'table-cell')); ?>
            <?php //$this->Form->postLink(__('Delete'), array('action' => 'delete', $shipping['Shipping']['id']), array(), __('Are you sure you want to delete # %s?', $shipping['Shipping']['id'])); ?>
            <?php echo $this->Html->link($row, array('action' => 'admin_edit', $shipping['Shipping']['id'], 'admin' => true), array('class' => 'table-row', 'escape' => false)); ?>
        <?php endforeach; ?>
    </div>
    <?php echo $this->Element('paginator'); ?>
</div>
