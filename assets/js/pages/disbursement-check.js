(function($) {
    'use strict';

    $(function() {
        if($('#disbursement-check-table').length){
            disbursementTable('#disbursement-check-table');
        }

        $(document).on('click','#apply-filter',function() {
            disbursementTable('#disbursement-check-table');
        });

        $(document).on('click','#transmit-disbursement-check',function() {
            let disbursement_check_id = [];
            const transaction = 'transmit multiple disbursement check';

            $('.datatable-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    disbursement_check_id.push(element.value);
                }
            });
    
            if(disbursement_check_id.length > 0){
                Swal.fire({
                    title: 'Confirm Multiple Disbursement Check Transmit',
                    text: 'Are you sure you want to transmit these disbursement check?',
                    icon: 'warning',
                    showCancelButton: !0,
                    confirmButtonText: 'Transmit',
                    cancelButtonText: 'Cancel',
                    confirmButtonClass: 'btn btn-success mt-2',
                    cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                    buttonsStyling: !1
                }).then(function(result) {
                    if (result.value) {
                        $.ajax({
                            type: 'POST',
                            url: 'controller/disbursement-controller.php',
                            dataType: 'json',
                            data: {
                                disbursement_check_id: disbursement_check_id,
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Transmit Disbursement Checks Success', 'The selected disbursement checks have been transmitted successfully.', 'success');
                                    disbursementTable('#disbursement-check-table');
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else {
                                        showNotification('Transmit Disbursement Checks Error', response.message, 'danger');
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
                            complete: function(){
                                toggleHideActionDropdown();
                            }
                        });
                        
                        return false;
                    }
                });
            }
            else{
                showNotification('Transmit Disbursement Checks Error', 'Please select the disbursement checks you wish to transmit.', 'danger');
            }
        });

        $(document).on('click','#outstanding-disbursement-check',function() {
            let disbursement_check_id = [];
            const transaction = 'outstanding multiple disbursement check';

            $('.datatable-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    disbursement_check_id.push(element.value);
                }
            });
    
            if(disbursement_check_id.length > 0){
                Swal.fire({
                    title: 'Confirm Multiple Disbursement Check Outstanding',
                    text: 'Are you sure you want to outstanding these disbursement check?',
                    icon: 'warning',
                    showCancelButton: !0,
                    confirmButtonText: 'Outstanding',
                    cancelButtonText: 'Cancel',
                    confirmButtonClass: 'btn btn-success mt-2',
                    cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                    buttonsStyling: !1
                }).then(function(result) {
                    if (result.value) {
                        $.ajax({
                            type: 'POST',
                            url: 'controller/disbursement-controller.php',
                            dataType: 'json',
                            data: {
                                disbursement_check_id: disbursement_check_id,
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Outstanding Disbursement Checks Success', 'The selected disbursement checks have been outstanding successfully.', 'success');
                                    disbursementTable('#disbursement-check-table');
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else {
                                        showNotification('Outstanding Disbursement Checks Error', response.message, 'danger');
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
                            complete: function(){
                                toggleHideActionDropdown();
                            }
                        });
                        
                        return false;
                    }
                });
            }
            else{
                showNotification('Outstanding Disbursement Checks Error', 'Please select the disbursement checks you wish to outstanding.', 'danger');
            }
        });

        $(document).on('click','#negotiated-disbursement-check',function() {
            let disbursement_check_id = [];
            const transaction = 'negotiated multiple disbursement check';

            $('.datatable-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    disbursement_check_id.push(element.value);
                }
            });
    
            if(disbursement_check_id.length > 0){
                Swal.fire({
                    title: 'Confirm Multiple Disbursement Check Negotiated',
                    text: 'Are you sure you want to negotiated these disbursement check?',
                    icon: 'warning',
                    showCancelButton: !0,
                    confirmButtonText: 'Negotiated',
                    cancelButtonText: 'Cancel',
                    confirmButtonClass: 'btn btn-success mt-2',
                    cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                    buttonsStyling: !1
                }).then(function(result) {
                    if (result.value) {
                        $.ajax({
                            type: 'POST',
                            url: 'controller/disbursement-controller.php',
                            dataType: 'json',
                            data: {
                                disbursement_check_id: disbursement_check_id,
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Negotiated Disbursement Checks Success', 'The selected disbursement checks have been negotiated successfully.', 'success');
                                    disbursementTable('#disbursement-check-table');
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else {
                                        showNotification('Negotiated Disbursement Checks Error', response.message, 'danger');
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
                            complete: function(){
                                toggleHideActionDropdown();
                            }
                        });
                        
                        return false;
                    }
                });
            }
            else{
                showNotification('Negotiated Disbursement Checks Error', 'Please select the disbursement checks you wish to negotiated.', 'danger');
            }
        });
    });
})(jQuery);

function disbursementTable(datatable_name, buttons = false, show_all = false){
    const type = 'disbursement check monitoring table';
    var filter_check_date_start_date = $('#filter_check_date_start_date').val();
    var filter_check_date_end_date = $('#filter_check_date_end_date').val();
    var filter_transmitted_date_start_date = $('#filter_transmitted_date_start_date').val();
    var filter_transmitted_date_end_date = $('#filter_transmitted_date_end_date').val();
    var filter_outstanding_date_start_date = $('#filter_outstanding_date_start_date').val();
    var filter_outstanding_date_end_date = $('#filter_outstanding_date_end_date').val();
    var filter_negotiated_date_start_date = $('#filter_negotiated_date_start_date').val();
    var filter_negotiated_date_end_date = $('#filter_negotiated_date_end_date').val();
    var filter_check_status = $('.check-status-filter:checked').val();

    var settings;

    const column = [
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'TRANSACTION_DATE' },
        { 'data' : 'CUSTOMER_NAME' },
        { 'data' : 'DEPARTMENT_NAME' },
        { 'data' : 'COMPANY_NAME' },
        { 'data' : 'TRANSACTION_NUMBER' },
        { 'data' : 'CHECK_NUMBER' },
        { 'data' : 'CHECK_DATE' },
        { 'data' : 'CHECK_AMOUNT' },
        { 'data' : 'REVERSAL_DATE' },
        { 'data' : 'TRANSMITTED_DATE' },
        { 'data' : 'OUTSTANDING_DATE' },
        { 'data' : 'NEGOTIATED_DATE' },
        { 'data' : 'CHECK_STATUS' }
    ];

    const column_definition = [
        { 'width': '1%','bSortable': false, 'aTargets': 0 },
        { 'width': 'auto', 'type': 'date', 'aTargets': 1 },
        { 'width': 'auto', 'aTargets': 2 },
        { 'width': 'auto', 'aTargets': 3 },
        { 'width': 'auto', 'aTargets': 4 },
        { 'width': 'auto', 'aTargets': 5 },
        { 'width': 'auto', 'aTargets': 6 },
        { 'width': 'auto', 'aTargets': 7 },
        { 'width': 'auto', 'aTargets': 8 },
        { 'width': 'auto', 'aTargets': 9 },
        { 'width': 'auto', 'aTargets': 10 },
        { 'width': 'auto', 'aTargets': 11 },
        { 'width': 'auto', 'aTargets': 12 },
        { 'width': 'auto', 'aTargets': 13 },
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_disbursement_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {'type' : type, 
                'filter_check_date_start_date' : filter_check_date_start_date, 
                'filter_check_date_end_date' : filter_check_date_end_date,
                'filter_transmitted_date_start_date' : filter_transmitted_date_start_date,
                'filter_transmitted_date_end_date' : filter_transmitted_date_end_date,
                'filter_outstanding_date_start_date' : filter_outstanding_date_start_date,
                'filter_outstanding_date_end_date' : filter_outstanding_date_end_date,
                'filter_negotiated_date_start_date' : filter_negotiated_date_start_date,
                'filter_negotiated_date_end_date' : filter_negotiated_date_end_date,
                'filter_check_status' : filter_check_status,
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