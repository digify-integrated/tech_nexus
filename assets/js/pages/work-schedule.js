(function($) {
    'use strict';

    $(function() {
        if($('#work-schedule-table').length){
            workScheduleTable('#work-schedule-table');
        }

        if($('#work-schedule-form').length){
            workScheduleForm();
        }

        if($('#work-schedule-id').length){
            displayDetails('get work schedule details');

            if($('#fixed-working-hours-table').length){
                fixedWorkingHoursTable('#fixed-working-hours-table');
            }

            if($('#flexible-working-hours-table').length){
                flexibleWorkingHoursTable('#flexible-working-hours-table');
            }

            if($('#fixed-working-hours-form').length){
                fixedWorkingHoursForm();
            }

            if($('#flexible-working-hours-form').length){
                flexibleWorkingHoursForm();
            }

            $(document).on('click','#add-fixed-working-hours',function() {
                resetModalForm('fixed-working-hours-form');

                $('#fixed-working-hours-modal').modal('show');
            });

            $(document).on('click','.update-fixed-working-hours',function() {
                const work_hours_id = $(this).data('work-hours-id');
        
                sessionStorage.setItem('work_hours_id', work_hours_id);
                
                displayDetails('get fixed work hours details');
        
                $('#fixed-working-hours-modal').modal('show');
            });

            $(document).on('click','.delete-fixed-working-hours',function() {
                const work_hours_id = $(this).data('work-hours-id');
                const transaction = 'delete work hours';
        
                Swal.fire({
                    title: 'Confirm Working Hour Deletion',
                    text: 'Are you sure you want to delete this working hour?',
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
                            url: 'controller/work-schedule-controller.php',
                            dataType: 'json',
                            data: {
                                work_hours_id : work_hours_id, 
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Delete Working Hour Success', 'The working hour has been deleted successfully.', 'success');
                                    reloadDatatable('#fixed-working-hours-table');
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else if (response.notExist) {
                                        showNotification('Delete Working Hour Error', 'The working hour does not exist.', 'danger');
                                        reloadDatatable('#fixed-working-hours-table');
                                    }
                                    else {
                                        showNotification('Delete Working Hour Error', response.message, 'danger');
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

            $(document).on('click','#add-flexible-working-hours',function() {
                resetModalForm('flexible-working-hours-form');

                $('#flexible-working-hours-modal').modal('show');
            });

            $(document).on('click','.update-flexible-working-hours',function() {
                const work_hours_id = $(this).data('work-hours-id');
        
                sessionStorage.setItem('work_hours_id', work_hours_id);
                
                displayDetails('get flexible work hours details');
        
                $('#flexible-working-hours-modal').modal('show');
            });

            $(document).on('click','.delete-flexible-working-hours',function() {
                const work_hours_id = $(this).data('work-hours-id');
                const transaction = 'delete work hours';
        
                Swal.fire({
                    title: 'Confirm Working Hour Deletion',
                    text: 'Are you sure you want to delete this working hour?',
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
                            url: 'controller/work-schedule-controller.php',
                            dataType: 'json',
                            data: {
                                work_hours_id : work_hours_id, 
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Delete Working Hour Success', 'The working hour has been deleted successfully.', 'success');
                                    reloadDatatable('#flexible-working-hours-table');
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else if (response.notExist) {
                                        showNotification('Delete Working Hour Error', 'The working hour does not exist.', 'danger');
                                        reloadDatatable('#flexible-working-hours-table');
                                    }
                                    else {
                                        showNotification('Delete Working Hour Error', response.message, 'danger');
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

        $(document).on('click','.delete-work-schedule',function() {
            const work_schedule_id = $(this).data('work-schedule-id');
            const transaction = 'delete work schedule';
    
            Swal.fire({
                title: 'Confirm Work Schedule Deletion',
                text: 'Are you sure you want to delete this work schedule?',
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
                        url: 'controller/work-schedule-controller.php',
                        dataType: 'json',
                        data: {
                            work_schedule_id : work_schedule_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete Work Schedule Success', 'The work schedule has been deleted successfully.', 'success');
                                reloadDatatable('#work-schedule-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Delete Work Schedule Error', 'The work schedule does not exist.', 'danger');
                                    reloadDatatable('#work-schedule-table');
                                }
                                else {
                                    showNotification('Delete Work Schedule Error', response.message, 'danger');
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

        $(document).on('click','#delete-work-schedule',function() {
            let work_schedule_id = [];
            const transaction = 'delete multiple work schedule';

            $('.datatable-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    work_schedule_id.push(element.value);
                }
            });
    
            if(work_schedule_id.length > 0){
                Swal.fire({
                    title: 'Confirm Multiple Work Schedules Deletion',
                    text: 'Are you sure you want to delete these work schedules?',
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
                            url: 'controller/work-schedule-controller.php',
                            dataType: 'json',
                            data: {
                                work_schedule_id: work_schedule_id,
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Delete Work Schedule Success', 'The selected work schedules have been deleted successfully.', 'success');
                                        reloadDatatable('#work-schedule-table');
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else {
                                        showNotification('Delete Work Schedule Error', response.message, 'danger');
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
                showNotification('Deletion Multiple Work Schedule Error', 'Please select the work schedules you wish to delete.', 'danger');
            }
        });

        $(document).on('click','#delete-work-schedule-details',function() {
            const work_schedule_id = $('#work-schedule-id').text();
            const transaction = 'delete work schedule';
    
            Swal.fire({
                title: 'Confirm Work Schedule Deletion',
                text: 'Are you sure you want to delete this work schedule?',
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
                        url: 'controller/work-schedule-controller.php',
                        dataType: 'json',
                        data: {
                            work_schedule_id : work_schedule_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Deleted Work Schedule Success', 'The work schedule has been deleted successfully.', 'success');
                                window.location = 'work-schedule.php';
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
                                    showNotification('Delete Work Schedule Error', response.message, 'danger');
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
            discardCreate('work-schedule.php');
        });

        $(document).on('click','#edit-form',function() {
            displayDetails('get work schedule details');

            enableForm();
        });

        $(document).on('click','#duplicate-work-schedule',function() {
            const work_schedule_id = $('#work-schedule-id').text();
            const transaction = 'duplicate work schedule';
    
            Swal.fire({
                title: 'Confirm Work Schedule Duplication',
                text: 'Are you sure you want to duplicate this work schedule?',
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
                        url: 'controller/work-schedule-controller.php',
                        dataType: 'json',
                        data: {
                            work_schedule_id : work_schedule_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Duplicate Work Schedule Success', 'The work schedule has been duplicated successfully.', 'success');
                                window.location = 'work-schedule.php?id=' + response.workScheduleID;
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Duplicate Work Schedule Error', 'The work schedule does not exist.', 'danger');
                                    reloadDatatable('#work-schedule-table');
                                }
                                else {
                                    showNotification('Duplicate Work Schedule Error', response.message, 'danger');
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

        $(document).on('click','#filter-datatable',function() {
            workScheduleTable('#work-schedule-table');
        });
    });
})(jQuery);

function workScheduleTable(datatable_name, buttons = false, show_all = false){
    const type = 'work schedule table';
    var filter_work_schedule_type = $('#filter_work_schedule_type').val();
    var settings;

    const column = [ 
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'WORK_SCHEDULE_NAME' },
        { 'data' : 'WORK_SCHEDULE_TYPE' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '1%','bSortable': false, 'aTargets': 0 },
        { 'width': '64%', 'aTargets': 1 },
        { 'width': '20%', 'aTargets': 2 },
        { 'width': '15%','bSortable': false, 'aTargets': 3 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_work_schedule_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {'type' : type, 'filter_work_schedule_type' : filter_work_schedule_type},
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

function fixedWorkingHoursTable(datatable_name, buttons = false, show_all = false){
    const type = 'fixed working hours table';
    const work_schedule_id = $('#work-schedule-id').text();
    var settings;

    const column = [ 
        { 'data' : 'DAY_OF_WEEK' },
        { 'data' : 'DAY_PERIOD' },
        { 'data' : 'WORK_FROM' },
        { 'data' : 'WORK_TO' },
        { 'data' : 'NOTES' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '15%', 'aTargets': 0 },
        { 'width': '15%', 'aTargets': 1 },
        { 'width': '15%', 'aTargets': 2 },
        { 'width': '15%', 'aTargets': 3 },
        { 'width': '15%', 'aTargets': 4 },
        { 'width': '15%', 'bSortable': false, 'aTargets': 5 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_work_schedule_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {'type' : type, 'work_schedule_id' : work_schedule_id},
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

function flexibleWorkingHoursTable(datatable_name, buttons = false, show_all = false){
    const type = 'flexible working hours table';
    const work_schedule_id = $('#work-schedule-id').text();
    var settings;

    const column = [ 
        { 'data' : 'WORK_DATE' },
        { 'data' : 'DAY_PERIOD' },
        { 'data' : 'WORK_FROM' },
        { 'data' : 'WORK_TO' },
        { 'data' : 'NOTES' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '15%', 'aTargets': 0 },
        { 'width': '15%', 'aTargets': 1 },
        { 'width': '15%', 'aTargets': 2 },
        { 'width': '15%', 'aTargets': 3 },
        { 'width': '15%', 'aTargets': 4 },
        { 'width': '15%', 'bSortable': false, 'aTargets': 5 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_work_schedule_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {'type' : type, 'work_schedule_id' : work_schedule_id},
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

function workScheduleForm(){
    $('#work-schedule-form').validate({
        rules: {
            work_schedule_name: {
                required: true
            },
            work_schedule_type_id: {
                required: true
            },
            work_schedule_description: {
                required: true
            },
        },
        messages: {
            work_schedule_name: {
                required: 'Please enter the work schedule name'
            },
            work_schedule_type_id: {
                required: 'Please choose the work schedule type'
            },
            work_schedule_description: {
                required: 'Please enter the work schedule description'
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
            const work_schedule_id = $('#work-schedule-id').text();
            const transaction = 'save work schedule';
        
            $.ajax({
                type: 'POST',
                url: 'controller/work-schedule-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&work_schedule_id=' + work_schedule_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-data');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert Work Schedule Success' : 'Update Work Schedule Success';
                        const notificationDescription = response.insertRecord ? 'The work schedule has been inserted successfully.' : 'The work schedule has been updated successfully.';
                        
                        setNotification(notificationMessage, notificationDescription, 'success');
                        window.location = 'work-schedule.php?id=' + response.workScheduleID;
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

function fixedWorkingHoursForm(){
    $('#fixed-working-hours-form').validate({
        rules: {
            day_of_week: {
                required: true
            },
            day_period: {
                required: true
            },
            work_from: {
                required: true
            },
            work_to: {
                required: true
            }
        },
        messages: {
            day_of_week: {
                required: 'Please choose the day of week'
            },
            day_period: {
                required: 'Please choose the day period'
            },
            work_from: {
                required: 'Please choose the work from'
            },
            work_to: {
                required: 'Please choose the work to'
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
            const work_schedule_id = $('#work-schedule-id').text();
            const transaction = 'save fixed work hours';
        
            $.ajax({
                type: 'POST',
                url: 'controller/work-schedule-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&work_schedule_id=' + work_schedule_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-fixed-working-hours-form');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert Working Hours Success' : 'Update Working Hours Success';
                        const notificationDescription = response.insertRecord ? 'The working hours has been inserted successfully.' : 'The working hours has been updated successfully.';
                        
                        showNotification(notificationMessage, notificationDescription, 'success');
                        $('#fixed-working-hours-modal').modal('hide');
                        resetModalForm('fixed-working-hours-form');
                    }
                    else {
                        if (response.Overlap) {
                            showNotification('Working Hours Overlap', 'This work schedule overlaps with an existing one. Please select a different time slot or adjust your schedule.', 'danger');
                        }
                        else if (response.isInactive) {
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
                    enableFormSubmitButton('submit-fixed-working-hours-form', 'Submit');
                    reloadDatatable('#fixed-working-hours-table');
                }
            });
        
            return false;
        }
    });
}

function flexibleWorkingHoursForm(){
    $('#flexible-working-hours-form').validate({
        rules: {
            work_date: {
                required: true
            },
            day_period: {
                required: true
            },
            work_from: {
                required: true
            },
            work_to: {
                required: true
            }
        },
        messages: {
            work_date: {
                required: 'Please choose the work day'
            },
            day_period: {
                required: 'Please choose the day period'
            },
            work_from: {
                required: 'Please choose the work from'
            },
            work_to: {
                required: 'Please choose the work to'
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
            const work_schedule_id = $('#work-schedule-id').text();
            const transaction = 'save flexible work hours';
        
            $.ajax({
                type: 'POST',
                url: 'controller/work-schedule-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&work_schedule_id=' + work_schedule_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-flexible-working-hours-form');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert Working Hours Success' : 'Update Working Hours Success';
                        const notificationDescription = response.insertRecord ? 'The working hours has been inserted successfully.' : 'The working hours has been updated successfully.';
                        
                        showNotification(notificationMessage, notificationDescription, 'success');
                        $('#flexible-working-hours-modal').modal('hide');
                        resetModalForm('flexible-working-hours-form');
                    }
                    else {
                        if (response.Overlap) {
                            showNotification('Working Hours Overlap', 'This work schedule overlaps with an existing one. Please select a different time slot or adjust your schedule.', 'danger');
                        }
                        else if (response.isInactive) {
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
                    enableFormSubmitButton('submit-flexible-working-hours-form', 'Submit');
                    reloadDatatable('#flexible-working-hours-table');
                }
            });
        
            return false;
        }
    });
}

function displayDetails(transaction){
    switch (transaction) {
        case 'get work schedule details':
            const work_schedule_id = $('#work-schedule-id').text();
            
            $.ajax({
                url: 'controller/work-schedule-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    work_schedule_id : work_schedule_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetModalForm('work-schedule-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#work_schedule_id').val(work_schedule_id);
                        $('#work_schedule_name').val(response.workScheduleName);
                        $('#work_schedule_description').val(response.workScheduleDescription);

                        checkOptionExist('#work_schedule_type_id', response.workScheduleTypeID, '');

                        $('#work_schedule_name_label').text(response.workScheduleName);
                        $('#work_schedule_description_label').text(response.workScheduleDescription);
                        $('#work_schedule_type_id_label').text(response.workScheduleTypeName);
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Work Schedule Details Error', response.message, 'danger');
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
        case 'get fixed work hours details':
            var work_hours_id = sessionStorage.getItem('work_hours_id');
                    
            $.ajax({
                url: 'controller/work-schedule-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    work_hours_id : work_hours_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetModalForm('fixed-working-hours-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#work_hours_id').val(work_hours_id);

                        checkOptionExist('#day_of_week', response.dayOfWeek, '');
                        checkOptionExist('#day_period', response.dayPeriod, '');

                        $('#work_from').val(response.startTime);
                        $('#work_to').val(response.endTIme);
                        $('#notes').val(response.notes);
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                           showNotification('Get Working Hours Details Error', response.message, 'danger');
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
        case 'get flexible work hours details':
            var work_hours_id = sessionStorage.getItem('work_hours_id');
                    
            $.ajax({
                url: 'controller/work-schedule-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    work_hours_id : work_hours_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetModalForm('flexible-working-hours-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#work_hours_id').val(work_hours_id);
                        $('#work_date').val(response.workDate);

                        checkOptionExist('#day_period', response.dayPeriod, '');

                        $('#work_from').val(response.startTime);
                        $('#work_to').val(response.endTIme);
                        $('#notes').val(response.notes);
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                           showNotification('Get Working Hours Details Error', response.message, 'danger');
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