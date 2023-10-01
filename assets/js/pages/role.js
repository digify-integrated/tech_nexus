(function($) {
    'use strict';

    $(function() {
        if($('#role-table').length){
            roleTable('#role-table');
        }

        if($('#role-form').length){
            roleForm();
        }

        if($('#role-id').length){
            displayDetails('get role details');

            if($('#update-menu-item-role-access-table').length){
                updateMenuItemRoleAccessTable('#update-menu-item-role-access-table');
            }

            $(document).on('click','.update-menu-item-role-access',function() {
                const menu_item_id = $(this).data('menu-item-id');
                const role_id = $(this).data('role-id');
                const access_type = $(this).data('access-type');
                const transaction = 'save menu item role access';
                var access;

                if ($(this).is(':checked')){  
                    access = '1';
                }
                else{
                    access = '0';
                }
                
                $.ajax({
                    type: 'POST',
                    url: 'controller/role-controller.php',
                    dataType: 'json',
                    data: {
                        menu_item_id : menu_item_id, 
                        role_id : role_id, 
                        access_type : access_type,
                        access : access,
                        transaction : transaction
                    },
                    success: function (response) {
                        if (!response.success) {
                            if (response.isInactive) {
                                setNotification('User Inactive', response.message, 'danger');
                                window.location = 'logout.php?logout';
                            }
                            else if (response.notExist) {
                                showNotification('Update Role Access Error', 'The role access does not exist.', 'danger');
                            }
                            else {
                                showNotification('Update Role Access Error', response.message, 'danger');
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
            });
    
            $(document).on('click','.delete-menu-item-role-access',function() {
                const menu_item_id = $(this).data('menu-item-id');
                const role_id = $(this).data('role-id');
                const transaction = 'delete menu item role access';
        
                Swal.fire({
                    title: 'Confirm Menu Item Access Deletion',
                    text: 'Are you sure you want to delete this menu item access?',
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
                                menu_item_id : menu_item_id, 
                                role_id : role_id, 
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Delete Menu Item Access Success', 'The menu item access has been deleted successfully.', 'success');
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else if (response.notExist) {
                                        showNotification('Delete Menu Item Access Error', 'The role access does not exist.', 'danger');
                                    }
                                    else {
                                        showNotification('Delete Menu Item Access Error', response.message, 'danger');
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
                                reloadDatatable('#update-menu-item-role-access-table');
                            }
                        });
                        return false;
                    }
                });
            });

            if($('#role-user-account-access-table').length){
                roleUserAccountTable('#role-user-account-access-table');
            }

            if($('#add-role-user-account-form').length){
                addRoleUserAccountForm();
            }

            $(document).on('click','#add-role-user-account',function() {    
                $('#add-role-user-account-modal').modal('show');
                addRoleUserAccountTable('#add-role-user-account-table');
            });

            $(document).on('click','.delete-role-user-account',function() {
                const user_account_id = $(this).data('user-account-id');
                const role_id = $(this).data('role-id');
                const transaction = 'delete role user account';
        
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
                            url: 'controller/role-controller.php',
                            dataType: 'json',
                            data: {
                                user_account_id : user_account_id, 
                                role_id : role_id, 
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Delete User Account Success', 'The user account has been deleted successfully.', 'success');
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else if (response.notExist) {
                                        showNotification('Delete User Account Error', 'The user account does not exist.', 'danger');
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
                                reloadDatatable('#role-user-account-access-table');
                            }
                        });
                        return false;
                    }
                });
            });

            if($('#update-system-action-role-access-table').length){
                updateSystemActionRoleAccessTable('#update-system-action-role-access-table');
            }

            $(document).on('click','.update-system-action-role-access',function() {
                const system_action_id = $(this).data('system-action-id');
                const role_id = $(this).data('role-id');
                const transaction = 'save system action role access';
                var access;

                if ($(this).is(':checked')){  
                    access = '1';
                }
                else{
                    access = '0';
                }
                
                $.ajax({
                    type: 'POST',
                    url: 'controller/role-controller.php',
                    dataType: 'json',
                    data: {
                        system_action_id : system_action_id, 
                        role_id : role_id, 
                        access : access,
                        transaction : transaction
                    },
                    success: function (response) {
                        if (!response.success) {
                            if (response.isInactive) {
                                setNotification('User Inactive', response.message, 'danger');
                                window.location = 'logout.php?logout';
                            }
                            else if (response.notExist) {
                                showNotification('Update Role Access Error', 'The role access does not exist.', 'danger');
                            }
                            else {
                                showNotification('Update Role Access Error', response.message, 'danger');
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
            });
    
            $(document).on('click','.delete-system-action-role-access',function() {
                const system_action_id = $(this).data('system-action-id');
                const role_id = $(this).data('role-id');
                const transaction = 'delete system action role access';
        
                Swal.fire({
                    title: 'Confirm System Action Access Deletion',
                    text: 'Are you sure you want to delete this system action access?',
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
                                system_action_id : system_action_id, 
                                role_id : role_id, 
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Delete System Action Access Success', 'The system action access has been deleted successfully.', 'success');
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else if (response.notExist) {
                                        showNotification('Delete System Action Access Error', 'The role access does not exist.', 'danger');
                                    }
                                    else {
                                        showNotification('Delete System Action Access Error', response.message, 'danger');
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
                                reloadDatatable('#update-system-action-role-access-table');
                            }
                        });
                        return false;
                    }
                });
            });
        }

        $(document).on('click','.delete-role',function() {
            const role_id = $(this).data('role-id');
            const transaction = 'delete role';
    
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
                            role_id : role_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete Role Success', 'The role has been deleted successfully.', 'success');
                                reloadDatatable('#role-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Delete Role Error', 'The role does not exist.', 'danger');
                                    reloadDatatable('#role-table');
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
                        }
                    });
                    return false;
                }
            });
        });

        $(document).on('click','#delete-role',function() {
            let role_id = [];
            const transaction = 'delete multiple role';

            $('.datatable-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    role_id.push(element.value);
                }
            });
    
            if(role_id.length > 0){
                Swal.fire({
                    title: 'Confirm Multiple Roles Deletion',
                    text: 'Are you sure you want to delete these roles?',
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
                                role_id: role_id,
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Delete Role Success', 'The selected roles have been deleted successfully.', 'success');
                                        reloadDatatable('#role-table');
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
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
                                toggleHideActionDropdown();
                            }
                        });
                        
                        return false;
                    }
                });
            }
            else{
                showNotification('Deletion Multiple Role Error', 'Please select the roles you wish to delete.', 'danger');
            }
        });

        $(document).on('click','#delete-role-details',function() {
            const role_id = $('#role-id').text();
            const transaction = 'delete role';
    
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
                            role_id : role_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Deleted Role Success', 'The role has been deleted successfully.', 'success');
                                window.location = 'role.php';
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
                        }
                    });
                    return false;
                }
            });
        });

        $(document).on('click','#edit-form',function() {
            displayDetails('get role details');

            enableForm();
        });

        $(document).on('click','.update-role',function() {
            const role_id = $(this).data('role-id');
    
            sessionStorage.setItem('role_id', role_id);
            
            displayDetails('get role details');
    
            $('#role-modal').modal('show');
        });
    });
})(jQuery);

function roleTable(datatable_name, buttons = false, show_all = false){
    const type = 'role table';
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
            'url' : 'view/_role_generation.php',
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

function updateMenuItemRoleAccessTable(datatable_name, buttons = false, show_all = false){
    const role_id = $('#role-id').text();
    const type = 'update role menu item access table';
    var settings;

    const column = [ 
        { 'data' : 'MENU_ITEM_NAME' },
        { 'data' : 'READ_ACCESS' },
        { 'data' : 'WRITE_ACCESS' },
        { 'data' : 'CREATE_ACCESS' },
        { 'data' : 'DELETE_ACCESS' },
        { 'data' : 'DUPLICATE_ACCESS' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '40%', 'aTargets': 0 },
        { 'width': '10%', 'bSortable': false, 'aTargets': 1 },
        { 'width': '10%', 'bSortable': false, 'aTargets': 2 },
        { 'width': '10%', 'bSortable': false, 'aTargets': 3 },
        { 'width': '10%', 'bSortable': false, 'aTargets': 4 },
        { 'width': '10%', 'bSortable': false, 'aTargets': 5 },
        { 'width': '10%', 'bSortable': false, 'aTargets': 6 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_role_configuration_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {'type' : type, 'role_id' : role_id},
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

function updateSystemActionRoleAccessTable(datatable_name, buttons = false, show_all = false){
    const role_id = $('#role-id').text();
    const type = 'update role system action access table';
    var settings;

    const column = [ 
        { 'data' : 'SYSTEM_ACTION_NAME' },
        { 'data' : 'ROLE_ACCESS' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '70%', 'aTargets': 0 },
        { 'width': '20%', 'bSortable': false, 'aTargets': 1 },
        { 'width': '10%', 'bSortable': false, 'aTargets': 2 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_role_configuration_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {'type' : type, 'role_id' : role_id},
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

function roleUserAccountTable(datatable_name, buttons = false, show_all = false){
    const role_id = $('#role-id').text();
    const type = 'role user account table';
    var settings;

    const column = [ 
        { 'data' : 'FILE_AS' },
        { 'data' : 'EMAIL' },
        { 'data' : 'LAST_CONNECTION_DATE' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '35%', 'aTargets': 0 },
        { 'width': '35%', 'aTargets': 1 },
        { 'width': '20%', 'aTargets': 2 },
        { 'width': '10%', 'bSortable': false, 'aTargets': 3 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_user_account_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {'type' : type, 'role_id' : role_id},
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

function addRoleUserAccountTable(datatable_name, buttons = false, show_all = false){
    const role_id = $('#role-id').text();
    const type = 'add role user account table';
    var settings;

    const column = [ 
        { 'data' : 'FILE_AS' },
        { 'data' : 'EMAIL' },
        { 'data' : 'ASSIGN' }
    ];

    const column_definition = [
        { 'width': '45%', 'aTargets': 0 },
        { 'width': '45%', 'aTargets': 1 },
        { 'width': '10%', 'bSortable': false, 'aTargets': 2 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[-1], ['All']];

    settings = {
        'ajax': { 
            'url' : 'view/_user_account_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {'type' : type, 'role_id' : role_id},
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

function roleForm(){
    $('#role-form').validate({
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
            const role_id = $('#role-id').text();
            const transaction = 'save role';
        
            $.ajax({
                type: 'POST',
                url: 'controller/role-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&role_id=' + role_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-data');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert Role Success' : 'Update Role Success';
                        const notificationDescription = response.insertRecord ? 'The role has been inserted successfully.' : 'The role has been updated successfully.';
                        
                        setNotification(notificationMessage, notificationDescription, 'success');
                        window.location = 'role.php?id=' + response.roleID;
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
                    $('#role-modal').modal('hide');
                    reloadDatatable('#role-table');
                    resetModalForm('role-form');
                }
            });
        
            return false;
        }
    });
}

function addRoleUserAccountForm(){
    $('#add-role-user-account-form').validate({
        submitHandler: function(form) {
            const transaction = 'add role user account';
            const role_id = $('#role-id').text();

            var user_account_id = [];

            $('.user-account-role').each(function(){
                if ($(this).is(':checked')){  
                    user_account_id.push(this.value);  
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
                        showNotification('Add User Account Success', 'The user account has been added successfully.', 'success');
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
                    enableFormSubmitButton('submit-add-role-user-account', 'Submit');
                    $('#add-role-user-account-modal').modal('hide');
                    reloadDatatable('#role-user-account-access-table');
                }
            });
            return false;
        }
    });
}

function displayDetails(transaction){
    switch (transaction) {
        case 'get role details':
            const role_id = $('#role-id').text();
            
            $.ajax({
                url: 'controller/role-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    role_id : role_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetForm('role-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#role_id').val(role_id);
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
                            showNotification('Get Role Details Error', response.message, 'danger');
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