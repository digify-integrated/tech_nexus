(function($) {
    'use strict';

    $(function() {
        if($('#user-account-table').length){
            userAccountTable('#user-account-table');
        }

        if($('#user-account-form').length){
            userAccountForm();
        }

        if($('#user-account-id').length){
            displayDetails('get user account details');
        }

        $(document).on('click','.delete-user-account',function() {
            const user_account_id = $(this).data('user-account-id');
            const transaction = 'delete user account';
    
            Swal.fire({
                title: 'Confirm User Account Deletion',
                text: 'Are you sure you want to delete this user account?',
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
                        url: 'controller/user-account-controller.php',
                        dataType: 'json',
                        data: {
                            user_account_id : user_account_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete User Account Success', 'The user account has been deleted successfully.', 'success');
                                reloadDatatable('#user-account-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Delete User Account Error', 'The user account does not exist.', 'danger');
                                    reloadDatatable('#user-account-table');
                                }
                                else {
                                    showNotification('Delete User Account Error', response.message, 'danger');
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

        $(document).on('click','#delete-user-account',function() {
            let user_account_id = [];
            const transaction = 'delete multiple user account';

            $('.datatable-checkbox-children[data-delete="1"]').each((index, element) => {
                if ($(element).is(':checked')) {
                    user_account_id.push(element.value);
                }
            });
    
            if(user_account_id.length > 0){
                Swal.fire({
                    title: 'Confirm Multiple User Accounts Deletion',
                    text: 'Are you sure you want to delete these user accounts?',
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
                            url: 'controller/user-account-controller.php',
                            dataType: 'json',
                            data: {
                                user_account_id: user_account_id,
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Delete User Account Success', 'The selected user accounts have been deleted successfully.', 'success');
                                        reloadDatatable('#user-account-table');
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else {
                                        showNotification('Delete User Account Error', response.message, 'danger');
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
                showNotification('Deletion Multiple User Account Error', 'Please select the user accounts you wish to delete.', 'danger');
            }
        });

        $(document).on('click','#delete-user-account-details',function() {
            const user_account_id = $(this).data('user-account-id');
            const transaction = 'delete user account';
    
            Swal.fire({
                title: 'Confirm User Account Deletion',
                text: 'Are you sure you want to delete this user account?',
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
                        url: 'controller/user-account-controller.php',
                        dataType: 'json',
                        data: {
                            user_account_id : user_account_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Deleted User Account Success', 'The user account has been deleted successfully.', 'success');
                                window.location = 'user-account.php';
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
                                    showNotification('Delete User Account Error', response.message, 'danger');
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
            discardCreate('user-account.php');
        });

        $(document).on('click','#edit-form',function() {
            displayDetails('get user account details');

            $('.form-details').addClass('d-none');
            $('.form-edit').removeClass('d-none');
        });

        $(document).on('click','#duplicate-user-account',function() {
            const user_account_id = $(this).data('user-account-id');
            const transaction = 'duplicate user account';
    
            Swal.fire({
                title: 'Confirm User Account Duplication',
                text: 'Are you sure you want to duplicate this user account?',
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
                        url: 'controller/user-account-controller.php',
                        dataType: 'json',
                        data: {
                            user_account_id : user_account_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Duplicate User Account Success', 'The user account has been duplicated successfully.', 'success');
                                window.location = 'user-account.php?id=' + response.userAccountID;
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Duplicate User Account Error', 'The user account does not exist.', 'danger');
                                    reloadDatatable('#user-account-table');
                                }
                                else {
                                    showNotification('Duplicate User Account Error', response.message, 'danger');
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

        $(document).on('click','#create-user-account',function() {
            resetModalForm("user-account-form");

            $('#user-account-modal').modal('show');
        });

        $(document).on('click','.update-user-account',function() {
            const user_account_id = $(this).data('user-account-id');
    
            sessionStorage.setItem('user_account_id', user_account_id);
            
            displayDetails('get user account details');
    
            $('#user-account-modal').modal('show');
        });
    });
})(jQuery);

function userAccountTable(datatable_name, buttons = false, show_all = false){
    const type = 'user account table';
    var filter_status = $('#filter_status').val();
    var filter_locked = $('#filter_locked').val();
    var settings;

    const column = [ 
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'FILE_AS' },
        { 'data' : 'IS_ACTIVE' },
        { 'data' : 'IS_LOCKED' },
        { 'data' : 'LAST_CONNECTION_DATE' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '1%','bSortable': false, 'aTargets': 0 },
        { 'width': '30%', 'aTargets': 1 },
        { 'width': '15%', 'aTargets': 2 },
        { 'width': '15%', 'aTargets': 3 },
        { 'width': '15%', 'aTargets': 4 },
        { 'width': '10%','bSortable': false, 'aTargets': 5 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_user_account_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {'type' : type, 'filter_status' : filter_status, 'filter_locked' : filter_locked},
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

function userAccountForm(){
    $('#user-account-form').validate({
        rules: {
            role_name: {
                required: true
            },
            role_description: {
                required: true
            }
        },
        messages: {
            role_name: {
                required: 'Please enter the role name'
            },
            role_description: {
                required: 'Please enter the role description'
            }
        },
        errorPlacement: function (error, element) {
            if (element.hasClass('select2')) {
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
            const user_account_id = $('#user-account-id').text();
            const transaction = 'save user account';
        
            $.ajax({
                type: 'POST',
                url: 'controller/user-account-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&user_account_id=' + user_account_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-data');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert User Account Success' : 'Update User Account Success';
                        const notificationDescription = response.insertRecord ? 'The user account has been inserted successfully.' : 'The user account has been updated successfully.';
                        
                        setNotification(notificationMessage, notificationDescription, 'success');
                        window.location = 'user-account.php?id=' + response.userAccountID;
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
                    enableFormSubmitButton('submit-data', 'Submit');
                    $('#user-account-modal').modal('hide');
                    reloadDatatable('#user-account-table');
                    resetModalForm('user-account-form');
                }
            });
        
            return false;
        }
    });
}

function displayDetails(transaction){
    switch (transaction) {
        case 'get user account details':
            const user_account_id = $('#user-account-id').text();
            
            $.ajax({
                url: 'controller/user-account-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    user_account_id : user_account_id, 
                    transaction : transaction
                },
                success: function(response) {
                    if (response.success) {
                        $('#user_account_id').val(user_account_id);
                        $('#role_name').val(response.roleName);
                        $('#role_description').val(response.roleDescription);

                        $('#role_name_label').text(response.roleName);
                        $('#role_description_label').text(response.roleDescription);
                        $('#assignable_label').text(response.assignableLabel);
                        
                        checkOptionExist('#assignable', response.assignable, '');
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get User Account Details Error', response.message, 'danger');
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