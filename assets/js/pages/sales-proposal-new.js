(function($) {
    'use strict';

    $(function() {
        if($('#add-sales-proposal-form').length){
            addSalesProposalForm();
        }

        $(document).on('change','#product_id',function() {  
            if($(this).val() != ''){
                displayDetails('get product details');
            }
            else{
                $('#delivery_price').val('');
                $('#old_color').val('');
                $('#old_body').val('');
                $('#old_engine').val('');
            }
        });

        $(document).on('change','#product_type',function() {
            var productType = $(this).val();

            switch (productType) {
                case 'Brand New':
                    $('#refinancing-title').text('Brand New Details');
                    break;
                case 'Refinancing':
                    $('#refinancing-title').text('Refinancing Details');
                    break;
                case 'Restructure':
                    $('#refinancing-title').text('Restructure Details');
                    break;
            }

            if(productType != ''){
                $('#pricing-computation-card, #other-charges-card, #renewal-amount-card').removeClass('d-none');
            }
            
            $('#unit-card, #fuel-card, #refinancing-card').addClass('d-none');
            resetModalForm('unit-card');
            resetModalForm('refinancing-card');
            resetModalForm('fuel-card');

            $('#product_engine_number').text('');
            $('#product_chassis_number').text('');
            $('#product_plate_number').text('');

            $('#diesel_total').text('');
            $('#regular_total').text('');
            $('#premium_total').text('');
            $('#fuel_total').text('');
            $('#delivery_price').text('');
            
            switch (productType) {
                case 'Unit':
                case 'Rental':
                case 'Consignment':
                    $('#unit-card').removeClass('d-none');
                    resetModalForm('fuel-card');
                    resetModalForm('refinancing-card');
                    break;
                case 'Fuel':
                    $('#fuel-card').removeClass('d-none');
                    resetModalForm('unit-card');
                    resetModalForm('refinancing-card');
                    break;
                case 'Brand New':
                case 'Refinancing':
                case 'Restructure':
                    $('#refinancing-card').removeClass('d-none');
                    resetModalForm('unit-card');
                    resetModalForm('fuel-card');
                    break;
            }

            if(productType == 'Unit' || productType === 'Rental' || productType === 'Consignment' || productType == 'Fuel'){
                if($('#delivery_price').length){
                    $('#delivery_price').prop('readonly', true);
                }
            }
            else{
                if($('#delivery_price').length){
                    $('#delivery_price').prop('readonly', false);
                }
            }

            if(productType == 'Refinancing' || productType == 'Restructure'){
                if($('#insurance_premium').length){
                    $('#insurance_premium').prop('readonly', false);
                }
            }
            else{
                if($('#insurance_premium').length){
                    $('#insurance_premium').prop('readonly', true);
                }
            }
        });

        $(document).on('change','#start_date',function() {
            calculateFirstDueDate();
        });

        $(document).on('change','#payment_frequency',function() {
            calculateFirstDueDate();
            calculatePaymentNumber();
        });

        $(document).on('change','#number_of_payments',function() {
            calculateFirstDueDate();
        });

        $(document).on('change','#for_change_engine',function() {
            if($(this).val() == 'Yes'){
                $('#new_engine').attr('readonly', false); 
            }
            else{
                $('#new_engine').val('');
                $('#new_engine').attr('readonly', true); 
            }
        });

        $(document).on('change','#diesel_fuel_quantity',function() {
            calculateFuelTotal();
            calculateTotalOtherCharges();
        });

        $(document).on('change','#diesel_price_per_liter',function() {
            calculateFuelTotal();
            calculateTotalOtherCharges();
        });

        $(document).on('change','#regular_fuel_quantity',function() {
            calculateFuelTotal();
            calculateTotalOtherCharges();
        });

        $(document).on('change','#regular_price_per_liter',function() {
            calculateFuelTotal();
            calculateTotalOtherCharges();
        });

        $(document).on('change','#premium_fuel_quantity',function() {
            calculateFuelTotal();
            calculateTotalOtherCharges();
        });

        $(document).on('change','#premium_price_per_liter',function() {
            calculateFuelTotal();
            calculateTotalOtherCharges();
        });

        $(document).on('change','#delivery_price',function() {
            calculateTotalDeliveryPrice();
            calculateRenewalAmount();
            calculateTotalOtherCharges();
        });

        $(document).on('change','#add_on_charge',function() {
            calculateTotalDeliveryPrice();
            calculateTotalOtherCharges();
        });

        $(document).on('change','#nominal_discount',function() {
            calculateTotalDeliveryPrice();
            calculateTotalOtherCharges();
        });

        $(document).on('change','#term_length',function() {
            calculatePricingComputation();
        });

        $(document).on('change','#interest_rate',function() {
            calculatePricingComputation();
            calculateTotalOtherCharges();
        });

        $(document).on('change','#cost_of_accessories',function() {
            calculatePricingComputation();
            calculateTotalOtherCharges();
        });

        $(document).on('change','#reconditioning_cost',function() {
            calculatePricingComputation();
            calculateTotalOtherCharges();
        });

        $(document).on('change','#downpayment',function() {
            calculatePricingComputation();
            calculateTotalOtherCharges();
        });

        $(document).on('change','#insurance_coverage',function() {
            calculateTotalOtherCharges();
        });

        $(document).on('change','#insurance_premium',function() {
            calculateTotalOtherCharges();
        });

        $(document).on('change','#insurance_premium_discount',function() {
            calculateTotalOtherCharges();
        });

        $(document).on('change','#handling_fee',function() {
            calculateTotalOtherCharges();
        });

        $(document).on('change','#handling_fee_discount',function() {
            calculateTotalOtherCharges();
        });

        $(document).on('change','#transfer_fee',function() {
            calculateTotalOtherCharges();
        });

        $(document).on('change','#transfer_fee_discount',function() {
            calculateTotalOtherCharges();
        });

        $(document).on('change','#registration_fee',function() {
            calculateTotalOtherCharges();
        });

        $(document).on('change','#doc_stamp_tax',function() {
            calculateTotalOtherCharges();
        });

        $(document).on('change','#doc_stamp_tax_discount',function() {
            calculateTotalOtherCharges();
        });

        $(document).on('change','#transaction_fee',function() {
            calculateTotalOtherCharges();
        });

        $(document).on('change','#transaction_fee_discount',function() {
            calculateTotalOtherCharges();
        });

        $(document).on('change','#compute_second_year',function() {
            calculateRenewalAmount();
        });

        $(document).on('change','#compute_third_year',function() {
            calculateRenewalAmount();
        });

        $(document).on('change','#compute_fourth_year',function() {
            calculateRenewalAmount();
        });

        $(document).on('change','#transaction_type',function() {
            $('#summary-transaction-type').text($(this).val());

            if($(this).val() == 'Bank Financing'){
                $('#term_length').val('45');
                checkOptionExist('#payment_frequency', 'Lumpsum', '');
                checkOptionExist('#term_type', 'Days', '');
            }
        });

        $(document).on('change','#term_type',function() {
            $('#payment_frequency').empty().append(new Option('--', '', false, false));

            if($(this).val() == 'Days'){
                $('#payment_frequency').append(new Option('Lumpsum', 'Lumpsum', false, false));
            }
            else if($(this).val() == 'Months'){
                $('#payment_frequency').append(new Option('Lumpsum', 'Lumpsum', false, false));
                $('#payment_frequency').append(new Option('Monthly', 'Monthly', false, false));
                $('#payment_frequency').append(new Option('Quarterly', 'Quarterly', false, false));
                $('#payment_frequency').append(new Option('Semi-Annual', 'Semi-Annual', false, false));
            }
        });
    });
})(jQuery);

function addSalesProposalForm(){
    $('#add-sales-proposal-form').validate({
        rules: {
            renewal_tag: {
                required: true
            },
            product_type: {
                required: true
            },
            application_source_id: {
                required: true
            },
            transaction_type: {
                required: true
            },
            company_id: {
                required: true
            },
            financing_institution: {
                required: {
                    depends: function(element) {
                        return $("select[name='transaction_type']").val() === 'Bank Financing';
                    }
                }
            },
            comaker_id: {
                required: {
                    depends: function(element) {
                        return $("select[name='transaction_type']").val() === 'Installment Sales';
                    }
                }
            },
            release_date: {
                required: true
            },
            start_date: {
                required: true
            },
            term_length: {
                required: true
            },
            term_type: {
                required: true
            },
            payment_frequency: {
                required: true
            },
            initial_approving_officer: {
                required: true
            },
            final_approving_officer: {
                required: true
            },
            commission_amount: {
                required: true
            },
            product_id: {
                required: {
                    depends: function(element) {
                        return ["Unit", "Rental", "Consignment"].includes($("select[name='product_type']").val());
                    }
                }
            },
            new_color: {
                required: {
                    depends: function(element) {
                        return $("select[name='product_type']").val() === 'Unit';
                    }
                }
            },
            new_body: {
                required: {
                    depends: function(element) {
                        return $("select[name='product_type']").val() === 'Unit';
                    }
                }
            },
            new_engine: {
                required: {
                    depends: function(element) {
                        return $("select[name='for_change_engine']").val() === 'Yes';
                    }
                }
            },
            diesel_fuel_quantity: {
                required: {
                    depends: function(element) {
                        return $("select[name='product_type']").val() === 'Fuel';
                    }
                }
            },
            diesel_price_per_liter: {
                required: {
                    depends: function(element) {
                        return $("select[name='product_type']").val() === 'Fuel';
                    }
                }
            },
            regular_fuel_quantity: {
                required: {
                    depends: function(element) {
                        return $("select[name='product_type']").val() === 'Fuel';
                    }
                }
            },
            regular_price_per_liter: {
                required: {
                    depends: function(element) {
                        return $("select[name='product_type']").val() === 'Fuel';
                    }
                }
            },
            premium_fuel_quantity: {
                required: {
                    depends: function(element) {
                        return $("select[name='product_type']").val() === 'Fuel';
                    }
                }
            },
            premium_price_per_liter: {
                required: {
                    depends: function(element) {
                        return $("select[name='product_type']").val() === 'Fuel';
                    }
                }
            },
            ref_engine_no: {
                required: {
                    depends: function(element) {
                        var productType = $("select[name='product_type']").val();
                        return productType === 'Refinancing' || productType === 'Brand New' || productType === 'Restructure';
                    }
                }
            },
            ref_chassis_no: {
                required: {
                    depends: function(element) {
                        var productType = $("select[name='product_type']").val();
                        return productType === 'Refinancing' || productType === 'Brand New' || productType === 'Restructure';
                    }
                }
            },
            ref_plate_no: {
                required: {
                    depends: function(element) {
                        var productType = $("select[name='product_type']").val();
                        return productType === 'Refinancing' || productType === 'Brand New' || productType === 'Restructure';
                    }
                }
            },
            final_orcr_name: {
                required: {
                    depends: function(element) {
                        var productType = $("#product_category").val();
                        return productType === '1';
                    }
                }
            },
        },
        messages: {
            renewal_tag: {
                required: 'Please choose the renewal tag'
            },
            product_type: {
                required: 'Please choose the product type'
            },
            application_source_id: {
                required: 'Please choose the application source'
            },
            company_id: {
                required: 'Please choose the company'
            },
            transaction_type: {
                required: 'Please choose the transaction type'
            },
            financing_institution: {
                required: 'Please enter the financing institution'
            },
            comaker_id: {
                required: 'Please choose the co-maker'
            },
            release_date: {
                required: 'Please choose the release date'
            },
            start_date: {
                required: 'Please choose the start date'
            },
            term_length: {
                required: 'Please enter the term length'
            },
            term_type: {
                required: 'Please choose the term type'
            },
            payment_frequency: {
                required: 'Please choose the payment frequency'
            },
            initial_approving_officer: {
                required: 'Please choose the initial approving officer'
            },
            final_approving_officer: {
                required: 'Please choose the final approving officer'
            },
            commission_amount: {
                required: 'Please enter the commission amount'
            },
            product_id: {
                required: 'Please choose the stock'
            },
            new_color: {
                required: 'Please enter the new color'
            },
            new_body: {
                required: 'Please enter the new body'
            },
            new_engine: {
                required: 'Please enter the new engine'
            },
            diesel_fuel_quantity: {
                required: 'Please enter the fuel quantity'
            },
            diesel_price_per_liter: {
                required: 'Please enter the price per liter'
            },
            regular_fuel_quantity: {
                required: 'Please enter the fuel quantity'
            },
            regular_price_per_liter: {
                required: 'Please enter the price per liter'
            },
            premium_fuel_quantity: {
                required: 'Please enter the fuel quantity'
            },
            premium_price_per_liter: {
                required: 'Please enter the price per liter'
            },
            ref_engine_no: {
                required: 'Please enter the engine number'
            },
            ref_chassis_no: {
                required: 'Please enter the chassis number'
            },
            ref_plate_no: {
                required: 'Please enter the plate number'
            },
            final_orcr_name: {
                required: 'Please enter the final name on or/cr'
            },
        },
        errorPlacement: function (error, element) {
            if (element.hasClass('select2') || element.hasClass('modal-select2')) {
              error.insertAfter(element.next('.select2-container'));
            }
            else if (element.hasClass('form-check-input')) {
                error.insertAfter(element.closest('.form-check-inline'));
            }
            else if (element.parent('.input-group').length) {
              error.insertAfter(element.parent());
            }
            else {
              error.insertAfter(element);
            }
        },
        highlight: function(element) {
            var inputElement = $(element);
            if (inputElement.hasClass('select2-hidden-accessible')) {
              inputElement.next().find('.select2-selection__rendered').addClass('is-invalid');
            }
            else {
              inputElement.addClass('is-invalid');
            }
        },
        unhighlight: function(element) {
            var inputElement = $(element);
            if (inputElement.hasClass('select2-hidden-accessible')) {
              inputElement.next().find('.select2-selection__rendered').removeClass('is-invalid');
            }
            else {
              inputElement.removeClass('is-invalid');
            }
        },
        submitHandler: function(form) {
            const sales_proposal_id = $('#sales-proposal-id').text();
            const customer_id = $('#customer-id').text();
            const transaction = 'save sales proposal';
        
            $.ajax({
                type: 'POST',
                url: 'controller/sales-proposal-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&sales_proposal_id=' + sales_proposal_id + '&customer_id=' + customer_id,
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        if(response.insertRecord){
                            setNotification('Insert Sales Proposal Success', 'The sales proposal has been inserted successfully.', 'success');
                            window.location = 'sales-proposal.php?customer='+ response.customerID +'&id=' + response.salesProposalID;
                        }
                    }
                    else {
                        if (response.isInactive) {
                            setNotification('User Inactive', response.message, 'danger');
                            window.location = 'logout.php?logout';
                        }
                        else {
                            showNotification('Transaction Error', response.message, 'danger');
                        }
                    }
                },
                error: function(xhr, status, error) {
                    var fullErrorMessage = 'XHR status: ${status}, Error: ${error}';
                    if (xhr.responseText) {
                        fullErrorMessage += ', Response: ${xhr.responseText}';
                    }
                    showErrorDialog(fullErrorMessage);
                }
            });
        
            return false;
        }
    });
}

function checkIntegerDivision(dividend, divisor) {
    let result = dividend / divisor;
    return Number.isInteger(result) ? result : 0;
}

function calculateFirstDueDate(){
    var start_date = $('#start_date').val();
    var payment_frequency = $('#payment_frequency').val();
    var term_length = $('#term_length').val();
    var number_of_payments = $('#number_of_payments').val();

    $.ajax({
        type: 'POST',
        url: './config/calculate_first_due_date.php',
        data: { start_date: start_date, term_length : term_length, payment_frequency: payment_frequency, number_of_payments: number_of_payments },
        success: function (result) {
            $('#first_due_date').val(result);
        }
    });
}

function calculatePaymentNumber() {
    var payment_frequency = $('#payment_frequency').val();
    var term_length = $('#term_length').val();

    let number_of_payments = 0;

    switch (payment_frequency) {
        case 'Lumpsum':
            number_of_payments = 1;
            break;
        case 'Monthly':
            number_of_payments = term_length;
            break;
        case 'Quarterly':
            number_of_payments = checkIntegerDivision(term_length, 3);
            break;
        case 'Semi-Annual':
            number_of_payments = checkIntegerDivision(term_length, 6);
            break;
        default:
            number_of_payments = 0;
    }

    $('#number_of_payments').val(number_of_payments);

    calculateFirstDueDate();
}

function calculateFuelTotal() {
    var diesel_fuel_quantity = parseCurrency($('#diesel_fuel_quantity').val());
    var diesel_price_per_liter = parseCurrency($('#diesel_price_per_liter').val());
    var regular_fuel_quantity = parseCurrency($('#regular_fuel_quantity').val());
    var regular_price_per_liter = parseCurrency($('#regular_price_per_liter').val());
    var premium_fuel_quantity = parseCurrency($('#premium_fuel_quantity').val());
    var premium_price_per_liter = parseCurrency($('#premium_price_per_liter').val());

    var total_diesel = diesel_fuel_quantity * diesel_price_per_liter;
    var total_regular = regular_fuel_quantity * regular_price_per_liter;
    var total_premium = premium_fuel_quantity * premium_price_per_liter;
    var total_delivery_price = total_diesel + total_regular + total_premium;

    $('#diesel_total').text(parseCurrency(total_diesel.toFixed(2)).toLocaleString("en-US"));
    $('#regular_total').text(parseCurrency(total_regular.toFixed(2)).toLocaleString("en-US"));
    $('#premium_total').text(parseCurrency(total_premium.toFixed(2)).toLocaleString("en-US"));
    $('#fuel_total').text(parseCurrency(total_delivery_price.toFixed(2)).toLocaleString("en-US"));
    $('#delivery_price').val(parseCurrency(total_delivery_price.toFixed(2)).toLocaleString("en-US"));

    calculateTotalDeliveryPrice();
}

function calculateTotalDeliveryPrice(){
    var delivery_price = parseCurrency($("#delivery_price").val());
    var add_on_charge = parseCurrency($("#add_on_charge").val());
    var nominal_discount = parseCurrency($("#nominal_discount").val());

    var total = (delivery_price + add_on_charge) - nominal_discount;

    if(total <= 0){
        total = 0;
    }

    $('#total_delivery_price').val(parseCurrency(total.toFixed(2)).toLocaleString("en-US"));
    $('#summary-deliver-price').text(parseCurrency(total.toFixed(2)).toLocaleString("en-US"));
    $('#total_delivery_price_label').val(total);

    calculatePricingComputation();
}

function calculatePricingComputation(){
    var term_length = parseCurrency($('#term_length').val());
    var interest_rate = parseCurrency($('#interest_rate').val());
    var delivery_price = parseCurrency($('#total_delivery_price').val());
    var cost_of_accessories = parseCurrency($('#cost_of_accessories').val());
    var reconditioning_cost = parseCurrency($('#reconditioning_cost').val());
    var downpayment = parseCurrency($('#downpayment').val());
    var number_of_payments = parseCurrency($('#number_of_payments').val());

    var payment_frequency = $('#payment_frequency').val();

    if(payment_frequency == 'Lumpsum'){
        term_length = 1;
    }
    else if(payment_frequency == 'Semi-Annual' || payment_frequency == 'Quarterly'){
        term_length = (number_of_payments);
    }

    var subtotal = delivery_price + cost_of_accessories + reconditioning_cost;
    var outstanding_balance = subtotal - downpayment;
    var pn_amount = outstanding_balance * (1 + (interest_rate/100));
    var repayment_amount = Math.ceil(pn_amount / term_length);
    var downpayment_percent = (downpayment / delivery_price) * 100;

    $('#subtotal').val(parseCurrency(subtotal.toFixed(2)).toLocaleString("en-US"));
    $('#outstanding_balance').val(parseCurrency(outstanding_balance.toFixed(2)).toLocaleString("en-US"));

    $('#amount_financed').val(parseCurrency(outstanding_balance.toFixed(2)).toLocaleString("en-US"));
    $('#pn_amount').val(parseCurrency(pn_amount.toFixed(2)).toLocaleString("en-US"));
    $('#repayment_amount').val(parseCurrency(repayment_amount.toFixed(2)).toLocaleString("en-US"));

    $('#summary-repayment-amount').text(parseCurrency(repayment_amount.toFixed(2)).toLocaleString("en-US"));
    $('#summary-outstanding-balance').text(parseCurrency(outstanding_balance.toFixed(2)).toLocaleString("en-US"));
    $('#summary-sub-total').text(parseCurrency(subtotal.toFixed(2)).toLocaleString("en-US"));

    $('#summary-cost-of-accessories').text(parseFloat(cost_of_accessories.toFixed(2)).toLocaleString("en-US"));
    $('#summary-reconditioning-cost').text(parseFloat(reconditioning_cost.toFixed(2)).toLocaleString("en-US"));
    $('#summary-downpayment').text(parseFloat(downpayment.toFixed(2)).toLocaleString("en-US"));
    $('#summary-repayment-amount').text(parseFloat(repayment_amount.toFixed(2)).toLocaleString("en-US"));
    $('#summary-interest-rate').text(parseFloat(interest_rate.toFixed(2)).toLocaleString("en-US") + '%');
    $('#summary-outstanding-balance').text(parseFloat(outstanding_balance.toFixed(2)).toLocaleString("en-US"));
    $('#summary-sub-total').text(parseFloat(subtotal.toFixed(2)).toLocaleString("en-US"));
    $('#downpayment-percent').text(parseFloat(downpayment_percent.toFixed(2)).toLocaleString("en-US"));
}

function calculateRenewalAmount(){
    var product_type = $('#product_type').val();
    var product_category = $('#product_category').val();
   
    if(product_type == 'Refinancing'){
        var delivery_price = parseCurrency($('#insurance_coverage').val()) || 0;

        if(delivery_price > 0){
            var second_year_coverage = delivery_price * 0.9;
            var third_year_coverage = second_year_coverage * 0.9;
            var fourth_year_coverage = third_year_coverage * 0.9;
            
            if($('#compute_second_year').is(':checked')) {
                $('#insurance_coverage_second_year').val(parseCurrency(second_year_coverage.toFixed(2)).toLocaleString("en-US"));
                $('#summary-insurance-coverage-second-year').text(parseCurrency(second_year_coverage.toFixed(2)).toLocaleString("en-US"));
        
                var premium = Math.ceil((((second_year_coverage * 0.025) + 2700) * 1.2526) + 1300);
        
                $('#insurance_premium_second_year').val(parseCurrency(premium.toFixed(2)).toLocaleString("en-US"));
                $('#summary-insurance-premium-second-year').text(parseCurrency(premium.toFixed(2)).toLocaleString("en-US"));

                $('#insurance_premium_second_year').attr('readonly', true); 
                $('#insurance_coverage_second_year').attr('readonly', true); 
            }
            else{
                $('#insurance_coverage_second_year').val(0);
                $('#insurance_premium_second_year').val(0);
                
                $('#insurance_premium_second_year').attr('readonly', true); 
                $('#insurance_coverage_second_year').attr('readonly', true); 
            }
        
            if($('#compute_third_year').is(':checked')) {
                $('#insurance_coverage_third_year').val(parseCurrency(third_year_coverage.toFixed(2)).toLocaleString("en-US"));
                $('#summary-insurance-coverage-third-year').text(parseCurrency(third_year_coverage.toFixed(2)).toLocaleString("en-US"));
        
                var premium = Math.ceil((((third_year_coverage * 0.025) + 2700) * 1.2526) + 1300);
        
                    $('#insurance_premium_third_year').val(parseCurrency(premium.toFixed(2)).toLocaleString("en-US"));
                    $('#summary-insurance-premium-third-year').text(parseCurrency(premium.toFixed(2)).toLocaleString("en-US"));

                    $('#insurance_coverage_third_year').attr('readonly', true); 
                    $('#insurance_premium_third_year').attr('readonly', true);
            }
            else{
                $('#insurance_coverage_third_year').val(0);
                $('#insurance_premium_third_year').val(0);

                $('#insurance_coverage_third_year').attr('readonly', true); 
                $('#insurance_premium_third_year').attr('readonly', true);
            }
        
            if($('#compute_fourth_year').is(':checked')) {
                $('#insurance_coverage_fourth_year').val(parseCurrency(fourth_year_coverage.toFixed(2)).toLocaleString("en-US"));
                $('#summary-insurance-coverage-fourth-year').text(parseCurrency(fourth_year_coverage.toFixed(2)).toLocaleString("en-US"));
        
                var premium = Math.ceil((((fourth_year_coverage * 0.025) + 2700) * 1.2526) + 1300);
        
                    $('#insurance_premium_fourth_year').val(parseCurrency(premium.toFixed(2)).toLocaleString("en-US"));
                    $('#summary-insurance-premium-fourth-year').text(parseCurrency(premium.toFixed(2)).toLocaleString("en-US"));

                    $('#insurance_coverage_fourth_year').attr('readonly', true); 
                    $('#insurance_premium_fourth_year').attr('readonly', true);
            }
            else{
                $('#insurance_coverage_fourth_year').val(0);
                $('#insurance_premium_fourth_year').val(0);

                $('#insurance_coverage_fourth_year').attr('readonly', true); 
                $('#insurance_premium_fourth_year').attr('readonly', true);
            }
        }
        else{
            $('#insurance_coverage_second_year').val(0);
            $('#insurance_premium_second_year').val(0);
            $('#insurance_coverage_third_year').val(0);
            $('#insurance_premium_third_year').val(0);
            $('#insurance_coverage_fourth_year').val(0);
            $('#insurance_premium_fourth_year').val(0);
    
            $('#summary-insurance-coverage-second-year').text(0);
            $('#summary-insurance-premium-second-year').text(0);
            
            $('#summary-insurance-coverage-third-year').text(0);
            $('#summary-insurance-premium-third-year').text(0);
            
            $('#summary-insurance-coverage-fourth-year').text(0);
            $('#summary-insurance-premium-fourth-year').text(0);
        }
    }
    else if(product_type == 'Restructure'){
        if($('#compute_second_year').is(':checked')) {
            $('#insurance_premium_second_year').attr('readonly', false);
            $('#insurance_coverage_second_year').attr('readonly', false);
        }
        else{            
            $('#insurance_coverage_second_year').attr('readonly', true); 
            $('#insurance_premium_second_year').attr('readonly', true);
        }
    
        if($('#compute_third_year').is(':checked')) {
            $('#insurance_premium_third_year').attr('readonly', false); 
            $('#insurance_coverage_third_year').attr('readonly', false); 
        }
        else{
            $('#insurance_coverage_third_year').attr('readonly', true); 
            $('#insurance_premium_third_year').attr('readonly', true);
        }
    
        if($('#compute_fourth_year').is(':checked')) {
            $('#insurance_premium_fourth_year').attr('readonly', false); 
            $('#insurance_coverage_fourth_year').attr('readonly', false); 
        }
        else{            
            $('#insurance_coverage_fourth_year').attr('readonly', true); 
            $('#insurance_premium_fourth_year').attr('readonly', true);
        }
    }
    else{
        var delivery_price = parseCurrency($('#insurance_coverage').val()) || 0;

        if(delivery_price > 0){
            var second_year_coverage = delivery_price * 0.8;
            var third_year_coverage = second_year_coverage * 0.9;
            var fourth_year_coverage = third_year_coverage * 0.9;
            
            if($('#compute_second_year').is(':checked')) {
                $('#insurance_coverage_second_year').val(parseCurrency(second_year_coverage.toFixed(2)).toLocaleString("en-US"));
                $('#summary-insurance-coverage-second-year').text(parseCurrency(second_year_coverage.toFixed(2)).toLocaleString("en-US"));
        
                if(product_category == '1' || product_category == '3'){
                    var premium = Math.ceil((((second_year_coverage * 0.025) + 2700) * 1.2526) + 1300);
        
                    $('#insurance_premium_second_year').val(parseCurrency(premium.toFixed(2)).toLocaleString("en-US"));
                    $('#summary-insurance-premium-second-year').text(parseCurrency(premium.toFixed(2)).toLocaleString("en-US"));
                }
                else if(product_category == '2'){
                    var premium = Math.ceil((second_year_coverage * 0.025) * 1.2526);
        
                    $('#insurance_premium_second_year').val(parseCurrency(premium.toFixed(2)).toLocaleString("en-US"));
                    $('#summary-insurance-premium-second-year').text(parseCurrency(premium.toFixed(2)).toLocaleString("en-US"));
                }
                else{
                    $('#insurance_premium_second_year').val(0);
                    $('#insurance_premium_second_year').attr('readonly', false);
                    $('#insurance_coverage_second_year').attr('readonly', false);
                }
            }
            else{
                $('#insurance_coverage_second_year').val(0);
                $('#insurance_premium_second_year').val(0);
                
                $('#insurance_coverage_second_year').attr('readonly', true); 
                $('#insurance_premium_second_year').attr('readonly', true);
            }
        
            if($('#compute_third_year').is(':checked')) {
                $('#insurance_coverage_third_year').val(parseCurrency(third_year_coverage.toFixed(2)).toLocaleString("en-US"));
                $('#summary-insurance-coverage-third-year').text(parseCurrency(third_year_coverage.toFixed(2)).toLocaleString("en-US"));
        
                if(product_category == '1' || product_category == '3'){
                    var premium = Math.ceil((((third_year_coverage * 0.025) + 2700) * 1.2526) + 1300);
        
                    $('#insurance_premium_third_year').val(parseCurrency(premium.toFixed(2)).toLocaleString("en-US"));
                    $('#summary-insurance-premium-third-year').text(parseCurrency(premium.toFixed(2)).toLocaleString("en-US"));
                }
                else if(product_category == '2'){
                    var premium = Math.ceil((third_year_coverage * 0.025) * 1.2526);
        
                    $('#insurance_premium_third_year').val(parseCurrency(premium.toFixed(2)).toLocaleString("en-US"));
                    $('#summary-insurance-premium-third-year').text(parseCurrency(premium.toFixed(2)).toLocaleString("en-US"));
                }
                else{
                    $('#insurance_premium_third_year').val(0);
                    $('#insurance_premium_third_year').attr('readonly', false); 
                    $('#insurance_coverage_third_year').attr('readonly', false); 
                }
            }
            else{
                $('#insurance_coverage_third_year').val(0);
                $('#insurance_premium_third_year').val(0);

                $('#insurance_coverage_third_year').attr('readonly', true); 
                $('#insurance_premium_third_year').attr('readonly', true);
            }
        
            if($('#compute_fourth_year').is(':checked')) {
                $('#insurance_coverage_fourth_year').val(parseCurrency(fourth_year_coverage.toFixed(2)).toLocaleString("en-US"));
                $('#summary-insurance-coverage-fourth-year').text(parseCurrency(fourth_year_coverage.toFixed(2)).toLocaleString("en-US"));
        
                if(product_category == '1' || product_category == '3'){
                    var premium = Math.ceil((((fourth_year_coverage * 0.025) + 2700) * 1.2526) + 1300);
        
                    $('#insurance_premium_fourth_year').val(parseCurrency(premium.toFixed(2)).toLocaleString("en-US"));
                    $('#summary-insurance-premium-fourth-year').text(parseCurrency(premium.toFixed(2)).toLocaleString("en-US"));
                }
                else if(product_category == '2'){
                    var premium = Math.ceil((fourth_year_coverage * 0.025) * 1.2526);
        
                    $('#insurance_premium_fourth_year').val(parseCurrency(premium.toFixed(2)).toLocaleString("en-US"));
                    $('#summary-insurance-premium-fourth-year').text(parseCurrency(premium.toFixed(2)).toLocaleString("en-US"));
                }
                else{
                    $('#insurance_premium_fourth_year').val(0);
                    $('#insurance_premium_fourth_year').attr('readonly', false); 
                    $('#insurance_coverage_fourth_year').attr('readonly', false); 
                }
            }
            else{
                $('#insurance_coverage_fourth_year').val(0);
                $('#insurance_premium_fourth_year').val(0);
                
                $('#insurance_coverage_fourth_year').attr('readonly', true); 
                $('#insurance_premium_fourth_year').attr('readonly', true);
            }
        }
        else{
            $('#insurance_coverage_second_year').val(0);
            $('#insurance_premium_second_year').val(0);
            $('#insurance_coverage_third_year').val(0);
            $('#insurance_premium_third_year').val(0);
            $('#insurance_coverage_fourth_year').val(0);
            $('#insurance_premium_fourth_year').val(0);
    
            $('#summary-insurance-coverage-second-year').text(0);
            $('#summary-insurance-premium-second-year').text(0);
            
            $('#summary-insurance-coverage-third-year').text(0);
            $('#summary-insurance-premium-third-year').text(0);
            
            $('#summary-insurance-coverage-fourth-year').text(0);
            $('#summary-insurance-premium-fourth-year').text(0);
        }
    }
}

function calculateTotalOtherCharges(){
    var productType = $('#product_type').val();

    if(productType != 'Fuel' && productType != 'Parts' && productType != 'Repair' && productType != 'Rental'){
        var amount_financed = parseCurrency($("#amount_financed").val());
        var pn_amount = parseCurrency($("#pn_amount").val());
        var product_category = $('#product_category').val();
    
        var insurance_coverage = parseCurrency($("#insurance_coverage").val());
        
        if(product_category == '1' || product_category == '3' || productType == 'Brand New'){
            var insurance_premium = Math.ceil((((insurance_coverage * 0.025) + 2700) * 1.2526) + 1300);
        }
        else if(product_category == '2'){
            var insurance_premium = Math.ceil((insurance_coverage * 0.025) * 1.2526);
        }
        else{
           var insurance_premium = 0;
        }

        if(productType == 'Refinancing' || productType == 'Restructure'){
            var insurance_premium = parseCurrency($("#insurance_premium").val());
        }

        var handling_fee = (amount_financed * 0.035) + 6000;
        var transaction_fee = (amount_financed * 0.01) + 7000;
        var doc_stamp_tax = Math.ceil(((((amount_financed-5000)/5000)*20)+40)+(pn_amount/200*1.5));
    
        $("#insurance_premium").val(parseCurrency(insurance_premium.toFixed(2)).toLocaleString("en-US"));
        $("#handling_fee").val(parseCurrency(handling_fee.toFixed(2)).toLocaleString("en-US"));
        $("#transaction_fee").val(parseCurrency(transaction_fee.toFixed(2)).toLocaleString("en-US"));
        $("#doc_stamp_tax").val(parseCurrency(doc_stamp_tax.toFixed(2)).toLocaleString("en-US"));    
    
        var transfer_fee = parseCurrency($("#transfer_fee").val());
        var insurance_premium_discount = parseCurrency($("#insurance_premium_discount").val());
        var handling_fee_discount = parseCurrency($("#handling_fee_discount").val());
        var registration_fee = parseCurrency($("#registration_fee").val());
        var doc_stamp_tax_discount = parseCurrency($("#doc_stamp_tax_discount").val());
        var transaction_fee_discount = parseCurrency($("#transaction_fee_discount").val());
        var transfer_fee_discount = parseCurrency($("#transfer_fee_discount").val());
    
        var insurance_premium_subtotal = insurance_premium - insurance_premium_discount;
        var handling_fee_subtotal = handling_fee - handling_fee_discount;
        var doc_stamp_tax_subtotal = doc_stamp_tax - doc_stamp_tax_discount;
        var transaction_fee_subtotal = transaction_fee - transaction_fee_discount;
        var transfer_fee_subtotal = transfer_fee - transfer_fee_discount;
    
        $('#insurance_premium_subtotal').val(parseCurrency(insurance_premium_subtotal.toFixed(2)).toLocaleString("en-US"));
        $('#handling_fee_subtotal').val(handling_fee_subtotal.toFixed(2));
        $('#doc_stamp_tax_subtotal').val(doc_stamp_tax_subtotal.toFixed(2));
        $('#transaction_fee_subtotal').val(parseCurrency(transaction_fee_subtotal.toFixed(2)).toLocaleString("en-US"));
        $('#transfer_fee_subtotal').val(transfer_fee_subtotal.toFixed(2));
    
        var total = insurance_premium_subtotal + handling_fee_subtotal + transfer_fee_subtotal + registration_fee + doc_stamp_tax_subtotal + transaction_fee_subtotal;
    
        $('#total_other_charges').val(parseCurrency(total.toFixed(2)).toLocaleString("en-US"));
    
        $('#summary-insurance-coverage').text(parseCurrency($("#insurance_coverage").val()).toLocaleString("en-US"));
        $('#summary-insurance-premium').text(parseCurrency(insurance_premium_subtotal.toFixed(2)).toLocaleString("en-US"));
        $('#summary-handing-fee').text(parseCurrency(handling_fee_subtotal.toFixed(2)).toLocaleString("en-US"));
        $('#summary-transfer-fee').text(parseCurrency(transfer_fee_subtotal.toFixed(2)).toLocaleString("en-US"));
        $('#summary-registration-fee').text(parseCurrency(registration_fee.toFixed(2)).toLocaleString("en-US"));
        $('#summary-doc-stamp-tax').text(parseCurrency(doc_stamp_tax_subtotal.toFixed(2)).toLocaleString("en-US"));
        $('#summary-transaction-fee').text(parseCurrency(transaction_fee_subtotal.toFixed(2)).toLocaleString("en-US"));
        $('#summary-other-charges-total').text(parseCurrency(total.toFixed(2)).toLocaleString("en-US"));
    }
    else{
        $("#insurance_premium").val('0.00');
        $("#handling_fee").val('0.00');
        $("#transfer_fee").val('0.00');
        $("#transaction_fee").val('0.00');
        $("#doc_stamp_tax").val('0.00');

        $('#insurance_premium_subtotal').val('0.00');
        $('#handling_fee_subtotal').val('0.00');
        $('#doc_stamp_tax_subtotal').val('0.00');
        $('#transaction_fee_subtotal').val('0.00');
        $('#transfer_fee_subtotal').val('0.00');

        $('#summary-insurance-coverage').text('0.00');
        $('#summary-insurance-premium').text('0.00');
        $('#summary-handing-fee').text('0.00');
        $('#summary-transfer-fee').text('0.00');
        $('#summary-registration-fee').text('0.00');
        $('#summary-doc-stamp-tax').text('0.00');
        $('#summary-transaction-fee').text('0.00');
        $('#summary-other-charges-total').text('0.00');
    }    
}

function displayDetails(transaction){
    switch (transaction) {
        case 'get product details':
            var product_id;
    
            if($('#product_id_details').length){
                product_id = $('#product_id_details').text();
            }
            else{
                product_id = $('#product_id').val();
            }
    
            if(product_id != '' && product_id != 0){
                $.ajax({
                    url: 'controller/product-controller.php',
                    method: 'POST',
                    dataType: 'json',
                    data: {
                        product_id : product_id, 
                        transaction : transaction
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#delivery_price').val(parseCurrency((response.productPrice).toFixed(2)).toLocaleString("en-US"));
                            $('#old_color').val(response.colorName);
                            $('#old_body').val(response.bodyTypeName);
                            $('#old_engine').val(response.engineNumber);

                            $('#product_engine_number').text(response.engineNumber);
                            $('#product_chassis_number').text(response.chassisNumber);
                            $('#product_plate_number').text(response.plateNumber);
                            $('#product_category').val(response.productCategoryID);
                        } 
                        else {
                            if(response.isInactive){
                                window.location = 'logout.php?logout';
                            }
                            else{
                                showNotification('Get Product Details Error', response.message, 'danger');
                            }
                        }
                    },
                    error: function(xhr, status, error) {
                        var fullErrorMessage = 'XHR status: ${status}, Error: ${error}';
                        if (xhr.responseText) {
                            fullErrorMessage += ', Response: ${xhr.responseText}';
                        }
                        showErrorDialog(fullErrorMessage);
                    },
                    complete: function(){
                        calculateTotalDeliveryPrice();
                        calculateTotalOtherCharges();
                    }
                });
            }
            break;
    }
}