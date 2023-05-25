(function($) {
    'use strict';

    $(function() {
        const email_account = $('#email_account').text();

        if($('#menu-items-table').length){
            initialized_menu_items_table('#menu-items-table');
        }

        if($('#assign-menu-item-role-access-modal').length){
            initializeMenuItemRoleAccessForm();
        }

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
                        url: 'controller.php',
                        data: {email_account : email_account, menu_item_id : menu_item_id, transaction : transaction},
                        success: function (response) {
                            switch (response) {
                                case 'Deleted':
                                    showNotification('Delete Menu Item Success', 'The menu item has been deleted successfully.', 'success');
                                    reloadDatatable('#menu-items-table');
                                    break;
                                case 'Not Found':
                                    showNotification('Delete Menu Item Error', 'The menu item does not exist.', 'danger');
                                    reloadDatatable('#menu-items-table');
                                    break;
                                case 'Inactive User':
                                case 'User Not Found':
                                    window.location = 'logout.php?logout';
                                    break;
                                default:
                                    showNotification('Delete Menu Item Error', response, 'danger');
                                    break;
                            }
                        }
                    });
                    return false;
                }
            });
        });

        $(document).on('click','#delete-menu-item',function() {
            let menu_item_id = [];
            const transaction = 'delete multiple menu item';

            $('.datatable-checkbox-children[data-delete="1"]').each((index, element) => {
                if ($(element).is(':checked')) {
                    menu_item_id.push(element.value);
                }
            });
    
            if(menu_item_id.length > 0){
                Swal.fire({
                    title: 'Confirm Multiple Menu Items Deletion',
                    text: 'Are you sure you want to delete these menu items?',
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
                                        showNotification('Delete Menu Item Success', 'The selected menu items have been deleted successfully.', 'success');
                                        reloadDatatable('#menu-items-table');
                                        break;
                                    case 'User Not Found':
                                    case 'Inactive User':
                                        window.location = 'logout.php?logout';
                                        break;
                                    default:
                                        showNotification('Menu Item Deletion Error', response, 'danger');
                                        break;
                                }
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
                showNotification('Deletion Multiple Menu Item Error', 'Please select the menu items you wish to delete.', 'danger');
            }
        });

        $(document).on('click','.assign-menu-item-role-access',function() {
            const menu_item_id = $(this).data('menu-item-id');

            sessionStorage.setItem('menu_item_id', menu_item_id);

            $('#assign-menu-item-role-access-modal').modal('show');
            initializeAssignMenuItemRoleAccessTable('#assign-menu-item-role-access-table');
        });

        $(document).on('click','#apply-filter',function() {
            initialized_menu_items_table('#menu-items-table');
        });
    });
})(jQuery);

function initialized_menu_items_table(datatable_name, buttons = false, show_all = false){
    toggleHideActionDropdown();

    const email_account = $('#email_account').text();
    const filter_menu_group_id = $('#filter_menu_group_id').val();
    const filter_parent_id = $('#filter_parent_id').val();
    const type = 'menu item table';
    var settings;

    const column = [ 
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'MENU_ITEM_ID' },
        { 'data' : 'MENU_ITEM_NAME' },
        { 'data' : 'MENU_GROUP_NAME' },
        { 'data' : 'PARENT_ID' },
        { 'data' : 'ORDER_SEQUENCE' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '1%','bSortable': false, 'aTargets': 0 },
        { 'width': '10%', 'aTargets': 1 },
        { 'width': '23%', 'aTargets': 2 },
        { 'width': '23%', 'aTargets': 3 },
        { 'width': '23%', 'aTargets': 4 },
        { 'width': '10%', 'aTargets': 5 },
        { 'width': '10%','bSortable': false, 'aTargets': 6 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'system-generation.php',
            'method' : 'POST',
            'dataType': 'JSON',
            'data': {'type' : type, 'email_account' : email_account, 'filter_menu_group_id' : filter_menu_group_id, 'filter_parent_id' : filter_parent_id},
            'dataSrc' : ''
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