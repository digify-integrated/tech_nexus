(function($) {
    'use strict';

    $(function() {
        var sales_proposal_status = $('#sales_proposal_status').val();

        $('#v-pills-tab .nav-link').removeAttr('data-bs-toggle');
        
        if($('#sales-proposal-table').length){
            salesProposalTable('#sales-proposal-table');
        }

        if($('#all-sales-proposal-table').length){
            allSalesProposalTable('#all-sales-proposal-table');
        }

        if($('#sales-proposal-job-order-table').length){
            salesProposalJobOrderTable('#sales-proposal-job-order-table');
        }

        if($('#sales-proposal-ci-report-table').length){
            salesProposalCIReportTable('#sales-proposal-ci-report-table');
        }

        if($('#sales-proposal-deposit-amount-table').length){
            salesProposalDepositAmountTable('#sales-proposal-deposit-amount-table');
        }        

        if($('#sales-proposal-additional-job-order-table').length){
            salesProposalAdditionalJobOrderTable('#sales-proposal-additional-job-order-table');
        }

        if($('#summary-job-order-table').length){
            salesProposalSummaryJobOrderTable();
        }

        if($('#summary-amount-of-deposit-table').length){
            salesProposalSummaryDepositTable();
        }

        if($('#summary-additional-job-order-table').length){
            salesProposalSummaryAdditionalJobOrderTable();
        }

        if($('#sales-proposal-for-ci-table').length){
            salesProposalForCITable('#sales-proposal-for-ci-table');
        }

        if($('#sales-proposal-for-ci-evaluation-table').length){
            salesProposalForCIEvaluationTable('#sales-proposal-for-ci-evaluation-table');
        }

        if($('#schedule-of-payments-table').length){
            scheduleOfPaymentsTable('#schedule-of-payments-table');
        }

        if($('#installment-sales-approval-table').length){
            installmentSalesApprovalTable('#installment-sales-approval-table');
        }

        if($('#sales-proposal-for-bank-financing-table').length){
            salesProposalForBankFinancingTable('#sales-proposal-for-bank-financing-table');
        }

        if($('#disclosure-schedule').length){
            salesProposalDisclosureScheduleTable()
        }

        if($('#sales-proposal-change-request-table').length){
            salesProposalChangeRequestTable('#sales-proposal-change-request-table');
        }

        if($('#approved-sales-proposal-table').length){
            approvedSalesProposalTable('#approved-sales-proposal-table');
        }

        if($('#incoming-sales-proposal-table').length){
            incomingSalesProposalTable('#incoming-sales-proposal-table');
        }

        if($('#sales-proposal-for-dr-table').length){
            salesProposalForDRTable('#sales-proposal-for-dr-table');
        }

        if($('#sales-proposal-released-table').length){
            salesProposalReleasedTable('#sales-proposal-released-table', true);
        }

        if($('#sales-proposal-pdc-manual-input-table').length){
            salesProposalPDCManualInputTable('#sales-proposal-pdc-manual-input-table');
        }

        if(sales_proposal_status == 'Draft'){
            if($('#sales-proposal-form').length){
                salesProposalForm();
            }
    
            if($('#sales-proposal-unit-details-form').length){
                salesProposalUnitForm();
            }
    
            if($('#sales-proposal-fuel-details-form').length){
                salesProposalFuelForm();
            }
    
            if($('#sales-proposal-refinancing-details-form').length){
                salesProposalRefinancingForm();
            }
    
            if($('#sales-proposal-job-order-form').length){
                salesProposalJobOrderForm();
            }
    
            if($('#sales-proposal-pricing-computation-form').length){
                salesProposalPricingComputationForm();
            }
        }
        else{
            if($('#sales-proposal-form').length){
                disableFormAndSelect2('sales-proposal-form');
            }
    
            if($('#sales-proposal-unit-details-form').length){
                disableFormAndSelect2('sales-proposal-unit-details-form');
            }
    
            if($('#sales-proposal-fuel-details-form').length){
                disableFormAndSelect2('sales-proposal-fuel-details-form');
            }
    
            if($('#sales-proposal-refinancing-details-form').length){
                disableFormAndSelect2('sales-proposal-refinancing-details-form');
            }
    
            if($('#sales-proposal-job-order-form').length){
                disableFormAndSelect2('sales-proposal-job-order-form');
            }
    
            if($('#sales-proposal-pricing-computation-form').length){
                disableFormAndSelect2('sales-proposal-pricing-computation-form');
            }
        }

        if(sales_proposal_status == 'Draft' || sales_proposal_status == 'For DR'){
            if($('#sales-proposal-other-charges-form').length){
                salesProposalOtherChargesForm();
            }
    
            if($('#sales-proposal-deposit-amount-form').length){
                salesProposalDepositAmountForm();
            }
    
            if($('#sales-proposal-renewal-amount-form').length){
                salesProposalRenewalAmountForm();
            }
        }
        else{
            if($('#sales-proposal-other-charges-form').length){
                disableFormAndSelect2('sales-proposal-other-charges-form');
            }
    
            if($('#sales-proposal-deposit-amount-form').length){
                disableFormAndSelect2('sales-proposal-deposit-amount-form');
            }
    
            if($('#sales-proposal-renewal-amount-form').length){
                disableFormAndSelect2('sales-proposal-renewal-amount-form');
            }
        }
        
        if(sales_proposal_status == 'For DR'){
            if($('#sales-proposal-other-product-details-form').length){
                salesProposalOtherProductDetailsForm(); 
                displayDetails('get sales proposal other product details');   
            }
        }
        else{
            if($('#sales-proposal-other-product-details-form').length){
                disableFormAndSelect2('sales-proposal-other-product-details-form');
                displayDetails('get sales proposal other product details');   
            }
        }

        if($('#v-insurance-request').length){
            displayDetails('get sales proposal insurance request details');   
        }

        if($('#sales-proposal-additional-job-order-form').length){
            salesProposalAdditionalJobOrderForm();
        }

        if($('#sales-proposal-client-confirmation-form').length){
            salesProposalClientConfirmationForm();
        }

        if($('#sales-proposal-comaker-confirmation-form').length){
            salesProposalComakerConfirmationForm();
        }

        if($('#sales-proposal-engine-stencil-form').length){
            salesProposalEngineStencilForm();
        }

        if($('#sales-proposal-credit-advice-form').length){
            salesProposalCreditAdviceForm();
        }

        if($('#sales-proposal-final-approval-form').length){
            salesProposalFinalApprovalForm();
        }

        if($('#sales-proposal-initial-approval-form').length){
            salesProposalInitalApprovalForm();
        }
        
        if($('#sales-proposal-reject-form').length){
            salesProposalRejectForm();
        }

        if($('#approve-installment-sales-form').length){
            installmentSalesApprovalForm();
        }
        
        if($('#sales-proposal-cancel-form').length){
            salesProposalCancelForm();
        }
        
        if($('#sales-proposal-ci-recommendation-form').length){
            salesProposalCIRecommendationForm();
        }
        
        if($('#sales-proposal-set-to-draft-form').length){
            salesProposalSetToDraftForm();
        }
        
        if($('#sales-proposal-other-document-form').length){
            salesProposalOtherDocumentForm();
        }

        if($('#sales-proposal-quality-control-form').length){
            salesProposalQualityControlForm();
        }
        
        if($('#sales-proposal-outgoing-checklist-form').length){
            salesProposalOutgoingChecklistForm();
        }
        
        if($('#sales-proposal-unit-image-form').length){
            salesProposalUnitImageForm();
        }

        if($('#sales-proposal-job-order-confirmation-form').length){
            salesProposalAdditionalJobOrderConfirmationImageForm();
        }
        
        if($('#sales-proposal-pdc-manual-input-form').length){
            salesProposalPDCManualInputForm();
        }

        if($('#sales-proposal-tag-as-released-form').length){
            salesProposalReleaseForm();
        }

        $(document).on('change','#product_type',function() {
            var productType = $(this).val();

            if(productType == 'Brand New'){
                $('#sales-proposal-tab-4').text('Brand New Details');
            }
            else if(productType == 'Refinancing'){
                $('#sales-proposal-tab-4').text('Refinancing Details');
            }
            else if(productType == 'Restructure'){
                $('#sales-proposal-tab-4').text('Restructure Details');
            }

            if($('#sales-proposal-tab-2').length && $('#sales-proposal-tab-3').length && $('#sales-proposal-tab-4').length){
                $('#sales-proposal-tab-2, #sales-proposal-tab-3, #sales-proposal-tab-4').addClass('d-none');

                if (productType === 'Unit' || productType === 'Rental' || productType === 'Consignment') {
                    $('#sales-proposal-tab-2').removeClass('d-none');
                    resetModalForm('sales-proposal-unit-details-form');
                    displayDetails('get sales proposal unit details');
                }
               else if (productType === 'Fuel') {
                    $('#sales-proposal-tab-3').removeClass('d-none');
                   // resetModalForm('sales-proposal-fuel-details-form');
                    displayDetails('get sales proposal fuel details');
                }
                else if (productType === 'Refinancing' || productType === 'Restructure' || productType === 'Brand New') {
                    $('#sales-proposal-tab-4').removeClass('d-none');
                    resetModalForm('sales-proposal-refinancing-details-form');
                    displayDetails('get sales proposal refinancing details');
                }
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

        $(document).on('change','#comaker_id',function() {
            displayDetails('get comaker details');
        });

        $(document).on('change','#additional_maker_id',function() {
            displayDetails('get additional maker details');
        });

        $(document).on('change','#comaker_id2',function() {
            displayDetails('get comaker2 details');
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

        $(document).on('change','#term_length',function() {
            calculatePaymentNumber();
        });

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

        $(document).on('change','#commission_amount',function() {
            $('#summary-commission').text(encryptCommission($(this).val()));
        });

        $(document).on('change','#referred_by',function() {
            $('#summary-referred-by').text($(this).val());
        });

        $(document).on('change','#product_type',function() {
            $('#summary-product-type').text($(this).val());
        });

        $(document).on('change','#transaction_type',function() {
            $('#summary-transaction-type').text($(this).val());

            if($(this).val() == 'Bank Financing'){
                $('#term_length').val('45');
                checkOptionExist('#payment_frequency', 'Lumpsum', '');
                checkOptionExist('#term_type', 'Days', '');
            }
        });

        $(document).on('change','#term_length',function() {
            var term_type = $('#term_type').val();

            $('#summary-term').text($(this).val() + ' ' + term_type);
        });

        $(document).on('change','#term_type',function() {
            var term_length = $('#term_length').val();

            $('#summary-term').text(term_length + ' ' + $(this).val());
        });

        $(document).on('change','#number_of_payments',function() {
            $('#summary-no-payments').text($(this).val());
        });

        $(document).on('change','#remarks',function() {
            $('#summary-remarks').text($(this).val());
        });

        $(document).on('change','#new_color',function() {
            $('#summary-new-color').text($(this).val());
        });

        $(document).on('change','#new_body',function() {
            $('#summary-new-body').text($(this).val());
        });

        $(document).on('change','#new_engine',function() {
            $('#summary-new-engine').text($(this).val());
        });

        $(document).on('change','#diesel_fuel_quantity',function() {
            $('#summary-diesel-fuel-quantity').text($(this).val() + ' lt');
        });

        $(document).on('change','#regular_fuel_quantity',function() {
            $('#summary-regular-fuel-quantity').text($(this).val() + ' lt');
        });

        $(document).on('change','#premium_fuel_quantity',function() {
            $('#summary-premium-fuel-quantity').text($(this).val() + ' lt');
        });

        //-----------------------------------------------------------------------



        $(document).on('click','#sales-proposal-final-approval',function() {
            resetModalForm("sales-proposal-final-approval-form");
        });

        $(document).on('click','#sales-proposal-reject',function() {
            resetModalForm("sales-proposal-reject-form");
        });

        $(document).on('click','#sales-proposal-cancel',function() {
            resetModalForm("sales-proposal-cancel-form");
        });

        $(document).on('click','#sales-proposal-set-to-draft',function() {
            resetModalForm("sales-proposal-set-to-draft-form");
        });

        $(document).on('click', '#next-step', function() {
            var $this = $(this);
            $this.prop('disabled', true);
            setTimeout(function() {
                $this.prop('disabled', false);
            }, 800);
            traverseTabs('next');
        });
        
        $(document).on('click', '#previous-step', function() {
            var $this = $(this);
            $this.prop('disabled', true);
            setTimeout(function() {
                $this.prop('disabled', false);
            }, 800);
            traverseTabs('previous');
        });
        
        $(document).on('click', '#first-step', function() {
            var $this = $(this);
            $this.prop('disabled', true);
            setTimeout(function() {
                $this.prop('disabled', false);
            }, 800);
            traverseTabs('first');
        });
        
        $(document).on('click', '#last-step', function() {
            var $this = $(this);
            $this.prop('disabled', true);
            setTimeout(function() {
                $this.prop('disabled', false);
            }, 800);
            traverseTabs('last');
        });
        
        $(document).on('click', '#last-step2', function() {
            var $this = $(this);
            $this.prop('disabled', true);
            setTimeout(function() {
                $this.prop('disabled', false);
            }, 800);
            traverseTabs('last');
        });

        if(sales_proposal_status != 'Draft'){
            if($('#last-step').length){
                $("#last-step")[0].click();
            }
        }

        $(document).on('click','#add-sales-proposal-job-order',function() {
            resetModalForm("sales-proposal-job-order-form");
            $('#job_order_type').val('add');

        });

        $(document).on('click','.update-sales-proposal-job-order',function() {
            const sales_proposal_job_order_id = $(this).data('sales-proposal-job-order-id');
    
            sessionStorage.setItem('sales_proposal_job_order_id', sales_proposal_job_order_id);
            
            displayDetails('get sales proposal job order details');
            
            $('#job_order_type').val('update');
        });

        $(document).on('click','.delete-sales-proposal-job-order',function() {
            const sales_proposal_job_order_id = $(this).data('sales-proposal-job-order-id');
            const transaction = 'delete sales proposal job order';
    
            Swal.fire({
                title: 'Confirm Job Order Deletion',
                text: 'Are you sure you want to delete this job order?',
                icon: 'warning',
                showCancelButton: !0,
                confirmButtonText: 'Delete',
                cancelButtonText: 'Cancel',
                confirmButtonClass: 'btn btn-danger mt-2',
                cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                buttonsStyling: !1
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        type: 'POST',
                        url: 'controller/sales-proposal-controller.php',
                        dataType: 'json',
                        data: {
                            sales_proposal_job_order_id : sales_proposal_job_order_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete Job Order Success', 'The job order has been deleted successfully.', 'success');
                                reloadDatatable('#sales-proposal-job-order-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    window.location = '404.php';
                                }
                                else {
                                    showNotification('Delete Job Order Error', response.message, 'danger');
                                }
                            }
                        },
                        error: function(xhr, status, error) {
                            var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                            if (xhr.responseText) {
                                fullErrorMessage += `, Response: ${xhr.responseText}`;
                            }
                            showErrorDialog(fullErrorMessage);
                        }
                    });
                    return false;
                }
            });
        });

        $(document).on('click','#add-sales-proposal-deposit-amount',function() {
            resetModalForm("sales-proposal-deposit-amount-form");
        });

        $(document).on('click','.update-sales-proposal-deposit-amount',function() {
            const sales_proposal_deposit_amount_id = $(this).data('sales-proposal-deposit-amount-id');
    
            sessionStorage.setItem('sales_proposal_deposit_amount_id', sales_proposal_deposit_amount_id);
            
            displayDetails('get sales proposal deposit amount details');
        });

        $(document).on('click','.delete-sales-proposal-deposit-amount',function() {
            const sales_proposal_deposit_amount_id = $(this).data('sales-proposal-deposit-amount-id');
            const transaction = 'delete sales proposal deposit amount';
    
            Swal.fire({
                title: 'Confirm Deposit Amount Deletion',
                text: 'Are you sure you want to delete this deposit amount?',
                icon: 'warning',
                showCancelButton: !0,
                confirmButtonText: 'Delete',
                cancelButtonText: 'Cancel',
                confirmButtonClass: 'btn btn-danger mt-2',
                cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                buttonsStyling: !1
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        type: 'POST',
                        url: 'controller/sales-proposal-controller.php',
                        dataType: 'json',
                        data: {
                            sales_proposal_deposit_amount_id : sales_proposal_deposit_amount_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete Amount of Deposit Success', 'The amount of deposit has been deleted successfully.', 'success');
                                reloadDatatable('#sales-proposal-deposit-amount-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    window.location = '404.php';
                                }
                                else {
                                    showNotification('Delete Amount of Deposit Error', response.message, 'danger');
                                }
                            }
                        },
                        error: function(xhr, status, error) {
                            var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                            if (xhr.responseText) {
                                fullErrorMessage += `, Response: ${xhr.responseText}`;
                            }
                            showErrorDialog(fullErrorMessage);
                        }
                    });
                    return false;
                }
            });
        });

        $(document).on('click','#add-sales-proposal-additional-job-order',function() {
            resetModalForm("sales-proposal-additional-job-order-form");
        });

        $(document).on('click','.update-sales-proposal-additional-job-order',function() {
            const sales_proposal_additional_job_order_id = $(this).data('sales-proposal-additional-job-order-id');
    
            sessionStorage.setItem('sales_proposal_additional_job_order_id', sales_proposal_additional_job_order_id);
            
            displayDetails('get sales proposal additional job order details');
        });

        $(document).on('click','.delete-sales-proposal-additional-job-order',function() {
            const sales_proposal_additional_job_order_id = $(this).data('sales-proposal-additional-job-order-id');
            const transaction = 'delete sales proposal additional job order';
    
            Swal.fire({
                title: 'Confirm Additional Job Order Deletion',
                text: 'Are you sure you want to delete this additional job order?',
                icon: 'warning',
                showCancelButton: !0,
                confirmButtonText: 'Delete',
                cancelButtonText: 'Cancel',
                confirmButtonClass: 'btn btn-danger mt-2',
                cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                buttonsStyling: !1
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        type: 'POST',
                        url: 'controller/sales-proposal-controller.php',
                        dataType: 'json',
                        data: {
                            sales_proposal_additional_job_order_id : sales_proposal_additional_job_order_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete Additional Job Order Success', 'The additional job order has been deleted successfully.', 'success');
                                reloadDatatable('#sales-proposal-additional-job-order-table');
                                displayDetails('get sales proposal additional job order total details');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    window.location = '404.php';
                                }
                                else {
                                    showNotification('Delete Additional Job Order Error', response.message, 'danger');
                                }
                            }
                        },
                        error: function(xhr, status, error) {
                            var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                            if (xhr.responseText) {
                                fullErrorMessage += `, Response: ${xhr.responseText}`;
                            }
                            showErrorDialog(fullErrorMessage);
                        }
                    });
                    return false;
                }
            });
        });

        $(document).on('click','#tag-for-initial-approval',function() {
            const sales_proposal_id = $('#sales-proposal-id').text();
            const transaction = 'tag for initial approval';
    
            Swal.fire({
                title: 'Confirm Tagging of Sales Proposal For Initial Approval',
                text: 'Are you sure you want to tag this sales proposal for initial approval?',
                icon: 'warning',
                showCancelButton: !0,
                confirmButtonText: 'For Initial Approval',
                cancelButtonText: 'Cancel',
                confirmButtonClass: 'btn btn-warning mt-2',
                cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                buttonsStyling: !1
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        type: 'POST',
                        url: 'controller/sales-proposal-controller.php',
                        dataType: 'json',
                        data: {
                            sales_proposal_id : sales_proposal_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Tag Sales Proposal For Initial Approval Success', 'The sales proposal has been tagged for initial approval successfully.', 'success');
                                window.location.reload();
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                        else if (response.emptyStencil) {
                            showNotification('For Initial Approval Error', 'Please upload the new engine stencil first.', 'danger');
                        } 
                                else if (response.notExist) {
                                    window.location = '404.php';
                                }
                                else {
                                    showNotification('Tag Sales Proposal For Initial Approval Error', response.message, 'danger');
                                }
                            }
                        },
                        error: function(xhr, status, error) {
                            var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                            if (xhr.responseText) {
                                fullErrorMessage += `, Response: ${xhr.responseText}`;
                            }
                            showErrorDialog(fullErrorMessage);
                        }
                    });
                    return false;
                }
            });
        });

        $(document).on('click','#tag-for-review',function() {
            const sales_proposal_id = $('#sales-proposal-id').text();
            const transaction = 'tag for review';
    
            Swal.fire({
                title: 'Confirm Tagging of Sales Proposal For Review',
                text: 'Are you sure you want to tag this sales proposal for review?',
                icon: 'warning',
                showCancelButton: !0,
                confirmButtonText: 'For Review',
                cancelButtonText: 'Cancel',
                confirmButtonClass: 'btn btn-warning mt-2',
                cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                buttonsStyling: !1
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        type: 'POST',
                        url: 'controller/sales-proposal-controller.php',
                        dataType: 'json',
                        data: {
                            sales_proposal_id : sales_proposal_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Tag Sales Proposal For Review Success', 'The sales proposal has been tagged for review successfully.', 'success');
                                window.location.reload();
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.emptyStencil) {
                                    showNotification('For Review Error', 'Please upload the new engine stencil first.', 'danger');
                                } 
                                else if (response.zeroBalance) {
                                    showNotification('For Review Error', 'The outstanding balance cannot be zero.', 'danger');
                                } 
                                else if (response.clientConfirmation) {
                                    showNotification('For Review Error', 'Please upload the client confirmation first.', 'danger');
                                } 
                                else if (response.comakerConfirmation) {
                                    showNotification('For Review Error', 'Please upload the comaker confirmation first.', 'danger');
                                } 
                                else if (response.comakerRelation) {
                                    showNotification('For Review Error', 'Please update the comaker relation first.', 'danger');
                                } 
                                else if (response.notExist) {
                                    window.location = '404.php';
                                }
                                else {
                                    showNotification('Tag Sales Proposal For Initial Approval Error', response.message, 'danger');
                                }
                            }
                        },
                        error: function(xhr, status, error) {
                            var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                            if (xhr.responseText) {
                                fullErrorMessage += `, Response: ${xhr.responseText}`;
                            }
                            showErrorDialog(fullErrorMessage);
                        }
                    });
                    return false;
                }
            });
        });

        $(document).on('click','#for-ci-sales-proposal',function() {
            const sales_proposal_id = $('#sales-proposal-id').text();
            const transaction = 'tag for ci';
    
            Swal.fire({
                title: 'Confirm Tagging of Sales Proposal For Credit Investigation',
                text: 'Are you sure you want to tag this sales proposal for credit investigation?',
                icon: 'info',
                showCancelButton: !0,
                confirmButtonText: 'For CI',
                cancelButtonText: 'Cancel',
                confirmButtonClass: 'btn btn-info mt-2',
                cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                buttonsStyling: !1
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        type: 'POST',
                        url: 'controller/sales-proposal-controller.php',
                        dataType: 'json',
                        data: {
                            sales_proposal_id : sales_proposal_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Tag Sales Proposal For Credit Investigation Success', 'The sales proposal has been tagged for credit investigation successfully.', 'success');
                                window.location.reload();
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    window.location = '404.php';
                                }
                                else {
                                    showNotification('Tag Sales Proposal For Credit Investigation Error', response.message, 'danger');
                                }
                            }
                        },
                        error: function(xhr, status, error) {
                            var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                            if (xhr.responseText) {
                                fullErrorMessage += `, Response: ${xhr.responseText}`;
                            }
                            showErrorDialog(fullErrorMessage);
                        }
                    });
                    return false;
                }
            });
        });

        $(document).on('click','#complete-ci',function() {
            const sales_proposal_id = $('#sales-proposal-id').text();
            const transaction = 'complete ci';
    
            Swal.fire({
                title: 'Confirm Tagging of Credit Investigation As Complete',
                text: 'Are you sure you want to tag the credit investigation as complete?',
                icon: 'info',
                showCancelButton: !0,
                confirmButtonText: 'Complete',
                cancelButtonText: 'Cancel',
                confirmButtonClass: 'btn btn-info mt-2',
                cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                buttonsStyling: !1
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        type: 'POST',
                        url: 'controller/sales-proposal-controller.php',
                        dataType: 'json',
                        data: {
                            sales_proposal_id : sales_proposal_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Tag Credit Investigation As Complete Success', 'The credit investigation has been tagged as complete successfully.', 'success');
                                window.location.reload();
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    window.location = '404.php';
                                }
                                else {
                                    showNotification('Tag Credit Investigation As Complete Error', response.message, 'danger');
                                }
                            }
                        },
                        error: function(xhr, status, error) {
                            var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                            if (xhr.responseText) {
                                fullErrorMessage += `, Response: ${xhr.responseText}`;
                            }
                            showErrorDialog(fullErrorMessage);
                        }
                    });
                    return false;
                }
            });
        });

        $(document).on('click','#sales-proposal-change-request',function() {
            const sales_proposal_id = $('#sales-proposal-id').text();
            const transaction = 'tag change request as complete';
    
            Swal.fire({
                title: 'Confirm Tagging of Change Request As Complete',
                text: 'Are you sure you want to tag this change request as complete?',
                icon: 'warning',
                showCancelButton: !0,
                confirmButtonText: 'Complete',
                cancelButtonText: 'Cancel',
                confirmButtonClass: 'btn btn-warning mt-2',
                cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                buttonsStyling: !1
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        type: 'POST',
                        url: 'controller/sales-proposal-controller.php',
                        dataType: 'json',
                        data: {
                            sales_proposal_id : sales_proposal_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Tag Change Request As Complete Success', 'The change request has been tagged as complete successfully.', 'success');
                                window.location.reload();
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    window.location = '404.php';
                                }
                                else {
                                    showNotification('Tag Change Request As Complete Error', response.message, 'danger');
                                }
                            }
                        },
                        error: function(xhr, status, error) {
                            var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                            if (xhr.responseText) {
                                fullErrorMessage += `, Response: ${xhr.responseText}`;
                            }
                            showErrorDialog(fullErrorMessage);
                        }
                    });
                    return false;
                }
            });
        });

        $(document).on('click','#on-process-sales-proposal',function() {
            const sales_proposal_id = $('#sales-proposal-id').text();
            const transaction = 'tag on process';
    
            Swal.fire({
                title: 'Confirm Tagging of Sales Proposal On-Process',
                text: 'Are you sure you want to tag this sales proposal on-process?',
                icon: 'info',
                showCancelButton: !0,
                confirmButtonText: 'On Process',
                cancelButtonText: 'Cancel',
                confirmButtonClass: 'btn btn-info mt-2',
                cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                buttonsStyling: !1
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        type: 'POST',
                        url: 'controller/sales-proposal-controller.php',
                        dataType: 'json',
                        data: {
                            sales_proposal_id : sales_proposal_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Tag Sales Proposal On-Process Success', 'The sales proposal has been tagged on-process successfully.', 'success');
                                window.location.reload();
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    window.location = '404.php';
                                }
                                else {
                                    showNotification('Tag Sales Proposal On-Process Error', response.message, 'danger');
                                }
                            }
                        },
                        error: function(xhr, status, error) {
                            var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                            if (xhr.responseText) {
                                fullErrorMessage += `, Response: ${xhr.responseText}`;
                            }
                            showErrorDialog(fullErrorMessage);
                        }
                    });
                    return false;
                }
            });
        });

        $(document).on('click','#ready-for-release-sales-proposal',function() {
            const sales_proposal_id = $('#sales-proposal-id').text();
            const transaction = 'tag ready for release';
    
            Swal.fire({
                title: 'Confirm Tagging of Sales Proposal Ready For Release',
                text: 'Are you sure you want to tag this sales proposal ready for release?',
                icon: 'info',
                showCancelButton: !0,
                confirmButtonText: 'Ready For Release',
                cancelButtonText: 'Cancel',
                confirmButtonClass: 'btn btn-success mt-2',
                cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                buttonsStyling: !1
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        type: 'POST',
                        url: 'controller/sales-proposal-controller.php',
                        dataType: 'json',
                        data: {
                            sales_proposal_id : sales_proposal_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Tag Sales Proposal Ready For Release Success', 'The sales proposal has been tagged ready for release successfully.', 'success');
                                window.location.reload();
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    window.location = '404.php';
                                }
                                else {
                                    showNotification('Tag Sales Proposal Ready For Release Error', response.message, 'danger');
                                }
                            }
                        },
                        error: function(xhr, status, error) {
                            var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                            if (xhr.responseText) {
                                fullErrorMessage += `, Response: ${xhr.responseText}`;
                            }
                            showErrorDialog(fullErrorMessage);
                        }
                    });
                    return false;
                }
            });
        });

        $(document).on('click','#for-dr-sales-proposal',function() {
            const sales_proposal_id = $('#sales-proposal-id').text();
            const transaction = 'tag for DR';
    
            Swal.fire({
                title: 'Confirm Tagging of Sales Proposal For DR',
                text: 'Are you sure you want to tag this sales proposal for DR?',
                icon: 'info',
                showCancelButton: !0,
                confirmButtonText: 'For DR',
                cancelButtonText: 'Cancel',
                confirmButtonClass: 'btn btn-success mt-2',
                cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                buttonsStyling: !1
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        type: 'POST',
                        url: 'controller/sales-proposal-controller.php',
                        dataType: 'json',
                        data: {
                            sales_proposal_id : sales_proposal_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Tag Sales Proposal For DR Success', 'The sales proposal has been tagged for DR successfully.', 'success');
                                window.location.reload();
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    window.location = '404.php';
                                }
                                else {
                                    showNotification('Tag Sales Proposal For DR Error', response.message, 'danger');
                                }
                            }
                        },
                        error: function(xhr, status, error) {
                            var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                            if (xhr.responseText) {
                                fullErrorMessage += `, Response: ${xhr.responseText}`;
                            }
                            showErrorDialog(fullErrorMessage);
                        }
                    });
                    return false;
                }
            });
        });

        $(document).on('click','.delete-sales-proposal-manual-pdc-input',function() {
            const sales_proposal_manual_pdc_input_id = $(this).data('sales-proposal-manual-pdc-input-id');
            const transaction = 'delete sales proposal pdc manual input';
    
            Swal.fire({
                title: 'Confirm PDC Manual Input Deletion',
                text: 'Are you sure you want to delete this pdc manual input?',
                icon: 'warning',
                showCancelButton: !0,
                confirmButtonText: 'Delete',
                cancelButtonText: 'Cancel',
                confirmButtonClass: 'btn btn-danger mt-2',
                cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                buttonsStyling: !1
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        type: 'POST',
                        url: 'controller/sales-proposal-controller.php',
                        dataType: 'json',
                        data: {
                            sales_proposal_manual_pdc_input_id : sales_proposal_manual_pdc_input_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete PDC Manual Input Success', 'The PDC manula input has been deleted successfully.', 'success');
                                reloadDatatable('#sales-proposal-pdc-manual-input-table');
                                salesProposalSummaryPDCManualInputTable();
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    window.location = '404.php';
                                }
                                else {
                                    showNotification('Delete PDC Manual Input Error', response.message, 'danger');
                                }
                            }
                        },
                        error: function(xhr, status, error) {
                            var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                            if (xhr.responseText) {
                                fullErrorMessage += `, Response: ${xhr.responseText}`;
                            }
                            showErrorDialog(fullErrorMessage);
                        }
                    });
                    return false;
                }
            });
        });

        if($('#other-charges-rows').length){
            salesProposalSummaryPDCManualInputTable();    
        }

        if($('#sales-proposal-id').length){
            displayDetails('get sales proposal basic details');
            displayDetails('get sales proposal pricing computation details');
            displayDetails('get sales proposal confirmation details');
            displayDetails('get sales proposal renewal amount details');
            displayDetails('get sales proposal other charges details');
        }

        $(document).on('click','#apply-filter',function() {
            if($('#sales-proposal-table').length){
                salesProposalTable('#sales-proposal-table');
            }
    
            if($('#all-sales-proposal-table').length){
                allSalesProposalTable('#all-sales-proposal-table');
            }
    
            if($('#sales-proposal-released-table').length){
                salesProposalReleasedTable('#sales-proposal-released-table', true);
            }
    
            if($('#schedule-of-payments-table').length){
                scheduleOfPaymentsTable('#schedule-of-payments-table');
            }
        });

       $('#print').on('click', function () {
            $('#pricing-computation-block, #amortization-block, #remarks-block').removeClass('dontprint');
            window.print();
        });

        $('#print2').on('click', function () {
            $('#pricing-computation-block, #amortization-block, #remarks-block').addClass('dontprint');
            window.print();
        });

    });
})(jQuery);

function salesProposalTable(datatable_name, buttons = false, show_all = false){
    const type = 'sales proposal table';
    var customer_id = $('#customer_id').val();
    var sales_proposal_status_filter = $('.sales-proposal-status-filter:checked').val();

    var settings;

    const column = [ 
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'SALES_PROPOSAL_NUMBER' },
        { 'data' : 'CREATED_DATE' },
        { 'data' : 'CUSTOMER' },
        { 'data' : 'PRODUCT_TYPE' },
        { 'data' : 'PRODUCT' },
        { 'data' : 'STATUS' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '1%','bSortable': false, 'aTargets': 0 },
        { 'width': 'auto', 'aTargets': 1 },
        { 'width': 'auto', 'type': 'date', 'aTargets': 2 },
        { 'width': 'auto', 'aTargets': 3 },
        { 'width': 'auto', 'aTargets': 4 },
        { 'width': 'auto', 'aTargets': 5 },
        { 'width': 'auto', 'aTargets': 6 },
        { 'width': '10%','bSortable': false, 'aTargets': 7 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_sales_proposal_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {'type' : type, 'customer_id' : customer_id, 'sales_proposal_status_filter' : sales_proposal_status_filter},
            'dataSrc' : '',
            'error': function(xhr, status, error) {
                var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                if (xhr.responseText) {
                    fullErrorMessage += `, Response: ${xhr.responseText}`;
                }
                showErrorDialog(fullErrorMessage);
            }
        },
        'order': [[ 2, 'desc' ]],
        'columns' : column,
        'columnDefs': column_definition,
        'lengthMenu': length_menu,
        'language': {
            'emptyTable': 'No data found',
            'searchPlaceholder': 'Search...',
            'search': '',
            'loadingRecords': 'Just a moment while we fetch your data...'
        }
    }

    if (buttons) {
        settings.dom = "<'row'<'col-sm-3'l><'col-sm-6 text-center mb-2'B><'col-sm-3'f>>" +  "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-5'i><'col-sm-7'p>>";
        settings.buttons = ['csv', 'excel', 'pdf'];
    }

    destroyDatatable(datatable_name);

    $(datatable_name).dataTable(settings);
}

function allSalesProposalTable(datatable_name, buttons = false, show_all = false){
    const type = 'all sales proposal table';

    var sale_proposal_status_filter = [];
    var product_type_filter = [];
    var company_filter = [];
    var user_filter = [];

    $('.sales-proposal-status-filter:checked').each(function() {
        sale_proposal_status_filter.push($(this).val());
    });

    $('.product-type-filter:checked').each(function() {
        product_type_filter.push($(this).val());
    });

    $('.company-filter:checked').each(function() {
        company_filter.push($(this).val());
    });

    $('.user-filter:checked').each(function() {
        user_filter.push($(this).val());
    });

    var filter_sale_proposal_status = sale_proposal_status_filter.join(', ');
    var filter_product_type = product_type_filter.join(', ');
    var filter_company = company_filter.join(', ');
    var filter_user = user_filter.join(', ');

    
    var filter_created_date_start_date = $('#filter_created_date_start_date').val();
    var filter_created_date_end_date = $('#filter_created_date_end_date').val();
    var filter_released_date_start_date = $('#filter_released_date_start_date').val();
    var filter_released_date_end_date = $('#filter_released_date_end_date').val();

    var settings;

    const column = [ 
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'CUSTOMER' },
        { 'data' : 'PRODUCT' },
        { 'data' : 'STATUS' },
        { 'data' : 'SALES_PROPOSAL_NUMBER' },
        { 'data' : 'PRODUCT_TYPE' },
        { 'data' : 'CREATED_DATE' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '1%','bSortable': false, 'aTargets': 0 },
        { 'width': '14%', 'aTargets': 1 },
        { 'width': '15%', 'aTargets': 2 },
        { 'width': '10%', 'aTargets': 3 },
        { 'width': '15%', 'aTargets': 4 },
        { 'width': '10%', 'aTargets': 5},
        { 'width': '15%', 'type': 'date', 'aTargets': 6 },
        { 'width': '10%','bSortable': false, 'aTargets': 7 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_sales_proposal_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {
                'type' : type,
                'filter_sale_proposal_status' : filter_sale_proposal_status,
                'filter_product_type' : filter_product_type,
                'filter_company' : filter_company,
                'filter_user' : filter_user,
                'filter_created_date_start_date' : filter_created_date_start_date,
                'filter_created_date_end_date' : filter_created_date_end_date,
                'filter_released_date_start_date' : filter_released_date_start_date,
                'filter_released_date_end_date' : filter_released_date_end_date,
            },
            'dataSrc' : '',
            'error': function(xhr, status, error) {
                var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                if (xhr.responseText) {
                    fullErrorMessage += `, Response: ${xhr.responseText}`;
                }
                showErrorDialog(fullErrorMessage);
            }
        },
        'order': [[ 6, 'desc' ]],
        'columns' : column,
        'columnDefs': column_definition,
        'lengthMenu': length_menu,
        'searching': true, // Enable searching
        'search': {
            'search': '',
            'placeholder': 'Search...', // Placeholder text for the search input
            'position': 'top', // Position the search bar to the left
        },
        'language': {
            'emptyTable': 'No data found',
            'searchPlaceholder': 'Search...',
            'search': '',
            'loadingRecords': 'Just a moment while we fetch your data...'
        }
    }

    if (buttons) {
        settings.dom = "<'row'<'col-sm-3'l><'col-sm-6 text-center mb-2'B><'col-sm-3'f>>" +  "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-5'i><'col-sm-7'p>>";
        settings.buttons = ['csv', 'excel', 'pdf'];
    }

    destroyDatatable(datatable_name);

    $(datatable_name).dataTable(settings);
}

function salesProposalJobOrderTable(datatable_name, buttons = false, show_all = false){
    const sales_proposal_id = $('#sales-proposal-id').text();
    const type = 'sales proposal job order table';
    var settings;

    const column = [ 
        { 'data' : 'JOB_ORDER' },
        { 'data' : 'COST' },
        { 'data' : 'PROGRESS' },
        { 'data' : 'APPROVAL_DOCUMENT' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '42%', 'aTargets': 0 },
        { 'width': '14%', 'aTargets': 1 },
        { 'width': '14%', 'aTargets': 2 },
        { 'width': '14%', 'aTargets': 3 },
        { 'width': '16%','bSortable': false, 'aTargets': 4 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_sales_proposal_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {'type' : type, 'sales_proposal_id' : sales_proposal_id},
            'dataSrc' : '',
            'error': function(xhr, status, error) {
                var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                if (xhr.responseText) {
                    fullErrorMessage += `, Response: ${xhr.responseText}`;
                }
                showErrorDialog(fullErrorMessage);
            }
        },
        'order': [[ 0, 'asc' ]],
        'columns' : column,
        'columnDefs': column_definition,
        'lengthMenu': length_menu,
        'language': {
            'emptyTable': 'No data found',
            'searchPlaceholder': 'Search...',
            'search': '',
            'loadingRecords': 'Just a moment while we fetch your data...'
        }
    }

    if (buttons) {
        settings.dom = "<'row'<'col-sm-3'l><'col-sm-6 text-center mb-2'B><'col-sm-3'f>>" +  "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-5'i><'col-sm-7'p>>";
        settings.buttons = ['csv', 'excel', 'pdf'];
    }

    destroyDatatable(datatable_name);

    $(datatable_name).dataTable(settings);
}

function salesProposalCIReportTable(datatable_name, buttons = false, show_all = false){
    const sales_proposal_id = $('#sales-proposal-id').text();
    const type = 'sales proposal ci report table';
    var settings;

    const column = [ 
        { 'data' : 'CUSTOMER' },
        { 'data' : 'APPRAISER' },
        { 'data' : 'INVESTIGATOR' },
        { 'data' : 'STATUS' },
        { 'data' : 'DATE_STARTED' },
        { 'data' : 'RELEASED_DATE' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': 'auto', 'aTargets': 0 },
        { 'width': 'auto', 'aTargets': 1 },
        { 'width': 'auto', 'aTargets': 2 },
        { 'width': 'auto', 'aTargets': 3 },
        { 'width': 'auto', 'type': 'date', 'aTargets': 4 },
        { 'width': 'auto', 'type': 'date', 'aTargets': 5 },
        { 'width': '10%','bSortable': false, 'aTargets': 6 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_ci_report_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {'type' : type, 'sales_proposal_id' : sales_proposal_id},
            'dataSrc' : '',
            'error': function(xhr, status, error) {
                var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                if (xhr.responseText) {
                    fullErrorMessage += `, Response: ${xhr.responseText}`;
                }
                showErrorDialog(fullErrorMessage);
            }
        },
        'order': [[ 0, 'asc' ]],
        'columns' : column,
        'columnDefs': column_definition,
        'lengthMenu': length_menu,
        'language': {
            'emptyTable': 'No data found',
            'searchPlaceholder': 'Search...',
            'search': '',
            'loadingRecords': 'Just a moment while we fetch your data...'
        }
    }

    if (buttons) {
        settings.dom = "<'row'<'col-sm-3'l><'col-sm-6 text-center mb-2'B><'col-sm-3'f>>" +  "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-5'i><'col-sm-7'p>>";
        settings.buttons = ['csv', 'excel', 'pdf'];
    }

    destroyDatatable(datatable_name);

    $(datatable_name).dataTable(settings);
}

function salesProposalDepositAmountTable(datatable_name, buttons = false, show_all = false){
    const sales_proposal_id = $('#sales-proposal-id').text();
    const type = 'sales proposal deposit amount table';
    var settings;

    const column = [ 
        { 'data' : 'DEPOSIT_DATE' },
        { 'data' : 'REFERENCE_NUMBER' },
        { 'data' : 'DEPOSIT_AMOUNT' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '25%', 'type': 'date', 'aTargets': 0 },
        { 'width': '30%', 'aTargets': 1 },
        { 'width': '30%', 'aTargets': 2 },
        { 'width': '15%','bSortable': false, 'aTargets': 3 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_sales_proposal_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {'type' : type, 'sales_proposal_id' : sales_proposal_id},
            'dataSrc' : '',
            'error': function(xhr, status, error) {
                var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                if (xhr.responseText) {
                    fullErrorMessage += `, Response: ${xhr.responseText}`;
                }
                showErrorDialog(fullErrorMessage);
            }
        },
        'order': [[ 0, 'asc' ]],
        'columns' : column,
        'columnDefs': column_definition,
        'lengthMenu': length_menu,
        'language': {
            'emptyTable': 'No data found',
            'searchPlaceholder': 'Search...',
            'search': '',
            'loadingRecords': 'Just a moment while we fetch your data...'
        }
    }

    if (buttons) {
        settings.dom = "<'row'<'col-sm-3'l><'col-sm-6 text-center mb-2'B><'col-sm-3'f>>" +  "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-5'i><'col-sm-7'p>>";
        settings.buttons = ['csv', 'excel', 'pdf'];
    }

    destroyDatatable(datatable_name);

    $(datatable_name).dataTable(settings);
}

function salesProposalAdditionalJobOrderTable(datatable_name, buttons = false, show_all = false){
    const sales_proposal_id = $('#sales-proposal-id').text();
    const type = 'sales proposal additional job order table';
    var settings;

    const column = [ 
        { 'data' : 'JOB_ORDER_NUMBER' },
        { 'data' : 'JOB_ORDER_DATE' },
        { 'data' : 'PARTICULARS' },
        { 'data' : 'COST' },
        { 'data' : 'PROGRESS' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '25%', 'aTargets': 0 },
        { 'width': '15%', 'type': 'date', 'aTargets': 1 },
        { 'width': '15%', 'aTargets': 2 },
        { 'width': '15%', 'aTargets': 3 },
        { 'width': '15%', 'aTargets': 4 },
        { 'width': '15%','bSortable': false, 'aTargets': 5 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_sales_proposal_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {'type' : type, 'sales_proposal_id' : sales_proposal_id},
            'dataSrc' : '',
            'error': function(xhr, status, error) {
                var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                if (xhr.responseText) {
                    fullErrorMessage += `, Response: ${xhr.responseText}`;
                }
                showErrorDialog(fullErrorMessage);
            }
        },
        'order': [[ 0, 'asc' ]],
        'columns' : column,
        'columnDefs': column_definition,
        'lengthMenu': length_menu,
        'language': {
            'emptyTable': 'No data found',
            'searchPlaceholder': 'Search...',
            'search': '',
            'loadingRecords': 'Just a moment while we fetch your data...'
        }
    }

    if (buttons) {
        settings.dom = "<'row'<'col-sm-3'l><'col-sm-6 text-center mb-2'B><'col-sm-3'f>>" +  "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-5'i><'col-sm-7'p>>";
        settings.buttons = ['csv', 'excel', 'pdf'];
    }

    destroyDatatable(datatable_name);

    $(datatable_name).dataTable(settings);
}

function salesProposalSummaryDepositTable(){
    const sales_proposal_id = $('#sales-proposal-id').text();
    const type = 'summary deposit amount table';

    $.ajax({
        type: "POST",
        url: "view/_sales_proposal_generation.php",
        dataType: 'json',
        data: { type: type, sales_proposal_id: sales_proposal_id },
        success: function (result) {
            if($('#summary-amount-of-deposit-table').length){
                document.getElementById('summary-amount-of-deposit-table').innerHTML = result[0].table;
            }
        }
    });
}

function salesProposalDisclosureScheduleTable(){
    const sales_proposal_id = $('#sales-proposal-id').text();
    const type = 'summary disclosure schedule table';

    $.ajax({
        type: "POST",
        url: "view/_sales_proposal_generation.php",
        dataType: 'json',
        data: { type: type, sales_proposal_id: sales_proposal_id },
        success: function (result) {
            if($('#disclosure-schedule').length){
                document.getElementById('disclosure-schedule').innerHTML = result[0].table;
            }
        }
    });
}

function salesProposalSummaryAdditionalJobOrderTable(){
    const sales_proposal_id = $('#sales-proposal-id').text();
    const type = 'summary additional job order table';

    $.ajax({
        type: "POST",
        url: "view/_sales_proposal_generation.php",
        dataType: 'json',
        data: { type: type, sales_proposal_id: sales_proposal_id },
        success: function (result) {
            if($('#summary-additional-job-order-table').length){
                document.getElementById('summary-additional-job-order-table').innerHTML = result[0].table;
            }
        }
    });
}

function salesProposalSummaryJobOrderTable(){
    const sales_proposal_id = $('#sales-proposal-id').text();
    const type = 'summary job order table';

    $.ajax({
        type: "POST",
        url: "view/_sales_proposal_generation.php",
        dataType: 'json',
        data: { type: type, sales_proposal_id: sales_proposal_id },
        success: function (result) {
            if($('#summary-job-order-table').length){
                document.getElementById('summary-job-order-table').innerHTML = result[0].table;
            }
        }
    });
}

function salesProposalForCITable(datatable_name, buttons = false, show_all = false){
    const type = 'sales proposal for ci table';

    var settings;

    const column = [ 
        { 'data' : 'SALES_PROPOSAL_NUMBER' },
        { 'data' : 'CUSTOMER' },
        { 'data' : 'PRODUCT_TYPE' },
        { 'data' : 'PRODUCT' },
        { 'data' : 'FOR_CI_DATE' },
        { 'data' : 'STATUS' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '15%', 'aTargets': 0 },
        { 'width': '15%', 'aTargets': 1 },
        { 'width': '15%', 'aTargets': 2 },
        { 'width': '25%', 'aTargets': 3 },
        { 'width': '25%', 'type': 'date', 'aTargets': 4 },
        { 'width': '10%', 'aTargets': 5 },
        { 'width': '10%','bSortable': false, 'aTargets': 6 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_sales_proposal_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {'type' : type},
            'dataSrc' : '',
            'error': function(xhr, status, error) {
                var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                if (xhr.responseText) {
                    fullErrorMessage += `, Response: ${xhr.responseText}`;
                }
                showErrorDialog(fullErrorMessage);
            }
        },
        'order': [[ 4, 'desc' ]],
        'columns' : column,
        'columnDefs': column_definition,
        'lengthMenu': length_menu,
        'language': {
            'emptyTable': 'No data found',
            'searchPlaceholder': 'Search...',
            'search': '',
            'loadingRecords': 'Just a moment while we fetch your data...'
        }
    };

    if (buttons) {
        settings.dom = "<'row'<'col-sm-3'l><'col-sm-6 text-center mb-2'B><'col-sm-3'f>>" +  "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-5'i><'col-sm-7'p>>";
        settings.buttons = ['csv', 'excel', 'pdf'];
    }

    destroyDatatable(datatable_name);

    $(datatable_name).dataTable(settings);
}

function salesProposalForCIEvaluationTable(datatable_name, buttons = false, show_all = false){
    const type = 'sales proposal for ci evaluation table';

    var settings;

    const column = [ 
        { 'data' : 'SALES_PROPOSAL_NUMBER' },
        { 'data' : 'CUSTOMER' },
        { 'data' : 'PRODUCT_TYPE' },
        { 'data' : 'PRODUCT' },
        { 'data' : 'FOR_CI_DATE' },
        { 'data' : 'STATUS' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '15%', 'aTargets': 0 },
        { 'width': '15%', 'aTargets': 1 },
        { 'width': '15%', 'aTargets': 2 },
        { 'width': '25%', 'aTargets': 3 },
        { 'width': '25%', 'type': 'date', 'aTargets': 4 },
        { 'width': '10%', 'aTargets': 5 },
        { 'width': '10%','bSortable': false, 'aTargets': 6 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_sales_proposal_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {'type' : type},
            'dataSrc' : '',
            'error': function(xhr, status, error) {
                var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                if (xhr.responseText) {
                    fullErrorMessage += `, Response: ${xhr.responseText}`;
                }
                showErrorDialog(fullErrorMessage);
            }
        },
        'order': [[ 4, 'desc' ]],
        'columns' : column,
        'columnDefs': column_definition,
        'lengthMenu': length_menu,
        'language': {
            'emptyTable': 'No data found',
            'searchPlaceholder': 'Search...',
            'search': '',
            'loadingRecords': 'Just a moment while we fetch your data...'
        }
    };

    if (buttons) {
        settings.dom = "<'row'<'col-sm-3'l><'col-sm-6 text-center mb-2'B><'col-sm-3'f>>" +  "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-5'i><'col-sm-7'p>>";
        settings.buttons = ['csv', 'excel', 'pdf'];
    }

    destroyDatatable(datatable_name);

    $(datatable_name).dataTable(settings);
}

function installmentSalesApprovalTable(datatable_name, buttons = false, show_all = false){
    const type = 'installment sales approval table';

    var settings;

    const column = [ 
        { 'data' : 'SALES_PROPOSAL_NUMBER' },
        { 'data' : 'CUSTOMER' },
        { 'data' : 'PRODUCT_TYPE' },
        { 'data' : 'PRODUCT' },
        { 'data' : 'FOR_CI_DATE' },
        { 'data' : 'STATUS' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '15%', 'aTargets': 0 },
        { 'width': '15%', 'aTargets': 1 },
        { 'width': '15%', 'aTargets': 2 },
        { 'width': '25%', 'aTargets': 3 },
        { 'width': '25%', 'type': 'date', 'aTargets': 4 },
        { 'width': '10%', 'aTargets': 5 },
        { 'width': '10%','bSortable': false, 'aTargets': 6 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_sales_proposal_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {'type' : type},
            'dataSrc' : '',
            'error': function(xhr, status, error) {
                var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                if (xhr.responseText) {
                    fullErrorMessage += `, Response: ${xhr.responseText}`;
                }
                showErrorDialog(fullErrorMessage);
            }
        },
        'order': [[ 4, 'desc' ]],
        'columns' : column,
        'columnDefs': column_definition,
        'lengthMenu': length_menu,
        'language': {
            'emptyTable': 'No data found',
            'searchPlaceholder': 'Search...',
            'search': '',
            'loadingRecords': 'Just a moment while we fetch your data...'
        }
    };

    if (buttons) {
        settings.dom = "<'row'<'col-sm-3'l><'col-sm-6 text-center mb-2'B><'col-sm-3'f>>" +  "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-5'i><'col-sm-7'p>>";
        settings.buttons = ['csv', 'excel', 'pdf'];
    }

    destroyDatatable(datatable_name);

    $(datatable_name).dataTable(settings);
}

function scheduleOfPaymentsTable(datatable_name, buttons = false, show_all = false){
    const type = 'schedule of payments table';
    var filter_release_date_start_date = $('#filter_release_date_start_date').val();
    var filter_release_date_end_date = $('#filter_release_date_end_date').val();

    var settings;

    const column = [ 
        { 'data' : 'NUMBER' },
        { 'data' : 'SALES_PROPOSAL_NUMBER' },
        { 'data' : 'RELEASE_DATE' },
        { 'data' : 'DUE_DATE' },
        { 'data' : 'AMOUNT_DUE' }
    ];

    const column_definition = [
        { 'width': '5%', 'aTargets': 0 },
        { 'width': 'auto', 'aTargets': 1 },
        { 'width': 'auto', 'type': 'date', 'aTargets': 2 },
        { 'width': 'auto', 'type': 'date', 'aTargets': 3 },
        { 'width': 'auto', 'aTargets': 4 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[-1], ['All']];

    settings = {
        'ajax': { 
            'url' : 'view/_sales_proposal_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {
                'type' : type, 
                'filter_release_date_start_date' : filter_release_date_start_date,
                'filter_release_date_end_date' : filter_release_date_end_date
            },
            'dataSrc' : '',
            'error': function(xhr, status, error) {
                var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                if (xhr.responseText) {
                    fullErrorMessage += `, Response: ${xhr.responseText}`;
                }
                showErrorDialog(fullErrorMessage);
            }
        },
        'order': [[ 1, 'asc' ]],
        'columns' : column,
        'columnDefs': column_definition,
        'lengthMenu': length_menu,
        'language': {
            'emptyTable': 'No data found',
            'searchPlaceholder': 'Search...',
            'search': '',
            'loadingRecords': 'Just a moment while we fetch your data...'
        }
    };

    if (buttons) {
        settings.dom = "<'row'<'col-sm-6'l><'col-sm-6'B>>" + "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-5'i><'col-sm-7'p>>";
        settings.buttons = [
            {
                extend: "copyHtml5",
                exportOptions: {
                    columns: [0, ":visible"],
                },
            },
            {
                extend: "excelHtml5",
                exportOptions: {
                    columns: ":visible",
                },
            },
            {
                extend: "pdfHtml5",
                exportOptions: {
                    columns: ":visible",
                },
            },
            "colvis",
        ];
    }

    destroyDatatable(datatable_name);

    $(datatable_name).dataTable(settings);
}

function salesProposalForBankFinancingTable(datatable_name, buttons = false, show_all = false){
    const type = 'sales proposal for bank financing table';

    var settings;

    const column = [ 
        { 'data' : 'SALES_PROPOSAL_NUMBER' },
        { 'data' : 'CUSTOMER' },
        { 'data' : 'PRODUCT_TYPE' },
        { 'data' : 'PRODUCT' },
        { 'data' : 'FOR_CI_DATE' },
        { 'data' : 'STATUS' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '15%', 'aTargets': 0 },
        { 'width': '15%', 'aTargets': 1 },
        { 'width': '15%', 'aTargets': 2 },
        { 'width': '25%', 'aTargets': 3 },
        { 'width': '25%', 'type': 'date', 'aTargets': 4 },
        { 'width': '10%', 'aTargets': 5 },
        { 'width': '10%','bSortable': false, 'aTargets': 6 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_sales_proposal_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {'type' : type},
            'dataSrc' : '',
            'error': function(xhr, status, error) {
                var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                if (xhr.responseText) {
                    fullErrorMessage += `, Response: ${xhr.responseText}`;
                }
                showErrorDialog(fullErrorMessage);
            }
        },
        'order': [[ 4, 'desc' ]],
        'columns' : column,
        'columnDefs': column_definition,
        'lengthMenu': length_menu,
        'language': {
            'emptyTable': 'No data found',
            'searchPlaceholder': 'Search...',
            'search': '',
            'loadingRecords': 'Just a moment while we fetch your data...'
        }
    };

    if (buttons) {
        settings.dom = "<'row'<'col-sm-3'l><'col-sm-6 text-center mb-2'B><'col-sm-3'f>>" +  "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-5'i><'col-sm-7'p>>";
        settings.buttons = ['csv', 'excel', 'pdf'];
    }

    destroyDatatable(datatable_name);

    $(datatable_name).dataTable(settings);
}

function salesProposalChangeRequestTable(datatable_name, buttons = false, show_all = false){
    const type = 'sales proposal change request table';

    var settings;

    const column = [ 
        { 'data' : 'SALES_PROPOSAL_NUMBER' },
        { 'data' : 'CUSTOMER' },
        { 'data' : 'PRODUCT_TYPE' },
        { 'data' : 'PRODUCT' },
        { 'data' : 'PROCEED_DATE' },
        { 'data' : 'STATUS' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '15%', 'aTargets': 0 },
        { 'width': '15%', 'aTargets': 1 },
        { 'width': '15%', 'aTargets': 2 },
        { 'width': '25%', 'aTargets': 3 },
        { 'width': '25%', 'type': 'date', 'aTargets': 4 },
        { 'width': '10%', 'aTargets': 5 },
        { 'width': '10%','bSortable': false, 'aTargets': 6 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_sales_proposal_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {'type' : type},
            'dataSrc' : '',
            'error': function(xhr, status, error) {
                var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                if (xhr.responseText) {
                    fullErrorMessage += `, Response: ${xhr.responseText}`;
                }
                showErrorDialog(fullErrorMessage);
            }
        },
        'order': [[ 4, 'desc' ]],
        'columns' : column,
        'columnDefs': column_definition,
        'lengthMenu': length_menu,
        'language': {
            'emptyTable': 'No data found',
            'searchPlaceholder': 'Search...',
            'search': '',
            'loadingRecords': 'Just a moment while we fetch your data...'
        }
    };

    if (buttons) {
        settings.dom = "<'row'<'col-sm-3'l><'col-sm-6 text-center mb-2'B><'col-sm-3'f>>" +  "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-5'i><'col-sm-7'p>>";
        settings.buttons = ['csv', 'excel', 'pdf'];
    }

    destroyDatatable(datatable_name);

    $(datatable_name).dataTable(settings);
}

function approvedSalesProposalTable(datatable_name, buttons = false, show_all = false){
    const type = 'approved sales proposal table';

    var settings;

    const column = [ 
        { 'data' : 'SALES_PROPOSAL_NUMBER' },
        { 'data' : 'CUSTOMER' },
        { 'data' : 'PRODUCT_TYPE' },
        { 'data' : 'PRODUCT' },
        { 'data' : 'PROCEED_DATE' },
        { 'data' : 'PROGRESS' },
        { 'data' : 'STATUS' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '15%', 'aTargets': 0 },
        { 'width': '15%', 'aTargets': 1 },
        { 'width': '15%', 'aTargets': 2 },
        { 'width': '25%', 'aTargets': 3 },
        { 'width': '15%', 'type': 'date', 'aTargets': 4 },
        { 'width': '10%', 'aTargets': 5 },
        { 'width': '10%', 'aTargets': 6 },
        { 'width': '10%','bSortable': false, 'aTargets': 7 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_sales_proposal_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {'type' : type},
            'dataSrc' : '',
            'error': function(xhr, status, error) {
                var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                if (xhr.responseText) {
                    fullErrorMessage += `, Response: ${xhr.responseText}`;
                }
                showErrorDialog(fullErrorMessage);
            }
        },
        'order': [[ 4, 'desc' ]],
        'columns' : column,
        'columnDefs': column_definition,
        'lengthMenu': length_menu,
        'language': {
            'emptyTable': 'No data found',
            'searchPlaceholder': 'Search...',
            'search': '',
            'loadingRecords': 'Just a moment while we fetch your data...'
        }
    };

    if (buttons) {
        settings.dom = "<'row'<'col-sm-3'l><'col-sm-6 text-center mb-2'B><'col-sm-3'f>>" +  "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-5'i><'col-sm-7'p>>";
        settings.buttons = ['csv', 'excel', 'pdf'];
    }

    destroyDatatable(datatable_name);

    $(datatable_name).dataTable(settings);
}

function incomingSalesProposalTable(datatable_name, buttons = false, show_all = false){
    const type = 'incoming sales proposal table';

    var settings;

    const column = [ 
        { 'data' : 'SALES_PROPOSAL_NUMBER' },
        { 'data' : 'CUSTOMER' },
        { 'data' : 'PRODUCT_TYPE' },
        { 'data' : 'PRODUCT' },
        { 'data' : 'PROCEED_DATE' },
        { 'data' : 'STATUS' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '15%', 'aTargets': 0 },
        { 'width': '15%', 'aTargets': 1 },
        { 'width': '15%', 'aTargets': 2 },
        { 'width': '25%', 'aTargets': 3 },
        { 'width': '25%', 'type': 'date', 'aTargets': 4 },
        { 'width': '10%', 'aTargets': 5 },
        { 'width': '10%','bSortable': false, 'aTargets': 6 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_sales_proposal_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {'type' : type},
            'dataSrc' : '',
            'error': function(xhr, status, error) {
                var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                if (xhr.responseText) {
                    fullErrorMessage += `, Response: ${xhr.responseText}`;
                }
                showErrorDialog(fullErrorMessage);
            }
        },
        'order': [[ 4, 'desc' ]],
        'columns' : column,
        'columnDefs': column_definition,
        'lengthMenu': length_menu,
        'language': {
            'emptyTable': 'No data found',
            'searchPlaceholder': 'Search...',
            'search': '',
            'loadingRecords': 'Just a moment while we fetch your data...'
        }
    };

    if (buttons) {
        settings.dom = "<'row'<'col-sm-3'l><'col-sm-6 text-center mb-2'B><'col-sm-3'f>>" +  "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-5'i><'col-sm-7'p>>";
        settings.buttons = ['csv', 'excel', 'pdf'];
    }

    destroyDatatable(datatable_name);

    $(datatable_name).dataTable(settings);
}

function salesProposalForDRTable(datatable_name, buttons = false, show_all = false){
    const type = 'sales proposal for dr table';

    var settings;

    const column = [ 
        { 'data' : 'SALES_PROPOSAL_NUMBER' },
        { 'data' : 'CUSTOMER' },
        { 'data' : 'PRODUCT_TYPE' },
        { 'data' : 'PRODUCT' },
        { 'data' : 'FOR_DR_DATE' },
        { 'data' : 'STATUS' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '15%', 'aTargets': 0 },
        { 'width': '15%', 'aTargets': 1 },
        { 'width': '15%', 'aTargets': 2 },
        { 'width': '25%', 'aTargets': 3 },
        { 'width': '25%', 'type': 'date', 'aTargets': 4 },
        { 'width': '10%', 'aTargets': 5 },
        { 'width': '10%','bSortable': false, 'aTargets': 6 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_sales_proposal_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {'type' : type},
            'dataSrc' : '',
            'error': function(xhr, status, error) {
                var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                if (xhr.responseText) {
                    fullErrorMessage += `, Response: ${xhr.responseText}`;
                }
                showErrorDialog(fullErrorMessage);
            }
        },
        'order': [[ 4, 'desc' ]],
        'columns' : column,
        'columnDefs': column_definition,
        'lengthMenu': length_menu,
        'language': {
            'emptyTable': 'No data found',
            'searchPlaceholder': 'Search...',
            'search': '',
            'loadingRecords': 'Just a moment while we fetch your data...'
        }
    };

    if (buttons) {
        settings.dom = "<'row'<'col-sm-3'l><'col-sm-6 text-center mb-2'B><'col-sm-3'f>>" +  "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-5'i><'col-sm-7'p>>";
        settings.buttons = ['csv', 'excel', 'pdf'];
    }

    destroyDatatable(datatable_name);

    $(datatable_name).dataTable(settings);
}

function salesProposalReleasedTable(datatable_name, buttons = false, show_all = false){
    const type = 'sales proposal released table';
    var filter_release_date_start_date = $('#filter_release_date_start_date').val();
    var filter_release_date_end_date = $('#filter_release_date_end_date').val();

    var settings;

    const column = [ 
        { 'data' : 'SALES_PROPOSAL_NUMBER' },
        { 'data' : 'CUSTOMER' },
        { 'data' : 'PRODUCT_TYPE' },
        { 'data' : 'PRODUCT' },
        { 'data' : 'RELEASED_DATE' },
        { 'data' : 'STATUS' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '15%', 'aTargets': 0 },
        { 'width': '15%', 'aTargets': 1 },
        { 'width': '15%', 'aTargets': 2 },
        { 'width': '25%', 'aTargets': 3 },
        { 'width': '25%', 'type': 'date', 'aTargets': 4 },
        { 'width': '10%', 'aTargets': 5 },
        { 'width': '10%','bSortable': false, 'aTargets': 6 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_sales_proposal_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {
                'type' : type, 
                'filter_release_date_start_date' : filter_release_date_start_date,
                'filter_release_date_end_date' : filter_release_date_end_date
            },
            'dataSrc' : '',
            'error': function(xhr, status, error) {
                var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                if (xhr.responseText) {
                    fullErrorMessage += `, Response: ${xhr.responseText}`;
                }
                showErrorDialog(fullErrorMessage);
            }
        },
        'order': [[ 1, 'desc' ]],
        'columns' : column,
        'columnDefs': column_definition,
        'lengthMenu': length_menu,
        'searching': true, // Enable searching
        'search': {
            'search': '',
            'placeholder': 'Search...', // Placeholder text for the search input
            'position': 'top', // Position the search bar to the left
        },
        'language': {
            'emptyTable': 'No data found',
            'searchPlaceholder': 'Search...',
            'search': '',
            'loadingRecords': 'Just a moment while we fetch your data...'
        }
    }

    if (buttons) {
        settings.dom = "Bfrtip";
        settings.buttons = ['csv', 'excel', 'pdf'];
    }

    destroyDatatable(datatable_name);

    $(datatable_name).dataTable(settings);
}

function salesProposalPDCManualInputTable(datatable_name, buttons = false, show_all = false){
    const sales_proposal_id = $('#sales-proposal-id').text();
    const type = 'sales proposal pdc manual input table';
    var settings;

    const column = [ 
        { 'data' : 'ACCOUNT_NUMBER' },
        { 'data' : 'BANK_BRANCH' },
        { 'data' : 'CHECK_DATE' },
        { 'data' : 'CHECK_NUMBER' },
        { 'data' : 'PAYMENT_FOR' },
        { 'data' : 'GROSS_AMOUNT' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '14.16%', 'aTargets': 0 },
        { 'width': '14.16%', 'aTargets': 1 },
        { 'width': '14.16%', 'type': 'date', 'aTargets': 2 },
        { 'width': '14.16%', 'aTargets': 3 },
        { 'width': '14.16%', 'aTargets': 4 },
        { 'width': '15%','bSortable': false, 'aTargets': 5 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_sales_proposal_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {'type' : type, 'sales_proposal_id' : sales_proposal_id},
            'dataSrc' : '',
            'error': function(xhr, status, error) {
                var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                if (xhr.responseText) {
                    fullErrorMessage += `, Response: ${xhr.responseText}`;
                }
                showErrorDialog(fullErrorMessage);
            }
        },
        'order': [[ 0, 'asc' ]],
        'columns' : column,
        'columnDefs': column_definition,
        'lengthMenu': length_menu,
        'language': {
            'emptyTable': 'No data found',
            'searchPlaceholder': 'Search...',
            'search': '',
            'loadingRecords': 'Just a moment while we fetch your data...'
        }
    };

    if (buttons) {
        settings.dom = "<'row'<'col-sm-3'l><'col-sm-6 text-center mb-2'B><'col-sm-3'f>>" +  "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-5'i><'col-sm-7'p>>";
        settings.buttons = ['csv', 'excel', 'pdf'];
    }

    destroyDatatable(datatable_name);

    $(datatable_name).dataTable(settings);
}

function salesProposalSummaryPDCManualInputTable(){
    const sales_proposal_id = $('#sales-proposal-id').text();
    const type = 'summary pdc manual input table';

    $.ajax({
        type: "POST",
        url: "view/_sales_proposal_generation.php",
        dataType: 'json',
        data: { type: type, sales_proposal_id: sales_proposal_id },
        success: function (result) {
            if($('#other-charges-rows').length){
                document.getElementById('other-charges-rows').innerHTML = result[0].table;
            }
        }
    });
}

function salesProposalForm(){
    $('#sales-proposal-form').validate({
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
                },
                complete: function() {
                    //displayDetails('get sales proposal basic details');
                    //displayDetails('get sales proposal unit details');
                    //displayDetails('get sales proposal fuel details');
                    //displayDetails('get sales proposal refinancing details');
                    //displayDetails('get sales proposal pricing computation details');
                }
            });
        
            return false;
        }
    });
}

function salesProposalUnitForm(){
    $('#sales-proposal-unit-details-form').validate({
        rules: {
            product_id: {
                required: true
            },
            new_color: {
                required: true
            },
            new_body: {
                required: true
            },
            for_change_engine: {
                required: true
            },
            new_engine: {
                required: {
                    depends: function(element) {
                        return $("select[name='for_change_engine']").val() === 'Yes';
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
            const transaction = 'save sales proposal unit';
        
            $.ajax({
                type: 'POST',
                url: 'controller/sales-proposal-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&sales_proposal_id=' + sales_proposal_id + '&customer_id=' + customer_id,
                dataType: 'json',
                success: function (response) {
                    if (!response.success) {
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
                },
                complete: function() {
                   //displayDetails('get sales proposal unit details');
                }
            });
        
            return false;
        }
    });
}

function salesProposalFuelForm(){
    $('#sales-proposal-fuel-details-form').validate({
        rules: {
            diesel_fuel_quantity: {
                required: true
            },
            diesel_price_per_liter: {
                required: true
            },
            regular_fuel_quantity: {
                required: true
            },
            regular_price_per_liter: {
                required: true
            },
            premium_fuel_quantity: {
                required: true
            },
            premium_price_per_liter: {
                required: true
            },
        },
        messages: {
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
            const transaction = 'save sales proposal fuel';
        
            $.ajax({
                type: 'POST',
                url: 'controller/sales-proposal-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&sales_proposal_id=' + sales_proposal_id + '&customer_id=' + customer_id,
                dataType: 'json',
                success: function (response) {
                    if (!response.success) {
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

function salesProposalRefinancingForm(){
    $('#sales-proposal-refinancing-details-form').validate({
        rules: {
            ref_engine_no: {
                required: true
            },
            ref_chassis_no: {
                required: true
            },
            ref_plate_no: {
                required: true
            },
        },
        messages: {
            ref_engine_no: {
                required: 'Please enter the engine number'
            },
            ref_chassis_no: {
                required: 'Please enter the chassis number'
            },
            ref_plate_no: {
                required: 'Please enter the plate number'
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
            const transaction = 'save sales proposal refinancing';
        
            $.ajax({
                type: 'POST',
                url: 'controller/sales-proposal-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&sales_proposal_id=' + sales_proposal_id + '&customer_id=' + customer_id,
                dataType: 'json',
                success: function (response) {
                    if (!response.success) {
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
                },
                complete: function() {
                    //displayDetails('get sales proposal refinancing details');
                }
            });
        
            return false;
        }
    });
}

function salesProposalJobOrderForm(){
    $('#sales-proposal-job-order-form').validate({
        rules: {
            job_order: {
                required: true
            },
            job_order_cost: {
                required: true
            },
            approval_document: {
                required: function(element) {
                    return $('#job_order_type').val() === 'add';
                }
            },
        },
        messages: {
            job_order: {
                required: 'Please enter the job order'
            },
            job_order_cost: {
                required: 'Please enter the cost'
            },
            approval_document: {
                required: 'Please choose the approval document'
            },
        },
        errorPlacement: function (error, element) {
            if (element.hasClass('select2') || element.hasClass('modal-select2') || element.hasClass('offcanvas-select2')) {
              error.insertAfter(element.next('.select2-container'));
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
            const transaction = 'save sales proposal job order';

            var formData = new FormData(form);
            formData.append('sales_proposal_id', sales_proposal_id);
            formData.append('transaction', transaction);

            $.ajax({
                type: 'POST',
                url: 'controller/sales-proposal-controller.php',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-sales-proposal-job-order');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert Job Order Success' : 'Update Job Order Success';
                        const notificationDescription = response.insertRecord ? 'The job order has been inserted successfully.' : 'The job order has been updated successfully.';
                        
                        showNotification(notificationMessage, notificationDescription, 'success');
                    }
                    else {
                        if (response.isInactive) {
                            setNotification('User Inactive', response.message, 'danger');
                            window.location = 'logout.php?logout';
                        } else {
                            showNotification('Transaction Error', response.message, 'danger');
                        }
                    }
                },
                error: function(xhr, status, error) {
                    var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                    if (xhr.responseText) {
                        fullErrorMessage += `, Response: ${xhr.responseText}`;
                    }
                    showErrorDialog(fullErrorMessage);
                },
                complete: function() {
                    enableFormSubmitButton('submit-sales-proposal-job-order', 'Submit');
                    $('#sales-proposal-job-order-offcanvas').offcanvas('hide');
                    reloadDatatable('#sales-proposal-job-order-table');
                    resetModalForm('sales-proposal-job-order-form');
                    salesProposalSummaryJobOrderTable();
                }
            });
        
            return false;
        }
    });
}

function salesProposalPricingComputationForm(){
    $('#sales-proposal-pricing-computation-form').validate({
        rules: {
            delivery_price: {
                required: true
            },
            add_on_charge: {
                required: true
            },
            nominal_discount: {
                required: true
            },
            interest_rate: {
                required: true
            },
            cost_of_accessories: {
                required: true
            },
            reconditioning_cost: {
                required: true
            },
            downpayment: {
                required: true
            },
        },
        messages: {
            delivery_price: {
                required: 'Please enter the delivery price'
            },
            add_on_charge: {
                required: 'Please enter the add-on'
            },
            nominal_discount: {
                required: 'Please enter the nominal discount'
            },
            interest_rate: {
                required: 'Please enter the interest rate'
            },
            cost_of_accessories: {
                required: 'Please enter the cost of accessories'
            },
            reconditioning_cost: {
                required: 'Please enter the reconditioning cost'
            },
            downpayment: {
                required: 'Please enter the downpayment'
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
            const transaction = 'save sales proposal pricing computation';
        
            $.ajax({
                type: 'POST',
                url: 'controller/sales-proposal-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&sales_proposal_id=' + sales_proposal_id + '&customer_id=' + customer_id,
                dataType: 'json',
                success: function (response) {
                    if (!response.success) {
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

function salesProposalOtherChargesForm(){
    $('#sales-proposal-other-charges-form').validate({
        rules: {
            insurance_coverage: {
                required: true
            },
            insurance_premium: {
                required: true
            },
            handling_fee: {
                required: true
            },
            transfer_fee: {
                required: true
            },
            registration_fee: {
                required: true
            },
            doc_stamp_tax: {
                required: true
            },
            transaction_fee: {
                required: true
            },
        },
        messages: {
            insurance_coverage: {
                required: 'Please enter the insurance coverage'
            },
            insurance_premium: {
                required: 'Please enter the insurance premium'
            },
            handling_fee: {
                required: 'Please enter the handling fee'
            },
            transfer_fee: {
                required: 'Please enter the transfer fee'
            },
            registration_fee: {
                required: 'Please enter the registration fee'
            },
            doc_stamp_tax: {
                required: 'Please enter the doc stamp tax'
            },
            transaction_fee: {
                required: 'Please enter the transaction fee'
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
            const transaction = 'save sales proposal other charges';
        
            $.ajax({
                type: 'POST',
                url: 'controller/sales-proposal-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&sales_proposal_id=' + sales_proposal_id + '&customer_id=' + customer_id,
                dataType: 'json',
                success: function (response) {
                    if (!response.success) {
                        if (response.isInactive) {
                            setNotification('User Inactive', response.message, 'danger');
                            window.location = 'logout.php?logout';
                        }
                        else if (response.negativeAmount) {
                            showNotification('Transaction Error', 'Kindly check the other charges amount. The subtotal cannot be negative.', 'danger');
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
                },
                complete: function() {
                    //displayDetails('get sales proposal other charges details');
                }
            });
        
            return false;
        }
    });
}

function salesProposalRenewalAmountForm(){
    $('#sales-proposal-renewal-amount-form').validate({
        errorPlacement: function (error, element) {
            if (element.hasClass('select2') || element.hasClass('modal-select2') || element.hasClass('offcanvas-select2')) {
              error.insertAfter(element.next('.select2-container'));
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
            const transaction = 'save sales proposal renewal amount';
        
            $.ajax({
                type: 'POST',
                url: 'controller/sales-proposal-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&sales_proposal_id=' + sales_proposal_id,
                dataType: 'json',
                success: function (response) {
                    if (!response.success) {
                        if (response.isInactive) {
                            setNotification('User Inactive', response.message, 'danger');
                            window.location = 'logout.php?logout';
                        } else {
                            showNotification('Transaction Error', response.message, 'danger');
                        }
                    }
                },
                error: function(xhr, status, error) {
                    var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                    if (xhr.responseText) {
                        fullErrorMessage += `, Response: ${xhr.responseText}`;
                    }
                    showErrorDialog(fullErrorMessage);
                },
                complete: function() {
                    //displayDetails('get sales proposal renewal amount details');
                }
            });
        
            return false;
        }
    });
}

function salesProposalDepositAmountForm(){
    $('#sales-proposal-deposit-amount-form').validate({
        rules: {
            deposit_date: {
                required: true
            },
            reference_number: {
                required: true
            },
            deposit_amount: {
                required: true
            },
        },
        messages: {
            deposit_date: {
                required: 'Please choose the deposit date'
            },
            reference_number: {
                required: 'Please enter the reference number'
            },
            deposit_amount: {
                required: 'Please enter the deposit amount'
            }
        },
        errorPlacement: function (error, element) {
            if (element.hasClass('select2') || element.hasClass('modal-select2') || element.hasClass('offcanvas-select2')) {
              error.insertAfter(element.next('.select2-container'));
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
            const transaction = 'save sales proposal deposit amount';
        
            $.ajax({
                type: 'POST',
                url: 'controller/sales-proposal-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&sales_proposal_id=' + sales_proposal_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-sales-proposal-deposit-amount');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert Amount of Deposit Success' : 'Update Amount of Deposit Success';
                        const notificationDescription = response.insertRecord ? 'The amount of deposit has been inserted successfully.' : 'The amount of deposit has been updated successfully.';
                        
                        showNotification(notificationMessage, notificationDescription, 'success');
                    }
                    else {
                        if (response.isInactive) {
                            setNotification('User Inactive', response.message, 'danger');
                            window.location = 'logout.php?logout';
                        } else {
                            showNotification('Transaction Error', response.message, 'danger');
                        }
                    }
                },
                error: function(xhr, status, error) {
                    var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                    if (xhr.responseText) {
                        fullErrorMessage += `, Response: ${xhr.responseText}`;
                    }
                    showErrorDialog(fullErrorMessage);
                },
                complete: function() {
                    enableFormSubmitButton('submit-sales-proposal-deposit-amount', 'Submit');
                    $('#sales-proposal-deposit-amount-offcanvas').offcanvas('hide');
                    reloadDatatable('#sales-proposal-deposit-amount-table');
                    resetModalForm('sales-proposal-deposit-amount-form');
                }
            });
        
            return false;
        }
    });
}

function salesProposalAdditionalJobOrderForm(){
    $('#sales-proposal-additional-job-order-form').validate({
        rules: {
            job_order_number: {
                required: true
            },
            job_order_date: {
                required: true
            },
            particulars: {
                required: true
            },
            additional_job_order_cost: {
                required: true
            }
        },
        messages: {
            job_order_number: {
                required: 'Please enter the job order'
            },
            job_order_date: {
                required: 'Please choose the job order date'
            },
            particulars: {
                required: 'Please enter the particulars'
            },
            additional_job_order_cost: {
                required: 'Please enter the cost'
            }
        },
        errorPlacement: function (error, element) {
            if (element.hasClass('select2') || element.hasClass('modal-select2') || element.hasClass('offcanvas-select2')) {
              error.insertAfter(element.next('.select2-container'));
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
            const transaction = 'save sales proposal additional job order';
        
            $.ajax({
                type: 'POST',
                url: 'controller/sales-proposal-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&sales_proposal_id=' + sales_proposal_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-sales-proposal-additional-job-order');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert Additional Job Order Success' : 'Update Additional Job Order Success';
                        const notificationDescription = response.insertRecord ? 'The additional job order has been inserted successfully.' : 'The additional job order has been updated successfully.';
                        
                        showNotification(notificationMessage, notificationDescription, 'success');
                    }
                    else {
                        if (response.isInactive) {
                            setNotification('User Inactive', response.message, 'danger');
                            window.location = 'logout.php?logout';
                        } else {
                            showNotification('Transaction Error', response.message, 'danger');
                        }
                    }
                },
                error: function(xhr, status, error) {
                    var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                    if (xhr.responseText) {
                        fullErrorMessage += `, Response: ${xhr.responseText}`;
                    }
                    showErrorDialog(fullErrorMessage);
                },
                complete: function() {
                    enableFormSubmitButton('submit-sales-proposal-additional-job-order', 'Submit');
                    $('#sales-proposal-additional-job-order-offcanvas').offcanvas('hide');
                    reloadDatatable('#sales-proposal-additional-job-order-table');
                    resetModalForm('sales-proposal-additional-job-order-form');
                }
            });
        
            return false;
        }
    });
}

function salesProposalClientConfirmationForm(){
    $('#sales-proposal-client-confirmation-form').validate({
        rules: {
            client_confirmation_image: {
                required: true
            },
        },
        messages: {
            client_confirmation_image: {
                required: 'Please choose the client confirmation image'
            },
        },
        errorPlacement: function (error, element) {
            if (element.hasClass('select2') || element.hasClass('modal-select2') || element.hasClass('offcanvas-select2')) {
              error.insertAfter(element.next('.select2-container'));
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
            const transaction = 'save sales proposal client confirmation';
    
            var formData = new FormData(form);
            formData.append('sales_proposal_id', sales_proposal_id);
            formData.append('transaction', transaction);
        
            $.ajax({
                type: 'POST',
                url: 'controller/sales-proposal-controller.php',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-sales-proposal-client-confirmation');
                },
                success: function (response) {
                    if (response.success) {
                        setNotification('Client Confirmation Upload Success', 'The client confirmation has been uploaded successfully', 'success');
                        window.location.reload();
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
                    var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                    if (xhr.responseText) {
                        fullErrorMessage += `, Response: ${xhr.responseText}`;
                    }
                    showErrorDialog(fullErrorMessage);
                },
                complete: function() {
                    enableFormSubmitButton('submit-sales-proposal-client-confirmation', 'Submit');
                }
            });
        
            return false;
        }
    });
}

function salesProposalComakerConfirmationForm(){
    $('#sales-proposal-comaker-confirmation-form').validate({
        rules: {
            comaker_confirmation_image: {
                required: true
            },
        },
        messages: {
            comaker_confirmation_image: {
                required: 'Please choose the comaker confirmation image'
            },
        },
        errorPlacement: function (error, element) {
            if (element.hasClass('select2') || element.hasClass('modal-select2') || element.hasClass('offcanvas-select2')) {
              error.insertAfter(element.next('.select2-container'));
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
            const transaction = 'save sales proposal comaker confirmation';
    
            var formData = new FormData(form);
            formData.append('sales_proposal_id', sales_proposal_id);
            formData.append('transaction', transaction);
        
            $.ajax({
                type: 'POST',
                url: 'controller/sales-proposal-controller.php',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-sales-proposal-comaker-confirmation');
                },
                success: function (response) {
                    if (response.success) {
                        setNotification('Comaker Confirmation Upload Success', 'The comaker confirmation has been uploaded successfully', 'success');
                        window.location.reload();
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
                    var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                    if (xhr.responseText) {
                        fullErrorMessage += `, Response: ${xhr.responseText}`;
                    }
                    showErrorDialog(fullErrorMessage);
                },
                complete: function() {
                    enableFormSubmitButton('submit-sales-proposal-comaker-confirmation', 'Submit');
                }
            });
        
            return false;
        }
    });
}

function salesProposalCreditAdviceForm(){
    $('#sales-proposal-credit-advice-form').validate({
        rules: {
            credit_advice_image: {
                required: true
            },
        },
        messages: {
            credit_advice_image: {
                required: 'Please choose the credit advice image'
            },
        },
        errorPlacement: function (error, element) {
            if (element.hasClass('select2') || element.hasClass('modal-select2') || element.hasClass('offcanvas-select2')) {
              error.insertAfter(element.next('.select2-container'));
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
            const transaction = 'save sales proposal credit advice';
    
            var formData = new FormData(form);
            formData.append('sales_proposal_id', sales_proposal_id);
            formData.append('transaction', transaction);
        
            $.ajax({
                type: 'POST',
                url: 'controller/sales-proposal-controller.php',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-sales-proposal-credit-advice');
                },
                success: function (response) {
                    if (response.success) {
                        showNotification('Credit Advice Upload Success', 'The credit advice has been uploaded successfully', 'success');
                        displayDetails('get sales proposal confirmation details');
                        $('#sales-proposal-credit-advice-offcanvas').offcanvas('hide');
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
                    var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                    if (xhr.responseText) {
                        fullErrorMessage += `, Response: ${xhr.responseText}`;
                    }
                    showErrorDialog(fullErrorMessage);
                },
                complete: function() {
                    enableFormSubmitButton('submit-sales-proposal-credit-advice', 'Submit');
                }
            });
        
            return false;
        }
    });
}

function salesProposalEngineStencilForm(){
    $('#sales-proposal-engine-stencil-form').validate({
        rules: {
            new_engine_stencil_image: {
                required: true
            },
        },
        messages: {
            new_engine_stencil_image: {
                required: 'Please choose the new engine stencil image'
            },
        },
        errorPlacement: function (error, element) {
            if (element.hasClass('select2') || element.hasClass('modal-select2') || element.hasClass('offcanvas-select2')) {
              error.insertAfter(element.next('.select2-container'));
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
            const transaction = 'save sales proposal new engine stencil';
    
            var formData = new FormData(form);
            formData.append('sales_proposal_id', sales_proposal_id);
            formData.append('transaction', transaction);
        
            $.ajax({
                type: 'POST',
                url: 'controller/sales-proposal-controller.php',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('engine-stencil');
                },
                success: function (response) {
                    if (response.success) {
                        showNotification('New Engine Stencil Upload Success', 'The new engine stencil has been uploaded successfully', 'success');
                        displayDetails('get sales proposal confirmation details');
                        
                        $('#sales-proposal-new-engine-stencil-offcanvas').offcanvas('hide');
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
                    var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                    if (xhr.responseText) {
                        fullErrorMessage += `, Response: ${xhr.responseText}`;
                    }
                    showErrorDialog(fullErrorMessage);
                },
                complete: function() {
                    enableFormSubmitButton('engine-stencil', 'Submit');
                }
            });
        
            return false;
        }
    });
}

function salesProposalInitalApprovalForm(){
    $('#sales-proposal-initial-approval-form').validate({
        rules: {
            initial_approval_remarks: {
                required: true
            }
        },
        messages: {
            initial_approval_remarks: {
                required: 'Please enter the approval remarks'
            }
        },
        errorPlacement: function (error, element) {
            if (element.hasClass('select2') || element.hasClass('modal-select2') || element.hasClass('offcanvas-select2')) {
              error.insertAfter(element.next('.select2-container'));
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
            const transaction = 'sales proposal initial approval';
        
            $.ajax({
                type: 'POST',
                url: 'controller/sales-proposal-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&sales_proposal_id=' + sales_proposal_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-sales-proposal-initial-approval');
                },
                success: function (response) {
                    if (response.success) {
                        showNotification('For Initial Approval Success', 'The sales proposal has been tagged for final approval successfully.', 'success');
                        window.location.reload();
                    }
                    else{
                        if (response.isInactive) {
                            setNotification('User Inactive', response.message, 'danger');
                            window.location = 'logout.php?logout';
                        } 
                        else if (response.withApplication) {
                            showNotification('For Initial Approval Error', 'The product selected already linked to another sales proposal.', 'danger');
                        } 
                        else {
                            showNotification('Transaction Error', response.message, 'danger');
                        }
                    }
                },
                error: function(xhr, status, error) {
                    var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                    if (xhr.responseText) {
                        fullErrorMessage += `, Response: ${xhr.responseText}`;
                    }
                    showErrorDialog(fullErrorMessage);
                },
                complete: function() {
                    enableFormSubmitButton('submit-sales-proposal-initial-approval', 'Submit');
                    $('#sales-proposal-initial-approval-offcanvas').offcanvas('hide');
                }
            });
        
            return false;
        }
    });
}

function salesProposalFinalApprovalForm(){
    $('#sales-proposal-final-approval-form').validate({
        rules: {
            final_approval_remarks: {
                required: true
            }
        },
        messages: {
            final_approval_remarks: {
                required: 'Please enter the approval remarks'
            }
        },
        errorPlacement: function (error, element) {
            if (element.hasClass('select2') || element.hasClass('modal-select2') || element.hasClass('offcanvas-select2')) {
              error.insertAfter(element.next('.select2-container'));
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
            const transaction = 'sales proposal final approval';
        
            $.ajax({
                type: 'POST',
                url: 'controller/sales-proposal-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&sales_proposal_id=' + sales_proposal_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-sales-proposal-final-approval');
                },
                success: function (response) {
                    if (response.success) {
                        showNotification('Proceed Sales Proposal Success', 'The sales proposal has been set to proceed successfully.', 'success');
                        window.location.reload();
                    }
                    else{
                        if (response.isInactive) {
                            setNotification('User Inactive', response.message, 'danger');
                            window.location = 'logout.php?logout';
                        } else {
                            showNotification('Transaction Error', response.message, 'danger');
                        }
                    }
                },
                error: function(xhr, status, error) {
                    var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                    if (xhr.responseText) {
                        fullErrorMessage += `, Response: ${xhr.responseText}`;
                    }
                    showErrorDialog(fullErrorMessage);
                },
                complete: function() {
                    enableFormSubmitButton('submit-sales-proposal-final-approval', 'Submit');
                    $('#sales-proposal-final-approval-offcanvas').offcanvas('hide');
                }
            });
        
            return false;
        }
    });
}

function salesProposalRejectForm(){
    $('#sales-proposal-reject-form').validate({
        rules: {
            rejection_reason: {
                required: true
            }
        },
        messages: {
            rejection_reason: {
                required: 'Please enter the rejection reason'
            }
        },
        errorPlacement: function (error, element) {
            if (element.hasClass('select2') || element.hasClass('modal-select2') || element.hasClass('offcanvas-select2')) {
              error.insertAfter(element.next('.select2-container'));
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
            const transaction = 'sales proposal reject';
        
            $.ajax({
                type: 'POST',
                url: 'controller/sales-proposal-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&sales_proposal_id=' + sales_proposal_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-sales-proposal-reject');
                },
                success: function (response) {
                    if (response.success) {
                        showNotification('Reject Sales Proposal Success', 'The sales proposal has been rejected successfully.', 'success');
                        window.location.reload();
                    }
                    else{
                        if (response.isInactive) {
                            setNotification('User Inactive', response.message, 'danger');
                            window.location = 'logout.php?logout';
                        } else {
                            showNotification('Transaction Error', response.message, 'danger');
                        }
                    }
                },
                error: function(xhr, status, error) {
                    var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                    if (xhr.responseText) {
                        fullErrorMessage += `, Response: ${xhr.responseText}`;
                    }
                    showErrorDialog(fullErrorMessage);
                },
                complete: function() {
                    enableFormSubmitButton('submit-sales-proposal-reject', 'Submit');
                    $('#sales-proposal-reject-offcanvas').offcanvas('hide');
                }
            });
        
            return false;
        }
    });
}

function installmentSalesApprovalForm(){
    $('#approve-installment-sales-form').validate({
        rules: {
            installment_sales_approval_remarks: {
                required: true
            },
            term_length_2: {
                required: true
            },
            add_on_charge_2: {
                required: true
            },
            nominal_discount_2: {
                required: true
            },
            nominal_discount_2: {
                required: true
            },
            interest_rate_2: {
                required: true
            },
            downpayment_2: {
                required: true
            },
        },
        messages: {
            installment_sales_approval_remarks: {
                required: 'Please enter the approval remarks'
            },
            term_length_2: {
                required: 'Please enter the term length'
            },
            add_on_charge_2: {
                required: 'Please enter the add-on charge'
            },
            nominal_discount_2: {
                required: 'Please enter the nominal discount'
            },
            interest_rate_2: {
                required: 'Please enter the interest rate'
            },
            downpayment_2: {
                required: 'Please enter the downpayment'
            },
        },
        errorPlacement: function (error, element) {
            if (element.hasClass('select2') || element.hasClass('modal-select2') || element.hasClass('offcanvas-select2')) {
              error.insertAfter(element.next('.select2-container'));
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
            const transaction = 'sales installment approval';
        
            $.ajax({
                type: 'POST',
                url: 'controller/sales-proposal-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&sales_proposal_id=' + sales_proposal_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-sales-proposal-reject');
                },
                success: function (response) {
                    if (response.success) {
                        setNotification('Approve Sales Installment Success', 'The sales installment has been approved successfully.', 'success');

                        displayDetails('get sales proposal basic details');                                
                        displayDetails('get sales proposal pricing computation details');
                    }
                    else{
                        if (response.isInactive) {
                            setNotification('User Inactive', response.message, 'danger');
                            window.location = 'logout.php?logout';
                        } else {
                            showNotification('Transaction Error', response.message, 'danger');
                        }
                    }
                },
                error: function(xhr, status, error) {
                    var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                    if (xhr.responseText) {
                        fullErrorMessage += `, Response: ${xhr.responseText}`;
                    }
                    showErrorDialog(fullErrorMessage);
                },
                complete: function() {
                    $('#sales-proposal-pricing-computation-form').submit();
                    enableFormSubmitButton('submit-sales-proposal-reject', 'Submit');
                    $('#sales-proposal-reject-offcanvas').offcanvas('hide');
                    window.location.reload();
                }
            });
        
            return false;
        }
    });
}

function salesProposalCancelForm(){
    $('#sales-proposal-cancel-form').validate({
        rules: {
            cancellation_reason: {
                required: true
            }
        },
        messages: {
            cancellation_reason: {
                required: 'Please enter the cancellation reason'
            }
        },
        errorPlacement: function (error, element) {
            if (element.hasClass('select2') || element.hasClass('modal-select2') || element.hasClass('offcanvas-select2')) {
              error.insertAfter(element.next('.select2-container'));
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
            const transaction = 'sales proposal cancel';
        
            $.ajax({
                type: 'POST',
                url: 'controller/sales-proposal-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&sales_proposal_id=' + sales_proposal_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-sales-proposal-cancel');
                },
                success: function (response) {
                    if (response.success) {
                        showNotification('Cancel Sales Proposal Success', 'The sales proposal has been cancelled successfully.', 'success');
                        window.location.reload();
                    }
                    else{
                        if (response.isInactive) {
                            setNotification('User Inactive', response.message, 'danger');
                            window.location = 'logout.php?logout';
                        } else {
                            showNotification('Transaction Error', response.message, 'danger');
                        }
                    }
                },
                error: function(xhr, status, error) {
                    var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                    if (xhr.responseText) {
                        fullErrorMessage += `, Response: ${xhr.responseText}`;
                    }
                    showErrorDialog(fullErrorMessage);
                },
                complete: function() {
                    enableFormSubmitButton('submit-sales-proposal-cancel', 'Submit');
                    $('#sales-proposal-cancel-offcanvas').offcanvas('hide');
                }
            });
        
            return false;
        }
    });
}

function salesProposalCIRecommendationForm(){
    $('#sales-proposal-ci-recommendation-form').validate({
        rules: {
            ci_recommendation: {
                required: true
            }
        },
        messages: {
            ci_recommendation: {
                required: 'Please enter the CI recommendation'
            }
        },
        errorPlacement: function (error, element) {
            if (element.hasClass('select2') || element.hasClass('modal-select2') || element.hasClass('offcanvas-select2')) {
              error.insertAfter(element.next('.select2-container'));
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
            const transaction = 'sales proposal ci recommendation';

            $.ajax({
                type: 'POST',
                url: 'controller/sales-proposal-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&sales_proposal_id=' + sales_proposal_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-sales-proposal-ci-recommendation');
                },
                success: function (response) {
                    if (response.success) {
                        showNotification('CI Recommendation Success', 'The CI recommendation has been submitted successfully.', 'success');
                    }
                    else{
                        if (response.isInactive) {
                            setNotification('User Inactive', response.message, 'danger');
                            window.location = 'logout.php?logout';
                        } else {
                            showNotification('Transaction Error', response.message, 'danger');
                        }
                    }
                },
                error: function(xhr, status, error) {
                    var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                    if (xhr.responseText) {
                        fullErrorMessage += `, Response: ${xhr.responseText}`;
                    }
                    showErrorDialog(fullErrorMessage);
                },
                complete: function() {
                    enableFormSubmitButton('submit-sales-proposal-ci-recommendation', 'Submit');
                    $('#sales-proposal-ci-recommendation-offcanvas').offcanvas('hide');
                }
            });
        
            return false;
        }
    });
}

function salesProposalSetToDraftForm(){
    $('#sales-proposal-set-to-draft-form').validate({
        rules: {
            set_to_draft_reason: {
                required: true
            },
            set_to_draft_file: {
                required: true
            },
        },
        messages: {
            set_to_draft_reason: {
                required: 'Please enter the set to draft reason'
            },
            set_to_draft_file: {
                required: 'Please choose the set to draft file'
            },
        },
        errorPlacement: function (error, element) {
            if (element.hasClass('select2') || element.hasClass('modal-select2') || element.hasClass('offcanvas-select2')) {
              error.insertAfter(element.next('.select2-container'));
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
            const transaction = 'sales proposal set to draft';

            var formData = new FormData(form);
            formData.append('sales_proposal_id', sales_proposal_id);
            formData.append('transaction', transaction);
        
            $.ajax({
                type: 'POST',
                url: 'controller/sales-proposal-controller.php',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-sales-proposal-set-to-draft');
                },
                success: function (response) {
                    if (response.success) {
                        setNotification('Set Sales Proposal To Draft Success', 'The sales proposal has been set to draft successfully.', 'success');
                        window.location.reload();
                    }
                    else{
                        if (response.isInactive) {
                            setNotification('User Inactive', response.message, 'danger');
                            window.location = 'logout.php?logout';
                        } else {
                            showNotification('Transaction Error', response.message, 'danger');
                        }
                    }
                },
                error: function(xhr, status, error) {
                    var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                    if (xhr.responseText) {
                        fullErrorMessage += `, Response: ${xhr.responseText}`;
                    }
                    showErrorDialog(fullErrorMessage);
                },
                complete: function() {
                    enableFormSubmitButton('submit-sales-proposal-set-to-draft', 'Submit');
                    $('#sales-proposal-set-to-draft-offcanvas').offcanvas('hide');
                }
            });
        
            return false;
        }
    });
}

function salesProposalOtherDocumentForm(){
    $('#sales-proposal-other-document-form').validate({
        rules: {
            other_document_file: {
                required: true
            },
        },
        messages: {
            other_document_file: {
                required: 'Please choose the other document'
            },
        },
        errorPlacement: function (error, element) {
            if (element.hasClass('select2') || element.hasClass('modal-select2') || element.hasClass('offcanvas-select2')) {
              error.insertAfter(element.next('.select2-container'));
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
            const transaction = 'sales proposal other document';

            var formData = new FormData(form);
            formData.append('sales_proposal_id', sales_proposal_id);
            formData.append('transaction', transaction);
        
            $.ajax({
                type: 'POST',
                url: 'controller/sales-proposal-controller.php',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-sales-proposal-set-to-draft');
                },
                success: function (response) {
                    if (response.success) {
                        setNotification('Save Other Document', 'The other document has been saved successfully.', 'success');
                        window.location.reload();
                    }
                    else{
                        if (response.isInactive) {
                            setNotification('User Inactive', response.message, 'danger');
                            window.location = 'logout.php?logout';
                        } else {
                            showNotification('Transaction Error', response.message, 'danger');
                        }
                    }
                },
                error: function(xhr, status, error) {
                    var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                    if (xhr.responseText) {
                        fullErrorMessage += `, Response: ${xhr.responseText}`;
                    }
                    showErrorDialog(fullErrorMessage);
                },
                complete: function() {
                    enableFormSubmitButton('submit-sales-proposal-set-to-draft', 'Submit');
                    $('#sales-proposal-set-to-draft-offcanvas').offcanvas('hide');
                }
            });
        
            return false;
        }
    });
}

function salesProposalQualityControlForm(){
    $('#sales-proposal-quality-control-form').validate({
        rules: {
            quality_control_image: {
                required: true
            },
        },
        messages: {
            quality_control_image: {
                required: 'Please choose the quality control form image'
            },
        },
        errorPlacement: function (error, element) {
            if (element.hasClass('select2') || element.hasClass('modal-select2') || element.hasClass('offcanvas-select2')) {
              error.insertAfter(element.next('.select2-container'));
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
            const transaction = 'save sales proposal quality control form';
    
            var formData = new FormData(form);
            formData.append('sales_proposal_id', sales_proposal_id);
            formData.append('transaction', transaction);
        
            $.ajax({
                type: 'POST',
                url: 'controller/sales-proposal-controller.php',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-sales-proposal-quality-control');
                },
                success: function (response) {
                    if (response.success) {
                        setNotification('Quality Control Form Upload Success', 'The quality control form has been uploaded successfully', 'success');
                        window.location.reload();
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
                    var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                    if (xhr.responseText) {
                        fullErrorMessage += `, Response: ${xhr.responseText}`;
                    }
                    showErrorDialog(fullErrorMessage);
                },
                complete: function() {
                    enableFormSubmitButton('submit-sales-proposal-quality-control', 'Submit');
                }
            });
        
            return false;
        }
    });
}

function salesProposalOutgoingChecklistForm(){
    $('#sales-proposal-outgoing-checklist-form').validate({
        rules: {
            outgoing_checklist_image: {
                required: true
            },
        },
        messages: {
            outgoing_checklist_image: {
                required: 'Please choose the outgoing checklist image'
            },
        },
        errorPlacement: function (error, element) {
            if (element.hasClass('select2') || element.hasClass('modal-select2') || element.hasClass('offcanvas-select2')) {
              error.insertAfter(element.next('.select2-container'));
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
            const transaction = 'save sales proposal outgoing checklist';
    
            var formData = new FormData(form);
            formData.append('sales_proposal_id', sales_proposal_id);
            formData.append('transaction', transaction);
        
            $.ajax({
                type: 'POST',
                url: 'controller/sales-proposal-controller.php',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-sales-proposal-outgoing-checklist');
                },
                success: function (response) {
                    if (response.success) {
                        setNotification('Outgoing Checklist Upload Success', 'The outgoing checklist has been uploaded successfully', 'success');
                        window.location.reload();
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
                    var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                    if (xhr.responseText) {
                        fullErrorMessage += `, Response: ${xhr.responseText}`;
                    }
                    showErrorDialog(fullErrorMessage);
                },
                complete: function() {
                    enableFormSubmitButton('submit-sales-proposal-outgoing-checklist', 'Submit');
                }
            });
        
            return false;
        }
    });
}

function salesProposalUnitImageForm(){
    $('#sales-proposal-unit-image-form').validate({
        rules: {
            unit_image_image: {
                required: true
            },
        },
        messages: {
            unit_image_image: {
                required: 'Please choose the unit image'
            },
        },
        errorPlacement: function (error, element) {
            if (element.hasClass('select2') || element.hasClass('modal-select2') || element.hasClass('offcanvas-select2')) {
              error.insertAfter(element.next('.select2-container'));
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
            const transaction = 'save sales proposal unit image';
    
            var formData = new FormData(form);
            formData.append('sales_proposal_id', sales_proposal_id);
            formData.append('transaction', transaction);
        
            $.ajax({
                type: 'POST',
                url: 'controller/sales-proposal-controller.php',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-sales-proposal-unit-image');
                },
                success: function (response) {
                    if (response.success) {
                        setNotification('Unit Image Upload Success', 'The unit image has been uploaded successfully', 'success');
                        window.location.reload();
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
                    var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                    if (xhr.responseText) {
                        fullErrorMessage += `, Response: ${xhr.responseText}`;
                    }
                    showErrorDialog(fullErrorMessage);
                },
                complete: function() {
                    enableFormSubmitButton('submit-sales-proposal-unit-image', 'Submit');
                }
            });
        
            return false;
        }
    });
}

function salesProposalAdditionalJobOrderConfirmationImageForm(){
    $('#sales-proposal-job-order-confirmation-form').validate({
        rules: {
            additional_job_order_confirmation_image: {
                required: true
            },
        },
        messages: {
            additional_job_order_confirmation_image: {
                required: 'Please choose the additional job order confimation image'
            },
        },
        errorPlacement: function (error, element) {
            if (element.hasClass('select2') || element.hasClass('modal-select2') || element.hasClass('offcanvas-select2')) {
              error.insertAfter(element.next('.select2-container'));
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
            const transaction = 'save sales proposal additional job order confirmation';
    
            var formData = new FormData(form);
            formData.append('sales_proposal_id', sales_proposal_id);
            formData.append('transaction', transaction);
        
            $.ajax({
                type: 'POST',
                url: 'controller/sales-proposal-controller.php',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-sales-proposal-additional-job-order-confirmation');
                },
                success: function (response) {
                    if (response.success) {
                        setNotification('Additional Job Order Confirmation Upload Success', 'The additional job order confirmation has been uploaded successfully', 'success');
                        window.location.reload();
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
                    var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                    if (xhr.responseText) {
                        fullErrorMessage += `, Response: ${xhr.responseText}`;
                    }
                    showErrorDialog(fullErrorMessage);
                },
                complete: function() {
                    enableFormSubmitButton('submit-sales-proposal-additional-job-order-confirmation', 'Submit');
                }
            });
        
            return false;
        }
    });
}

function salesProposalPDCManualInputForm(){
    $('#sales-proposal-pdc-manual-input-form').validate({
        rules: {
            pdc_payment_frequency: {
                required: true
            },
            payment_for: {
                required: true
            },
            no_of_payments: {
                required: true
            },
            first_check_number: {
                required: true
            },
            first_check_date: {
                required: true
            },
            amount_due: {
                required: true
            },
        },
        messages: {
            pdc_payment_frequency: {
                required: 'Please choose the payment frequency'
            },
            payment_for: {
                required: 'Please choose the payment for'
            },
            no_of_payments: {
                required: 'Please enter the number of payments'
            },
            first_check_number: {
                required: 'Please enter the first check number'
            },
            first_check_date: {
                required: 'Please choose the first check date'
            },
            amount_due: {
                required: 'Please enter the gross amount'
            },
        },
        errorPlacement: function (error, element) {
            if (element.hasClass('select2') || element.hasClass('modal-select2') || element.hasClass('offcanvas-select2')) {
              error.insertAfter(element.next('.select2-container'));
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
            const transaction = 'save sales proposal pdc manual input';
        
            $.ajax({
                type: 'POST',
                url: 'controller/sales-proposal-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&sales_proposal_id=' + sales_proposal_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-sales-proposal-pdc-manual-input');
                },
                success: function (response) {
                    if (!response.success) {
                        if (response.isInactive) {
                            setNotification('User Inactive', response.message, 'danger');
                            window.location = 'logout.php?logout';
                        } else {
                            showNotification('Transaction Error', response.message, 'danger');
                        }
                    }
                    else{
                        reloadDatatable('#sales-proposal-pdc-manual-input-table');
                    }
                },
                error: function(xhr, status, error) {
                    var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                    if (xhr.responseText) {
                        fullErrorMessage += `, Response: ${xhr.responseText}`;
                    }
                    showErrorDialog(fullErrorMessage);
                },
                complete: function() {
                    enableFormSubmitButton('submit-sales-proposal-pdc-manual-input', 'Submit');
                    $('#sales-proposal-pdc-manual-input-offcanvas').offcanvas('hide');
                    salesProposalSummaryPDCManualInputTable();
                }
            });
        
            return false;
        }
    });
}

function salesProposalOtherProductDetailsForm(){
    $('#sales-proposal-other-product-details-form').validate({
        rules: {
            dr_number: {
                required: true
            },
            actual_start_date: {
                required: true
            },
            product_description: {
                required: true
            },
        },
        messages: {
            dr_number: {
                required: 'Please enter the DR number'
            },
            actual_start_date: {
                required: 'Please choose the actual start date'
            },
            product_description: {
                required: 'Please enter the product description'
            },
        },
        errorPlacement: function (error, element) {
            if (element.hasClass('select2') || element.hasClass('modal-select2') || element.hasClass('offcanvas-select2')) {
              error.insertAfter(element.next('.select2-container'));
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
            const transaction = 'save sales proposal other product details';
        
            $.ajax({
                type: 'POST',
                url: 'controller/sales-proposal-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&sales_proposal_id=' + sales_proposal_id,
                dataType: 'json',
                success: function (response) {
                    if (!response.success) {
                        if (response.isInactive) {
                            setNotification('User Inactive', response.message, 'danger');
                            window.location = 'logout.php?logout';
                        } else {
                            showNotification('Transaction Error', response.message, 'danger');
                        }
                    }
                },
                error: function(xhr, status, error) {
                    var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                    if (xhr.responseText) {
                        fullErrorMessage += `, Response: ${xhr.responseText}`;
                    }
                    showErrorDialog(fullErrorMessage);
                },
                complete: function() {
                    displayDetails('get sales proposal other product details');
                    salesProposalDisclosureScheduleTable();
                }
            });
        
            return false;
        }
    });
}

function salesProposalReleaseForm(){
    $('#sales-proposal-tag-as-released-form').validate({
        rules: {
            release_remarks: {
                required: true
            },
        },
        messages: {
            release_remarks: {
                required: 'Please eneter the release remarks'
            },
        },
        errorPlacement: function (error, element) {
            if (element.hasClass('select2') || element.hasClass('modal-select2') || element.hasClass('offcanvas-select2')) {
              error.insertAfter(element.next('.select2-container'));
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
            const transaction = 'tag for release';
        
            $.ajax({
                type: 'POST',
                url: 'controller/sales-proposal-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&sales_proposal_id=' + sales_proposal_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-other-product-details-data');
                },
                success: function (response) {
                    if (!response.success) {
                        if (response.isInactive) {
                            setNotification('User Inactive', response.message, 'danger');
                            window.location = 'logout.php?logout';
                        } else {
                            showNotification('Transaction Error', response.message, 'danger');
                        }
                    }
                    else{
                        window.location.reload();
                    }
                },
                error: function(xhr, status, error) {
                    var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                    if (xhr.responseText) {
                        fullErrorMessage += `, Response: ${xhr.responseText}`;
                    }
                    showErrorDialog(fullErrorMessage);
                },
                complete: function() {
                    enableFormSubmitButton('submit-other-product-details-data', 'Submit');
                    displayDetails('get sales proposal other product details');
                }
            });
        
            return false;
        }
    });
}

function displayDetails(transaction){
    switch (transaction) {
        case 'get sales proposal basic details':
            var sales_proposal_id = $('#sales-proposal-id').text();
            
            $.ajax({
                url: 'controller/sales-proposal-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    sales_proposal_id : sales_proposal_id, 
                    transaction : transaction
                },
                success: function(response) {
                    if (response.success) {
                        $('#sales_proposal_number').text(response.salesProposalNumber);
                        $('#summary-sales-proposal-number').text(response.salesProposalNumber);

                        if(response.productType != 'Unit' && response.productType != 'Rental' && response.productType != 'Consignment' && response.productType != 'Refinancing' && response.productType != 'Brand New' && response.productType != 'Restructure'){
                            $('#summary-stock-no').text(response.salesProposalNumber);
                        }

                        $('#financing_institution').val(response.financingInstitution);
                        $('#referred_by').val(response.referredBy);
                        $('#commission_amount').val(response.commissionAmount);
                        $('#release_date').val(response.releaseDate);
                        $('#start_date').val(response.startDate);
                        $('#term_length').val(response.termLength);
                        $('#number_of_payments').val(response.numberOfPayments);
                        $('#first_due_date').val(response.firstDueDate);
                        $('#remarks').val(response.remarks);                        
                        $('#dr_number').val(response.drNumber);
                        $('#release_to').val(response.releaseTo);
                        $('#actual_start_date').val(response.actualStartDate);
                        
                        if($('#term_length_2').length){
                            $('#term_length_2').val(response.termLength);
                        }

                        if($('#draft-file').length){
                            document.getElementById('draft-file').src = response.setToDraftFile;
                        }

                        if($('#other-document-file').length){
                            document.getElementById('other-document-file').src = response.otherDocumentFile;
                        }

                        $('#summary-commission').text(encryptCommission(response.commissionAmount));
                       
                        checkOptionExist('#renewal_tag', response.renewalTag, '');
                        checkOptionExist('#application_source_id', response.applicationSourceID, '');
                        checkOptionExist('#product_type', response.productType, '');
                        checkOptionExist('#transaction_type', response.transactionType, '');
                        checkOptionExist('#comaker_id', response.comakerID, '');
                        checkOptionExist('#additional_maker_id', response.additionalMakerID, '');
                        checkOptionExist('#comaker_id2', response.comakerID2, '');
                        checkOptionExist('#term_type', response.termType, '');
                        checkOptionExist('#payment_frequency', response.paymentFrequency, '');
                        checkOptionExist('#initial_approving_officer', response.initialApprovingOfficer, '');
                        checkOptionExist('#final_approving_officer', response.finalApprovingOfficer, '');
                        checkOptionExist('#company_id', response.companyID, '');

                        $('#summary-referred-by').text(response.referredBy);
                        $('#summary-release-date').text(response.releaseDate);
                        $('#summary-product-type').text(response.productType);
                        $('#summary-transaction-type').text(response.transactionType);
                        $('#summary-term').text(response.termLength + ' ' + response.termType);
                        $('#insurance_term').text(response.termLength + ' ' + response.termType);
                        $('#insurance_maturity').text(response.maturityDate);
                        $('#summary-no-payments').text(response.numberOfPayments);
                        $('#summary-remarks').text(response.remarks);
                        $('#summary-initial-approval-by').text(response.initialApprovingOfficerName);
                        $('#summary-final-approval-by').text(response.finalApprovingOfficerName);
                        $('#summary-created-by').text(response.createdByName);

                        $('#initial_approval_remarks_label').text(response.initialApprovalRemarks);
                        $('#initial_approval_remarks').val(response.initialApprovalRemarks);
                        $('#final_approval_remarks_label').text(response.finalApprovalRemarks);
                        $('#final_approval_remarks').val(response.finalApprovalRemarks);
                        $('#installment_sales_approval_remarks_label').text(response.installmentSalesApprovalRemarks);
                        $('#ci_recommendation_label').html(response.ci_recommendation.replace(/\n/g, "<br>"));
                        $('#rejection_reason_label').text(response.rejectionReason);
                        $('#cancellation_reason_label').text(response.cancellationReason);
                        $('#set_to_draft_reason_label').text(response.setToDraftReason);
                        $('#release_remarks_label').text(response.releaseRemarks);

                        $('#summary-total-job-order-progress').text(response.jobOrderProgress);

                        document.getElementById('summary-total-additional-job-order').innerHTML = response.totalAdditionalJobOrder;
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Sales Proposal Details Error', response.message, 'danger');
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
                    var productType = $('#product_type').val();

                    if(productType == 'Unit' || productType == 'Rental' || productType == 'Consignment'){
                        displayDetails('get sales proposal unit details');
                    }
                    else if(productType == 'Fuel'){
                        displayDetails('get sales proposal fuel details');
                    }
                    else{
                        displayDetails('get sales proposal refinancing details');
                    }

                    calculateTotalOtherCharges();
                }
            });
            break;
        case 'get sales proposal unit details':
            var sales_proposal_id = $('#sales-proposal-id').text();
            
            $.ajax({
                url: 'controller/sales-proposal-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    sales_proposal_id : sales_proposal_id, 
                    transaction : transaction
                },
                success: function(response) {
                    if (response.success) {
                        $('#old_color').val(response.oldColor);
                        $('#new_color').val(response.newColor);
                        $('#old_body').val(response.oldBody);
                        $('#new_body').val(response.newBody);
                        $('#old_engine').val(response.oldEngine);
                        $('#new_engine').val(response.newEngine);
                        $('#final_orcr_name').val(response.finalOrcrName);
                        $('#summary-final-name-on-orcr').text(response.finalOrcrName);

                        checkOptionExist('#product_id', response.productID, '');
                        checkOptionExist('#for_registration', response.forRegistration, '');
                        checkOptionExist('#with_cr', response.withCR, '');
                        checkOptionExist('#for_transfer', response.forTransfer, '');
                        checkOptionExist('#for_change_color', response.forChangeColor, '');
                        checkOptionExist('#for_change_body', response.forChangeBody, '');
                        checkOptionExist('#for_change_engine', response.forChangeEngine, '');
                        
                        $('#summary-new-color').text(response.newColor);
                        $('#summary-new-body').text(response.newBody);
                        $('#summary-new-engine').text(response.newEngine);
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Sales Proposal Details Error', response.message, 'danger');
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
                    displayDetails('get sales proposal pricing computation details');
                }
            });
            break;
        case 'get sales proposal fuel details':
            var sales_proposal_id = $('#sales-proposal-id').text();
            
            $.ajax({
                url: 'controller/sales-proposal-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    sales_proposal_id : sales_proposal_id, 
                    transaction : transaction
                },
                success: function(response) {
                    if (response.success) {
                        $('#diesel_fuel_quantity').val(response.dieselFuelQuantity);
                        $('#diesel_price_per_liter').val(response.dieselPricePerLiter);
                        $('#regular_fuel_quantity').val(response.regularFuelQuantity);
                        $('#regular_price_per_liter').val(response.regularPricePerLiter);
                        $('#premium_fuel_quantity').val(response.premiumFuelQuantity);
                        $('#premium_price_per_liter').val(response.premiumPricePerLiter);

                        $('#summary-diesel-fuel-quantity').text(response.dieselFuelQuantity + ' lt');
                        $('#summary-regular-fuel-quantity').text(response.regularFuelQuantity + ' lt');
                        $('#summary-premium-fuel-quantity').text(response.premiumFuelQuantity + ' lt');
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Sales Proposal Details Error', response.message, 'danger');
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
                    calculateFuelTotal();
                    displayDetails('get sales proposal pricing computation details');
                }
            });
            break;
        case 'get sales proposal refinancing details':
            var sales_proposal_id = $('#sales-proposal-id').text();
            
            $.ajax({
                url: 'controller/sales-proposal-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    sales_proposal_id : sales_proposal_id, 
                    transaction : transaction
                },
                success: function(response) {
                    if (response.success) {
                        $('#ref_stock_no').text(response.refStockNo);
                        $('#ref_engine_no').val(response.refEngineNo);
                        $('#ref_chassis_no').val(response.refChassisNo);
                        $('#ref_plate_no').val(response.refPlateNo);

                        $('#orcr_no').val(response.orcrNo);
                        $('#orcr_date').val(response.orcrDate);
                        $('#orcr_expiry_date').val(response.orcrExpiryDate);
                        $('#received_from').val(response.receivedFrom);
                        $('#received_from_address').val(response.receivedFromAddress);
                        $('#received_from_id_number').val(response.receivedFromIDNumber);
                        $('#unit_description').val(response.unitDescription);

                        var existingText = $('#summary-remarks').text().trim();
                        var newText = response.unitDescription.trim();
                        
                        // Check if the new text is already present
                        if (!existingText.includes(newText)) {
                            if (existingText) {
                                $('#summary-remarks').text(existingText + "\n\n" + newText);
                            } else {
                                $('#summary-remarks').text(newText);
                            }
                        }
                        
                        
                        checkOptionExist('#received_from_id_type', response.receivedFromIDType, '');

                        $('#summary-stock-no').text(response.refStockNo);
                        $('#summary-engine-no').text(response.refEngineNo);
                        $('#summary-chassis-no').text(response.refChassisNo);
                        $('#summary-plate-no').text(response.refPlateNo);

                        $('#insurance_unit_no').text(response.refStockNo);
                        $('#insurance_engine_no').text(response.refEngineNo);
                        $('#insurance_chassis_no').text(response.refChassisNo);
                        $('#insurance_plate_no').text(response.refPlateNo);
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Sales Proposal Details Error', response.message, 'danger');
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
                    displayDetails('get sales proposal pricing computation details');
                }
            });
            break;
        case 'get sales proposal job order details':
            var sales_proposal_job_order_id = sessionStorage.getItem('sales_proposal_job_order_id');
                
            $.ajax({
                url: 'controller/sales-proposal-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    sales_proposal_job_order_id : sales_proposal_job_order_id, 
                    transaction : transaction
                },
                success: function(response) {
                    if (response.success) {
                        $('#sales_proposal_job_order_id').val(sales_proposal_job_order_id);
                        $('#job_order').val(response.jobOrder);
                        $('#job_order_cost').val(response.cost);
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Sales Proposal Job Order Details Error', response.message, 'danger');
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
            break;
        case 'get sales proposal pricing computation details':
            var sales_proposal_id = $('#sales-proposal-id').text();
            
            $.ajax({
                url: 'controller/sales-proposal-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    sales_proposal_id : sales_proposal_id, 
                    transaction : transaction
                },
                success: function(response) {
                    if (response.success) {
                        var productType = $('#product_type').val();

                        if(productType != 'Unit' && productType != 'Rental' && productType != 'Consignment'){
                            $('#delivery_price').val(response.deliveryPrice);
                        }

                        $('#nominal_discount').val(response.nominalDiscount);
                        $('#add_on_charge').val(response.addOnCharge);
                        $('#cost_of_accessories').val(response.costOfAccessories);
                        $('#reconditioning_cost').val(response.reconditioningCost);
                        $('#downpayment').val(response.downpayment);
                        $('#interest_rate').val(response.interestRate);
                        
                        if($('#add_on_charge_2').length){
                            $('#add_on_charge_2').val(response.addOnCharge);
                        }
                        
                        if($('#nominal_discount_2').length){
                            $('#nominal_discount_2').val(response.nominalDiscount);
                        }
                        
                        if($('#interest_rate_2').length){
                            $('#interest_rate_2').val(response.interestRate);
                        }
                        
                        if($('#downpayment_2').length){
                            $('#downpayment_2').val(response.downpayment);
                        }
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Sales Proposal Pricing Computation Details Error', response.message, 'danger');
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
                }
            });
            break;
        case 'get sales proposal other charges details':
            var sales_proposal_id = $('#sales-proposal-id').text();
                
            $.ajax({
                url: 'controller/sales-proposal-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    sales_proposal_id : sales_proposal_id, 
                    transaction : transaction
                },
                success: function(response) {
                    if (response.success) {
                        $('#insurance_coverage').val(response.insuranceCoverage);
                        $('#insurance_request_coverage_1').text(response.insuranceCoverage);
                        $('#insurance_premium').val(response.insurancePremium);
                        $('#insurance_request_premium_1').text(response.insurancePremium);
                        $('#handling_fee').val(response.handlingFee);
                        $('#transfer_fee').val(response.transferFee);
                        $('#registration_fee').val(response.registrationFee);
                        $('#doc_stamp_tax').val(response.docStampTax);
                        $('#transaction_fee').val(response.transactionFee);

                        $('#insurance_premium_discount').val(response.insurancePremiumDiscount);
                        $('#handling_fee_discount').val(response.handlingFeeDiscount);
                        $('#doc_stamp_tax_discount').val(response.docStampTaxDiscount);
                        $('#transaction_fee_discount').val(response.transactionFeeDiscount);
                        $('#transfer_fee_discount').val(response.transferFeeDiscount);
                        $('#insurance_premium_subtotal').val(response.insurancePremiumSubtotal);
                        $('#handling_fee_subtotal').val(response.handlingFeeSubtotal);
                        $('#doc_stamp_tax_subtotal').val(response.docStampTaxSubtotal);
                        $('#transaction_fee_subtotal').val(response.transactionFeeSubtotal);
                        $('#transfer_fee_subtotal').val(response.transferFeeSubtotal);

                        $('#summary-insurance-coverage').text(parseFloat(response.insuranceCoverage).toLocaleString("en-US"));
                        $('#summary-insurance-premium').text(parseFloat(response.insurancePremiumSubtotal).toLocaleString("en-US"));
                        $('#summary-handing-fee').text(parseFloat(response.handlingFeeSubtotal).toLocaleString("en-US"));
                        $('#summary-transfer-fee').text(parseFloat(response.transferFeeSubtotal).toLocaleString("en-US"));
                        $('#summary-registration-fee').text(parseFloat(response.registrationFee).toLocaleString("en-US"));
                        $('#summary-doc-stamp-tax').text(parseFloat(response.docStampTaxSubtotal).toLocaleString("en-US"));
                        $('#summary-transaction-fee').text(parseFloat(response.transactionFeeSubtotal).toLocaleString("en-US"));
                        $('#summary-other-charges-total').text(parseFloat(response.totalOtherCharges).toLocaleString("en-US"));
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Sales Proposal Other Charges Details Error', response.message, 'danger');
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
                    calculateTotalOtherCharges();
                }
            });
            break;
        case 'get sales proposal renewal amount details':
            var sales_proposal_id = $('#sales-proposal-id').text();
            
            $.ajax({
                url: 'controller/sales-proposal-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    sales_proposal_id : sales_proposal_id, 
                    transaction : transaction
                },
                success: function(response) {
                    if (response.success) {
                        $('#registration_second_year').val(response.registrationSecondYear);
                        $('#registration_third_year').val(response.registrationThirdYear);
                        $('#registration_fourth_year').val(response.registrationFourthYear);
                        $('#insurance_coverage_second_year').val(response.insuranceCoverageSecondYear);
                        $('#insurance_coverage_third_year').val(response.insuranceCoverageThirdYear);
                        $('#insurance_coverage_fourth_year').val(response.insuranceCoverageFourthYear);
                        $('#insurance_premium_second_year').val(response.insurancePremiumSecondYear);
                        $('#insurance_premium_third_year').val(response.insurancePremiumThirdYear);
                        $('#insurance_premium_fourth_year').val(response.insurancePremiumFourthYear);

                        $('#summary-registration-second-year').text(response.registrationSecondYearSummary);
                        $('#summary-registration-second-year').text(response.registrationSecondYearSummary);
                        $('#summary-registration-third-year').text(response.registrationThirdYearSummary);
                        $('#summary-registration-fourth-year').text(response.registrationFourthYearSummary);
                        $('#summary-insurance-coverage-second-year').text(response.insuranceCoverageSecondYearSummary);
                        $('#2nd_year_coverage').text(response.insuranceCoverageSecondYearSummary);
                        $('#summary-insurance-coverage-third-year').text(response.insuranceCoverageThirdYearSummary);
                        $('#3rd_year_coverage').text(response.insuranceCoverageThirdYearSummary);
                        $('#summary-insurance-coverage-fourth-year').text(response.insuranceCoverageFourthYearSummary);
                        $('#4th_year_coverage').text(response.insuranceCoverageFourthYearSummary);
                        $('#summary-insurance-premium-second-year').text(response.insurancePremiumSecondYearSummary);
                        $('#summary-insurance-premium-third-year').text(response.insurancePremiumThirdYearSummary);
                        $('#summary-insurance-premium-fourth-year').text(response.insurancePremiumFourthYearSummary);

                        $('#2nd_year_date').text(response.secondYearInsuranceDate);
                        $('#3rd_year_date').text(response.thirdYearInsuranceDate);
                        $('#4th_year_date').text(response.fourthYearInsuranceDate);

                        var product_category = $('#product_category').val();
                        var product_type = $('#product_type').val();

                        if(response.insuranceCoverageSecondYear > 0){
                            $('#compute_second_year').prop('checked', true);

                            if(product_type == 'Refinancing' || product_type == 'Restructure'){
                                $('#insurance_coverage_second_year').attr('readonly', true);
                                $('#insurance_premium_second_year').attr('readonly', true);
                            }
                            else{
                                if(product_category != '1' && product_category != '2'){
                                    $('#insurance_coverage_second_year').attr('readonly', false);
                                    $('#insurance_premium_second_year').attr('readonly', false);
                                }
                                else{
                                    $('#insurance_coverage_second_year').attr('readonly', true);
                                    $('#insurance_premium_second_year').attr('readonly', true);
                                }
                            }                            
                        }
                        else{
                            $('#compute_second_year').prop('checked', false);
                        }

                        if(response.insuranceCoverageThirdYear > 0){
                            $('#compute_third_year').prop('checked', true);

                            if(product_type == 'Refinancing' || product_type == 'Restructure'){
                                $('#insurance_coverage_third_year').attr('readonly', true);
                                $('#insurance_premium_third_year').attr('readonly', true);
                            }
                            else{
                                if(product_category != '1' && product_category != '2'){
                                    $('#insurance_coverage_third_year').attr('readonly', false); 
                                    $('#insurance_premium_third_year').attr('readonly', false); 
                                }
                                else{
                                    $('#insurance_coverage_third_year').attr('readonly', true); 
                                    $('#insurance_premium_third_year').attr('readonly', true); 
                                }
                            }
                        }
                        else{
                            $('#compute_third_year').prop('checked', false);
                        }

                        if(response.insuranceCoverageFourthYear > 0){
                            $('#compute_fourth_year').prop('checked', true);

                            if(product_type == 'Refinancing' || product_type == 'Restructure'){
                                $('#insurance_premium_fourth_year').attr('readonly', true);
                                $('#insurance_premium_fourth_year').attr('readonly', true);
                            }
                            else{
                                if(product_category != '1' && product_category != '2'){
                                    $('#insurance_premium_fourth_year').attr('readonly', false); 
                                    $('#insurance_premium_fourth_year').attr('readonly', false); 
                                }
                                else{
                                    $('#insurance_premium_fourth_year').attr('readonly', true); 
                                    $('#insurance_premium_fourth_year').attr('readonly', true); 
                                }
                            }                            
                        }
                        else{
                            $('#compute_fourth_year').prop('checked', false);
                        }
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Sales Proposal Renewal Amount Details Error', response.message, 'danger');
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
            break;
        case 'get sales proposal deposit amount details':
            var sales_proposal_deposit_amount_id = sessionStorage.getItem('sales_proposal_deposit_amount_id');
        
            $.ajax({
                url: 'controller/sales-proposal-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    sales_proposal_deposit_amount_id : sales_proposal_deposit_amount_id, 
                    transaction : transaction
                },
                success: function(response) {
                    if (response.success) {
                        $('#sales_proposal_deposit_amount_id').val(sales_proposal_deposit_amount_id);
                        $('#deposit_date').val(response.depositDate);
                        $('#reference_number').val(response.referenceNumber);
                        $('#deposit_amount').val(response.depositAmount);
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Sales Proposal Deposit Amount Total Details Error', response.message, 'danger');
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
            break;
        case 'get sales proposal additional job order details':
            var sales_proposal_additional_job_order_id = sessionStorage.getItem('sales_proposal_additional_job_order_id');
                
            $.ajax({
                url: 'controller/sales-proposal-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    sales_proposal_additional_job_order_id : sales_proposal_additional_job_order_id, 
                    transaction : transaction
                },
                success: function(response) {
                    if (response.success) {
                        $('#sales_proposal_additional_job_order_id').val(sales_proposal_additional_job_order_id);
                        $('#job_order_number').val(response.jobOrderNumber);
                        $('#job_order_date').val(response.jobOrderDate);
                        $('#particulars').val(response.particulars);
                        $('#additional_job_order_cost').val(response.cost);
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Sales Proposal Additional Job Order Details Error', response.message, 'danger');
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
            break;
        case 'get sales proposal confirmation details':
            var sales_proposal_id = $('#sales-proposal-id').text();

            $.ajax({
                url: 'controller/sales-proposal-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                   sales_proposal_id : sales_proposal_id, 
                    transaction : transaction
                },
                success: function(response) {
                    if (response.success) {
                        if($('#client-confirmation-image').length){
                            document.getElementById('client-confirmation-image').src = response.clientConfirmation;
                        }

                        if($('#comaker-confirmation-image').length){
                            document.getElementById('comaker-confirmation-image').src = response.comakerConfirmation;
                        }

                        if($('#credit-advice-image').length){
                            document.getElementById('credit-advice-image').src = response.creditAdvice;
                        }

                        if($('#new-engine-stencil-image').length){
                            document.getElementById('new-engine-stencil-image').src = response.newEngineStencil;
                        }

                        if($('#additional-job-order-confirmation-image').length){
                            document.getElementById('additional-job-order-confirmation-image').src = response.additionalJobOrderConfirmationImage;
                        }

                        if($('#quality-control-form-image').length){
                            document.getElementById('quality-control-form-image').src = response.qualityControlForm;
                        }

                        if($('#outgoing-checklist-image').length){
                            document.getElementById('outgoing-checklist-image').src = response.outgoingChecklist;
                        }

                        if($('#unit-image').length){
                            document.getElementById('unit-image').src = response.unitImage;
                        }

                        if($('#unit-back').length){
                            document.getElementById('unit-back').src = response.unitBack;
                        }

                        if($('#unit-left').length){
                            document.getElementById('unit-left').src = response.unitLeft;
                        }

                        if($('#unit-right').length){
                            document.getElementById('unit-right').src = response.unitRight;
                        }

                        if($('#unit-interior').length){
                            document.getElementById('unit-interior').src = response.unitInterior;
                        }
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Sales Proposal Additional Job Order Details Error', response.message, 'danger');
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
            break;
        case 'get comaker details':
            var comaker_id = $('#comaker_id').val();
                
            $.ajax({
                url: 'controller/customer-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    comaker_id : comaker_id, 
                    transaction : transaction
                },
                success: function(response) {
                    if (response.success) {    
                        $('#summary-comaker-name').text(response.comakerName);
                        $('#summary-comaker-address').text(response.comakerAddress);
                        $('#summary-comaker-mobile').text(response.comakerMobile);
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Co-Maker Details Error', response.message, 'danger');
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
            break;
        case 'get additional maker details':
            var comaker_id = $('#additional_maker_id').val();
                
            $.ajax({
                url: 'controller/customer-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    comaker_id : comaker_id, 
                    transaction : transaction
                },
                success: function(response) {
                    if (response.success) {    
                        $('#summary-additional-maker-name').text(response.comakerName);
                        $('#summary-additional-maker-address').text(response.comakerAddress);
                        $('#summary-additional-maker-mobile').text(response.comakerMobile);
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Co-Maker Details Error', response.message, 'danger');
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
            break;
        case 'get comaker2 details':
            var comaker_id = $('#comaker_id2').val();
                
            $.ajax({
                url: 'controller/customer-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    comaker_id : comaker_id, 
                    transaction : transaction
                },
                success: function(response) {
                    if (response.success) {    
                        $('#summary-additional-comaker-name').text(response.comakerName);
                        $('#summary-additional-comaker-address').text(response.comakerAddress);
                        $('#summary-additional-comaker-mobile').text(response.comakerMobile);
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Co-Maker Details Error', response.message, 'danger');
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
            break;
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
                            $('#delivery_price').val(response.productPrice);
                            $('#old_color').val(response.colorName);
                            $('#old_body').val(response.bodyTypeName);
                            $('#old_engine').val(response.engineNumber);

                            $('#product_engine_number').text(response.engineNumber);
                            $('#product_chassis_number').text(response.chassisNumber);
                            $('#product_plate_number').text(response.plateNumber);
                            $('#product_category').val(response.productCategoryID);

                            $('#summary-stock-no').text(response.summaryStockNumber);
                            $('#summary-engine-no').text(response.engineNumber);
                            $('#summary-chassis-no').text(response.chassisNumber);
                            $('#summary-plate-no').text(response.plateNumber);

                            $('#insurance_unit_no').text(response.summaryStockNumber);
                            $('#insurance_engine_no').text(response.engineNumber);
                            $('#insurance_chassis_no').text(response.chassisNumber);
                            $('#insurance_plate_no').text(response.plateNumber);
                            $('#insurance_color').text(response.colorName);

                            if($('#product_cost_label').length){
                                $('#product_cost_label').text(parseFloat(response.productCost).toLocaleString("en-US"));
                            }
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
        case 'get sales proposal other product details':
            var sales_proposal_id = $('#sales-proposal-id').text();
    
            $.ajax({
                url: 'controller/sales-proposal-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    sales_proposal_id : sales_proposal_id, 
                    transaction : transaction
                },
                success: function(response) {
                    if (response.success) {
                        $('#year_model').val(response.yearModel);
                        $('#insurance_year_model').text(response.yearModel);
                        $('#cr_no').val(response.crNo);
                        $('#cr_no_label').text(response.crNo);
                        $('#mv_file_no').val(response.mvFileNo);
                        $('#insurance_mv_file_no').text(response.mvFileNo);
                        $('#make').val(response.make);
                        $('#insurance_make').text(response.make);
                        $('#product_description').val(response.productDescription);
                        $('#other-details-gatepass1').text(response.productDescription);
                        $('#other-details-gatepass2').text(response.productDescription);
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Other Product Details Error', response.message, 'danger');
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
            break;
        case 'get sales proposal insurance request details':
            var sales_proposal_id = $('#sales-proposal-id').text();
    
            $.ajax({
                url: 'controller/sales-proposal-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    sales_proposal_id : sales_proposal_id, 
                    transaction : transaction
                },
                success: function(response) {
                    if (response.success) {
                        $('#od_theft').text(response.odTheft);
                        $('#od_rate').text(response.odRate);
                        $('#od_theft_premium').text(response.odTheftPremium);
                        $('#total_premium').text(response.odTheftPremium);
                        $('#vat_premium').text(response.vatPremium);
                        $('#doc_stamps').text(response.docStamps);
                        $('#local_govt_tax').text(response.localGovtTax);
                        $('#gross').text(response.gross);
                        $('#1st_year_coverage').text(response.odTheft);
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Other Product Details Error', response.message, 'danger');
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
            break;
    }
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
    
    $('#summary-deliver-price').text(parseFloat(total.toFixed(2)).toLocaleString("en-US"));

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

function traverseTabs(direction) {
    const $tabs = $('.nav-link');
    const $active = $tabs.filter('.active');
    const currentStep = $active.data('step');

    const totalTabs = $tabs.length;
    const sales_proposal_status = $('#sales_proposal_status').val();
    const sales_proposal_type   = $('#sales_proposal_type').val();
    const product_type          = $('#product_type').val();

    // --- Helpers ---
    const toggleButton = (selector, show) => {
        const $el = $(selector);
        if ($el.length) $el.toggleClass('d-none', !show);
    };

    const validateAndSubmit = (formSelector) => {
        const $form = $(formSelector);

        if ($form.length) {
            // 🔒 Check if form is disabled (all inputs/selects disabled)
            const isDisabled = $form.find(':input:enabled').length === 0;

            if (!isDisabled) {
                if ($form.valid()) {
                    $form.submit();   // only submit if enabled + valid
                    return true;
                } else {
                    return false;     // stops navigation if validation fails
                }
            }
        }
        return true; // if no form, or if disabled, just allow navigation
    };


    const findNextVisibleIndex = (startIndex, dir) => {
        let index = startIndex;
        const increment = dir === 'next' ? 1 : -1;
        while (true) {
            index = (index + increment + totalTabs) % totalTabs;
            if (!$tabs.eq(index).hasClass('d-none')) return index;
            if (index === startIndex) break;
        }
        return startIndex;
    };

    // --- Step Configuration (examples) ---
    const stepConfig = {
        proposal: { form: '#sales-proposal-form' },
        unit: { form: '#sales-proposal-unit-details-form' },
        fuel: { form: '#sales-proposal-fuel-details-form' },
        refinancing: {
            form: '#sales-proposal-refinancing-details-form',
            onEnter: () => {
                const type = product_type;
                $('#sales-proposal-tab-4').text(
                    type === 'Brand New' ? 'Brand New Details' :
                    type === 'Restructure' ? 'Restructure Details' :
                    'Refinancing Details'
                );
            }
        },
        pricing: { form: '#sales-proposal-pricing-computation-form' },
        otherCharges: { form: '#sales-proposal-other-charges-form' },
        renewal: { form: '#sales-proposal-renewal-amount-form' },
        otherProduct: { form: '#sales-proposal-other-product-details-form' },
        summary: {
            onEnter: () => {
                [
                    '#tag-for-initial-approval-button',
                    '#tag-for-review-button',
                    '#sales-proposal-initial-approval-button',
                    '#sales-proposal-final-approval-button',
                    '#sales-proposal-reject-button',
                    '#sales-proposal-cancel-button',
                    '#for-ci-sales-proposal-button',
                    '#sales-proposal-set-to-draft-button',
                    '#for-dr-sales-proposal-button',
                    '#approve-installment-sales-button',
                    '#print-button'
                ].forEach(sel => toggleButton(sel, true));
            },
            onLeave: () => {
                [
                    '#tag-for-initial-approval-button',
                    '#tag-for-review-button',
                    '#sales-proposal-initial-approval-button',
                    '#sales-proposal-final-approval-button',
                    '#sales-proposal-reject-button',
                    '#sales-proposal-cancel-button',
                    '#for-ci-sales-proposal-button',
                    '#sales-proposal-set-to-draft-button',
                    '#for-dr-sales-proposal-button',
                    '#approve-installment-sales-button',
                    '#summary-print-button',
                    '#summary-print-button',
                    '#print-button'
                ].forEach(sel => toggleButton(sel, false));
            }
        },
        jobOrder: {
            onEnter: () => toggleButton('#add-sales-proposal-job-order-button', true),
            onLeave: () => toggleButton('#add-sales-proposal-job-order-button', false)
        },
        additionalJobOrder: {
            onEnter: () => toggleButton('#add-sales-proposal-additional-job-order-button', true),
            onLeave: () => toggleButton('#add-sales-proposal-additional-job-order-button', false)
        },
        deposit: {
            onEnter: () => toggleButton('#add-sales-proposal-deposit-amount-button', true),
            onLeave: () => toggleButton('#add-sales-proposal-deposit-amount-button', false)
        },
        gatepass: {
            onEnter: () => toggleButton('#gatepass-print-button', true),
            onLeave: () => toggleButton('#gatepass-print-button', false)
        },
        ciReport: {
            onEnter: () => toggleButton('#complete-ci-button', true),
            onLeave: () => toggleButton('#complete-ci-button', false)
        },
        online: {
            onEnter: () => toggleButton('#online-print-button', true),
            onLeave: () => toggleButton('#online-print-button', false)
        },
        authorization: {
            onEnter: () => toggleButton('#authorization-print-button', true),
            onLeave: () => toggleButton('#authorization-print-button', false)
        },
        promissory: {
            onEnter: () => toggleButton('#pn-print-button', true),
            onLeave: () => toggleButton('#pn-print-button', false)
        },
        disclosure: {
            onEnter: () => toggleButton('#disclosure-print-button', true),
            onLeave: () => toggleButton('#disclosure-print-button', false)
        },
        insurance: {
            onEnter: () => toggleButton('#insurance-request-print-button', true),
            onLeave: () => toggleButton('#insurance-request-print-button', false)
        }
    };

    // --- Figure out next tab ---
    const currentIndex = $tabs.index($active);
    const nextIndex = (direction === 'next' || direction === 'previous')
        ? findNextVisibleIndex(currentIndex, direction)
        : (direction === 'first' ? 0 : totalTabs - 1);

    const $nextTab = $tabs.eq(nextIndex);
    const nextStep = $nextTab.data('step');

    // --- Validate current form if moving forward ---
    if (direction === 'next' && stepConfig[currentStep]?.form) {
        if (!validateAndSubmit(stepConfig[currentStep].form)) return;
    }

    // --- Run leave hooks ---
    if (stepConfig[currentStep]?.onLeave) {
        stepConfig[currentStep].onLeave();
    }

    // --- Switch tab ---
    $nextTab.tab('show');

    // --- Run enter hooks ---
    if (stepConfig[nextStep]?.onEnter) {
        stepConfig[nextStep].onEnter();
    }

    // --- Update progress ---
    const visibleTabs = $tabs.not('.d-none').length;
    const progress = ((nextIndex + 1) / visibleTabs) * 100;
    $('#bar .progress-bar').css('width', progress + '%');

   // --- Update nav buttons (first/prev/next/last) ---
    const $visibleTabs = $tabs.not('.d-none');
    const firstVisibleIndex = $visibleTabs.first().index();
    const lastVisibleIndex  = $visibleTabs.last().index();

    if ($visibleTabs.length === 1) {
        // only one tab visible → disable everything
        $('#first-step, #previous-step, #last-step, #next-step').addClass('disabled');
    } else {
        if (nextIndex === firstVisibleIndex) {
            $('#first-step, #previous-step').addClass('disabled');
            $('#last-step, #next-step').removeClass('disabled');
        } else if (nextIndex === lastVisibleIndex) {
            $('#last-step, #next-step').addClass('disabled');
            $('#first-step, #previous-step').removeClass('disabled');
        } else {
            $('#first-step, #previous-step, #last-step, #next-step').removeClass('disabled');
        }
    }

}

function disableFormAndSelect2(formId) {
    // Disable all form elements
    var form = document.getElementById(formId);
    var elements = form.elements;
    for (var i = 0; i < elements.length; i++) {
        elements[i].disabled = true;
    }

    // Disable Select2 dropdowns
    var select2Dropdowns = form.getElementsByClassName('select2');
    for (var j = 0; j < select2Dropdowns.length; j++) {
        var select2Instance = $(select2Dropdowns[j]);
        select2Instance.select2('destroy');
        select2Instance.prop('disabled', true);
    }
}