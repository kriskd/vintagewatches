<div class="ebays admin-index">
    <h1>Recent Auctions</h1>
    <?php echo $this->Form->create('Invoice', array('type' => 'get', 'url' => array('controller' => 'invoices', 'action' => 'ebay', 'admin' => true))); ?>
    <?php echo $this->Form->input('ebayItemId', array('value' => '', 'type' => 'hidden')); ?>
    <div class="table">
        <div class="table-row">
            <span class="table-head">Sold</span>
            <span class="table-head">Invoiced</span>
            <span class="table-head">Item ID</span>
            <span class="table-head">Title</span>
            <span class="table-head">Start Time</span>
            <span class="table-head">End Time</span>
        </div>
        <?php foreach($items as $item): ?>
            <?php $sold = $item->SellingStatus->QuantitySold > 0 ? true : false; ?>
            <?php $link = $this->Html->link('Create Invoice', array(
                                                    'controller' => 'invoices',
                                                    'action' => 'ebay', (string)$item->ItemID,
                                                    'admin' => true), array('escape' => false)); ?>
            <?php //echo $link; ?>
            <?php $row = $this->Html->tag('span', $sold==true ? '<span class="glyphicon glyphicon-ok green"></span>' : '', array('class' => 'table-cell text-center')); ?>
            <?php $row .= $this->Html->tag('span', ($item->Invoiced==1 ? '<span class="glyphicon glyphicon-ok green"></span>' :
                                           ($item->AllowInvoice == 1 ? $this->Form->button('Invoice', array('data-ebayid' => (string)$item->ItemID, 'class' => 'btn btn-primary create-ebay-invoice')) : '')),
                                           array('class' => 'table-cell text-center', 'escape' => false)); ?>
            <?php $row .= $this->Html->tag('span', (string)$item->ItemID, array('class' => 'table-cell')); ?>
            <?php $row .= $this->Html->tag('span', $this->Text->truncate((string)$item->Title, 25), array('class' => 'table-cell')); ?>
            <?php $row .= $this->Html->tag('span', date('Y-m-d H:i:s', strtotime((string)$item->ListingDetails->StartTime)), array('class' => 'table-cell')); ?>
            <?php $row .= $this->Html->tag('span', date('Y-m-d H:i:s', strtotime((string)$item->ListingDetails->EndTime)), array('class' => 'table-cell')); ?>
            <?php echo $this->Html->link($row, array('action' => 'admin_view', (string)$item->ItemID, 'admin' => true), array('class' => 'table-row', 'escape' => false)); ?>
        <?php endforeach; ?>
    </div>
    <?php echo $this->Form->end(); ?>
</div>