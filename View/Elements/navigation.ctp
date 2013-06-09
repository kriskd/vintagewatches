<div class="navbar">
    <div class="navbar-inner">
        <ul class="nav">
            <li>
                <?php echo $this->Html->link('Store', array('controller' => 'watches', 'action' => 'index')); ?>
            </li>
            <li>
                <?php echo $this->Html->cartLink('<i class="icon-shopping-cart"></i> ' . $this->Cart->cartCount('(', ')'), array('controller' => 'cart', 'action' => 'index'), array('escape' => false)); ?>
            </li>
        </ul>
    </div>
</div>