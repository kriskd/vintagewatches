<?php if ($itemActive): ?>
    <hr />
    <div class="book">
        <?= $this->Html->image('/items/1/bookcoverwithborder-2-thumb.jpg', [
            'url' => [
                'controller' => 'items',
                'action' => 'add', 1,
            ],
            'alt' => 'Hamilton Wristwatches: A Reference Guide',
        ]); ?>
        <p>
            New Hamilton wristwatch book draws praise from collectors worldwide. Get your copy today.
        </p>
            <?= $this->Html->link('Order the book now!', [
                'controller' => 'items',
                'action' => 'add', 1,
            ], [
                'class' => 'btn btn-gold',
            ]); ?>
    </div>
<?php endif; ?>
