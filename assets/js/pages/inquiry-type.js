(function($) {
    'use strict';

    $(function() {
        if($('#inquiry-type-table').length){
            inquiryTypeTable('#inquiry-type-table');
        }

        if($('#inquiry-type-form').length){
            inquiryTypeForm();
        }

        if($('#inquiry-type-id').length){
            displayDetails('get inquiry type details');
        }

        $(document).on('click','.delete-inquiry-type',function() {
            const inquiry_type_id = $(this).data('inquiry-type-id');
            const transaction = 'delete inquiry type';
    
            Swal.fire({
                title: 'Confirm Inquiry Type Deletion',
                text: 'Are you sure you want to delete this inquiry type?',
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
                        url: 'controller/inquiry-type-controller.php',
                        dataType: 'json',
                        data: {
                            inquiry_type_id : inquiry_type_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete Inquiry Type Success', 'The inquiry type has been deleted successfully.', 'success');
                                reloadDatatable('#inquiry-type-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Delete Inquiry Type Error', 'The inquiry type does not exist.', 'danger');
                                    reloadDatatable('#inquiry-type-table');
                                }
                                else {
                                    showNotification('Delete Inquiry Type Error', response.message, 'danger');
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

        $(document).on('click','#delete-inquiry-type',function() {
            let inquiry_type_id = [];
            const transaction = 'delete multiple inquiry type';

            $('.datatable-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    inquiry_type_id.push(element.value);
                }
            });
    
            if(inquiry_type_id.length > 0){
                Swal.fire({
                    title: 'Confirm Multiple Inquiry Type Deletion',
                    text: 'Are you sure you want to delete these inquiry type?',
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
                            url: 'controller/inquiry-type-controller.php',
                            dataType: 'json',
                            data: {
                                inquiry_type_id: inquiry_type_id,
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Delete Inquiry Type Success', 'The selected inquiry type have been deleted successfully.', 'success');
                                        reloadDatatable('#inquiry-type-table');
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else {
                                        showNotification('Delete Inquiry Type Error', response.message, 'danger');
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
                showNotification('Deletion Multiple Inquiry Type Error', 'Please select the inquiry type you wish to delete.', 'danger');
            }
        });

        $(document).on('click','#delete-inquiry-type-details',function() {
            const inquiry_type_id = $('#inquiry-type-id').text();
            const transaction = 'delete inquiry type';
    
            Swal.fire({
                title: 'Confirm Inquiry Type Deletion',
                text: 'Are you sure you want to delete this inquiry type?',
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
                        url: 'controller/inquiry-type-controller.php',
                        dataType: 'json',
                        data: {
                            inquiry_type_id : inquiry_type_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Deleted Inquiry Type Success', 'The inquiry type has been deleted successfully.', 'success');
                                window.location = 'inquiry-type.php';
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
                                    showNotification('Delete Inquiry Type Error', response.message, 'danger');
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
            discardCreate('inquiry-type.php');
        });

        $(document).on('click','#edit-form',function() {
            displayDetails('get inquiry type details');

            enableForm();
        });

        $(document).on('click','#duplicate-inquiry-type',function() {
            const inquiry_type_id = $('#inquiry-type-id').text();
            const transaction = 'duplicate inquiry type';
    
            Swal.fire({
                title: 'Confirm Inquiry Type Duplication',
                text: 'Are you sure you want to duplicate this inquiry type?',
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
                        url: 'controller/inquiry-type-controller.php',
                        dataType: 'json',
                        data: {
                            inquiry_type_id : inquiry_type_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Duplicate Inquiry Type Success', 'The inquiry type has been duplicated successfully.', 'success');
                                window.location = 'inquiry-type.php?id=' + response.inquiryTypeID;
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Duplicate Inquiry Type Error', 'The inquiry type does not exist.', 'danger');
                                    reloadDatatable('#inquiry-type-table');
                                }
                                else {
                                    showNotification('Duplicate Inquiry Type Error', response.message, 'danger');
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

function inquiryTypeTable(datatable_name, buttons = false, show_all = false){
    const type = 'inquiry type table';
    var settings;

    const column = [ 
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'INQUIRY_TYPE_NAME' },
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
            'url' : 'view/_inquiry_type_generation.php',
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

function inquiryTypeForm(){
    $('#inquiry-type-form').validate({
        rules: {
            inquiry_type_name: {
                required: true
            },
        },
        messages: {
            inquiry_type_name: {
                required: 'Please enter the inquiry type name'
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
            const inquiry_type_id = $('#inquiry-type-id').text();
            const transaction = 'save inquiry type';
        
            $.ajax({
                type: 'POST',
                url: 'controller/inquiry-type-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&inquiry_type_id=' + inquiry_type_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-data');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert Inquiry Type Success' : 'Update Inquiry Type Success';
                        const notificationDescription = response.insertRecord ? 'The inquiry type has been inserted successfully.' : 'The inquiry type has been updated successfully.';
                        
                        setNotification(notificationMessage, notificationDescription, 'success');
                        window.location = 'inquiry-type.php?id=' + response.inquiryTypeID;
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
        case 'get inquiry type details':
            const inquiry_type_id = $('#inquiry-type-id').text();
            
            $.ajax({
                url: 'controller/inquiry-type-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    inquiry_type_id : inquiry_type_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetForm('inquiry-type-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#inquiry_type_id').val(inquiry_type_id);
                        $('#inquiry_type_name').val(response.inquiryTypeName);

                        $('#inquiry_type_name_label').text(response.inquiryTypeName);
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Inquiry Type Details Error', response.message, 'danger');
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