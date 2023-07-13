(function($) {
    'use strict';

    $(function() {
        if($('#role-configuration-table').length){
            initializeRoleTable('#role-configuration-table');
        }

        if($('#role-configuration-form').length){
            initializeRoleForm();
        }

        if($('#role-id').length){
            displayDetails('get role details');

            if($('#assign-menu-item-access-modal').length){
                initializeMenuItemAccessForm();
            }
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
                        dataType: 'JSON',
                        data: {role_id : role_id, transaction : transaction},
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
                            dataType: 'JSON',
                            data: {role_id : role_id, transaction : transaction},
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
                        dataType: 'JSON',
                        data: {role_id : role_id, transaction : transaction},
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
                        dataType: 'JSON',
                        data: {role_id : role_id, transaction : transaction},
                        success: function (response) {
                            if (response.success) {
                                setNotification('Duplicate Role Success', 'The role has been duplicated successfully.', 'success');
                                window.location = 'role-configuration.php?id=' + response.menuItemID;
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

        $(document).on('click','#create-role',function() {
            resetModalForm("role-configuration-form");

            $('#role-modal').modal('show');
        });

        $(document).on('click','#assign-menu-item-access',function() {
            const role_id = $(this).data('role-id');

            sessionStorage.setItem('role_id', role_id);

            $('#assign-menu-item-access-modal').modal('show');
            initializeMenuItemAccessTable('#assign-menu-item-access-table');
        });

        $(document).on('click','.update-role',function() {
            const role_id = $(this).data('role-id');
    
            sessionStorage.setItem('role_id', role_id);
            
            displayDetails('get role details');
    
            $('#role-modal').modal('show');
        });
    });
})(jQuery);

function initializeRoleTable(datatable_name, buttons = false, show_all = false){
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

function initializeMenuItemAccessTable(datatable_name, buttons = false, show_all = false){
    const role_id = sessionStorage.getItem('role_id');
    const type = 'assign menu item access table';
    var settings;

    const column = [ 
        { 'data' : 'MENU_ITEM_NAME' },
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
            'url' : 'view/_role_configuration_generation.php',
            'method' : 'POST',
            'dataType': 'JSON',
            'data': {'type' : type, 'role_id' : role_id},
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

function initializeRoleForm(){
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
            const role_id = $('#role-id').text();
            const transaction = 'save role';
        
            $.ajax({
                type: 'POST',
                url: 'controller/role-controller.php',
                data: $(form).serialize() + '&role_id=' + role_id + '&transaction=' + transaction,
                dataType: 'JSON',
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
                  var fullErrorMessage = 'XHR status: ' + status + ', Error: ' + error;
        
                  var response = xhr.responseText;
                  fullErrorMessage += ', Response: ' + response;
                
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

function initializeMenuItemAccessForm(){
    $('#assign-role-role-access-form').validate({
        submitHandler: function(form) {
            const transaction = 'save role access';

            var role_id = sessionStorage.getItem('role_id');
            
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
                data: $(form).serialize() + '&role_id=' + role_id + '&permission=' + permission + '&transaction=' + transaction,
                dataType: 'JSON',
                beforeSend: function() {
                    disableFormSubmitButton('submit-menu-access-form');
                },
                success: function (response) {
                    if (response.success) {
                        showNotification('Update Role Role Access Success', 'The role role access has been updated successfully.', 'success')
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
                    enableFormSubmitButton('submit-menu-access-form', 'Submit');
                    $('#assign-menu-item-access-modal').modal('hide');
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
                dataType: 'JSON',
                data: {role_id : role_id, transaction : transaction},
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
                    var fullErrorMessage = 'XHR status: ' + status + ', Error: ' + error;
          
                    var response = xhr.responseText;
                    fullErrorMessage += ', Response: ' + response;
                  
                    showErrorDialog(fullErrorMessage);
                }
            });
            break;
    }
}