$(document).ready(function(){
    
    $('.launch-tooltip').tooltip();

    /**
     * Billing country autocomplete
     */
    $(document).on('keyup', '#AddressBillingCountryName', function(){
        $('#AddressBillingCountryName').autocomplete({
            source: '/cart/index.json',
            minLength: 3,
            select: function(event,ui){
                $(this).val(ui.item.value);
                $('.billing-country-name').empty().append(ui.item.value);
                $('#AddressBillingCountry').attr('value', ui.item.id);
                $('#AddressShippingCountry').attr('value', ui.item.id);
            }
        });
    });
    
    /**
     * Shipping country autocomplete
     * There should be a way to do both of these in one method
     * Shipping country will populate based on billing country
     */
    /*$(document).on('keyup', '#AddressShippingCountryName', function(){
        $('#AddressShippingCountryName').autocomplete({
            source: '/cart/index.json',
            minLength: 3,
            select: function(event,ui){
                $(this).val(ui.item.value);
                $('#AddressShippingCountry').attr('value', ui.item.id);
            }
        });
    });*/
    
    /**
     * Show shipping address form when ship to different address is selected
     */
    $(document).on('click', '#AddressShipping-address', function(){
        $('.address-form-shipping').toggle(this.checked);
    });
    
    /**
     * Check if a country is selected
     * Enable/disable submit button
     * Get shipping amount and appropriate address form
     */
    if ($('.select-country input:radio').is(':checked')) {
        //Enable submit button if country selected
        $('.submit-payment').prop('disabled', false);
        var country = $('.select-country input:radio:checked').val();
        computeTotal(country);
    }
    else {
        //Disable submit if no country selected
        $('.submit-payment').attr('disabled', 'disabled');
    }
    
    /**
     * Action for selecting a country
     */
    $('.select-country input').change(function(){
        //Uncheck the shipping option radios
        $('.shipping .radio input').prop('checked', false);
        //Remove shipping forms
        $('.address-forms').empty();
        //Enable the submit button
        $('.submit-payment').prop('disabled', false);
        //Hide the current address form that is showing
        //$('.address-form').hide();
        //Display correcting address form and shipping amount based on country
        var country = $(this).val();
        computeTotal(country);
    });

    /**
     * Compute shipping and total based on country
     */
    function computeTotal(country) {
        $.ajax({
            url: '/cart/getShipping.json',
            data: {"country" : country},
            dataType: 'json',
            success: function(data){
                var shipping = data.shipping;
                var totalFormatted = data.totalFormatted;
                $('.shipping-amount').empty().append(shipping);
                $('.total-formatted-amount').empty().append(totalFormatted);
                $('.shipping-inner').show();
            }
        });
    }
    
    $('.shipping .radio input').change(function(){
        var country = $('.select-country input:radio:checked').val();
        var shippingOption = $(this).val(); 
        getAddressForm(country, shippingOption);
    });
    
    if ($('.shipping .radio input').is(':checked')) {
        var country = $('.select-country input:radio:checked').val();
        var shippingOption = $('.shipping .radio input:radio:checked').val();
        getAddressForm(country, shippingOption);
    }
    
    /*$(document).on('click', '.address-form-billing .navbar li', function(){
        $(this).parent().find('li').removeClass('active');
        $(this).addClass('active');
        var href = $(this).find('a').attr('href');
        $.ajax({
            url: href,
            dataType: 'html',
            success: function(data){
                $('.billing-country-address-form').empty().append(data);
            }
        });
        return false;
    });*/
    
    //Get the country from the combo US/Canada form
    $(document).on('change', '.us-ca', function(){
        var state = $(this).val();
        $.ajax({
            url: '/cart/getCountry.json',
            data: {"state" : state},
            dataType: 'json',
            success: function(data){
                var country = data.country;
                $('#AddressBillingCountry').val(country);
            }
        })
    });
    
    function getAddressForm(country, shippingOption) {
        $.ajax({
            url: '/cart/getAddress.html',
            data: {"country" : country, "shipping" : shippingOption},
            dataType: 'html',
            success: function(data){
                $('.address-forms').empty().append(data);
                $('.shipping-instructions').show();
                $('.address textarea').show();
            }
        });
    }
    
    /*$('.payment-form').submit(function(){ 
        $('.submit-payment').attr('disabled', 'disabled');
        var form = $(this); 
        var error = false;
 
        // Get the values:
        var ccNum = $('.card-number').val(),
            cvcNum = $('.card-cvc').val(),
            expMonth = $('.card-expiry-month').val(),
            expYear = $('.card-expiry-year').val(); 
        
        // Validate the number:
        if (!Stripe.validateCardNumber(ccNum)) {
            error = true;
            reportError('The credit card number appears to be invalid.');
        }
         
        // Validate the CVC:
        if (!Stripe.validateCVC(cvcNum)) {
            error = true;
            reportError('The CVC number appears to be invalid.');
        }
         
        // Validate the expiration:
        if (!Stripe.validateExpiry(expMonth, expYear)) {
            error = true;
            reportError('The expiration date appears to be invalid.');
        }
        
        if (!error) { 
            // Get the Stripe token:
            Stripe.createToken(form, function(status, response){
                var token = response.id;
                form.append($('<input type="hidden" name="stripeToken" />').val(token));
                form.get(0).submit();
            });
        }
        return false;
    });*/
    
    function reportError(msg) {
 
        // Show the error in the form:
        $('.payment-errors').show().text(msg).addClass('error');
     
        // Re-enable the submit button:
        $('.submit-payment').prop('disabled', false);
     
        return false;
 
    }
});