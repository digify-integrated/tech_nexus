(function($) {
    'use strict';

    $(function() {
        initializeMenuGroupForm();

        if($('#menu-group-id').length){
            displayDetails('menu groups details');

            if($('#menu-item-table').length){
                initializeMenuItemTable('#menu-item-table');
            }

            if($('#menu-item-modal').length){
                initializeMenuItemForm();
            }

            if($('#assign-menu-item-role-access-modal').length){
                initializeMenuItemRoleAccessForm();
            }
        }
        
        $(document).on('click','#discard-create',function() {
            discardCreate('menu-groups.php');
        });

        $(document).on('click','#edit-form',function() {
            displayDetails('menu groups details');
        });

        $(document).on('click','#delete-menu-group',function() {
            const email_account = $('#email_account').text();
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
                        url: 'controller.php',
                        data: {email_account : email_account, menu_group_id : menu_group_id, transaction : transaction},
                        success: function (response) {
                            switch (response) {
                                case 'Deleted':
                                    setNotification('Deleted Menu Group Success', 'The menu group has been deleted successfully.', 'success');
                                    window.location = 'menu-groups.php';
                                    break;
                                case 'Not Found':
                                    setNotification('Delete Menu Group Error', 'The menu group does not exist.', 'danger');
                                    window.location = '404.php';
                                    break;
                                case 'User Not Found':
                                case 'Inactive User':
                                    window.location = 'logout.php?logout';
                                    break;
                                default:
                                    show_toastr('Delete Menu Group Error', response, 'danger');
                                    break;
                            }
                        }
                    });
                    return false;
                }
            });
        });

        $(document).on('click','#duplicate-menu-group',function() {
            const email_account = $('#email_account').text();
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
                        url: 'controller.php',
                        data: {email_account : email_account, menu_group_id : menu_group_id, transaction : transaction},
                        dataType: 'JSON',
                        success: function (response) {
                            switch (response[0]['RESPONSE']) {
                                case 'Duplicated':
                                    setNotification('Duplicate Menu Group Success', 'The menu group has been duplicated successfully.', 'success');
                                    window.location = 'menu-group-form.php?id=' + response[0]['MENU_GROUP_ID'];
                                    break;
                                case 'Not Found':
                                    setNotification('Duplicate Menu Group Error', 'The source menu group does not exist.', 'danger');
                                    window.location = '404.php';
                                    break;
                                case 'User Not Found':
                                case 'Inactive User':
                                    window.location = 'logout.php?logout';
                                    break;
                                default:
                                    setNotification('Transaction Error', response[0]['RESPONSE'], 'danger');
                                    break;
                            }
                        }
                    });
                    return false;
                }
            });
        });

        $(document).on('click','#create-menu-item',function() {
            $('#menu-item-modal').modal('show');

            resetModalForm("menu-item-form");
        });

        $(document).on('click','.assign-menu-item-role-access',function() {
            const menu_item_id = $(this).data('menu-item-id');

            sessionStorage.setItem('menu_item_id', menu_item_id);

            $('#assign-menu-item-role-access-modal').modal('show');
            initializeAssignMenuItemRoleAccessTable('#assign-menu-item-role-access-table');
        });

        $(document).on('click','.update-menu-item',function() {
            const menu_item_id = $(this).data('menu-item-id');
    
            sessionStorage.setItem('menu_item_id', menu_item_id);
            
            displayDetails('modal menu item details');
    
            $('#menu-item-modal').modal('show');
        });

        $(document).on('click','.delete-menu-item',function() {
            const email_account = $('#email_account').text();
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
                        url: 'controller.php',
                        data: {email_account : email_account, menu_item_id : menu_item_id, transaction : transaction},
                        success: function (response) {
                            switch (response) {
                                case 'Deleted':
                                    showNotification('Delete Menu Item Success', 'The menu item has been deleted successfully.', 'success');
                                    reloadDatatable('#menu-item-table');
                                    break;
                                case 'Not Found':
                                    showNotification('Delete Menu Item Error', 'The menu item does not exist or has already been deleted.', 'warning');
                                    reloadDatatable('#menu-item-table');
                                    break;
                                case 'User Not Found':
                                case 'Inactive User':
                                    window.location = '404.php';
                                    break;
                                default:
                                    showNotification('Menu Item Deletion Error', response, 'danger');
                                    break;
                            }
                        }
                    });
                    return false;
                }
            });
        });
    });
})(jQuery);

function initializeMenuItemTable(datatable_name, buttons = false, show_all = false){
    const menu_group_id = $('#menu-group-id').text();
    const email_account = $('#email_account').text();
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
            'url' : 'system-generation.php',
            'method' : 'POST',
            'dataType': 'JSON',
            'data': {'type' : type, 'email_account' : email_account, 'menu_group_id' : menu_group_id},
            'dataSrc' : ''
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

function initializeAssignMenuItemRoleAccessTable(datatable_name, buttons = false, show_all = false){
    const menu_item_id = sessionStorage.getItem('menu_item_id');
    const email_account = $('#email_account').text();
    const type = 'assign menu item role access table';
    var settings;

    const column = [ 
        { 'data' : 'ROLE_NAME' },
        { 'data' : 'READ_ACCESS' },
        { 'data' : 'WRITE_ACCESS' },
        { 'data' : 'CREATE_ACCESS' },
        { 'data' : 'DELETE_ACCESS' }
    ];

    const column_definition = [
        { 'width': '40%', 'aTargets': 0 },
        { 'width': '12%', 'bSortable': false, 'aTargets': 1 },
        { 'width': '12%', 'bSortable': false, 'aTargets': 2 },
        { 'width': '12%', 'bSortable': false, 'aTargets': 3 },
        { 'width': '12%', 'bSortable': false, 'aTargets': 4 },
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[-1], ['All']];

    settings = {
        'ajax': { 
            'url' : 'system-generation.php',
            'method' : 'POST',
            'dataType': 'JSON',
            'data': {'type' : type, 'email_account' : email_account, 'menu_item_id' : menu_item_id},
            'dataSrc' : ''
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

function initializeMenuGroupForm(){
    $('#menu-group-form').validate({
        rules: {
            menu_group: {
                required: true
            },
            menu_group_order_sequence: {
                required: true
            }
        },
        messages: {
            menu_group: {
                required: 'Please enter the menu group'
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
            const email_account = $('#email_account').text();
            const transaction = 'submit menu group';
          
            $.ajax({
                type: 'POST',
                url: 'controller.php',
                data: $(form).serialize() + '&email_account=' + email_account + '&transaction=' + transaction,
                dataType: 'JSON',
                beforeSend: function() {
                    disableFormSubmitButton('submit-data');
                },
                success: function (response) {
                    switch (response[0]['RESPONSE']) {
                        case 'Inserted':
                            setNotification('Insert Menu Group Success', 'The menu group has been inserted successfully.', 'success');
                            window.location = window.location.href + '?id=' + response[0]['MENU_GROUP_ID'];
                            break;
                        case 'Updated':
                            setNotification('Update Menu Group Success', 'The menu group has been updated successfully.', 'success');
                            window.location.reload();
                            break;
                        case 'User Not Found':
                        case 'Inactive User':
                            window.location = 'logout.php?logout';
                            break;
                        default:
                            setNotification('Transaction Error', response, 'danger');
                            break;
                    }
                },
                complete: function() {
                    enableFormSubmitButton('submit-data', 'Save');
                }
            });
        
            return false;
        }
    });
}

function initializeMenuItemForm(){
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
            const email_account = $('#email_account').text();
            const menu_group_id = $('#menu-group-id').text();
            const transaction = 'submit menu item';
        
            $.ajax({
                type: 'POST',
                url: 'controller.php',
                data: $(form).serialize() + '&email_account=' + email_account + '&menu_group_id=' + menu_group_id + '&transaction=' + transaction,
                dataType: 'JSON',
                beforeSend: function() {
                    disableFormSubmitButton('submit-data');
                },
                success: function (response) {
                    switch (response[0]['RESPONSE']) {
                        case 'Inserted':
                            showNotification('Insert Menu Item Success', 'The menu item has been inserted successfully.', 'success');
                            break;
                        case 'Updated':
                            showNotification('Update Menu Item Success', 'The menu item has been updated successfully.', 'success');
                            break;
                        case 'User Not Found':
                        case 'Inactive User':
                            window.location = 'logout.php?logout';
                            break;
                        default:
                            showNotification('Transaction Error', response, 'danger');
                            break;
                    }
                },
                complete: function() {
                    enableFormSubmitButton('submit-form', 'Submit');
                    $('#menu-item-modal').modal('hide');
                    reloadDatatable('#menu-item-table');
                    resetModalForm('menu-item-form');
                }
            });
        
            return false;
        }
    });
}

function initializeMenuItemRoleAccessForm(){
    $('#assign-menu-item-role-access-form').validate({
        submitHandler: function(form) {
            const email_account = $('#email_account').text();
            const transaction = 'submit menu item role access';

            var menu_item_id = sessionStorage.getItem('menu_item_id');
            
            var permission = [];
        
            $('.role-access').each(function(){
                if($(this).is(':checked')){  
                    permission.push(this.value + '-1' );  
                }
                else{
                    permission.push(this.value + '-0' );
                }
            });
        
            $.ajax({
                type: 'POST',
                url: 'controller.php',
                data: $(form).serialize() + '&email_account=' + email_account + '&menu_item_id=' + menu_item_id + '&permission=' + permission + '&transaction=' + transaction,
                beforeSend: function() {
                    disableFormSubmitButton('submit-form');
                },
                success: function (response) {
                    switch (response) {
                        case 'Updated':
                            showNotification('Update Menu Item Role Access Success', 'The menu item role access has been updated successfully.', 'success');
                            break;
                        case 'User Not Found':
                        case 'Inactive User':
                            window.location = 'logout.php?logout';
                            break;
                        default:
                            showNotification('Transaction Error', response, 'danger');
                            break;
                    }
                },
                complete: function() {
                    enableFormSubmitButton('submit-form', 'Submit');
                    $('#assign-menu-item-role-access-modal').modal('hide');
                }
            });
        
            return false;
        }
    });
}