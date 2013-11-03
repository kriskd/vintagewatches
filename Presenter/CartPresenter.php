<?php
App::uses('Presenter', 'CakePHP-GiftWrap.Presenter');

class CartPresenter extends Presenter
{
    /**
     * Display "Add to Cart" button if item is not in cart
     * Display "This item is in your cart" if item is in cart
     */
    public function addToCart()
    {
        // An array of item IDs in the cart
        debug($this->cartItemIds);
        
        // #fail Looks for idHelper Class
        // debug($this->id);
        
        // Just a test that the view calls the Presenter
        return 'here';
    }
}