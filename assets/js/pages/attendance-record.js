(function($) {
    'use strict';

    $(function() {
        if($('#attendance-record-table').length){
            attendanceRecordTable('#attendance-record-table');
        }

        if($('#attendance-record-import-table').length){
            importAttendanceRecordTable('#attendance-record-import-table', false, true);
        }

        if($('#attendance-record-form').length){
            attendanceRecordForm();
        }

        if($('#import-attendance-form').length){
            importAttendanceForm();
        }

        if($('#attendance-id').length){
            displayDetails('get attendance record details');
        }

        $(document).on('click','.delete-attendance-record',function() {
            const attendance_id = $(this).data('attendance-id');
            const transaction = 'delete attendance record';
    
            Swal.fire({
                title: 'Confirm Attendance Record Deletion',
                text: 'Are you sure you want to delete this attendance record?',
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
                        url: 'controller/attendance-record-controller.php',
                        dataType: 'json',
                        data: {
                            attendance_id : attendance_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete Attendance Record Success', 'The attendance record has been deleted successfully.', 'success');
                                reloadDatatable('#attendance-record-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Delete Attendance Record Error', 'The attendance record does not exist.', 'danger');
                                    reloadDatatable('#attendance-record-table');
                                }
                                else {
                                    showNotification('Delete Attendance Record Error', response.message, 'danger');
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

        $(document).on('click','#delete-attendance-record',function() {
            let attendance_id = [];
            const transaction = 'delete multiple attendance record';

            $('.datatable-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    attendance_id.push(element.value);
                }
            });
    
            if(attendance_id.length > 0){
                Swal.fire({
                    title: 'Confirm Multiple Attendance Records Deletion',
                    text: 'Are you sure you want to delete these attendance records?',
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
                            url: 'controller/attendance-record-controller.php',
                            dataType: 'json',
                            data: {
                                attendance_id: attendance_id,
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Delete Attendance Record Success', 'The selected attendance records have been deleted successfully.', 'success');
                                        reloadDatatable('#attendance-record-table');
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else {
                                        showNotification('Delete Attendance Record Error', response.message, 'danger');
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
                showNotification('Deletion Multiple Attendance Record Error', 'Please select the attendance records you wish to delete.', 'danger');
            }
        });

        $(document).on('click','#delete-attendance-record-details',function() {
            const attendance_id = $('#attendance-id').text();
            const transaction = 'delete attendance record';
    
            Swal.fire({
                title: 'Confirm Attendance Record Deletion',
                text: 'Are you sure you want to delete this attendance record?',
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
                        url: 'controller/attendance-record-controller.php',
                        dataType: 'json',
                        data: {
                            attendance_id : attendance_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Deleted Attendance Record Success', 'The attendance record has been deleted successfully.', 'success');
                                window.location = 'attendance-record.php';
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
                                    showNotification('Delete Attendance Record Error', response.message, 'danger');
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

        $(document).on('click','#import-attendance-record',function() {
            let attendance_id = [];
            const transaction = 'save imported attendance record';

            $('.datatable-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    attendance_id.push(element.value);
                }
            });
    
            if(attendance_id.length > 0){
                Swal.fire({
                    title: 'Confirm Imported Attendance Records Saving',
                    text: 'Are you sure you want to save these imported attendance records?',
                    icon: 'info',
                    showCancelButton: !0,
                    confirmButtonText: 'Import',
                    cancelButtonText: 'Cancel',
                    confirmButtonClass: 'btn btn-warning mt-2',
                    cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                    buttonsStyling: !1
                }).then(function(result) {
                    if (result.value) {
                        $.ajax({
                            type: 'POST',
                            url: 'controller/attendance-record-controller.php',
                            dataType: 'json',
                            data: {
                                attendance_id: attendance_id,
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    setNotification('Save Imported Attendance Record Success', 'The selected imported attendance records have been saved successfully.', 'success');
                                    window.location = 'attendance-record.php';
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else {
                                        showNotification('Delete Attendance Record Error', response.message, 'danger');
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
                showNotification('Save Imported Attendance Record Error', 'Please select the imported attendance records you wish to save.', 'danger');
            }
        });

        $(document).on('click','#discard-create',function() {
            discardCreate('attendance-record.php');
        });

        $(document).on('click','#edit-form',function() {
            displayDetails('get attendance record details');

            enableForm();
        });

        $(document).on('click','#apply-filter',function() {
            attendanceRecordTable('#attendance-record-table');
        });

        $(document).on('click','#apply-import-filter',function() {
            importAttendanceRecordTable('#attendance-record-import-table', false, true);
        });
    });
})(jQuery);

function attendanceRecordTable(datatable_name, buttons = false, show_all = false){
    const type = 'attendance record table';
    var filter_attendance_record_date_start_date = $('#filter_attendance_record_date_start_date').val();
    var filter_attendance_record_date_end_date = $('#filter_attendance_record_date_end_date').val();
    var employment_status_filter = $('.employment-status-filter:checked').val();
    var check_in_mode_filter_values = [];
    var check_out_mode_filter_values = [];
    var company_filter_values = [];
    var department_filter_values = [];
    var job_position_filter_values = [];
    var branch_filter_values = [];

    $('.check-in-mode-filter:checked').each(function() {
        check_in_mode_filter_values.push("'" + $(this).val() + "'");
    });

    $('.check-out-mode-filter:checked').each(function() {
        check_out_mode_filter_values.push("'" + $(this).val() + "'");
    });

    $('.company-filter:checked').each(function() {
        company_filter_values.push($(this).val());
    });

    $('.department-filter:checked').each(function() {
        department_filter_values.push($(this).val());
    });

    $('.job-position-filter:checked').each(function() {
        job_position_filter_values.push($(this).val());
    });

    $('.branch-filter:checked').each(function() {
        branch_filter_values.push($(this).val());
    });

    var check_in_mode_filter = check_in_mode_filter_values.join(', ');
    var check_out_mode_filter = check_out_mode_filter_values.join(', ');
    var company_filter = company_filter_values.join(', ');
    var department_filter = department_filter_values.join(', ');
    var job_position_filter = job_position_filter_values.join(', ');
    var branch_filter = branch_filter_values.join(', ');
    var settings;

    const column = [ 
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'EMPLOYEE_NAME' },
        { 'data' : 'CHECK_IN' },
        { 'data' : 'CHECK_IN_MODE' },
        { 'data' : 'CHECK_OUT' },
        { 'data' : 'CHECK_OUT_MODE' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '1%','bSortable': false, 'aTargets': 0 },
        { 'width': '24%', 'aTargets': 1 },
        { 'width': '15%', 'aTargets': 2 },
        { 'width': '15%', 'aTargets': 3 },
        { 'width': '15%', 'aTargets': 4 },
        { 'width': '15%', 'aTargets': 5 },
        { 'width': '15%','bSortable': false, 'aTargets': 6 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_attendance_record_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {'type' : type, 'filter_attendance_record_date_start_date' : filter_attendance_record_date_start_date, 'filter_attendance_record_date_end_date' : filter_attendance_record_date_end_date, 'check_in_mode_filter' : check_in_mode_filter, 'check_out_mode_filter' : check_out_mode_filter, 'employment_status_filter' : employment_status_filter, 'company_filter' : company_filter, 'department_filter' : department_filter, 'job_position_filter' : job_position_filter, 'branch_filter' : branch_filter},
            'dataSrc' : '',
            'error': function(xhr, status, error) {
                var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                if (xhr.responseText) {
                    fullErrorMessage += `, Response: ${xhr.responseText}`;
                }
                showErrorDialog(fullErrorMessage);
            }
        },
        'order': [[ 2, 'desc' ]],
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

function importAttendanceRecordTable(datatable_name, buttons = false, show_all = false){
    const type = 'import attendance record table';
    var filter_attendance_record_date_start_date = $('#filter_attendance_record_date_start_date').val();
    var filter_attendance_record_date_end_date = $('#filter_attendance_record_date_end_date').val();

    var settings;

    const column = [ 
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'EMPLOYEE_NAME' },
        { 'data' : 'CHECK_IN' },
        { 'data' : 'CHECK_IN_MODE' },
        { 'data' : 'CHECK_OUT' },
        { 'data' : 'CHECK_OUT_MODE' }
    ];

    const column_definition = [
        { 'width': '1%','bSortable': false, 'aTargets': 0 },
        { 'width': '29%', 'aTargets': 1 },
        { 'width': '15%', 'aTargets': 2 },
        { 'width': '20%', 'aTargets': 3 },
        { 'width': '15%', 'aTargets': 4 },
        { 'width': '20%', 'aTargets': 5 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_attendance_record_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {'type' : type, 'filter_attendance_record_date_start_date' : filter_attendance_record_date_start_date, 'filter_attendance_record_date_end_date' : filter_attendance_record_date_end_date},
            'dataSrc' : '',
            'error': function(xhr, status, error) {
                var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                if (xhr.responseText) {
                    fullErrorMessage += `, Response: ${xhr.responseText}`;
                }
                showErrorDialog(fullErrorMessage);
            }
        },
        'order': [[ 2, 'desc' ]],
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

function attendanceRecordForm(){
    $('#attendance-record-form').validate({
        rules: {
            employee_id: {
                required: true
            },
            check_in_date: {
                required: true
            },
            check_in_time: {
                required: true
            },
            check_out_date: {
                required: {
                    depends: function(element) {
                        return $('#check_out_time').val().trim().length > 0; 
                    }
                }
            },
            check_out_time: {
                required: {
                    depends: function(element) {
                        return $('#check_out_date').val().trim().length > 0;
                    }
                }
            }
        },
        messages: {
            employee_id: {
                required: 'Please choose the employee'
            },
            check_in_date: {
                required: 'Please choose the check in date'
            },
            check_in_time: {
                required: 'Please choose the check in time'
            },
            check_out_date: {
                required: 'Please choose the check out date'
            },
            check_out_time: {
                required: 'Please choose the check out time'
            }
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
            const attendance_id = $('#attendance-id').text();
            const transaction = 'save attendance record';
        
            $.ajax({
                type: 'POST',
                url: 'controller/attendance-record-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&attendance_id=' + attendance_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-data');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert Attendance Record Success' : 'Update Attendance Record Success';
                        const notificationDescription = response.insertRecord ? 'The attendance record has been inserted successfully.' : 'The attendance record has been updated successfully.';
                        
                        setNotification(notificationMessage, notificationDescription, 'success');
                        window.location = 'attendance-record.php?id=' + response.attendanceID;
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

function importAttendanceForm(){
    $('#import-attendance-form').validate({
        rules: {
            import_type: {
                required: true
            },
            company_id: {
                required: true
            },
            import_file: {
                required: true
            }
        },
        messages: {
            import_type: {
                required: 'Please choose the import type'
            },
            company_id: {
                required: 'Please choose the company'
            },
            import_file: {
                required: 'Please choose the import file'
            }
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
            const transaction = 'save attendance record import';
            var formData = new FormData(form);
            formData.append('transaction', transaction);
        
            $.ajax({
                type: 'POST',
                url: 'controller/attendance-record-controller.php',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-load-file');
                },
                success: function (response) {
                    if (response.success) {
                        setNotification('Attendance Record File Load Success', 'The attendance record file has been loaded successfully.', 'success');
                        window.location.reload();
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
                    enableFormSubmitButton('submit-load-file', 'Submit');
                }
            });
        
            return false;
        }
    });
}

function displayDetails(transaction){
    switch (transaction) {
        case 'get attendance record details':
            const attendance_id = $('#attendance-id').text();
            
            $.ajax({
                url: 'controller/attendance-record-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    attendance_id : attendance_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetForm('attendance-record-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#attendance_id').val(attendance_id);
                        $('#check_in_date').val(response.checkInDate);
                        $('#check_out_date').val(response.checkOutDate);
                        $('#check_in_time').val(response.checkInTime);
                        $('#check_out_time').val(response.checkOutTime);
                        $('#check_in_notes').val(response.checkInNotes);
                        $('#check_out_notes').val(response.checkOutNotes);

                        document.getElementById('check-in-map').innerHTML = response.checkInMap;
                        document.getElementById('check-out-map').innerHTML = response.checkOutMap;

                        document.getElementById('check-in-image').src = response.checkInImage;
                        document.getElementById('check-out-image').src = response.checkOutImage;

                        checkOptionExist('#employee_id', response.contactID, '');

                        $('#employee_id_label').text(response.employeeName);
                        $('#check_in_date_label').text(response.checkInDate);
                        $('#check_out_date_label').text(response.checkOutDate);
                        $('#check_in_time_label').text(response.checkInTimeLabel);
                        $('#check_out_time_label').text(response.checkOutTimeLabel);
                        $('#check_in_notes_label').text(response.checkInNotes);
                        $('#check_out_notes_label').text(response.checkOutNotes);
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Attendance Record Details Error', response.message, 'danger');
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