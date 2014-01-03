<div class="contacts index">
	<h2><?php echo __('Contacts'); ?></h2>
	<div class="table">
        <div class="table-row row">
            <span class="table-head col-lg-3"><?php echo $this->Paginator->sort('name'); ?></span>
            <span class="table-head col-lg-3"><?php echo $this->Paginator->sort('email'); ?></span>
            <span class="table-head col-lg-3"><?php echo $this->Paginator->sort('created'); ?></span>
            <span class="table-head col-lg-3">Delete</span>
        </div>
        <?php foreach($contacts as $contact): ?>
            <?php $row1 = $this->Html->tag('span', $contact['Contact']['name'], array('class' => 'table-cell col-lg-3')); ?>
            <?php $row1 .= $this->Html->tag('span', $contact['Contact']['email'], array('class' => 'table-cell col-lg-3')); ?>
            <?php $row1 .= $this->Html->tag('span', $contact['Contact']['created'], array('class' => 'table-cell col-lg-3 text-center')); ?>
            <?php $row1 .= $this->Html->tag('span', $this->Form->button('Delete', array('data-query' => http_build_query($this->request->query), 'data-contactid' => $contact['Contact']['id'], 'data-contactname' => $contact['Contact']['name'], 'class' => 'btn btn-danger delete-contact')),
                                            array('class' => 'table-cell text-center col-lg-3', 'escape' => false)); ?>
            <?php $row1 = $this->Html->tag('div', $row1, array('class' => 'table-row row contact-info')); ?>
            <?php $row2 = $this->Html->tag('span', '', array('class' => 'table-cell col-lg-1')); ?>
            <?php $row2 .= $this->Html->tag('span', $contact['Contact']['comment'], array('class' => 'table-cell col-lg-11')); ?>
            <?php $row2 = $this->Html->tag('div', $row2, array('class' => 'table-row row')); ?>
            <?php echo $row1 . $row2; ?>
        <?php endforeach; ?>
    </div>
	<?php echo $this->Element('paginator'); ?>
</div>
