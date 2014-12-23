<div class="blogs view">
    <h2><?php echo $blog['Blog']['title']; ?></h2>
    <div class="row">
        <div class="col-lg-10">
            <h5><?php echo date('F j, Y g:i A', strtotime($blog['Blog']['published'])); ?></h5>
            <?php echo $blog['Blog']['content']; ?>
        </div>
        <div class="col-lg-2">
            
            <?php foreach ($blogIndex as $year => $months): ?>
                <h5><?php echo $year; ?> (<?php echo array_sum($months); ?>)</h5>
                <?php foreach($months as $month => $count): ?>
                    <p><?php echo date('F', mktime(0, 0, 0, $month, 10)); ?> (<?php echo $count; ?>)</p>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </div>
    </div>
</div>
