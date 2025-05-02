(function($) {
    'use strict';

    $(function() {
        if($('#leave-application-table').length){
            leaveApplicationTable('#leave-application-table');
        }
        if($('#manual-leave-application-table').length){
            manualLeaveApplicationTable('#manual-leave-application-table');
        }

        if($('#leave-approval-table').length){
            leaveApprovalTable('#leave-approval-table');
        }

        if($('#leave-recommendation-table').length){
            leaveRecommendationTable('#leave-recommendation-table');
        }

        if($('#leave-summary-table').length){
            leaveSummaryTable('#leave-summary-table', true, true);
        }

        if($('#leave-application-form').length){
            leaveApplicationForm();
        }

        if($('#leave-application-cancel-form').length){
            leaveApplicationCancelForm();
        }

        if($('#leave-application-reject-form').length){
            leaveApplicationRejectForm();
        }

        if($('#leave-application-id').length){
            displayDetails('get leave application details');
        }

        if($('#leave-form-image-form').length){
            leaveFormImageForm();
        }

        $(document).on('change', '#leave_type_id', function() {
            const type = $(this).val();
            const $silGroup = $('.sil-group');
            const $leaveGroup = $('.leave-group');
        
            if (type === '1') {
                $silGroup.removeClass('d-none');
                $leaveGroup.addClass('d-none');
            } else {
                $leaveGroup.removeClass('d-none');
                $silGroup.addClass('d-none');
            }
        });

        $(document).on('click','.delete-leave-application',function() {
            const leave_application_id = $(this).data('leave-application-id');
            const transaction = 'delete leave application';
    
            Swal.fire({
                title: 'Confirm Leave Application Deletion',
                text: 'Are you sure you want to delete this leave application?',
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
                        url: 'controller/leave-application-controller.php',
                        dataType: 'json',
                        data: {
                            leave_application_id : leave_application_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete Leave Application Success', 'The leave application has been deleted successfully.', 'success');
                                reloadDatatable('#leave-application-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Delete Leave Application Error', 'The leave application does not exist.', 'danger');
                                    reloadDatatable('#leave-application-table');
                                }
                                else {
                                    showNotification('Delete Leave Application Error', response.message, 'danger');
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

        $(document).on('click','#delete-leave-application',function() {
            let leave_application_id = [];
            const transaction = 'delete multiple leave application';

            $('.datatable-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    leave_application_id.push(element.value);
                }
            });
    
            if(leave_application_id.length > 0){
                Swal.fire({
                    title: 'Confirm Multiple Leave Applications Deletion',
                    text: 'Are you sure you want to delete these leave applications?',
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
                            url: 'controller/leave-application-controller.php',
                            dataType: 'json',
                            data: {
                                leave_application_id: leave_application_id,
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Delete Leave Application Success', 'The selected leave applications have been deleted successfully.', 'success');
                                        reloadDatatable('#leave-application-table');
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else {
                                        showNotification('Delete Leave Application Error', response.message, 'danger');
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
                showNotification('Deletion Multiple Leave Application Error', 'Please select the leave applications you wish to delete.', 'danger');
            }
        });

        $(document).on('click','#delete-leave-application-details',function() {
            const leave_application_id = $('#leave-application-id').text();
            const transaction = 'delete leave application';
    
            Swal.fire({
                title: 'Confirm Leave Application Deletion',
                text: 'Are you sure you want to delete this leave application?',
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
                        url: 'controller/leave-application-controller.php',
                        dataType: 'json',
                        data: {
                            leave_application_id : leave_application_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Deleted Leave Application Success', 'The leave application has been deleted successfully.', 'success');
                                
                                if($("#creation_type").val() === "manual"){
                                    window.location = 'manual-leave-application.php';
                                }
                                else{
                                    window.location = 'leave-application.php';
                                }                                
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
                                    showNotification('Delete Leave Application Error', response.message, 'danger');
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

        $(document).on('click','#tag-leave-application-for-recommendation',function() {
            const leave_application_id = $('#leave-application-id').text();
            const transaction = 'leave application for recommendation';
    
            Swal.fire({
                title: 'Confirm Tagging Leave Application For Recommendation',
                text: 'Are you sure you want to tag this leave application for recommendation?',
                icon: 'warning',
                showCancelButton: !0,
                confirmButtonText: 'For Recommendation',
                cancelButtonText: 'Cancel',
                confirmButtonClass: 'btn btn-info mt-2',
                cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                buttonsStyling: !1
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        type: 'POST',
                        url: 'controller/leave-application-controller.php',
                        dataType: 'json',
                        data: {
                            leave_application_id : leave_application_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Leave Application For Recommendation Success', 'The leave application has been tagged for recommendation.', 'success');
                                window.location.reload();
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.leaveFormEmpty) {
                                    showNotification('Leave Application For Recommendation Error', 'The leave form is empty.', 'danger');
                                }
                                else if (response.notExist) {
                                    window.location = '404.php';
                                }
                                else {
                                    showNotification('Delete Leave Application Error', response.message, 'danger');
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

        $(document).on('click','#tag-leave-application-recommendation',function() {
            const leave_application_id = $('#leave-application-id').text();
            const transaction = 'leave application recommendation';
    
            Swal.fire({
                title: 'Confirm Tagging Leave Application Recommendation',
                text: 'Are you sure you want to tag this leave application as recommended?',
                icon: 'warning',
                showCancelButton: !0,
                confirmButtonText: 'Recommended',
                cancelButtonText: 'Cancel',
                confirmButtonClass: 'btn btn-success mt-2',
                cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                buttonsStyling: !1
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        type: 'POST',
                        url: 'controller/leave-application-controller.php',
                        dataType: 'json',
                        data: {
                            leave_application_id : leave_application_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Leave Application Recommendation Success', 'The leave application has been tagged as recommended.', 'success');
                                window.location.reload();
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
                                    showNotification('Delete Leave Application Error', response.message, 'danger');
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

        $(document).on('click','#tag-leave-application-approve',function() {
            const leave_application_id = $('#leave-application-id').text();
            const transaction = 'leave application approval';
    
            Swal.fire({
                title: 'Confirm Tagging Leave Application Approval',
                text: 'Are you sure you want to tag this leave application as approved?',
                icon: 'warning',
                showCancelButton: !0,
                confirmButtonText: 'Approved',
                cancelButtonText: 'Cancel',
                confirmButtonClass: 'btn btn-success mt-2',
                cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                buttonsStyling: !1
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        type: 'POST',
                        url: 'controller/leave-application-controller.php',
                        dataType: 'json',
                        data: {
                            leave_application_id : leave_application_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Leave Application Approval Success', 'The leave application has been tagged as approved.', 'success');
                                window.location.reload();
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.entitlementZero) {
                                    showNotification('Leave Application Approval Error', 'The employee does not have enough leave entitlement.', 'danger');
                                }
                                else if (response.notExist) {
                                    window.location = '404.php';
                                }
                                else {
                                    showNotification('Delete Leave Application Error', response.message, 'danger');
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

        $(document).on('click','#tag-leave-application-approve',function() {
            let leave_application_id = [];
            const transaction = 'approve multiple leave';

            $('.datatable-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    leave_application_id.push(element.value);
                }
            });
    
            if(leave_application_id.length > 0){
                Swal.fire({
                    title: 'Confirm Multiple Leave Approval',
                    text: 'Are you sure you want to approve these leave?',
                    icon: 'warning',
                    showCancelButton: !0,
                    confirmButtonText: 'Approve',
                    cancelButtonText: 'Cancel',
                    confirmButtonClass: 'btn btn-success mt-2',
                    cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                    buttonsStyling: !1
                }).then(function(result) {
                    if (result.value) {
                        $.ajax({
                            type: 'POST',
                            url: 'controller/leave-application-controller.php',
                            dataType: 'json',
                            data: {
                                leave_application_id: leave_application_id,
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Approve Leave Success', 'The selected leave have been approved successfully.', 'success');
                                        reloadDatatable('#leave-approval-table');
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else {
                                        showNotification('Approve Leave Error', response.message, 'danger');
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
                showNotification('Approve Multiple Leave Error', 'Please select the leave you wish to delete.', 'danger');
            }
        });

        $(document).on('click','#discard-create',function() {
            if($("#creation_type").val() === "manual"){
                discardCreate('manual-leave-application.php');
            }
            else{
                discardCreate('leave-application.php');
            }            
        });

        $(document).on('click','#edit-form',function() {
            displayDetails('get leave application details');

            enableForm();
        });

        $(document).on('click','#apply-filter',function() {
            if($('#leave-application-table').length){
                leaveApplicationTable('#leave-application-table');
            }

            if($('#leave-summary-table').length){
                leaveSummaryTable('#leave-summary-table', true, true);
            }            
        });
    });
})(jQuery);

function leaveApplicationTable(datatable_name, buttons = false, show_all = false){
    const type = 'leave application table';
    var settings;

    const column = [ 
        { 'data' : 'LEAVE_TYPE' },
        { 'data' : 'LEAVE_DATE' },
        { 'data' : 'APPLICATION_DATE' },
        { 'data' : 'STATUS' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': 'auto', 'aTargets': 0 },
        { 'width': 'auto', 'aTargets': 1 },
        { 'width': 'auto', 'aTargets': 2 },
        { 'width': 'auto', 'aTargets': 3 },
        { 'width': '15%','bSortable': false, 'aTargets': 4 }
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

function manualLeaveApplicationTable(datatable_name, buttons = false, show_all = false){
    const type = 'manual leave application table';
    var settings;

    const column = [ 
        { 'data' : 'FILE_AS' },
        { 'data' : 'LEAVE_TYPE' },
        { 'data' : 'LEAVE_DATE' },
        { 'data' : 'APPLICATION_DATE' },
        { 'data' : 'STATUS' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': 'auto', 'aTargets': 0 },
        { 'width': 'auto', 'aTargets': 1 },
        { 'width': 'auto', 'aTargets': 2 },
        { 'width': 'auto', 'aTargets': 3 },
        { 'width': 'auto', 'aTargets': 4 },
        { 'width': '15%','bSortable': false, 'aTargets': 5 }
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

function leaveApprovalTable(datatable_name, buttons = false, show_all = false){    
    const type = 'leave approval table';
    var settings;

    const column = [ 
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'FILE_AS' },
        { 'data' : 'LEAVE_TYPE' },
        { 'data' : 'LEAVE_DATE' },
        { 'data' : 'APPLICATION_DATE' },
        { 'data' : 'STATUS' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '1%','bSortable': false, 'aTargets': 0 },
        { 'width': 'auto', 'aTargets': 1 },
        { 'width': 'auto', 'aTargets': 2 },
        { 'width': 'auto', 'aTargets': 3 },
        { 'width': 'auto', 'aTargets': 4 },
        { 'width': 'auto', 'aTargets': 5 },
        { 'width': '15%','bSortable': false, 'aTargets': 6 }
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
        'order': [[ 2, 'asc' ]],
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

function leaveRecommendationTable(datatable_name, buttons = false, show_all = false){
    const type = 'leave recommendation table';
    var settings;

    const column = [ 
        { 'data' : 'FILE_AS' },
        { 'data' : 'LEAVE_TYPE' },
        { 'data' : 'LEAVE_DATE' },
        { 'data' : 'APPLICATION_DATE' },
        { 'data' : 'STATUS' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': 'auto', 'aTargets': 0 },
        { 'width': 'auto', 'aTargets': 1 },
        { 'width': 'auto', 'aTargets': 2 },
        { 'width': 'auto', 'aTargets': 3 },
        { 'width': 'auto', 'aTargets': 4 },
        { 'width': '15%','bSortable': false, 'aTargets': 5 }
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

function leaveSummaryTable(datatable_name, buttons = false, show_all = false){
    const type = 'leave summary table';
    var leave_status_filter = $('.leave-status-filter:checked').val();
    var filter_leave_start_date = $('#filter_leave_start_date').val();
    var filter_leave_end_date = $('#filter_leave_end_date').val();
    var filter_application_start_date = $('#filter_application_start_date').val();
    var filter_application_end_date = $('#filter_application_end_date').val();
    var filter_approval_start_date = $('#filter_approval_start_date').val();
    var filter_approval_end_date = $('#filter_approval_end_date').val();

    var settings;

    const column = [
        { 'data' : 'FILE_AS' },
        { 'data' : 'LEAVE_TYPE' },
        { 'data' : 'LEAVE_DATE' },
        { 'data' : 'APPLICATION_DATE' },
        { 'data' : 'APPROVAL_DATE' },
        { 'data' : 'STATUS' }
    ];

    const column_definition = [
        { 'width': 'auto', 'aTargets': 0 },
        { 'width': 'auto', 'aTargets': 1 },
        { 'width': 'auto', 'aTargets': 2 },
        { 'width': 'auto', 'aTargets': 3 },
        { 'width': 'auto', 'aTargets': 4 },
        { 'width': 'auto', 'aTargets': 5 },
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_leave_application_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {
                'type' : type,
                'leave_status_filter' : leave_status_filter,
                'filter_leave_start_date' : filter_leave_start_date,
                'filter_leave_end_date' : filter_leave_end_date,
                'filter_application_start_date' : filter_application_start_date,
                'filter_application_end_date' : filter_application_end_date,
                'filter_approval_start_date' : filter_approval_start_date,
                'filter_approval_end_date' : filter_approval_end_date
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

function leaveApplicationForm(){
    $('#leave-application-form').validate({
        rules: {
            leave_type_id: {
                required: true
            },
            leave_date: {
                required: true
            },
            leave_start_time: {
                required: function() {
                    return $("#leave_type_id").val() !== "1";
                }
            },
            leave_end_time: {
                required: function() {
                    return $("#leave_type_id").val() !== "1";
                }
            },
            application_type: {
                required: function() {
                    return $("#leave_type_id").val() === "1";
                }
            },
            employee_id: {
                required: function() {
                    return $("#creation_type").val() === "manual";
                }
            },
            reason: {
                required: true
            }
        },
        messages: {
            leave_type_id: {
                required: 'Please choose the leave type'
            },
            leave_date: {
                required: 'Please choose the leave date'
            },
            leave_start_time: {
                required: 'Please choose the start time'
            },
            leave_end_time: {
                required: 'Please choose the end time'
            },
            application_type: {
                required: 'Please choose the application type'
            },
            reason: {
                required: 'Please enter the leave reason'
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
            const leave_application_id = $('#leave-application-id').text();
            const transaction = 'save leave application';
        
            $.ajax({
                type: 'POST',
                url: 'controller/leave-application-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&leave_application_id=' + leave_application_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-data');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert Leave Application Success' : 'Update Leave Application Success';
                        const notificationDescription = response.insertRecord ? 'The leave application has been inserted successfully.' : 'The leave application has been updated successfully.';
                        
                        setNotification(notificationMessage, notificationDescription, 'success');

                        if($("#creation_type").val() === "manual"){
                            window.location = 'manual-leave-application.php?id=' + response.leaveApplicationID;
                        }
                        else{
                            window.location = 'leave-application.php?id=' + response.leaveApplicationID;
                        }
                       
                    }
                    else {
                        if (response.isInactive) {
                            setNotification('User Inactive', response.message, 'danger');
                            window.location = 'logout.php?logout';
                        }
                        else if (response.entitlementZero) {
                            showNotification('Leave Application Error', 'You do not have enough leave entitlement.', 'danger');
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

function leaveApplicationCancelForm(){
    $('#leave-application-cancel-form').validate({
        rules: {
            cancellation_reason: {
                required: true
            }
        },
        messages: {
            cancellation_reason: {
                required: 'Please enter the cancellation reason'
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
            const leave_application_id = $('#leave-application-id').text();
            const transaction = 'leave application cancel';
        
            $.ajax({
                type: 'POST',
                url: 'controller/leave-application-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&leave_application_id=' + leave_application_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-leave-application-cancel');
                },
                success: function (response) {
                    if (response.success) {
                        setNotification('Cancel Leave Application Success', 'The leave application has been cancelled successfully.', 'success');
                        window.location.reload();
                    }
                    else{
                        if (response.isInactive) {
                            setNotification('User Inactive', response.message, 'danger');
                            window.location = 'logout.php?logout';
                        } else {
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
                    enableFormSubmitButton('submit-leave-application-cancel', 'Submit');
                    $('#leave-application-cancel-offcanvas').offcanvas('hide');
                }
            });
        
            return false;
        }
    });
}

function leaveApplicationRejectForm(){
    $('#leave-application-reject-form').validate({
        rules: {
            rejection_reason: {
                required: true
            }
        },
        messages: {
            rejection_reason: {
                required: 'Please enter the rejection reason'
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
            const leave_application_id = $('#leave-application-id').text();
            const transaction = 'leave application reject';
        
            $.ajax({
                type: 'POST',
                url: 'controller/leave-application-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&leave_application_id=' + leave_application_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-leave-application-reject');
                },
                success: function (response) {
                    if (response.success) {
                        setNotification('Reject Leave Application Success', 'The leave application has been rejected successfully.', 'success');
                        window.location.reload();
                    }
                    else{
                        if (response.isInactive) {
                            setNotification('User Inactive', response.message, 'danger');
                            window.location = 'logout.php?logout';
                        } else {
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
                    enableFormSubmitButton('submit-leave-application-reject', 'Submit');
                    $('#leave-application-reject-offcanvas').offcanvas('hide');
                }
            });
        
            return false;
        }
    });
}

function leaveFormImageForm(){
    $('#leave-form-image-form').validate({
        rules: {
            leave_form_image: {
                required: true
            },
        },
        messages: {
            leave_form_image: {
                required: 'Please choose the leave form'
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
            const leave_application_id = $('#leave-application-id').text();
            const transaction = 'save leave form image';
    
            var formData = new FormData(form);
            formData.append('leave_application_id', leave_application_id);
            formData.append('transaction', transaction);
        
            $.ajax({
                type: 'POST',
                url: 'controller/leave-application-controller.php',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-leave-form-image');
                },
                success: function (response) {
                    if (response.success) {
                        setNotification('Leave From Upload Success', 'The leave form has been uploaded successfully', 'success');
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
                    enableFormSubmitButton('submit-leave-form-image', 'Submit');
                }
            });
        
            return false;
        }
    });
}

function displayDetails(transaction){
    switch (transaction) {
        case 'get leave application details':
            const leave_application_id = $('#leave-application-id').text();
            
            $.ajax({
                url: 'controller/leave-application-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    leave_application_id : leave_application_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetForm('leave-application-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#leave_date').val(response.leaveDate);
                        $('#leave_start_time').val(response.leaveStartTime);
                        $('#leave_end_time').val(response.leaveEndTime);
                        $('#reason').val(response.reason);

                        checkOptionExist('#leave_type_id', response.leaveTypeID, '');
                        checkOptionExist('#application_type', response.applicationType, '');
                        checkOptionExist('#employee_id', response.contactID, '');

                        $('#leave_type_id_label').text(response.leaveTypeName);
                        $('#leave_date_label').text(response.leaveDate);
                        $('#leave_start_time_label').text(response.leaveStartTimeLabel);
                        $('#leave_end_time_label').text(response.leaveEndTimeLabel);
                        $('#reason_label').text(response.reason);

                        document.getElementById('leave-form').src = response.leaveForm;

                        if(response.status != 'Draft'){
                            disableFormAndSelect2('leave-application-form');
                        }
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Leave Application Details Error', response.message, 'danger');
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

function disableFormAndSelect2(formId) {
    // Disable all form elements
    var form = document.getElementById(formId);
    var elements = form.elements;
    for (var i = 0; i < elements.length; i++) {
        elements[i].disabled = true;
    }

    // Disable Select2 dropdowns
    var select2Dropdowns = form.getElementsByClassName('select2');
    for (var j = 0; j < select2Dropdowns.length; j++) {
        var select2Instance = $(select2Dropdowns[j]);
        select2Instance.select2('destroy');
        select2Instance.prop('disabled', true);
    }
}