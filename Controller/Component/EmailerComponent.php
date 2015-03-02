<?php
App::uses('Component', 'Controller');
App::uses('CakeEmail', 'Network/Email');

class EmailerComponent extends Component
{
    public $Controller;

    public function initialize(Controller $controller) {
        $this->Controller = $controller;
    }
    
    public function order($order = null) {
        $url = $this->Controller->referer(null, true);
        $route = Router::parse($url);
        $action = $route['action'];

        if (strcasecmp($action, 'checkout')==0) {
            $Email = new CakeEmail('smtp');
            $Email->template('order_received', 'default')
                ->emailFormat('html')
                ->to(Configure::read('ordersEmail'))
                ->from(Configure::read('fromEmail'))
                ->subject('Order No. ' . $order['Order']['id'])
                ->viewVars(array('order' => $order))
                ->helpers(array('Html' => array('className' => 'MyHtml'),
                    'Number' => array('className' => 'MyNumber')))
                    ->send();
        }

        if (empty($order['Order']['shipDate'])) {
            $subject = 'Thank you for your order from Bruce\'s Vintage Watches';
        } else {
            $subject = 'Your order from Bruce\'s Vintage Watches was shipped on ' . date('F j, Y', strtotime($order['Order']['shipDate']));
        }

        $Email = new CakeEmail('smtp');
        return $Email->template('order_received', 'default')
            ->emailFormat('html')
            ->to($order['Order']['email'])
            ->from(Configure::read('fromEmail'))
            ->subject($subject)
            ->viewVars(array('order' => $order))
            ->helpers(array('Html' => array('className' => 'MyHtml'),
                'Number' => array('className' => 'MyNumber')))
                ->send();
    }

}
