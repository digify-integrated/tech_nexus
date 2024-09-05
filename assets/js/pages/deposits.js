(function($) {
    'use strict';

    $(function() {
        if($('#deposits-table').length){
            depositsTable('#deposits-table');
        }

        if($('#deposits-form').length){
            depositsForm();
        }

        if($('#deposits-id').length){
            displayDetails('get deposits details');
        }

        $(document).on('click','.delete-deposits',function() {
            const deposits_id = $(this).data('deposits-id');
            const transaction = 'delete deposits';
    
            Swal.fire({
                title: 'Confirm Deposits Deletion',
                text: 'Are you sure you want to delete this deposits?',
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
                        url: 'controller/deposits-controller.php',
                        dataType: 'json',
                        data: {
                            deposits_id : deposits_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete Deposits Success', 'The deposits has been deleted successfully.', 'success');
                                reloadDatatable('#deposits-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Delete Deposits Error', 'The deposits does not exist.', 'danger');
                                    reloadDatatable('#deposits-table');
                                }
                                else {
                                    showNotification('Delete Deposits Error', response.message, 'danger');
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

        $(document).on('click','#delete-deposits',function() {
            let deposits_id = [];
            const transaction = 'delete multiple deposits';

            $('.datatable-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    deposits_id.push(element.value);
                }
            });
    
            if(deposits_id.length > 0){
                Swal.fire({
                    title: 'Confirm Multiple Deposits Deletion',
                    text: 'Are you sure you want to delete these deposits?',
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
                            url: 'controller/deposits-controller.php',
                            dataType: 'json',
                            data: {
                                deposits_id: deposits_id,
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Delete Deposits Success', 'The selected deposits have been deleted successfully.', 'success');
                                        reloadDatatable('#deposits-table');
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else {
                                        showNotification('Delete Deposits Error', response.message, 'danger');
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
                showNotification('Deletion Multiple Deposits Error', 'Please select the deposits you wish to delete.', 'danger');
            }
        });

        $(document).on('click','#delete-deposits-details',function() {
            const deposits_id = $('#deposits-id').text();
            const transaction = 'delete deposits';
    
            Swal.fire({
                title: 'Confirm Deposits Deletion',
                text: 'Are you sure you want to delete this deposits?',
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
                        url: 'controller/deposits-controller.php',
                        dataType: 'json',
                        data: {
                            deposits_id : deposits_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Deleted Deposits Success', 'The deposits has been deleted successfully.', 'success');
                                window.location = 'deposits.php';
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
                                    showNotification('Delete Deposits Error', response.message, 'danger');
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
            discardCreate('deposits.php');
        });

        $(document).on('click','#apply-filter',function() {
            depositsTable('#deposits-table');
        });
        
        $(document).on('click','#print',function() {
            var checkedBoxes = [];
            var collection_report_date = $('#collection_report_date').val();
            var link = 'deposits-print.php?';

            $('.company-checkbox').each((index, element) => {
                if ($(element).is(':checked')) {
                    checkedBoxes.push(element.value);
                }
            });

            if(checkedBoxes != ''){
                link = link + 'id=' + checkedBoxes;
            }

            if(collection_report_date != '' && checkedBoxes != ''){
                link = link + '&date=' + collection_report_date;
            }
            else{
                link = link + 'date=' + collection_report_date;
            }

            window.open(link, '_blank');
        });
    });
})(jQuery);

function depositsTable(datatable_name, buttons = false, show_all = false){
    const type = 'deposits table';
    var filter_transaction_date_start_date = $('#filter_transaction_date_start_date').val();
    var filter_transaction_date_end_date = $('#filter_transaction_date_end_date').val();

    var filter_deposit_date_start_date = $('#filter_deposit_date_start_date').val();
    var filter_deposit_date_end_date = $('#filter_deposit_date_end_date').val();
    
    var settings;

    const column = [ 
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'DEPOSIT_AMOUNT' },
        { 'data' : 'DEPOSIT_DATE' },
        { 'data' : 'DEPOSITED_TO' },
        { 'data' : 'REFERENCE_NUMBER' },
        { 'data' : 'TRANSACTION_DATE' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '1%','bSortable': false, 'aTargets': 0 },
        { 'width': 'auto', 'aTargets': 1 },
        { 'width': 'auto', 'type': 'date', 'aTargets': 2 },
        { 'width': 'auto', 'aTargets': 3 },
        { 'width': 'auto', 'aTargets': 4 },
        { 'width': 'auto', 'type': 'date', 'aTargets': 5 },
        { 'width': '15%','bSortable': false, 'aTargets': 6 },
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_deposits_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {'type' : type, 
                'filter_transaction_date_start_date' : filter_transaction_date_start_date, 
                'filter_transaction_date_end_date' : filter_transaction_date_end_date, 
                'filter_deposit_date_start_date' : filter_deposit_date_start_date, 
                'filter_deposit_date_end_date' : filter_deposit_date_end_date
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
        'order': [[ 2, 'asc' ]],
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

function depositsForm(){
    $('#deposits-form').validate({
        rules: {
            deposit_amount: {
                required: true
            },
            company_id: {
                required: true
            },
            deposit_date: {
                required: true
            },
            deposited_to: {
                required: true
            }
        },
        messages: {
            deposit_amount: {
                required: 'Please choose the mode of payment'
            },
            company_id: {
                required: 'Please choose the company'
            },
            deposit_date: {
                required: 'Please choose the deposits type'
            },
            deposited_to: {
                required: 'Please choose the company'
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
            const deposits_id = $('#deposits-id').text();
            const transaction = 'save deposits';
        
            $.ajax({
                type: 'POST',
                url: 'controller/deposits-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&deposits_id=' + deposits_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-data');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert Deposits Success' : 'Update Deposits Success';
                        const notificationDescription = response.insertRecord ? 'The deposits has been inserted successfully.' : 'The deposits has been updated successfully.';
                        
                        setNotification(notificationMessage, notificationDescription, 'success');
                        window.location = 'deposits.php?id=' + response.depositsID;
                    }
                    else {
                        if (response.isInactive) {
                            setNotification('User Inactive', response.message, 'danger');
                            window.location = 'logout.php?logout';
                        }
                        else if (response.checkConflict) {
                            showNotification('Insert Deposits Management Error', 'The check number you entered conflicts to the existing check number on this loan.', 'danger');
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
        case 'get deposits details':
            const deposits_id = $('#deposits-id').text();
            
            $.ajax({
                url: 'controller/deposits-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    deposits_id : deposits_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetForm('deposits-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#deposit_amount').val(response.depositAmount);
                        $('#reference_number').val(response.referenceNumber);
                        $('#remarks').val(response.remarks);
                        $('#deposit_date').val(response.depositDate);

                        checkOptionExist('#company_id', response.companyID, '');
                        checkOptionExist('#deposited_to', response.depositedTo, '');
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Deposits Details Error', response.message, 'danger');
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