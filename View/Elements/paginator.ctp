<?php /*
<p>
    <?php
    echo $this->Paginator->counter(array(
    'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
    ));
    ?>
</p>
*/ ?>
<ul class="pagination">
    <?php
        echo $this->Paginator->prev(html_entity_decode('&laquo;'), array('tag' => 'li'), null, array('class' => 'prev disabled', 'escape' => false));
        echo $this->Paginator->numbers(array(
                                             'tag' => 'li',
                                             'separator' => '',
                                             'currentClass' => 'active',
                                             'currentTag' => 'a'
                                             )
                                       );
        echo $this->Paginator->next(html_entity_decode('&raquo;'), array('tag' => 'li'), null, array('class' => 'next disabled', 'escape' => false));
    ?>
</ul>