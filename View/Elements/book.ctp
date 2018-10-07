<?php if ($itemActive): ?>
    <hr />
    <div class="book">
        <?= $this->Html->image('/items/2/coverartsample-1-thumb.jpg', [
            'url' => [
                'controller' => 'items',
                'action' => 'add', 2,
            ],
            'alt' => 'Elgin Wristwatches: A Collector\'s Guide',
        ]); ?>
        <p>
            Just off the presses: Elgin Wristwatches: A Collector's Guide. First reference devoted exclusively to Elgin, with nearly 900 models from 1932 to 1962 identified. Get your copy today.
        </p>
            <?= $this->Html->link('Order the book now!', [
                'controller' => 'items',
                'action' => 'add', 2,
            ], [
                'class' => 'btn btn-gold',
            ]); ?>
    </div>
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
