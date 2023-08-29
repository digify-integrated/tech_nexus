(function($) {
    'use strict';

    $(function() {
        if($('#email-setting-table').length){
            emailSettingTable('#email-setting-table');
        }

        if($('#email-setting-form').length){
            emailSettingForm();
        }

        if($('#email-setting-id').length){
            displayDetails('get email setting details');

            if($('#change-mail-password-form').length){
                changePasswordForm();
            }

            $(document).on('click','#change-mail-password',function() {    
                $('#change-mail-password-modal').modal('show');
                resetModalForm('change-mail-password-form');
            });
        }

        $(document).on('click','.delete-email-setting',function() {
            const email_setting_id = $(this).data('email-setting-id');
            const transaction = 'delete email setting';
    
            Swal.fire({
                title: 'Confirm Email Setting Deletion',
                text: 'Are you sure you want to delete this email setting?',
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
                        url: 'controller/email-setting-controller.php',
                        dataType: 'json',
                        data: {
                            email_setting_id : email_setting_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete Email Setting Success', 'The email setting has been deleted successfully.', 'success');
                                reloadDatatable('#email-setting-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Delete Email Setting Error', 'The email setting does not exist.', 'danger');
                                    reloadDatatable('#email-setting-table');
                                }
                                else {
                                    showNotification('Delete Email Setting Error', response.message, 'danger');
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

        $(document).on('click','#delete-email-setting',function() {
            let email_setting_id = [];
            const transaction = 'delete multiple email setting';

            $('.datatable-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    email_setting_id.push(element.value);
                }
            });
    
            if(email_setting_id.length > 0){
                Swal.fire({
                    title: 'Confirm Multiple Email Settings Deletion',
                    text: 'Are you sure you want to delete these email settings?',
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
                            url: 'controller/email-setting-controller.php',
                            dataType: 'json',
                            data: {
                                email_setting_id: email_setting_id,
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Delete Email Setting Success', 'The selected email settings have been deleted successfully.', 'success');
                                        reloadDatatable('#email-setting-table');
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else {
                                        showNotification('Delete Email Setting Error', response.message, 'danger');
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
                showNotification('Deletion Multiple Email Setting Error', 'Please select the email settings you wish to delete.', 'danger');
            }
        });

        $(document).on('click','#delete-email-setting-details',function() {
            const email_setting_id = $('#email-setting-id').text();
            const transaction = 'delete email setting';
    
            Swal.fire({
                title: 'Confirm Email Setting Deletion',
                text: 'Are you sure you want to delete this email setting?',
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
                        url: 'controller/email-setting-controller.php',
                        dataType: 'json',
                        data: {
                            email_setting_id : email_setting_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Deleted Email Setting Success', 'The email setting has been deleted successfully.', 'success');
                                window.location = 'email-setting.php';
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
                                    showNotification('Delete Email Setting Error', response.message, 'danger');
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
            discardCreate('email-setting.php');
        });

        $(document).on('click','#edit-form',function() {
            displayDetails('get email setting details');

            enableForm();
        });

        $(document).on('click','#duplicate-email-setting',function() {
            const email_setting_id = $('#email-setting-id').text();
            const transaction = 'duplicate email setting';
    
            Swal.fire({
                title: 'Confirm Email Setting Duplication',
                text: 'Are you sure you want to duplicate this email setting?',
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
                        url: 'controller/email-setting-controller.php',
                        dataType: 'json',
                        data: {
                            email_setting_id : email_setting_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Duplicate Email Setting Success', 'The email setting has been duplicated successfully.', 'success');
                                window.location = 'email-setting.php?id=' + response.emailSettingID;
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Duplicate Email Setting Error', 'The email setting does not exist.', 'danger');
                                    reloadDatatable('#email-setting-table');
                                }
                                else {
                                    showNotification('Duplicate Email Setting Error', response.message, 'danger');
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

function emailSettingTable(datatable_name, buttons = false, show_all = false){
    const type = 'email setting table';
    var settings;

    const column = [ 
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'EMAIL_SETTING_NAME' },
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
            'url' : 'view/_email_setting_generation.php',
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

function emailSettingForm(){
    $('#email-setting-form').validate({
        rules: {
            email_setting_name: {
                required: true
            },
            email_setting_description: {
                required: true
            },
            mail_host: {
                required: true
            },
            mail_username: {
                required: true
            },
            mail_encryption: {
                required: true
            },
            smtp_auth: {
                required: true
            },
            mail_from_name: {
                required: true
            },
            port: {
                required: true
            },
            mail_from_email: {
                required: true
            },
            smtp_auto_tls: {
                required: true
            }
        },
        messages: {
            email_setting_name: {
                required: 'Please enter the email setting name'
            },
            email_setting_description: {
                required: 'Please enter the email setting description'
            },
            mail_host: {
                required: 'Please enter the mail host'
            },
            mail_username: {
                required: 'Please enter the mail username'
            },
            mail_encryption: {
                required: 'Please choose the mail encryption'
            },
            smtp_auth: {
                required: 'Please choose the SMTP Authentication'
            },
            mail_from_name: {
                required: 'Please enter the mail from name'
            },
            port: {
                required: 'Please enter the port'
            },
            mail_from_email: {
                required: 'Please enter the mail from email'
            },
            smtp_auto_tls: {
                required: 'Please choose the SMTP Auto TLS'
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
            const email_setting_id = $('#email-setting-id').text();
            const transaction = 'save email setting';
        
            $.ajax({
                type: 'POST',
                url: 'controller/email-setting-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&email_setting_id=' + email_setting_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-data');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert Email Setting Success' : 'Update Email Setting Success';
                        const notificationDescription = response.insertRecord ? 'The email setting has been inserted successfully.' : 'The email setting has been updated successfully.';
                        
                        setNotification(notificationMessage, notificationDescription, 'success');
                        window.location = 'email-setting.php?id=' + response.emailSettingID;
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

function changePasswordForm(){
    $('#change-mail-password-form').validate({
        rules: {
            new_password: {
              required: true,
              password_strength: true
            },
            confirm_password: {
              required: true,
              equalTo: '#new_password'
            }
        },
        messages: {
            new_password: {
              required: 'Please enter your new password'
            },
            confirm_password: {
              required: 'Please re-enter your password for confirmation',
              equalTo: 'The passwords you entered do not match. Please make sure to enter the same password in both fields'
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
        const transaction = 'change mail password';
        const email_setting_id = $('#email-setting-id').text();
  
        $.ajax({
            type: 'POST',
            url: 'controller/email-setting-controller.php',
            data: $(form).serialize() + '&transaction=' + transaction + '&email_setting_id=' + email_setting_id,
            dataType: 'json',
            beforeSend: function() {
                disableFormSubmitButton('submit-change-mail-password-form');
            },
            success: function(response) {
                if (response.success) {
                    showNotification('Password Change Success', 'The password has been successfully updated.', 'success');
                    $('#change-mail-password-modal').modal('hide');
                    resetModalForm('change-mail-password-form');
                }
                else{
                    if(response.isInactive){
                        window.location = 'logout.php?logout';
                    }
                    else{
                        showNotification('Password Change Error', response.message, 'danger');
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
                enableFormSubmitButton('submit-change-mail-password-form', 'Update Password');
            }
        });
  
        return false;
      }
    });
}

function displayDetails(transaction){
    switch (transaction) {
        case 'get email setting details':
            const email_setting_id = $('#email-setting-id').text();
            
            $.ajax({
                url: 'controller/email-setting-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    email_setting_id : email_setting_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetModalForm('email-setting-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#email_setting_id').val(email_setting_id);
                        $('#email_setting_name').val(response.emailSettingName);
                        $('#email_setting_description').val(response.emailSettingDescription);
                        $('#mail_host').val(response.mailHost);
                        $('#mail_username').val(response.mailUsername);
                        $('#mail_from_name').val(response.mailFromName);
                        $('#port').val(response.port);
                        $('#mail_from_email').val(response.mailFromEmail);

                        checkOptionExist('#mail_encryption', response.mailEncryption, '');
                        checkOptionExist('#smtp_auth', response.smtpAuth, '');
                        checkOptionExist('#smtp_auto_tls', response.smtoAutoTLS, '');

                        $('#email_setting_name_label').text(response.emailSettingName);
                        $('#email_setting_description_label').text(response.emailSettingDescription);
                        $('#mail_host_label').text(response.mailHost);
                        $('#mail_username_label').text(response.mailUsername);
                        $('#mail_from_name_label').text(response.mailFromName);
                        $('#port_label').text(response.port);
                        $('#mail_from_email_label').text(response.mailFromEmail);
                        $('#mail_encryption_label').text(response.mailEncryption);
                        $('#smtp_auth_label').text(response.smtpAuthName);
                        $('#smtp_auto_tls_label').text(response.smtoAutoTLSName);
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Email Setting Details Error', response.message, 'danger');
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