<?php
App::uses('AppController', 'Controller');

class CountriesController extends AppController {

    public function getCountries() {
        //Handle ajax request for autocomplete
        if($this->request->is('ajax')){
            $query = $this->request->query; 
            $search = $query['term'];
            if(!$search){ 
                throw new NotFoundException('Search term required');
            }

            $filtered = array();
            $countries = $this->Country->getList();
            foreach($countries as $key => $country){
                if(stripos($country, htmlentities($search)) !== false){
                    $filtered[] = array('id' => $key, 'value' => html_entity_decode($country, ENT_COMPAT, 'UTF-8'));
                }
            }

            $this->set(compact('filtered'));
            $this->layout = 'ajax';
        }
    }
}
