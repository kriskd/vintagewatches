<?php
App::uses('FakerTestFixture', 'CakeFaker.Lib');

class AddressFixture extends FakerTestFixture {
    protected $model_name = 'Address', $num_records = 5;
    public $fields = array(
        'id' => array('type' => 'integer', 'key' => 'primary'),
        'class' => array(
            'type' => 'string',
            'length' => 30,
            'null' => false
        ),
        'foreign_id' => array(
            'type' => 'integer',
            'null' => false,
        ),
        'type' => array(
            'type' => 'string',
            'length' => 30,
            'null' => false
        ),
        'firstName' => array(
            'type' => 'string',
            'length' => 50,
            'null' => false
        ),
        'lastName' => array(
            'type' => 'string',
            'length' => 50,
            'null' => false
        ),
        'company' => array(
            'type' => 'string',
            'length' => 200,
        ),
        'address1' => array(
            'type' => 'string',
            'length' => 100,
            'null' => false
        ),
        'address2' => array(
            'type' => 'string',
            'length' => 100,
        ),
        'city' => array(
            'type' => 'string',
            'length' => 50,
            'null' => false
        ),
        'state' => array(
            'type' => 'string',
            'length' => 2,
            'null' => false
        ),
        'postalCode' => array(
            'type' => 'string',
            'length' => 20,
            'null' => false
        ),
        'country' => array(
            'type' => 'string',
            'length' => 2,
            'null' => false
        ),
    );
    protected function alterFields($generator) {
        return array(
            'city' => function() use ($generator) { return $generator->city; },
            'state' => function() use ($generator) { return $generator->stateAbbr; },
            'postalCode' => function() use ($generator) { return $generator->postcode; },
            'country' => function() use ($generator) { return $generator->countryCode; },
        );
    }
}
