(function($) {
    'use strict';

    $(function() {
        if($('#bank-account-type-table').length){
            bankAccountTypeTable('#bank-account-type-table');
        }

        if($('#bank-account-type-form').length){
            bankAccountTypeForm();
        }

        if($('#bank-account-type-id').length){
            displayDetails('get bank account type details');
        }

        $(document).on('click','.delete-bank-account-type',function() {
            const bank_account_type_id = $(this).data('bank-account-type-id');
            const transaction = 'delete bank account type';
    
            Swal.fire({
                title: 'Confirm Bank Account Type Deletion',
                text: 'Are you sure you want to delete this bank account type?',
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
                        url: 'controller/bank-account-type-controller.php',
                        dataType: 'json',
                        data: {
                            bank_account_type_id : bank_account_type_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete Bank Account Type Success', 'The bank account type has been deleted successfully.', 'success');
                                reloadDatatable('#bank-account-type-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Delete Bank Account Type Error', 'The bank account type does not exist.', 'danger');
                                    reloadDatatable('#bank-account-type-table');
                                }
                                else {
                                    showNotification('Delete Bank Account Type Error', response.message, 'danger');
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

        $(document).on('click','#delete-bank-account-type',function() {
            let bank_account_type_id = [];
            const transaction = 'delete multiple bank account type';

            $('.datatable-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    bank_account_type_id.push(element.value);
                }
            });
    
            if(bank_account_type_id.length > 0){
                Swal.fire({
                    title: 'Confirm Multiple Bank Account Types Deletion',
                    text: 'Are you sure you want to delete these bank account types?',
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
                            url: 'controller/bank-account-type-controller.php',
                            dataType: 'json',
                            data: {
                                bank_account_type_id: bank_account_type_id,
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Delete Bank Account Type Success', 'The selected bank account types have been deleted successfully.', 'success');
                                    reloadDatatable('#bank-account-type-table');
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else {
                                        showNotification('Delete Bank Account Type Error', response.message, 'danger');
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
                showNotification('Deletion Multiple Bank Account Type Error', 'Please select the bank account types you wish to delete.', 'danger');
            }
        });

        $(document).on('click','#delete-bank-account-type-details',function() {
            const bank_account_type_id = $('#bank-account-type-id').text();
            const transaction = 'delete bank account type';
    
            Swal.fire({
                title: 'Confirm Bank Account Type Deletion',
                text: 'Are you sure you want to delete this bank account type?',
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
                        url: 'controller/bank-account-type-controller.php',
                        dataType: 'json',
                        data: {
                            bank_account_type_id : bank_account_type_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Deleted Bank Account Type Success', 'The bank account type has been deleted successfully.', 'success');
                                window.location = 'bank-account-type.php';
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
                                    showNotification('Delete Bank Account Type Error', response.message, 'danger');
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
            discardCreate('bank-account-type.php');
        });

        $(document).on('click','#edit-form',function() {
            displayDetails('get bank account type details');

            enableForm();
        });

        $(document).on('click','#duplicate-bank-account-type',function() {
            const bank_account_type_id = $('#bank-account-type-id').text();
            const transaction = 'duplicate bank account type';
    
            Swal.fire({
                title: 'Confirm Bank Account Type Duplication',
                text: 'Are you sure you want to duplicate this bank account type?',
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
                        url: 'controller/bank-account-type-controller.php',
                        dataType: 'json',
                        data: {
                            bank_account_type_id : bank_account_type_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Duplicate Bank Account Type Success', 'The bank account type has been duplicated successfully.', 'success');
                                window.location = 'bank-account-type.php?id=' + response.bankAccountTypeID;
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Duplicate Bank Account Type Error', 'The bank account type does not exist.', 'danger');
                                    reloadDatatable('#bank-account-type-table');
                                }
                                else {
                                    showNotification('Duplicate Bank Account Type Error', response.message, 'danger');
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

function bankAccountTypeTable(datatable_name, buttons = false, show_all = false){
    const type = 'bank account type table';
    var settings;

    const column = [ 
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'BANK_ACCOUNT_TYPE_NAME' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '1%','bSortable': false, 'aTargets': 0 },
        { 'width': '84%', 'aTargets': 1 },
        { 'width': '15%','bSortable': false, 'aTargets': 2 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_bank_account_type_generation.php',
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

function bankAccountTypeForm(){
    $('#bank-account-type-form').validate({
        rules: {
            bank_account_type_name: {
                required: true
            },
        },
        messages: {
            bank_account_type_name: {
                required: 'Please enter the bank account type name'
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
            const bank_account_type_id = $('#bank-account-type-id').text();
            const transaction = 'save bank account type';
        
            $.ajax({
                type: 'POST',
                url: 'controller/bank-account-type-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&bank_account_type_id=' + bank_account_type_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-data');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert Bank Account Type Success' : 'Update Bank Account Type Success';
                        const notificationDescription = response.insertRecord ? 'The bank account type has been inserted successfully.' : 'The bank account type has been updated successfully.';
                        
                        setNotification(notificationMessage, notificationDescription, 'success');
                        window.location = 'bank-account-type.php?id=' + response.bankAccountTypeID;
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
        case 'get bank account type details':
            const bank_account_type_id = $('#bank-account-type-id').text();
            
            $.ajax({
                url: 'controller/bank-account-type-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    bank_account_type_id : bank_account_type_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetForm('bank-account-type-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#bank_account_type_id').val(bank_account_type_id);
                        $('#bank_account_type_name').val(response.bankAccountTypeName);

                        $('#bank_account_type_name_label').text(response.bankAccountTypeName);
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Bank Account Type Details Error', response.message, 'danger');
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