$(document).ready(function(){
    
    $('.launch-tooltip').tooltip();
    $('.carousel').carousel({
        pause: 'hover'    
    });
    $('.required label').append(' <span class="required">*</span>');
    
    $(document).on('click', '.fake-upload', function(){
        $('.image-upload').click();
    });

    $('#OrderShipDate, #InvoiceShipDate, #InvoiceExpiration').datepicker({dateFormat: 'yy-mm-dd'});
    $('#CouponExpireDate').datepicker({
      dateFormat: 'yy-mm-dd',
      minDate: new Date()
    });
    
    //Disable and enable filter input field based on filter option
    orderAdmin($('.admin-index select').val());
    $(document).on('change', '.admin-index select', function(){
        orderAdmin($(this).val());
    });
    
    function orderAdmin(filterValue) { 
        if (filterValue == '') {
            $('.admin-index .input input:not("#WatchActive")').val('').prop('disabled', true);
        } else {
            $('.admin-index .input input:not("#WatchActive")').prop('disabled', false);
        }
    }
    
    $(document).on('click', '.admin-index #WatchActive', function(){
        var value;
        if ($(this).is(':checked')) {
            value = 1;
        } else {
            value = 0;
        }
        var watchid = $(this).data('watchid');
        $.ajax({
            url: '/watches/active',
            data: {'active': value, 'watchid': watchid},
            type: 'post'
        });
    });

    $('.admin-index .archive-coupon').on('click', function() {
      $('.alert-danger').remove();
      var selected = $(this);
      var value = 0;
      if ($(this).is(':checked')) {
        value = 1;
      }
      var couponid = $(this).data('couponid');
      var couponcode = $(this).data('couponcode');
      $.ajax({
        url: '/coupons/archive',
        data: {'archived': value, 'couponid' : couponid, 'couponcode' : couponcode },
        dataType: 'json',
        type: 'post',
        success: function(data) {
          if(data.msg != 'undefined' && data.msg.length > 0) {
            $('.content').prepend('<div class="alert alert-danger">'+data.msg+'</div>');
            selected.prop('checked', 'checked');
          }
        }
      });
    });

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
    
    /**
     * Add and remove state field based on country
     */
    $(document).on('change', '.orders #Address0Country, .orders #Address1Country', function(){
        var id = $(this).attr('id');
        var addressId = id.match(/\d/);
        var country = $(this).val();
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
        computeTotal();
        $('.shipping').removeClass('hide').addClass('show');
        $('.shipping-inner').show();
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
        //Show shipping block
        $('.shipping').removeClass('hide').addClass('show');
        //Hide address and credit card blocks
        $('.address').removeClass('show').addClass('hide');
        $('.credit-card-order').removeClass('show').addClass('hide');
    });

    $('.cart-details').on('change', 'input', function() {
      computeTotal();
    });

    /**
     * Coupon and Order email need to be the same
     */
    $('#CouponEmail').on('keyup', function() {
      var couponEmail = $(this).val();
      if (couponEmail.length > 0) {
        $('#OrderEmail').val(couponEmail); 
      }
    });
    // Only set Coupon email based on order email if Coupon email not empty
      $('#OrderEmail').on('keyup', function() {
        var couponEmail = $('#CouponEmail').val();
        if (couponEmail.length > 0) {
          var orderEmail = $(this).val();
          $('#CouponEmail').val(orderEmail);
          computeTotal();
        }
      });

    /**
     * Compute shipping,coupon amount and total
     */
    function computeTotal() {
      $.ajax({
        url: '/orders/totalCart.json',
        data: $('#OrderCheckoutForm').serialize(),
        dataType: 'json',
        cache: false,
        success: function(data){
          var shipping = data.shipping;
          var totalFormatted = data.totalFormatted;
          var couponAmount = data.couponAmount;
          var message = data.alert;
          $('.shipping-amount').empty().append(shipping).find('.launch-tooltip').tooltip();
          $('.coupon-amount').empty();
          if (typeof(couponAmount) != 'undefined' && couponAmount !== null) {
            $('.coupon-amount').append('('+data.couponFormatted+')');
          }
          if ($('#CouponEmail').val().length>0 && $('#CouponCode').val().length>0 && (typeof(couponAmount)=='undefined' || couponAmount == null)) {
            $('.cart-details .coupon-alert').empty().append(message);
          } else {
            $('.cart-details .coupon-alert').empty();
          }
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
    
    // Only for checkout page
    if ($('.shipping .radio input').length>1) {
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
                $('.invoices .line-items').append(data).find('.launch-tooltip').tooltip();;
                // Remove all required spans and add to avoid multiple span on elements already in DOM
                $('.invoices .input.required label').each(function(){
                    $(this).find('span').remove();
                    $(this).append(' <span class="required">*</span>');
                });
            }
        });
        return false;
    });
    
    $(document).on('click', '.remove-line-item', function() { 
        var count = $(this).parent().data('count');
        if (typeof(count) != "undefined") {
            // Line item edit
            var description = $(this).parent().data('description');
            var invoice_id = $(this).parent().data('invoice_id');
            var item_id = $(this).parent().data('item_id');
            $.ajax({
                url: '/invoices/deleteModal',
                dataType: 'html',
                data: {'description' : description, 'invoice_id' : invoice_id, 'item_id' : item_id},
                type: 'post',
                cache: false,
                success: function(data){
                    $('body').append(data);
                    $('#delete-line-item').modal();
                }
            });
        } else {
            // Line item add, just delete row from DOM
            $(this).parents('.line-item').remove();
        }
        return false;
    });
    
    $(document).on('click', '.invoice-url', function(){
        $(this).focus().select();
    });
    
    /**
     * Since an anchor tag surrounds an entire table row, we can't have anchor tags within the rows.
     * So create a GET form, put the required ID in a hidden field and submit form via javascript
     */
    $(document).on('click', '.create-ebay-invoice', function(e) {
        var ebayId = $(this).data('ebayid'); 
        $('#InvoiceEbayItemId').val(ebayId);
        $('form').get(0).submit();
        return false;
    });
    
    $(document).on('click', '.goto-order', function(e) {
        var orderId = $(this).data('orderid'); 
        $('#OrderOrderId').val(orderId);
        $('#OrderAdminIndexForm').get(0).submit();
        return false;
    });
    
    //Delete contact
    $(document).on('click', '.delete-contact', function(e) {
        var contactId = $(this).data('contactid');
        var contactName = $(this).data('contactname');
        var query = $(this).data('query'); 
        $.ajax({
            url: '/contacts/deleteModal',
            dataType: 'html',
            data: {'contactId' : contactId, 'contactName' : contactName, 'query' : query},
            type: 'post',
            cache: false,
            success: function(data){
                $('body').append(data);
                $('#delete-contact').modal();
            }
        });
        return false;
    });
    
    //Delete coupon
    $(document).on('click', '.delete-coupon', function(e) {
        var couponId = $(this).data('coupon');
        var couponCode = $(this).data('code');
        $.ajax({
            url: '/coupons/deleteModal',
            dataType: 'html',
            data: {'couponId' : couponId, 'couponCode' : couponCode},
            type: 'post',
            cache: false,
            success: function(data){
                $('body').append(data);
                $('#delete-coupon').modal();
            }
        });
        return false;
    });

    //Format coupon amount to 2 decimal places
    $('#CouponAmount').on('blur', function() {
      var val = $(this).val();
      $(this).val(parseFloat(Math.round(val * 100) / 100).toFixed(2));
    });

    //Disable submit button on click
    $(document).on('click', '.fake-contact-submit', function() { 
        $(this).prop('disabled', true);
        $('form').get(0).submit();
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
