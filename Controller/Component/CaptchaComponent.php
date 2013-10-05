<?php
class CaptchaComponent extends Component
{
    public $components = array('Session');
    
    public function initialize(Controller $controller) {
        $this->controller = $controller;
    }
    
    public function makeCaptcha()
    {
        $values = array('umbrella','key','clock','television','leaf');

        $imageKey = mt_rand(0,(sizeof($values)-1));
        shuffle($values);
        $selectedItem = $values[$imageKey];

        for($i=0; $i<sizeof($values); $i++) {
            $rand = mt_rand();
            $items[$rand] = $values[$i];
            if ($i == $imageKey) {
                $answer = $rand;
            }
        }

        $this->Session->write('Captcha.answer', $answer);

        return array($items, $selectedItem);
    }
    
    public function verify()
    {
        $data = $this->controller->request->data;
        $model = $this->controller->modelClass;
        $captcha = $data[$model]['s3captcha'];
        $correctAnswer = $this->Session->read('Captcha.answer'); 
        if (empty($correctAnswer) || empty($captcha)) { 
            return false;
        }
        
        if ($captcha == $correctAnswer) {
            return true;
        } 
        return false;
    }
}