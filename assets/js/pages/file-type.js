(function($) {
    'use strict';

    $(function() {
        if($('#file-type-table').length){
            fileTypeTable('#file-type-table');
        }

        if($('#file-type-form').length){
            fileTypeForm();
        }

        if($('#file-type-id').length){
            displayDetails('get file type details');

            if($('#file-extension-table').length){
                fileExtensionTable('#file-extension-table');
            }

            if($('#file-extension-form').length){
                fileExtensionForm();
            }

            $(document).on('click','#add-file-extension',function() {
                resetModalForm("file-extension-form");

                $('#file-extension-modal').modal('show');
            });

            $(document).on('click','.update-file-extension',function() {
                const file_extension_id = $(this).data('file-extension-id');
        
                sessionStorage.setItem('file_extension_id', file_extension_id);
                
                displayDetails('get file extension details');
        
                $('#file-extension-modal').modal('show');
            });

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
        }

        $(document).on('click','.delete-file-type',function() {
            const file_type_id = $(this).data('file-type-id');
            const transaction = 'delete file type';
    
            Swal.fire({
                title: 'Confirm File Type Deletion',
                text: 'Are you sure you want to delete this file type?',
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
                        url: 'controller/file-type-controller.php',
                        dataType: 'json',
                        data: {
                            file_type_id : file_type_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete File Type Success', 'The file type has been deleted successfully.', 'success');
                                reloadDatatable('#file-type-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Delete File Type Error', 'The file type does not exist.', 'danger');
                                    reloadDatatable('#file-type-table');
                                }
                                else {
                                    showNotification('Delete File Type Error', response.message, 'danger');
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

        $(document).on('click','#delete-file-type',function() {
            let file_type_id = [];
            const transaction = 'delete multiple file type';

            $('.datatable-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    file_type_id.push(element.value);
                }
            });
    
            if(file_type_id.length > 0){
                Swal.fire({
                    title: 'Confirm Multiple File Types Deletion',
                    text: 'Are you sure you want to delete these file types?',
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
                            url: 'controller/file-type-controller.php',
                            dataType: 'json',
                            data: {
                                file_type_id: file_type_id,
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Delete File Type Success', 'The selected file types have been deleted successfully.', 'success');
                                        reloadDatatable('#file-type-table');
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else {
                                        showNotification('Delete File Type Error', response.message, 'danger');
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
                showNotification('Deletion Multiple File Type Error', 'Please select the file types you wish to delete.', 'danger');
            }
        });

        $(document).on('click','#delete-file-type-details',function() {
            const file_type_id = $('#file-type-id').text();
            const transaction = 'delete file type';
    
            Swal.fire({
                title: 'Confirm File Type Deletion',
                text: 'Are you sure you want to delete this file type?',
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
                        url: 'controller/file-type-controller.php',
                        dataType: 'json',
                        data: {
                            file_type_id : file_type_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Deleted File Type Success', 'The file type has been deleted successfully.', 'success');
                                window.location = 'file-type.php';
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
                                    showNotification('Delete File Type Error', response.message, 'danger');
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
            discardCreate('file-type.php');
        });

        $(document).on('click','#edit-form',function() {
            displayDetails('get file type details');

            enableForm();
        });

        $(document).on('click','#duplicate-file-type',function() {
            const file_type_id = $('#file-type-id').text();
            const transaction = 'duplicate file type';
    
            Swal.fire({
                title: 'Confirm File Type Duplication',
                text: 'Are you sure you want to duplicate this file type?',
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
                        url: 'controller/file-type-controller.php',
                        dataType: 'json',
                        data: {
                            file_type_id : file_type_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Duplicate File Type Success', 'The file type has been duplicated successfully.', 'success');
                                window.location = 'file-type.php?id=' + response.fileTypeID;
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Duplicate File Type Error', 'The file type does not exist.', 'danger');
                                    reloadDatatable('#file-type-table');
                                }
                                else {
                                    showNotification('Duplicate File Type Error', response.message, 'danger');
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

function fileTypeTable(datatable_name, buttons = false, show_all = false){
    const type = 'file type table';
    var settings;

    const column = [ 
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'FILE_TYPE_NAME' },
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
            'url' : 'view/_file_type_generation.php',
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
    const file_type_id = $('#file-type-id').text();
    const type = 'file type file extension table';
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
            'data': {'type' : type, 'file_type_id' : file_type_id},
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

function fileTypeForm(){
    $('#file-type-form').validate({
        rules: {
            file_type_name: {
                required: true
            }
        },
        messages: {
            file_type_name: {
                required: 'Please enter the file type name'
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
            const file_type_id = $('#file-type-id').text();
            const transaction = 'save file type';
        
            $.ajax({
                type: 'POST',
                url: 'controller/file-type-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&file_type_id=' + file_type_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-data');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert File Type Success' : 'Update File Type Success';
                        const notificationDescription = response.insertRecord ? 'The file type has been inserted successfully.' : 'The file type has been updated successfully.';
                        
                        setNotification(notificationMessage, notificationDescription, 'success');
                        window.location = 'file-type.php?id=' + response.fileTypeID;
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

function fileExtensionForm(){
    $('#file-extension-form').validate({
        rules: {
            file_extension_name: {
                required: true
            }
        },
        messages: {
            file_extension_name: {
                required: 'Please enter the file extension name'
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
            const file_type_id = $('#file-type-id').text();
            const transaction = 'save file extension';
        
            $.ajax({
                type: 'POST',
                url: 'controller/file-extension-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&file_type_id=' + file_type_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-file-extension-form');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert File Extension Success' : 'Update File Extension Success';
                        const notificationDescription = response.insertRecord ? 'The file extension has been inserted successfully.' : 'The file extension has been updated successfully.';
                        
                        showNotification(notificationMessage, notificationDescription, 'success');
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
                    var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                    if (xhr.responseText) {
                        fullErrorMessage += `, Response: ${xhr.responseText}`;
                    }
                    showErrorDialog(fullErrorMessage);
                },
                complete: function() {
                    enableFormSubmitButton('submit-file-extension-form', 'Submit');
                    $('#file-extension-modal').modal('hide');
                    reloadDatatable('#file-extension-table');
                    resetModalForm('file-extension-form');
                }
            });
        
            return false;
        }
    });
}

function displayDetails(transaction){
    switch (transaction) {
        case 'get file type details':
            const file_type_id = $('#file-type-id').text();
            
            $.ajax({
                url: 'controller/file-type-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    file_type_id : file_type_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetModalForm('file-type-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#file_type_id').val(file_type_id);
                        $('#file_type_name').val(response.fileTypeName);

                        $('#file_type_name_label').text(response.fileTypeName);
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get File Type Details Error', response.message, 'danger');
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
        case 'get file extension details':
                var file_extension_id = sessionStorage.getItem('file_extension_id');
                
                $.ajax({
                    url: 'controller/file-extension-controller.php',
                    method: 'POST',
                    dataType: 'json',
                    data: {
                        file_extension_id : file_extension_id, 
                        transaction : transaction
                    },
                    beforeSend: function() {
                        resetModalForm('file-extension-form');
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#file_extension_id').val(file_extension_id);
                            $('#file_extension_name').val(response.fileExtensionName);
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