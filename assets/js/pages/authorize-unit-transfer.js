(function($) {
    'use strict';

    $(function() {
        if($('#authorize-unit-transfer-table').length){
            authorizeUnitTransferTable('#authorize-unit-transfer-table');
        }

        if($('#authorize-unit-transfer-form').length){
            authorizeUnitTransferForm();
        }

        if($('#authorize-unit-transfer-id').length){
            displayDetails('get authorize unit transfer details');
        }

        if($('#authorize-unit-transfer-id').length){
            disableFormAndSelect2('authorize-unit-transfer-form');
        }

        $(document).on('click','.delete-authorize-unit-transfer',function() {
            const authorize_unit_transfer_id = $(this).data('authorize-unit-transfer-id');
            const transaction = 'delete authorize unit transfer';
    
            Swal.fire({
                title: 'Confirm Authorize Unit Transfer Deletion',
                text: 'Are you sure you want to delete this authorize unit transfer?',
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
                        url: 'controller/authorize-unit-transfer-controller.php',
                        dataType: 'json',
                        data: {
                            authorize_unit_transfer_id : authorize_unit_transfer_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete Authorize Unit Transfer Success', 'The authorize unit transfer has been deleted successfully.', 'success');
                                reloadDatatable('#authorize-unit-transfer-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Delete Authorize Unit Transfer Error', 'The authorize unit transfer does not exist.', 'danger');
                                    reloadDatatable('#authorize-unit-transfer-table');
                                }
                                else {
                                    showNotification('Delete Authorize Unit Transfer Error', response.message, 'danger');
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

        $(document).on('click','#delete-authorize-unit-transfer',function() {
            let authorize_unit_transfer_id = [];
            const transaction = 'delete multiple authorize unit transfer';

            $('.datatable-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    authorize_unit_transfer_id.push(element.value);
                }
            });
    
            if(authorize_unit_transfer_id.length > 0){
                Swal.fire({
                    title: 'Confirm Multiple Authorize Unit Transfers Deletion',
                    text: 'Are you sure you want to delete these authorize unit transfers?',
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
                            url: 'controller/authorize-unit-transfer-controller.php',
                            dataType: 'json',
                            data: {
                                authorize_unit_transfer_id: authorize_unit_transfer_id,
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Delete Authorize Unit Transfer Success', 'The selected authorize unit transfers have been deleted successfully.', 'success');
                                    reloadDatatable('#authorize-unit-transfer-table');
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else {
                                        showNotification('Delete Authorize Unit Transfer Error', response.message, 'danger');
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
                showNotification('Deletion Multiple Authorize Unit Transfer Error', 'Please select the authorize unit transfers you wish to delete.', 'danger');
            }
        });

        $(document).on('click','#delete-authorize-unit-transfer-details',function() {
            const authorize_unit_transfer_id = $('#authorize-unit-transfer-id').text();
            const transaction = 'delete authorize unit transfer';
    
            Swal.fire({
                title: 'Confirm Authorize Unit Transfer Deletion',
                text: 'Are you sure you want to delete this authorize unit transfer?',
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
                        url: 'controller/authorize-unit-transfer-controller.php',
                        dataType: 'json',
                        data: {
                            authorize_unit_transfer_id : authorize_unit_transfer_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Deleted Authorize Unit Transfer Success', 'The authorize unit transfer has been deleted successfully.', 'success');
                                window.location = 'authorize-unit-transfer.php';
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
                                    showNotification('Delete Authorize Unit Transfer Error', response.message, 'danger');
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
            discardCreate('authorize-unit-transfer.php');
        });

        $(document).on('click','#edit-form',function() {
            displayDetails('get authorize unit transfer details');

            enableForm();
        });
    });
})(jQuery);

function authorizeUnitTransferTable(datatable_name, buttons = false, show_all = false){
    const type = 'authorize unit transfer table';
    var settings;

    const column = [ 
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'WAREHOUSE' },
        { 'data' : 'USER' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '1%','bSortable': false, 'aTargets': 0 },
        { 'width': '42%', 'aTargets': 1 },
        { 'width': '42%', 'aTargets': 2 },
        { 'width': '15%','bSortable': false, 'aTargets': 3 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_authorize_unit_transfer_generation.php',
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

function authorizeUnitTransferForm(){
    $('#authorize-unit-transfer-form').validate({
        rules: {
            warehouse_id: {
                required: true
            },
            user_id1: {
                required: true
            },
        },
        messages: {
            warehouse_id: {
                required: 'Please choose the warehouse'
            },
            user_id1: {
                required: 'Please choose the user'
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
            const authorize_unit_transfer_id = $('#authorize-unit-transfer-id').text();
            const transaction = 'save authorize unit transfer';
        
            $.ajax({
                type: 'POST',
                url: 'controller/authorize-unit-transfer-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&authorize_unit_transfer_id=' + authorize_unit_transfer_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-data');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert Authorize Unit Transfer Success' : 'Update Authorize Unit Transfer Success';
                        const notificationDescription = response.insertRecord ? 'The authorize unit transfer has been inserted successfully.' : 'The authorize unit transfer has been updated successfully.';
                        
                        setNotification(notificationMessage, notificationDescription, 'success');
                        window.location = 'authorize-unit-transfer.php?id=' + response.authorizeUnitTransferID;
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
        case 'get authorize unit transfer details':
            const authorize_unit_transfer_id = $('#authorize-unit-transfer-id').text();
            
            $.ajax({
                url: 'controller/authorize-unit-transfer-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    authorize_unit_transfer_id : authorize_unit_transfer_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetForm('authorize-unit-transfer-form');
                },
                success: function(response) {
                    if (response.success) {
                        checkOptionExist('#warehouse_id', response.warehouseID, '');
                        checkOptionExist('#user_id1', response.userID, '');
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Authorize Unit Transfer Details Error', response.message, 'danger');
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

function disableFormAndSelect2(formId) {
    // Disable all form elements
    var form = document.getElementById(formId);
    var elements = form.elements;
    for (var i = 0; i < elements.length; i++) {
        elements[i].disabled = true;
    }

    // Disable Select2 dropdowns
    var select2Dropdowns = form.getElementsByClassName('select2');
    for (var j = 0; j < select2Dropdowns.length; j++) {
        var select2Instance = $(select2Dropdowns[j]);
        select2Instance.select2('destroy');
        select2Instance.prop('disabled', true);
    }
}