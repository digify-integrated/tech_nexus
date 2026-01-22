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

        $(document).on('change','#is_late',function() {
            if($(this).val() == 'Yes'){
                $('#late_minutes').prop('readonly', false);
            }
            else{
                $('#late_minutes').val('0');
                $('#late_minutes').prop('readonly', true);
            }
        });

        $(document).on('change','#is_undertime',function() {
            if($(this).val() == 'Yes'){
                $('#undertime_minutes').prop('readonly', false);
            }
            else{
                $('#undertime_minutes').val('0');
                $('#undertime_minutes').prop('readonly', true);
            }
        });

        $(document).on('change','#is_on_unpaid_leave',function() {
            if($(this).val() == 'Yes'){
                $('#unpaid_leave_minutes').prop('readonly', false);
            }
            else{
                $('#unpaid_leave_minutes').val('0');
                $('#unpaid_leave_minutes').prop('readonly', true);
            }
        });

        $(document).on('click','#change-status',function() {
            let employee_daily_status_id = [];

            $('.datatable-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    employee_daily_status_id.push(element.value);
                }
            });
    
            if(employee_daily_status_id.length > 0){
                $('#employee_daily_status_id').val(employee_daily_status_id);
            }
            else{
                showNotification('Change Status Error', 'Please select the employee you wish to change the status.', 'danger');
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

            displayDetails('get employee daily status details');
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
            is_present: {
                required: true
            },
            is_late: {
                required: true
            },
            late_minutes: {
                required: {
                    depends: function () {
                        return $('#is_late').val() === 'Yes';
                    }
                },
                min: {
                    depends: function () {
                        return $('#is_late').val() === 'Yes';
                    },
                    param: 1
                }
            },
            is_undertime: {
                required: true
            },
            undertime_minutes: {
                required: {
                    depends: function () {
                        return $('#is_undertime').val() === 'Yes';
                    }
                },
                min: {
                    depends: function () {
                        return $('#is_undertime').val() === 'Yes';
                    },
                    param: 1
                }
            },
            is_on_unpaid_leave: {
                required: true
            },
            unpaid_leave_minutes: {
                required: {
                    depends: function () {
                        return $('#is_on_unpaid_leave').val() === 'Yes';
                    }
                },
                min: {
                    depends: function () {
                        return $('#is_on_unpaid_leave').val() === 'Yes';
                    },
                    param: 1
                }
            },
            is_on_paid_leave: {
                required: true
            },
            is_on_official_business: {
                required: true
            }
        },
        messages: {
            is_present: {
                required: 'Please choose if present or not'
            },
            is_late: {
                required: 'Please choose if late or not'
            },
            late_minutes: {
                required: 'Late minutes is required when marked as late',
                min: 'Late minutes must be at least 1'
            },
            is_undertime: {
                required: 'Please choose if undertime or not'
            },
            undertime_minutes: {
                required: 'Undertime minutes is required when marked as undertime',
                min: 'Undertime minutes must be at least 1'
            },
            is_on_unpaid_leave: {
                required: 'Please choose if on unpaid leave or not'
            },
            unpaid_leave_minutes: {
                required: 'Unpaid leave minutes is required when on unpaid leave',
                min: 'Unpaid leave minutes must be at least 1'
            },
            is_on_paid_leave: {
                required: 'Please choose if on paid leave or not'
            },
            is_on_official_business: {
                required: 'Please choose if on official business or not'
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
            const transaction = 'add remarks';
        
            $.ajax({
                type: 'POST',
                url: 'controller/daily-employee-status-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-add-remarks');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = 'Update Status Success';
                        const notificationDescription = 'Update status successfully.';
                        
                        showNotification(notificationMessage, notificationDescription, 'success');
                        reloadDatatable('#daily-employee-status-table');
                        $('#add-remarks-offcanvas').offcanvas('hide');
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

function displayDetails(transaction){
    switch (transaction) {
        case 'get employee daily status details':
            var employee_daily_status_id = sessionStorage.getItem('employee_daily_status_id');
            
            $.ajax({
                url: 'controller/daily-employee-status-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    employee_daily_status_id : employee_daily_status_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetForm('add-remarks-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#employee_daily_status_id').val(employee_daily_status_id);
                        $('#late_minutes').val(response.late_minutes);
                        $('#undertime_minutes').val(response.undertime_minutes);
                        $('#unpaid_leave_minutes').val(response.unpaid_leave_minutes);
                        $('#remarks').val(response.remarks);

                        checkOptionExist('#is_present', response.is_present, '');
                        checkOptionExist('#is_late', response.is_late, '');
                        checkOptionExist('#is_undertime', response.is_undertime, '');
                        checkOptionExist('#is_on_unpaid_leave', response.is_on_unpaid_leave, '');
                        checkOptionExist('#is_on_paid_leave', response.is_on_paid_leave, '');
                        checkOptionExist('#is_on_official_business', response.is_on_official_business, '');
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Employee Daily Status Details Error', response.message, 'danger');
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