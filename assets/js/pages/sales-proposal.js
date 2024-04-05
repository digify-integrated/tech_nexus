(function($) {
    'use strict';

    $(function() {
        if($('#add-sales-proposal-form').length){
            addSalesProposalForm();
        }

        if($('#sales-proposal-form').length){
            salesProposalForm();
        }

        if($('#sales-proposal-ob-order-confirmation-form').length){
            salesProposalAdditionalJobOrderConfirmationImageForm();
        }

        if($('#sales-proposal-table').length){
            salesProposalTable('#sales-proposal-table');
        }

        if($('#all-sales-proposal-table').length){
            allSalesProposalTable('#all-sales-proposal-table');
        }

        if($('#sales-proposal-change-request-table').length){
            salesProposalChangeRequestTable('#sales-proposal-change-request-table');
        }

        if($('#approved-sales-proposal-table').length){
            approvedSalesProposalTable('#approved-sales-proposal-table');
        }

        if($('#sales-proposal-for-ci-table').length){
            salesProposalForCITable('#sales-proposal-for-ci-table');
        }

        if($('#sales-proposal-id').length){
            
            displayDetails('get sales proposal details');
            
            if($('#details-tab').length){
                displayDetails('get sales proposal accessories total details');
                displayDetails('get sales proposal job order total details');
                displayDetails('get sales proposal additional job order total details');
                displayDetails('get sales proposal pricing computation details');
                displayDetails('get sales proposal other charges details');
                displayDetails('get sales proposal renewal amount details');
            }
            if($('#summary-tab').length){                    
                salesProposalSummaryJobOrderTable();
                salesProposalSummaryAdditionalJobOrderTable();
                salesProposalSummaryDepositTable();
            }

            if($('#sales-proposal-client-confirmation-form').length){
                salesProposalClientConfirmationForm();
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

            if($('#sales-proposal-credit-advice-form').length){
                salesProposalCreditAdviceForm();
            }

            if($('#sales-proposal-engine-stencil-form').length){
                salesProposalEngineStencilForm();
            }

            if($('#sales-proposal-initial-approval-form').length){
                salesProposalInitalApprovalForm();
            }

            $(document).on('click','#sales-proposal-initial-approval',function() {
                resetModalForm("sales-proposal-initial-approval-form");
            });

            if($('#sales-proposal-final-approval-form').length){
                salesProposalFinalApprovalForm();
            }

            $(document).on('click','#sales-proposal-final-approval',function() {
                resetModalForm("sales-proposal-final-approval-form");
            });
            
            if($('#sales-proposal-reject-form').length){
                salesProposalRejectForm();
            }

            $(document).on('click','#sales-proposal-reject',function() {
                resetModalForm("sales-proposal-reject-form");
            });
            
            if($('#sales-proposal-cancel-form').length){
                salesProposalCancelForm();
            }

            $(document).on('click','#sales-proposal-cancel',function() {
                resetModalForm("sales-proposal-cancel-form");
            });
            
            if($('#sales-proposal-set-to-draft-form').length){
                salesProposalSetToDraftForm();
            }

            $(document).on('click','#sales-proposal-set-to-draft',function() {
                resetModalForm("sales-proposal-set-to-draft-form");
            });

            if($('#sales-proposal-accessories-table').length){
                salesProposalAccessoriesTable('#sales-proposal-accessories-table');
            }

            if($('#sales-proposal-accessories-form').length){
                salesProposalAccessoriesForm();
            }

            if($('#sales-proposal-job-order-table').length){
                salesProposalJobOrderTable('#sales-proposal-job-order-table');
            }

            if($('#sales-proposal-job-order-form').length){
                salesProposalJobOrderForm();
            }

            if($('#sales-proposal-additional-job-order-table').length){
                salesProposalAdditionalJobOrderTable('#sales-proposal-additional-job-order-table');
            }

            if($('#sales-proposal-additional-job-order-form').length){
                salesProposalAdditionalJobOrderForm();
            }

            if($('#sales-proposal-deposit-amount-table').length){
                salesProposalDepositAmountTable('#sales-proposal-deposit-amount-table');
            }

            if($('#sales-proposal-deposit-amount-form').length){
                salesProposalDepositAmountForm();
            }

            $(document).on('change','#term_length',function() {
                calculatePricingComputation();
            });

            $(document).on('change','#interest_rate',function() {
                calculatePricingComputation();
            });

            $(document).on('change','#delivery_price',function() {
                calculatePricingComputation();
                calculateRenewalAmount();
            });

            $(document).on('change','#cost_of_accessories',function() {
                calculatePricingComputation();
            });

            $(document).on('change','#reconditioning_cost',function() {
                calculatePricingComputation();
            });

            $(document).on('change','#downpayment',function() {
                calculatePricingComputation();
            });

            $(document).on('change','#insurance_premium',function() {
                calculateTotalOtherCharges();
            });

            $(document).on('change','#handling_fee',function() {
                calculateTotalOtherCharges();
            });

            $(document).on('change','#transfer_fee',function() {
                calculateTotalOtherCharges();
            });

            $(document).on('change','#registration_fee',function() {
                calculateTotalOtherCharges();
            });

            $(document).on('change','#doc_stamp_tax',function() {
                calculateTotalOtherCharges();
            });

            $(document).on('change','#transaction_fee',function() {
                calculateTotalOtherCharges();
            });

            $(document).on('change','#add_on_charge',function() {
                calculateTotalDeliveryPrice();
            });

            $(document).on('change','#nominal_discount',function() {
                calculateTotalDeliveryPrice();
            });

            $(document).on('click','#add-sales-proposal-accessories',function() {
                resetModalForm("sales-proposal-accessories-form");
            });

            $(document).on('click','.update-sales-proposal-accessories',function() {
                const sales_proposal_accessories_id = $(this).data('sales-proposal-accessories-id');
        
                sessionStorage.setItem('sales_proposal_accessories_id', sales_proposal_accessories_id);
                
                displayDetails('get sales proposal accessories details');
            });

            $(document).on('click','.delete-sales-proposal-accessories',function() {
                const sales_proposal_accessories_id = $(this).data('sales-proposal-accessories-id');
                const transaction = 'delete sales proposal accessories';
        
                Swal.fire({
                    title: 'Confirm Accessories Deletion',
                    text: 'Are you sure you want to delete this accessories?',
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
                                sales_proposal_accessories_id : sales_proposal_accessories_id, 
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Delete Accessories Success', 'The accessories has been deleted successfully.', 'success');
                                    reloadDatatable('#sales-proposal-accessories-table');
                                    displayDetails('get sales proposal accessories total details');
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
                                        showNotification('Delete Accessories Error', response.message, 'danger');
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

            $(document).on('click','#add-sales-proposal-job-order',function() {
                resetModalForm("sales-proposal-job-order-form");
            });

            $(document).on('click','.update-sales-proposal-job-order',function() {
                const sales_proposal_job_order_id = $(this).data('sales-proposal-job-order-id');
        
                sessionStorage.setItem('sales_proposal_job_order_id', sales_proposal_job_order_id);
                
                displayDetails('get sales proposal job order details');
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
                                    displayDetails('get sales proposal job order total details');
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

            if($('#sales-proposal-pricing-computation-form').length){
                salesProposalPricingComputationForm();
            }

            if($('#sales-proposal-other-charges-form').length){
                salesProposalOtherChargesForm();
            }

            if($('#sales-proposal-renewal-amount-form').length){
                salesProposalRenewalAmountForm();
            }

            $(document).on('change','#compute_second_year',function() {
                calculateRenewalAmount();
            });

            $(document).on('change','#compute_third_year',function() {
                calculateRenewalAmount();
            });

            $(document).on('change','#compute_fourth_year',function() {
                calculateRenewalAmount();
            });
        }

        $(document).on('change','#product_type',function() {
            if($(this).val() == 'Unit'){
                $('.unit-row').removeClass('d-none'); 

                $('.refinancing-row').addClass('d-none'); 
                $('#ref_engine_no').val(''); 
                $('#ref_chassis_no').val(''); 
                $('#ref_plate_no').val(''); 

                $('.fuel-row').addClass('d-none'); 
                checkOptionExist('#fuel_type', '', '');
                $('#fuel_quantity').val(''); 
            }
            else if($(this).val() == 'Fuel'){
                $('.fuel-row').removeClass('d-none'); 

                $('.refinancing-row').addClass('d-none'); 
                $('#ref_engine_no').val(''); 
                $('#ref_chassis_no').val(''); 
                $('#ref_plate_no').val(''); 

                $('.unit-row').addClass('d-none'); 
                checkOptionExist('#product_id', '', '');
            }
            else if($(this).val() == 'Refinancing'){
                $('.refinancing-row').removeClass('d-none'); 

                $('.unit-row').addClass('d-none'); 
                checkOptionExist('#product_id', '', '');

                $('.fuel-row').addClass('d-none'); 
                checkOptionExist('#fuel_type', '', '');
                $('#fuel_quantity').val(''); 
            }
            else{
                $('.refinancing-row').addClass('d-none'); 
                $('#ref_engine_no').val(''); 
                $('#ref_chassis_no').val(''); 
                $('#ref_plate_no').val(''); 

                $('.unit-row').addClass('d-none'); 
                checkOptionExist('#product_id', '', '');

                $('.fuel-row').addClass('d-none'); 
                checkOptionExist('#fuel_type', '', '');
                $('#fuel_quantity').val(''); 
            }

            if($(this).val() == 'Unit' || $(this).val() == 'Fuel'){
                if($('#delivery_price').length){
                    $('#delivery_price').prop('readonly', true);
                }
            }
            else{
                if($('#delivery_price').length){
                    $('#delivery_price').prop('readonly', false);
                }
            }
        });

        $(document).on('change','#transaction_type',function() {
            if($(this).val() == 'Bank Financing'){
                $('#financing-institution-row').removeClass('d-none'); 
            }
            else{
                $('#financing-institution-row').addClass('d-none');
                $('#financing_institution').val('');
            }
        });

        $(document).on('change','#for_change_color',function() {
            if($(this).val() == 'Yes'){
                $("#new_color").attr("readonly", false); 
            }
            else{
                $('#new_color').val('');
                $("#new_color").attr("readonly", true); 
            }
        });

        $(document).on('change','#for_change_body',function() {
            if($(this).val() == 'Yes'){
                $("#new_body").attr("readonly", false); 
            }
            else{
                $('#new_body').val('');
                $("#new_body").attr("readonly", true); 
            }
        });

        $(document).on('change','#for_change_engine',function() {
            if($(this).val() == 'Yes'){
                $("#new_engine").attr("readonly", false); 
            }
            else{
                $('#new_engine').val('');
                $("#new_engine").attr("readonly", true); 
            }
        });

        $(document).on('change','#fuel_quantity',function() {
            calculateTotalFuelDeliveryPrice();
        });

        $(document).on('change','#price_per_liter',function() {
            calculateTotalFuelDeliveryPrice();
        });

        $(document).on('change','#product_id',function() {  
            if($(this).val() != ''){
                displayDetails('get product details');
            }
            else{
                $('#product_engine_number').text('--');
                $('#product_chassis_number').text('--');
                $('#product_plate_number').text('--');
                $('#old_color').val('');
                $('#old_body').val('');
                $('#old_engine').val('');
            }
        });
        
        $(document).on('change','#comaker_id',function() {
            if($(this).val() != ''){
                displayDetails('get comaker details');
            }
            else{
                $('#comaker_file_as').text('--');
                $('#comaker_address').text('--');
                $('#comaker_email').text('--');
                $('#comaker_mobile').text('--');
                $('#comaker_telephone').text('--');
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

            calculatePaymentNumber();
        });
        
        $(document).on('click','#next-step-1-normal',function() {
            nextStep(1);
        });
        
        $(document).on('click','#next-step-1-modified',function() {
            nextStep(1);
        });

        $(document).on('click','#prev-step-2-modified',function() {
            prevStep(2);
        });

        $(document).on('click','#next-step-2-modified',function() {
            nextStep(2);
        });

        $(document).on('click','#prev-step-3-modified',function() {
            prevStep(3);
        });
        
        $(document).on('click','#next-step-1',function() {
            $("#sales-proposal-form").submit();
        });
        
        $(document).on('click','#prev-step-2',function() {
            prevStep(2);
        });
        
        $(document).on('click','#next-step-2',function() {
            nextStep(2);
        });
        
        $(document).on('click','#prev-step-3',function() {
            prevStep(3);
        });
        
        $(document).on('click','#next-step-3',function() {
            if($('#delivery_price').valid() && $('#nominal_discount').valid() ){
                nextStep(3);
                $("#sales-proposal-pricing-computation-form").submit();
                $("#sales-proposal-other-charges-form").submit();
                $("#sales-proposal-renewal-amount-form").submit();
            }
            else{
                showNotification('Form Required', 'Kindly fill-out all of the required fields before proceeding.', 'warning');
            }
        });

        $(document).on('click','#next-step-3-normal',function() {
            nextStep(3);
        });
        
        $(document).on('click','#prev-step-4',function() {
            prevStep(4);
        });
        
        $(document).on('click','#next-step-4',function() {
            nextStep(4);
        });
        
        $(document).on('click','#next-step-5',function() {
            nextStep(5);
        });
        
        $(document).on('click','#prev-step-5',function() {
            prevStep(5);
        });
        
        $(document).on('click','#prev-step-6',function() {
            prevStep(6);
        });
        
        $(document).on('change','#payment_frequency',function() {
            calculatePaymentNumber();
        });
        
        $(document).on('change','#term_length',function() {
            calculatePaymentNumber();
        });
        
        $(document).on('focusout', '#start_date', function () {
            calculatePaymentNumber();
        });

        $(document).on('click','#apply-filter',function() {
            if($('#sales-proposal-table').length){
                salesProposalTable('#sales-proposal-table');
            }
    
            if($('#all-sales-proposal-table').length){
                allSalesProposalTable('#all-sales-proposal-table');
            }
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
                title: 'Confirm Tagging of Sales Proposeal For DR',
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
        { 'data' : 'CUSTOMER' },
        { 'data' : 'PRODUCT_TYPE' },
        { 'data' : 'PRODUCT' },
        { 'data' : 'STATUS' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '1%','bSortable': false, 'aTargets': 0 },
        { 'width': '14%', 'aTargets': 1 },
        { 'width': '25%', 'aTargets': 2 },
        { 'width': '15%', 'aTargets': 3 },
        { 'width': '25%', 'aTargets': 4 },
        { 'width': '10%', 'aTargets': 5 },
        { 'width': '10%','bSortable': false, 'aTargets': 6 }
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
        'order': [[ 1, 'desc' ]],
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

function allSalesProposalTable(datatable_name, buttons = false, show_all = false){
    const type = 'all sales proposal table';
    var sales_proposal_status_filter = $('.sales-proposal-status-filter:checked').val();

    var settings;

    const column = [ 
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'SALES_PROPOSAL_NUMBER' },
        { 'data' : 'CUSTOMER' },
        { 'data' : 'PRODUCT_TYPE' },
        { 'data' : 'PRODUCT' },
        { 'data' : 'CREATED_DATE' },
        { 'data' : 'STATUS' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '1%','bSortable': false, 'aTargets': 0 },
        { 'width': '14%', 'aTargets': 1 },
        { 'width': '15%', 'aTargets': 2 },
        { 'width': '15%', 'aTargets': 3 },
        { 'width': '15%', 'aTargets': 4 },
        { 'width': '10%', 'aTargets': 5},
        { 'width': '10%', 'aTargets': 6 },
        { 'width': '10%','bSortable': false, 'aTargets': 7 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_sales_proposal_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {'type' : type, 'sales_proposal_status_filter' : sales_proposal_status_filter},
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
        { 'width': '25%', 'aTargets': 4 },
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
        { 'data' : 'STATUS' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '15%', 'aTargets': 0 },
        { 'width': '15%', 'aTargets': 1 },
        { 'width': '15%', 'aTargets': 2 },
        { 'width': '25%', 'aTargets': 3 },
        { 'width': '25%', 'aTargets': 4 },
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
        { 'width': '25%', 'aTargets': 4 },
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

function salesProposalAccessoriesTable(datatable_name, buttons = false, show_all = false){
    const sales_proposal_id = $('#sales-proposal-id').text();
    const type = 'sales proposal accessories table';
    var settings;

    const column = [ 
        { 'data' : 'ACCESSORIES' },
        { 'data' : 'COST' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '42%', 'aTargets': 0 },
        { 'width': '42%', 'aTargets': 1 },
        { 'width': '16%','bSortable': false, 'aTargets': 2 }
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

function salesProposalSummaryAccessoriesTable(){
    const sales_proposal_id = $('#sales-proposal-id').text();
    const type = 'summary accessories table';

    $.ajax({
        type: "POST",
        url: "view/_sales_proposal_generation.php",
        dataType: 'json',
        data: { type: type, sales_proposal_id: sales_proposal_id },
        success: function (result) {
            document.getElementById('summary-accessories-table').innerHTML = result[0].table;
        }
    });
}

function salesProposalJobOrderTable(datatable_name, buttons = false, show_all = false){
    const sales_proposal_id = $('#sales-proposal-id').text();
    const type = 'sales proposal job order table';
    var settings;

    const column = [ 
        { 'data' : 'JOB_ORDER' },
        { 'data' : 'COST' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '42%', 'aTargets': 0 },
        { 'width': '42%', 'aTargets': 1 },
        { 'width': '16%','bSortable': false, 'aTargets': 2 }
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

function salesProposalAdditionalJobOrderTable(datatable_name, buttons = false, show_all = false){
    const sales_proposal_id = $('#sales-proposal-id').text();
    const type = 'sales proposal additional job order table';
    var settings;

    const column = [ 
        { 'data' : 'JOB_ORDER_NUMBER' },
        { 'data' : 'JOB_ORDER_DATE' },
        { 'data' : 'PARTICULARS' },
        { 'data' : 'COST' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '25%', 'aTargets': 0 },
        { 'width': '15%', 'aTargets': 1 },
        { 'width': '15%', 'aTargets': 2 },
        { 'width': '30%', 'aTargets': 3 },
        { 'width': '15%','bSortable': false, 'aTargets': 4 }
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
        { 'width': '25%', 'aTargets': 0 },
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
    };

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

function addSalesProposalForm(){
    $('#add-sales-proposal-form').validate({
        rules: {
            product_type: {
                required: true
            },
            product_id: {
                required: {
                    depends: function(element) {
                        return $("select[name='product_type']").val() === 'Unit';
                    }
                }
            },
            fuel_type: {
                required: {
                    depends: function(element) {
                        return $("select[name='product_type']").val() === 'Fuel';
                    }
                }
            },
            fuel_quantity: {
                required: {
                    depends: function(element) {
                        return $("select[name='product_type']").val() === 'Fuel';
                    }
                }
            },
            ref_engine_no: {
                required: {
                    depends: function(element) {
                        return $("select[name='product_type']").val() === 'Refinancing';
                    }
                }
            },
            ref_chassis_no: {
                required: {
                    depends: function(element) {
                        return $("select[name='product_type']").val() === 'Refinancing';
                    }
                }
            },
            ref_plate_no: {
                required: {
                    depends: function(element) {
                        return $("select[name='product_type']").val() === 'Refinancing';
                    }
                }
            },
            transaction_type: {
                required: true
            },
            renewal_tag: {
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
            number_of_payments: {
                required: true
            },
            payment_frequency: {
                required: true
            },
            first_due_date: {
                required: true
            },
            initial_approving_officer: {
                required: true
            },
            final_approving_officer: {
                required: true
            },
            for_registration: {
                required: true
            },
            with_cr: {
                required: true
            },
            for_transfer: {
                required: true
            },
            for_change_color: {
                required: true
            },
            new_color: {
                required: {
                    depends: function(element) {
                        return $("select[name='for_change_color']").val() === 'Yes';
                    }
                }
            },
            for_change_body: {
                required: true
            },
            new_body: {
                required: {
                    depends: function(element) {
                        return $("select[name='for_change_body']").val() === 'Yes';
                    }
                }
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
        },
        messages: {
            product_type: {
                required: 'Please choose the product type'
            },
            product_id: {
                required: 'Please choose the stock'
            },
            fuel_type: {
                required: 'Please choose the fuel type'
            },
            fuel_quantity: {
                required: 'Please choose the fuel quantity'
            },
            transaction_type: {
                required: 'Please choose the transaction type'
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
            renewal_tag: {
                required: 'Please choose the renewal tag'
            },
            financing_institution: {
                required: 'Please enter the financing institution'
            },
            comaker_id: {
                required: 'Please choose the comaker'
            },
            release_date: {
                required: 'Please choose the estimated date of release'
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
            number_of_payments: {
                required: 'Please enter the number of payments'
            },
            payment_frequency: {
                required: 'Please choose the payment frequency'
            },
            first_due_date: {
                required: 'Please choose the first due date'
            },
            initial_approving_officer: {
                required: 'Please choose the initial approving officer'
            },
            final_approving_officer: {
                required: 'Please choose the final approving officer'
            },
            new_color: {
                required: 'Please enter the new color'
            },
            new_body: {
                required: 'Please enter the new body'
            },
        },
        errorPlacement: function (error, element) {
            if (element.hasClass('select2') || element.hasClass('modal-select2')) {
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
            const customer_id = $('#customer-id').text();
            const transaction = 'save sales proposal';
        
            $.ajax({
                type: 'POST',
                url: 'controller/sales-proposal-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&sales_proposal_id=' + sales_proposal_id + '&customer_id=' + customer_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-data');           
                },
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
                    enableFormSubmitButton('submit-data', 'Submit');
                }
            });
        
            return false;
        }
    });
}

function salesProposalForm(){
    $('#sales-proposal-form').validate({
        rules: {
            product_type: {
                required: true
            },
            product_id: {
                required: {
                    depends: function(element) {
                        return $("select[name='product_type']").val() === 'Unit';
                    }
                }
            },
            fuel_type: {
                required: {
                    depends: function(element) {
                        return $("select[name='product_type']").val() === 'Fuel';
                    }
                }
            },
            fuel_quantity: {
                required: {
                    depends: function(element) {
                        return $("select[name='product_type']").val() === 'Fuel';
                    }
                }
            },
            price_per_liter: {
                required: {
                    depends: function(element) {
                        return $("select[name='product_type']").val() === 'Fuel';
                    }
                }
            },
            ref_engine_no: {
                required: {
                    depends: function(element) {
                        return $("select[name='product_type']").val() === 'Refinancing';
                    }
                }
            },
            ref_chassis_no: {
                required: {
                    depends: function(element) {
                        return $("select[name='product_type']").val() === 'Refinancing';
                    }
                }
            },
            ref_plate_no: {
                required: {
                    depends: function(element) {
                        return $("select[name='product_type']").val() === 'Refinancing';
                    }
                }
            },
            transaction_type: {
                required: true
            },
            renewal_tag: {
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
            commission_amount: {
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
            number_of_payments: {
                required: true
            },
            payment_frequency: {
                required: true
            },
            first_due_date: {
                required: true
            },
            initial_approving_officer: {
                required: true
            },
            final_approving_officer: {
                required: true
            },
            for_registration: {
                required: true
            },
            with_cr: {
                required: true
            },
            for_transfer: {
                required: true
            },
            for_change_color: {
                required: true
            },
            new_color: {
                required: {
                    depends: function(element) {
                        return $("select[name='for_change_color']").val() === 'Yes';
                    }
                }
            },
            for_change_body: {
                required: true
            },
            new_body: {
                required: {
                    depends: function(element) {
                        return $("select[name='for_change_body']").val() === 'Yes';
                    }
                }
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
        },
        messages: {
            product_type: {
                required: 'Please choose the product type'
            },
            product_id: {
                required: 'Please choose the stock'
            },
            fuel_type: {
                required: 'Please choose the fuel type'
            },
            fuel_quantity: {
                required: 'Please choose the fuel quantity'
            },
            price_per_liter: {
                required: 'Please enter the price per liter'
            },
            commission_amount: {
                required: 'Please enter the commission amount'
            },
            transaction_type: {
                required: 'Please choose the transaction type'
            },
            renewal_tag: {
                required: 'Please choose the renewal tag'
            },
            financing_institution: {
                required: 'Please enter the financing institution'
            },
            comaker_id: {
                required: 'Please choose the comaker'
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
            release_date: {
                required: 'Please choose the estimated date of release'
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
            number_of_payments: {
                required: 'Please enter the number of payments'
            },
            payment_frequency: {
                required: 'Please choose the payment frequency'
            },
            first_due_date: {
                required: 'Please choose the first due date'
            },
            initial_approving_officer: {
                required: 'Please choose the initial approving officer'
            },
            final_approving_officer: {
                required: 'Please choose the final approving officer'
            },
            new_color: {
                required: 'Please enter the new color'
            },
            new_body: {
                required: 'Please enter the new body'
            },
        },
        errorPlacement: function (error, element) {
            if (element.hasClass('select2') || element.hasClass('modal-select2')) {
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
                    displayDetails('get sales proposal details');
                    nextStep(1);
                }
            });
        
            return false;
        }
    });
}

function salesProposalAccessoriesForm(){
    $('#sales-proposal-accessories-form').validate({
        rules: {
            accessories: {
                required: true
            },
            accessories_cost: {
                required: true
            }
        },
        messages: {
            accessories: {
                required: 'Please enter the accessories'
            },
            accessories_cost: {
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
            const transaction = 'save sales proposal accessories';
        
            $.ajax({
                type: 'POST',
                url: 'controller/sales-proposal-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&sales_proposal_id=' + sales_proposal_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-sales-proposal-accessories');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert Accessories Success' : 'Update Accessories Success';
                        const notificationDescription = response.insertRecord ? 'The accessories has been inserted successfully.' : 'The accessories has been updated successfully.';
                        
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
                    enableFormSubmitButton('submit-sales-proposal-accessories', 'Submit');
                    $('#sales-proposal-accessories-offcanvas').offcanvas('hide');
                    reloadDatatable('#sales-proposal-accessories-table');
                    resetModalForm('sales-proposal-accessories-form');
                    displayDetails('get sales proposal accessories total details');
                    salesProposalSummaryAccessoriesTable();
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
            }
        },
        messages: {
            job_order: {
                required: 'Please enter the job order'
            },
            job_order_cost: {
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
            const transaction = 'save sales proposal job order';
        
            $.ajax({
                type: 'POST',
                url: 'controller/sales-proposal-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&sales_proposal_id=' + sales_proposal_id,
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
                    displayDetails('get sales proposal job order total details');
                    salesProposalSummaryJobOrderTable();
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
                    displayDetails('get sales proposal additional job order total details');
                    salesProposalSummaryAdditionalJobOrderTable();
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
                    salesProposalSummaryDepositTable();
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
            nominal_discount: {
                required: true
            }
        },
        messages: {
            delivery_price: {
                required: 'Please enter the delivery price'
            },
            nominal_discount: {
                required: 'Please enter the nominal discount'
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
            const transaction = 'save sales proposal pricing computation';
        
            $.ajax({
                type: 'POST',
                url: 'controller/sales-proposal-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&sales_proposal_id=' + sales_proposal_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-pricing-computation-data');
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
                },
                error: function(xhr, status, error) {
                    var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                    if (xhr.responseText) {
                        fullErrorMessage += `, Response: ${xhr.responseText}`;
                    }
                    showErrorDialog(fullErrorMessage);
                },
                complete: function() {
                    enableFormSubmitButton('submit-pricing-computation-data', 'Submit');
                    displayDetails('get sales proposal pricing computation details');
                }
            });
        
            return false;
        }
    });
}

function salesProposalOtherChargesForm(){
    $('#sales-proposal-other-charges-form').validate({
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
            const transaction = 'save sales proposal other charges';
        
            $.ajax({
                type: 'POST',
                url: 'controller/sales-proposal-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&sales_proposal_id=' + sales_proposal_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-other-charges-data');
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
                },
                error: function(xhr, status, error) {
                    var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                    if (xhr.responseText) {
                        fullErrorMessage += `, Response: ${xhr.responseText}`;
                    }
                    showErrorDialog(fullErrorMessage);
                },
                complete: function() {
                    enableFormSubmitButton('submit-other-charges-data', 'Submit');
                    displayDetails('get sales proposal other charges details');
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
                beforeSend: function() {
                    disableFormSubmitButton('submit-renewal-amount-data');
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
                },
                error: function(xhr, status, error) {
                    var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                    if (xhr.responseText) {
                        fullErrorMessage += `, Response: ${xhr.responseText}`;
                    }
                    showErrorDialog(fullErrorMessage);
                },
                complete: function() {
                    enableFormSubmitButton('submit-renewal-amount-data', 'Submit');
                    displayDetails('get sales proposal renewal amount details');
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

function salesProposalSetToDraftForm(){
    $('#sales-proposal-set-to-draft-form').validate({
        rules: {
            set_to_draft_reason: {
                required: true
            }
        },
        messages: {
            set_to_draft_reason: {
                required: 'Please enter the set to draft reason'
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
            const transaction = 'sales proposal set to draft';
        
            $.ajax({
                type: 'POST',
                url: 'controller/sales-proposal-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&sales_proposal_id=' + sales_proposal_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-sales-proposal-set-to-draft');
                },
                success: function (response) {
                    if (response.success) {
                        showNotification('Set Sales Proposal To Draft Success', 'The sales proposal has been set to draft successfully.', 'success');
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
                        setNotification('Credit Advice Upload Success', 'The credit advice has been uploaded successfully', 'success');
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
                        setNotification('New Engine Stencil Upload Success', 'The new engine stencil has been uploaded successfully', 'success');
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
                    enableFormSubmitButton('engine-stencil', 'Submit');
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
    $('#sales-proposal-ob-order-confirmation-form').validate({
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

function displayDetails(transaction){
    switch (transaction) {
        case 'get sales proposal details':
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
                        $('#referred_by').val(response.referredBy);
                        $('#release_date').val(response.releaseDate);
                        $('#start_date').val(response.startDate);
                        $('#term_length').val(response.termLength);
                        $('#number_of_payments').val(response.numberOfPayments);
                        $('#first_due_date').val(response.firstDueDate);
                        $('#remarks').val(response.remarks);
                        $('#financing_institution').val(response.financingInstitution);
                        $('#new_color').val(response.newColor);
                        $('#new_body').val(response.newBody);
                        $('#new_engine').val(response.newEngine);
                        $('#fuel_quantity').val(response.fuelQuantity);
                        $('#price_per_liter').val(response.pricePerLiter);
                        $('#commission_amount').val(response.commissionAmount);
                        $('#ref_chassis_no').val(response.refChassisNo);
                        $('#ref_plate_no').val(response.refPlateNo);
                        $('#ref_engine_no').val(response.refEngineNo);

                        if($('#product_type').val() == 'Refinancing'){
                            $('#summary-stock-no').text(response.refStockNo);
                            $('#summary-engine-no').text(response.refEngineNo);
                            $('#summary-chassis-no').text(response.refChassisNo);
                            $('#summary-plate-no').text(response.refPlateNo);
                        }

                        $('#summary-sales-proposal-number').text(response.salesProposalNumber);
                        $('#summary-referred-by').text(response.referredBy);
                        $('#summary-release-date').text(response.releaseDate);
                        $('#summary-term').text(response.termLength + ' ' + response.termType);
                        $('#summary-no-payments').text(response.numberOfPayments);
                        $('#summary-for-registration').text(response.forRegistration);
                        $('#summary-with-cr').text(response.withCR);
                        $('#summary-for-transfer').text(response.forTransfer);
                        $('#summary-remarks').text(response.remarks);
                        $('#summary-created-by').text(response.createdByName);
                        $('#summary-initial-approval-by').text(response.initialApprovingOfficerName);
                        $('#summary-final-approval-by').text(response.finalApprovingOfficerName);
                        $('#summary-product-type').text(response.productType);
                        $('#summary-transaction-type').text(response.transactionType);
                        $('#summary-for-change-color').text(response.forChangeColor);
                        $('#summary-new-color').text(response.newColor);
                        $('#summary-for-change-body').text(response.forChangeBody);
                        $('#summary-new-body').text(response.newBody);
                        $('#summary-for-change-engine').text(response.forChangeEngine);
                        $('#summary-new-engine').text(response.newEngine);
                        $('#summary-fuel-type').text(response.fuelType);
                        $('#summary-fuel-quantity').text(response.fuelQuantity + ' lt');

                        $('#product_id_label').text(response.productName);
                        $('#comaker_id_label').text(response.comakerName);
                        $('#created-by').text(response.createdByName);
                        $('#product_type_label').text(response.productType);
                        $('#transaction_type_label').text(response.transactionType);
                        $('#financing_institution_label').text(response.financingInstitution);

                        $('#initial-approval-by').text(response.initialApprovalByName);
                        $('#initial-approval-remarks').text(response.initialApprovalRemarks);
                        $('#initial-approval-date').text(response.initialApprovalDate);
                        $('#approval-by').text(response.approvalByName);
                        $('#final-approval-remarks').text(response.finalApprovalRemarks);
                        $('#final-approval-date').text(response.approvalDate);
                        $('#for-ci-date').text(response.forCIDate);
                        $('#rejection-reason').text(response.rejectionReason);
                        $('#rejection-date').text(response.rejectionDate);
                        $('#cancellation-reason').text(response.cancellationReason);
                        $('#cancellation-date').text(response.cancellationDate);
                        $('#set-to-draft-reason').text(response.setToDraftReason);
                        
                        $('#comaker_id_details').text(response.comakerID);
                        $('#product_id_details').text(response.productID);
                        $('#referred_by_label').text(response.referredBy);
                        $('#release_date_label').text(response.releaseDate);
                        $('#renewal_tag_label').text(response.renewalTag);
                        $('#start_date_label').text(response.startDate);
                        $('#term_length_label').text(response.termLength + ' ' + response.termType);
                        $('#number_of_payments_label').text(response.numberOfPayments);
                        $('#payment_frequency_label').text('Frequency of Payment: ' + response.paymentFrequency);
                        $('#first_due_date_label').text(response.firstDueDate);
                        $('#for_registration_label').text(response.forRegistration);
                        $('#with_cr_label').text(response.withCR);
                        $('#for_transfer_label').text(response.forTransfer);
                        $('#remarks_label').text(response.remarks);
                        $('#initial_approving_officer_label').text(response.initialApprovingOfficerName);
                        $('#final_approving_officer_label').text(response.finalApprovingOfficerName);
                        $('#for_change_color_label').text(response.forChangeColor);
                        $('#new_color_label').text(response.newColor);
                        $('#for_change_body_label').text(response.forChangeBody);
                        $('#for_change_engine_label').text(response.forChangeEngine);
                        $('#fuel_type_label').text(response.fuelType);
                        $('#fuel_quantity_label').text(response.fuelQuantity + ' lt');
                        $('#new_body_label').text(response.newBody);
                        $('#new_engine_label').text(response.newEngine);
                        $('#price_per_liter_label').text(response.pricePerLiter);
                        $('#commission_amount_label').text(response.commissionAmount);

                        if($('#client-confirmation-image').length){
                            document.getElementById('client-confirmation-image').src = response.clientConfirmation;
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
                
                        document.getElementById('quality-control-form-image').src = response.qualityControlForm;
                        document.getElementById('outgoing-checklist-image').src = response.outgoingChecklist;
                        document.getElementById('unit-image').src = response.unitImage;


                        var savedValues = response.fuelType[0].split(',').map(function(item) {
                            return item.trim(); // Trim whitespace
                        });
                        
                        // Preselect saved values in Select2
                        $('#fuel_type').val(savedValues).trigger('change');
                        

                        checkOptionExist('#product_id', response.productID, '');
                        checkOptionExist('#product_type', response.productType, '');
                        checkOptionExist('#renewal_tag', response.renewalTag, '');
                        checkOptionExist('#transaction_type', response.transactionType, '');
                        checkOptionExist('#comaker_id', response.comakerID, '');
                        checkOptionExist('#term_type', response.termType, '');
                        checkOptionExist('#payment_frequency', response.paymentFrequency, '');
                        checkOptionExist('#for_registration', response.forRegistration, '');
                        checkOptionExist('#with_cr', response.withCR, '');
                        checkOptionExist('#for_transfer', response.forTransfer, '');
                        checkOptionExist('#for_change_color', response.forChangeColor, '');
                        checkOptionExist('#for_change_body', response.forChangeBody, '');
                        checkOptionExist('#for_change_engine', response.forChangeEngine, '');
                        checkOptionExist('#initial_approving_officer', response.initialApprovingOfficer, '');
                        checkOptionExist('#final_approving_officer', response.finalApprovingOfficer, '');

                        if(parseFloat($("#insurance_coverage_second_year").val()) > 0 && parseFloat($("#insurance_premium_second_year").val()) > 0){
                            $('#compute_second_year').prop('checked', true);
                        }

                        if(parseFloat($("#insurance_coverage_third_year").val()) > 0 && parseFloat($("#insurance_premium_third_year").val()) > 0){
                            $('#compute_third_year').prop('checked', true);
                        }

                        if(parseFloat($("#insurance_coverage_fourth_year").val()) > 0 && parseFloat($("#insurance_premium_fourth_year").val()) > 0){
                            $('#compute_fourth_year').prop('checked', true);
                        }
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
                    if($('#details-tab').length){
                        
                        if($('#product_type').val() == 'Unit'){
                          displayDetails('get product details');
                        }
                        
                        calculateFirstDueDate();
                        
                        if($('#comaker_id_details').length && $('#comaker_id_details').text() != '' && $('#comaker_id_details').text() != '0'){
                            displayDetails('get comaker details');
                        }
                    }
                }
            });
            break;
        case 'get sales proposal accessories details':
                var sales_proposal_accessories_id = sessionStorage.getItem('sales_proposal_accessories_id');
                
                $.ajax({
                    url: 'controller/sales-proposal-controller.php',
                    method: 'POST',
                    dataType: 'json',
                    data: {
                        sales_proposal_accessories_id : sales_proposal_accessories_id, 
                        transaction : transaction
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#sales_proposal_accessories_id').val(sales_proposal_accessories_id);
                            $('#accessories').val(response.accessories);
                            $('#accessories_cost').val(response.cost);
                        } 
                        else {
                            if(response.isInactive){
                                window.location = 'logout.php?logout';
                            }
                            else{
                                showNotification('Get Sales Proposal Accessories Details Error', response.message, 'danger');
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
        case 'get sales proposal accessories total details':
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
                            $('#sales-proposal-accessories-total').text('Php ' + response.total);
                            $('#summary-accessories-total').text(response.total);
                        } 
                        else {
                            if(response.isInactive){
                                window.location = 'logout.php?logout';
                            }
                            else{
                                showNotification('Get Sales Proposal Accessories Total Details Error', response.message, 'danger');
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
        case 'get sales proposal job order total details':
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
                                $('#sales-proposal-job-order-total').text('Php ' + response.total);
                                $('#summary-job-order-total').text(response.total);
                            } 
                            else {
                                if(response.isInactive){
                                    window.location = 'logout.php?logout';
                                }
                                else{
                                    showNotification('Get Sales Proposal Job Order Total Details Error', response.message, 'danger');
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
        case 'get sales proposal additional job order total details':
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
                        $('#sales-proposal-additional-job-order-total').text('Php ' + response.total);
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Sales Proposal Additional Job Order Total Details Error', response.message, 'danger');
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
                        $('#delivery_price').val(response.deliveryPrice);
                        $('#nominal_discount').val(response.nominalDiscount);
                        $('#add_on_charge').val(response.addOnCharge);
                        $('#cost_of_accessories').val(response.costOfAccessories);
                        $('#reconditioning_cost').val(response.reconditioningCost);
                        $('#downpayment').val(response.downpayment);
                        $('#interest_rate').val(response.interestRate);

                        $('#summary-deliver-price').text(parseFloat(response.totalDeliveryPrice).toLocaleString("en-US"));
                        $('#summary-cost-of-accessories').text(parseFloat(response.costOfAccessories).toLocaleString("en-US"));
                        $('#summary-reconditioning-cost').text(parseFloat(response.reconditioningCost).toLocaleString("en-US"));
                        $('#summary-downpayment').text(parseFloat(response.downpayment).toLocaleString("en-US"));
                        $('#summary-repayment-amount').text(parseFloat(response.repaymentAmount).toLocaleString("en-US"));
                        $('#summary-interest-rate').text(parseFloat(response.interestRate).toLocaleString("en-US") + '%');
                        $('#summary-outstanding-balance').text(parseFloat(response.outstandingBalance).toLocaleString("en-US"));
                        $('#summary-sub-total').text(parseFloat(response.subtotal).toLocaleString("en-US"));

                        $('#delivery_price_label').text(parseFloat(response.deliveryPrice).toLocaleString("en-US"));
                        $('#nominal_discount_label').text(parseFloat(response.nominalDiscount).toLocaleString("en-US"));
                        $('#add_on_charge_label').text(parseFloat(response.addOnCharge).toLocaleString("en-US"));
                        $('#cost_of_accessories_label').text(parseFloat(response.costOfAccessories).toLocaleString("en-US"));
                        $('#reconditioning_cost_label').text(parseFloat(response.reconditioningCost).toLocaleString("en-US"));
                        $('#downpayment_label').text(parseFloat(response.downpayment).toLocaleString("en-US"));
                        $('#interest_rate_label').text(parseFloat(response.interestRate).toLocaleString("en-US"));
                        $('#subtotal_label').text(parseFloat(response.subtotal).toLocaleString("en-US"));
                        $('#outstanding_balance_label').text(parseFloat(response.outstandingBalance).toLocaleString("en-US"));
                        $('#amount_financed_label').text(parseFloat(response.amountFinanced).toLocaleString("en-US"));
                        $('#pn_amount_label').text(parseFloat(response.pnAmount).toLocaleString("en-US"));
                        $('#repayment_amount_label').text(parseFloat(response.repaymentAmount).toLocaleString("en-US"));
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
                    if($('#product_type').val() == 'Fuel'){
                        calculateTotalFuelDeliveryPrice();
                    }

                    if($('#product_type').val() == 'unit'){
                        displayDetails('get product details');
                    }

                    calculatePricingComputation();
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
                        $('#insurance_premium').val(response.insurancePremium);
                        $('#handling_fee').val(response.handlingFee);
                        $('#transfer_fee').val(response.transferFee);
                        $('#registration_fee').val(response.registrationFee);
                        $('#doc_stamp_tax').val(response.docStampTax);
                        $('#transaction_fee').val(response.transactionFee);

                        $('#summary-insurance-coverage').text(parseFloat(response.insuranceCoverage).toLocaleString("en-US"));
                        $('#summary-insurance-premium').text(parseFloat(response.insurancePremium).toLocaleString("en-US"));
                        $('#summary-handing-fee').text(parseFloat(response.handlingFee).toLocaleString("en-US"));
                        $('#summary-transfer-fee').text(parseFloat(response.transferFee).toLocaleString("en-US"));
                        $('#summary-registration-fee').text(parseFloat(response.registrationFee).toLocaleString("en-US"));
                        $('#summary-doc-stamp-tax').text(parseFloat(response.docStampTax).toLocaleString("en-US"));
                        $('#summary-transaction-fee').text(parseFloat(response.transactionFee).toLocaleString("en-US"));
                        $('#summary-other-charges-total').text(parseFloat(response.totalOtherCharges).toLocaleString("en-US"));

                        $('#insurance_coverage_label').text(parseFloat(response.insuranceCoverage).toLocaleString("en-US"));
                        $('#insurance_premium_label').text(parseFloat(response.insurancePremium).toLocaleString("en-US"));
                        $('#handling_fee_label').text(parseFloat(response.handlingFee).toLocaleString("en-US"));
                        $('#transfer_fee_label').text(parseFloat(response.transferFee).toLocaleString("en-US"));
                        $('#registration_fee_label').text(parseFloat(response.registrationFee).toLocaleString("en-US"));
                        $('#doc_stamp_tax_label').text(parseFloat(response.docStampTax).toLocaleString("en-US"));
                        $('#transaction_fee_label').text(parseFloat(response.transactionFee).toLocaleString("en-US"));
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

                        $('#summary-registration-second-year').text(parseFloat(response.registrationSecondYear).toLocaleString("en-US"));
                        $('#summary-registration-third-year').text(parseFloat(response.registrationThirdYear).toLocaleString("en-US"));
                        $('#summary-registration-fourth-year').text(parseFloat(response.registrationFourthYear).toLocaleString("en-US"));
                        $('#summary-insurance-coverage-second-year').text(parseFloat(response.insuranceCoverageSecondYear).toLocaleString("en-US"));
                        $('#summary-insurance-coverage-third-year').text(parseFloat(response.insuranceCoverageThirdYear).toLocaleString("en-US"));
                        $('#summary-insurance-coverage-fourth-year').text(parseFloat(response.insuranceCoverageFourthYear).toLocaleString("en-US"));
                        $('#summary-insurance-premium-second-year').text(parseFloat(response.insurancePremiumSecondYear).toLocaleString("en-US"));
                        $('#summary-insurance-premium-third-year').text(parseFloat(response.insurancePremiumThirdYear).toLocaleString("en-US"));
                        $('#summary-insurance-premium-fourth-year').text(parseFloat(response.insurancePremiumFourthYear).toLocaleString("en-US"));

                        $('#registration_second_year_label').text(parseFloat(response.registrationSecondYear).toLocaleString("en-US"));
                        $('#registration_third_year_label').text(parseFloat(response.registrationThirdYear).toLocaleString("en-US"));
                        $('#registration_fourth_year_label').text(parseFloat(response.registrationFourthYear).toLocaleString("en-US"));
                        $('#insurance_coverage_second_year_label').text(parseFloat(response.insuranceCoverageSecondYear).toLocaleString("en-US"));
                        $('#insurance_coverage_third_year_label').text(parseFloat(response.insuranceCoverageThirdYear).toLocaleString("en-US"));
                        $('#insurance_coverage_fourth_year_label').text(parseFloat(response.insuranceCoverageFourthYear).toLocaleString("en-US"));
                        $('#insurance_premium_second_year_label').text(parseFloat(response.insurancePremiumSecondYear).toLocaleString("en-US"));
                        $('#insurance_premium_third_year_label').text(parseFloat(response.insurancePremiumThirdYear).toLocaleString("en-US"));
                        $('#insurance_premium_fourth_year_label').text(parseFloat(response.insurancePremiumFourthYear).toLocaleString("en-US"));
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
                            $('#product_engine_number').text(response.engineNumber);
                            $('#product_chassis_number').text(response.chassisNumber);
                            $('#product_plate_number').text(response.plateNumber);
                            $('#product_category').val(response.productCategoryID);

                            $('#delivery_price_label').text(parseFloat(response.productPrice * 1000).toLocaleString("en-US"));
                            $('#delivery_price').val(response.productPrice * 1000);

                            $('#old_color').val(response.colorName);
                            $('#old_body').val(response.bodyTypeName);
                            $('#old_engine').val(response.engineNumber);

                            $('#old_color_label').text(response.colorName);
                            $('#old_body_label').text(response.bodyTypeName);
                            $('#old_engine_label').text(response.engineNumber);
    
                            $('#summary-stock-no').text(response.summaryStockNumber);
                            $('#summary-engine-no').text(response.engineNumber);
                            $('#summary-chassis-no').text(response.chassisNumber);
                            $('#summary-plate-no').text(response.plateNumber);
    
                            if($('#product_cost_label').length){
                                $('#product_cost_label').text(parseFloat(response.productCost * 1000).toLocaleString("en-US"));
                            }                        
    
                            if($('#product_cost_label').length){
                                
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
                    }
                });
            }
            break;
        case 'get comaker details':
            var comaker_id;

            if($('#comaker_id_details').length && $('#comaker_id_details').text() != '' && $('#comaker_id_details').text() != '0'){
                comaker_id = $('#comaker_id_details').text();
            }
            else{
                comaker_id = $('#comaker_id').val();
            }
            
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
                        $('#comaker_file_as').text(response.comakerName);
                        $('#comaker_address').text(response.comakerAddress);
                        $('#comaker_email').text(response.comakerEmail);
                        $('#comaker_mobile').text(response.chassisNumber);
                        $('#comaker_telephone').text(response.comakerMobile);

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
    }
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

function checkIntegerDivision(dividend, divisor) {
    let result = dividend / divisor;
    return Number.isInteger(result) ? result : 0;
}

function calculateFirstDueDate(){
    var start_date = $("#start_date").val();
    var payment_frequency = $("#payment_frequency").val();
    var number_of_payments = $("#number_of_payments").val();

    $.ajax({
        type: "POST",
        url: "./config/calculate_first_due_date.php",
        data: { start_date: start_date, payment_frequency: payment_frequency, number_of_payments: number_of_payments },
        success: function (result) {
            $('#first_due_date').val(result);
        }
    });
}

function calculatePricingComputation(){
    var term_length = parseFloat($("#term_length").val()) || 0;
    var interest_rate = parseFloat($("#interest_rate").val()) || 0;
    var delivery_price = parseFloat($("#total_delivery_price").val()) || 0;
    var cost_of_accessories = parseFloat($("#cost_of_accessories").val()) || 0;
    var reconditioning_cost = parseFloat($("#reconditioning_cost").val()) || 0;
    var downpayment = parseFloat($("#downpayment").val()) || 0;

    var payment_frequency = $('#payment_frequency').val();

    if(payment_frequency == 'Lumpsum'){
        term_length = 1;
    }

    var subtotal = delivery_price + cost_of_accessories + reconditioning_cost;
    var outstanding_balance = subtotal - downpayment;
    var pn_amount = outstanding_balance * (1 + (interest_rate/100));
    var repayment_amount = Math.ceil(pn_amount / term_length);

    $('#subtotal').val(subtotal.toFixed(2));
    $('#outstanding_balance').val(outstanding_balance.toFixed(2));

    $('#amount_financed').val(outstanding_balance.toFixed(2));
    $('#pn_amount').val(pn_amount.toFixed(2));
    $('#repayment_amount').val(repayment_amount.toFixed(2));
}

function calculateTotalOtherCharges(){
    var insurance_premium = parseFloat($("#insurance_premium").val()) || 0;
    var handling_fee = parseFloat($("#handling_fee").val()) || 0;
    var transfer_fee = parseFloat($("#transfer_fee").val()) || 0;
    var registration_fee = parseFloat($("#registration_fee").val()) || 0;
    var doc_stamp_tax = parseFloat($("#doc_stamp_tax").val()) || 0;
    var transaction_fee = parseFloat($("#transaction_fee").val()) || 0;

    var total = insurance_premium + handling_fee + transfer_fee + registration_fee + doc_stamp_tax + transaction_fee;

    $('#total_other_charges').val(total.toFixed(2));
}

function calculateTotalDeliveryPrice(){
    var delivery_price = parseFloat($("#delivery_price").val()) || 0;
    var add_on_charge = parseFloat($("#add_on_charge").val()) || 0;
    var nominal_discount = parseFloat($("#nominal_discount").val()) || 0;

    var total = (delivery_price + add_on_charge) - nominal_discount;

    if(total <= 0){
        total = 0;
    }

    $('#total_delivery_price').val(total.toFixed(2));
    $('#total_delivery_price_label').val(total.toFixed(2));
    calculatePricingComputation();
}

function calculateTotalFuelDeliveryPrice(){
    var fuel_quantity = parseFloat($("#fuel_quantity").val()) || 0;
    var price_per_liter = parseFloat($("#price_per_liter").val()) || 0;

    var total = fuel_quantity * price_per_liter;

    if(total <= 0){
        total = 0;
    }

    $('#delivery_price').val(total.toFixed(2));
    calculateTotalDeliveryPrice();
    calculatePricingComputation();
}

function calculateRenewalAmount(){
    var product_category = $('#product_category').val();
    var delivery_price = parseFloat($("#delivery_price").val()) || 0;

    if(delivery_price > 0){
        var second_year_coverage = delivery_price * 0.8;
        var third_year_coverage = second_year_coverage * 0.9;
        var fourth_year_coverage = third_year_coverage * 0.9;
        
        if($('#compute_second_year').is(':checked')) {
            $('#insurance_coverage_second_year').val(second_year_coverage.toFixed(2));
    
            if(product_category == '1'){
                var premium = (((second_year_coverage * 0.025) + 2700) * 1.2526) + 1300;
    
                $('#insurance_premium_second_year').val(premium.toFixed(2));
            }
            else if(product_category == '2'){
                var premium = (second_year_coverage * 0.025) * 1.2526;
    
                $('#insurance_premium_second_year').val(premium.toFixed(2));
            }
            else{
                $('#insurance_premium_second_year').val(0);
            }
        }
        else{
            $('#insurance_coverage_second_year').val(0);
            $('#insurance_premium_second_year').val(0);
        }
    
        if($('#compute_third_year').is(':checked')) {
            $('#insurance_coverage_third_year').val(third_year_coverage.toFixed(2));
    
            if(product_category == '1'){
                var premium = (((third_year_coverage * 0.025) + 2700) * 1.2526) + 1300;
    
                $('#insurance_premium_third_year').val(premium.toFixed(2));
            }
            else if(product_category == '2'){
                var premium = (third_year_coverage * 0.025) * 1.2526;
    
                $('#insurance_premium_third_year').val(premium.toFixed(2));
            }
            else{
                $('#insurance_premium_third_year').val(0);
            }
        }
        else{
            $('#insurance_coverage_third_year').val(0);
            $('#insurance_premium_third_year').val(0);
        }
    
        if($('#compute_fourth_year').is(':checked')) {
            $('#insurance_coverage_fourth_year').val(fourth_year_coverage.toFixed(2));
    
            if(product_category == '1'){
                var premium = (((fourth_year_coverage * 0.025) + 2700) * 1.2526) + 1300;
    
                $('#insurance_premium_fourth_year').val(premium.toFixed(2));
            }
            else if(product_category == '2'){
                var premium = (fourth_year_coverage * 0.025) * 1.2526;
    
                $('#insurance_premium_fourth_year').val(premium.toFixed(2));
            }
            else{
                $('#insurance_premium_fourth_year').val(0);
            }
        }
        else{
            $('#insurance_coverage_fourth_year').val(0);
            $('#insurance_premium_fourth_year').val(0);
        }
    }
    else{
        $('#insurance_coverage_second_year').val(0);
        $('#insurance_premium_second_year').val(0);
        $('#insurance_coverage_third_year').val(0);
        $('#insurance_premium_third_year').val(0);
        $('#insurance_coverage_fourth_year').val(0);
        $('#insurance_premium_fourth_year').val(0);
    }
    
    $("#sales-proposal-renewal-amount-form").submit();
}

function nextStep(currentStep) {
    $('#sales-proposal-tab-' + (currentStep + 1)).tab('show');
}

function prevStep(currentStep) {
    $('#sales-proposal-tab-' + (currentStep - 1)).tab('show');
}