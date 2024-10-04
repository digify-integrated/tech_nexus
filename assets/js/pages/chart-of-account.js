(function($) {
    'use strict';

    $(function() {
        if($('#chart-of-account-table').length){
            chartOfAccountTable('#chart-of-account-table');
        }

        if($('#chart-of-account-form').length){
            chartOfAccountForm();
        }

        if($('#chart-of-account-id').length){
            displayDetails('get chart of account details');
        }

        $(document).on('click','.delete-chart-of-account',function() {
            const chart_of_account_id = $(this).data('chart-of-account-id');
            const transaction = 'delete chart of account';
    
            Swal.fire({
                title: 'Confirm Chart of Account Deletion',
                text: 'Are you sure you want to delete this chart of account?',
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
                        url: 'controller/chart-of-account-controller.php',
                        dataType: 'json',
                        data: {
                            chart_of_account_id : chart_of_account_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete Chart of Account Success', 'The chart of account has been deleted successfully.', 'success');
                                reloadDatatable('#chart-of-account-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Delete Chart of Account Error', 'The chart of account does not exist.', 'danger');
                                    reloadDatatable('#chart-of-account-table');
                                }
                                else {
                                    showNotification('Delete Chart of Account Error', response.message, 'danger');
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

        $(document).on('click','#delete-chart-of-account',function() {
            let chart_of_account_id = [];
            const transaction = 'delete multiple chart of account';

            $('.datatable-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    chart_of_account_id.push(element.value);
                }
            });
    
            if(chart_of_account_id.length > 0){
                Swal.fire({
                    title: 'Confirm Multiple Chart of Accounts Deletion',
                    text: 'Are you sure you want to delete these chart of accounts?',
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
                            url: 'controller/chart-of-account-controller.php',
                            dataType: 'json',
                            data: {
                                chart_of_account_id: chart_of_account_id,
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Delete Chart of Account Success', 'The selected chart of accounts have been deleted successfully.', 'success');
                                    reloadDatatable('#chart-of-account-table');
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else {
                                        showNotification('Delete Chart of Account Error', response.message, 'danger');
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
                showNotification('Deletion Multiple Chart of Account Error', 'Please select the chart of accounts you wish to delete.', 'danger');
            }
        });

        $(document).on('click','#delete-chart-of-account-details',function() {
            const chart_of_account_id = $('#chart-of-account-id').text();
            const transaction = 'delete chart of account';
    
            Swal.fire({
                title: 'Confirm Chart of Account Deletion',
                text: 'Are you sure you want to delete this chart of account?',
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
                        url: 'controller/chart-of-account-controller.php',
                        dataType: 'json',
                        data: {
                            chart_of_account_id : chart_of_account_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Deleted Chart of Account Success', 'The chart of account has been deleted successfully.', 'success');
                                window.location = 'chart-of-account.php';
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
                                    showNotification('Delete Chart of Account Error', response.message, 'danger');
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
            discardCreate('chart-of-account.php');
        });

        $(document).on('click','#edit-form',function() {
            displayDetails('get chart of account details');

            enableForm();
        });

        $(document).on('click','#duplicate-chart-of-account',function() {
            const chart_of_account_id = $('#chart-of-account-id').text();
            const transaction = 'duplicate chart of account';
    
            Swal.fire({
                title: 'Confirm Chart of Account Duplication',
                text: 'Are you sure you want to duplicate this chart of account?',
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
                        url: 'controller/chart-of-account-controller.php',
                        dataType: 'json',
                        data: {
                            chart_of_account_id : chart_of_account_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Duplicate Chart of Account Success', 'The chart of account has been duplicated successfully.', 'success');
                                window.location = 'chart-of-account.php?id=' + response.chartOfAccountID;
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Duplicate Chart of Account Error', 'The chart of account does not exist.', 'danger');
                                    reloadDatatable('#chart-of-account-table');
                                }
                                else {
                                    showNotification('Duplicate Chart of Account Error', response.message, 'danger');
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

function chartOfAccountTable(datatable_name, buttons = false, show_all = false){
    const type = 'chart of account table';
    var settings;

    const column = [ 
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'CODE' },
        { 'data' : 'NAME' },
        { 'data' : 'ACCOUNT_TYPE' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '1%','bSortable': false, 'aTargets': 0 },
        { 'width': 'auto', 'aTargets': 1 },
        { 'width': 'auto', 'aTargets': 2 },
        { 'width': 'auto', 'aTargets': 3 },
        { 'width': '15%','bSortable': false, 'aTargets': 4 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_chart_of_account_generation.php',
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

function chartOfAccountForm(){
    $('#chart-of-account-form').validate({
        rules: {
            code: {
                required: true
            },
            code_name: {
                required: true
            },
            account_type: {
                required: true
            },
        },
        messages: {
            code: {
                required: 'Please enter the code'
            },
            code_name: {
                required: 'Please enter the name'
            },
            account_type: {
                required: 'Please choose the account type'
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
            const chart_of_account_id = $('#chart-of-account-id').text();
            const transaction = 'save chart of account';
        
            $.ajax({
                type: 'POST',
                url: 'controller/chart-of-account-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&chart_of_account_id=' + chart_of_account_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-data');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert Chart of Account Success' : 'Update Chart of Account Success';
                        const notificationDescription = response.insertRecord ? 'The chart of account has been inserted successfully.' : 'The chart of account has been updated successfully.';
                        
                        setNotification(notificationMessage, notificationDescription, 'success');
                        window.location = 'chart-of-account.php?id=' + response.chartOfAccountID;
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
        case 'get chart of account details':
            const chart_of_account_id = $('#chart-of-account-id').text();
            
            $.ajax({
                url: 'controller/chart-of-account-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    chart_of_account_id : chart_of_account_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetForm('chart-of-account-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#chart_of_account_id').val(chart_of_account_id);
                        $('#code').val(response.code);
                        $('#code_name').val(response.name);

                        checkOptionExist('#account_type', response.accountType, '');
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Chart of Account Details Error', response.message, 'danger');
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