(function($) {
    'use strict';

    $(function() {
        if($('#sales-proposal-table').length){
            salesProposalTable('#sales-proposal-table');
        }

        if($('#all-sales-proposal-table').length){
            allSalesProposalTable('#all-sales-proposal-table');
        }

        if($('#sales-proposal-job-order-table').length){
            salesProposalJobOrderTable('#sales-proposal-job-order-table');
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

        if($('#sales-proposal-other-charges-form').length){
            salesProposalOtherChargesForm();
        }

        if($('#sales-proposal-renewal-amount-form').length){
            salesProposalRenewalAmountForm();
        }

        if($('#sales-proposal-deposit-amount-form').length){
            salesProposalDepositAmountForm();
        }

        if($('#sales-proposal-additional-job-order-form').length){
            salesProposalAdditionalJobOrderForm();
        }

        if($('#sales-proposal-client-confirmation-form').length){
            salesProposalClientConfirmationForm();
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
        
        if($('#sales-proposal-reject-form').length){
            salesProposalRejectForm();
        }
        
        if($('#sales-proposal-cancel-form').length){
            salesProposalCancelForm();
        }
        
        if($('#sales-proposal-set-to-draft-form').length){
            salesProposalSetToDraftForm();
        }

        $(document).on('change','#product_type',function() {
            var productType = $(this).val();
            $('#delivery_price').val('');

            if($('#sales-proposal-tab-2').length && $('#sales-proposal-tab-3').length && $('#sales-proposal-tab-4').length){
                $('#sales-proposal-tab-2, #sales-proposal-tab-3, #sales-proposal-tab-4').addClass('d-none');

                if (productType === 'Unit') {
                    $('#sales-proposal-tab-2').removeClass('d-none');
                    resetModalForm('sales-proposal-unit-details-form');
                }
                else if (productType === 'Fuel') {
                    $('#sales-proposal-tab-3').removeClass('d-none');
                    resetModalForm('sales-proposal-fuel-details-form');
                }
                else if (productType === 'Refinancing') {
                    $('#sales-proposal-tab-4').removeClass('d-none');
                    resetModalForm('sales-proposal-refinancing-details-form');
                }
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

        $(document).on('change','#comaker_id',function() {
            displayDetails('get comaker details');
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
            alert();
        });

        $(document).on('change','#payment_frequency',function() {
            calculateFirstDueDate();
        });

        $(document).on('change','#number_of_payments',function() {
            calculateFirstDueDate();
        });

        $(document).on('change','#payment_frequency',function() {
            calculatePaymentNumber();
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

        $(document).on('change','#for_change_color',function() {
            if($(this).val() == 'Yes'){
                $('#new_color').attr('readonly', false); 
            }
            else{
                $('#new_color').val('');
                $('#new_color').attr('readonly', true); 
            }
        });

        $(document).on('change','#for_change_body',function() {
            if($(this).val() == 'Yes'){
                $('#new_body').attr('readonly', false); 
            }
            else{
                $('#new_body').val('');
                $('#new_body').attr('readonly', true); 
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
        });

        $(document).on('change','#diesel_price_per_liter',function() {
            calculateFuelTotal();
        });

        $(document).on('change','#regular_fuel_quantity',function() {
            calculateFuelTotal();
        });

        $(document).on('change','#regular_price_per_liter',function() {
            calculateFuelTotal();
        });

        $(document).on('change','#premium_fuel_quantity',function() {
            calculateFuelTotal();
        });

        $(document).on('change','#premium_price_per_liter',function() {
            calculateFuelTotal();
        });

        $(document).on('change','#delivery_price',function() {
            calculateTotalDeliveryPrice();
            calculateRenewalAmount();
        });

        $(document).on('change','#add_on_charge',function() {
            calculateTotalDeliveryPrice();
        });

        $(document).on('change','#nominal_discount',function() {
            calculateTotalDeliveryPrice();
        });

        $(document).on('change','#term_length',function() {
            calculatePricingComputation();
        });

        $(document).on('change','#interest_rate',function() {
            calculatePricingComputation();
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

        $(document).on('change','#compute_second_year',function() {
            calculateRenewalAmount();
        });

        $(document).on('change','#compute_third_year',function() {
            calculateRenewalAmount();
        });

        $(document).on('change','#compute_fourth_year',function() {
            calculateRenewalAmount();
        });

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

        $(document).on('click','#next-step',function() {
            traverseTabs('next');
        });

        $(document).on('click','#previous-step',function() {
            traverseTabs('previous');
        });

        $(document).on('click','#first-step',function() {
            traverseTabs('first');
        });

        $(document).on('click','#last-step',function() {
            traverseTabs('last');
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

        if($('#sales-proposal-id').length){
            displayDetails('get sales proposal basic details');
        }
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

function salesProposalForm(){
    $('#sales-proposal-form').validate({
        rules: {
            renewal_tag: {
                required: true
            },
            product_type: {
                required: true
            },
            transaction_type: {
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
                    displayDetails('get sales proposal details');
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
                },
                complete: function() {
                    displayDetails('get sales proposal details');
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
                    displayDetails('get sales proposal details');
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
                },
                complete: function() {
                    displayDetails('get sales proposal details');
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
                        showNotification('Client Confirmation Upload Success', 'The client confirmation has been uploaded successfully', 'success');
                        displayDetails('get sales proposal confirmation details');
                        $('#sales-proposal-client-confirmation-offcanvas').offcanvas('hide');
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

                        $('#financing_institution').val(response.financingInstitution);
                        $('#referred_by').val(response.referredBy);
                        $('#commission_amount').val(response.commissionAmount);
                        $('#release_date').val(response.releaseDate);
                        $('#start_date').val(response.startDate);
                        $('#term_length').val(response.termLength);
                        $('#number_of_payments').val(response.numberOfPayments);
                        $('#first_due_date').val(response.firstDueDate);
                        $('#remarks').val(response.remarks);

                        checkOptionExist('#renewal_tag', response.renewalTag, '');
                        checkOptionExist('#product_type', response.productType, '');
                        checkOptionExist('#transaction_type', response.transactionType, '');
                        checkOptionExist('#comaker_id', response.comakerID, '');
                        checkOptionExist('#term_type', response.termType, '');
                        checkOptionExist('#payment_frequency', response.paymentFrequency, '');
                        checkOptionExist('#initial_approving_officer', response.initialApprovingOfficer, '');
                        checkOptionExist('#final_approving_officer', response.finalApprovingOfficer, '');

                        $('#summary-referred-by').text(response.referredBy);
                        $('#summary-release-date').text(response.releaseDate);
                        $('#summary-product-type').text(response.productType);
                        $('#summary-transaction-type').text(response.transactionType);
                        $('#summary-term').text(response.termLength + ' ' + response.termType);
                        $('#summary-no-payments').text(response.numberOfPayments);
                        $('#summary-remarks').text(response.remarks);
                        $('#summary-initial-approval-by').text(response.initialApprovingOfficerName);
                        $('#summary-final-approval-by').text(response.finalApprovingOfficerName);
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

                    if(productType == 'Unit'){
                        displayDetails('get sales proposal unit details');
                    }
                    else if(productType == 'Fuel'){
                        displayDetails('get sales proposal fuel details');
                    }
                    else if(productType == 'Refinancing'){
                        displayDetails('get sales proposal refinancing details');
                    }

                    calculateRenewalAmount();

                    displayDetails('get comaker details');
                    displayDetails('get sales proposal pricing computation details');
                    displayDetails('get sales proposal other charges details');
                    displayDetails('get sales proposal renewal amount details');
                    displayDetails('get sales proposal confirmation details');
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

                        checkOptionExist('#product_id', response.productID, '');
                        checkOptionExist('#for_registration', response.forRegistration, '');
                        checkOptionExist('#with_cr', response.withCR, '');
                        checkOptionExist('#for_transfer', response.forTransfer, '');
                        checkOptionExist('#for_change_color', response.forChangeColor, '');
                        checkOptionExist('#for_change_body', response.forChangeBody, '');
                        checkOptionExist('#for_change_engine', response.forChangeEngine, '');
                        
                        $('#summary-for-registration').text(response.forRegistration);
                        $('#summary-with-cr').text(response.withCR);
                        $('#summary-for-transfer').text(response.forTransfer);
                        $('#summary-for-change-color').text(response.forChangeColor);
                        $('#summary-new-color').text(response.newColor);
                        $('#summary-for-change-body').text(response.forChangeBody);
                        $('#summary-new-body').text(response.newBody);
                        $('#summary-for-change-engine').text(response.forChangeEngine);
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

                        $('#summary-stock-no').text(response.refStockNo);
                        $('#summary-engine-no').text(response.refEngineNo);
                        $('#summary-chassis-no').text(response.refChassisNo);
                        $('#summary-plate-no').text(response.refPlateNo);
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

                        if(response.insurancePremiumSecondYear > 0){
                            $('#compute_second_year').prop('checked', true);
                        }
                        else{
                            $('#compute_second_year').prop('checked', false);
                        }

                        if(response.insurancePremiumThirdYear > 0){
                            $('#compute_third_year').prop('checked', true);
                        }
                        else{
                            $('#compute_third_year').prop('checked', false);
                        }

                        if(response.insurancePremiumFourthYear > 0){
                            $('#compute_fourth_year').prop('checked', true);
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
                            $('#delivery_price').val(response.productPrice * 1000);
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
                    }
                });
            }
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
    var number_of_payments = $('#number_of_payments').val();

    $.ajax({
        type: 'POST',
        url: './config/calculate_first_due_date.php',
        data: { start_date: start_date, payment_frequency: payment_frequency, number_of_payments: number_of_payments },
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
    var diesel_fuel_quantity = parseFloat($('#diesel_fuel_quantity').val()) || 0;
    var diesel_price_per_liter = parseFloat($('#diesel_price_per_liter').val()) || 0;
    var regular_fuel_quantity = parseFloat($('#regular_fuel_quantity').val()) || 0;
    var regular_price_per_liter = parseFloat($('#regular_price_per_liter').val()) || 0;
    var premium_fuel_quantity = parseFloat($('#premium_fuel_quantity').val()) || 0;
    var premium_price_per_liter = parseFloat($('#premium_price_per_liter').val()) || 0;

    var total_diesel = diesel_fuel_quantity * diesel_price_per_liter;
    var total_regular = regular_fuel_quantity * regular_price_per_liter;
    var total_premium = premium_fuel_quantity * premium_price_per_liter;
    var total_delivery_price = total_diesel + total_regular + total_premium;

    $('#diesel_total').text(total_diesel.toFixed(2));
    $('#regular_total').text(total_regular.toFixed(2));
    $('#premium_total').text(total_premium.toFixed(2));
    $('#fuel_total').text(total_delivery_price.toFixed(2));
    $('#delivery_price').val(total_delivery_price.toFixed(2));

    calculateTotalDeliveryPrice();
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

function calculatePricingComputation(){
    var term_length = parseFloat($('#term_length').val()) || 0;
    var interest_rate = parseFloat($('#interest_rate').val()) || 0;
    var delivery_price = parseFloat($('#total_delivery_price').val()) || 0;
    var cost_of_accessories = parseFloat($('#cost_of_accessories').val()) || 0;
    var reconditioning_cost = parseFloat($('#reconditioning_cost').val()) || 0;
    var downpayment = parseFloat($('#downpayment').val()) || 0;

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
}

function traverseTabs(direction) {
    var activeTab = $('.nav-link.active');
    var currentTabId = activeTab.attr('href');
    var currentIndex = $('.nav-link').index(activeTab);
    var nextIndex;
    var totalTabs = $('.nav-link').length;

    // Calculate total number of visible tabs
    var visibleTabs = $('.nav-link:not(.d-none)').length;

    function findNextVisibleIndex(startIndex, direction) {
        var index = startIndex;
        var increment = direction === 'next' ? 1 : -1;
        while (true) {
            index = (index + increment + totalTabs) % totalTabs;
            if (!$('.nav-link:eq(' + index + ')').hasClass('d-none')) {
                return index;
            }
            if (index === startIndex) {
                break;
            }
        }
        return startIndex;
    }

    if (direction === 'next') {
        nextIndex = findNextVisibleIndex(currentIndex, 'next');
    } else if (direction === 'previous') {
        nextIndex = findNextVisibleIndex(currentIndex, 'previous');
    } else if (direction === 'first') {
        nextIndex = 0;
    } else if (direction === 'last') {
        nextIndex = totalTabs - 1;
    }

    if (currentIndex == 0) {
        if ($('#sales-proposal-form').valid()) {
            $('#sales-proposal-form').submit();
        } else {
            return;
        }
    }
    else if (currentIndex == 1 && direction === 'next') {
        if ($('#sales-proposal-unit-details-form').valid()) {
            $('#sales-proposal-unit-details-form').submit();
        } else {
            return;
        }
    }
    else if (currentIndex == 2 && direction === 'next') {
        if ($('#sales-proposal-fuel-details-form').valid()) {
            $('#sales-proposal-fuel-details-form').submit();
        } else {
            return;
        }
    }
    else if (currentIndex == 3 && direction === 'next') {
        if ($('#sales-proposal-refinancing-details-form').valid()) {
            $('#sales-proposal-refinancing-details-form').submit();
        } else {
            return;
        }
    }
    else if (currentIndex == 5 && direction === 'next') {
        if ($('#sales-proposal-pricing-computation-form').valid()) {
            $('#sales-proposal-pricing-computation-form').submit();
        } else {
            return;
        }
    }
    else if (currentIndex == 6 && direction === 'next') {
        if ($('#sales-proposal-other-charges-form').valid()) {
            $('#sales-proposal-other-charges-form').submit();
        } else {
            return;
        }
    }
    else if (currentIndex == 7 && direction === 'next') {
        if ($('#sales-proposal-renewal-amount-form').valid()) {
            $('#sales-proposal-renewal-amount-form').submit();
        } else {
            return;
        }
    }

    if (nextIndex == 4) {
        $('#add-sales-proposal-job-order-button').removeClass('d-none');
    }
    else{
        $('#add-sales-proposal-job-order-button').addClass('d-none');
    }

    if (nextIndex == 8) {
        $('#add-sales-proposal-deposit-amount-button').removeClass('d-none');
    }
    else{
        $('#add-sales-proposal-deposit-amount-button').addClass('d-none');
    }
    
    if (nextIndex == 9) {
        $('#add-sales-proposal-additional-job-order-button').removeClass('d-none');
    }
    else{
        $('#add-sales-proposal-additional-job-order-button').addClass('d-none');
    }
    
    if (nextIndex == 10) {
        $('#sales-proposal-client-confirmation-button').removeClass('d-none');
        $('#sales-proposal-new-engine-stencil-button').removeClass('d-none');

        if($('#sales-proposal-credit-advice-button').length){
            $('#sales-proposal-credit-advice-button').removeClass('d-none');
        }
    }
    else{
        $('#sales-proposal-client-confirmation-button').addClass('d-none');
        $('#sales-proposal-new-engine-stencil-button').addClass('d-none');

        if($('#sales-proposal-credit-advice-button').length){
            $('#sales-proposal-credit-advice-button').addClass('d-none');
        }
    }
    
    if (nextIndex == 11) {
        if($('#print-button').length){
            $('#print-button').removeClass('d-none');
        }

        if($('#tag-for-initial-approval-button').length){
            $('#tag-for-initial-approval-button').removeClass('d-none');
        }
        
        if($('#sales-proposal-initial-approval-button').length){
            $('#sales-proposal-initial-approval-button').removeClass('d-none');
        }

        if($('#sales-proposal-final-approval-button').length){
            $('#sales-proposal-final-approval-button').removeClass('d-none');
        }

        if($('#sales-proposal-reject-button').length){
            $('#sales-proposal-reject-button').removeClass('d-none');
        }

        if($('#sales-proposal-cancel-button').length){
            $('#sales-proposal-cancel-button').removeClass('d-none');
        }

        if($('#for-ci-sales-proposal-button').length){
            $('#for-ci-sales-proposal-button').removeClass('d-none');
        }

        if($('#sales-proposal-set-to-draft-button').length){
            $('#sales-proposal-set-to-draft-button').removeClass('d-none');
        }

        if($('#complete-ci-button').length){
            $('#complete-ci-button').removeClass('d-none');
        }

        if($('#for-dr-sales-proposal-button').length){
            $('#for-dr-sales-proposal-button').removeClass('d-none');
        }
    }
    else{
        if($('#tag-for-initial-approval-button').length){
            $('#tag-for-initial-approval-button').addClass('d-none');
        }

        if($('#sales-proposal-initial-approval-button').length){
            $('#sales-proposal-initial-approval-button').addClass('d-none');
        }

        if($('#sales-proposal-final-approval-button').length){
            $('#sales-proposal-final-approval-button').addClass('d-none');
        }

        if($('#sales-proposal-reject-button').length){
            $('#sales-proposal-reject-button').addClass('d-none');
        }

        if($('#sales-proposal-cancel-button').length){
            $('#sales-proposal-cancel-button').addClass('d-none');
        }

        if($('#for-ci-sales-proposal-button').length){
            $('#for-ci-sales-proposal-button').addClass('d-none');
        }

        if($('#sales-proposal-set-to-draft-button').length){
            $('#sales-proposal-set-to-draft-button').addClass('d-none');
        }

        if($('#complete-ci-button').length){
            $('#complete-ci-button').addClass('d-none');
        }

        if($('#for-dr-sales-proposal-button').length){
            $('#for-dr-sales-proposal-button').addClass('d-none');
        }

        if($('#print-button').length){
            $('#print-button').addClass('d-none');
        }
    }

    var nextTabId = $('.nav-link:eq(' + nextIndex + ')').attr('href');

    if (nextTabId !== undefined) {
        if (nextIndex === 0) {
            $('#first-step').addClass('disabled');
            $('#previous-step').addClass('disabled');
        } else {
            $('#first-step').removeClass('disabled');
            $('#previous-step').removeClass('disabled');
        }

        if (nextIndex === totalTabs - 1) {
            $('#last-step').addClass('disabled');
            $('#next-step').addClass('disabled');
        } else {
            $('#last-step').removeClass('disabled');
            $('#next-step').removeClass('disabled');
        }

        $('.nav-link[href="' + nextTabId + '"]').tab('show');

        // Calculate width of the progress bar based on visible tabs
        var progressBarWidth = ((nextIndex + 1) / visibleTabs) * 100;
        $('#bar .progress-bar').css('width', progressBarWidth + '%');
    }
}
