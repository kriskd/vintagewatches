<div class="navbar">
    <div class="navbar-inner">
        <ul class="nav">
            <li>
                <?php echo $this->Html->cartLink('<i class="icon-shopping-cart icon-large"></i> ' . $this->Cart->cartCount('Items in Your Cart: ', '', $controller),
                                                 array('controller' => 'cart', 'action' => 'index'),
                                                 array('escape' => false),
                                                 false,
                                                 $controller); ?>
            </li>
            <li>
                <?php echo $this->Html->link('<i class="icon-tags icon-large"></i> Go Back to Store',
                                             array('controller' => 'watches', 'action' => 'index'),
                                             array('escape' => false)); ?>
            </li>
        </ul>
    </div>
</div>