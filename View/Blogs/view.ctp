<h2><?php echo $blog['Blog']['title']; ?></h2>
<h4><?php echo date('F j, Y g:i A', strtotime($blog['Blog']['published'])); ?></h4>
<?php echo $blog['Blog']['content']; ?>
