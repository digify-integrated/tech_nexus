(function($) {
    'use strict';

    $(function() {
        if($('#file-extension-table').length){
            fileExtensionTable('#file-extension-table');
        }

        if($('#file-extension-form').length){
            fileExtensionForm();
        }

        if($('#file-extension-id').length){
            displayDetails('get file extension details');
        }

        $(document).on('click','.delete-file-extension',function() {
            const file_extension_id = $(this).data('file-extension-id');
            const transaction = 'delete file extension';
    
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
                        url: 'controller/file-extension-controller.php',
                        dataType: 'json',
                        data: {
                            file_extension_id : file_extension_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete File Extension Success', 'The file extension has been deleted successfully.', 'success');
                                reloadDatatable('#file-extension-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Delete File Extension Error', 'The file extension does not exist.', 'danger');
                                    reloadDatatable('#file-extension-table');
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
                        }
                    });
                    return false;
                }
            });
        });

        $(document).on('click','#delete-file-extension',function() {
            let file_extension_id = [];
            const transaction = 'delete multiple file extension';

            $('.datatable-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    file_extension_id.push(element.value);
                }
            });
    
            if(file_extension_id.length > 0){
                Swal.fire({
                    title: 'Confirm Multiple File Extensions Deletion',
                    text: 'Are you sure you want to delete these file extensions?',
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
                            url: 'controller/file-extension-controller.php',
                            dataType: 'json',
                            data: {
                                file_extension_id: file_extension_id,
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Delete File Extension Success', 'The selected file extensions have been deleted successfully.', 'success');
                                        reloadDatatable('#file-extension-table');
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
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
                                toggleHideActionDropdown();
                            }
                        });
                        
                        return false;
                    }
                });
            }
            else{
                showNotification('Deletion Multiple File Extension Error', 'Please select the file extensions you wish to delete.', 'danger');
            }
        });

        $(document).on('click','#delete-file-extension-details',function() {
            const file_extension_id = $('#file-extension-id').text();
            const transaction = 'delete file extension';
    
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
                        url: 'controller/file-extension-controller.php',
                        dataType: 'json',
                        data: {
                            file_extension_id : file_extension_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Deleted File Extension Success', 'The file extension has been deleted successfully.', 'success');
                                window.location = 'file-extension.php';
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
                        }
                    });
                    return false;
                }
            });
        });

        $(document).on('click','#discard-create',function() {
            discardCreate('file-extension.php');
        });
        
        $(document).on('click','#edit-form',function() {
            displayDetails('get file extension details');

            enableForm();
        });

        $(document).on('click','#duplicate-file-extension',function() {
            const file_extension_id = $('#file-extension-id').text();
            const transaction = 'duplicate file extension';
    
            Swal.fire({
                title: 'Confirm File Extension Duplication',
                text: 'Are you sure you want to duplicate this file extension?',
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
                        url: 'controller/file-extension-controller.php',
                        dataType: 'json',
                        data: {
                            file_extension_id : file_extension_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Duplicate File Extension Success', 'The file extension has been duplicated successfully.', 'success');
                                window.location = 'file-extension.php?id=' + response.fileExtensionID;
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Duplicate File Extension Error', 'The file extension does not exist.', 'danger');
                                    reloadDatatable('#file-extension-table');
                                }
                                else {
                                    showNotification('Duplicate File Extension Error', response.message, 'danger');
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

        $(document).on('click','#create-file-extension',function() {
            resetForm("file-extension-form");

            $('#file-extension-modal').modal('show');
        });
    });
})(jQuery);

function fileExtensionTable(datatable_name, buttons = false, show_all = false){
    const type = 'file extension table';
    var settings;

    const column = [ 
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'FILE_EXTENSION_NAME' },
        { 'data' : 'FILE_TYPE_NAME' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '2%','bSortable': false, 'aTargets': 0 },
        { 'width': '41%', 'aTargets': 1 },
        { 'width': '41%', 'aTargets': 2 },
        { 'width': '15%','bSortable': false, 'aTargets': 3 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_file_extension_generation.php',
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

function fileExtensionForm(){
    $('#file-extension-form').validate({
        rules: {
            file_extension_name: {
                required: true
            },
            file_type_id: {
                required: true
            }
        },
        messages: {
            file_extension_name: {
                required: 'Please enter the file extension name'
            },
            file_type_id: {
                required: 'Please choose the file type'
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
            const file_extension_id = $('#file-extension-id').text();
            const transaction = 'save file extension';
        
            $.ajax({
                type: 'POST',
                url: 'controller/file-extension-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&file_extension_id=' + file_extension_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-data');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert File Extension Success' : 'Update File Extension Success';
                        const notificationDescription = response.insertRecord ? 'The file extension has been inserted successfully.' : 'The file extension has been updated successfully.';
                        
                        setNotification(notificationMessage, notificationDescription, 'success');
                        window.location = 'file-extension.php?id=' + response.fileExtensionID;
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
                }
            });
        
            return false;
        }
    });
}

function displayDetails(transaction){
    switch (transaction) {
        case 'get file extension details':
            const file_extension_id = $('#file-extension-id').text();
            
            $.ajax({
                url: 'controller/file-extension-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    file_extension_id : file_extension_id, 
                    transaction : transaction
                },
                success: function(response) {
                    if (response.success) {
                        $('#file_extension_id').val(file_extension_id);
                        $('#file_extension_name').val(response.fileExtensionName);
                        
                        $('#file_extension_name_label').text(response.fileExtensionName);
                        
                        document.getElementById('file_type_id_label').innerHTML = response.fileTypeName;
                        
                        checkOptionExist('#file_type_id', response.fileTypeID, '');
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get File Extension Details Error', response.message, 'danger');
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