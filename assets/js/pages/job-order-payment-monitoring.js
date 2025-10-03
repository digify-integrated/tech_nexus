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

        $(document).on('click','.paid-sp-job-order',function() {
            const sales_proposal_job_order_id = $(this).data('sales-proposal-job-order-id');
            var parts_transaction_id = $('#parts-transaction-id').text();
            const transaction = 'paid job order';
    
            Swal.fire({
                title: 'Confirm Job Order Tag As Paid',
                text: 'Are you sure you want to tag this job order as paid?',
                icon: 'warning',
                showCancelButton: !0,
                confirmButtonText: 'Paid',
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
                            sales_proposal_job_order_id : sales_proposal_job_order_id, 
                            parts_transaction_id : parts_transaction_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Tag As Paid Success', 'The job order has been tagged as paid successfully.', 'success');
                                reloadDatatable('#job-order-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else {
                                    showNotification('Tag As Paid Error', response.message, 'danger');
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
            const backjob_monitoring_job_order_id = $(this).data('backjob-monitoring-job-order-id');
            var parts_transaction_id = $('#parts-transaction-id').text();
            const transaction = 'paid job order';
    
            Swal.fire({
                title: 'Confirm Job Order Tag As Paid',
                text: 'Are you sure you want to tag this job order as paid?',
                icon: 'warning',
                showCancelButton: !0,
                confirmButtonText: 'Paid',
                cancelButtonText: 'Cancel',
                confirmButtonClass: 'btn btn-success mt-2',
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
                                showNotification('Tag As Paid Success', 'The job order has been tagged as paid successfully.', 'success');
                                reloadDatatable('#internal-job-order-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else {
                                    showNotification('Tag As Paid Error', response.message, 'danger');
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
            const sales_proposal_additional_job_order_id = $(this).data('sales-proposal-additional-job-order-id');
            var parts_transaction_id = $('#parts-transaction-id').text();
            const transaction = 'paid additional job order';
    
            Swal.fire({
                title: 'Confirm Additional Job Order Tag As Paid',
                text: 'Are you sure you want to tag this job order as paid?',
                icon: 'warning',
                showCancelButton: !0,
                confirmButtonText: 'Paid',
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
                            sales_proposal_additional_job_order_id : sales_proposal_additional_job_order_id, 
                            parts_transaction_id : parts_transaction_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Tag As Paid Success', 'The job order has been tagged as paid successfully.', 'success');
                                reloadDatatable('#additional-job-order-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else {
                                    showNotification('Tag As Paid Error', response.message, 'danger');
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
            const backjob_monitoring_additional_job_order_id = $(this).data('backjob-monitoring-additional-job-order-id');
            var parts_transaction_id = $('#parts-transaction-id').text();
            const transaction = 'paid additional job order';
    
            Swal.fire({
                title: 'Confirm Additional Job Order Tag As Paid',
                text: 'Are you sure you want to tag this job order as paid?',
                icon: 'warning',
                showCancelButton: !0,
                confirmButtonText: 'Paid',
                cancelButtonText: 'Cancel',
                confirmButtonClass: 'btn btn-success mt-2',
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
                                showNotification('Tag As Paid Success', 'The job order has been tagged as paid successfully.', 'success');
                                reloadDatatable('#internal-additional-job-order-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else {
                                    showNotification('Tag As Paid Error', response.message, 'danger');
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

    });
})(jQuery);

function jobOrderTable(datatable_name, buttons = false, show_all = false){
    const type = 'job order table 2';
    var settings;

    const column = [ 
        { 'data' : 'OS_NUMBER' },
        { 'data' : 'JOB_ORDER' },
        { 'data' : 'CONTRACTOR' },
        { 'data' : 'WORK_CENTER' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': 'auto', 'aTargets': 0 },
        { 'width': 'auto', 'aTargets': 1 },
        { 'width': 'auto', 'aTargets': 2 },
        { 'width': 'auto', 'aTargets': 3 },
        { 'width': '10%', 'bSortable': false, 'aTargets': 4 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_parts_transaction_generation.php',
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
    var settings;

    const column = [ 
        { 'data' : 'TYPE' },
        { 'data' : 'OS_NUMBER' },
        { 'data' : 'JOB_ORDER' },
        { 'data' : 'CONTRACTOR' },
        { 'data' : 'WORK_CENTER' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': 'auto', 'aTargets': 0 },
        { 'width': 'auto', 'aTargets': 1 },
        { 'width': 'auto', 'aTargets': 2 },
        { 'width': 'auto', 'aTargets': 3 },
        { 'width': 'auto', 'aTargets': 4 },
        { 'width': '10%', 'bSortable': false, 'aTargets': 5 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_parts_transaction_generation.php',
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
    var settings;

    const column = [ 
        { 'data' : 'OS_NUMBER' },
        { 'data' : 'JOB_ORDER' },
        { 'data' : 'CONTRACTOR' },
        { 'data' : 'WORK_CENTER' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': 'auto', 'aTargets': 0 },
        { 'width': 'auto', 'aTargets': 1 },
        { 'width': 'auto', 'aTargets': 2 },
        { 'width': 'auto', 'aTargets': 3 },
        { 'width': '10%', 'bSortable': false, 'aTargets': 4 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_parts_transaction_generation.php',
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
    var settings;

    const column = [ 
        { 'data' : 'TYPE' },
        { 'data' : 'OS_NUMBER' },
        { 'data' : 'JOB_ORDER' },
        { 'data' : 'CONTRACTOR' },
        { 'data' : 'WORK_CENTER' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': 'auto', 'aTargets': 0 },
        { 'width': 'auto', 'aTargets': 1 },
        { 'width': 'auto', 'aTargets': 2 },
        { 'width': 'auto', 'aTargets': 3 },
        { 'width': 'auto', 'aTargets': 4 },
        { 'width': '10%', 'bSortable': false, 'aTargets': 5 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_parts_transaction_generation.php',
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