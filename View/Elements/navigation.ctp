<nav class="navbar navbar-fixed-top navbar-inverse" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <?php $toggleButton = $this->Html->tag('span', 'Toggle Navigation', array('class' => 'sr-only')); ?>
            <?php $toggleButton .= $this->Html->tag('span', '', array('class' => 'icon-bar')); ?>
            <?php $toggleButton .= $this->Html->tag('span', '', array('class' => 'icon-bar')); ?>
            <?php $toggleButton .= $this->Html->tag('span', '', array('class' => 'icon-bar')); ?>
            <?php echo $this->Form->button($toggleButton, array('type' => 'button',
                                                     'class' => 'navbar-toggle',
                                                     'data-toggle' => 'collapse',
                                                     'data-target' => '.navbar-ex1-collapse'
                                                     )
                                            ); ?>
            <?php echo $this->Html->link('Bruce\'s Vintage Watches', '/', array('class' => 'navbar-brand')); ?>
            <?php echo $this->Html->image('credit_card_logos_43.gif', array('class' => 'credit-card-logos')); ?>
        </div>
        <div class="collapse navbar-collapse navbar-ex1-collapse">
            <ul class="nav navbar-nav">
                <?php if($loggedIn == true): ?>
                    <li class="dropdown <?php echo $this->params->prefix == 'admin' ? 'active' : ''; ?>">
                        <?php echo $this->Html->link('<i class="icon-wrench icon-large"></i> Admin<span class="caret"></span>',
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
                            <?php echo $this->Html->adminLink('<i class="glyphicon glyphicon-book"></i> Pages',
                                                              array('controller' => 'pages', 'action' => 'admin_index'),
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
                <?php //Wrapped in <li> in Component ?>
                <?php echo $this->Html->navLink('<i class="glyphicon glyphicon-tags"></i>&nbsp;&nbsp;Go to the Store',
                                             array('controller' => 'watches', 'action' => 'index'),
                                             array('escape' => false),
                                             false); ?>
                <li class="<?php echo strcasecmp($controller->name, 'orders')==0 && $this->params->prefix != 'admin' ? 'active' : '' ?>">
                    <?php echo $this->Html->cartLink('Checkout <i class="glyphicon glyphicon-shopping-cart"></i> ' . $this->Cart->cartCount('Items in Your Cart: ', '', $controller),
                                                     array('controller' => 'orders', 'action' => 'index'),
                                                     array('escape' => false),
                                                     false,
                                                     $controller); ?>
                </li>
    
                <li class="dropdown">
                    <?php echo $this->Html->link('About Us<span class="caret"></span>', '#',
                                                 array('class' => 'dropdown-toggle',
                                                        'data-toggle' => 'dropdown',
                                                        'escape' => false)
                                                ); ?>
                    <ul class="dropdown-menu">
                        <?php $navigation = $this->Navigation->getNavigation(); ?>
                        <?php if (!(empty($navigation))): ?>
                            <?php foreach ($navigation as $slug => $name): ?>
                                <li><?php echo $this->Html->link($name, array('controller' => 'pages',
                                                                              'action' => 'display', $slug,
                                                                              'admin' => false)); ?></li>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        <li><?php echo $this->Html->link('Contact Me', '#'); ?></li>
                    </ul>
                </li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li>
                    <?php echo $this->Html->image('credit_card_logos_43.gif'); ?>
                </li>
            </ul>
        </div>
    </div>
</nav>