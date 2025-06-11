(function($) {
    'use strict';

    $(function() {
        if($('#business-location-type-table').length){
            businessLocationTypeTable('#business-location-type-table');
        }

        if($('#business-location-type-form').length){
            businessLocationTypeForm();
        }

        if($('#business-location-type-id').length){
            displayDetails('get business location type details');
        }

        $(document).on('click','.delete-business-location-type',function() {
            const business_location_type_id = $(this).data('business-location-type-id');
            const transaction = 'delete business location type';
    
            Swal.fire({
                title: 'Confirm Business Location Type Deletion',
                text: 'Are you sure you want to delete this business location type?',
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
                        url: 'controller/business-location-type-controller.php',
                        dataType: 'json',
                        data: {
                            business_location_type_id : business_location_type_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete Business Location Type Success', 'The business location type has been deleted successfully.', 'success');
                                reloadDatatable('#business-location-type-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Delete Business Location Type Error', 'The business location type does not exist.', 'danger');
                                    reloadDatatable('#business-location-type-table');
                                }
                                else {
                                    showNotification('Delete Business Location Type Error', response.message, 'danger');
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

        $(document).on('click','#delete-business-location-type',function() {
            let business_location_type_id = [];
            const transaction = 'delete multiple business location type';

            $('.datatable-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    business_location_type_id.push(element.value);
                }
            });
    
            if(business_location_type_id.length > 0){
                Swal.fire({
                    title: 'Confirm Multiple Business Location Types Deletion',
                    text: 'Are you sure you want to delete these business location types?',
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
                            url: 'controller/business-location-type-controller.php',
                            dataType: 'json',
                            data: {
                                business_location_type_id: business_location_type_id,
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Delete Business Location Type Success', 'The selected business location types have been deleted successfully.', 'success');
                                    reloadDatatable('#business-location-type-table');
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else {
                                        showNotification('Delete Business Location Type Error', response.message, 'danger');
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
                showNotification('Deletion Multiple Business Location Type Error', 'Please select the business location types you wish to delete.', 'danger');
            }
        });

        $(document).on('click','#delete-business-location-type-details',function() {
            const business_location_type_id = $('#business-location-type-id').text();
            const transaction = 'delete business location type';
    
            Swal.fire({
                title: 'Confirm Business Location Type Deletion',
                text: 'Are you sure you want to delete this business location type?',
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
                        url: 'controller/business-location-type-controller.php',
                        dataType: 'json',
                        data: {
                            business_location_type_id : business_location_type_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Deleted Business Location Type Success', 'The business location type has been deleted successfully.', 'success');
                                window.location = 'business-location-type.php';
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
                                    showNotification('Delete Business Location Type Error', response.message, 'danger');
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
            discardCreate('business-location-type.php');
        });

        $(document).on('click','#edit-form',function() {
            displayDetails('get business location type details');

            enableForm();
        });

        $(document).on('click','#duplicate-business-location-type',function() {
            const business_location_type_id = $('#business-location-type-id').text();
            const transaction = 'duplicate business location type';
    
            Swal.fire({
                title: 'Confirm Business Location Type Duplication',
                text: 'Are you sure you want to duplicate this business location type?',
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
                        url: 'controller/business-location-type-controller.php',
                        dataType: 'json',
                        data: {
                            business_location_type_id : business_location_type_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Duplicate Business Location Type Success', 'The business location type has been duplicated successfully.', 'success');
                                window.location = 'business-location-type.php?id=' + response.businessLocationTypeID;
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Duplicate Business Location Type Error', 'The business location type does not exist.', 'danger');
                                    reloadDatatable('#business-location-type-table');
                                }
                                else {
                                    showNotification('Duplicate Business Location Type Error', response.message, 'danger');
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

function businessLocationTypeTable(datatable_name, buttons = false, show_all = false){
    const type = 'business location type table';
    var settings;

    const column = [ 
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'BUSINESS_LOCATION_TYPE_NAME' },
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
            'url' : 'view/_business_location_type_generation.php',
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

function businessLocationTypeForm(){
    $('#business-location-type-form').validate({
        rules: {
            business_location_type_name: {
                required: true
            },
        },
        messages: {
            business_location_type_name: {
                required: 'Please enter the business location type name'
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
            const business_location_type_id = $('#business-location-type-id').text();
            const transaction = 'save business location type';
        
            $.ajax({
                type: 'POST',
                url: 'controller/business-location-type-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&business_location_type_id=' + business_location_type_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-data');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert Business Location Type Success' : 'Update Business Location Type Success';
                        const notificationDescription = response.insertRecord ? 'The business location type has been inserted successfully.' : 'The business location type has been updated successfully.';
                        
                        setNotification(notificationMessage, notificationDescription, 'success');
                        window.location = 'business-location-type.php?id=' + response.businessLocationTypeID;
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
        case 'get business location type details':
            const business_location_type_id = $('#business-location-type-id').text();
            
            $.ajax({
                url: 'controller/business-location-type-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    business_location_type_id : business_location_type_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetForm('business-location-type-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#business_location_type_id').val(business_location_type_id);
                        $('#business_location_type_name').val(response.businessLocationTypeName);

                        $('#business_location_type_name_label').text(response.businessLocationTypeName);
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Business Location Type Details Error', response.message, 'danger');
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