(function($) {
    'use strict';

    $(function() {
        if($('#upload-setting-table').length){
            uploadSettingTable('#upload-setting-table');
        }

        if($('#upload-setting-form').length){
            uploadSettingForm();
        }

        if($('#upload-setting-id').length){
            displayDetails('get upload setting details');

            if($('#file-extension-table').length){
                fileExtensionTable('#file-extension-table');
            }

            if($('#assign-file-extension-modal').length){
                assignFileExtensionForm();
            }

            $(document).on('click','#assign-file-extension',function() {
                $('#assign-file-extension-modal').modal('show');
                
                assignFileExtensionTable('#assign-file-extension-table');
            });

            $(document).on('click','.delete-file-extension',function() {
                const upload_setting_id = $(this).data('upload-setting-id');
                const file_extension_id = $(this).data('file-extension-id');
                const transaction = 'delete upload setting file extension';
        
                Swal.fire({
                    title: 'Confirm File Extension Deletion',
                    text: 'Are you sure you want to delete this file extension?',
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
                            url: 'controller/upload-setting-controller.php',
                            dataType: 'json',
                            data: {
                                upload_setting_id : upload_setting_id, 
                                file_extension_id : file_extension_id, 
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Delete File Extension Success', 'The file extension has been deleted successfully.', 'success');
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else if (response.notExist) {
                                        showNotification('Delete File Extension Error', 'The file extension does not exist.', 'danger');
                                    }
                                    else {
                                        showNotification('Delete File Extension Error', response.message, 'danger');
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
                                reloadDatatable('#file-extension-table');
                            }
                        });
                        return false;
                    }
                });
            });
        }

        $(document).on('click','.delete-upload-setting',function() {
            const upload_setting_id = $(this).data('upload-setting-id');
            const transaction = 'delete upload setting';
    
            Swal.fire({
                title: 'Confirm Upload Setting Deletion',
                text: 'Are you sure you want to delete this upload setting?',
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
                        url: 'controller/upload-setting-controller.php',
                        dataType: 'json',
                        data: {
                            upload_setting_id : upload_setting_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete Upload Setting Success', 'The upload setting has been deleted successfully.', 'success');
                                reloadDatatable('#upload-setting-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Delete Upload Setting Error', 'The upload setting does not exist.', 'danger');
                                    reloadDatatable('#upload-setting-table');
                                }
                                else {
                                    showNotification('Delete Upload Setting Error', response.message, 'danger');
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

        $(document).on('click','#delete-upload-setting',function() {
            let upload_setting_id = [];
            const transaction = 'delete multiple upload setting';

            $('.datatable-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    upload_setting_id.push(element.value);
                }
            });
    
            if(upload_setting_id.length > 0){
                Swal.fire({
                    title: 'Confirm Multiple Upload Settings Deletion',
                    text: 'Are you sure you want to delete these upload settings?',
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
                            url: 'controller/upload-setting-controller.php',
                            dataType: 'json',
                            data: {
                                upload_setting_id: upload_setting_id,
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Delete Upload Setting Success', 'The selected upload settings have been deleted successfully.', 'success');
                                        reloadDatatable('#upload-setting-table');
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else {
                                        showNotification('Delete Upload Setting Error', response.message, 'danger');
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
                showNotification('Deletion Multiple Upload Setting Error', 'Please select the upload settings you wish to delete.', 'danger');
            }
        });

        $(document).on('click','#delete-upload-setting-details',function() {
            const upload_setting_id = $(this).data('upload-setting-id');
            const transaction = 'delete upload setting';
    
            Swal.fire({
                title: 'Confirm Upload Setting Deletion',
                text: 'Are you sure you want to delete this upload setting?',
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
                        url: 'controller/upload-setting-controller.php',
                        dataType: 'json',
                        data: {
                            upload_setting_id : upload_setting_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Deleted Upload Setting Success', 'The upload setting has been deleted successfully.', 'success');
                                window.location = 'upload-setting.php';
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
                                    showNotification('Delete Upload Setting Error', response.message, 'danger');
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
            discardCreate('upload-setting.php');
        });

        $(document).on('click','#edit-form',function() {
            displayDetails('get upload setting details');

            enableForm();
        });

        $(document).on('click','#duplicate-upload-setting',function() {
            const upload_setting_id = $(this).data('upload-setting-id');
            const transaction = 'duplicate upload setting';
    
            Swal.fire({
                title: 'Confirm Upload Setting Duplication',
                text: 'Are you sure you want to duplicate this upload setting?',
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
                        url: 'controller/upload-setting-controller.php',
                        dataType: 'json',
                        data: {
                            upload_setting_id : upload_setting_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Duplicate Upload Setting Success', 'The upload setting has been duplicated successfully.', 'success');
                                window.location = 'upload-setting.php?id=' + response.uploadSettingID;
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Duplicate Upload Setting Error', 'The upload setting does not exist.', 'danger');
                                    reloadDatatable('#upload-setting-table');
                                }
                                else {
                                    showNotification('Duplicate Upload Setting Error', response.message, 'danger');
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

function uploadSettingTable(datatable_name, buttons = false, show_all = false){
    const type = 'upload setting table';
    var settings;

    const column = [ 
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'UPLOAD_SETTING_NAME' },
        { 'data' : 'MAX_FILE_SIZE' },
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
            'url' : 'view/_upload_setting_generation.php',
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

function fileExtensionTable(datatable_name, buttons = false, show_all = false){
    const upload_setting_id = $('#upload-setting-id').text();
    const type = 'upload setting file extension table';
    var settings;

    const column = [ 
        { 'data' : 'FILE_EXTENSION_NAME' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '80%', 'aTargets': 0 },
        { 'width': '20%','bSortable': false, 'aTargets': 1 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_file_extension_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {'type' : type, 'upload_setting_id' : upload_setting_id},
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

function assignFileExtensionTable(datatable_name, buttons = false, show_all = false){
    const upload_setting_id = $('#upload-setting-id').text();
    const type = 'assign upload setting file extension table';
    var settings;

    const column = [ 
        { 'data' : 'FILE_EXTENSION_NAME' },
        { 'data' : 'ASSIGN' }
    ];

    const column_definition = [
        { 'width': '90%', 'aTargets': 0 },
        { 'width': '10%', 'bSortable': false, 'aTargets': 1 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[-1], ['All']];

    settings = {
        'ajax': { 
            'url' : 'view/_file_extension_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {'type' : type, 'upload_setting_id' : upload_setting_id},
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

function uploadSettingForm(){
    $('#upload-setting-form').validate({
        rules: {
            upload_setting_name: {
                required: true
            },
            upload_setting_description: {
                required: true
            },
            max_file_size: {
                required: true
            }
        },
        messages: {
            upload_setting_name: {
                required: 'Please enter the upload setting name'
            },
            upload_setting_description: {
                required: 'Please enter the upload setting description'
            },
            max_file_size: {
                required: 'Please enter the max file size'
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
            const upload_setting_id = $('#upload-setting-id').text();
            const transaction = 'save upload setting';
        
            $.ajax({
                type: 'POST',
                url: 'controller/upload-setting-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&upload_setting_id=' + upload_setting_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-data');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert Upload Setting Success' : 'Update Upload Setting Success';
                        const notificationDescription = response.insertRecord ? 'The upload setting has been inserted successfully.' : 'The upload setting has been updated successfully.';
                        
                        setNotification(notificationMessage, notificationDescription, 'success');
                        window.location = 'upload-setting.php?id=' + response.uploadSettingID;
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

function assignFileExtensionForm(){
    $('#assign-file-extension-form').validate({
        submitHandler: function(form) {
            const transaction = 'assign upload setting file extension';
            const upload_setting_id = $('#upload-setting-id').text();

            var file_extension_id = [];

            $('.upload-setting-file-extension').each(function(){
                if ($(this).is(':checked')){  
                    file_extension_id.push(this.value);  
                }
            });

            $.ajax({
                type: 'POST',
                url: 'controller/upload-setting-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&upload_setting_id=' + upload_setting_id + '&file_extension_id=' + file_extension_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-add-upload-setting-role-access');
                },
                success: function(response) {
                    if (response.success) {
                        showNotification('Assign File Extension Success', 'The file extension has been assigned successfully.', 'success');
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
                    enableFormSubmitButton('submit-assign-file-extension', 'Submit');
                    $('#assign-file-extension-modal').modal('hide');
                    reloadDatatable('#file-extension-table');
                }
            });
            return false;
        }
    });
}

function displayDetails(transaction){
    switch (transaction) {
        case 'get upload setting details':
            const upload_setting_id = $('#upload-setting-id').text();
            
            $.ajax({
                url: 'controller/upload-setting-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    upload_setting_id : upload_setting_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetModalForm('upload-setting-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#upload_setting_id').val(upload_setting_id);
                        $('#upload_setting_name').val(response.uploadSettingName);
                        $('#upload_setting_description').val(response.uploadSettingDescription);
                        $('#max_file_size').val(response.maxFileSize);

                        $('#upload_setting_name_label').text(response.uploadSettingName);
                        $('#upload_setting_description_label').text(response.uploadSettingDescription);
                        $('#max_file_size_label').text(response.maxFileSize + ' mb');
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Upload Setting Details Error', response.message, 'danger');
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