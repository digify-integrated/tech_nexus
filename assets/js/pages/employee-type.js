(function($) {
    'use strict';

    $(function() {
        if($('#employee-type-table').length){
            employeeTypeTable('#employee-type-table');
        }

        if($('#employee-type-form').length){
            employeeTypeForm();
        }

        if($('#employee-type-id').length){
            displayDetails('get employee type details');
        }

        $(document).on('click','.delete-employee-type',function() {
            const employee_type_id = $(this).data('employee-type-id');
            const transaction = 'delete employee type';
    
            Swal.fire({
                title: 'Confirm Employee Type Deletion',
                text: 'Are you sure you want to delete this employee type?',
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
                        url: 'controller/employee-type-controller.php',
                        dataType: 'json',
                        data: {
                            employee_type_id : employee_type_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete Employee Type Success', 'The employee type has been deleted successfully.', 'success');
                                reloadDatatable('#employee-type-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Delete Employee Type Error', 'The employee type does not exist.', 'danger');
                                    reloadDatatable('#employee-type-table');
                                }
                                else {
                                    showNotification('Delete Employee Type Error', response.message, 'danger');
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

        $(document).on('click','#delete-employee-type',function() {
            let employee_type_id = [];
            const transaction = 'delete multiple employee type';

            $('.datatable-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    employee_type_id.push(element.value);
                }
            });
    
            if(employee_type_id.length > 0){
                Swal.fire({
                    title: 'Confirm Multiple Employee Types Deletion',
                    text: 'Are you sure you want to delete these employee types?',
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
                            url: 'controller/employee-type-controller.php',
                            dataType: 'json',
                            data: {
                                employee_type_id: employee_type_id,
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Delete Employee Type Success', 'The selected employee types have been deleted successfully.', 'success');
                                    reloadDatatable('#employee-type-table');
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else {
                                        showNotification('Delete Employee Type Error', response.message, 'danger');
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
                showNotification('Deletion Multiple Employee Type Error', 'Please select the employee types you wish to delete.', 'danger');
            }
        });

        $(document).on('click','#delete-employee-type-details',function() {
            const employee_type_id = $('#employee-type-id').text();
            const transaction = 'delete employee type';
    
            Swal.fire({
                title: 'Confirm Employee Type Deletion',
                text: 'Are you sure you want to delete this employee type?',
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
                        url: 'controller/employee-type-controller.php',
                        dataType: 'json',
                        data: {
                            employee_type_id : employee_type_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Deleted Employee Type Success', 'The employee type has been deleted successfully.', 'success');
                                window.location = 'employee-type.php';
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
                                    showNotification('Delete Employee Type Error', response.message, 'danger');
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
            discardCreate('employee-type.php');
        });

        $(document).on('click','#edit-form',function() {
            displayDetails('get employee type details');

            enableForm();
        });

        $(document).on('click','#duplicate-employee-type',function() {
            const employee_type_id = $('#employee-type-id').text();
            const transaction = 'duplicate employee type';
    
            Swal.fire({
                title: 'Confirm Employee Type Duplication',
                text: 'Are you sure you want to duplicate this employee type?',
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
                        url: 'controller/employee-type-controller.php',
                        dataType: 'json',
                        data: {
                            employee_type_id : employee_type_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Duplicate Employee Type Success', 'The employee type has been duplicated successfully.', 'success');
                                window.location = 'employee-type.php?id=' + response.employeeTypeID;
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Duplicate Employee Type Error', 'The employee type does not exist.', 'danger');
                                    reloadDatatable('#employee-type-table');
                                }
                                else {
                                    showNotification('Duplicate Employee Type Error', response.message, 'danger');
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
    });
})(jQuery);

function employeeTypeTable(datatable_name, buttons = false, show_all = false){
    const type = 'employee type table';
    var settings;

    const column = [ 
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'EMPLOYEE_TYPE_NAME' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '1%','bSortable': false, 'aTargets': 0 },
        { 'width': '84%', 'aTargets': 1 },
        { 'width': '15%','bSortable': false, 'aTargets': 2 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_employee_type_generation.php',
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

function employeeTypeForm(){
    $('#employee-type-form').validate({
        rules: {
            employee_type_name: {
                required: true
            },
        },
        messages: {
            employee_type_name: {
                required: 'Please enter the employee type name'
            },
        },
        errorPlacement: function (error, element) {
            if (element.hasClass('select2')) {
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
            const employee_type_id = $('#employee-type-id').text();
            const transaction = 'save employee type';
        
            $.ajax({
                type: 'POST',
                url: 'controller/employee-type-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&employee_type_id=' + employee_type_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-data');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert Employee Type Success' : 'Update Employee Type Success';
                        const notificationDescription = response.insertRecord ? 'The employee type has been inserted successfully.' : 'The employee type has been updated successfully.';
                        
                        setNotification(notificationMessage, notificationDescription, 'success');
                        window.location = 'employee-type.php?id=' + response.employeeTypeID;
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
        case 'get employee type details':
            const employee_type_id = $('#employee-type-id').text();
            
            $.ajax({
                url: 'controller/employee-type-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    employee_type_id : employee_type_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetModalForm('employee-type-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#employee_type_id').val(employee_type_id);
                        $('#employee_type_name').val(response.employeeTypeName);

                        $('#employee_type_name_label').text(response.employeeTypeName);
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Employee Type Details Error', response.message, 'danger');
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