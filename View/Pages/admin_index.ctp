<div class="pages admin-index">
    <h1>Pages Admin</h1>
    <?php echo $this->Html->link('Add a Page', array('action' => 'add', 'admin' => 'true'),
                                                array('class' => 'btn btn-primary')); ?>
    <table>
        <tr>
            <th>Name</th>
            <th>Slug</th>
            <th>Created</th>
            <th>Modified</th>
            <th>Edit</th>
        </tr>
        <?php foreach ($pages as $page): ?>
            <tr>
                <td>
                    <?php echo $page['Page']['name']; ?>                    
                </td>
                <td>
                    <?php echo $page['Page']['slug']; ?>
                </td>
                <td>
                    <?php echo $page['Page']['created']; ?>
                </td>
                <td>
                    <?php echo $page['Page']['modified']; ?>
                </td>
                <td>
                    <?php echo $this->Html->link('Edit', array('action' => 'edit', $page['Page']['id'],
                                                               'admin' => true
                                                               ),
                                                        array('class' => 'btn btn-success')
                                                 ); ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>