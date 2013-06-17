<?php

class CartController extends AppController
{
    public $uses = array('Watch');
        
    public function index()
    {
        if($this->Cart->cartEmpty() == true){
            $this->redirect(array('controller' => 'watches', 'action' => 'index'));
        }
        $items = $this->Session->read('Cart.items'); 
        $watches = $this->Watch->find('all', array('conditions' => array('id' => $items),
                                                   'fields' => array('id', 'stock_id', 'price', 'name')
                                                   )
                                      );
	$total = 0;
        $months = array_combine(range(1,12), range(1,12));
        $year = date('Y'); 
        for($i=date('Y'); $i<=date('Y')+10; $i++){
            $years[$i] = $i;
        }
        
        $this->set(compact('watches', 'months', 'years', 'total'));
    }
    
    public function add($id = null)
    {   
        if (!$this->Watch->exists($id)) {
            throw new NotFoundException(__('Invalid watch'));
        }
        $items = array();
        if($this->Session->check('Cart.items') == true){
            $items = $this->Session->read('Cart.items');
            if(in_array($id, $items)){
                $this->Session->setFlash('That item is already in your cart.');
                $this->redirect(array('controller' => 'watches', 'action' => 'index'));
            }
        }

        $items[] = $id; 
        $this->Session->write('Cart.items', $items);
        
        $this->redirect(array('action' => 'index'));
    }
    
    public function checkout()
    {
        if($this->request->is('post')){
	    if($this->Session->check('Cart.total') == true){
		$amount = $this->Session->read('Cart.total'); 
		if($amount > 0){
		    $stripeToken = $this->request->data['stripeToken'];
		    $data = array('amount' => $amount,
				  'stripeToken' => $stripeToken);
		    $result = $this->Stripe->charge($data);
		    if($result['stripe_paid'] == true){
			$items = $this->Session->read('Cart.items');
			foreach($items as $id){
			    $this->Watch->id = $id;
			    $this->Watch->saveField('active', 0);
			}
			$this->Session->delete('Cart');
			$this->Session->setFlash('Payment Received');
			$this->redirect(array('controller' => 'watches', 'action' => 'index'));
		    }
		}
	    } 
	    $this->Session->setFlash('Please select your country.');
	    $this->redirect(array('action' => 'index')); 
        }
        
    }
    
    public function remove($id = null)
    {
        if (!$this->Watch->exists($id)) {
            throw new NotFoundException(__('Invalid watch'));
        }

        if($this->Session->check('Cart.items') == true){
            $items = $this->Session->read('Cart.items'); 
            if(in_array($id, $items)){
                $key = array_search($id, $items);
                unset($items[$key]); 
                $this->Session->write('Cart.items', $items);
                $this->redirect(array('action' => 'index'));
            }
        }
    }
    
    public function getTotal($country = null)
    {
	$shipping = null;
	if($this->request->is('ajax')){
	    switch($country){
		case 'us':
		    $shipping = '8';
		    break;
		case 'ca':
		    $shipping = '38';
		    break;
		case 'other';
		    $shipping = '45';
		    break;
	    }
	    $items = $this->Session->read('Cart.items'); 
	    $watches = $this->Watch->find('all', array('conditions' => array('id' => $items),
						       'fields' => array('id', 'stock_id', 'price', 'name')
						       )
					 );
	    $subTotal = $this->Cart->getSubTotal($watches);
	    $total = $subTotal + $shipping;
	    $this->Session->write('Cart.total', $total);
	}
	
	$this->set(array('data' => compact('shipping', 'total')));
	$this->layout = 'ajax';
    }
    
    protected function _getStates()
    {
        return array('AL'=>'Alabama',  
		'AK'=>'Alaska',  
                'AZ'=>'Arizona',  
                'AR'=>'Arkansas',  
                'CA'=>'California',  
                'CO'=>'Colorado',  
                'CT'=>'Connecticut',  
                'DE'=>'Delaware',  
                'DC'=>'District Of Columbia',  
                'FL'=>'Florida',  
                'GA'=>'Georgia',  
                'HI'=>'Hawaii',  
                'ID'=>'Idaho',  
                'IL'=>'Illinois',  
                'IN'=>'Indiana',  
                'IA'=>'Iowa',  
                'KS'=>'Kansas',  
                'KY'=>'Kentucky',  
                'LA'=>'Louisiana',  
                'ME'=>'Maine',  
                'MD'=>'Maryland',  
                'MA'=>'Massachusetts',  
                'MI'=>'Michigan',  
                'MN'=>'Minnesota',  
                'MS'=>'Mississippi',  
                'MO'=>'Missouri',  
                'MT'=>'Montana',
                'NE'=>'Nebraska',
                'NV'=>'Nevada',
                'NH'=>'New Hampshire',
                'NJ'=>'New Jersey',
                'NM'=>'New Mexico',
                'NY'=>'New York',
                'NC'=>'North Carolina',
                'ND'=>'North Dakota',
                'OH'=>'Ohio',  
                'OK'=>'Oklahoma',  
                'OR'=>'Oregon',  
                'PA'=>'Pennsylvania',  
                'RI'=>'Rhode Island',  
                'SC'=>'South Carolina',  
                'SD'=>'South Dakota',
                'TN'=>'Tennessee',  
                'TX'=>'Texas',  
                'UT'=>'Utah',  
                'VT'=>'Vermont',  
                'VA'=>'Virginia',  
                'WA'=>'Washington',  
                'WV'=>'West Virginia',  
                'WI'=>'Wisconsin',  
                'WY'=>'Wyoming');
    }
    
    protected function _getCanadianStates()
    {
        return array( 
            'BC'=>'British Columbia', 
            'ON'=>'Ontario', 
            'NL'=>'Newfoundland and Labrador', 
            'NS'=>'Nova Scotia', 
            'PE'=>'Prince Edward Island', 
            'NB'=>'New Brunswick', 
            'QC'=>'Quebec', 
            'MB'=>'Manitoba', 
            'SK'=>'Saskatchewan', 
            'AB'=>'Alberta', 
            'NT'=>'Northwest Territories', 
            'NU'=>'Nunavut',
            'YT'=>'Yukon Territory');
    }
}
