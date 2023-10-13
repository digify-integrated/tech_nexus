(function($) {
    'use strict';

    $(function() {
        if($('#currency-table').length){
            currencyTable('#currency-table');
        }

        if($('#currency-form').length){
            currencyForm();
        }

        if($('#currency-id').length){
            displayDetails('get currency details');
        }

        $(document).on('click','.delete-currency',function() {
            const currency_id = $(this).data('currency-id');
            const transaction = 'delete currency';
    
            Swal.fire({
                title: 'Confirm Currency Deletion',
                text: 'Are you sure you want to delete this currency?',
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
                        url: 'controller/currency-controller.php',
                        dataType: 'json',
                        data: {
                            currency_id : currency_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete Currency Success', 'The currency has been deleted successfully.', 'success');
                                reloadDatatable('#currency-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Delete Currency Error', 'The currency does not exist.', 'danger');
                                    reloadDatatable('#currency-table');
                                }
                                else {
                                    showNotification('Delete Currency Error', response.message, 'danger');
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

        $(document).on('click','#delete-currency',function() {
            let currency_id = [];
            const transaction = 'delete multiple currency';

            $('.datatable-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    currency_id.push(element.value);
                }
            });
    
            if(currency_id.length > 0){
                Swal.fire({
                    title: 'Confirm Multiple Currencies Deletion',
                    text: 'Are you sure you want to delete these currencies?',
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
                            url: 'controller/currency-controller.php',
                            dataType: 'json',
                            data: {
                                currency_id: currency_id,
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Delete Currency Success', 'The selected currencies have been deleted successfully.', 'success');
                                        reloadDatatable('#currency-table');
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else {
                                        showNotification('Delete Currency Error', response.message, 'danger');
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
                showNotification('Deletion Multiple Currency Error', 'Please select the currencies you wish to delete.', 'danger');
            }
        });

        $(document).on('click','#delete-currency-details',function() {
            const currency_id = $('#currency-id').text();
            const transaction = 'delete currency';
    
            Swal.fire({
                title: 'Confirm Currency Deletion',
                text: 'Are you sure you want to delete this currency?',
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
                        url: 'controller/currency-controller.php',
                        dataType: 'json',
                        data: {
                            currency_id : currency_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Deleted Currency Success', 'The currency has been deleted successfully.', 'success');
                                window.location = 'currency.php';
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
                                    showNotification('Delete Currency Error', response.message, 'danger');
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
            discardCreate('currency.php');
        });

        $(document).on('click','#edit-form',function() {
            displayDetails('get currency details');

            enableForm();
        });

        $(document).on('click','#duplicate-currency',function() {
            const currency_id = $('#currency-id').text();
            const transaction = 'duplicate currency';
    
            Swal.fire({
                title: 'Confirm Currency Duplication',
                text: 'Are you sure you want to duplicate this currency?',
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
                        url: 'controller/currency-controller.php',
                        dataType: 'json',
                        data: {
                            currency_id : currency_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Duplicate Currency Success', 'The currency has been duplicated successfully.', 'success');
                                window.location = 'currency.php?id=' + response.currencyID;
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Duplicate Currency Error', 'The currency does not exist.', 'danger');
                                    reloadDatatable('#currency-table');
                                }
                                else {
                                    showNotification('Duplicate Currency Error', response.message, 'danger');
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

function currencyTable(datatable_name, buttons = false, show_all = false){
    const type = 'currency table';
    var settings;

    const column = [ 
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'CURRENCY_NAME' },
        { 'data' : 'SHORTHAND' },
        { 'data' : 'SYMBOL' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '1%','bSortable': false, 'aTargets': 0 },
        { 'width': '64%', 'aTargets': 1 },
        { 'width': '10%', 'aTargets': 2 },
        { 'width': '10%', 'aTargets': 3 },
        { 'width': '15%','bSortable': false, 'aTargets': 4 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_currency_generation.php',
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

function currencyForm(){
    $('#currency-form').validate({
        rules: {
            currency_name: {
                required: true
            },
            symbol: {
                required: true
            },
            shorthand: {
                required: true
            }
        },
        messages: {
            currency_name: {
                required: 'Please enter the currency name'
            },
            symbol: {
                required: 'Please enter the symbol'
            },
            shorthand: {
                required: 'Please enter the shorthand'
            }
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
            const currency_id = $('#currency-id').text();
            const transaction = 'save currency';
        
            $.ajax({
                type: 'POST',
                url: 'controller/currency-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&currency_id=' + currency_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-data');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert Currency Success' : 'Update Currency Success';
                        const notificationDescription = response.insertRecord ? 'The currency has been inserted successfully.' : 'The currency has been updated successfully.';
                        
                        setNotification(notificationMessage, notificationDescription, 'success');
                        window.location = 'currency.php?id=' + response.currencyID;
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
        case 'get currency details':
            const currency_id = $('#currency-id').text();
            
            $.ajax({
                url: 'controller/currency-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    currency_id : currency_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetForm('currency-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#currency_id').val(currency_id);
                        $('#currency_name').val(response.currencyName);
                        $('#symbol').val(response.symbol);
                        $('#shorthand').val(response.shorthand);

                        $('#currency_name_label').text(response.currencyName);
                        $('#symbol_label').text(response.symbol);
                        $('#shorthand_label').text(response.shorthand);
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Currency Details Error', response.message, 'danger');
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