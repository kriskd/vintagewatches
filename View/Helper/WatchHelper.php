<?php
class WatchHelper extends AppHelper
{
    public $helpers = array('Html', 'Form');

    /**
     * $storeOpen bool for store opened or closed
     */
    public function closeOpenStore($storeOpen)
    {
        if ($storeOpen === true) {
            return $this->Html->link('Close Store', array('action' => 'close'), array('class' => 'btn btn-danger'));
        }
        return $this->Html->link('Open Store', array('action' => 'open'), array('class' => 'btn btn-success'));
    }

    /*
     * Active checkbox on watch add and edit or message if watch is sold
     */
    public function active($watch = null)
    {
        $options = array('label' => array('class' => 'control-label'),
							 'div' => "checkbox-inline",
                                                         );
        if (empty($watch)) {
            $options['checked'] = true;
        }

        if (!empty($watch['Watch']['order_id'])) {
            return $this->Html->tag('span', 'This watch is associated with an order and can not be activated.', array('class' => 'label label-danger'));
        }

        return $this->Form->input('active', $options);
    }

    public function date($date) {
        return empty($date) ? '' : date_create($date)->format('F j, Y');
    }
}
