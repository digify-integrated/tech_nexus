(function($) {
    'use strict';

    $(function() {
        if($('#role-configuration-table').length){
            roleTable('#role-configuration-table');
        }

        if($('#role-configuration-form').length){
            roleForm();
        }

        if($('#role-id').length){
            displayDetails('get role details');

            if($('#update-menu-item-access-table').length){
                updateMenuItemAccessTable('#update-menu-item-access-table');
            }

            if($('#update-system-action-access-table').length){
                updateSystemActionAccessTable('#update-system-action-access-table');
            }

            $(document).on('click','#add-menu-item-access',function() {
                const role_id = $(this).data('role-id');

                sessionStorage.setItem('role_id', role_id);

                $('#add-menu-item-access-modal').modal('show');
                addMenuItemAccessTable('#add-menu-item-access-table');
            });

            $(document).on('click','#edit-menu-item-access',function() {
                $('.update-menu-item-access').removeClass('d-none');
                $('.edit-menu-item-access-details').addClass('d-none');

                const updateAccess = document.querySelectorAll('.update-menu-item-access');

                updateAccess.forEach(button => {
                    button.removeAttribute('disabled');
                });
            });

            $(document).on('click','.delete-menu-item-access',function() {
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
                                    reloadDatatable('#update-menu-item-access-table');
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else if (response.notExist) {
                                        showNotification('Delete Menu Item Access Error', 'The menu item access does not exist.', 'danger');
                                        reloadDatatable('#update-menu-item-access-table');
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
                            }
                        });
                        return false;
                    }
                });
            });

            $(document).on('click','#discard-menu-item-access-update',() => {
                Swal.fire({
                    title: 'Discard Changes Confirmation',
                    text: 'Are you sure you want to discard the changes made to this item? The changes will be lost permanently once discarded.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Discard',
                    cancelButtonText: 'Cancel',
                    customClass: {
                        confirmButton: 'btn btn-danger mt-2',
                        cancelButton: 'btn btn-secondary ms-2 mt-2'
                    },
                    buttonsStyling: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        reloadDatatable('#update-menu-item-access-table');
                        $('.update-menu-item-access').addClass('d-none');
                        $('.edit-menu-item-access-details').removeClass('d-none');

                        const updateAccess = document.querySelectorAll('.update-menu-item-access');

                        updateAccess.forEach(button => {
                            button.setAttribute('disabled', 'disabled');
                        });
                    }
                });
            });

            $(document).on('click','#add-system-action-access',function() {
                const role_id = $(this).data('role-id');

                sessionStorage.setItem('role_id', role_id);

                $('#add-system-action-access-modal').modal('show');
                addSystemActionAccessTable('#add-system-action-access-table');
            });

            $(document).on('click','#edit-system-action-access',function() {
                $('.update-system-action-access').removeClass('d-none');
                $('.edit-system-action-access-details').addClass('d-none');

                const updateAccess = document.querySelectorAll('.update-system-action-access');

                updateAccess.forEach(button => {
                    button.removeAttribute('disabled');
                });
            });

            $(document).on('click','.delete-system-action-access',function() {
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
                                    reloadDatatable('#update-system-action-access-table');
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else if (response.notExist) {
                                        showNotification('Delete System Action Access Error', 'The system action access does not exist.', 'danger');
                                        reloadDatatable('#update-system-action-access-table');
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
                            }
                        });
                        return false;
                    }
                });
            });

            $(document).on('click','#discard-system-action-access-update',() => {
                Swal.fire({
                    title: 'Discard Changes Confirmation',
                    text: 'Are you sure you want to discard the changes made to this item? The changes will be lost permanently once discarded.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Discard',
                    cancelButtonText: 'Cancel',
                    customClass: {
                        confirmButton: 'btn btn-danger mt-2',
                        cancelButton: 'btn btn-secondary ms-2 mt-2'
                    },
                    buttonsStyling: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        reloadDatatable('#update-system-action-access-table');
                        $('.update-system-action-access').addClass('d-none');
                        $('.edit-system-action-access-details').removeClass('d-none');

                        const updateAccess = document.querySelectorAll('.update-system-action-access');

                        updateAccess.forEach(button => {
                            button.setAttribute('disabled', 'disabled');
                        });
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
                                reloadDatatable('#role-configuration-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Delete Role Error', 'The role does not exist.', 'danger');
                                    reloadDatatable('#role-configuration-table');
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

            $('.datatable-checkbox-children[data-delete="1"]').each((index, element) => {
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
                                        reloadDatatable('#role-configuration-table');
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
                                setNotification('Deleted Role Success', 'The role has been deleted successfully.', 'success');
                                window.location = 'role-configuration.php';
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

        $(document).on('click','#discard-create',function() {
            discardCreate('role-configuration.php');
        });

        $(document).on('click','#edit-form',function() {
            displayDetails('get role details');

            $('.form-details').addClass('d-none');
            $('.form-edit').removeClass('d-none');
        });

        $(document).on('click','#duplicate-role',function() {
            const role_id = $(this).data('role-id');
            const transaction = 'duplicate role';
    
            Swal.fire({
                title: 'Confirm Role Duplication',
                text: 'Are you sure you want to duplicate this role?',
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
                        url: 'controller/role-controller.php',
                        dataType: 'json',
                        data: {
                            role_id : role_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Duplicate Role Success', 'The role has been duplicated successfully.', 'success');
                                window.location = 'role-configuration.php?id=' + response.roleID;
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Duplicate Role Error', 'The role does not exist.', 'danger');
                                    reloadDatatable('#role-configuration-table');
                                }
                                else {
                                    showNotification('Duplicate Role Error', response.message, 'danger');
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

        $(document).on('click','#create-role',function() {
            resetModalForm("role-configuration-form");

            $('#role-modal').modal('show');
        });

        $(document).on('click','#assign-menu-item-access',function() {
            const role_id = $(this).data('role-id');

            sessionStorage.setItem('role_id', role_id);

            $('#assign-menu-item-access-modal').modal('show');
            menuItemAccessTable('#assign-menu-item-access-table');
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
    const type = 'role configuration table';
    var settings;

    const column = [ 
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'ROLE_ID' },
        { 'data' : 'ROLE_NAME' },
        { 'data' : 'ASSIGNABLE' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '1%','bSortable': false, 'aTargets': 0 },
        { 'width': '6%', 'aTargets': 1 },
        { 'width': '63%', 'aTargets': 2 },
        { 'width': '15%', 'aTargets': 3 },
        { 'width': '15%','bSortable': false, 'aTargets': 4 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_role_configuration_generation.php',
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

function addMenuItemAccessTable(datatable_name, buttons = false, show_all = false){
    const role_id = $('#role-id').text();
    const type = 'add menu item access table';
    var settings;

    const column = [ 
        { 'data' : 'MENU_ITEM_NAME' },
        { 'data' : 'ASSIGN' }
    ];

    const column_definition = [
        { 'width': '90%', 'aTargets': 0 },
        { 'width': '10%', 'bSortable': false, 'aTargets': 1 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[-1], ['All']];

    settings = {
        'ajax': { 
            'url' : 'view/_menu_item_generation.php',
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

function updateMenuItemAccessTable(datatable_name, buttons = false, show_all = false){
    const role_id = $('#role-id').text();
    const type = 'update menu item access table';
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

    const length_menu = show_all ? [[-1], ['All']] : [[-1], ['All']];

    settings = {
        'ajax': { 
            'url' : 'view/_menu_item_generation.php',
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

function addSystemActionAccessTable(datatable_name, buttons = false, show_all = false){
    const role_id = $('#role-id').text();
    const type = 'add system action access table';
    var settings;

    const column = [ 
        { 'data' : 'SYSTEM_ACTION_NAME' },
        { 'data' : 'ASSIGN' }
    ];

    const column_definition = [
        { 'width': '90%', 'aTargets': 0 },
        { 'width': '10%', 'bSortable': false, 'aTargets': 1 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[-1], ['All']];

    settings = {
        'ajax': { 
            'url' : 'view/_system_action_generation.php',
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

function updateSystemActionAccessTable(datatable_name, buttons = false, show_all = false){
    const role_id = $('#role-id').text();
    const type = 'update system action access table';
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

    const length_menu = show_all ? [[-1], ['All']] : [[-1], ['All']];

    settings = {
        'ajax': { 
            'url' : 'view/_system_action_generation.php',
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
    $('#role-configuration-form').validate({
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
                        window.location = 'role-configuration.php?id=' + response.roleID;
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
                    reloadDatatable('#role-configuration-table');
                    resetModalForm('role-configuration-form');
                }
            });
        
            return false;
        }
    });
}

function addMenuItemAccessForm(){
    $('#add-menu-item-access-form').validate({
        submitHandler: function(form) {
            const transaction = 'add menu item access';
            var role_id = sessionStorage.getItem('role_id');
            var menu_item_id = [];

            $('.menu-item-access').each(function(){
                if ($(this).is(':checked')){  
                    menu_item_id.push(this.value);  
                }
            });

            $.ajax({
                type: 'POST',
                url: 'controller/role-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&role_id=' + role_id + '&menu_item_id=' + menu_item_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-menu-item-access');
                },
                success: function(response) {
                    if (response.success) {
                        showNotification('Add Menu Item Access Success', 'The menu item access has been added successfully.', 'success');
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
                    enableFormSubmitButton('submit-menu-item-access', 'Submit');
                    $('#add-menu-item-access-modal').modal('hide');
                    reloadDatatable('#update-menu-item-access-table');
                    $('.update-menu-item-access').addClass('d-none');
                    $('.edit-menu-item-access-details').removeClass('d-none');

                    const updateAccess = document.querySelectorAll('.update-menu-item-access');

                    updateAccess.forEach(button => {
                        button.setAttribute('disabled', 'disabled');
                    });
                }
            });
            return false;
        }
    });
}

function updateMenuItemAccessForm(){
    $('#update-menu-item-access-form').validate({
        submitHandler: function(form) {
            const transaction = 'save menu item access';

            const menu_item_id = $('#menu-item-id').text();
            
            var permission = [];
        
            $('.update-role-access').each(function(){
                if($(this).is(':checked')){  
                    permission.push(this.value + '-1' );  
                }
                else{
                    permission.push(this.value + '-0' );
                }
            });
        
            $.ajax({
                type: 'POST',
                url: 'controller/role-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&menu_item_id=' + menu_item_id + '&permission=' + permission,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-update-menu-item-access');
                },
                success: function (response) {
                    if (response.success) {
                        showNotification('Update Menu Item Access Success', 'The menu item access has been updated successfully.', 'success')
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
                    enableFormSubmitButton('submit-update-menu-item-access', 'Save');
                    reloadDatatable('#update-menu-item-access-table');

                    $('.update-menu-item-access').addClass('d-none');
                    $('.edit-menu-item-access-details').removeClass('d-none');

                    const elements = document.querySelectorAll('.update-menu-item-access');

                    elements.forEach(element => {
                        element.addAttribute('disabled');
                    });
                }
            });
        
            return false;
        }
    });
}

function addSystemActionAccessForm(){
    $('#add-system-action-access-form').validate({
        submitHandler: function(form) {
            const transaction = 'add system action access';
            const system_action_id = $('#system-action-id').text();
            var role_id = [];

            $('.role-access').each(function(){
                if ($(this).is(':checked')){  
                    role_id.push(this.value);  
                }
            });

            $.ajax({
                type: 'POST',
                url: 'controller/role-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&system_action_id=' + system_action_id + '&role_id=' + role_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('add-system-action-access');
                },
                success: function(response) {
                    if (response.success) {
                        showNotification('Add Role Access Success', 'The role access has been added successfully.', 'success');
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
                    enableFormSubmitButton('add-system-action-access', 'Submit');
                    $('#add-role-access-modal').modal('hide');
                    reloadDatatable('#update-role-access-table');
                    $('.update-access').addClass('d-none');
                    $('.edit-access-details').removeClass('d-none');

                    const updateAccess = document.querySelectorAll('.update-role-access');

                    updateAccess.forEach(button => {
                        button.setAttribute('disabled', 'disabled');
                    });
                }
            });
            return false;
        }
    });
}

function updateRoleAccessForm(){
    $('#update-role-access-form').validate({
        submitHandler: function(form) {
            const transaction = 'save role system action access';

            const system_action_id = $('#system-action-id').text();
            
            var permission = [];
        
            $('.update-role-access').each(function(){
                if($(this).is(':checked')){  
                    permission.push(this.value + '-1' );  
                }
                else{
                    permission.push(this.value + '-0' );
                }
            });
        
            $.ajax({
                type: 'POST',
                url: 'controller/role-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&system_action_id=' + system_action_id + '&permission=' + permission,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-system-action-access');
                },
                success: function (response) {
                    if (response.success) {
                        showNotification('Update Role Access Success', 'The role access has been updated successfully.', 'success')
                    }
                    else {
                        if (response.isInactive) {
                            setNotification('User Inactive', response.message, 'danger');
                            window.location = 'logout.php?logout';
                        } else {
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
                    enableFormSubmitButton('submit-system-action-access', 'Save');
                    reloadDatatable('#update-role-access-table');

                    $('.update-access').addClass('d-none');
                    $('.edit-access-details').removeClass('d-none');

                    const elements = document.querySelectorAll('.update-role-access');

                    elements.forEach(element => {
                        element.addAttribute('disabled');
                    });
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