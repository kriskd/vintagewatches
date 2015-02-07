<?php

class PageFixture extends CakeTestFixture {
    public $useDbConfig = 'test';
    public $import = array(
        'model' => 'Page',
        'records' => true,
    );
}
