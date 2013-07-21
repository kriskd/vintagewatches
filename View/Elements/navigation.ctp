<ul class="nav nav-tabs">
    <?php if($this->params->prefix == 'admin'): ?>
        <li class="dropdown <?php echo $this->params->prefix == 'admin' ? 'active' : ''; ?>">
            <?php echo $this->Html->link('<i class="icon-wrench icon-large"></i> Admin<b class="caret"></b>',
                                         array('controller' => 'orders', 'action' => 'index', 'admin' => true),
                                         array('data-toggle' => 'dropdown', 'escape' => false)); ?>
            <ul class="dropdown-menu">
                <?php echo $this->Html->adminLink('<i class="icon-pushpin icon-large"></i> Orders',
                                                  array('controller' => 'orders', 'action' => 'admin_index'),
                                                  array('escape' => false),
                                                  false,
                                                  $controller); ?>
                <?php echo $this->Html->adminLink('<i class="icon-cogs icon-large"></i> Watches',
                                                  array('controller' => 'watches', 'action' => 'admin_index'),
                                                  array('escape' => false),
                                                  false,
                                                  $controller); ?>
                <li><?php echo $this->Html->link('<i class="icon-off icon-large"></i> Logout',
                                                 array('controller' => 'users', 'action' => 'logout', 'admin' => false),
                                                 array('escape' => false)
                                                 ); ?></li>
            </ul>
        </li>
    <?php endif; ?>
    <li class="<?php echo strcasecmp($controller->name, 'orders')==0 && $this->params->prefix != 'admin' ? 'active' : '' ?>">
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

</ul>