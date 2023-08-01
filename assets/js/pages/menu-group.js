(function($) {
    'use strict';

    $(function() {
        if($('#menu-group-table').length){
            menuGroupTable('#menu-group-table');
        }

        if($('#menu-group-form').length){
            menuGroupForm();
        }

        if($('#menu-group-id').length){
            displayDetails('get menu group details');

            if($('#menu-item-table').length){
                menuItemTable('#menu-item-table');
            }

            if($('#menu-item-modal').length){
                menuItemForm();
            }

            if($('#assign-menu-item-role-access-modal').length){
                menuItemRoleAccessForm();
            }

            $(document).on('click','#create-menu-item',function() {
                resetModalForm("menu-item-form");

                $('#menu-item-modal').modal('show');
            });

            $(document).on('click','.assign-menu-item-role-access',function() {
                const menu_item_id = $(this).data('menu-item-id');

                sessionStorage.setItem('menu_item_id', menu_item_id);

                $('#assign-menu-item-role-access-modal').modal('show');
                
                roleAccessTable('#assign-menu-item-role-access-table');
            });

            $(document).on('click','.update-menu-item',function() {
                const menu_item_id = $(this).data('menu-item-id');
        
                sessionStorage.setItem('menu_item_id', menu_item_id);
                
                displayDetails('get menu item details');
        
                $('#menu-item-modal').modal('show');
            });

            $(document).on('click','.delete-menu-item',function() {
                const menu_item_id = $(this).data('menu-item-id');
                const transaction = 'delete menu item';
        
                Swal.fire({
                    title: 'Confirm Menu Item Deletion',
                    text: 'Are you sure you want to delete this menu item?',
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
                            url: 'controller/menu-item-controller.php',
                            dataType: 'json',
                            data: {
                                menu_item_id : menu_item_id, 
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Delete Menu Item Success', 'The menu item has been deleted successfully.', 'success');
                                    reloadDatatable('#menu-item-table');
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
                                        showNotification('Delete Menu Item Error', response.message, 'danger');
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
        }

        $(document).on('click','.delete-menu-group',function() {
            const menu_group_id = $(this).data('menu-group-id');
            const transaction = 'delete menu group';
    
            Swal.fire({
                title: 'Confirm Menu Group Deletion',
                text: 'Are you sure you want to delete this menu group?',
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
                        url: 'controller/menu-group-controller.php',
                        dataType: 'json',
                        data: {
                            menu_group_id : menu_group_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete Menu Group Success', 'The menu group has been deleted successfully.', 'success');
                                reloadDatatable('#menu-group-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Delete Menu Group Error', 'The menu group does not exist.', 'danger');
                                    reloadDatatable('#menu-group-table');
                                }
                                else {
                                    showNotification('Delete Menu Group Error', response.message, 'danger');
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

        $(document).on('click','#delete-menu-group',function() {
            let menu_group_id = [];
            const transaction = 'delete multiple menu group';

            $('.datatable-checkbox-children[data-delete="1"]').each((index, element) => {
                if ($(element).is(':checked')) {
                    menu_group_id.push(element.value);
                }
            });
    
            if(menu_group_id.length > 0){
                Swal.fire({
                    title: 'Confirm Multiple Menu Groups Deletion',
                    text: 'Are you sure you want to delete these menu groups?',
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
                            url: 'controller/menu-group-controller.php',
                            dataType: 'json',
                            data: {
                                menu_group_id: menu_group_id,
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Delete Menu Group Success', 'The selected menu groups have been deleted successfully.', 'success');
                                        reloadDatatable('#menu-group-table');
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else {
                                        showNotification('Delete Menu Group Error', response.message, 'danger');
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
                showNotification('Deletion Multiple Menu Group Error', 'Please select the menu groups you wish to delete.', 'danger');
            }
        });

        $(document).on('click','#delete-menu-group-details',function() {
            const menu_group_id = $(this).data('menu-group-id');
            const transaction = 'delete menu group';
    
            Swal.fire({
                title: 'Confirm Menu Group Deletion',
                text: 'Are you sure you want to delete this menu group?',
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
                        url: 'controller/menu-group-controller.php',
                        dataType: 'json',
                        data: {
                            menu_group_id : menu_group_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Deleted Menu Group Success', 'The menu group has been deleted successfully.', 'success');
                                window.location = 'menu-group.php';
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
                                    showNotification('Delete Menu Group Error', response.message, 'danger');
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
            discardCreate('menu-group.php');
        });

        $(document).on('click','#edit-form',function() {
            displayDetails('get menu group details');

            $('.form-details').addClass('d-none');
            $('.form-edit').removeClass('d-none');
        });

        $(document).on('click','#duplicate-menu-group',function() {
            const menu_group_id = $(this).data('menu-group-id');
            const transaction = 'duplicate menu group';
    
            Swal.fire({
                title: 'Confirm Menu Group Duplication',
                text: 'Are you sure you want to duplicate this menu group?',
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
                        url: 'controller/menu-group-controller.php',
                        dataType: 'json',
                        data: {
                            menu_group_id : menu_group_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Duplicate Menu Group Success', 'The menu group has been duplicated successfully.', 'success');
                                window.location = 'menu-group.php?id=' + response.menuGroupID;
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Duplicate Menu Group Error', 'The menu group does not exist.', 'danger');
                                    reloadDatatable('#menu-group-table');
                                }
                                else {
                                    showNotification('Duplicate Menu Group Error', response.message, 'danger');
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

function menuGroupTable(datatable_name, buttons = false, show_all = false){
    toggleHideActionDropdown();

    const type = 'menu group table';
    var settings;

    const column = [ 
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'MENU_GROUP_NAME' },
        { 'data' : 'ORDER_SEQUENCE' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '1%','bSortable': false, 'aTargets': 0 },
        { 'width': '74%', 'aTargets': 1 },
        { 'width': '15%', 'aTargets': 2 },
        { 'width': '10%','bSortable': false, 'aTargets': 3 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_menu_group_generation.php',
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
        'scrollY': false,
        'scrollX': true,
        'scrollCollapse': true,
        'fnDrawCallback': function( oSettings ) {
            readjustDatatableColumn();
        },
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

function menuItemTable(datatable_name, buttons = false, show_all = false){
    const menu_group_id = $('#menu-group-id').text();
    const type = 'menu group menu item table';
    var settings;

    const column = [ 
        { 'data' : 'MENU_ITEM_ID' },
        { 'data' : 'MENU_ITEM_NAME' },
        { 'data' : 'PARENT_ID' },
        { 'data' : 'ORDER_SEQUENCE' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '7%', 'aTargets': 0 },
        { 'width': '32%', 'aTargets': 1 },
        { 'width': '32%', 'aTargets': 2 },
        { 'width': '14%', 'aTargets': 3 },
        { 'width': '15%','bSortable': false, 'aTargets': 4 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_menu_group_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {'type' : type, 'menu_group_id' : menu_group_id},
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

function roleAccessTable(datatable_name, buttons = false, show_all = false){
    var menu_item_id = sessionStorage.getItem('menu_item_id');
    const type = 'update menu group role access table';
    var settings;

    const column = [ 
        { 'data' : 'ROLE_NAME' },
        { 'data' : 'READ_ACCESS' },
        { 'data' : 'WRITE_ACCESS' },
        { 'data' : 'CREATE_ACCESS' },
        { 'data' : 'DELETE_ACCESS' },
        { 'data' : 'DUPLICATE_ACCESS' }
    ];

    const column_definition = [
        { 'width': '40%', 'aTargets': 0 },
        { 'width': '12%', 'bSortable': false, 'aTargets': 1 },
        { 'width': '12%', 'bSortable': false, 'aTargets': 2 },
        { 'width': '12%', 'bSortable': false, 'aTargets': 3 },
        { 'width': '12%', 'bSortable': false, 'aTargets': 4 },
        { 'width': '12%', 'bSortable': false, 'aTargets': 5 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[-1], ['All']];

    settings = {
        'ajax': { 
            'url' : 'view/_menu_item_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {'type' : type, 'menu_item_id' : menu_item_id},
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

function menuGroupForm(){
    $('#menu-group-form').validate({
        rules: {
            menu_group_name: {
                required: true
            },
            menu_group_order_sequence: {
                required: true
            }
        },
        messages: {
            menu_group_name: {
                required: 'Please enter the menu group name'
            },
            menu_group_order_sequence: {
                required: 'Please enter the order sequence'
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
            const transaction = 'save menu group';
          
            $.ajax({
                type: 'POST',
                url: 'controller/menu-group-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-data');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert Menu Group Success' : 'Update Menu Group Success';
                        const notificationDescription = response.insertRecord ? 'The menu group has been inserted successfully.' : 'The menu group has been updated successfully.';
                        
                        setNotification(notificationMessage, notificationDescription, 'success');
                        window.location = 'menu-group.php?id=' + response.menuGroupID;
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

function menuItemForm(){
    $('#menu-item-form').validate({
        rules: {
            menu_item_name: {
                required: true
            },
            menu_item_order_sequence: {
                required: true
            }
        },
        messages: {
            menu_item_name: {
                required: 'Please enter the menu item name'
            },
            menu_item_order_sequence: {
                required: 'Please enter the order sequence'
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
            const menu_group_id = $('#menu-group-id').text();
            const transaction = 'save menu item';
        
            $.ajax({
                type: 'POST',
                url: 'controller/menu-item-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&menu_group_id=' + menu_group_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-menu-item-form');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert Menu Item Success' : 'Update Menu Item Success';
                        const notificationDescription = response.insertRecord ? 'The menu item has been inserted successfully.' : 'The menu item has been updated successfully.';
                        
                        showNotification(notificationMessage, notificationDescription, 'success');
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
                    enableFormSubmitButton('submit-menu-item-form', 'Submit');
                    $('#menu-item-modal').modal('hide');
                    reloadDatatable('#menu-item-table');
                    resetModalForm('menu-item-form');
                }
            });
        
            return false;
        }
    });
}

function menuItemRoleAccessForm(){
    $('#assign-menu-item-role-access-form').validate({
        submitHandler: function(form) {
            const transaction = 'save menu item role access';

            var menu_item_id = sessionStorage.getItem('menu_item_id');
            
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
                    disableFormSubmitButton('submit-menu-access-form');
                },
                success: function (response) {
                    if (response.success) {
                        showNotification('Update Menu Item Role Access Success', 'The menu item role access has been updated successfully.', 'success')
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
                    enableFormSubmitButton('submit-menu-access-form', 'Submit');
                    $('#assign-menu-item-role-access-modal').modal('hide');
                }
            });
        
            return false;
        }
    });
}

function displayDetails(transaction){
    switch (transaction) {
        case 'get menu group details':
            var menu_group_id = $('#menu-group-id').text();
            
            $.ajax({
                url: 'controller/menu-group-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    menu_group_id : menu_group_id, 
                    transaction : transaction
                },
                success: function(response) {
                    if (response.success) {
                        $('#menu_group_name').val(response.menuGroupName);
                        $('#menu_group_order_sequence').val(response.orderSequence);
                        
                        $('#menu_group_name_label').text(response.menuGroupName);
                        $('#order_sequence_label').text(response.orderSequence);
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Menu Group Details Error', response.message, 'danger');
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
        case 'get menu item details':
            var menu_item_id = sessionStorage.getItem('menu_item_id');
            
            $.ajax({
                url: 'controller/menu-item-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    menu_item_id : menu_item_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetModalForm('menu-item-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#menu_item_id').val(menu_item_id);
                        $('#menu_item_name').val(response.menuItemName);
                        $('#menu_item_url').val(response.menuItemURL);
                        $('#menu_item_icon').val(response.menuItemIcon);
                        $('#menu_item_order_sequence').val(response.orderSequence);
                        
                        checkOptionExist('#parent_id', response.parentID, '');
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Menu Group Details Error', response.message, 'danger');
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