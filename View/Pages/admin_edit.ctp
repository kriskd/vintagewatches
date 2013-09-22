<div class="pages admin-edit">
    <h1><?php echo $page['Page']['name']; ?></h1>
    <?php $contents = $page['Content']; ?>
    <?php echo $this->Element('form_page', compact('contents')); ?>
</div>