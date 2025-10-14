(function($) {
    'use strict';

    $(function() {
        if($('#record-attendance-form').length){
            recordAttendanceForm();
        }

        if($('#dashboard-transmittal-table').length){
            transmittalTable('#dashboard-transmittal-table');
        }

        if($('#dashboard-transmittal-list').length){
            transmittalList();
        }

        if($('#travel-approval-form-table').length){
            travelApprovalFormTable('#travel-approval-form-table');
        }

        if($('#travel-approval-form-list').length){
            travelApprovalFormList();
        }

        if($('#dashboard-sales-proposal-for-initial-approval-table').length){
            dashboardInitialApproval('#dashboard-sales-proposal-for-initial-approval-table');
        }

        if($('#dashboard-sales-proposal-for-final-approval-table').length){
            dashboardFinalApproval('#dashboard-sales-proposal-for-final-approval-table');
        }

        if($('#dashboard-document-table').length){
            dashboardDocumentTable('#dashboard-document-table');
        }

        if($('#leave-dashboard-approval-table').length){
            leaveDashboardApprovalTable('#leave-dashboard-approval-table');
        }

        if($('#leave-dashboard-approval-list').length){
            leaveDashboardApprovalList();
        }

        if($('#sales-proposal-for-ci-dashboard-list').length){
            salesProposalForCIDashboard();
        }

        if($('#sales-proposal-for-verification-dashboard-list').length){
            salesProposalForVerificationDashboard();
        }

        if($('#daily-employee-status-dashboard-table').length){
            dailyEmployeeStatusDashboard('#daily-employee-status-dashboard-table');
            getEmployeeStatusCount();
        }

        $(document).on('click','#apply-dashboard-filter-attendance-status',function() {
            generateAbsentEmployeeList();
            generateLateEmployeeList();
            generateOnLeaveEmployeeList();
            generateOfficialBusinessEmployeeList();
        });


        if($('#absent-employee-list').length){
            generateAbsentEmployeeList();
        }

        if($('#late-employee-list').length){
            generateLateEmployeeList();
        }

        if($('#on-leave-employee-list').length){
            generateOnLeaveEmployeeList();
        }

        if($('#official-business-employee-list').length){
            generateOfficialBusinessEmployeeList();
        }

        if($('#for-initial-approval-list').length){
            dashboardInitialApprovalList();
        }

        if($('#for-final-approval-list').length){
            dashboardFinalApprovalList();
        }
        
        if($('#parts-incoming-dashboard-table').length){
            partsIncomingTable('#parts-incoming-dashboard-table');
        }
        
        if($('#parts-incoming-dashboard-list').length){
            partsIncomingList();
        }

        if($('#parts-transaction-dashboard-list').length){
            partsTransactionList();
        }

        if($('#dashboard-internal-job-order-list').length){
            internalJobOrderList();
        }

        if($('#parts-transaction-dashboard-table').length){
            partsTransactionTable('#parts-transaction-dashboard-table');
        }

        /*document.querySelector('#record-attendance').addEventListener('click', async function() {
            getLocation('location');
        
            const videContainer = document.getElementById('video-container');
            const attendanceVideo = document.getElementById('attendance-video');
            const attendanceImage = document.getElementById('attendance-image');
            const captureAttendance = document.getElementById('capture-attendance');
            const submitAttendance = document.getElementById('submit-attendance');
        
            attendanceImage.classList.add('d-none');
            submitAttendance.classList.add('d-none');
        
            $('#record-attendance-modal').modal('show');
        
            try {
                const stream = await navigator.mediaDevices.getUserMedia({ video: true, audio: false });
                attendanceVideo.srcObject = stream;
        
                attendanceVideo.addEventListener('loadedmetadata', function() {
                    attendanceVideo.classList.remove('d-none');
                    videContainer.classList.remove('d-none');
                    captureAttendance.classList.remove('d-none');
                });
        
                document.getElementById('record-attendance-modal').addEventListener('hidden.bs.modal', function (e) {
                    if (stream) {
                        stream.getTracks().forEach(track => track.stop());
                        videContainer.classList.add('d-none');
                        captureAttendance.classList.add('d-none');
                    }
                });
            } catch (error) {
                document.getElementById('record-attendance-modal').modal('hide');
                showNotification('Open Video Source Error', 'An error occurred while trying to open the video source: ' + error, 'danger');
            }
        });
        
        document.querySelector('#capture-attendance').addEventListener('click', function () {
            const location = $('#location').val();
          
            if (location !== '') {
                const attendanceVideo = document.getElementById('attendance-video');
                
                if (attendanceVideo.srcObject) {
                    $('#attendance-image, #submit-attendance').removeClass('d-none');
                    $(this).addClass('d-none');
                    $('#attendance-video, #capture-attendance').addClass('d-none');
            
                    const attendanceImage = document.getElementById('attendance-image');
            
                    attendanceImage.width = attendanceVideo.videoWidth;
                    attendanceImage.height = attendanceVideo.videoHeight;
            
                    attendanceImage.getContext('2d').drawImage(attendanceVideo, 0, 0, attendanceImage.width, attendanceImage.height);
            
                    sessionStorage.setItem('attendance_image_data', attendanceImage.toDataURL('image/png'));
            
                    attendanceVideo.srcObject.getTracks().forEach(track => track.stop());
                }
                else {
                    showNotification('Capture Image Error', 'Failed to capture image. Video not started.', 'danger');
                }
            }
            else {
                showNotification('Capture Image Error', 'Failed to capture image. Location cannot be determined.', 'danger');
            }
        });*/
    });
})(jQuery);

function dailyEmployeeStatusDashboard(datatable_name, buttons = false, show_all = false){
    const type = 'daily employee status dashboard table';
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
        { 'data' : 'EMPLOYEE' },
        { 'data' : 'BRANCH' },
        { 'data' : 'STATUS' },
        { 'data' : 'ATTENDANCE_DATE' },
        { 'data' : 'REMARKS' }
    ];

    const column_definition = [
        { 'width': 'auto', 'aTargets': 0 },
        { 'width': 'auto', 'aTargets': 1 },
        { 'width': 'auto', 'aTargets': 2 },
        { 'width': 'auto', 'type': 'date', 'aTargets': 3 },
        { 'width': 'auto', 'aTargets': 4 }
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

function generateAbsentEmployeeList(){
    const type = 'employee status dashboard list';
    const list_type = 'Absent';
    var filter_attendance_date = $('#filter_attendance_date').val();
    var branch_filter_values = [];

    $('.branch-filter:checked').each(function() {
        branch_filter_values.push($(this).val());
    });
    
    var branch_filter = branch_filter_values.join(', ');
        
    $.ajax({
        type: 'POST',
        url: 'view/_daily_employee_status_generation.php',
        data: 'type=' + type + 
        '&filter_attendance_date=' + filter_attendance_date + 
        '&branch_filter=' + branch_filter +
        '&list_type=' + list_type,
        dataType: 'json',
        success: function (response) {
            document.getElementById("absent-employee-list").innerHTML = response.LIST;
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

function generateLateEmployeeList(){
    const type = 'employee status dashboard list';
    const list_type = 'Late';
    var filter_attendance_date = $('#filter_attendance_date').val();
    var branch_filter_values = [];

    $('.branch-filter:checked').each(function() {
        branch_filter_values.push($(this).val());
    });
    
    var branch_filter = branch_filter_values.join(', ');
        
    $.ajax({
        type: 'POST',
        url: 'view/_daily_employee_status_generation.php',
        data: 'type=' + type + 
        '&filter_attendance_date=' + filter_attendance_date + 
        '&branch_filter=' + branch_filter +
        '&list_type=' + list_type,
        dataType: 'json',
        success: function (response) {
            document.getElementById("late-employee-list").innerHTML = response.LIST;
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

function generateOnLeaveEmployeeList(){
    const type = 'employee status dashboard list';
    const list_type = 'On-Leave';
    var filter_attendance_date = $('#filter_attendance_date').val();
    var branch_filter_values = [];

    $('.branch-filter:checked').each(function() {
        branch_filter_values.push($(this).val());
    });
    
    var branch_filter = branch_filter_values.join(', ');
        
    $.ajax({
        type: 'POST',
        url: 'view/_daily_employee_status_generation.php',
        data: 'type=' + type + 
        '&filter_attendance_date=' + filter_attendance_date + 
        '&branch_filter=' + branch_filter +
        '&list_type=' + list_type,
        dataType: 'json',
        success: function (response) {
            document.getElementById("on-leave-employee-list").innerHTML = response.LIST;
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

function generateOfficialBusinessEmployeeList(){
    const type = 'employee status dashboard list';
    const list_type = 'Official Business';
    var filter_attendance_date = $('#filter_attendance_date').val();
    var branch_filter_values = [];

    $('.branch-filter:checked').each(function() {
        branch_filter_values.push($(this).val());
    });
    
    var branch_filter = branch_filter_values.join(', ');
        
    $.ajax({
        type: 'POST',
        url: 'view/_daily_employee_status_generation.php',
        data: 'type=' + type + 
        '&filter_attendance_date=' + filter_attendance_date + 
        '&branch_filter=' + branch_filter +
        '&list_type=' + list_type,
        dataType: 'json',
        success: function (response) {
            document.getElementById("official-business-employee-list").innerHTML = response.LIST;
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

function recordAttendanceForm(){
    $('#record-attendance-form').validate({
        submitHandler: function(form) {
            const attendance_image_data = sessionStorage.getItem('attendance_image_data');

            const transaction = 'save attendance record regular';

            var formData = new FormData(form);
            formData.append('transaction', transaction);
            formData.append('attendance_image_data', attendance_image_data);
        
            $.ajax({
                type: 'POST',
                url: 'controller/employee-controller.php',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-attendance');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Attendance Check-In Success' : 'Attendance Check-Out Success';
                        const notificationDescription = response.insertRecord ? 'The attendance record has been inserted successfully.' : 'The attendance record has been updated successfully.';
                        
                        setNotification(notificationMessage, notificationDescription, 'success');
                        location.reload();
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
                    enableFormSubmitButton('submit-attendance', 'Submit');
                }
            });
        
            return false;
        }
    });
}

function dashboardInitialApprovalList(){
    const type = 'dashboard for initial approval list';
        
    $.ajax({
        type: 'POST',
        url: 'view/_sales_proposal_generation.php',
        data: 'type=' + type,
        dataType: 'json',
        success: function (response) {
            document.getElementById('for-initial-approval-list').innerHTML = response.LIST;
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

function dashboardFinalApprovalList(){
    const type = 'dashboard for final approval list';
        
    $.ajax({
        type: 'POST',
        url: 'view/_sales_proposal_generation.php',
        data: 'type=' + type,
        dataType: 'json',
        success: function (response) {
            document.getElementById('for-final-approval-list').innerHTML = response.LIST;
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

function leaveDashboardApprovalTable(datatable_name, buttons = false, show_all = false){
    const type = 'leave dashboard approval table';
    var settings;

    const column = [ 
        { 'data' : 'FILE_AS' },
        { 'data' : 'LEAVE_TYPE' },
        { 'data' : 'LEAVE_DATE' },
        { 'data' : 'APPLICATION_DATE' },
        { 'data' : 'STATUS' }
    ];

    const column_definition = [
        { 'width': 'auto', 'aTargets': 0 },
        { 'width': 'auto', 'aTargets': 1 },
        { 'width': 'auto', 'aTargets': 2 },
        { 'width': 'auto', 'aTargets': 3 },
        { 'width': 'auto', 'aTargets': 4 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_leave_application_generation.php',
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

function leaveDashboardApprovalList(){
    const type = 'leave dashboard approval list';
        
    $.ajax({
        type: 'POST',
        url: 'view/_leave_application_generation.php',
        data: 'type=' + type,
        dataType: 'json',
        success: function (response) {
            document.getElementById('leave-dashboard-approval-list').innerHTML = response.LIST;
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

function salesProposalForCIDashboard(){
    const type = 'sales proposal for ci list';

    $.ajax({
        type: 'POST',
        url: 'view/_sales_proposal_generation.php',
        data: 'type=' + type,
        dataType: 'json',
        success: function (response) {
            document.getElementById('sales-proposal-for-ci-dashboard-list').innerHTML = response.LIST;
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

function salesProposalForVerificationDashboard(){
    const type = 'sales proposal for verification list';

    $.ajax({
        type: 'POST',
        url: 'view/_sales_proposal_generation.php',
        data: 'type=' + type,
        dataType: 'json',
        success: function (response) {
            document.getElementById('sales-proposal-for-verification-dashboard-list').innerHTML = response.LIST;
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

function transmittalTable(datatable_name, buttons = false, show_all = false){
    const type = 'transmittal dashboard table';
    var settings;

    const column = [ 
        { 'data' : 'TRANSMITTAL_DESCRIPTION' },
        { 'data' : 'TRANSMITTED_FROM' },
        { 'data' : 'TRANSMITTED_TO' },
        { 'data' : 'TRANSMITTAL_DATE' },
        { 'data' : 'STATUS' }
    ];

    const column_definition = [
        { 'width': '35%', 'aTargets': 0 },
        { 'width': '20%', 'aTargets': 1 },
        { 'width': '20%', 'aTargets': 2 },
        { 'width': '20%', 'aTargets': 3 },
        { 'width': '5%', 'aTargets': 4 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_transmittal_generation.php',
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
        'order': [[ 4, 'desc' ]],
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

function transmittalList(){
    const type = 'transmittal dashboard list';
        
    $.ajax({
        type: 'POST',
        url: 'view/_transmittal_generation.php',
        data: 'type=' + type,
        dataType: 'json',
        success: function (response) {
            document.getElementById('dashboard-transmittal-list').innerHTML = response.LIST;
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

function dashboardInitialApproval(datatable_name, buttons = false, show_all = false){
    const type = 'dashboard for initial approval table';
    var settings;

    const column = [ 
        { 'data' : 'CUSTOMER' },
        { 'data' : 'PRODUCT' },
        { 'data' : 'STATUS' },
        { 'data' : 'SALES_PROPOSAL_NUMBER' },
        { 'data' : 'PRODUCT_TYPE' },
        { 'data' : 'CREATED_DATE' }
    ];

    const column_definition = [
        { 'width': '14%', 'aTargets': 0 },
        { 'width': '15%', 'aTargets': 1 },
        { 'width': '10%', 'aTargets': 2 },
        { 'width': '15%', 'aTargets': 3 },
        { 'width': '10%', 'aTargets': 4 },
        { 'width': '15%', 'type': 'date', 'aTargets': 5 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_sales_proposal_generation.php',
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
        'order': [[ 5, 'desc' ]],
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

function dashboardFinalApproval(datatable_name, buttons = false, show_all = false){
    const type = 'dashboard for final approval table';
    var settings;

    const column = [ 
        { 'data' : 'CUSTOMER' },
        { 'data' : 'PRODUCT' },
        { 'data' : 'STATUS' },
        { 'data' : 'SALES_PROPOSAL_NUMBER' },
        { 'data' : 'PRODUCT_TYPE' },
        { 'data' : 'CREATED_DATE' }
    ];

    const column_definition = [
        { 'width': '14%', 'aTargets': 0 },
        { 'width': '15%', 'aTargets': 1 },
        { 'width': '10%', 'aTargets': 2 },
        { 'width': '15%', 'aTargets': 3 },
        { 'width': '10%', 'aTargets': 4 },
        { 'width': '15%', 'type': 'date', 'aTargets': 5 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_sales_proposal_generation.php',
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
        'order': [[ 5, 'desc' ]],
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

function dashboardDocumentTable(datatable_name, buttons = false, show_all = false){
    const type = 'dashboard document table';
    var settings;

    const column = [ 
        { 'data' : 'DOCUMENT_NAME' },
        { 'data' : 'DOCUMENT_CATEGORY' },
        { 'data' : 'AUTHOR' },
        { 'data' : 'UPLOAD_DATE' },
        { 'data' : 'DOCUMENT_STATUS' },
    ];

    const column_definition = [
        { 'width': '10%', 'aTargets': 0 },
        { 'width': '15%', 'aTargets': 1 },
        { 'width': '15%', 'aTargets': 2 },
        { 'width': '15%', 'type': 'date', 'aTargets': 3 },
        { 'width': '15%', 'aTargets': 4 },
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_document_generation.php',
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
        'order': [[ 3, 'desc' ]],
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

function travelApprovalFormTable(datatable_name, buttons = false, show_all = false){
    const type = 'travel form dashboard table';
    var settings;

    const column = [ 
        { 'data' : 'CREATED_BY' },
        { 'data' : 'CHECKED_BY' },
        { 'data' : 'CHECKED_DATE' },
        { 'data' : 'RECOMMENDED_BY' },
        { 'data' : 'RECOMMENDED_DATE' },
        { 'data' : 'APPROVAL_BY' },
        { 'data' : 'APPROVAL_DATE' },
        { 'data' : 'STATUS' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': 'auto', 'aTargets': 0 },
        { 'width': 'auto', 'aTargets': 1 },
        { 'width': 'auto', 'aTargets': 2 },
        { 'width': 'auto', 'aTargets': 3 },
        { 'width': 'auto', 'aTargets': 4 },
        { 'width': 'auto', 'aTargets': 5 },
        { 'width': 'auto', 'aTargets': 6 },
        { 'width': 'auto', 'aTargets': 7 },
        { 'width': '15%','bSortable': false, 'aTargets': 8 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_travel_form_generation.php',
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
        'order': [[ 4, 'desc' ]],
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

function travelApprovalFormList(){
    const type = 'travel form dashboard list';
        
    $.ajax({
        type: 'POST',
        url: 'view/_travel_form_generation.php',
        data: 'type=' + type,
        dataType: 'json',
        success: function (response) {
            document.getElementById('travel-approval-form-list').innerHTML = response.LIST;
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

function partsIncomingTable(datatable_name, buttons = false, show_all = false){
    const type = 'parts incoming dashboard table';
    var view_cost = $('#view-cost').val();

    var settings;

    if(view_cost > 0){
        var column = [
            { 'data' : 'TRANSACTION_ID' },
            { 'data' : 'PRODUCT' },
            { 'data' : 'LINES' },
            { 'data' : 'QUANTITY' },
            { 'data' : 'COST' },
            { 'data' : 'TRANSACTION_DATE' },
            { 'data' : 'STATUS' },
            { 'data' : 'ACTION' }
        ];

        var column_definition = [
            { 'width': 'auto', 'aTargets': 0 },
            { 'width': 'auto', 'aTargets': 1 },
            { 'width': 'auto', 'aTargets': 2 },
            { 'width': 'auto', 'aTargets': 3 },
            { 'width': 'auto', 'aTargets': 4 },
            { 'width': 'auto', 'type': 'date', 'aTargets': 5 },
            { 'width': 'auto', 'aTargets': 6 },
            { 'width': '15%','bSortable': false, 'aTargets': 7 }
        ];
    }
    else{
          var column = [
            { 'data' : 'TRANSACTION_ID' },
            { 'data' : 'PRODUCT' },
            { 'data' : 'LINES' },
            { 'data' : 'QUANTITY' },
            { 'data' : 'TRANSACTION_DATE' },
            { 'data' : 'STATUS' },
            { 'data' : 'ACTION' }
        ];

        var column_definition = [
            { 'width': 'auto', 'aTargets': 0 },
            { 'width': 'auto', 'aTargets': 1 },
            { 'width': 'auto', 'aTargets': 2 },
            { 'width': 'auto', 'aTargets': 3 },
            { 'width': 'auto', 'type': 'date', 'aTargets': 4 },
            { 'width': 'auto', 'aTargets': 5 },
            { 'width': '15%','bSortable': false, 'aTargets': 6 }
        ];
    }

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_parts_incoming_generation.php',
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
        'order': [[ 0, 'desc' ]],
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

function partsIncomingList(){
    const type = 'parts incoming dashboard list';
        
    $.ajax({
        type: 'POST',
        url: 'view/_parts_incoming_generation.php',
        data: 'type=' + type,
        dataType: 'json',
        success: function (response) {
            document.getElementById('parts-incoming-dashboard-list').innerHTML = response.LIST;
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

function partsTransactionTable(datatable_name, buttons = false, show_all = false){
    const type = 'parts transaction dashboard table';

    var settings;

    const column = [
        { 'data' : 'TRANSACTION_ID' },
        { 'data' : 'CUSTOMER_TYPE' },
        { 'data' : 'REFERENCE' },
        { 'data' : 'NUMBER_OF_ITEMS' },
        { 'data' : 'TOTAL_AMOUNT' },
        { 'data' : 'TRANSACTION_DATE' },
        { 'data' : 'ISSUANCE_DATE' },
        { 'data' : 'STATUS' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': 'auto', 'aTargets': 0 },
        { 'width': 'auto', 'aTargets': 1 },
        { 'width': 'auto', 'aTargets': 2 },
        { 'width': 'auto', 'aTargets': 3 },
        { 'width': 'auto', 'aTargets': 4 },
        { 'width': 'auto', 'aTargets': 5 },
        { 'width': 'auto', 'type': 'date', 'aTargets': 6 },
        { 'width': 'auto', 'aTargets': 7 },
        { 'width': '15%','bSortable': false, 'aTargets': 8 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_parts_transaction_generation.php',
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
        'order': [[ 0, 'desc' ]],
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

function partsTransactionList(){
    const type = 'parts transaction dashboard list';
        
    $.ajax({
        type: 'POST',
        url: 'view/_parts_transaction_generation.php',
        data: 'type=' + type,
        dataType: 'json',
        success: function (response) {
            document.getElementById('parts-transaction-dashboard-list').innerHTML = response.LIST;
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

function internalJobOrderList(){
    const type = 'backjob monitoring list';
        
    $.ajax({
        type: 'POST',
        url: 'view/_back_job_monitoring_generation.php',
        data: 'type=' + type,
        dataType: 'json',
        success: function (response) {
            document.getElementById('dashboard-internal-job-order-list').innerHTML = response.LIST;
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