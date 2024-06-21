(function($) {
    'use strict';

    $(function() {
        if($('#leave-application-table').length){
            leaveApplicationTable('#leave-application-table');
        }

        if($('#leave-approval-table').length){
            leaveApprovalTable('#leave-approval-table');
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
                                window.location = 'leave-application.php';
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

        $(document).on('click','#tag-leave-application-for-approval',function() {
            const leave_application_id = $('#leave-application-id').text();
            const transaction = 'leave application for approval';
    
            Swal.fire({
                title: 'Confirm Tagging Leave Application For Approval',
                text: 'Are you sure you want to tag this leave application for approval?',
                icon: 'warning',
                showCancelButton: !0,
                confirmButtonText: 'For Approval',
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
                                setNotification('Leave Application For Approval Success', 'The leave application has been tagged for approval.', 'success');
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

        $(document).on('click','#discard-create',function() {
            discardCreate('leave-application.php');
        });

        $(document).on('click','#edit-form',function() {
            displayDetails('get leave application details');

            enableForm();
        });

        $(document).on('click','#apply-filter',function() {
            leaveApplicationTable('#leave-application-table');
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

function leaveApprovalTable(datatable_name, buttons = false, show_all = false){
    const type = 'leave approval table';
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
                required: true
            },
            leave_end_time: {
                required: true
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
                        window.location = 'leave-application.php?id=' + response.leaveApplicationID;
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
    $('#leave-application-cancel-form').validate({
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
                    disableFormSubmitButton('submit-leave-application-cancel');
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
                    enableFormSubmitButton('submit-leave-application-cancel', 'Submit');
                    $('#leave-application-cancel-offcanvas').offcanvas('hide');
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

                        $('#leave_type_id_label').text(response.leaveTypeName);
                        $('#leave_date_label').text(response.leaveDate);
                        $('#leave_start_time_label').text(response.leaveStartTimeLabel);
                        $('#leave_end_time_label').text(response.leaveEndTimeLabel);
                        $('#reason_label').text(response.reason);
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