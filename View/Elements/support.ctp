<?php if (!(empty($navigation))): ?>
                            <?php foreach ($navigation as $slug => $name): ?>
                                <li><?php echo $this->Html->link($name, array('controller' => 'pages',
                                                                              'action' => 'display', $slug,
                                                                              'admin' => false)); ?></li>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        <li><?php echo $this->Html->link('Order History', array('controller' => 'orders', 'action' => 'index', 'admin' => false)); ?></li>
                        <li><?php echo $this->Html->link('Contact Me', array('controller' => 'contacts', 'action' => 'index', 'admin' => false)); ?></li>