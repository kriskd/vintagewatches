<div class="ebays admin-items">
    <div class="table">
        <div class="table-row">
            <span class="table-head">Item ID</span>
            <span class="table-head">Start Time</span>
            <span class="table-head">End Time</span>
        </div>
        <?php foreach($items as $item): ?>
            <?php $row = $this->Html->tag('span', (string)$item->ItemID, array('class' => 'table-cell')); ?>
            <?php $row .= $this->Html->tag('span', date('Y-m-d H:i:s', strtotime((string)$item->ListingDetails->StartTime)), array('class' => 'table-cell')); ?>
            <?php $row .= $this->Html->tag('span', date('Y-m-d H:i:s', strtotime((string)$item->ListingDetails->EndTime)), array('class' => 'table-cell')); ?>
            <?php echo $this->Html->tag('div', $row, array('class' => 'table-row')); ?>
        <?php endforeach; ?>
    </div>
</div>