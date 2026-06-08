(function($) {
    'use strict';

    $(function() {
        if($('#insurance-type-table').length){
            insuranceTypeTable('#insurance-type-table');
        }

        if($('#insurance-type-form').length){
            insuranceTypeForm();
        }

        if($('#insurance-type-id').length){
            displayDetails('get insurance type details');
        }

        $(document).on('click','.delete-insurance-type',function() {
            const insurance_type_id = $(this).data('insurance-type-id');
            const transaction = 'delete insurance type';
    
            Swal.fire({
                title: 'Confirm Insurance Type Deletion',
                text: 'Are you sure you want to delete this insurance type?',
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
                        url: 'controller/insurance-type-controller.php',
                        dataType: 'json',
                        data: {
                            insurance_type_id : insurance_type_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete Insurance Type Success', 'The insurance type has been deleted successfully.', 'success');
                                reloadDatatable('#insurance-type-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Delete Insurance Type Error', 'The insurance type does not exist.', 'danger');
                                    reloadDatatable('#insurance-type-table');
                                }
                                else {
                                    showNotification('Delete Insurance Type Error', response.message, 'danger');
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

        $(document).on('click','#delete-insurance-type',function() {
            let insurance_type_id = [];
            const transaction = 'delete multiple insurance type';

            $('.datatable-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    insurance_type_id.push(element.value);
                }
            });
    
            if(insurance_type_id.length > 0){
                Swal.fire({
                    title: 'Confirm Multiple Insurance Type Deletion',
                    text: 'Are you sure you want to delete these insurance type?',
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
                            url: 'controller/insurance-type-controller.php',
                            dataType: 'json',
                            data: {
                                insurance_type_id: insurance_type_id,
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Delete Insurance Type Success', 'The selected insurance type have been deleted successfully.', 'success');
                                        reloadDatatable('#insurance-type-table');
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else {
                                        showNotification('Delete Insurance Type Error', response.message, 'danger');
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
                showNotification('Deletion Multiple Insurance Type Error', 'Please select the insurance type you wish to delete.', 'danger');
            }
        });

        $(document).on('click','#delete-insurance-type-details',function() {
            const insurance_type_id = $('#insurance-type-id').text();
            const transaction = 'delete insurance type';
    
            Swal.fire({
                title: 'Confirm Insurance Type Deletion',
                text: 'Are you sure you want to delete this insurance type?',
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
                        url: 'controller/insurance-type-controller.php',
                        dataType: 'json',
                        data: {
                            insurance_type_id : insurance_type_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Deleted Insurance Type Success', 'The insurance type has been deleted successfully.', 'success');
                                window.location = 'insurance-type.php';
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
                                    showNotification('Delete Insurance Type Error', response.message, 'danger');
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
            discardCreate('insurance-type.php');
        });

        $(document).on('click','#edit-form',function() {
            displayDetails('get insurance type details');

            enableForm();
        });

        $(document).on('click','#duplicate-insurance-type',function() {
            const insurance_type_id = $('#insurance-type-id').text();
            const transaction = 'duplicate insurance type';
    
            Swal.fire({
                title: 'Confirm Insurance Type Duplication',
                text: 'Are you sure you want to duplicate this insurance type?',
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
                        url: 'controller/insurance-type-controller.php',
                        dataType: 'json',
                        data: {
                            insurance_type_id : insurance_type_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Duplicate Insurance Type Success', 'The insurance type has been duplicated successfully.', 'success');
                                window.location = 'insurance-type.php?id=' + response.insuranceTypeID;
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Duplicate Insurance Type Error', 'The insurance type does not exist.', 'danger');
                                    reloadDatatable('#insurance-type-table');
                                }
                                else {
                                    showNotification('Duplicate Insurance Type Error', response.message, 'danger');
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

function insuranceTypeTable(datatable_name, buttons = false, show_all = false){
    const type = 'insurance type table';
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
            'url' : 'view/_insurance_type_generation.php',
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

function insuranceTypeForm(){
    $('#insurance-type-form').validate({
        rules: {
            insurance_type_name: {
                required: true
            },
        },
        messages: {
            insurance_type_name: {
                required: 'Please enter the insurance type name'
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
            const insurance_type_id = $('#insurance-type-id').text();
            const transaction = 'save insurance type';
        
            $.ajax({
                type: 'POST',
                url: 'controller/insurance-type-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&insurance_type_id=' + insurance_type_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-data');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert Insurance Type Success' : 'Update Insurance Type Success';
                        const notificationDescription = response.insertRecord ? 'The insurance type has been inserted successfully.' : 'The insurance type has been updated successfully.';
                        
                        setNotification(notificationMessage, notificationDescription, 'success');
                        window.location = 'insurance-type.php?id=' + response.insuranceTypeID;
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
        case 'get insurance type details':
            const insurance_type_id = $('#insurance-type-id').text();
            
            $.ajax({
                url: 'controller/insurance-type-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    insurance_type_id : insurance_type_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetForm('insurance-type-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#insurance_type_id').val(insurance_type_id);
                        $('#insurance_type_name').val(response.insuranceTypeName);

                        $('#insurance_type_name_label').text(response.insuranceTypeName);
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Insurance Type Details Error', response.message, 'danger');
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