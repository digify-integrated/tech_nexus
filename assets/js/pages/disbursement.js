(function($) {
    'use strict';

    $(function() {
        if($('#disbursement-table').length){
            disbursementTable('#disbursement-table');
        }

        if($('#disbursement-form').length){
            disbursementForm();
        }

        if($('#disbursement-id').length){
            displayDetails('get disbursement details');
        }

        $(document).on('click','.delete-disbursement',function() {
            const disbursement_id = $(this).data('disbursement-id');
            const transaction = 'delete disbursement';
    
            Swal.fire({
                title: 'Confirm Disbursement Deletion',
                text: 'Are you sure you want to delete this disbursement?',
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
                        url: 'controller/disbursement-controller.php',
                        dataType: 'json',
                        data: {
                            disbursement_id : disbursement_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete Disbursement Success', 'The disbursement has been deleted successfully.', 'success');
                                reloadDatatable('#disbursement-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Delete Disbursement Error', 'The disbursement does not exist.', 'danger');
                                    reloadDatatable('#disbursement-table');
                                }
                                else {
                                    showNotification('Delete Disbursement Error', response.message, 'danger');
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

        $(document).on('click','#delete-disbursement',function() {
            let disbursement_id = [];
            const transaction = 'delete multiple disbursement';

            $('.datatable-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    disbursement_id.push(element.value);
                }
            });
    
            if(disbursement_id.length > 0){
                Swal.fire({
                    title: 'Confirm Multiple Disbursement Deletion',
                    text: 'Are you sure you want to delete these disbursement?',
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
                            url: 'controller/disbursement-controller.php',
                            dataType: 'json',
                            data: {
                                disbursement_id: disbursement_id,
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Delete Disbursement Success', 'The selected disbursement have been deleted successfully.', 'success');
                                        reloadDatatable('#disbursement-table');
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else {
                                        showNotification('Delete Disbursement Error', response.message, 'danger');
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
                showNotification('Deletion Multiple Disbursement Error', 'Please select the disbursement you wish to delete.', 'danger');
            }
        });

        $(document).on('click','#delete-disbursement-details',function() {
            const disbursement_id = $('#disbursement-id').text();
            const transaction = 'delete disbursement';
    
            Swal.fire({
                title: 'Confirm Disbursement Deletion',
                text: 'Are you sure you want to delete this disbursement?',
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
                        url: 'controller/disbursement-controller.php',
                        dataType: 'json',
                        data: {
                            disbursement_id : disbursement_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Deleted Disbursement Success', 'The disbursement has been deleted successfully.', 'success');
                                window.location = 'disbursement.php';
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
                                    showNotification('Delete Disbursement Error', response.message, 'danger');
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

        $(document).on('click','#post-disbursement',function() {
            const disbursement_id = $('#disbursement-id').text();
            const transaction = 'post disbursement';
    
            Swal.fire({
                title: 'Confirm Disbursement Posting',
                text: 'Are you sure you want to post this disbursement?',
                icon: 'warning',
                showCancelButton: !0,
                confirmButtonText: 'Post',
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
                            disbursement_id : disbursement_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Post Disbursement Success', 'The disbursement has been posted successfully.', 'success');
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
                                    showNotification('Post Disbursement Error', response.message, 'danger');
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
            discardCreate('disbursement.php');
        });

        $(document).on('click','#edit-form',function() {
            displayDetails('get disbursement details');

            enableForm();
        });

        $(document).on('click','#apply-filter',function() {
            disbursementTable('#disbursement-table');
        });

        $(document).on('click','#print',function() {
            var checkedBoxes = [];

            $('.datatable-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    checkedBoxes.push(element.value);
                }
            });

            if(checkedBoxes != ''){
                window.open('disbursement-print.php?id=' + checkedBoxes, '_blank');
            }
            else{
                showNotification('Print Disbursements Error', 'No selected disbursement.', 'danger');
            }
        });
    });
})(jQuery);

function disbursementTable(datatable_name, buttons = false, show_all = false){
    const type = 'disbursement table';
    var filter_transaction_date_start_date = $('#filter_transaction_date_start_date').val();
    var filter_transaction_date_end_date = $('#filter_transaction_date_end_date').val();

    var settings;

    const column = [
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'TRANSACTION_DATE' },
        { 'data' : 'TRANSACTION_NUMBER' },
        { 'data' : 'CUSTOMER' },
        { 'data' : 'TRANSACTION_TYPE' },
        { 'data' : 'DEPARTMENT' },
        { 'data' : 'COMPANY' },
        { 'data' : 'PARTICULARS' },
        { 'data' : 'DISBURSMENT_AMOUNT' },
        { 'data' : 'REFERENCE_NUMBER' },
        { 'data' : 'ACTION' }
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
        { 'width': '15%','bSortable': false, 'aTargets': 10 },
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_disbursement_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {'type' : type, 
                'filter_transaction_date_start_date' : filter_transaction_date_start_date, 
                'filter_transaction_date_end_date' : filter_transaction_date_end_date
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

function disbursementForm(){
    $('#disbursement-form').validate({
        rules: {
            transaction_type: {
                required: true
            },
            transaction_number: {
                required: true
            },
            disbursement_amount: {
                required: true
            },
            particulars: {
                required: true
            }
        },
        messages: {
            transaction_type: {
                required: 'Please choose the mode of payment'
            },
            transaction_number: {
                required: 'Please enter the transaction number'
            },
            disbursement_amount: {
                required: 'Please enter the disbursement amount'
            },
            particulars: {
                required: 'Please enter the particulars'
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
            const disbursement_id = $('#disbursement-id').text();
            const transaction = 'save disbursement';
        
            $.ajax({
                type: 'POST',
                url: 'controller/disbursement-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&disbursement_id=' + disbursement_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-data');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert Disbursement Success' : 'Update Disbursement Success';
                        const notificationDescription = response.insertRecord ? 'The disbursement has been inserted successfully.' : 'The disbursement  has been updated successfully.';
                        
                        setNotification(notificationMessage, notificationDescription, 'success');
                        window.location = 'disbursement.php?id=' + response.disbursementID;
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
        case 'get disbursement details':
            const disbursement_id = $('#disbursement-id').text();
            
            $.ajax({
                url: 'controller/disbursement-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    disbursement_id : disbursement_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetForm('disbursement-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#transaction_number').val(response.transactionNumber);
                        $('#reference_number').val(response.referenceNumber);
                        $('#disbursement_amount').val(response.disbursementAmount);
                        $('#particulars').val(response.particulars);

                        checkOptionExist('#transaction_type', response.transactionType, '');
                        checkOptionExist('#customer_id', response.customerID, '');
                        checkOptionExist('#department_id', response.departmentID, '');
                        checkOptionExist('#company_id', response.companyID, '');
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Disbursement Details Error', response.message, 'danger');
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