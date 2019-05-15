<div class="items admin-index">
    <div class="row">
        <div class="col-lg-2 col-lg-push-10 col-md-2 col-md-push-10 col-sm-2 col-sm-push-10 col-xs-12">
            <?php echo $this->Html->link('Add Item', array(
                                                        'action' => 'add', 'admin' => 'true'
                                                    ),
                                                        array(
                                                        'class' => 'btn btn-primary admin-add'
                                                    )
                                        ); ?>
        </div>
        <div class="col-lg-10 col-lg-pull-2 col-md-10 col-md-pull-2 col-sm-10 col-sm-pull-2 col-xs-12">
            <h1><?php echo __('Items'); ?></h1>
        </div>
    </div>
    <div class="table">
        <div class="table-row">
			<span class="table-head"><?php echo $this->Paginator->sort('active'); ?></span>
			<span class="table-head"><?php echo $this->Paginator->sort('name'); ?></span>
			<span class="table-head"><?php echo $this->Paginator->sort('sequence'); ?></span>
			<span class="table-head"><?php echo $this->Paginator->sort('quantity'); ?></span>
			<span class="table-head"><?php echo $this->Paginator->sort('price'); ?></span>
        </div>
        <?php foreach ($items as $item): ?>
            <?php $row = $this->Html->tag('span', $item['Item']['active']==true ? '<span class="glyphicon glyphicon-ok green"></span>' : '', array('class' => 'table-cell text-center')); ?>
            <?php $row .= $this->Html->tag('span', h($item['Item']['name']), array('class' => 'table-cell')); ?>
            <?php $row .= $this->Html->tag('span', h($item['Item']['sequence']), array('class' => 'table-cell')); ?>
            <?php $row .= $this->Html->tag('span', h($item['Item']['quantity']), array('class' => 'table-cell')); ?>
            <?php $row .= $this->Html->tag('span', h($item['Item']['price']), array('class' => 'table-cell')); ?>
            <?php echo $this->Html->link($row, array('action' => 'admin_edit', $item['Item']['id'], 'admin' => true), array('class' => 'table-row', 'escape' => false)); ?>
        <?php endforeach; ?>
    </div>
    <?php echo $this->Element('paginator'); ?>
</div>
