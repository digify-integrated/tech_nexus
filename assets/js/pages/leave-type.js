(function($) {
    'use strict';

    $(function() {
        if($('#leave-type-table').length){
            leaveTypeTable('#leave-type-table');
        }

        if($('#leave-type-form').length){
            leaveTypeForm();
        }

        if($('#leave-type-id').length){
            displayDetails('get leave type details');
        }

        $(document).on('click','.delete-leave-type',function() {
            const leave_type_id = $(this).data('leave-type-id');
            const transaction = 'delete leave type';
    
            Swal.fire({
                title: 'Confirm Leave Type Deletion',
                text: 'Are you sure you want to delete this leave type?',
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
                        url: 'controller/leave-type-controller.php',
                        dataType: 'json',
                        data: {
                            leave_type_id : leave_type_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete Leave Type Success', 'The leave type has been deleted successfully.', 'success');
                                reloadDatatable('#leave-type-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Delete Leave Type Error', 'The leave type does not exist.', 'danger');
                                    reloadDatatable('#leave-type-table');
                                }
                                else {
                                    showNotification('Delete Leave Type Error', response.message, 'danger');
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

        $(document).on('click','#delete-leave-type',function() {
            let leave_type_id = [];
            const transaction = 'delete multiple leave type';

            $('.datatable-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    leave_type_id.push(element.value);
                }
            });
    
            if(leave_type_id.length > 0){
                Swal.fire({
                    title: 'Confirm Multiple Leave Types Deletion',
                    text: 'Are you sure you want to delete these leave types?',
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
                            url: 'controller/leave-type-controller.php',
                            dataType: 'json',
                            data: {
                                leave_type_id: leave_type_id,
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Delete Leave Type Success', 'The selected leave types have been deleted successfully.', 'success');
                                        reloadDatatable('#leave-type-table');
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else {
                                        showNotification('Delete Leave Type Error', response.message, 'danger');
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
                showNotification('Deletion Multiple Leave Type Error', 'Please select the leave types you wish to delete.', 'danger');
            }
        });

        $(document).on('click','#delete-leave-type-details',function() {
            const leave_type_id = $('#leave-type-id').text();
            const transaction = 'delete leave type';
    
            Swal.fire({
                title: 'Confirm Leave Type Deletion',
                text: 'Are you sure you want to delete this leave type?',
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
                        url: 'controller/leave-type-controller.php',
                        dataType: 'json',
                        data: {
                            leave_type_id : leave_type_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Deleted Leave Type Success', 'The leave type has been deleted successfully.', 'success');
                                window.location = 'leave-type.php';
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
                                    showNotification('Delete Leave Type Error', response.message, 'danger');
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
            discardCreate('leave-type.php');
        });

        $(document).on('click','#edit-form',function() {
            displayDetails('get leave type details');

            enableForm();
        });

        $(document).on('click','#duplicate-leave-type',function() {
            const leave_type_id = $('#leave-type-id').text();
            const transaction = 'duplicate leave type';
    
            Swal.fire({
                title: 'Confirm Leave Type Duplication',
                text: 'Are you sure you want to duplicate this leave type?',
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
                        url: 'controller/leave-type-controller.php',
                        dataType: 'json',
                        data: {
                            leave_type_id : leave_type_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Duplicate Leave Type Success', 'The leave type has been duplicated successfully.', 'success');
                                window.location = 'leave-type.php?id=' + response.leaveTypeID;
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Duplicate Leave Type Error', 'The leave type does not exist.', 'danger');
                                    reloadDatatable('#leave-type-table');
                                }
                                else {
                                    showNotification('Duplicate Leave Type Error', response.message, 'danger');
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

        $(document).on('click','#apply-filter',function() {
            leaveTypeTable('#leave-type-table');
        });
    });
})(jQuery);

function leaveTypeTable(datatable_name, buttons = false, show_all = false){
    const type = 'leave type table';
    var filter_is_paid = $('.is-paid-filter:checked').val();
    var settings;

    const column = [ 
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'LEAVE_TYPE' },
        { 'data' : 'IS_PAID' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '1%','bSortable': false, 'aTargets': 0 },
        { 'width': 'auto', 'aTargets': 1 },
        { 'width': 'auto', 'aTargets': 2 },
        { 'width': '15%','bSortable': false, 'aTargets': 3 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_leave_type_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {'type' : type, 'filter_is_paid' : filter_is_paid},
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

function leaveTypeForm(){
    $('#leave-type-form').validate({
        rules: {
            leave_type_name: {
                required: true
            },
            is_paid: {
                required: true
            }
        },
        messages: {
            leave_type_name: {
                required: 'Please enter the leave type name'
            },
            is_paid: {
                required: 'Please choose if the leave type is paid or not'
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
            const leave_type_id = $('#leave-type-id').text();
            const transaction = 'save leave type';
        
            $.ajax({
                type: 'POST',
                url: 'controller/leave-type-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&leave_type_id=' + leave_type_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-data');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert Leave Type Success' : 'Update Leave Type Success';
                        const notificationDescription = response.insertRecord ? 'The leave type has been inserted successfully.' : 'The leave type has been updated successfully.';
                        
                        setNotification(notificationMessage, notificationDescription, 'success');
                        window.location = 'leave-type.php?id=' + response.leaveTypeID;
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
        case 'get leave type details':
            const leave_type_id = $('#leave-type-id').text();
            
            $.ajax({
                url: 'controller/leave-type-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    leave_type_id : leave_type_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetForm('leave-type-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#leave_type_id').val(leave_type_id);
                        $('#leave_type_name').val(response.leaveTypeName);

                        checkOptionExist('#is_paid', response.isPaid, '');

                        $('#leave_type_name_label').text(response.leaveTypeName);
                        $('#is_paid_label').text(response.isPaid);
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Leave Type Details Error', response.message, 'danger');
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