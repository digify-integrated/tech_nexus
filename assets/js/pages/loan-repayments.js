(function($) {
    'use strict';

    $(function() {
        if($('#loan-repayments-table').length){
            loanRepaymentsTable('#loan-repayments-table');
        }

        if($('#loan-repayments-form').length){
            loanRepaymentsForm();
        }

        if($('#loan-repayments-id').length){
            displayDetails('get leave type details');
        }

        $(document).on('click','.delete-loan-repayments',function() {
            const loan_repayments_id = $(this).data('loan-repayments-id');
            const transaction = 'delete leave type';
    
            Swal.fire({
                title: 'Confirm Leave Type Deletion',
                text: 'Are you sure you want to delete this leave type?',
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
                        url: 'controller/loan-repayments-controller.php',
                        dataType: 'json',
                        data: {
                            loan_repayments_id : loan_repayments_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete Leave Type Success', 'The leave type has been deleted successfully.', 'success');
                                reloadDatatable('#loan-repayments-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Delete Leave Type Error', 'The leave type does not exist.', 'danger');
                                    reloadDatatable('#loan-repayments-table');
                                }
                                else {
                                    showNotification('Delete Leave Type Error', response.message, 'danger');
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

        $(document).on('click','#delete-loan-repayments',function() {
            let loan_repayments_id = [];
            const transaction = 'delete multiple leave type';

            $('.datatable-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    loan_repayments_id.push(element.value);
                }
            });
    
            if(loan_repayments_id.length > 0){
                Swal.fire({
                    title: 'Confirm Multiple Leave Types Deletion',
                    text: 'Are you sure you want to delete these leave types?',
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
                            url: 'controller/loan-repayments-controller.php',
                            dataType: 'json',
                            data: {
                                loan_repayments_id: loan_repayments_id,
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Delete Leave Type Success', 'The selected leave types have been deleted successfully.', 'success');
                                        reloadDatatable('#loan-repayments-table');
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else {
                                        showNotification('Delete Leave Type Error', response.message, 'danger');
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
                showNotification('Deletion Multiple Leave Type Error', 'Please select the leave types you wish to delete.', 'danger');
            }
        });

        $(document).on('click','#delete-loan-repayments-details',function() {
            const loan_repayments_id = $('#loan-repayments-id').text();
            const transaction = 'delete leave type';
    
            Swal.fire({
                title: 'Confirm Leave Type Deletion',
                text: 'Are you sure you want to delete this leave type?',
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
                        url: 'controller/loan-repayments-controller.php',
                        dataType: 'json',
                        data: {
                            loan_repayments_id : loan_repayments_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Deleted Leave Type Success', 'The leave type has been deleted successfully.', 'success');
                                window.location = 'loan-repayments.php';
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
                                    showNotification('Delete Leave Type Error', response.message, 'danger');
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

        $(document).on('click','#discard-create',function() {
            discardCreate('loan-repayments.php');
        });

        $(document).on('click','#edit-form',function() {
            displayDetails('get leave type details');

            enableForm();
        });

        $(document).on('click','#duplicate-loan-repayments',function() {
            const loan_repayments_id = $('#loan-repayments-id').text();
            const transaction = 'duplicate leave type';
    
            Swal.fire({
                title: 'Confirm Leave Type Duplication',
                text: 'Are you sure you want to duplicate this leave type?',
                icon: 'info',
                showCancelButton: !0,
                confirmButtonText: 'Duplicate',
                cancelButtonText: 'Cancel',
                confirmButtonClass: 'btn btn-info mt-2',
                cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                buttonsStyling: !1
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        type: 'POST',
                        url: 'controller/loan-repayments-controller.php',
                        dataType: 'json',
                        data: {
                            loan_repayments_id : loan_repayments_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Duplicate Leave Type Success', 'The leave type has been duplicated successfully.', 'success');
                                window.location = 'loan-repayments.php?id=' + response.loanRepaymentsID;
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Duplicate Leave Type Error', 'The leave type does not exist.', 'danger');
                                    reloadDatatable('#loan-repayments-table');
                                }
                                else {
                                    showNotification('Duplicate Leave Type Error', response.message, 'danger');
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
            loanRepaymentsTable('#loan-repayments-table');
        });
    });
})(jQuery);

function loanRepaymentsTable(datatable_name, buttons = false, show_all = false){
    const type = 'loan repayments table';
    var filter_due_date_start_date = $('#filter_due_date_start_date').val();
    var filter_due_date_end_date = $('#filter_due_date_end_date').val();
    var filter_repayments_status = $('.loan-repayments-status-filter:checked').val();
    var settings;

    const column = [ 
        { 'data' : 'LOAN_NUMBER' },
        { 'data' : 'REFERENCE' },
        { 'data' : 'DUE_DATE' },
        { 'data' : 'STATUS' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': 'auto', 'aTargets': 0 },
        { 'width': 'auto', 'aTargets': 1 },
        { 'width': 'auto', 'aTargets': 2 },
        { 'width': 'auto', 'aTargets': 3 },
        { 'width': '15%','bSortable': false, 'aTargets': 4 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_loan_repayments_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {'type' : type, 'filter_due_date_start_date' : filter_due_date_start_date, 'filter_due_date_end_date' : filter_due_date_end_date, 'filter_repayments_status' : filter_repayments_status},
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

function loanRepaymentsForm(){
    $('#loan-repayments-form').validate({
        rules: {
            loan_repayments_name: {
                required: true
            },
            is_paid: {
                required: true
            }
        },
        messages: {
            loan_repayments_name: {
                required: 'Please enter the leave type name'
            },
            is_paid: {
                required: 'Please choose if the leave type is paid or not'
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
            const loan_repayments_id = $('#loan-repayments-id').text();
            const transaction = 'save leave type';
        
            $.ajax({
                type: 'POST',
                url: 'controller/loan-repayments-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&loan_repayments_id=' + loan_repayments_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-data');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert Leave Type Success' : 'Update Leave Type Success';
                        const notificationDescription = response.insertRecord ? 'The leave type has been inserted successfully.' : 'The leave type has been updated successfully.';
                        
                        setNotification(notificationMessage, notificationDescription, 'success');
                        window.location = 'loan-repayments.php?id=' + response.loanRepaymentsID;
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
                    enableFormSubmitButton('submit-data', 'Save');
                }
            });
        
            return false;
        }
    });
}

function displayDetails(transaction){
    switch (transaction) {
        case 'get leave type details':
            const loan_repayments_id = $('#loan-repayments-id').text();
            
            $.ajax({
                url: 'controller/loan-repayments-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    loan_repayments_id : loan_repayments_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetForm('loan-repayments-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#loan_repayments_id').val(loan_repayments_id);
                        $('#loan_repayments_name').val(response.loanRepaymentsName);

                        checkOptionExist('#is_paid', response.isPaid, '');

                        $('#loan_repayments_name_label').text(response.loanRepaymentsName);
                        $('#is_paid_label').text(response.isPaid);
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Leave Type Details Error', response.message, 'danger');
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
            break;
    }
}