<?php

class MyTestFixture extends CakeTestFixture {

  /**
   * Maps enum fields in the database to strings with a length of 64
   */
    function create($db) {
        foreach($this->fields as $name => &$field) {
            if (isset($field['type']) && strstr($field['type'], "enum") !== false) {
                $field['type'] = 'string';
                $field['length'] = 64;
            }
        }
        parent::create($db);
    }
}

