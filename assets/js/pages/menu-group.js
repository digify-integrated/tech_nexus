(function($) {
    'use strict';

    $(function() {
        if($('#menu-group-table').length){
            initialized_menu_group_table('#menu-group-table');
        }

        if($('#menu-group-form').length){
            initializeMenuGroupForm();
        }

        if($('#menu-group-id').length){
            displayDetails('get menu group details');

            /*if($('#menu-item-table').length){
                initializeMenuItemTable('#menu-item-table');
            }

            if($('#menu-item-modal').length){
                initializeMenuItemForm();
            }

            if($('#assign-menu-item-role-access-modal').length){
                initializeMenuItemRoleAccessForm();
            }*/
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
                        dataType: 'JSON',
                        data: {menu_group_id : menu_group_id, transaction : transaction},
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
                          var fullErrorMessage = 'XHR status: ' + status + ', Error: ' + error;
                
                          var response = xhr.responseText;
                          fullErrorMessage += ', Response: ' + response;
                        
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
                            dataType: 'JSON',
                            data: {menu_group_id : menu_group_id, transaction : transaction},
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
                              var fullErrorMessage = 'XHR status: ' + status + ', Error: ' + error;
                    
                              var response = xhr.responseText;
                              fullErrorMessage += ', Response: ' + response;
                            
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
                        dataType: 'JSON',
                        data: {menu_group_id : menu_group_id, transaction : transaction},
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
                          var fullErrorMessage = 'XHR status: ' + status + ', Error: ' + error;
                
                          var response = xhr.responseText;
                          fullErrorMessage += ', Response: ' + response;
                        
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
            displayDetails('menu groups details');
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
                        dataType: 'JSON',
                        data: {menu_group_id : menu_group_id, transaction : transaction},
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
                          var fullErrorMessage = 'XHR status: ' + status + ', Error: ' + error;
                
                          var response = xhr.responseText;
                          fullErrorMessage += ', Response: ' + response;
                        
                          showErrorDialog(fullErrorMessage);
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
                        url: 'controller/menu-group-controller.php',
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

function initialized_menu_group_table(datatable_name, buttons = false, show_all = false){
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

function displayDetails(transaction){
    switch (transaction) {
        case 'get menu group details':
            var menu_group_id = $('#menu-group-id').text();
            
            $.ajax({
                url: 'controller/menu-group-controller.php',
                method: 'POST',
                dataType: 'JSON',
                data: {menu_group_id : menu_group_id, transaction : transaction},
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
                    var fullErrorMessage = 'XHR status: ' + status + ', Error: ' + error;
          
                    var response = xhr.responseText;
                    fullErrorMessage += ', Response: ' + response;
                  
                    showErrorDialog(fullErrorMessage);
                }
            });
            break;
        case 'modal menu item details':
            var menu_item_id = sessionStorage.getItem('menu_item_id');
            
            $.ajax({
                url: 'controller/menu-group-controller.php',
                method: 'POST',
                dataType: 'JSON',
                data: {menu_item_id : menu_item_id, transaction : transaction},
                beforeSend: function() {
                    resetModalForm('menu-item-form');
                },
                success: function(response) {
                    $('#menu_item_id').val(menu_item_id);
                    $('#menu_item_name').val(response[0].MENU_ITEM_NAME);
                    $('#menu_item_url').val(response[0].MENU_ITEM_URL);
                    $('#menu_item_icon').val(response[0].MENU_ITEM_ICON);
                    $('#menu_item_order_sequence').val(response[0].ORDER_SEQUENCE);
                    
                    checkOptionExist('#parent_id', response[0].PARENT_ID, '');
                },
                error: function(xhr, status, error) {
                    var fullErrorMessage = 'XHR status: ' + status + ', Error: ' + error;
          
                    var response = xhr.responseText;
                    fullErrorMessage += ', Response: ' + response;
                  
                    showErrorDialog(fullErrorMessage);
                }
            });
            break;
    }
}