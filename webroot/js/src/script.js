$(document).ready(function(){
    
    $('.launch-tooltip').tooltip();
    $('.carousel').carousel({
        pause: 'hover'    
    });
    $('.required label').append(' <span class="required">*</span>');
    
    $(document).on('click', '.fake-upload', function(){
        $('.image-upload').click();
    });
    
    $('#OrderShipDate').datepicker({dateFormat: 'yy-mm-dd'});
    
    //Disable and enable filter input field based on filter option
    orderAdmin($('.admin-index select').val());
    $(document).on('change', '.admin-index select', function(){
        orderAdmin($(this).val());
    });
    
    function orderAdmin(filterValue) { 
        if (filterValue == '') {
            $('.admin-index .input input').val('').prop('disabled', true);
        } else {
            $('.admin-index .input input').prop('disabled', false);
        }
    }
    
    //Set position of indicators on homepage carousel, not a perfect solution
    setHomeCarouselImageHeight();
    $(window).on('resize', function(){
        setHomeCarouselImageHeight();
    });
    
    function setHomeCarouselImageHeight () {
        var height = $('#carousel-home .carousel-inner img').height();
        if (height == 0) {
            height = 375;
        }
        $('#carousel-home .carousel-indicators').css('top', height-25);
        $('#carousel-home .carousel-control .glyphicon').css('top', height-25);
    }
    
    var hideWatchIntroCookie = getCookie('hideWatchIntro'); 
    if (hideWatchIntroCookie != null && hideWatchIntroCookie == 1) {
        $('.watch-index-intro').hide();
        $('.watches-header .glyphicon-eye-open').show();
    }
    
    $(document).on('click', '.watch-index-intro .glyphicon-remove', function(){
        $('.watch-index-intro').hide();
        $('.watches-header .glyphicon-eye-open').show();
        setCookie('hideWatchIntro', 1, 90);
    });
    $(document).on('click', '.watches-header .glyphicon-eye-open', function(){
        $('.watch-index-intro').show();
        $('.watches-header .glyphicon-eye-open').hide();
        setCookie('hideWatchIntro', 0, 90);
    });
    
    $(document).on('change', '#WatchAdminIndexForm', function(){
        $(this).submit(); 
    });
    
    $(document).on('click', '.announcement-list-signup input[type=submit]', function(e){
        var val = $('input[name=email]').val();
        if (val=='') {
            $('.announcement-list-signup .announce-error').show();
            e.preventDefault();
        } 
    });
    
    $(document).on('change', '#Address0Country, #Address1Country', function(){
        var id = $(this).attr('id');
        var addressId = id.match(/\d/);
        var country = $(this).val();
        console.log(id + ' ' + addressId + ' ' + country);
        if (country !='US' && country !='CA') {
            $('#Address' + addressId + 'State').removeAttr('required').parent('.input').hide();
        } else {
            $('#Address' + addressId + 'State').attr('required', 'required').parent('.input').show();
        }
    });
    
    /**
     * Check if a country is selected
     * Enable/disable submit button
     * Get shipping amount and appropriate address form
     */
    if ($('.select-country input:radio').is(':checked')) {
        var country = $('.select-country input:radio:checked').val();
        computeTotal(country);
        $('.shipping').removeClass('hide').addClass('show');
    }
    
    /**
     * Action for selecting a country
     */
    $('.select-country input').change(function(){
        //Uncheck the shipping option radios
        $('.shipping .radio input').prop('checked', false);
        //Remove shipping forms
        $('.address-forms').empty();
        //Hide special order instructions
        $('.shipping-instructions').hide();
        //Disable submit 
        $('.submit-payment').attr('disabled', 'disabled');
        //Display correcting address form and shipping amount based on country
        var country = $(this).val();
        computeTotal(country);
        //Show shipping block
        $('.shipping').removeClass('hide').addClass('show');
        //Hide address and credit card blocks
        $('.address').removeClass('show').addClass('hide');
        $('.credit-card-order').removeClass('show').addClass('hide');
    });

    /**
     * Compute shipping and total based on country
     */
    function computeTotal(country) {
        $.ajax({
            url: '/orders/totalCart.json',
            data: {"country" : country},
            dataType: 'json',
            cache: false,
            success: function(data){
                var shipping = data.shipping;
                var totalFormatted = data.totalFormatted;
                $('.shipping-amount').empty().append(shipping).find('.launch-tooltip').tooltip();
                $('.total-formatted-amount').empty().append(totalFormatted);
                $('.shipping-inner').show();
            }
        });
    }
    
    $('.shipping .radio input').change(function(){
        //Enable the submit button
        $('.submit-payment').prop('disabled', false);
        var country = $('.select-country input:radio:checked').val();
        var shippingOption = $(this).val(); 
        getAddressForm(country, shippingOption);
        //Show address and credit card blocks
        $('.address').removeClass('hide').addClass('show');
        $('.credit-card-order').removeClass('hide').addClass('show');
    });
    
    if ($('.shipping .radio input').is(':checked')) { 
        //Enable submit button if shipping option selected
        $('.submit-payment').prop('disabled', false);
        var country = $('.select-country input:radio:checked').val();
        var shippingOption = $('.shipping .radio input:radio:checked').val();
        getAddressForm(country, shippingOption);
        //Show address and credit card blocks
        $('.address').removeClass('hide').addClass('show');
        $('.credit-card-order').removeClass('hide').addClass('show');
    }
    else { 
        //Hide special order instructions
        $('.shipping-instructions').hide();
        //Disable submit if no shipping option selected
        $('.submit-payment').attr('disabled', 'disabled');
        //Hide address and credit card blocks
        $('.address').removeClass('show').addClass('hide');
        $('.credit-card-order').removeClass('show').addClass('hide');
    }
    
    /**
     * Billing country autocomplete
     */
    $(document).on('keyup', '#AddressBillingCountryName', function(){
        $('#AddressBillingCountryName').autocomplete({
            source: '/orders/checkout.json',
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
     * Show shipping address form when ship to different address is selected
     */
    $(document).on('click', '#AddressShipping-address', function(){
        $('.address-form-shipping').toggle(this.checked);
    });
    
    //Get the country from the combo US/Canada form
    $(document).on('change', '.us-ca', function(){
        getCountry($(this));
    });
    
    $('.table a.table-row').hover(function(){
        $(this).addClass('row-hover');
    },
    function(){
        $(this).removeClass('row-hover');    
    });
    
    function getAddressForm(country, shippingOption) { 
        $.ajax({
            url: '/orders/getAddress.html',
            data: {"country" : country, "shipping" : shippingOption},
            dataType: 'html',
            cache: false,
            success: function(data){ 
                $('.address-forms').empty().append(data);
                $('.shipping-instructions').show();
                $('.address textarea').show();
                $('.address-forms .input.required label').append(' <span class="required">*</span>');
                $('.address-forms').find('.launch-tooltip').tooltip();
                //Check to see if we have a billing country and fill in shipping with it
                if ($('#AddressBillingCountry').val() != '') {
                    var countryName = $('#AddressBillingCountryName').val(); 
                    $('.billing-country-name').empty().append(countryName);
                }

                //Check to see if state dropdown has a value and fill in the hidden country field
                $('select.us-ca').each(function(){
                    getCountry($(this));
                })
                
                //Add required asterisk
                $('.address-forms .required label').append(' <span class="required">*</span>');
            }
        });
    }
    
    function getCountry (value) {
        $.ajax({
            url: '/orders/getCountry.json',
            data: value,
            dataType: 'json',
            cache: false,
            success: function(data){
                var country = data.country;
                var type = data.type;
                $('#Address' + type + 'Country').val(country);
            }
        })
    }
    
    /**
     * Add line item to invoice in admin
     */
    $(document).on('click', '.invoices .add-line-item', function(){
        var count = $('.line-item').length;
        $.ajax({
            url: '/invoices/getLineItem/' + count,
            dataType: 'html',
            cache: false,
            success: function(data){
                $('.invoices .line-items').append(data);
                // Remove all required spans and add to avoid multiple span on elements already in DOM
                $('.invoices .input.required label').each(function(){
                    $(this).find('span').remove();
                    $(this).append(' <span class="required">*</span>');
                });
            }
        });
    });
    
    $(document).on('click', '.invoice-url', function(){
        $(this).focus().select();
    });
    
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
    
    function setCookie(cookieName, value, exdays) {
        var exdate=new Date();
        exdate.setDate(exdate.getDate() + exdays);
        var cookieValue=escape(value) + ((exdays==null) ? "" : "; expires="+exdate.toUTCString()); 
        document.cookie = cookieName + "=" + cookieValue;
    }
    
    function getCookie(cookieName) {
        var cookieValue = document.cookie; 
        var cookieStart = cookieValue.indexOf(" " + cookieName + "="); 
        if (cookieStart == -1) {
            cookieStart = cookieValue.indexOf(cookieName + "=");
        }
        if (cookieStart == -1) {
            cookieValue = null;
        } else {
            cookieStart = cookieValue.indexOf("=", cookieStart) + 1; 
            var cookieEnd = cookieValue.indexOf(";", cookieStart);
            if (cookieEnd == -1) {
                cookieEnd = cookieValue.length;
            }
            cookieValue = unescape(cookieValue.substring(cookieStart, cookieEnd)); 
        }
        return cookieValue;
    }
});