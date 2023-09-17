(function($) {
    'use strict';

    $(function() {
        if($('#work-schedule-type-table').length){
            workScheduleTypeTable('#work-schedule-type-table');
        }

        if($('#work-schedule-type-form').length){
            workScheduleTypeForm();
        }

        if($('#work-schedule-type-id').length){
            displayDetails('get work schedule type details');
        }

        $(document).on('click','.delete-work-schedule-type',function() {
            const work_schedule_type_id = $(this).data('work-schedule-type-id');
            const transaction = 'delete work schedule type';
    
            Swal.fire({
                title: 'Confirm Work Schedule Type Deletion',
                text: 'Are you sure you want to delete this work schedule type?',
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
                        url: 'controller/work-schedule-type-controller.php',
                        dataType: 'json',
                        data: {
                            work_schedule_type_id : work_schedule_type_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete Work Schedule Type Success', 'The work schedule type has been deleted successfully.', 'success');
                                reloadDatatable('#work-schedule-type-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Delete Work Schedule Type Error', 'The work schedule type does not exist.', 'danger');
                                    reloadDatatable('#work-schedule-type-table');
                                }
                                else {
                                    showNotification('Delete Work Schedule Type Error', response.message, 'danger');
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

        $(document).on('click','#delete-work-schedule-type',function() {
            let work_schedule_type_id = [];
            const transaction = 'delete multiple work schedule type';

            $('.datatable-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    work_schedule_type_id.push(element.value);
                }
            });
    
            if(work_schedule_type_id.length > 0){
                Swal.fire({
                    title: 'Confirm Multiple Work Schedule Types Deletion',
                    text: 'Are you sure you want to delete these work schedule types?',
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
                            url: 'controller/work-schedule-type-controller.php',
                            dataType: 'json',
                            data: {
                                work_schedule_type_id: work_schedule_type_id,
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Delete Work Schedule Type Success', 'The selected work schedule types have been deleted successfully.', 'success');
                                        reloadDatatable('#work-schedule-type-table');
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else {
                                        showNotification('Delete Work Schedule Type Error', response.message, 'danger');
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
                showNotification('Deletion Multiple Work Schedule Type Error', 'Please select the work schedule types you wish to delete.', 'danger');
            }
        });

        $(document).on('click','#delete-work-schedule-type-details',function() {
            const work_schedule_type_id = $('#work-schedule-type-id').text();
            const transaction = 'delete work schedule type';
    
            Swal.fire({
                title: 'Confirm Work Schedule Type Deletion',
                text: 'Are you sure you want to delete this work schedule type?',
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
                        url: 'controller/work-schedule-type-controller.php',
                        dataType: 'json',
                        data: {
                            work_schedule_type_id : work_schedule_type_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Deleted Work Schedule Type Success', 'The work schedule type has been deleted successfully.', 'success');
                                window.location = 'work-schedule-type.php';
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
                                    showNotification('Delete Work Schedule Type Error', response.message, 'danger');
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
            discardCreate('work-schedule-type.php');
        });

        $(document).on('click','#edit-form',function() {
            displayDetails('get work schedule type details');

            enableForm();
        });

        $(document).on('click','#duplicate-work-schedule-type',function() {
            const work_schedule_type_id = $('#work-schedule-type-id').text();
            const transaction = 'duplicate work schedule type';
    
            Swal.fire({
                title: 'Confirm Work Schedule Type Duplication',
                text: 'Are you sure you want to duplicate this work schedule type?',
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
                        url: 'controller/work-schedule-type-controller.php',
                        dataType: 'json',
                        data: {
                            work_schedule_type_id : work_schedule_type_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Duplicate Work Schedule Type Success', 'The work schedule type has been duplicated successfully.', 'success');
                                window.location = 'work-schedule-type.php?id=' + response.workScheduleTypeID;
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Duplicate Work Schedule Type Error', 'The work schedule type does not exist.', 'danger');
                                    reloadDatatable('#work-schedule-type-table');
                                }
                                else {
                                    showNotification('Duplicate Work Schedule Type Error', response.message, 'danger');
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

function workScheduleTypeTable(datatable_name, buttons = false, show_all = false){
    const type = 'work schedule type table';
    var settings;

    const column = [ 
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'WORK_SCHEDULE_TYPE_NAME' },
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
            'url' : 'view/_work_schedule_type_generation.php',
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

function workScheduleTypeForm(){
    $('#work-schedule-type-form').validate({
        rules: {
            work_schedule_type_name: {
                required: true
            },
        },
        messages: {
            work_schedule_type_name: {
                required: 'Please enter the work schedule type name'
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
            const work_schedule_type_id = $('#work-schedule-type-id').text();
            const transaction = 'save work schedule type';
        
            $.ajax({
                type: 'POST',
                url: 'controller/work-schedule-type-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&work_schedule_type_id=' + work_schedule_type_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-data');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert Work Schedule Type Success' : 'Update Work Schedule Type Success';
                        const notificationDescription = response.insertRecord ? 'The work schedule type has been inserted successfully.' : 'The work schedule type has been updated successfully.';
                        
                        setNotification(notificationMessage, notificationDescription, 'success');
                        window.location = 'work-schedule-type.php?id=' + response.workScheduleTypeID;
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
        case 'get work schedule type details':
            const work_schedule_type_id = $('#work-schedule-type-id').text();
            
            $.ajax({
                url: 'controller/work-schedule-type-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    work_schedule_type_id : work_schedule_type_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetModalForm('work-schedule-type-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#work_schedule_type_id').val(work_schedule_type_id);
                        $('#work_schedule_type_name').val(response.workScheduleTypeName);

                        $('#work_schedule_type_name_label').text(response.workScheduleTypeName);
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Work Schedule Type Details Error', response.message, 'danger');
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