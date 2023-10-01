(function($) {
    'use strict';

    $(function() {
        if($('#system-setting-table').length){
            systemSettingTable('#system-setting-table');
        }

        if($('#system-setting-form').length){
            systemSettingForm();
        }

        if($('#system-setting-id').length){
            displayDetails('get system setting details');
        }

        $(document).on('click','.delete-system-setting',function() {
            const system_setting_id = $(this).data('system-setting-id');
            const transaction = 'delete system setting';
    
            Swal.fire({
                title: 'Confirm System Setting Deletion',
                text: 'Are you sure you want to delete this system setting?',
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
                        url: 'controller/system-setting-controller.php',
                        dataType: 'json',
                        data: {
                            system_setting_id : system_setting_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete System Setting Success', 'The system setting has been deleted successfully.', 'success');
                                reloadDatatable('#system-setting-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Delete System Setting Error', 'The system setting does not exist.', 'danger');
                                    reloadDatatable('#system-setting-table');
                                }
                                else {
                                    showNotification('Delete System Setting Error', response.message, 'danger');
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

        $(document).on('click','#delete-system-setting',function() {
            let system_setting_id = [];
            const transaction = 'delete multiple system setting';

            $('.datatable-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    system_setting_id.push(element.value);
                }
            });
    
            if(system_setting_id.length > 0){
                Swal.fire({
                    title: 'Confirm Multiple System Settings Deletion',
                    text: 'Are you sure you want to delete these system settings?',
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
                            url: 'controller/system-setting-controller.php',
                            dataType: 'json',
                            data: {
                                system_setting_id: system_setting_id,
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Delete System Setting Success', 'The selected system settings have been deleted successfully.', 'success');
                                        reloadDatatable('#system-setting-table');
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else {
                                        showNotification('Delete System Setting Error', response.message, 'danger');
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
                showNotification('Deletion Multiple System Setting Error', 'Please select the system settings you wish to delete.', 'danger');
            }
        });

        $(document).on('click','#delete-system-setting-details',function() {
            const system_setting_id = $('#system-setting-id').text();
            const transaction = 'delete system setting';
    
            Swal.fire({
                title: 'Confirm System Setting Deletion',
                text: 'Are you sure you want to delete this system setting?',
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
                        url: 'controller/system-setting-controller.php',
                        dataType: 'json',
                        data: {
                            system_setting_id : system_setting_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Deleted System Setting Success', 'The system setting has been deleted successfully.', 'success');
                                window.location = 'system-setting.php';
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
                                    showNotification('Delete System Setting Error', response.message, 'danger');
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
            discardCreate('system-setting.php');
        });

        $(document).on('click','#edit-form',function() {
            displayDetails('get system setting details');

            enableForm();
        });

        $(document).on('click','#duplicate-system-setting',function() {
            const system_setting_id = $('#system-setting-id').text();
            const transaction = 'duplicate system setting';
    
            Swal.fire({
                title: 'Confirm System Setting Duplication',
                text: 'Are you sure you want to duplicate this system setting?',
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
                        url: 'controller/system-setting-controller.php',
                        dataType: 'json',
                        data: {
                            system_setting_id : system_setting_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Duplicate System Setting Success', 'The system setting has been duplicated successfully.', 'success');
                                window.location = 'system-setting.php?id=' + response.systemSettingID;
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Duplicate System Setting Error', 'The system setting does not exist.', 'danger');
                                    reloadDatatable('#system-setting-table');
                                }
                                else {
                                    showNotification('Duplicate System Setting Error', response.message, 'danger');
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

function systemSettingTable(datatable_name, buttons = false, show_all = false){
    const type = 'system setting table';
    var settings;

    const column = [ 
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'SYSTEM_SETTING_NAME' },
        { 'data' : 'VALUE' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '1%','bSortable': false, 'aTargets': 0 },
        { 'width': '64%', 'aTargets': 1 },
        { 'width': '20%', 'aTargets': 2 },
        { 'width': '15%','bSortable': false, 'aTargets': 3 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_system_setting_generation.php',
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

function systemSettingForm(){
    $('#system-setting-form').validate({
        rules: {
            system_setting_name: {
                required: true
            },
            system_setting_description: {
                required: true
            },
            value: {
                required: true
            }
        },
        messages: {
            system_setting_name: {
                required: 'Please enter the system setting name'
            },
            system_setting_description: {
                required: 'Please enter the system setting description'
            },
            value: {
                required: 'Please enter the value'
            }
        },
        errorPlacement: function (error, element) {
            if (element.hasClass('select2') || element.hasClass('modal-select2')) {
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
            const system_setting_id = $('#system-setting-id').text();
            const transaction = 'save system setting';
        
            $.ajax({
                type: 'POST',
                url: 'controller/system-setting-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&system_setting_id=' + system_setting_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-data');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert System Setting Success' : 'Update System Setting Success';
                        const notificationDescription = response.insertRecord ? 'The system setting has been inserted successfully.' : 'The system setting has been updated successfully.';
                        
                        setNotification(notificationMessage, notificationDescription, 'success');
                        window.location = 'system-setting.php?id=' + response.systemSettingID;
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

function displayDetails(transaction){
    switch (transaction) {
        case 'get system setting details':
            const system_setting_id = $('#system-setting-id').text();
            
            $.ajax({
                url: 'controller/system-setting-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    system_setting_id : system_setting_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetForm('system-setting-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#system_setting_id').val(system_setting_id);
                        $('#system_setting_name').val(response.systemSettingName);
                        $('#system_setting_description').val(response.systemSettingDescription);
                        $('#value').val(response.value);

                        $('#system_setting_name_label').text(response.systemSettingName);
                        $('#system_setting_description_label').text(response.systemSettingDescription);
                        $('#value_label').text(response.value);
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get System Setting Details Error', response.message, 'danger');
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