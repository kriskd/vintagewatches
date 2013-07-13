<div class="navbar">
    <div class="navbar-inner">
        <ul class="nav">
            <li class="<?php echo strcasecmp($controller->name, 'cart')==0 ? 'active' : '' ?>">
                <?php echo $this->Html->cartLink('Checkout <i class="icon-shopping-cart icon-large"></i> ' . $this->Cart->cartCount('Items in Your Cart: ', '', $controller),
                                                 array('controller' => 'orders', 'action' => 'index'),
                                                 array('escape' => false),
                                                 false,
                                                 $controller); ?>
            </li>
            <?php //Wrapped in <li> in Component ?>
            <?php echo $this->Html->navLink('<i class="icon-tags icon-large"></i> Go Back to Store',
                                         array('controller' => 'watches', 'action' => 'index'),
                                         array('escape' => false),
                                         false,
                                         $controller); ?>
            <?php echo $this->Html->adminLink('<i class="icon-pushpin icon-large"></i> Orders',
                                              array('controller' => 'orders', 'action' => 'admin_index'),
                                              array('escape' => false)); ?>
            <?php echo $this->Html->adminLink('<i class="icon-cogs icon-large"></i> Watches',
                                              array('controller' => 'watches', 'action' => 'admin_index'),
                                              array('escape' => false)); ?>
        </ul>
    </div>
</div><?php  ?>