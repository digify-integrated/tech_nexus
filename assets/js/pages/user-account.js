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

            if($('#user-account-role-access-table').length){
                userAccountRoleTable('#user-account-role-access-table');
            }

            if($('#add-user-account-role-form').length){
                addUserAccountRoleForm();
            }

            $(document).on('click','#add-user-account-role',function() {    
                $('#add-user-account-role-modal').modal('show');
                addUserAccountRoleTable('#add-user-account-role-table');
            });

            $(document).on('click','#change-user-account-profile-picture',function() {    
                $('#change-user-account-profile-picture-modal').modal('show');
            });

            $(document).on('click','.delete-user-account-role',function() {
                const user_account_id = $(this).data('user-account-id');
                const role_id = $(this).data('role-id');
                const transaction = 'delete role user account';
        
                Swal.fire({
                    title: 'Confirm Role Deletion',
                    text: 'Are you sure you want to delete this role?',
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
                            url: 'controller/role-controller.php',
                            dataType: 'json',
                            data: {
                                user_account_id : user_account_id, 
                                role_id : role_id, 
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Delete Role Success', 'The role has been deleted successfully.', 'success');
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else if (response.notExist) {
                                        showNotification('Delete Role Error', 'The role does not exist.', 'danger');
                                    }
                                    else {
                                        showNotification('Delete Role Error', response.message, 'danger');
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
                                reloadDatatable('#user-account-role-access-table');
                            }
                        });
                        return false;
                    }
                });
            });

            $('#profile_picture').change(function() {
                var selectedFile = $(this)[0].files[0];

                if (selectedFile) {
                    const transaction = 'change user account profile picture';
                    const user_account_id = $('#user-account-id').text();

                    var formData = new FormData();
                    formData.append('user_account_id', user_account_id);
                    formData.append('transaction', transaction);
                    formData.append('profile_picture', selectedFile);
            
                    $.ajax({
                        type: 'POST',
                        url: 'controller/user-controller.php',
                        data: formData,
                        processData: false,
                        contentType: false,
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                showNotification('Profile Picture Change Success', 'The profile picture has been successfully updated.', 'success');
                                location.reload();
                            }
                            else{
                                if(response.isInactive){
                                    window.location = 'logout.php?logout';
                                }
                                else{
                                    showNotification('Profile Picture Change Error', response.message, 'danger');
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
                }
            });
        }

        $(document).on('click','#filter-datatable',function() {
            userAccountTable('#user-account-table');
        });

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
                        url: 'controller/user-controller.php',
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

            $('.datatable-checkbox-children').each((index, element) => {
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
                            url: 'controller/user-controller.php',
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
            const user_account_id = $('#user-account-id').text();
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
                        url: 'controller/user-controller.php',
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

        $(document).on('click','#activate-user-account',function() {
            let user_account_id = [];
            const transaction = 'activate multiple user account';

            $('.datatable-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    user_account_id.push(element.value);
                }
            });
    
            if(user_account_id.length > 0){
                Swal.fire({
                    title: 'Confirm Multiple User Accounts Activation',
                    text: 'Are you sure you want to activate these user accounts?',
                    icon: 'info',
                    showCancelButton: !0,
                    confirmButtonText: 'Activate',
                    cancelButtonText: 'Cancel',
                    confirmButtonClass: 'btn btn-success mt-2',
                    cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                    buttonsStyling: !1
                }).then(function(result) {
                    if (result.value) {
                        $.ajax({
                            type: 'POST',
                            url: 'controller/user-controller.php',
                            dataType: 'json',
                            data: {
                                user_account_id: user_account_id,
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Activate User Account Success', 'The selected user accounts have been activated successfully.', 'success');
                                    reloadDatatable('#user-account-table');
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else {
                                        showNotification('Activate User Account Error', response.message, 'danger');
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
                showNotification('Activation Multiple User Account Error', 'Please select the user accounts you wish to activate.', 'danger');
            }
        });

        $(document).on('click','#activate-user-account-details',function() {
            const user_account_id = $('#user-account-id').text();
            const transaction = 'activate user account';
    
            Swal.fire({
                title: 'Confirm User Account Activation',
                text: 'Are you sure you want to activate this user account?',
                icon: 'info',
                showCancelButton: !0,
                confirmButtonText: 'Activate',
                cancelButtonText: 'Cancel',
                confirmButtonClass: 'btn btn-success mt-2',
                cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                buttonsStyling: !1
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        type: 'POST',
                        url: 'controller/user-controller.php',
                        dataType: 'json',
                        data: {
                            user_account_id : user_account_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Activate User Account Success', 'The user account has been activated successfully.', 'success');
                                location.reload();
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
                                    showNotification('Activate User Account Error', response.message, 'danger');
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

        $(document).on('click','#deactivate-user-account',function() {
            let user_account_id = [];
            const transaction = 'deactivate multiple user account';

            $('.datatable-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    user_account_id.push(element.value);
                }
            });
    
            if(user_account_id.length > 0){
                Swal.fire({
                    title: 'Confirm Multiple User Accounts Deactivation',
                    text: 'Are you sure you want to deactivate these user accounts?',
                    icon: 'warning',
                    showCancelButton: !0,
                    confirmButtonText: 'Deactivate',
                    cancelButtonText: 'Cancel',
                    confirmButtonClass: 'btn btn-warning mt-2',
                    cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                    buttonsStyling: !1
                }).then(function(result) {
                    if (result.value) {
                        $.ajax({
                            type: 'POST',
                            url: 'controller/user-controller.php',
                            dataType: 'json',
                            data: {
                                user_account_id: user_account_id,
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Deactivate User Account Success', 'The selected user accounts have been deactivated successfully.', 'success');
                                    reloadDatatable('#user-account-table');
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else {
                                        showNotification('Deactivate User Account Error', response.message, 'danger');
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
                showNotification('Deactivation Multiple User Account Error', 'Please select the user accounts you wish to deactivate.', 'danger');
            }
        });

        $(document).on('click','#deactivate-user-account-details',function() {
            const user_account_id = $('#user-account-id').text();
            const transaction = 'deactivate user account';
    
            Swal.fire({
                title: 'Confirm User Account Deactivation',
                text: 'Are you sure you want to deactivate this user account?',
                icon: 'warning',
                showCancelButton: !0,
                confirmButtonText: 'Deactivate',
                cancelButtonText: 'Cancel',
                confirmButtonClass: 'btn btn-warning mt-2',
                cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                buttonsStyling: !1
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        type: 'POST',
                        url: 'controller/user-controller.php',
                        dataType: 'json',
                        data: {
                            user_account_id : user_account_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Deactivate User Account Success', 'The user account has been deactivated successfully.', 'success');
                                location.reload();
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
                                    showNotification('Deactivate User Account Error', response.message, 'danger');
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

        $(document).on('click','#unlock-user-account',function() {
            let user_account_id = [];
            const transaction = 'unlock multiple user account';

            $('.datatable-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    user_account_id.push(element.value);
                }
            });
    
            if(user_account_id.length > 0){
                Swal.fire({
                    title: 'Confirm Multiple User Accounts Unlock',
                    text: 'Are you sure you want to unlock these user accounts?',
                    icon: 'info',
                    showCancelButton: !0,
                    confirmButtonText: 'Unlock',
                    cancelButtonText: 'Cancel',
                    confirmButtonClass: 'btn btn-success mt-2',
                    cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                    buttonsStyling: !1
                }).then(function(result) {
                    if (result.value) {
                        $.ajax({
                            type: 'POST',
                            url: 'controller/user-controller.php',
                            dataType: 'json',
                            data: {
                                user_account_id: user_account_id,
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Unlock User Account Success', 'The selected user accounts have been unlocked successfully.', 'success');
                                    reloadDatatable('#user-account-table');
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else {
                                        showNotification('Unlock User Account Error', response.message, 'danger');
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
                showNotification('Unlock Multiple User Account Error', 'Please select the user accounts you wish to unlock.', 'danger');
            }
        });

        $(document).on('click','#unlock-user-account-details',function() {
            const user_account_id = $('#user-account-id').text();
            const transaction = 'unlock user account';
    
            Swal.fire({
                title: 'Confirm User Account Unlock',
                text: 'Are you sure you want to unlock this user account?',
                icon: 'info',
                showCancelButton: !0,
                confirmButtonText: 'Unlock',
                cancelButtonText: 'Cancel',
                confirmButtonClass: 'btn btn-success mt-2',
                cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                buttonsStyling: !1
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        type: 'POST',
                        url: 'controller/user-controller.php',
                        dataType: 'json',
                        data: {
                            user_account_id : user_account_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Unlock User Account Success', 'The user account has been unlocked successfully.', 'success');
                                location.reload();
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
                                    showNotification('Unlock User Account Error', response.message, 'danger');
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

        $(document).on('click','#lock-user-account',function() {
            let user_account_id = [];
            const transaction = 'lock multiple user account';

            $('.datatable-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    user_account_id.push(element.value);
                }
            });
    
            if(user_account_id.length > 0){
                Swal.fire({
                    title: 'Confirm Multiple User Accounts Lock',
                    text: 'Are you sure you want to lock these user accounts?',
                    icon: 'warning',
                    showCancelButton: !0,
                    confirmButtonText: 'Lock',
                    cancelButtonText: 'Cancel',
                    confirmButtonClass: 'btn btn-warning mt-2',
                    cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                    buttonsStyling: !1
                }).then(function(result) {
                    if (result.value) {
                        $.ajax({
                            type: 'POST',
                            url: 'controller/user-controller.php',
                            dataType: 'json',
                            data: {
                                user_account_id: user_account_id,
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Lock User Account Success', 'The selected user accounts have been locked successfully.', 'success');
                                    reloadDatatable('#user-account-table');
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else {
                                        showNotification('Lock User Account Error', response.message, 'danger');
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
                showNotification('Lock Multiple User Account Error', 'Please select the user accounts you wish to lock.', 'danger');
            }
        });

        $(document).on('click','#lock-user-account-details',function() {
            const user_account_id = $('#user-account-id').text();
            const transaction = 'lock user account';
    
            Swal.fire({
                title: 'Confirm User Account Lock',
                text: 'Are you sure you want to lock this user account?',
                icon: 'warning',
                showCancelButton: !0,
                confirmButtonText: 'Lock',
                cancelButtonText: 'Cancel',
                confirmButtonClass: 'btn btn-warning mt-2',
                cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                buttonsStyling: !1
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        type: 'POST',
                        url: 'controller/user-controller.php',
                        dataType: 'json',
                        data: {
                            user_account_id : user_account_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Lock User Account Success', 'The user account has been locked successfully.', 'success');
                                location.reload();
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
                                    showNotification('Lock User Account Error', response.message, 'danger');
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

        $(document).on('click','#change-user-account-password',function() {
            $('#change-user-account-password-modal').modal('show');
            resetModalForm('change-user-account-password-form');
        });

        if($('#change-user-account-password-form').length){
            changePasswordForm();
        }

        $(document).on('click','#discard-create',function() {
            discardCreate('user-account.php');
        });

        $(document).on('click','#edit-form',function() {
            displayDetails('get user account details');

            enableForm();
        });

        $(document).on('click','#send-reset-password-instructions',function() {
            const user_account_id = $('#user-account-id').text();
            const transaction = 'send reset password instructions';
    
            Swal.fire({
                title: 'Confirm Reset Password Instructions Send',
                text: 'Are you sure you want to send reset password instructions to this user account?',
                icon: 'warning',
                showCancelButton: !0,
                confirmButtonText: 'Send',
                cancelButtonText: 'Cancel',
                confirmButtonClass: 'btn btn-info mt-2',
                cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                buttonsStyling: !1
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        type: 'POST',
                        url: 'controller/user-controller.php',
                        dataType: 'json',
                        data: {
                            user_account_id : user_account_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Reset Password Instructions Send Success', 'The reset password instructions has been sent successfully.', 'success');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Reset Password Instructions Send Error', 'The user account does not exist.', 'danger');;
                                }
                                else {
                                    showNotification('Reset Password Instructions Send Error', response.message, 'danger');
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
    var filter_password_expiry_date_start_date = $('#filter_password_expiry_date_start_date').val();
    var filter_password_expiry_date_end_date = $('#filter_password_expiry_date_end_date').val();
    var filter_last_connection_date_start_date = $('#filter_last_connection_date_start_date').val();
    var filter_last_connection_date_end_date = $('#filter_last_connection_date_end_date').val();
    var filter_last_password_reset_start_date = $('#filter_last_password_reset_start_date').val();
    var filter_last_password_reset_end_date = $('#filter_last_password_reset_end_date').val();
    var filter_last_failed_login_attempt_start_date = $('#filter_last_failed_login_attempt_start_date').val();
    var filter_last_failed_login_attempt_end_date = $('#filter_last_failed_login_attempt_end_date').val();
    var settings;

    const column = [ 
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'FILE_AS' },
        { 'data' : 'IS_ACTIVE' },
        { 'data' : 'IS_LOCKED' },
        { 'data' : 'PASSWORD_EXPIRY_DATE' },
        { 'data' : 'LAST_CONNECTION_DATE' },
        { 'data' : 'LAST_PASSWORD_RESET' },
        { 'data' : 'LAST_FAILED_LOGIN_ATTEMPT' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '1%','bSortable': false, 'aTargets': 0 },
        { 'width': '29%', 'aTargets': 1 },
        { 'width': '10%', 'aTargets': 2 },
        { 'width': '10%', 'aTargets': 3 },
        { 'width': '10%', 'aTargets': 4 },
        { 'width': '10%', 'aTargets': 5 },
        { 'width': '10%', 'aTargets': 6 },
        { 'width': '10%', 'aTargets': 7 },
        { 'width': '10%','bSortable': false, 'aTargets': 8 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_user_account_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {'type' : type, 'filter_status' : filter_status, 'filter_locked' : filter_locked, 'filter_password_expiry_date_start_date' : filter_password_expiry_date_start_date, 'filter_password_expiry_date_end_date' : filter_password_expiry_date_end_date, 'filter_last_connection_date_start_date' : filter_last_connection_date_start_date, 'filter_last_connection_date_end_date' : filter_last_connection_date_end_date, 'filter_last_password_reset_start_date' : filter_last_password_reset_start_date, 'filter_last_password_reset_end_date' : filter_last_password_reset_end_date, 'filter_last_failed_login_attempt_start_date' : filter_last_failed_login_attempt_start_date, 'filter_last_failed_login_attempt_end_date' : filter_last_failed_login_attempt_end_date},
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

function userAccountRoleTable(datatable_name, buttons = false, show_all = false){
    const user_account_id = $('#user-account-id').text();
    const type = 'user account role table';
    var settings;

    const column = [ 
        { 'data' : 'ROLE_NAME' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '90%', 'aTargets': 0 },
        { 'width': '10%', 'bSortable': false, 'aTargets': 1 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_user_account_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {'type' : type, 'user_account_id' : user_account_id},
            'dataSrc' : '',
            'error': function(xhr, status, error) {
                var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                if (xhr.responseText) {
                    fullErrorMessage += `, Response: ${xhr.responseText}`;
                }
                showErrorDialog(fullErrorMessage);
            }
        },
        'order': [[ 0, 'asc' ]],
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

function addUserAccountRoleTable(datatable_name, buttons = false, show_all = false){
    const user_account_id = $('#user-account-id').text();
    const type = 'add user account role table';
    var settings;

    const column = [ 
        { 'data' : 'ROLE_NAME' },
        { 'data' : 'ASSIGN' }
    ];

    const column_definition = [
        { 'width': '90%', 'aTargets': 0 },
        { 'width': '10%', 'bSortable': false, 'aTargets': 1 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[-1], ['All']];

    settings = {
        'ajax': { 
            'url' : 'view/_user_account_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {'type' : type, 'user_account_id' : user_account_id},
            'dataSrc' : '',
            'error': function(xhr, status, error) {
                var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                if (xhr.responseText) {
                    fullErrorMessage += `, Response: ${xhr.responseText}`;
                }
                showErrorDialog(fullErrorMessage);
            }
        },
        'order': [[ 0, 'asc' ]],
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
            file_as: {
                required: true
            },
            email: {
                required: true
            }
        },
        messages: {
            file_as: {
                required: 'Please enter the name'
            },
            email: {
                required: 'Please enter the email'
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
            const user_account_id = $('#user-account-id').text();
            const transaction = 'save user account';
        
            $.ajax({
                type: 'POST',
                url: 'controller/user-controller.php',
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
                    enableFormSubmitButton('submit-data', 'Save');
                }
            });
        
            return false;
        }
    });
}

function addUserAccountRoleForm(){
    $('#add-user-account-role-form').validate({
        submitHandler: function(form) {
            const user_account_id = $('#user-account-id').text();
            const transaction = 'add user account role';

            var role_id = [];

            $('.user-account-role').each(function(){
                if ($(this).is(':checked')){  
                    role_id.push(this.value);  
                }
            });

            $.ajax({
                type: 'POST',
                url: 'controller/role-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&user_account_id=' + user_account_id + '&role_id=' + role_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-add-role-user-account');
                },
                success: function(response) {
                    if (response.success) {
                        showNotification('Add Role Success', 'The role has been added successfully.', 'success');
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
                    var fullErrorMessage = 'XHR status: ' + status + ', Error: ' + error;
                    fullErrorMessage += ', Response: ' + xhr.responseText;
                    showErrorDialog(fullErrorMessage);
                },
                complete: function() {
                    enableFormSubmitButton('submit-add-user-account-role', 'Submit');
                    $('#add-user-account-role-modal').modal('hide');
                    reloadDatatable('#user-account-role-access-table');
                }
            });
            return false;
        }
    });
}

function changePasswordForm(){
    $('#change-user-account-password-form').validate({
        rules: {
            new_password: {
              required: true,
              password_strength: true
            },
            confirm_password: {
              required: true,
              equalTo: '#new_password'
            }
        },
        messages: {
            new_password: {
              required: 'Please enter your new password'
            },
            confirm_password: {
              required: 'Please re-enter your password for confirmation',
              equalTo: 'The passwords you entered do not match. Please make sure to enter the same password in both fields'
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
        if ($(element).hasClass('select2-hidden-accessible')) {
          $(element).next().find('.select2-selection__rendered').addClass('is-invalid');
        } 
        else {
          $(element).addClass('is-invalid');
        }
      },
      unhighlight: function(element) {
        if ($(element).hasClass('select2-hidden-accessible')) {
          $(element).next().find('.select2-selection__rendered').removeClass('is-invalid');
        }
        else {
          $(element).removeClass('is-invalid');
        }
      },
      submitHandler: function(form) {
        const transaction = 'change user account password';
        const user_account_id = $('#user-account-id').text();
  
        $.ajax({
            type: 'POST',
            url: 'controller/user-controller.php',
            data: $(form).serialize() + '&transaction=' + transaction + '&user_account_id=' + user_account_id,
            dataType: 'json',
            beforeSend: function() {
                disableFormSubmitButton('submit-change-user-account-password-form');
            },
            success: function(response) {
                if (response.success) {
                    showNotification('Password Change Success', 'The password has been successfully updated.', 'success');
                    $('#change-user-account-password-modal').modal('hide');
                    resetModalForm('change-user-account-password-form');
                }
                else{
                    if(response.isInactive){
                        window.location = 'logout.php?logout';
                    }
                    else{
                        showNotification('Password Change Error', response.message, 'danger');
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
                enableFormSubmitButton('submit-change-user-account-password-form', 'Update Password');
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
                url: 'controller/user-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    user_account_id : user_account_id, 
                    transaction : transaction
                },
                success: function(response) {
                    if (response.success) {
                        $('#user_account_id').val(user_account_id);
                        $('#file_as').val(response.fileAs);
                        $('#email').val(response.email);

                        $('#file_as_label').text(response.fileAs);
                        $('#email_label').text(response.email);
                        $('#last_failed_login_attempt_label').text(response.lastFailedLoginAttempt);
                        $('#last_connection_date_label').text(response.lastConnectionDate);
                        $('#password_expiry_date_label').text(response.passwordExpiryDate);
                        $('#account_lock_duration_label').text(response.accountLockDuration);
                        $('#last_password_reset_label').text(response.lastPasswordReset);
                        document.getElementById('user_image').src = response.profilePicture;

                        document.getElementById('status_label').innerHTML = response.isActive;
                        document.getElementById('locked_label').innerHTML = response.isLocked;
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