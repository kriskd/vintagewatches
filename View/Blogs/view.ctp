<h2><?php echo $blog['Blog']['title']; ?></h2>
<div class="row">
    <div class="col-lg-8">
        <h4><?php echo date('F j, Y g:i A', strtotime($blog['Blog']['published'])); ?></h4>
        <?php echo $blog['Blog']['content']; ?>
    </div>
    <div class="col-lg-4">
        <?php foreach ($blogIndex as $year => $months): ?>
            <h5><?php echo $year; ?></h5>
            <?php foreach($months as $month => $count): ?>
                <p><?php echo date('F', mktime(0, 0, 0, $month, 10)); ?> (<?php echo $count; ?>)</p>
            <?php endforeach; ?>
        <?php endforeach; ?>
    </div>
</div>
