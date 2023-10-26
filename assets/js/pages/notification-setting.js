(function($) {
    'use strict';

    $(function() {
        if($('#notification-setting-table').length){
            notificationSettingTable('#notification-setting-table');
        }

        if($('#notification-setting-form').length){
            notificationSettingForm();
        }

        if($('#notification-setting-id').length){
            displayDetails('get notification setting details');

            if($('#update-system-notification-template-form').length){
                systemNotificationTemplateForm();
            }

            if($('#update-email-notification-template-form').length){
                emailNotificationTemplateForm();
            }

            if($('#update-sms-notification-template-form').length){
                smsNotificationTemplateForm();
            }

            $(document).on('click','#update-notification-channel',function() {   
                updateNotificationChannel('#update-notification-channel-table');
            });

            $(document).on('click','.update-notification-channel-status',function() {
                const notification_setting_id = $('#notification-setting-id').text();
                const channel = $(this).data('channel');
                const transaction = 'update notification channel status';
                var status;

                if ($(this).is(':checked')){  
                    status = '1';
                }
                else{
                    status = '0';
                }
                
                $.ajax({
                    type: 'POST',
                    url: 'controller/notification-setting-controller.php',
                    dataType: 'json',
                    data: {
                        notification_setting_id : notification_setting_id, 
                        channel : channel, 
                        status : status,
                        transaction : transaction
                    },
                    success: function (response) {
                        if (!response.success) {
                            if (response.isInactive) {
                                setNotification('User Inactive', response.message, 'danger');
                                window.location = 'logout.php?logout';
                            }
                            else if (response.notExist) {
                                showNotification('Update Notification Channel Status Error', 'The notification setting does not exist.', 'danger');
                            }
                            else {
                                showNotification('Update Notification Channel Status Error', response.message, 'danger');
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

            $(document).on('click','#update-system-notification-template',function() {
                resetModalForm("update-system-notification-template-form");
                displayDetails('get system notification template details');
            });

            $(document).on('click','#update-email-notification-template',function() {
                resetModalForm("update-email-notification-template-form");
                displayDetails('get email notification template details');
            });

            $(document).on('click','#update-sms-notification-template',function() {
                resetModalForm("update-sms-notification-template-form");
                displayDetails('get sms notification template details');
            });
        }

        $(document).on('click','.delete-notification-setting',function() {
            const notification_setting_id = $(this).data('notification-setting-id');
            const transaction = 'delete notification setting';
    
            Swal.fire({
                title: 'Confirm Notification Setting Deletion',
                text: 'Are you sure you want to delete this notification setting?',
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
                        url: 'controller/notification-setting-controller.php',
                        dataType: 'json',
                        data: {
                            notification_setting_id : notification_setting_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete Notification Setting Success', 'The notification setting has been deleted successfully.', 'success');
                                reloadDatatable('#notification-setting-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Delete Notification Setting Error', 'The notification setting does not exist.', 'danger');
                                    reloadDatatable('#notification-setting-table');
                                }
                                else {
                                    showNotification('Delete Notification Setting Error', response.message, 'danger');
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

        $(document).on('click','#delete-notification-setting',function() {
            let notification_setting_id = [];
            const transaction = 'delete multiple notification setting';

            $('.datatable-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    notification_setting_id.push(element.value);
                }
            });
    
            if(notification_setting_id.length > 0){
                Swal.fire({
                    title: 'Confirm Multiple Notification Settings Deletion',
                    text: 'Are you sure you want to delete these notification settings?',
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
                            url: 'controller/notification-setting-controller.php',
                            dataType: 'json',
                            data: {
                                notification_setting_id: notification_setting_id,
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Delete Notification Setting Success', 'The selected notification settings have been deleted successfully.', 'success');
                                        reloadDatatable('#notification-setting-table');
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else {
                                        showNotification('Delete Notification Setting Error', response.message, 'danger');
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
                showNotification('Deletion Multiple Notification Setting Error', 'Please select the notification settings you wish to delete.', 'danger');
            }
        });

        $(document).on('click','#delete-notification-setting-details',function() {
            const notification_setting_id = $('#notification-setting-id').text();
            const transaction = 'delete notification setting';
    
            Swal.fire({
                title: 'Confirm Notification Setting Deletion',
                text: 'Are you sure you want to delete this notification setting?',
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
                        url: 'controller/notification-setting-controller.php',
                        dataType: 'json',
                        data: {
                            notification_setting_id : notification_setting_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Deleted Notification Setting Success', 'The notification setting has been deleted successfully.', 'success');
                                window.location = 'notification-setting.php';
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
                                    showNotification('Delete Notification Setting Error', response.message, 'danger');
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
            discardCreate('notification-setting.php');
        });

        $(document).on('click','#edit-form',function() {
            displayDetails('get notification setting details');

            enableForm();
        });

        $(document).on('click','#duplicate-notification-setting',function() {
            const notification_setting_id = $('#notification-setting-id').text();
            const transaction = 'duplicate notification setting';
    
            Swal.fire({
                title: 'Confirm Notification Setting Duplication',
                text: 'Are you sure you want to duplicate this notification setting?',
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
                        url: 'controller/notification-setting-controller.php',
                        dataType: 'json',
                        data: {
                            notification_setting_id : notification_setting_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Duplicate Notification Setting Success', 'The notification setting has been duplicated successfully.', 'success');
                                window.location = 'notification-setting.php?id=' + response.notificationSettingID;
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Duplicate Notification Setting Error', 'The notification setting does not exist.', 'danger');
                                    reloadDatatable('#notification-setting-table');
                                }
                                else {
                                    showNotification('Duplicate Notification Setting Error', response.message, 'danger');
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

function notificationSettingTable(datatable_name, buttons = false, show_all = false){
    const type = 'notification setting table';
    var settings;

    const column = [ 
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'NOTIFICATION_SETTING_NAME' },
        { 'data' : 'NOTIFICATION_CHANNEL' },
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
            'url' : 'view/_notification_setting_generation.php',
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

function updateNotificationChannel(datatable_name, buttons = false, show_all = false){
    const notification_setting_id = $('#notification-setting-id').text();
    const type = 'update notification channel table';
    var settings;

    const column = [ 
        { 'data' : 'NOTIFICATION_CHANNEL' },
        { 'data' : 'ASSIGN' }
    ];

    const column_definition = [
        { 'width': '90%', 'aTargets': 0 },
        { 'width': '10%', 'bSortable': false, 'aTargets': 1 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[-1], ['All']];

    settings = {
        'ajax': { 
            'url' : 'view/_notification_setting_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {'type' : type, 'notification_setting_id' : notification_setting_id},
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

function notificationSettingForm(){
    $('#notification-setting-form').validate({
        rules: {
            notification_setting_name: {
                required: true
            },
            notification_setting_description: {
                required: true
            }
        },
        messages: {
            notification_setting_name: {
                required: 'Please enter the notification setting name'
            },
            notification_setting_description: {
                required: 'Please enter the notification setting description'
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
            const notification_setting_id = $('#notification-setting-id').text();
            const transaction = 'save notification setting';
        
            $.ajax({
                type: 'POST',
                url: 'controller/notification-setting-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&notification_setting_id=' + notification_setting_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-data');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert Notification Setting Success' : 'Update Notification Setting Success';
                        const notificationDescription = response.insertRecord ? 'The notification setting has been inserted successfully.' : 'The notification setting has been updated successfully.';
                        
                        setNotification(notificationMessage, notificationDescription, 'success');
                        window.location = 'notification-setting.php?id=' + response.notificationSettingID;
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

function systemNotificationTemplateForm(){
    $('#update-system-notification-template-form').validate({
        rules: {
            system_notification_title: {
                required: true
            },
            system_notification_message: {
                required: true
            }
        },
        messages: {
            system_notification_title: {
                required: 'Please enter the title'
            },
            system_notification_message: {
                required: 'Please enter the message'
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
            const notification_setting_id = $('#notification-setting-id').text();
            const transaction = 'update system notification template';
        
            $.ajax({
                type: 'POST',
                url: 'controller/notification-setting-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&notification_setting_id=' + notification_setting_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-update-email-notification-template');
                },
                success: function (response) {
                    if (response.success) {
                        showNotification('Update System Notification Template Success', 'The system notification template has been updated successfully.', 'success');
                    }
                    else {
                        if (response.isInactive) {
                            setNotification('User Inactive', response.message, 'danger');
                            window.location = 'logout.php?logout';
                        }
                        else {
                            showNotification('Update System Notification Template Error', response.message, 'danger');
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
                    enableFormSubmitButton('submit-update-email-notification-template', 'Submit');
                    $('#update-system-notification-template-offcanvas').offcanvas('hide');
                    resetModalForm('update-system-notification-template-form');
                }
            });
        
            return false;
        }
    });
}

function emailNotificationTemplateForm(){
    $('#update-email-notification-template-form').validate({
        rules: {
            email_notification_subject: {
                required: true
            }
        },
        messages: {
            email_notification_subject: {
                required: 'Please enter the subject'
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
            const notification_setting_id = $('#notification-setting-id').text();
            const transaction = 'update email notification template';
        
            $.ajax({
                type: 'POST',
                url: 'controller/notification-setting-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&notification_setting_id=' + notification_setting_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-update-email-notification-template');
                },
                success: function (response) {
                    if (response.success) {
                        showNotification('Update Email Notification Template Success', 'The email notification template has been updated successfully.', 'success');
                    }
                    else {
                        if (response.isInactive) {
                            setNotification('User Inactive', response.message, 'danger');
                            window.location = 'logout.php?logout';
                        }
                        else {
                            showNotification('Update Email Notification Template Error', response.message, 'danger');
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
                    enableFormSubmitButton('submit-update-email-notification-template', 'Submit');
                    $('#update-email-notification-template-offcanvas').offcanvas('hide');
                    resetModalForm('update-email-notification-template-form');
                }
            });
        
            return false;
        }
    });
}

function smsNotificationTemplateForm(){
    $('#update-sms-notification-template-form').validate({
        rules: {
            sms_notification_message: {
                required: true
            }
        },
        messages: {
            sms_notification_message: {
                required: 'Please enter the message'
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
            const notification_setting_id = $('#notification-setting-id').text();
            const transaction = 'update sms notification template';
        
            $.ajax({
                type: 'POST',
                url: 'controller/notification-setting-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&notification_setting_id=' + notification_setting_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-update-sms-notification-template');
                },
                success: function (response) {
                    if (response.success) {
                        showNotification('Update SMS Notification Template Success', 'The SMS notification template has been updated successfully.', 'success');
                    }
                    else {
                        if (response.isInactive) {
                            setNotification('User Inactive', response.message, 'danger');
                            window.location = 'logout.php?logout';
                        }
                        else {
                            showNotification('Update SMS Notification Template Error', response.message, 'danger');
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
                    enableFormSubmitButton('submit-update-sms-notification-template', 'Submit');
                    $('#update-sms-notification-template-offcanvas').offcanvas('hide');
                    resetModalForm('update-sms-notification-template-form');
                }
            });
        
            return false;
        }
    });
}

function displayDetails(transaction){
    switch (transaction) {
        case 'get notification setting details':
            var notification_setting_id = $('#notification-setting-id').text();
            
            $.ajax({
                url: 'controller/notification-setting-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    notification_setting_id : notification_setting_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetForm('notification-setting-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#notification_setting_id').val(notification_setting_id);
                        $('#notification_setting_name').val(response.notificationSettingName);
                        $('#notification_setting_description').val(response.notificationSettingDescription);

                        $('#notification_setting_name_label').text(response.notificationSettingName);
                        $('#notification_setting_description_label').text(response.notificationSettingDescription);
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Notification Setting Details Error', response.message, 'danger');
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
        case 'get system notification template details':
            var notification_setting_id = $('#notification-setting-id').text();
            
            $.ajax({
                url: 'controller/notification-setting-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    notification_setting_id : notification_setting_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetModalForm('update-system-notification-template-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#system_notification_title').val(response.systemNotificationTitle);
                        $('#system_notification_message').val(response.systemNotificationMessage);
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get System Notification Template Details Error', response.message, 'danger');
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
        case 'get email notification template details':
            var notification_setting_id = $('#notification-setting-id').text();
            
            $.ajax({
                url: 'controller/notification-setting-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    notification_setting_id : notification_setting_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetModalForm('update-email-notification-template-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#email_notification_subject').val(response.emailNotificationSubject);
                        tinymce.get('email_notification_body').setContent(response.emailNotificationBody)
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Email Notification Template Details Error', response.message, 'danger');
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
        case 'get sms notification template details':
            var notification_setting_id = $('#notification-setting-id').text();
            
            $.ajax({
                url: 'controller/notification-setting-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    notification_setting_id : notification_setting_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetModalForm('update-email-notification-template-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#sms_notification_message').val(response.smsNotificationMessage);
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get SMS Notification Template Details Error', response.message, 'danger');
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