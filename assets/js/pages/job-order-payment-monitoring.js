(function($) {
    'use strict';

    $(function() {
        if($('#job-order-table').length){
            jobOrderTable('#job-order-table');
        }

        if($('#internal-job-order-table').length){
            internalJobOrderTable('#internal-job-order-table');
        }

        if($('#additional-job-order-table').length){
            additionalJobOrderTable('#additional-job-order-table');
        }

        if($('#internal-additional-job-order-table').length){
            internalAdditionalJobOrderTable('#internal-additional-job-order-table');
        }

        if($('#paid-form').length){
            paidForm();
        }

        $(document).on('click','.paid-sp-job-order',function() {
            const job_order_id = $(this).data('sales-proposal-job-order-id');
            $('#job_order_id').val(job_order_id);
            sessionStorage.setItem('transaction', 'paid job order');
            sessionStorage.setItem('link', 'controller/sales-proposal-controller.php');
        });

        $(document).on('click','.cancel-sp-job-order',function() {
            const sales_proposal_job_order_id = $(this).data('sales-proposal-job-order-id');
            var parts_transaction_id = $('#parts-transaction-id').text();
            const transaction = 'cancel paid job order';
    
            Swal.fire({
                title: 'Confirm Job Order Cancel Paid',
                text: 'Are you sure you want to tag this job order as cancel paid?',
                icon: 'warning',
                showCancelButton: !0,
                confirmButtonText: 'Cancel Paid',
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
                            sales_proposal_job_order_id : sales_proposal_job_order_id, 
                            parts_transaction_id : parts_transaction_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Tag As Cancel Paid Success', 'The job order has been tagged as cancel paid successfully.', 'success');
                                reloadDatatable('#job-order-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else {
                                    showNotification('Tag As Cancel Paid Error', response.message, 'danger');
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

        $(document).on('click','.paid-bj-job-order',function() {
            const job_order_id = $(this).data('backjob-monitoring-job-order-id');
            $('#job_order_id').val(job_order_id);
            sessionStorage.setItem('transaction', 'paid job order');
            sessionStorage.setItem('link', 'controller/backjob-monitoring-controller.php');
        });

        $(document).on('click','.cancel-bj-job-order',function() {
            const backjob_monitoring_job_order_id = $(this).data('backjob-monitoring-job-order-id');
            var parts_transaction_id = $('#parts-transaction-id').text();
            const transaction = 'cancel paid job order';
    
            Swal.fire({
                title: 'Confirm Job Order Cancel Paid',
                text: 'Are you sure you want to tag this job order as cancel paid?',
                icon: 'warning',
                showCancelButton: !0,
                confirmButtonText: 'Cancel Paid',
                cancelButtonText: 'Cancel',
                confirmButtonClass: 'btn btn-warning mt-2',
                cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                buttonsStyling: !1
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        type: 'POST',
                        url: 'controller/backjob-monitoring-controller.php',
                        dataType: 'json',
                        data: {
                            backjob_monitoring_job_order_id : backjob_monitoring_job_order_id, 
                            parts_transaction_id : parts_transaction_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Tag As Cancel Paid Success', 'The job order has been tagged as cancel paid successfully.', 'success');
                                reloadDatatable('#internal-job-order-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else {
                                    showNotification('Tag As Cancel Paid Error', response.message, 'danger');
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

        $(document).on('click','.paid-sp-additional-job-order',function() {
            const job_order_id = $(this).data('sales-proposal-additional-job-order-id');
            
            $('#job_order_id').val(job_order_id);
            sessionStorage.setItem('transaction', 'paid additional job order');
            sessionStorage.setItem('link', 'controller/sales-proposal-controller.php');
        });

        $(document).on('click','.cancel-sp-additional-job-order',function() {
            const sales_proposal_additional_job_order_id = $(this).data('sales-proposal-additional-job-order-id');
            var parts_transaction_id = $('#parts-transaction-id').text();
            const transaction = 'cancel paid additional job order';
    
            Swal.fire({
                title: 'Confirm Additional Job Order Cancel Paid',
                text: 'Are you sure you want to tag this job order as cancel paid?',
                icon: 'warning',
                showCancelButton: !0,
                confirmButtonText: 'Cancel Paid',
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
                            sales_proposal_additional_job_order_id : sales_proposal_additional_job_order_id, 
                            parts_transaction_id : parts_transaction_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Tag As Cancel Paid Success', 'The job order has been tagged as cancel paid successfully.', 'success');
                                reloadDatatable('#additional-job-order-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else {
                                    showNotification('Tag As Cancel Paid Error', response.message, 'danger');
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

        $(document).on('click','.paid-bj-additional-job-order',function() {
            const job_order_id = $(this).data('backjob-monitoring-additional-job-order-id');
            $('#job_order_id').val(job_order_id);
            sessionStorage.setItem('transaction', 'paid additional job order');
            sessionStorage.setItem('link', 'controller/backjob-monitoring-controller.php');
        });

        $(document).on('click','.cancel-bj-additional-job-order',function() {
            const backjob_monitoring_additional_job_order_id = $(this).data('backjob-monitoring-additional-job-order-id');
            var parts_transaction_id = $('#parts-transaction-id').text();
            const transaction = 'cancel paid additional job order';
    
            Swal.fire({
                title: 'Confirm Additional Job Order Cancel Paid',
                text: 'Are you sure you want to tag this job order as cancel paid?',
                icon: 'warning',
                showCancelButton: !0,
                confirmButtonText: 'Cancel Paid',
                cancelButtonText: 'Cancel',
                confirmButtonClass: 'btn btn-warning mt-2',
                cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                buttonsStyling: !1
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        type: 'POST',
                        url: 'controller/backjob-monitoring-controller.php',
                        dataType: 'json',
                        data: {
                            backjob_monitoring_additional_job_order_id : backjob_monitoring_additional_job_order_id, 
                            parts_transaction_id : parts_transaction_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Tag As Cancel Paid Success', 'The job order has been tagged as cancel paid successfully.', 'success');
                                reloadDatatable('#internal-additional-job-order-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else {
                                    showNotification('Tag As Cancel Paid Error', response.message, 'danger');
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

        $(document).on('click','#apply-filter',function() {
            if($('#job-order-table').length){
                jobOrderTable('#job-order-table');
            }

            if($('#internal-job-order-table').length){
                internalJobOrderTable('#internal-job-order-table');
            }

            if($('#additional-job-order-table').length){
                additionalJobOrderTable('#additional-job-order-table');
            }

            if($('#internal-additional-job-order-table').length){
                internalAdditionalJobOrderTable('#internal-additional-job-order-table');
            }
        });

    });
})(jQuery);

function paidForm(){
    $('#paid-form').validate({
        rules: {
            reference_number: {
                required: true
            },
            payment_date: {
                required: true
            },
        },
        messages: {
            reference_number: {
                required: 'Enter the reference number'
            },
            payment_date: {
                required: 'Choose the reference number'
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
        
            $.ajax({
                type: 'POST',
                url: sessionStorage.getItem('link'),
                data: $(form).serialize() + '&transaction=' + sessionStorage.getItem('transaction'),
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-approve-return');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = 'Tag As Paid Success';
                        const notificationDescription = 'The payment has been tagged successfully.';
                        
                        showNotification(notificationMessage, notificationDescription, 'success');
                       
                        reloadDatatable('#job-order-table');
                        reloadDatatable('#additional-job-order-table');
                        reloadDatatable('#internal-job-order-table');
                        reloadDatatable('#internal-additional-job-order-table');
                    }
                    else {
                        if (response.isInactive) {
                            setNotification('User Inactive', response.message, 'danger');
                            window.location = 'logout.php?logout';
                        }
                        else if (response.partQuantityExceed) {
                            showNotification('Validate Return Error', 'One of the parts added does not have enough quantity. Kindly check the added parts.', 'danger');
                        }
                        else if (response.cartQuantity) {
                            showNotification('Validate Return Error', 'One of the parts added does not have enough quantity. Kindly check the added parts.', 'danger');
                        }
                        else if (response.jobOrder) {
                            showNotification('Validate Return Error', 'No job order or additional job order linked. Cannot be processed.', 'danger');
                        }
                        else {
                            showNotification('Return Error', response.message, 'danger');
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
                    enableFormSubmitButton('submit-approve-return', 'Submit');
                }
            });
        
            return false;
        }
    });
}


function jobOrderTable(datatable_name, buttons = false, show_all = false){
    const type = 'job order table 2';
    var payment_status_filter = [];

    $('.payment-status-checkbox:checked').each(function() {
        payment_status_filter.push($(this).val());
    });

    var filter_payment_status = payment_status_filter.join(', ');

    var settings;

    const column = [ 
        { 'data' : 'OS_NUMBER' },
        { 'data' : 'JOB_ORDER' },
        { 'data' : 'JOB_COST' },
        { 'data' : 'CONTRACTOR' },
        { 'data' : 'WORK_CENTER' },
        { 'data' : 'STATUS' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': 'auto', 'aTargets': 0 },
        { 'width': 'auto', 'aTargets': 1 },
        { 'width': 'auto', 'aTargets': 2 },
        { 'width': 'auto', 'aTargets': 3 },
        { 'width': 'auto', 'aTargets': 4 },
        { 'width': 'auto', 'aTargets': 5 },
        { 'width': '10%', 'bSortable': false, 'aTargets': 6 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_parts_transaction_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {'type' : type , 'filter_payment_status' : filter_payment_status},
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

function internalJobOrderTable(datatable_name, buttons = false, show_all = false){
    const type = 'internal job order table 2';
    var payment_status_filter = [];

    $('.payment-status-checkbox:checked').each(function() {
        payment_status_filter.push($(this).val());
    });

    var filter_payment_status = payment_status_filter.join(', ');
    var settings;

    const column = [ 
        { 'data' : 'TYPE' },
        { 'data' : 'OS_NUMBER' },
        { 'data' : 'JOB_ORDER' },
        { 'data' : 'JOB_COST' },
        { 'data' : 'CONTRACTOR' },
        { 'data' : 'WORK_CENTER' },
        { 'data' : 'STATUS' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': 'auto', 'aTargets': 0 },
        { 'width': 'auto', 'aTargets': 1 },
        { 'width': 'auto', 'aTargets': 2 },
        { 'width': 'auto', 'aTargets': 3 },
        { 'width': 'auto', 'aTargets': 4 },
        { 'width': 'auto', 'aTargets': 5 },
        { 'width': 'auto', 'aTargets': 6 },
        { 'width': '10%', 'bSortable': false, 'aTargets': 7 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_parts_transaction_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {'type' : type, 'filter_payment_status' : filter_payment_status},
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

function additionalJobOrderTable(datatable_name, buttons = false, show_all = false){
    const type = 'additional job order table 2';
    var payment_status_filter = [];

    $('.payment-status-checkbox:checked').each(function() {
        payment_status_filter.push($(this).val());
    });

    var filter_payment_status = payment_status_filter.join(', ');
    var settings;

    const column = [ 
        { 'data' : 'OS_NUMBER' },
        { 'data' : 'JOB_ORDER' },
        { 'data' : 'JOB_COST' },
        { 'data' : 'CONTRACTOR' },
        { 'data' : 'WORK_CENTER' },
        { 'data' : 'STATUS' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': 'auto', 'aTargets': 0 },
        { 'width': 'auto', 'aTargets': 1 },
        { 'width': 'auto', 'aTargets': 2 },
        { 'width': 'auto', 'aTargets': 3 },
        { 'width': 'auto', 'aTargets': 4 },
        { 'width': 'auto', 'aTargets': 5 },
        { 'width': '10%', 'bSortable': false, 'aTargets': 6 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_parts_transaction_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {'type' : type, 'filter_payment_status' : filter_payment_status},
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

function internalAdditionalJobOrderTable(datatable_name, buttons = false, show_all = false){
    const type = 'internal additional job order table 2';
    
    var payment_status_filter = [];

    $('.payment-status-checkbox:checked').each(function() {
        payment_status_filter.push($(this).val());
    });

    var filter_payment_status = payment_status_filter.join(', ');
    var settings;

    const column = [ 
        { 'data' : 'TYPE' },
        { 'data' : 'OS_NUMBER' },
        { 'data' : 'JOB_ORDER' },
        { 'data' : 'JOB_COST' },
        { 'data' : 'CONTRACTOR' },
        { 'data' : 'WORK_CENTER' },
        { 'data' : 'STATUS' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': 'auto', 'aTargets': 0 },
        { 'width': 'auto', 'aTargets': 1 },
        { 'width': 'auto', 'aTargets': 2 },
        { 'width': 'auto', 'aTargets': 3 },
        { 'width': 'auto', 'aTargets': 4 },
        { 'width': 'auto', 'aTargets': 6 },
        { 'width': '10%', 'bSortable': false, 'aTargets': 7 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_parts_transaction_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {'type' : type, 'filter_payment_status' : filter_payment_status},
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