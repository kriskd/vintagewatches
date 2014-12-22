<h1><?php //echo $blogs['Blog']['title']; ?></h1>
<?php foreach ($blogs as $blog): ?>
    <h2><?php echo $blog['Blog']['title']; ?></h2>
    <h4><?php echo date('F j, Y g:i A', strtotime($blog['Blog']['published'])); ?></h4>
    <?php //echo $entry['content']; ?>
<?php endforeach; ?>
<div class="row">
    <?php echo $this->Element('paginator'); ?>
</div>
