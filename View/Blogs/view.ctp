<div class="blogs view">
    <h2><?php echo $blog['Blog']['title']; ?></h2>
    <div class="row">
        <div class="col-lg-10 col-md-8 col-sm-8 col-xs-12">
            <h5><?php echo date('F j, Y g:i A', strtotime($blog['Blog']['published'])); ?></h5>
            <?php echo $blog['Blog']['content']; ?>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-4 col-xs-12">
            <h5>Blog Archive</h5>
            <?php foreach ($blogIndex as $year => $months): ?>
                <h5><?php echo $year; ?> (<?php echo $this->Blog->blogCount($months); ?>)</h5>
                <?php foreach($months as $month => $items): ?>
                    <p><?php echo date('F', mktime(0, 0, 0, $month, 10)); ?> (<?php echo count($items); ?>)</p>
                    <?php foreach ($items as $id => $item): ?>
                        <h6><?php echo $this->Html->link($item, array(
                            'action' => 'view', $id 
                        )); ?></h6>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </div>
    </div>
</div>
