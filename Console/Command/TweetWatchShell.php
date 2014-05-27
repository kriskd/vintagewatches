<?php
App::uses('HttpSocket', 'Network/Http');
App::uses('HttpSocketOauth', 'Vendor');

class TweetWatchShell extends AppShell
{
    public $uses = array('Watch');

    protected $HttpSocket;
    protected $Http;

    public function __construct()
    {
        parent::__construct();
        $this->HttpSocket = new HttpSocket();
        $this->Http = new HttpSocketOauth();
    }

    public function tweet()
    { 
        $watch = $this->Watch->find('first', array(
            'conditions' => array(
                'active' => 1,
                'tweeted_at < DATE_SUB(NOW(), INTERVAL 7 DAY)',
            ),
            'order' => 'tweeted_at, Watch.id',
            'fields' => array(
                'Watch.id', 'price', 'name'
            ),
            'contain' => array(
                'Image' => array(
                    'fields' => array(
                        'filenameMedium'
                    ),
                    'order' => array(
                        'primary DESC'
                    ), 
                ), 
                'Brand' => array(
                    'fields' => array(
                        'name'
                    )
                ),
            ), 
        ));
        //var_dump($watch);
        $tweet = strtoupper($watch['Brand']['name']) . ' - ' . $watch['Watch']['name'] . ' $' . $watch['Watch']['price'] . ' http://brucesvintagewatches.com/watches/view/' . $watch['Watch']['id'];
        //var_dump($tweet);
        $request = array(
            'method' => 'POST',
            'header' => array(
                'Content-Type' => 'multipart/form-data',
            ),
            'uri' => array(
                'scheme' => 'https',
                'host' => 'api.twitter.com',
                'path' => '1.1/statuses/update_with_media.json',
            ),
            'auth' => array(
                  'method' => 'OAuth',
                  'oauth_token' => Configure::read('Twitter.access_token'),
                  'oauth_token_secret' => Configure::read('Twitter.access_token_secret'),
                  'oauth_consumer_key' => Configure::read('Twitter.api_key'),
                  'oauth_consumer_secret' => Configure::read('Twitter.api_secret')
                  ),
            'data' => array(
                'media[]' => WWW_ROOT . $watch['Image'][0]['filenameMedium'] 
            ),
            'body' => array(
                'status' => $tweet 
            ),
        );

        $response = $this->Http->request($request);
        if ($response->headers['status'] == '200 OK') {
            $this->Watch->id = $watch['Watch']['id'];
            $this->Watch->save(array('tweeted_at' => date('Y-m-d H:i:s')));
        }
    }
}
