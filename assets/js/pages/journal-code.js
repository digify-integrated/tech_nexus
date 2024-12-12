(function($) {
    'use strict';

    $(function() {
        if($('#journal-code-table').length){
            journalCodeTable('#journal-code-table');
        }

        if($('#journal-code-form').length){
            journalCodeForm();
        }

        if($('#journal-code-id').length){
            displayDetails('get journal code details');
        }

        $(document).on('click','.delete-journal-code',function() {
            const journal_code_id = $(this).data('journal-code-id');
            const transaction = 'delete journal code';
    
            Swal.fire({
                title: 'Confirm Journal Code Deletion',
                text: 'Are you sure you want to delete this journal code?',
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
                        url: 'controller/journal-code-controller.php',
                        dataType: 'json',
                        data: {
                            journal_code_id : journal_code_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete Journal Code Success', 'The journal code has been deleted successfully.', 'success');
                                reloadDatatable('#journal-code-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Delete Journal Code Error', 'The journal code does not exist.', 'danger');
                                    reloadDatatable('#journal-code-table');
                                }
                                else {
                                    showNotification('Delete Journal Code Error', response.message, 'danger');
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

        $(document).on('click','#delete-journal-code',function() {
            let journal_code_id = [];
            const transaction = 'delete multiple journal code';

            $('.datatable-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    journal_code_id.push(element.value);
                }
            });
    
            if(journal_code_id.length > 0){
                Swal.fire({
                    title: 'Confirm Multiple Journal Codes Deletion',
                    text: 'Are you sure you want to delete these journal codes?',
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
                            url: 'controller/journal-code-controller.php',
                            dataType: 'json',
                            data: {
                                journal_code_id: journal_code_id,
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Delete Journal Code Success', 'The selected journal codes have been deleted successfully.', 'success');
                                    reloadDatatable('#journal-code-table');
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else {
                                        showNotification('Delete Journal Code Error', response.message, 'danger');
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
                showNotification('Deletion Multiple Journal Code Error', 'Please select the journal codes you wish to delete.', 'danger');
            }
        });

        $(document).on('click','#delete-journal-code-details',function() {
            const journal_code_id = $('#journal-code-id').text();
            const transaction = 'delete journal code';
    
            Swal.fire({
                title: 'Confirm Journal Code Deletion',
                text: 'Are you sure you want to delete this journal code?',
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
                        url: 'controller/journal-code-controller.php',
                        dataType: 'json',
                        data: {
                            journal_code_id : journal_code_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Deleted Journal Code Success', 'The journal code has been deleted successfully.', 'success');
                                window.location = 'journal-code.php';
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
                                    showNotification('Delete Journal Code Error', response.message, 'danger');
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
            discardCreate('journal-code.php');
        });

        $(document).on('click','#edit-form',function() {
            displayDetails('get journal code details');

            enableForm();
        });

        $(document).on('click','#duplicate-journal-code',function() {
            const journal_code_id = $('#journal-code-id').text();
            const transaction = 'duplicate journal code';
    
            Swal.fire({
                title: 'Confirm Journal Code Duplication',
                text: 'Are you sure you want to duplicate this journal code?',
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
                        url: 'controller/journal-code-controller.php',
                        dataType: 'json',
                        data: {
                            journal_code_id : journal_code_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Duplicate Journal Code Success', 'The journal code has been duplicated successfully.', 'success');
                                window.location = 'journal-code.php?id=' + response.journalCodeID;
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Duplicate Journal Code Error', 'The journal code does not exist.', 'danger');
                                    reloadDatatable('#journal-code-table');
                                }
                                else {
                                    showNotification('Duplicate Journal Code Error', response.message, 'danger');
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

function journalCodeTable(datatable_name, buttons = false, show_all = false){
    const type = 'journal code table';
    var settings;

    const column = [ 
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'COMPANY_ID' },
        { 'data' : 'TRANSACTION_TYPE' },
        { 'data' : 'PRODUCT_TYPE' },
        { 'data' : 'TRANSACTION' },
        { 'data' : 'ITEM' },
        { 'data' : 'DEBIT' },
        { 'data' : 'CREDIT' },
        { 'data' : 'REFERENCE_CODE' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '1%','bSortable': false, 'aTargets': 0 },
        { 'width': 'auto', 'aTargets': 1 },
        { 'width': 'auto', 'aTargets': 2 },
        { 'width': 'auto', 'aTargets': 3 },
        { 'width': 'auto', 'aTargets': 4 },
        { 'width': 'auto', 'aTargets': 5 },
        { 'width': 'auto', 'aTargets': 6 },
        { 'width': 'auto', 'aTargets': 7 },
        { 'width': 'auto', 'aTargets': 8 },
        { 'width': '15%','bSortable': false, 'aTargets': 9 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_journal_code_generation.php',
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

function journalCodeForm(){
    $('#journal-code-form').validate({
        rules: {
            company_id: {
                required: true
            },
            transaction_type: {
                required: true
            },
            product_type_id: {
                required: true
            },
            transaction: {
                required: true
            },
            item: {
                required: true
            },
            debit: {
                required: true
            },
            credit: {
                required: true
            },
        },
        messages: {
            company_id: {
                required: 'Please choose the company'
            },
            transaction_type: {
                required: 'Please choose the transaction type'
            },
            product_type_id: {
                required: 'Please choose the product'
            },
            transaction: {
                required: 'Please choose the transaction'
            },
            item: {
                required: 'Please choose the item'
            },
            debit: {
                required: 'Please choose the debit'
            },
            credit: {
                required: 'Please choose the credit'
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
            const journal_code_id = $('#journal-code-id').text();
            const transaction = 'save journal code';
        
            $.ajax({
                type: 'POST',
                url: 'controller/journal-code-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&journal_code_id=' + journal_code_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-data');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert Journal Code Success' : 'Update Journal Code Success';
                        const notificationDescription = response.insertRecord ? 'The journal code has been inserted successfully.' : 'The journal code has been updated successfully.';
                        
                        setNotification(notificationMessage, notificationDescription, 'success');
                        window.location = 'journal-code.php?id=' + response.journalCodeID;
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
        case 'get journal code details':
            const journal_code_id = $('#journal-code-id').text();
            
            $.ajax({
                url: 'controller/journal-code-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    journal_code_id : journal_code_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetForm('journal-code-form');
                },
                success: function(response) {
                    if (response.success) {
                        checkOptionExist('#company_id', response.companyID, '');
                        checkOptionExist('#transaction_type', response.transactionType, '');
                        checkOptionExist('#product_type_id', response.productTypeID, '');
                        checkOptionExist('#jtransaction', response.jtransaction, '');
                        checkOptionExist('#item', response.item, '');
                        checkOptionExist('#debit', response.debit, '');
                        checkOptionExist('#credit', response.credit, '');
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Journal Code Details Error', response.message, 'danger');
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