<h1><?php echo $xml->title; ?></h1>
<?php foreach ($xml->entry as $entry): ?>
    <h2><?php echo $entry->title; ?></h2>
    <h4><?php echo date('F j, Y g:i A', strtotime($entry->published)); ?></h4>
    <?php echo $entry->content; ?>
<?php endforeach; ?>
