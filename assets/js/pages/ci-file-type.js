(function($) {
    'use strict';

    $(function() {
        if($('#ci-file-type-table').length){
            ciFileTypeTable('#ci-file-type-table');
        }

        if($('#ci-file-type-form').length){
            ciFileTypeForm();
        }

        if($('#ci-file-type-id').length){
            displayDetails('get ci file type details');
        }

        $(document).on('click','.delete-ci-file-type',function() {
            const ci_file_type_id = $(this).data('ci-file-type-id');
            const transaction = 'delete ci file type';
    
            Swal.fire({
                title: 'Confirm CI File Type Deletion',
                text: 'Are you sure you want to delete this ci file type?',
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
                        url: 'controller/ci-file-type-controller.php',
                        dataType: 'json',
                        data: {
                            ci_file_type_id : ci_file_type_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete CI File Type Success', 'The ci file type has been deleted successfully.', 'success');
                                reloadDatatable('#ci-file-type-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Delete CI File Type Error', 'The ci file type does not exist.', 'danger');
                                    reloadDatatable('#ci-file-type-table');
                                }
                                else {
                                    showNotification('Delete CI File Type Error', response.message, 'danger');
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

        $(document).on('click','#delete-ci-file-type',function() {
            let ci_file_type_id = [];
            const transaction = 'delete multiple ci file type';

            $('.datatable-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    ci_file_type_id.push(element.value);
                }
            });
    
            if(ci_file_type_id.length > 0){
                Swal.fire({
                    title: 'Confirm Multiple CI File Types Deletion',
                    text: 'Are you sure you want to delete these ci file types?',
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
                            url: 'controller/ci-file-type-controller.php',
                            dataType: 'json',
                            data: {
                                ci_file_type_id: ci_file_type_id,
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Delete CI File Type Success', 'The selected ci file types have been deleted successfully.', 'success');
                                    reloadDatatable('#ci-file-type-table');
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else {
                                        showNotification('Delete CI File Type Error', response.message, 'danger');
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
                showNotification('Deletion Multiple CI File Type Error', 'Please select the ci file types you wish to delete.', 'danger');
            }
        });

        $(document).on('click','#delete-ci-file-type-details',function() {
            const ci_file_type_id = $('#ci-file-type-id').text();
            const transaction = 'delete ci file type';
    
            Swal.fire({
                title: 'Confirm CI File Type Deletion',
                text: 'Are you sure you want to delete this ci file type?',
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
                        url: 'controller/ci-file-type-controller.php',
                        dataType: 'json',
                        data: {
                            ci_file_type_id : ci_file_type_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Deleted CI File Type Success', 'The ci file type has been deleted successfully.', 'success');
                                window.location = 'ci-file-type.php';
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
                                    showNotification('Delete CI File Type Error', response.message, 'danger');
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
            discardCreate('ci-file-type.php');
        });

        $(document).on('click','#edit-form',function() {
            displayDetails('get ci file type details');

            enableForm();
        });

        $(document).on('click','#duplicate-ci-file-type',function() {
            const ci_file_type_id = $('#ci-file-type-id').text();
            const transaction = 'duplicate ci file type';
    
            Swal.fire({
                title: 'Confirm CI File Type Duplication',
                text: 'Are you sure you want to duplicate this ci file type?',
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
                        url: 'controller/ci-file-type-controller.php',
                        dataType: 'json',
                        data: {
                            ci_file_type_id : ci_file_type_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Duplicate CI File Type Success', 'The ci file type has been duplicated successfully.', 'success');
                                window.location = 'ci-file-type.php?id=' + response.ciFileTypeID;
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Duplicate CI File Type Error', 'The ci file type does not exist.', 'danger');
                                    reloadDatatable('#ci-file-type-table');
                                }
                                else {
                                    showNotification('Duplicate CI File Type Error', response.message, 'danger');
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

function ciFileTypeTable(datatable_name, buttons = false, show_all = false){
    const type = 'ci file type table';
    var settings;

    const column = [ 
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'CI_FILE_TYPE_NAME' },
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
            'url' : 'view/_ci_file_type_generation.php',
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

function ciFileTypeForm(){
    $('#ci-file-type-form').validate({
        rules: {
            ci_file_type_name: {
                required: true
            },
        },
        messages: {
            ci_file_type_name: {
                required: 'Please enter the ci file type name'
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
            const ci_file_type_id = $('#ci-file-type-id').text();
            const transaction = 'save ci file type';
        
            $.ajax({
                type: 'POST',
                url: 'controller/ci-file-type-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&ci_file_type_id=' + ci_file_type_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-data');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert CI File Type Success' : 'Update CI File Type Success';
                        const notificationDescription = response.insertRecord ? 'The ci file type has been inserted successfully.' : 'The ci file type has been updated successfully.';
                        
                        setNotification(notificationMessage, notificationDescription, 'success');
                        window.location = 'ci-file-type.php?id=' + response.ciFileTypeID;
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
        case 'get ci file type details':
            const ci_file_type_id = $('#ci-file-type-id').text();
            
            $.ajax({
                url: 'controller/ci-file-type-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    ci_file_type_id : ci_file_type_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetForm('ci-file-type-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#ci_file_type_id').val(ci_file_type_id);
                        $('#ci_file_type_name').val(response.ciFileTypeName);

                        $('#ci_file_type_name_label').text(response.ciFileTypeName);
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get CI File Type Details Error', response.message, 'danger');
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