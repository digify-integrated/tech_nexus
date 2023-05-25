(function($) {
    'use strict';

    $(function() {
        initializeFileTypeForm();

        if($('#file-type-id').length){
            displayDetails('file types details');

            if($('#file-extension-table').length){
                initializeFileExtensionTable('#file-extension-table');
            }

            if($('#file-extension-modal').length){
                initializeFileExtensionForm();
            }
        }
        
        $(document).on('click','#discard-create',function() {
            discardCreate('file-types.php');
        });

        $(document).on('click','#edit-form',function() {
            displayDetails('file types details');
        });

        $(document).on('click','#delete-file-type',function() {
            const email_account = $('#email_account').text();
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
                        url: 'controller.php',
                        data: {email_account : email_account, file_type_id : file_type_id, transaction : transaction},
                        success: function (response) {
                            switch (response) {
                                case 'Deleted':
                                    setNotification('Deleted File Type Success', 'The file type has been deleted successfully.', 'success');
                                    window.location = 'file-types.php';
                                    break;
                                case 'Not Found':
                                    setNotification('Delete File Type Error', 'The file type does not exist.', 'danger');
                                    window.location = '404.php';
                                    break;
                                case 'User Not Found':
                                case 'Inactive User':
                                    window.location = 'logout.php?logout';
                                    break;
                                default:
                                    show_toastr('Delete File Type Error', response, 'danger');
                                    break;
                            }
                        }
                    });
                    return false;
                }
            });
        });

        $(document).on('click','#duplicate-file-type',function() {
            const email_account = $('#email_account').text();
            const file_type_id = $(this).data('file-type-id');
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
                        url: 'controller.php',
                        data: {email_account : email_account, file_type_id : file_type_id, transaction : transaction},
                        dataType: 'JSON',
                        success: function (response) {
                            switch (response[0]['RESPONSE']) {
                                case 'Duplicated':
                                    setNotification('Duplicate File Type Success', 'The file type has been duplicated successfully.', 'success');
                                    window.location = 'file-type-form.php?id=' + response[0]['MENU_GROUP_ID'];
                                    break;
                                case 'Not Found':
                                    setNotification('Duplicate File Type Error', 'The source file type does not exist.', 'danger');
                                    window.location = '404.php';
                                    break;
                                case 'User Not Found':
                                case 'Inactive User':
                                    window.location = 'logout.php?logout';
                                    break;
                                default:
                                    setNotification('Transaction Error', response[0]['RESPONSE'], 'danger');
                                    break;
                            }
                        }
                    });
                    return false;
                }
            });
        });

        $(document).on('click','#create-file-extension',function() {
            $('#file-extension-modal').modal('show');

            resetModalForm("file-extension-form");
        });

        $(document).on('click','.assign-file-extension-role-access',function() {
            const menu_item_id = $(this).data('file-extension-id');

            sessionStorage.setItem('menu_item_id', menu_item_id);

            $('#assign-file-extension-role-access-modal').modal('show');
            initializeAssignFileExtensionRoleAccessTable('#assign-file-extension-role-access-table');
        });

        $(document).on('click','.update-file-extension',function() {
            const menu_item_id = $(this).data('file-extension-id');
    
            sessionStorage.setItem('menu_item_id', menu_item_id);
            
            displayDetails('modal file extension details');
    
            $('#file-extension-modal').modal('show');
        });

        $(document).on('click','.delete-file-extension',function() {
            const email_account = $('#email_account').text();
            const menu_item_id = $(this).data('file-extension-id');
            const transaction = 'delete file extension';
    
            Swal.fire({
                title: 'Confirm Menu Item Deletion',
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
                        url: 'controller.php',
                        data: {email_account : email_account, menu_item_id : menu_item_id, transaction : transaction},
                        success: function (response) {
                            switch (response) {
                                case 'Deleted':
                                    showNotification('Delete Menu Item Success', 'The file extension has been deleted successfully.', 'success');
                                    reloadDatatable('#file-extension-table');
                                    break;
                                case 'Not Found':
                                    showNotification('Delete Menu Item Error', 'The file extension does not exist or has already been deleted.', 'warning');
                                    reloadDatatable('#file-extension-table');
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

function initializeFileExtensionTable(datatable_name, buttons = false, show_all = false){
    const file_type_id = $('#file-type-id').text();
    const email_account = $('#email_account').text();
    const type = 'file type file extension table';
    var settings;

    const column = [ 
        { 'data' : 'FILE_EXTENSION_ID' },
        { 'data' : 'FILE_EXTENSION_NAME' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '10%', 'aTargets': 0 },
        { 'width': '75%', 'aTargets': 1 },
        { 'width': '15%','bSortable': false, 'aTargets': 4 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'system-generation.php',
            'method' : 'POST',
            'dataType': 'JSON',
            'data': {'type' : type, 'email_account' : email_account, 'file_type_id' : file_type_id},
            'dataSrc' : ''
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

function initializeFileTypeForm(){
    $('#file-type-form').validate({
        rules: {
            file_type_name: {
                required: true
            }
        },
        messages: {
            file_type_name: {
                required: 'Please enter the file type'
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
            const email_account = $('#email_account').text();
            const transaction = 'submit file type';
          
            $.ajax({
                type: 'POST',
                url: 'controller.php',
                data: $(form).serialize() + '&email_account=' + email_account + '&transaction=' + transaction,
                dataType: 'JSON',
                beforeSend: function() {
                    disableFormSubmitButton('submit-data');
                },
                success: function (response) {
                    switch (response[0]['RESPONSE']) {
                        case 'Inserted':
                            setNotification('Insert File Type Success', 'The file type has been inserted successfully.', 'success');
                            window.location = window.location.href + '?id=' + response[0]['FILE_TYPE_ID'];
                            break;
                        case 'Updated':
                            setNotification('Update File Type Success', 'The file type has been updated successfully.', 'success');
                            window.location.reload();
                            break;
                        case 'User Not Found':
                        case 'Inactive User':
                            window.location = 'logout.php?logout';
                            break;
                        default:
                            setNotification('Transaction Error', response, 'danger');
                            break;
                    }
                },
                complete: function() {
                    enableFormSubmitButton('submit-data', 'Save');
                }
            });
        
            return false;
        }
    });
}

function initializeFileExtensionForm(){
    $('#file-extension-form').validate({
        rules: {
            menu_item_name: {
                required: true
            },
            menu_item_order_sequence: {
                required: true
            }
        },
        messages: {
            menu_item_name: {
                required: 'Please enter the file extension name'
            },
            menu_item_order_sequence: {
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
            const email_account = $('#email_account').text();
            const file_type_id = $('#file-type-id').text();
            const transaction = 'submit file extension';
        
            $.ajax({
                type: 'POST',
                url: 'controller.php',
                data: $(form).serialize() + '&email_account=' + email_account + '&file_type_id=' + file_type_id + '&transaction=' + transaction,
                dataType: 'JSON',
                beforeSend: function() {
                    disableFormSubmitButton('submit-data');
                },
                success: function (response) {
                    switch (response[0]['RESPONSE']) {
                        case 'Inserted':
                            showNotification('Insert Menu Item Success', 'The file extension has been inserted successfully.', 'success');
                            break;
                        case 'Updated':
                            showNotification('Update Menu Item Success', 'The file extension has been updated successfully.', 'success');
                            break;
                        case 'User Not Found':
                        case 'Inactive User':
                            window.location = 'logout.php?logout';
                            break;
                        default:
                            showNotification('Transaction Error', response, 'danger');
                            break;
                    }
                },
                complete: function() {
                    enableFormSubmitButton('submit-form', 'Submit');
                    $('#file-extension-modal').modal('hide');
                    reloadDatatable('#file-extension-table');
                    resetModalForm('file-extension-form');
                }
            });
        
            return false;
        }
    });
}