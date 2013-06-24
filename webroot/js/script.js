$(document).ready(function(){
    
    $(document).on('keyup', '.country-autocomplete', function(){
        $('.country-autocomplete').autocomplete({
            source: '/cart/index.json',
            minLength: 3,
            select: function(event,ui){
                $(this).val(ui.item.value);
                $('#billingCountry').attr('value', ui.item.id);
            }
        });
    });
        
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
    
    $('.select-country input').change(function(){
        $('.submit-payment').prop('disabled', false);
        $('.address-form').hide();
        var country = $(this).val();
        computeTotal(country);
    });
    
    $('.launch-tooltip').tooltip();

    /**
     * Show the address form based on the country
     * Compute shipping and total based on country
     */
    function computeTotal(country) {
        $.ajax({
            url: '/cart/getCountry.json',
            data: {"country" : country},
            dataType: 'json',
            success: function(data){
                var shipping = data.shipping;
                var totalFormatted = data.totalFormatted;
                var form = data.form;
                $('.shipping-amount').empty().append(shipping);
                $('.total-formatted-amount').empty().append(totalFormatted);
                $('.address-form').show();
                $('.billing-country-address-form').empty().append(form);
            }
        });
    }
    
    $('.payment-form').submit(function(){ 
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
    });
    
    function reportError(msg) {
 
        // Show the error in the form:
        $('.payment-errors').show().text(msg).addClass('error');
     
        // Re-enable the submit button:
        $('.submit-payment').prop('disabled', false);
     
        return false;
 
    }
});