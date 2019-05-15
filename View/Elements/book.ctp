<?php if (!empty($items)): ?>
    <?php foreach ($items as $item): ?>
        <hr />
        <div class="book">
            <?= $this->Html->image($item['Item']['filenameThumb'], [
                'url' => [
                    'controller' => 'items',
                    'action' => 'add', $item['Item']['id'],
                ],
                'alt' => $item['Item']['name'],
            ]); ?>
            <p>
                <?= $item['Item']['summary'] ?>
            </p>
                <?= $this->Html->link('Order the book now!', [
                    'controller' => 'items',
                    'action' => 'add', $item['Item']['id'],
                ], [
                    'class' => 'btn btn-gold',
                ]); ?>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
