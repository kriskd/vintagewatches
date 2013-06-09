<div class="navbar">
    <div class="navbar-inner">
        <ul class="nav">
            <li>
                <?php echo $this->Html->link('Store', array('controller' => 'watches', 'action' => 'index')); ?>
            </li>
            <li>
                <?php echo $this->Html->cartLink('Cart', array('controller' => 'cart', 'action' => 'index')); ?>
            </li>
        </ul>
    </div>
</div>