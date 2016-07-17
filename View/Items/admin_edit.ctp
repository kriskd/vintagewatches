<div class="items form">
    <?= $this->Html->link('View Item', [
        'action' => 'add', $this->request->data['Item']['id'],
        'admin' => false,
    ], [
        'class' => 'btn btn-primary',
    ]); ?>
    <?= $this->Element('form_item', ['action' => 'Edit']); ?>
</div>
