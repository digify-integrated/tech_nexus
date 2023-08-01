(function($) {
    'use strict';

    $(function() {
        if($('#system-action-table').length){
            systemActionTable('#system-action-table');
        }

        if($('#system-action-form').length){
            systemActionForm();
        }

        if($('#system-action-id').length){
            displayDetails('get system action details');

            if($('#update-system-action-role-access-table').length){
                updateSystemActionRoleAccessTable('#update-system-action-role-access-table');
            }

            if($('#add-system-action-role-access-modal').length){
                addSystemActionRoleAccessForm();
            }

            $(document).on('click','#add-system-action-role-access',function() {
                const system_action_id = $(this).data('system-action-id');
    
                sessionStorage.setItem('system_action_id', system_action_id);
    
                $('#add-system-action-role-access-modal').modal('show');
                addSystemActionRoleAccessTable('#add-system-action-role-access-table');
            });

            $(document).on('click','.update-system-action-role-access',function() {
                const system_action_id = $(this).data('system-action-id');
                const role_id = $(this).data('role-id');
                const transaction = 'save system action role access';
                var access;

                if ($(this).is(':checked')){  
                    access = '1';
                }
                else{
                    access = '0';
                }
                
                $.ajax({
                    type: 'POST',
                    url: 'controller/role-controller.php',
                    dataType: 'json',
                    data: {
                        system_action_id : system_action_id, 
                        role_id : role_id, 
                        access : access,
                        transaction : transaction
                    },
                    success: function (response) {
                        if (!response.success) {
                            if (response.isInactive) {
                                setNotification('User Inactive', response.message, 'danger');
                                window.location = 'logout.php?logout';
                            }
                            else if (response.notExist) {
                                showNotification('Update Role Access Error', 'The role access does not exist.', 'danger');
                            }
                            else {
                                showNotification('Update Role Access Error', response.message, 'danger');
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
            });
    
            $(document).on('click','.delete-system-action-role-access',function() {
                const system_action_id = $(this).data('system-action-id');
                const role_id = $(this).data('role-id');
                const transaction = 'delete system action role access';
        
                Swal.fire({
                    title: 'Confirm Role Access Deletion',
                    text: 'Are you sure you want to delete this role access?',
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
                                    showNotification('Delete Role Access Success', 'The role access has been deleted successfully.', 'success');
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else if (response.notExist) {
                                        showNotification('Delete Role Access Error', 'The role access does not exist.', 'danger');
                                    }
                                    else {
                                        showNotification('Delete Role Access Error', response.message, 'danger');
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
                                reloadDatatable('#update-system-action-role-access-table');
                            }
                        });
                        return false;
                    }
                });
            });
        }

        $(document).on('click','.delete-system-action',function() {
            const system_action_id = $(this).data('system-action-id');
            const transaction = 'delete system action';
    
            Swal.fire({
                title: 'Confirm System Action Deletion',
                text: 'Are you sure you want to delete this system action?',
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
                        url: 'controller/system-action-controller.php',
                        dataType: 'json',
                        data: {
                            system_action_id : system_action_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete System Action Success', 'The system action has been deleted successfully.', 'success');
                                reloadDatatable('#system-action-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Delete System Action Error', 'The system action does not exist.', 'danger');
                                    reloadDatatable('#system-action-table');
                                }
                                else {
                                    showNotification('Delete System Action Error', response.message, 'danger');
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

        $(document).on('click','#delete-system-action',function() {
            let system_action_id = [];
            const transaction = 'delete multiple system action';

            $('.datatable-checkbox-children[data-delete="1"]').each((index, element) => {
                if ($(element).is(':checked')) {
                    system_action_id.push(element.value);
                }
            });
    
            if(system_action_id.length > 0){
                Swal.fire({
                    title: 'Confirm Multiple System Actions Deletion',
                    text: 'Are you sure you want to delete these system actions?',
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
                            url: 'controller/system-action-controller.php',
                            dataType: 'json',
                            data: {
                                system_action_id: system_action_id,
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Delete System Action Success', 'The selected system actions have been deleted successfully.', 'success');
                                        reloadDatatable('#system-action-table');
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else {
                                        showNotification('Delete System Action Error', response.message, 'danger');
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
                showNotification('Deletion Multiple System Action Error', 'Please select the system actions you wish to delete.', 'danger');
            }
        });

        $(document).on('click','#delete-system-action-details',function() {
            const system_action_id = $(this).data('system-action-id');
            const transaction = 'delete system action';
    
            Swal.fire({
                title: 'Confirm System Action Deletion',
                text: 'Are you sure you want to delete this system action?',
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
                        url: 'controller/system-action-controller.php',
                        dataType: 'json',
                        data: {
                            system_action_id : system_action_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Deleted System Action Success', 'The system action has been deleted successfully.', 'success');
                                window.location = 'system-action.php';
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
                                    showNotification('Delete System Action Error', response.message, 'danger');
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
            discardCreate('system-action.php');
        });

        $(document).on('click','#edit-form',function() {
            displayDetails('get system action details');

            $('.form-details').addClass('d-none');
            $('.form-edit').removeClass('d-none');
        });

        $(document).on('click','#duplicate-system-action',function() {
            const system_action_id = $(this).data('system-action-id');
            const transaction = 'duplicate system action';
    
            Swal.fire({
                title: 'Confirm System Action Duplication',
                text: 'Are you sure you want to duplicate this system action?',
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
                        url: 'controller/system-action-controller.php',
                        dataType: 'json',
                        data: {
                            system_action_id : system_action_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Duplicate System Action Success', 'The system action has been duplicated successfully.', 'success');
                                window.location = 'system-action.php?id=' + response.systemActionID;
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Duplicate System Action Error', 'The system action does not exist.', 'danger');
                                    reloadDatatable('#system-action-table');
                                }
                                else {
                                    showNotification('Duplicate System Action Error', response.message, 'danger');
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

        $(document).on('click','#create-system-action',function() {
            resetModalForm("system-action-form");

            $('#system-action-modal').modal('show');
        });

        $(document).on('click','#assign-system-action-role-access',function() {
            const system_action_id = $(this).data('system-action-id');

            sessionStorage.setItem('system_action_id', system_action_id);

            $('#assign-system-action-role-access-modal').modal('show');
            roleAccessTable('#assign-system-action-role-access-table');
        });

        $(document).on('click','.update-system-action',function() {
            const system_action_id = $(this).data('system-action-id');
    
            sessionStorage.setItem('system_action_id', system_action_id);
            
            displayDetails('get system action details');
    
            $('#system-action-modal').modal('show');
        });
    });
})(jQuery);

function systemActionTable(datatable_name, buttons = false, show_all = false){
    const type = 'system action table';
    var settings;

    const column = [ 
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'SYSTEM_ACTION_NAME' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '1%','bSortable': false, 'aTargets': 0 },
        { 'width': '84%', 'aTargets': 1 },
        { 'width': '15%','bSortable': false, 'aTargets': 2 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_system_action_generation.php',
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

function updateSystemActionRoleAccessTable(datatable_name, buttons = false, show_all = false){
    const system_action_id = $('#system-action-id').text();
    const type = 'update system action role access table';
    var settings;

    const column = [ 
        { 'data' : 'ROLE_NAME' },
        { 'data' : 'ROLE_ACCESS' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '70%', 'aTargets': 0 },
        { 'width': '20%', 'bSortable': false, 'aTargets': 1 },
        { 'width': '10%', 'bSortable': false, 'aTargets': 2 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_system_action_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {'type' : type, 'system_action_id' : system_action_id},
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

function addSystemActionRoleAccessTable(datatable_name, buttons = false, show_all = false){
    const system_action_id = $('#system-action-id').text();
    const type = 'add system action role access table';
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
            'url' : 'view/_system_action_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {'type' : type, 'system_action_id' : system_action_id},
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

function systemActionForm(){
    $('#system-action-form').validate({
        rules: {
            system_action_name: {
                required: true
            }
        },
        messages: {
            system_action_name: {
                required: 'Please enter the system action name'
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
            const system_action_id = $('#system-action-id').text();
            const transaction = 'save system action';
        
            $.ajax({
                type: 'POST',
                url: 'controller/system-action-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&system_action_id=' + system_action_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-data');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert System Action Success' : 'Update System Action Success';
                        const notificationDescription = response.insertRecord ? 'The system action has been inserted successfully.' : 'The system action has been updated successfully.';
                        
                        setNotification(notificationMessage, notificationDescription, 'success');
                        window.location = 'system-action.php?id=' + response.systemActionID;
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
                    $('#system-action-modal').modal('hide');
                    reloadDatatable('#system-action-table');
                    resetModalForm('system-action-form');
                }
            });
        
            return false;
        }
    });
}

function addSystemActionRoleAccessForm(){
    $('#add-system-action-role-access-form').validate({
        submitHandler: function(form) {
            const transaction = 'add system action role access';
            const system_action_id = $('#system-action-id').text();

            var role_id = [];

                $('.system-action-role-access').each(function(){
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
                    disableFormSubmitButton('submit-add-system-action-role-access');
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
                    enableFormSubmitButton('submit-add-system-action-role-access', 'Submit');
                    $('#add-system-action-role-access-modal').modal('hide');
                    reloadDatatable('#update-system-action-role-access-table');
                }
            });
            return false;
        }
    });
}

function displayDetails(transaction){
    switch (transaction) {
        case 'get system action details':
            const system_action_id = $('#system-action-id').text();
            
            $.ajax({
                url: 'controller/system-action-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    system_action_id : system_action_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetModalForm('system-action-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#system_action_id').val(system_action_id);
                        $('#system_action_name').val(response.systemActionName);

                        $('#system_action_name_label').text(response.systemActionName);
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get System Action Details Error', response.message, 'danger');
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