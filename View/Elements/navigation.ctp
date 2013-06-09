<div class="navbar">
    <div class="navbar-inner">
        <ul class="nav">
            <li>
                <?php echo $this->Html->link('Store', array('controller' => 'watches', 'action' => 'index')); ?>
            </li>
            <?php //This logic is repeated in CartContoller, needs to be in one location to be shared ?>
            <?php if($this->Session->check('Cart.items') == true): ?>
                <?php $items = $this->Session->read('Cart.items'); ?>
                <?php if(!empty($items)): ?>
                    <li>
                        <?php echo $this->Html->link('Cart', array('controller' => 'cart', 'action' => 'index')); ?>
                    </li>
                <?php endif; ?>
            <?php endif; ?>
        </ul>
    </div>
</div>