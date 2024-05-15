(function($) {
    'use strict';

    $(function() {
        if($('#contact-directory-table').length){
            contactDirectoryTable('#contact-directory-table');
        }

        if($('#contact-directory-form').length){
            contactDirectoryForm();
        }

        if($('#contact-directory-id').length){
            displayDetails('get contact directory details');
        }

        $(document).on('click','.delete-contact-directory',function() {
            const contact_directory_id = $(this).data('contact-directory-id');
            const transaction = 'delete contact directory';
    
            Swal.fire({
                title: 'Confirm Contact Directory Deletion',
                text: 'Are you sure you want to delete this contact directory?',
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
                        url: 'controller/contact-directory-controller.php',
                        dataType: 'json',
                        data: {
                            contact_directory_id : contact_directory_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete Contact Directory Success', 'The contact directory has been deleted successfully.', 'success');
                                reloadDatatable('#contact-directory-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Delete Contact Directory Error', 'The contact directory does not exist.', 'danger');
                                    reloadDatatable('#contact-directory-table');
                                }
                                else {
                                    showNotification('Delete Contact Directory Error', response.message, 'danger');
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

        $(document).on('click','#delete-contact-directory',function() {
            let contact_directory_id = [];
            const transaction = 'delete multiple contact directory';

            $('.datatable-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    contact_directory_id.push(element.value);
                }
            });
    
            if(contact_directory_id.length > 0){
                Swal.fire({
                    title: 'Confirm Multiple Contact Directories Deletion',
                    text: 'Are you sure you want to delete these contact directories?',
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
                            url: 'controller/contact-directory-controller.php',
                            dataType: 'json',
                            data: {
                                contact_directory_id: contact_directory_id,
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Delete Contact Directory Success', 'The selected contact directories have been deleted successfully.', 'success');
                                    reloadDatatable('#contact-directory-table');
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else {
                                        showNotification('Delete Contact Directory Error', response.message, 'danger');
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
                showNotification('Deletion Multiple Contact Directory Error', 'Please select the contact directories you wish to delete.', 'danger');
            }
        });

        $(document).on('click','#delete-contact-directory-details',function() {
            const contact_directory_id = $('#contact-directory-id').text();
            const transaction = 'delete contact directory';
    
            Swal.fire({
                title: 'Confirm Contact Directory Deletion',
                text: 'Are you sure you want to delete this contact directory?',
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
                        url: 'controller/contact-directory-controller.php',
                        dataType: 'json',
                        data: {
                            contact_directory_id : contact_directory_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Deleted Contact Directory Success', 'The contact directory has been deleted successfully.', 'success');
                                window.location = 'contact-directory.php';
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
                                    showNotification('Delete Contact Directory Error', response.message, 'danger');
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
            discardCreate('contact-directory.php');
        });

        $(document).on('click','#edit-form',function() {
            displayDetails('get contact directory details');

            enableForm();
        });
    });
})(jQuery);

function contactDirectoryTable(datatable_name, buttons = false, show_all = false){
    const type = 'contact directory table';
    var settings;

    const column = [ 
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'CONTACT_DIRECTORY' },
        { 'data' : 'LOCATION' },
        { 'data' : 'CONTACT_INFORMATION' },
        { 'data' : 'DIRECTORY_TYPE' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '1%','bSortable': false, 'aTargets': 0 },
        { 'width': 'auto', 'aTargets': 1 },
        { 'width': 'auto', 'aTargets': 2 },
        { 'width': 'auto', 'aTargets': 3 },
        { 'width': 'auto', 'aTargets': 4 },
        { 'width': '15%','bSortable': false, 'aTargets': 5 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_contact_directory_generation.php',
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

function contactDirectoryForm(){
    $('#contact-directory-form').validate({
        rules: {
            contact_name: {
                required: true
            },
            location: {
                required: true
            },
            directory_type: {
                required: true
            },
            contact_information: {
                required: true
            },
        },
        messages: {
            contact_name: {
                required: 'Please enter the name'
            },
            location: {
                required: 'Please enter the location'
            },
            directory_type: {
                required: 'Please enter the directory type'
            },
            contact_information: {
                required: 'Please enter the contact information'
            },
        },
        errorPlacement: function (error, element) {
            if (element.hasClass('select2') || element.hasClass('modal-select2') || element.hasClass('offcanvas-select2')) {
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
            const contact_directory_id = $('#contact-directory-id').text();
            const transaction = 'save contact directory';
        
            $.ajax({
                type: 'POST',
                url: 'controller/contact-directory-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&contact_directory_id=' + contact_directory_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-data');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert Contact Directory Success' : 'Update Contact Directory Success';
                        const notificationDescription = response.insertRecord ? 'The contact directory has been inserted successfully.' : 'The contact directory has been updated successfully.';
                        
                        setNotification(notificationMessage, notificationDescription, 'success');
                        window.location = 'contact-directory.php?id=' + response.contactDirectoryID;
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
        case 'get contact directory details':
            const contact_directory_id = $('#contact-directory-id').text();
            
            $.ajax({
                url: 'controller/contact-directory-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    contact_directory_id : contact_directory_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetForm('contact-directory-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#contact_directory_id').val(contact_directory_id);
                        $('#contact_name').val(response.contactName);
                        $('#position').val(response.position);
                        $('#contact_information').val(response.contactInformation);

                        checkOptionExist('#location', response.location, '');
                        checkOptionExist('#directory_type', response.directoryType, '');

                        $('#contact_name_label').text(response.contactName);
                        $('#position_label').text(response.position);
                        $('#location_label').text(response.location);
                        $('#directory_type_label').text(response.directoryType);
                        $('#contact_information_label').text(response.contactInformation);
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Contact Directory Details Error', response.message, 'danger');
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