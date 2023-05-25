(function($) {
    'use strict';

    $(function() {
        const email_account = $('#email_account').text();

        if($('#file-types-table').length){
            initialized_menu_groups_table('#file-types-table');
        }

        $(document).on('click','.delete-file-type',function() {
            const menu_group_id = $(this).data('file-type-id');
            const transaction = 'delete file type';
    
            Swal.fire({
                title: 'Confirm File Type Deletion',
                text: 'Are you sure you want to delete this file type?',
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
                                    showNotification('Delete File Type Success', 'The file type has been deleted successfully.', 'success');
                                    reloadDatatable('#file-types-table');
                                    break;
                                case 'Not Found':
                                    showNotification('Delete File Type Error', 'The file type does not exist.', 'danger');
                                    reloadDatatable('#file-types-table');
                                    break;
                                case 'Inactive User':
                                case 'User Not Found':
                                    window.location = 'logout.php?logout';
                                    break;
                                default:
                                    showNotification('Delete File Type Error', response, 'danger');
                                    break;
                            }
                        }
                    });
                    return false;
                }
            });
        });

        $(document).on('click','#delete-file-type',function() {
            let menu_group_id = [];
            const transaction = 'delete multiple file type';

            $('.datatable-checkbox-children[data-delete="1"]').each((index, element) => {
                if ($(element).is(':checked')) {
                    menu_group_id.push(element.value);
                }
            });
    
            if(menu_group_id.length > 0){
                Swal.fire({
                    title: 'Confirm Multiple File Types Deletion',
                    text: 'Are you sure you want to delete these file types?',
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
                                        showNotification('Delete File Type Success', 'The selected file types have been deleted successfully.', 'success');
                                        reloadDatatable('#file-types-table');
                                        break;
                                    case 'User Not Found':
                                    case 'Inactive User':
                                        window.location = 'logout.php?logout';
                                        break;
                                    default:
                                        showNotification('File Type Deletion Error', response, 'danger');
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
                showNotification('Deletion Multiple File Type Error', 'Please select the file types you wish to delete.', 'danger');
            }
        });
    });
})(jQuery);

function initialized_menu_groups_table(datatable_name, buttons = false, show_all = false){
    toggleHideActionDropdown();

    const email_account = $('#email_account').text();
    const type = 'file types table';
    var settings;

    const column = [ 
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'FILE_TYPE_ID' },
        { 'data' : 'FILE_TYPE_NAME' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '1%','bSortable': false, 'aTargets': 0 },
        { 'width': '10%', 'aTargets': 1 },
        { 'width': '79%', 'aTargets': 2 },
        { 'width': '10%','bSortable': false, 'aTargets': 3 }
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
