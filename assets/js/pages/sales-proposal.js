(function($) {
    'use strict';

    $(function() {
        if($('#sales-proposal-form').length){
            salesProposalForm();
        }

        if($('#sales-proposal-table').length){
            salesProposalTable('#sales-proposal-table');
        }

        if($('#all-sales-proposal-table').length){
            allSalesProposalTable('#all-sales-proposal-table');
        }

        if($('#sales-proposal-id').length){
            displayDetails('get sales proposal details');
            displayDetails('get sales proposal accessories total details');
            displayDetails('get sales proposal job order total details');
            displayDetails('get sales proposal additional job order total details');
            displayDetails('get sales proposal pricing computation details');
            displayDetails('get sales proposal other charges details');
            displayDetails('get sales proposal renewal amount details');
            salesProposalSummaryAccessoriesTable();
            salesProposalSummaryJobOrderTable();
            salesProposalSummaryAdditionalJobOrderTable();
            salesProposalSummaryDepositTable();

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

            $(document).on('change','#insurance_coverage',function() {
                calculateTotalOtherCharges();
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
        }

        $(document).on('change','#product_id',function() {
            if($(this).val() != ''){
                displayDetails('get product details');
            }
            else{
                $('#product_engine_number').text('--');
                $('#product_chassis_number').text('--');
                $('#product_plate_number').text('--');
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
        
        $(document).on('click','#prev-step-2',function() {
            prevStep(2);
        });
        
        $(document).on('click','#next-step-1-normal',function() {
            nextStep(1);
        });
        
        $(document).on('click','#next-step-2',function() {
            nextStep(2);
        });
        
        $(document).on('click','#prev-step-3',function() {
            prevStep(3);
        });
        
        $(document).on('click','#next-step-3',function() {
            nextStep(3);
        });
        
        $(document).on('click','#prev-step-4',function() {
            prevStep(4);
        });
        
        $(document).on('click','#next-step-4',function() {
            if($('#delivery_price').valid()){
                nextStep(4);
            }
            else{
                showNotification('Form Required', 'Kindly fill-out all of the required fields before proceeding.', 'warning');
            }
        });

        $(document).on('click','#next-step-4-normal',function() {
            nextStep(4);
        });
        
        $(document).on('click','#prev-step-5',function() {
            prevStep(5);
        });
        
        $(document).on('click','#next-step-5',function() {
            nextStep(5);
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
        { 'data' : 'PRODUCT' },
        { 'data' : 'STATUS' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '1%','bSortable': false, 'aTargets': 0 },
        { 'width': '14%', 'aTargets': 1 },
        { 'width': '25%', 'aTargets': 2 },
        { 'width': '30%', 'aTargets': 3 },
        { 'width': '15%', 'aTargets': 4 },
        { 'width': '15%','bSortable': false, 'aTargets': 5 }
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
        { 'data' : 'PRODUCT' },
        { 'data' : 'STATUS' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '1%','bSortable': false, 'aTargets': 0 },
        { 'width': '14%', 'aTargets': 1 },
        { 'width': '25%', 'aTargets': 2 },
        { 'width': '30%', 'aTargets': 3 },
        { 'width': '15%', 'aTargets': 4 },
        { 'width': '15%','bSortable': false, 'aTargets': 5 }
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
            document.getElementById('summary-job-order-table').innerHTML = result[0].table;
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
            document.getElementById('summary-additional-job-order-table').innerHTML = result[0].table;
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
            document.getElementById('summary-amount-of-deposit-table').innerHTML = result[0].table;
        }
    });
}

function salesProposalForm(){
    $('#sales-proposal-form').validate({
        rules: {
            product_id: {
                required: true
            },
            referred_by: {
                required: true
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
        },
        messages: {
            product_id: {
                required: 'Please choose the product'
            },
            referred_by: {
                required: 'Please enter the referred by'
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
                    nextStep(1);
                    displayDetails('get sales proposal details');
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
            }
        },
        messages: {
            delivery_price: {
                required: 'Please enter the delivery price'
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
                    if (response.success) {
                        showNotification('Update Pricing Computation Success', 'The pricing computation has been updated successfully.', 'success');
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
                    if (response.success) {
                        showNotification('Update Other Charges Success', 'The other charges has been updated successfully.', 'success');
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
                    disableFormSubmitButton('submit-renewal-data');
                },
                success: function (response) {
                    if (response.success) {
                        showNotification('Update Renewal Amount Success', 'The renewal amount has been updated successfully.', 'success');
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
                    enableFormSubmitButton('submit-renewal-data', 'Submit');
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
                        showNotification('Approve Sales Proposal Success', 'The sales proposal has been approved successfully.', 'success');
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
                    enableFormSubmitButton('submit-sales-proposal-initial-approval', 'Submit');
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

                        $('#referred_by_label').text(response.referredBy);
                        $('#release_date_label').text(response.releaseDate);
                        $('#start_date_label').text(response.startDate);
                        $('#term_length_label').text(response.termLength + ' ' + response.termType);
                        $('#number_of_payments_label').text(response.numberOfPayments);
                        $('#payment_frequency_label').text(response.paymentFrequency);
                        $('#first_due_date_label').text(response.firstDueDate);
                        $('#for_registration_label').text(response.forRegistration);
                        $('#with_cr_label').text(response.withCR);
                        $('#for_transfer_label').text(response.forTransfer);
                        $('#remarks_label').text(response.remarks);
                        $('#initial_approving_officer_label').text(response.initialApprovingOfficerName);
                        $('#final_approving_officer_label').text(response.finalApprovingOfficerName);

                        checkOptionExist('#product_id', response.productID, '');
                        checkOptionExist('#comaker_id', response.comakerID, '');
                        checkOptionExist('#term_type', response.termType, '');
                        checkOptionExist('#payment_frequency', response.paymentFrequency, '');
                        checkOptionExist('#for_registration', response.forRegistration, '');
                        checkOptionExist('#with_cr', response.withCR, '');
                        checkOptionExist('#for_transfer', response.forTransfer, '');
                        checkOptionExist('#initial_approving_officer', response.initialApprovingOfficer, '');
                        checkOptionExist('#final_approving_officer', response.finalApprovingOfficer, '');
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
                        $('#cost_of_accessories').val(response.costOfAccessories);
                        $('#reconditioning_cost').val(response.reconditioningCost);
                        $('#downpayment').val(response.downpayment);
                        $('#interest_rate').val(response.interestRate);

                        $('#summary-deliver-price').text(parseFloat(response.deliveryPrice).toLocaleString("en-US"));
                        $('#summary-cost-of-accessories').text(parseFloat(response.costOfAccessories).toLocaleString("en-US"));
                        $('#summary-reconditioning-cost').text(parseFloat(response.reconditioningCost).toLocaleString("en-US"));
                        $('#summary-downpayment').text(parseFloat(response.downpayment).toLocaleString("en-US"));
                        $('#summary-repayment-amount').text(parseFloat(response.repaymentAmount).toLocaleString("en-US"));

                        $('#delivery_price_label').text(parseFloat(response.deliveryPrice).toLocaleString("en-US"));
                        $('#cost_of_accessories_label').text(parseFloat(response.costOfAccessories).toLocaleString("en-US"));
                        $('#reconditioning_cost_label').text(parseFloat(response.reconditioningCost).toLocaleString("en-US"));
                        $('#downpayment_label').text(parseFloat(response.downpayment).toLocaleString("en-US"));
                        $('#interest_rate_label').text(parseFloat(response.repaymentAmount).toLocaleString("en-US"));
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
            const product_id = $('#product_id').val();
            
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

                        $('#summary-stock-no').text(response.stockNumber);
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
                }
            });
            break;
        case 'get comaker details':
            const comaker_id = $('#comaker_id').val();
            
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
    var delivery_price = parseFloat($("#delivery_price").val()) || 0;
    var cost_of_accessories = parseFloat($("#cost_of_accessories").val()) || 0;
    var reconditioning_cost = parseFloat($("#reconditioning_cost").val()) || 0;
    var downpayment = parseFloat($("#downpayment").val()) || 0;

    var subtotal = delivery_price + cost_of_accessories + reconditioning_cost;
    var outstanding_balance = subtotal - downpayment;
    var amount_financed = delivery_price - downpayment;
    var pn_amount = amount_financed * (1 + (interest_rate/100));
    var repayment_amount = Math.ceil(pn_amount / term_length);

    $('#subtotal').val(subtotal.toFixed(2));
    $('#outstanding_balance').val(outstanding_balance.toFixed(2));

    $('#amount_financed').val(amount_financed.toFixed(2));
    $('#pn_amount').val(pn_amount.toFixed(2));
    $('#repayment_amount').val(repayment_amount.toFixed(2));

    $('#summary-sub-total').text(parseFloat(subtotal.toFixed(2)).toLocaleString("en-US"));
    $('#summary-outstanding-balance').text(parseFloat(outstanding_balance.toFixed(2)).toLocaleString("en-US"));
}

function calculateTotalOtherCharges(){
    var insurance_coverage = parseFloat($("#insurance_coverage").val()) || 0;
    var insurance_premium = parseFloat($("#insurance_premium").val()) || 0;
    var handling_fee = parseFloat($("#handling_fee").val()) || 0;
    var transfer_fee = parseFloat($("#transfer_fee").val()) || 0;
    var registration_fee = parseFloat($("#registration_fee").val()) || 0;
    var doc_stamp_tax = parseFloat($("#doc_stamp_tax").val()) || 0;
    var transaction_fee = parseFloat($("#transaction_fee").val()) || 0;

    var total = insurance_coverage + insurance_premium + handling_fee + transfer_fee + registration_fee + doc_stamp_tax + transaction_fee;

    $('#total_other_charges').val(total.toFixed(2));
    $('#summary-other-charges-total').text(parseFloat(total.toFixed(2)).toLocaleString("en-US"));
}

function nextStep(currentStep) {    
    $('#sales-proposal-tab-' + (currentStep + 1)).tab('show');
}

function prevStep(currentStep) {    
    $('#sales-proposal-tab-' + (currentStep - 1)).tab('show');
}