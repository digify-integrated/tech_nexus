(function($) {
    'use strict';

    $(function() {
        if($('#record-attendance-form').length){
            recordAttendanceForm();
        }

        if($('#dashboard-transmittal-table').length){
            transmittalTable('#dashboard-transmittal-table');
        }

        if($('#travel-approval-form-table').length){
            travelApprovalFormTable('#travel-approval-form-table');
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

        document.querySelector('#record-attendance').addEventListener('click', async function() {
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
        });
    });
})(jQuery);

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