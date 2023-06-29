(function($) {
    'use strict';

    $(function() {
        if($('#menu-groups-table').length){
            initialized_menu_groups_table('#menu-groups-table');
        }

        if($('#menu-group-form').length){
            initializeMenuGroupForm();
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
                        url: 'controllers/administrator-controller.php',
                        data: {menu_group_id : menu_group_id, transaction : transaction},
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
                            url: 'controllers/administrator-controller.php',
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

        $(document).on('click','#delete-menu-group-details',function() {
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
                        url: 'controllers/administrator-controller.php',
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

        $(document).on('click','#discard-create',function() {
            discardCreate('menu-group.php');
        });

        $(document).on('click','#edit-form',function() {
            displayDetails('menu groups details');
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
                        url: 'controllers/administrator-controller.php',
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
                        url: 'controllers/administrator-controller.php',
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

function initialized_menu_groups_table(datatable_name, buttons = false, show_all = false){
    toggleHideActionDropdown();

    const type = 'menu group table';
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
            'url' : 'view/_menu_group_generation.php',
            'method' : 'POST',
            'dataType': 'JSON',
            'data': {'type' : type},
            'dataSrc' : '',
            'error': function(xhr, status, error) {
                var fullErrorMessage = 'XHR status: ' + status + ', Error: ' + error;
      
                var response = xhr.responseText;
                fullErrorMessage += ', Response: ' + response;
              
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

function initializeMenuGroupForm(){
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
            const transaction = 'save menu group';
          
            $.ajax({
                type: 'POST',
                url: 'controller/menu-group-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction,
                dataType: 'JSON',
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
                        } else {
                            showNotification('Transaction Error', response.message, 'danger');
                        }
                    }
                },
                error: function(xhr, status, error) {
                  var fullErrorMessage = 'XHR status: ' + status + ', Error: ' + error;
        
                  var response = xhr.responseText;
                  fullErrorMessage += ', Response: ' + response;
                
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