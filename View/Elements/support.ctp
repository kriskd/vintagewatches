<?php if (!(empty($navigation))): ?>
    <?php foreach ($navigation as $nav): ?>
        <li><?php echo $this->Html->link($nav['Page']['name'], array('controller' => 'pages',
                                                      'action' => 'display', $nav['Page']['slug'],
                                                      'admin' => false)); ?></li>
    <?php endforeach; ?>
<?php endif; ?>
<li><?= $this->Html->link('Sold Watches', ['controller' => 'watches', 'action' => 'sold', 'admin' => false]) ?>
<li><?php echo $this->Html->link('Blog', '/blog'); ?></li>
<li><?php echo $this->Html->link('Order History', array('controller' => 'orders', 'action' => 'index', 'admin' => false)); ?></li>
<li><?php echo $this->Html->link('Contact Me', array('controller' => 'contacts', 'action' => 'index', 'admin' => false)); ?></li>
