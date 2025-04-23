(function($) {
    'use strict';

    $(function() {
        if($('#daily-employee-status-table').length){
            dailyEmployeeStatus('#daily-employee-status-table');
        }

        if($('#add-remarks-form').length){
            addRemarksForm();
        }

        $(document).on('click','#apply-filter',function() {
            dailyEmployeeStatus('#daily-employee-status-table');
            getEmployeeStatusCount();
        });

        $(document).on('click','#tag-as-present',function() {
            let employee_daily_status_id = [];
            const transaction = 'change attendance status';

            $('.datatable-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    employee_daily_status_id.push(element.value);
                }
            });
    
            if(employee_daily_status_id.length > 0){
                Swal.fire({
                    title: 'Tag As Present',
                    text: 'Are you sure you want to tag employees as present?',
                    icon: 'warning',
                    showCancelButton: !0,
                    confirmButtonText: 'Present',
                    cancelButtonText: 'Cancel',
                    confirmButtonClass: 'btn btn-success mt-2',
                    cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                    buttonsStyling: !1
                }).then(function(result) {
                    if (result.value) {
                        $.ajax({
                            type: 'POST',
                            url: 'controller/daily-employee-status-controller.php',
                            dataType: 'json',
                            data: {
                                employee_daily_status_id: employee_daily_status_id,
                                type: 'Present',
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Tag As Present Success', 'The selected employees have been tagged as present successfully.', 'success');
                                    reloadDatatable('#daily-employee-status-table');
                                    getEmployeeStatusCount();
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else {
                                        showNotification('Tag As Present Error', response.message, 'danger');
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
                showNotification('Tag As Present Error', 'Please select the employee you wish to tagged as present.', 'danger');
            }
        });

        $(document).on('click','#tag-as-late',function() {
            let employee_daily_status_id = [];
            const transaction = 'change attendance status';

            $('.datatable-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    employee_daily_status_id.push(element.value);
                }
            });
    
            if(employee_daily_status_id.length > 0){
                Swal.fire({
                    title: 'Tag As Late',
                    text: 'Are you sure you want to tag employees as late?',
                    icon: 'warning',
                    showCancelButton: !0,
                    confirmButtonText: 'Late',
                    cancelButtonText: 'Cancel',
                    confirmButtonClass: 'btn btn-warning mt-2',
                    cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                    buttonsStyling: !1
                }).then(function(result) {
                    if (result.value) {
                        $.ajax({
                            type: 'POST',
                            url: 'controller/daily-employee-status-controller.php',
                            dataType: 'json',
                            data: {
                                employee_daily_status_id: employee_daily_status_id,
                                type: 'Late',
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Tag As Late Success', 'The selected employees have been tagged as late successfully.', 'success');
                                    reloadDatatable('#daily-employee-status-table');
                                    getEmployeeStatusCount();
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else {
                                        showNotification('Tag As Late Error', response.message, 'danger');
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
                showNotification('Tag As Late Error', 'Please select the employee you wish to tagged as late.', 'danger');
            }
        });

        $(document).on('click','#tag-as-absent',function() {
            let employee_daily_status_id = [];
            const transaction = 'change attendance status';

            $('.datatable-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    employee_daily_status_id.push(element.value);
                }
            });
    
            if(employee_daily_status_id.length > 0){
                Swal.fire({
                    title: 'Tag As Absent',
                    text: 'Are you sure you want to tag employees as absent?',
                    icon: 'warning',
                    showCancelButton: !0,
                    confirmButtonText: 'Absent',
                    cancelButtonText: 'Cancel',
                    confirmButtonClass: 'btn btn-warning mt-2',
                    cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                    buttonsStyling: !1
                }).then(function(result) {
                    if (result.value) {
                        $.ajax({
                            type: 'POST',
                            url: 'controller/daily-employee-status-controller.php',
                            dataType: 'json',
                            data: {
                                employee_daily_status_id: employee_daily_status_id,
                                type: 'Absent',
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Tag As Absent Success', 'The selected employees have been tagged as absent successfully.', 'success');
                                    reloadDatatable('#daily-employee-status-table');
                                    getEmployeeStatusCount();
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else {
                                        showNotification('Tag As Absent Error', response.message, 'danger');
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
                showNotification('Tag As Absent Error', 'Please select the employee you wish to tagged as absent.', 'danger');
            }
        });

        $(document).on('click','#tag-as-on-leave',function() {
            let employee_daily_status_id = [];
            const transaction = 'change attendance status';

            $('.datatable-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    employee_daily_status_id.push(element.value);
                }
            });
    
            if(employee_daily_status_id.length > 0){
                Swal.fire({
                    title: 'Tag As On-Leave',
                    text: 'Are you sure you want to tag employees as on-leave?',
                    icon: 'warning',
                    showCancelButton: !0,
                    confirmButtonText: 'On-Leave',
                    cancelButtonText: 'Cancel',
                    confirmButtonClass: 'btn btn-info mt-2',
                    cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                    buttonsStyling: !1
                }).then(function(result) {
                    if (result.value) {
                        $.ajax({
                            type: 'POST',
                            url: 'controller/daily-employee-status-controller.php',
                            dataType: 'json',
                            data: {
                                employee_daily_status_id: employee_daily_status_id,
                                type: 'On-Leave',
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Tag As On-Leave Success', 'The selected employees have been tagged as on-leave successfully.', 'success');
                                    reloadDatatable('#daily-employee-status-table');
                                    getEmployeeStatusCount();
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else {
                                        showNotification('Tag As On-Leave Error', response.message, 'danger');
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
                showNotification('Tag As On-Leave Error', 'Please select the employee you wish to tagged as on-leave.', 'danger');
            }
        });

        $(document).on('click','#tag-as-official-business',function() {
            let employee_daily_status_id = [];
            const transaction = 'change attendance status';

            $('.datatable-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    employee_daily_status_id.push(element.value);
                }
            });
    
            if(employee_daily_status_id.length > 0){
                Swal.fire({
                    title: 'Tag As Official Business',
                    text: 'Are you sure you want to tag employees as official business?',
                    icon: 'warning',
                    showCancelButton: !0,
                    confirmButtonText: 'Official Business',
                    cancelButtonText: 'Cancel',
                    confirmButtonClass: 'btn btn-success mt-2',
                    cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                    buttonsStyling: !1
                }).then(function(result) {
                    if (result.value) {
                        $.ajax({
                            type: 'POST',
                            url: 'controller/daily-employee-status-controller.php',
                            dataType: 'json',
                            data: {
                                employee_daily_status_id: employee_daily_status_id,
                                type: 'Official Business',
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Tag As Official Business Success', 'The selected employees have been tagged as official business successfully.', 'success');
                                    reloadDatatable('#daily-employee-status-table');
                                    getEmployeeStatusCount();
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else {
                                        showNotification('Tag As Official Business Error', response.message, 'danger');
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
                showNotification('Tag As Official Business Error', 'Please select the employee you wish to tagged as official business.', 'danger');
            }
        });

         $(document).on('click','.add-remarks',function() {
            const employee_daily_status_id = $(this).data('employee-daily-status-id');
    
            sessionStorage.setItem('employee_daily_status_id', employee_daily_status_id);
        });

        getEmployeeStatusCount();
    });
})(jQuery);

function dailyEmployeeStatus(datatable_name, buttons = false, show_all = false){
    const type = 'daily employee status table';
    var filter_attendance_date = $('#filter_attendance_date').val();

    var attendance_filter_values_status = [];
    var branch_filter_values = [];

    $('.status-checkbox:checked').each(function() {
        attendance_filter_values_status.push($(this).val());
    });

    $('.branch-filter:checked').each(function() {
        branch_filter_values.push($(this).val());
    });
    
    var filter_attendance_status = attendance_filter_values_status.join(', ');
    var branch_filter = branch_filter_values.join(', ');

    var settings;

    const column = [
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'EMPLOYEE' },
        { 'data' : 'BRANCH' },
        { 'data' : 'STATUS' },
        { 'data' : 'ATTENDANCE_DATE' },
        { 'data' : 'REMARKS' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '1%','bSortable': false, 'aTargets': 0 },
        { 'width': 'auto', 'aTargets': 1 },
        { 'width': 'auto', 'aTargets': 2 },
        { 'width': 'auto', 'aTargets': 3 },
        { 'width': 'auto', 'type': 'date', 'aTargets': 4 },
        { 'width': 'auto', 'aTargets': 5 },
        { 'width': '15%','bSortable': false, 'aTargets': 6 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_daily_employee_status_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {'type' : type, 
                'filter_attendance_date' : filter_attendance_date, 
                'filter_attendance_status' : filter_attendance_status,
                'branch_filter' : branch_filter,
            },
            'dataSrc' : '',
            'error': function(xhr, status, error) {
                var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                if (xhr.responseText) {
                    fullErrorMessage += `, Response: ${xhr.responseText}`;
                }
                showErrorDialog(fullErrorMessage);
            }
        },
        'order': [[ 1, 'desc' ]],
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

function getEmployeeStatusCount(){
    const transaction = 'get daily employee status count';
    var filter_attendance_date = $('#filter_attendance_date').val();
        
    $.ajax({
        type: 'POST',
        url: 'controller/daily-employee-status-controller.php',
        data: 'transaction=' + transaction + 
        '&filter_attendance_date=' + filter_attendance_date,
        dataType: 'json',
        success: function (response) {
            if (response.success) {
                $('#present-count').text(response.presentCount);
                $('#late-count').text(response.lateCount);
                $('#absent-count').text(response.absentCount);
                $('#on-leave-count').text(response.onLeaveCount);
                $('#official-business-count').text(response.officialBusinessCount);
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
}

function addRemarksForm(){
    $('#add-remarks-form').validate({
        rules: {
            remarks: {
                required: true
            },
        },
        messages: {
            remarks: {
                required: 'Please enter the remarks'
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
            var employee_daily_status_id = sessionStorage.getItem('employee_daily_status_id');
            const transaction = 'add remarks';
        
            $.ajax({
                type: 'POST',
                url: 'controller/daily-employee-status-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&employee_daily_status_id=' + employee_daily_status_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-add-remarks');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = 'Add Remarks Success';
                        const notificationDescription = 'Added remarks successfully.';
                        
                        showNotification(notificationMessage, notificationDescription, 'success');
                        reloadDatatable('#daily-employee-status-table');
                        $('#remarks').val('');
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
                    enableFormSubmitButton('submit-add-remarks', 'Submit');
                }
            });
        
            return false;
        }
    });
}