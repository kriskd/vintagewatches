<ul class="nav nav-tabs">
    <?php if($loggedIn == true): ?>
        <li class="dropdown <?php echo $this->params->prefix == 'admin' ? 'active' : ''; ?>">
            <?php echo $this->Html->link('<i class="icon-wrench icon-large"></i> Admin<b class="caret"></b>',
                                         array('controller' => 'orders', 'action' => 'index', 'admin' => true),
                                         array('data-toggle' => 'dropdown', 'escape' => false)); ?>
            <ul class="dropdown-menu">
                <?php echo $this->Html->adminLink('<i class="glyphicon glyphicon-usd"></i> Orders',
                                                  array('controller' => 'orders', 'action' => 'admin_index'),
                                                  array('escape' => false),
                                                  false,
                                                  $controller); ?>
                <?php echo $this->Html->adminLink('<i class="glyphicon glyphicon-cog"></i> Watches',
                                                  array('controller' => 'watches', 'action' => 'admin_index'),
                                                  array('escape' => false),
                                                  false,
                                                  $controller); ?>
                <li><?php echo $this->Html->link('<i class="glyphicon glyphicon-off"></i> Logout',
                                                 array('controller' => 'users', 'action' => 'logout', 'admin' => false),
                                                 array('escape' => false)
                                                 ); ?></li>
            </ul>
        </li>
    <?php endif; ?>
    <li class="<?php echo strcasecmp($controller->name, 'orders')==0 && $this->params->prefix != 'admin' ? 'active' : '' ?>">
        <?php echo $this->Html->cartLink('Checkout <i class="glyphicon glyphicon-shopping-cart"></i> ' . $this->Cart->cartCount('Items in Your Cart: ', '', $controller),
                                         array('controller' => 'orders', 'action' => 'index'),
                                         array('escape' => false),
                                         false,
                                         $controller); ?>
    </li>
    <?php //Wrapped in <li> in Component ?>

    <?php echo $this->Html->navLink('<i class="glyphicon glyphicon-tags"></i>&nbsp;&nbsp;Go Back to Store',
                                 array('controller' => 'watches', 'action' => 'index'),
                                 array('escape' => false),
                                 false); ?>

</ul>