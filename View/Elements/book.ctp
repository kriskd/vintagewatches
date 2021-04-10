<?php if (!empty($items)): ?>
    <?php foreach ($items as $item): ?>
        <hr />
        <div class="book">
            <?php if (!empty($item['Item']['filenameThumb'])): ?>
                <?= $this->Html->image($item['Item']['filenameThumb'], [
                    'url' => [
                        'controller' => 'items',
                        'action' => 'add', $item['Item']['id'],
                    ],
                    'alt' => $item['Item']['name'],
                ]); ?>
            <?php endif ?>
            <p>
                <?= $item['Item']['summary'] ?>
            </p>
                <?= $this->Html->link($item['Item']['quantity'] > 0 ? 'Order the book now!' : 'More Information', [
                    'controller' => 'items',
                    'action' => 'add', $item['Item']['id'],
                ], [
                    'class' => 'btn btn-gold',
                ]); ?>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
