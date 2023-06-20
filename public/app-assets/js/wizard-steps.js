/*=========================================================================================
    File Name: wizard-steps.js
    Description: wizard steps page specific js
    ----------------------------------------------------------------------------------------
    Item Name: Apex - Responsive Admin Theme
    Version: 1.0
    Author: PIXINVENT
    Author URL: http://www.themeforest.net/user/pixinvent
==========================================================================================*/

// Wizard tabs with icons setup
$(document).ready( function(){
    $(".icons-tab-steps").steps({
        headerTag: "h6",
        bodyTag: "fieldset",
        transitionEffect: "fade",
        titleTemplate: '<span class="step">#index#</span> #title#',
        labels: {
            finish: 'CONFIRM',
        },
        onFinished: function (event, currentIndex) {
            toastr.success('donemesssage', {
                "closeButton": true
            });
        },
        onStepChanging: function (event, currentIndex, newIndex) {
                 
                var first_name = $('#customer_first_name').val();
                var last_name = $('#customer_last_name').val();
                var email = $('#customer_email').val();
                var phone = $('#customer_phone').val();
                var address = $('#customer_address').val();
                var city = $('#customer_city').val();
                var state = $('#customer_state').val();
                var date_birth = $('#customer_birthday').val();
                var zip_code = $('#customer_zip_code').val();
                var ssn = $('#customer_ssn').val();
                var insurance_carrier = $('#insurance_carrier').val();
                var claim_number = $('#claim_number').val();
                var deductible_amount = $('#deductible_amount').val();
                var number_payments =  $("#number_payments").val();
                var customer_monthly_payment = $('#customer_monthly_payment').val();
                var zip_code = $('#zip_code').val();
                var date_loss = $('#date_loss').val();
                var payment_date = $('#payment_date').val();        
            
                $("#confirm_customer_first_name").text(first_name);
                $("#confirm_customer_last_name").text(last_name);
                $("#confirm_customer_email").text(email);
                $("#confirm_customer_phone").text(phone);
                $("#confirm_customer_address").text(address);
                $("#confirm_customer_city").text(city);
                $("#confirm_customer_state").text(state);
                $("#confirm_customer_birthday").text(date_birth);
                $("#confirm_zip_code").text(zip_code);
                $("#confirm_customer_ssn").text(ssn);
                $("#confirm_insurance_carrier").text(insurance_carrier);
                $("#confirm_claim_number").text(claim_number);
                $("#confirm_deductible_amount").text(deductible_amount);
                $("#confirm_number_payments").text(number_payments);
                $("#confirm_customer_monthly_payment").text(customer_monthly_payment);
                $("#confirm_date_loss").text(date_loss);
                $("#confirm_payment_date").text(payment_date);

                // return true; 

            
        }
      
    });

    

  
    // To select event date
    // $('.pickadate').pickadate();
 });