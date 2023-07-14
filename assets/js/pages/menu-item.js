(function($) {
    'use strict';

    $(function() {
        if($('#menu-item-table').length){
            initializeMenuItemTable('#menu-item-table');
        }

        if($('#sub-menu-item-table').length){
            initializeSubMenuItemTable('#sub-menu-item-table');
        }

        if($('#menu-item-form').length){
            initializeMenuItemForm();
        }

        if($('#menu-item-id').length){
            displayDetails('get menu item details');

            if($('#update-role-access-table').length){
                initializeUpdateRoleAccessTable('#update-role-access-table');
            }
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
                        url: 'controller/menu-item-controller.php',
                        dataType: 'JSON',
                        data: {menu_item_id : menu_item_id, transaction : transaction},
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
                                    showNotification('Delete Menu Item Error', 'The menu item does not exist.', 'danger');
                                    reloadDatatable('#menu-item-table');
                                }
                                else {
                                    showNotification('Delete Menu Item Error', response.message, 'danger');
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
                            url: 'controller/menu-item-controller.php',
                            dataType: 'JSON',
                            data: {menu_item_id : menu_item_id, transaction : transaction},
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Delete Menu Item Success', 'The selected menu items have been deleted successfully.', 'success');
                                        reloadDatatable('#menu-item-table');
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else {
                                        showNotification('Delete Menu Item Error', response.message, 'danger');
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
                showNotification('Deletion Multiple Menu Item Error', 'Please select the menu items you wish to delete.', 'danger');
            }
        });

        $(document).on('click','#delete-menu-item-details',function() {
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
                        dataType: 'JSON',
                        data: {menu_item_id : menu_item_id, transaction : transaction},
                        success: function (response) {
                            if (response.success) {
                                setNotification('Deleted Menu Item Success', 'The menu item has been deleted successfully.', 'success');
                                window.location = 'menu-item.php';
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
            discardCreate('menu-item.php');
        });
        
        $(document).on('click','#edit-form',function() {
            displayDetails('get menu item details');

            $('.form-details').addClass('d-none');
            $('.form-edit').removeClass('d-none');
        });

        $(document).on('click','#duplicate-menu-item',function() {
            const menu_item_id = $(this).data('menu-item-id');
            const transaction = 'duplicate menu item';
    
            Swal.fire({
                title: 'Confirm Menu Item Duplication',
                text: 'Are you sure you want to duplicate this menu item?',
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
                        url: 'controller/menu-item-controller.php',
                        dataType: 'JSON',
                        data: {menu_item_id : menu_item_id, transaction : transaction},
                        success: function (response) {
                            if (response.success) {
                                setNotification('Duplicate Menu Item Success', 'The menu item has been duplicated successfully.', 'success');
                                window.location = 'menu-item.php?id=' + response.menuItemID;
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Duplicate Menu Item Error', 'The menu item does not exist.', 'danger');
                                    reloadDatatable('#menu-item-table');
                                }
                                else {
                                    showNotification('Duplicate Menu Item Error', response.message, 'danger');
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
            resetModalForm("menu-item-form");

            $('#menu-item-modal').modal('show');
        });

        $(document).on('click','#add-role-access',function() {
            const menu_item_id = $(this).data('menu-item-id');

            sessionStorage.setItem('menu_item_id', menu_item_id);

            $('#add-role-access-modal').modal('show');
            initializeAddRoleAccessTable('#add-role-access-table');
        });

        $(document).on('click','.update-menu-item',function() {
            const menu_item_id = $(this).data('menu-item-id');
    
            sessionStorage.setItem('menu_item_id', menu_item_id);
            
            displayDetails('get menu item details');
    
            $('#menu-item-modal').modal('show');
        });
    });
})(jQuery);

function initializeMenuItemTable(datatable_name, buttons = false, show_all = false){
    const type = 'menu item table';
    var settings;

    const column = [ 
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'MENU_ITEM_ID' },
        { 'data' : 'MENU_ITEM_NAME' },
        { 'data' : 'MENU_GROUP_ID' },
        { 'data' : 'PARENT_ID' },
        { 'data' : 'ORDER_SEQUENCE' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '1%','bSortable': false, 'aTargets': 0 },
        { 'width': '6%', 'aTargets': 1 },
        { 'width': '21%', 'aTargets': 2 },
        { 'width': '21%', 'aTargets': 3 },
        { 'width': '21%', 'aTargets': 4 },
        { 'width': '14%', 'aTargets': 5 },
        { 'width': '15%','bSortable': false, 'aTargets': 6 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_menu_item_generation.php',
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

function initializeSubMenuItemTable(datatable_name, buttons = false, show_all = false){
    const menu_item_id = $('#menu-item-id').text();
    const type = 'sub menu item table';
    var settings;

    const column = [ 
        { 'data' : 'MENU_ITEM_NAME' },
        { 'data' : 'MENU_GROUP_ID' },
        { 'data' : 'ORDER_SEQUENCE' }
    ];

    const column_definition = [
        { 'width': '40%', 'aTargets': 0 },
        { 'width': '40%', 'aTargets': 1 },
        { 'width': '20%', 'aTargets': 2 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_menu_item_generation.php',
            'method' : 'POST',
            'dataType': 'JSON',
            'data': {'type' : type, 'menu_item_id' : menu_item_id},
            'dataSrc' : '',
            'error': function(xhr, status, error) {
                var fullErrorMessage = 'XHR status: ' + status + ', Error: ' + error;
      
                var response = xhr.responseText;
                fullErrorMessage += ', Response: ' + response;
              
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

function initializeUpdateRoleAccessTable(datatable_name, buttons = false, show_all = false){
    const menu_item_id = sessionStorage.getItem('menu_item_id');
    const type = 'update role access table';
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
        { 'width': '50%', 'aTargets': 0 },
        { 'width': '10%', 'bSortable': false, 'aTargets': 1 },
        { 'width': '10%', 'bSortable': false, 'aTargets': 2 },
        { 'width': '10%', 'bSortable': false, 'aTargets': 3 },
        { 'width': '10%', 'bSortable': false, 'aTargets': 4 },
        { 'width': '10%', 'bSortable': false, 'aTargets': 5 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[-1], ['All']];

    settings = {
        'ajax': { 
            'url' : 'view/_menu_item_generation.php',
            'method' : 'POST',
            'dataType': 'JSON',
            'data': {'type' : type, 'menu_item_id' : menu_item_id},
            'dataSrc' : '',
            'error': function(xhr, status, error) {
                var fullErrorMessage = 'XHR status: ' + status + ', Error: ' + error;
      
                var response = xhr.responseText;
                fullErrorMessage += ', Response: ' + response;
              
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

function initializeAddRoleAccessTable(datatable_name, buttons = false, show_all = false){
    const menu_item_id = sessionStorage.getItem('menu_item_id');
    const type = 'add role access table';
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
            'url' : 'view/_menu_item_generation.php',
            'method' : 'POST',
            'dataType': 'JSON',
            'data': {'type' : type, 'menu_item_id' : menu_item_id},
            'dataSrc' : '',
            'error': function(xhr, status, error) {
                var fullErrorMessage = 'XHR status: ' + status + ', Error: ' + error;
      
                var response = xhr.responseText;
                fullErrorMessage += ', Response: ' + response;
              
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

function initializeMenuItemForm(){
    $('#menu-item-form').validate({
        rules: {
            menu_item_name: {
                required: true
            },
            menu_group_id: {
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
            menu_group_id: {
                required: 'Please choose the menu group'
            },
            menu_item_order_sequence: {
                required: 'Please enter the order sequence'
            }
        },
        errorPlacement: function (error, element) {
            if (element.hasClass('select2')) {
                error.insertAfter(element.next('.select2-container'));
            }
            else if (element.parent('.input-item').length) {
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
            const menu_item_id = $('#menu-item-id').text();
            const transaction = 'save menu item';
        
            $.ajax({
                type: 'POST',
                url: 'controller/menu-item-controller.php',
                data: $(form).serialize() + '&menu_item_id=' + menu_item_id + '&transaction=' + transaction,
                dataType: 'JSON',
                beforeSend: function() {
                    disableFormSubmitButton('submit-data');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert Menu Item Success' : 'Update Menu Item Success';
                        const notificationDescription = response.insertRecord ? 'The menu item has been inserted successfully.' : 'The menu item has been updated successfully.';
                        
                        setNotification(notificationMessage, notificationDescription, 'success');
                        window.location = 'menu-item.php?id=' + response.menuItemID;
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
        
                  var response = xhr.responseText;
                  fullErrorMessage += ', Response: ' + response;
                
                  showErrorDialog(fullErrorMessage);
                },
                complete: function() {
                    enableFormSubmitButton('submit-data', 'Submit');
                    $('#menu-item-modal').modal('hide');
                    reloadDatatable('#menu-item-table');
                    resetModalForm('menu-item-form');
                }
            });
        
            return false;
        }
    });
}

function initializeAddRoleAccessForm(){
    $('#add-role-access-form').validate({
        submitHandler: function(form) {
            const transaction = 'save role access';

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
                url: 'controller/role-controller.php',
                data: $(form).serialize() + '&menu_item_id=' + menu_item_id + '&permission=' + permission + '&transaction=' + transaction,
                dataType: 'JSON',
                beforeSend: function() {
                    disableFormSubmitButton('add-menu-access-form');
                },
                success: function (response) {
                    if (response.success) {
                        showNotification('Add Role Access Success', 'The menu item role access has been added successfully.', 'success')
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
        
                  var response = xhr.responseText;
                  fullErrorMessage += ', Response: ' + response;
                
                  showErrorDialog(fullErrorMessage);
                },
                complete: function() {
                    enableFormSubmitButton('add-menu-access-form', 'Submit');
                    $('#add-role-access-modal').modal('hide');
                    reloadDatatable('#update-role-access-table');                    
                }
            });
        
            return false;
        }
    });
}

function displayDetails(transaction){
    switch (transaction) {
        case 'get menu item details':
            const menu_item_id = $('#menu-item-id').text();
            
            $.ajax({
                url: 'controller/menu-item-controller.php',
                method: 'POST',
                dataType: 'JSON',
                data: {menu_item_id : menu_item_id, transaction : transaction},
                success: function(response) {
                    if (response.success) {
                        $('#menu_item_id').val(menu_item_id);
                        $('#menu_item_name').val(response.menuItemName);
                        $('#menu_item_url').val(response.menuItemURL);
                        $('#menu_item_icon').val(response.menuItemIcon);
                        $('#menu_item_order_sequence').val(response.orderSequence);

                        $('#menu_item_name_label').text(response.menuItemName);
                        $('#order_sequence_label').text(response.orderSequence);
                        $('#menu_item_icon_label').text(response.menuItemIcon);
                        
                        document.getElementById('menu_group_id_label').innerHTML = response.menuGroupName;
                        document.getElementById('menu_item_url_label').innerHTML = response.menuItemURLLink;
                        document.getElementById('parent_id_label').innerHTML = response.parentName;
                        
                        checkOptionExist('#menu_group_id', response.menuGroupID, '');
                        checkOptionExist('#parent_id', response.parentID, '');
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Menu Item Details Error', response.message, 'danger');
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
    }
}