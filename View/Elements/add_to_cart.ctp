<?php if($this->Cart->inCart($watch['Watch']['id'], $controller)): ?>
    <span class="label label-warning">This item is in your cart</span>
<?php else: ?>
    <?php echo $this->Html->link('Add to Cart',
                                 array('controller' => 'orders', 'action' => 'add', $watch['Watch']['id']),
                                 array('class' => $class)); ?>
<?php endif; ?>