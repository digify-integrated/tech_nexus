(function($) {
    'use strict';

    $(function() {
        if($('#holiday-type-table').length){
            holidayTypeTable('#holiday-type-table');
        }

        if($('#holiday-type-form').length){
            holidayTypeForm();
        }

        if($('#holiday-type-id').length){
            displayDetails('get holiday type details');
        }

        $(document).on('click','.delete-holiday-type',function() {
            const holiday_type_id = $(this).data('holiday-type-id');
            const transaction = 'delete holiday type';
    
            Swal.fire({
                title: 'Confirm Holiday Type Deletion',
                text: 'Are you sure you want to delete this holiday type?',
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
                        url: 'controller/holiday-type-controller.php',
                        dataType: 'json',
                        data: {
                            holiday_type_id : holiday_type_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete Holiday Type Success', 'The holiday type has been deleted successfully.', 'success');
                                reloadDatatable('#holiday-type-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Delete Holiday Type Error', 'The holiday type does not exist.', 'danger');
                                    reloadDatatable('#holiday-type-table');
                                }
                                else {
                                    showNotification('Delete Holiday Type Error', response.message, 'danger');
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

        $(document).on('click','#delete-holiday-type',function() {
            let holiday_type_id = [];
            const transaction = 'delete multiple holiday type';

            $('.datatable-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    holiday_type_id.push(element.value);
                }
            });
    
            if(holiday_type_id.length > 0){
                Swal.fire({
                    title: 'Confirm Multiple Holiday Types Deletion',
                    text: 'Are you sure you want to delete these holiday types?',
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
                            url: 'controller/holiday-type-controller.php',
                            dataType: 'json',
                            data: {
                                holiday_type_id: holiday_type_id,
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Delete Holiday Type Success', 'The selected holiday types have been deleted successfully.', 'success');
                                    reloadDatatable('#holiday-type-table');
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else {
                                        showNotification('Delete Holiday Type Error', response.message, 'danger');
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
                showNotification('Deletion Multiple Holiday Type Error', 'Please select the holiday types you wish to delete.', 'danger');
            }
        });

        $(document).on('click','#delete-holiday-type-details',function() {
            const holiday_type_id = $('#holiday-type-id').text();
            const transaction = 'delete holiday type';
    
            Swal.fire({
                title: 'Confirm Holiday Type Deletion',
                text: 'Are you sure you want to delete this holiday type?',
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
                        url: 'controller/holiday-type-controller.php',
                        dataType: 'json',
                        data: {
                            holiday_type_id : holiday_type_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Deleted Holiday Type Success', 'The holiday type has been deleted successfully.', 'success');
                                window.location = 'holiday-type.php';
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
                                    showNotification('Delete Holiday Type Error', response.message, 'danger');
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
            discardCreate('holiday-type.php');
        });

        $(document).on('click','#edit-form',function() {
            displayDetails('get holiday type details');

            enableForm();
        });

        $(document).on('click','#duplicate-holiday-type',function() {
            const holiday_type_id = $('#holiday-type-id').text();
            const transaction = 'duplicate holiday type';
    
            Swal.fire({
                title: 'Confirm Holiday Type Duplication',
                text: 'Are you sure you want to duplicate this holiday type?',
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
                        url: 'controller/holiday-type-controller.php',
                        dataType: 'json',
                        data: {
                            holiday_type_id : holiday_type_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Duplicate Holiday Type Success', 'The holiday type has been duplicated successfully.', 'success');
                                window.location = 'holiday-type.php?id=' + response.holidayTypeID;
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Duplicate Holiday Type Error', 'The holiday type does not exist.', 'danger');
                                    reloadDatatable('#holiday-type-table');
                                }
                                else {
                                    showNotification('Duplicate Holiday Type Error', response.message, 'danger');
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

function holidayTypeTable(datatable_name, buttons = false, show_all = false){
    const type = 'holiday type table';
    var settings;

    const column = [ 
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'HOLIDAY_TYPE_NAME' },
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
            'url' : 'view/_holiday_type_generation.php',
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

function holidayTypeForm(){
    $('#holiday-type-form').validate({
        rules: {
            holiday_type_name: {
                required: true
            },
        },
        messages: {
            holiday_type_name: {
                required: 'Please enter the holiday type name'
            },
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
            const holiday_type_id = $('#holiday-type-id').text();
            const transaction = 'save holiday type';
        
            $.ajax({
                type: 'POST',
                url: 'controller/holiday-type-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&holiday_type_id=' + holiday_type_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-data');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert Holiday Type Success' : 'Update Holiday Type Success';
                        const notificationDescription = response.insertRecord ? 'The holiday type has been inserted successfully.' : 'The holiday type has been updated successfully.';
                        
                        setNotification(notificationMessage, notificationDescription, 'success');
                        window.location = 'holiday-type.php?id=' + response.holidayTypeID;
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
        case 'get holiday type details':
            const holiday_type_id = $('#holiday-type-id').text();
            
            $.ajax({
                url: 'controller/holiday-type-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    holiday_type_id : holiday_type_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetModalForm('holiday-type-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#holiday_type_id').val(holiday_type_id);
                        $('#holiday_type_name').val(response.holidayTypeName);

                        $('#holiday_type_name_label').text(response.holidayTypeName);
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Holiday Type Details Error', response.message, 'danger');
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