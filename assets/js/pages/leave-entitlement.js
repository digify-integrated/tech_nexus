(function($) {
    'use strict';

    $(function() {
        if($('#leave-entitlement-table').length){
            leaveEntitlementTable('#leave-entitlement-table');
        }

        if($('#leave-entitlement-form').length){
            leaveEntitlementForm();
        }

        if($('#leave-entitlement-id').length){
            displayDetails('get leave entitlement details');
        }

        $(document).on('click','.delete-leave-entitlement',function() {
            const leave_entitlement_id = $(this).data('leave-entitlement-id');
            const transaction = 'delete leave entitlement';
    
            Swal.fire({
                title: 'Confirm Leave Entitlement Deletion',
                text: 'Are you sure you want to delete this leave entitlement?',
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
                        url: 'controller/leave-entitlement-controller.php',
                        dataType: 'json',
                        data: {
                            leave_entitlement_id : leave_entitlement_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete Leave Entitlement Success', 'The leave entitlement has been deleted successfully.', 'success');
                                reloadDatatable('#leave-entitlement-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Delete Leave Entitlement Error', 'The leave entitlement does not exist.', 'danger');
                                    reloadDatatable('#leave-entitlement-table');
                                }
                                else {
                                    showNotification('Delete Leave Entitlement Error', response.message, 'danger');
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

        $(document).on('click','#delete-leave-entitlement',function() {
            let leave_entitlement_id = [];
            const transaction = 'delete multiple leave entitlement';

            $('.datatable-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    leave_entitlement_id.push(element.value);
                }
            });
    
            if(leave_entitlement_id.length > 0){
                Swal.fire({
                    title: 'Confirm Multiple Leave Entitlements Deletion',
                    text: 'Are you sure you want to delete these leave entitlements?',
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
                            url: 'controller/leave-entitlement-controller.php',
                            dataType: 'json',
                            data: {
                                leave_entitlement_id: leave_entitlement_id,
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Delete Leave Entitlement Success', 'The selected leave entitlements have been deleted successfully.', 'success');
                                        reloadDatatable('#leave-entitlement-table');
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else {
                                        showNotification('Delete Leave Entitlement Error', response.message, 'danger');
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
                showNotification('Deletion Multiple Leave Entitlement Error', 'Please select the leave entitlements you wish to delete.', 'danger');
            }
        });

        $(document).on('click','#delete-leave-entitlement-details',function() {
            const leave_entitlement_id = $('#leave-entitlement-id').text();
            const transaction = 'delete leave entitlement';
    
            Swal.fire({
                title: 'Confirm Leave Entitlement Deletion',
                text: 'Are you sure you want to delete this leave entitlement?',
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
                        url: 'controller/leave-entitlement-controller.php',
                        dataType: 'json',
                        data: {
                            leave_entitlement_id : leave_entitlement_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Deleted Leave Entitlement Success', 'The leave entitlement has been deleted successfully.', 'success');
                                window.location = 'leave-entitlement.php';
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
                                    showNotification('Delete Leave Entitlement Error', response.message, 'danger');
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
            discardCreate('leave-entitlement.php');
        });

        $(document).on('click','#edit-form',function() {
            displayDetails('get leave entitlement details');

            enableForm();
        });

        $(document).on('click','#apply-filter',function() {
            leaveEntitlementTable('#leave-entitlement-table');
        });
    });
})(jQuery);

function leaveEntitlementTable(datatable_name, buttons = false, show_all = false){
    const type = 'leave entitlement table';
    var settings;

    const column = [ 
        { 'data' : 'EMPLOYEE' },
        { 'data' : 'LEAVE_TYPE' },
        { 'data' : 'ENTITLEMENT' },
        { 'data' : 'PERIOD_COVERED' },
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
            'url' : 'view/_leave_entitlement_generation.php',
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

function leaveEntitlementForm(){
    $('#leave-entitlement-form').validate({
        rules: {
            employee_id: {
                required: true
            },
            leave_type_id: {
                required: true
            },
            entitlement_amount: {
                required: true
            },
            leave_period_start: {
                required: true
            },
            leave_period_end: {
                required: true
            }
        },
        messages: {
            employee_id: {
                required: 'Please choose the employee'
            },
            leave_type_id: {
                required: 'Please choose the leave type'
            },
            entitlement_amount: {
                required: 'Please enter the entitlement amount'
            },
            leave_period_start: {
                required: 'Please choose the coverage start date'
            },
            leave_period_end: {
                required: 'Please choose the coverage end date'
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
            const leave_entitlement_id = $('#leave-entitlement-id').text();
            const transaction = 'save leave entitlement';
        
            $.ajax({
                type: 'POST',
                url: 'controller/leave-entitlement-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&leave_entitlement_id=' + leave_entitlement_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-data');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert Leave Entitlement Success' : 'Update Leave Entitlement Success';
                        const notificationDescription = response.insertRecord ? 'The leave entitlement has been inserted successfully.' : 'The leave entitlement has been updated successfully.';
                        
                        setNotification(notificationMessage, notificationDescription, 'success');
                        window.location = 'leave-entitlement.php?id=' + response.leaveEntitlementID;
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
        case 'get leave entitlement details':
            const leave_entitlement_id = $('#leave-entitlement-id').text();
            
            $.ajax({
                url: 'controller/leave-entitlement-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    leave_entitlement_id : leave_entitlement_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetForm('leave-entitlement-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#leave_entitlement_id').val(leave_entitlement_id);
                        $('#entitlement_amount').val(response.entitlementAmount);
                        $('#leave_period_start').val(response.leavePeriodStart);
                        $('#leave_period_end').val(response.leavePeriodEnd);

                        checkOptionExist('#employee_id', response.contactID, '');
                        checkOptionExist('#leave_type_id', response.leaveTypeID, '');

                        $('#employee_id_label').text(response.employeeName);
                        $('#leave_type_id_label').text(response.leaveTypeName);
                        $('#entitlement_amount_label').text(response.entitlementAmount);
                        $('#leave_period_start_label').text(response.leavePeriodStart);
                        $('#leave_period_end_label').text(response.leavePeriodEnd);
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Leave Entitlement Details Error', response.message, 'danger');
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