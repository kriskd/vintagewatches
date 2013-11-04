<div class="Contacts index">
	<h2><?php echo __('Contacts'); ?></h2>
	<div class="table">
        <div class="table-row row">
            <span class="table-head col-lg-2"><?php echo $this->Paginator->sort('name'); ?></span>
            <span class="table-head col-lg-2"><?php echo $this->Paginator->sort('email'); ?></span>
            <span class="table-head col-lg-6"><?php echo $this->Paginator->sort('comment'); ?></span>
            <span class="table-head col-lg-2"><?php echo $this->Paginator->sort('created'); ?></span>
        </div>
        <?php foreach($contacts as $contact): ?>
            <?php $row = $this->Html->tag('span', $contact['Contact']['name'], array('class' => 'table-cell col-lg-2')); ?>
            <?php $row .= $this->Html->tag('span', $contact['Contact']['email'], array('class' => 'table-cell col-lg-2')); ?>
            <?php $row .= $this->Html->tag('span', $contact['Contact']['comment'], array('class' => 'table-cell col-lg-6')); ?>
            <?php $row .= $this->Html->tag('span', $contact['Contact']['created'], array('class' => 'table-cell col-lg-2 text-center')); ?>
            <?php echo $this->Html->tag('div', $row, array('class' => 'table-row row')); ?>
        <?php endforeach; ?>
    </div>
	<?php echo $this->Element('paginator'); ?>
</div>
