(function($) {
    'use strict';

    $(function() {
        const email_account = $('#email_account').text();

        if($('#menu-groups-table').length){
            initialized_menu_groups_table('#menu-groups-table');
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
                        url: 'controller.php',
                        data: {email_account : email_account, menu_group_id : menu_group_id, transaction : transaction},
                        success: function (response) {
                            switch (response) {
                                case 'Deleted':
                                    showNotification('Delete Menu Group Success', 'The menu group has been deleted successfully.', 'success');
                                    reloadDatatable('#menu-groups-table');
                                    break;
                                case 'Not Found':
                                    showNotification('Delete Menu Group Error', 'The menu group does not exist.', 'danger');
                                    reloadDatatable('#menu-groups-table');
                                    break;
                                case 'Inactive User':
                                case 'User Not Found':
                                    window.location = 'logout.php?logout';
                                    break;
                                default:
                                    showNotification('Delete Menu Group Error', response, 'danger');
                                    break;
                            }
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
                            url: 'controller.php',
                            data: {email_account : email_account, menu_group_id : menu_group_id, transaction : transaction},
                            success: function (response) {
                                switch (response) {
                                    case 'Deleted':
                                        showNotification('Delete Menu Group Success', 'The selected menu groups have been deleted successfully.', 'success');
                                        reloadDatatable('#menu-groups-table');
                                        break;
                                    case 'User Not Found':
                                    case 'Inactive User':
                                        window.location = 'logout.php?logout';
                                        break;
                                    default:
                                        showNotification('Menu Group Deletion Error', response, 'danger');
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
                showNotification('Deletion Multiple Menu Group Error', 'Please select the menu groups you wish to delete.', 'danger');
            }
        });
    });
})(jQuery);

function initialized_menu_groups_table(datatable_name, buttons = false, show_all = false){
    toggleHideActionDropdown();

    const email_account = $('#email_account').text();
    const type = 'menu groups table';
    var settings;

    const column = [ 
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'MENU_GROUP_ID' },
        { 'data' : 'MENU_GROUP_NAME' },
        { 'data' : 'ORDER_SEQUENCE' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '1%','bSortable': false, 'aTargets': 0 },
        { 'width': '10%', 'aTargets': 1 },
        { 'width': '64%', 'aTargets': 2 },
        { 'width': '15%', 'aTargets': 3 },
        { 'width': '10%','bSortable': false, 'aTargets': 4 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'system-generation.php',
            'method' : 'POST',
            'dataType': 'JSON',
            'data': {'type' : type, 'email_account' : email_account},
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
