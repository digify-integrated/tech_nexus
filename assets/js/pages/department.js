(function($) {
    'use strict';

    $(function() {
        if($('#department-table').length){
            departmentTable('#department-table');
        }

        if($('#department-form').length){
            departmentForm();
        }

        if($('#department-id').length){
            displayDetails('get department details');
        }

        $(document).on('click','.delete-department',function() {
            const department_id = $(this).data('department-id');
            const transaction = 'delete department';
    
            Swal.fire({
                title: 'Confirm Department Deletion',
                text: 'Are you sure you want to delete this department?',
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
                        url: 'controller/department-controller.php',
                        dataType: 'json',
                        data: {
                            department_id : department_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete Department Success', 'The department has been deleted successfully.', 'success');
                                reloadDatatable('#department-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Delete Department Error', 'The department does not exist.', 'danger');
                                    reloadDatatable('#department-table');
                                }
                                else {
                                    showNotification('Delete Department Error', response.message, 'danger');
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

        $(document).on('click','#delete-department',function() {
            let department_id = [];
            const transaction = 'delete multiple department';

            $('.datatable-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    department_id.push(element.value);
                }
            });
    
            if(department_id.length > 0){
                Swal.fire({
                    title: 'Confirm Multiple Departmentes Deletion',
                    text: 'Are you sure you want to delete these departmentes?',
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
                            url: 'controller/department-controller.php',
                            dataType: 'json',
                            data: {
                                department_id: department_id,
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Delete Department Success', 'The selected departmentes have been deleted successfully.', 'success');
                                        reloadDatatable('#department-table');
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else {
                                        showNotification('Delete Department Error', response.message, 'danger');
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
                showNotification('Deletion Multiple Department Error', 'Please select the departmentes you wish to delete.', 'danger');
            }
        });

        $(document).on('click','#delete-department-details',function() {
            const department_id = $('#department-id').text();
            const transaction = 'delete department';
    
            Swal.fire({
                title: 'Confirm Department Deletion',
                text: 'Are you sure you want to delete this department?',
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
                        url: 'controller/department-controller.php',
                        dataType: 'json',
                        data: {
                            department_id : department_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Deleted Department Success', 'The department has been deleted successfully.', 'success');
                                window.location = 'department.php';
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
                                    showNotification('Delete Department Error', response.message, 'danger');
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
            discardCreate('department.php');
        });

        $(document).on('click','#edit-form',function() {
            displayDetails('get department details');

            enableForm();
        });

        $(document).on('click','#duplicate-department',function() {
            const department_id = $('#department-id').text();
            const transaction = 'duplicate department';
    
            Swal.fire({
                title: 'Confirm Department Duplication',
                text: 'Are you sure you want to duplicate this department?',
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
                        url: 'controller/department-controller.php',
                        dataType: 'json',
                        data: {
                            department_id : department_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Duplicate Department Success', 'The department has been duplicated successfully.', 'success');
                                window.location = 'department.php?id=' + response.departmentID;
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Duplicate Department Error', 'The department does not exist.', 'danger');
                                    reloadDatatable('#department-table');
                                }
                                else {
                                    showNotification('Duplicate Department Error', response.message, 'danger');
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

function departmentTable(datatable_name, buttons = false, show_all = false){
    const type = 'department table';
    var settings;

    const column = [ 
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'DEPARTMENT_NAME' },
        { 'data' : 'PARENT_DEPARTMENT' },
        { 'data' : 'MANAGER' },
        { 'data' : 'EMPLOYEES' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '1%','bSortable': false, 'aTargets': 0 },
        { 'width': '20%', 'aTargets': 1 },
        { 'width': '25%', 'aTargets': 2 },
        { 'width': '20%', 'aTargets': 3 },
        { 'width': '14%', 'aTargets': 4 },
        { 'width': '15%','bSortable': false, 'aTargets': 5 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_department_generation.php',
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

function departmentForm(){
    $('#department-form').validate({
        rules: {
            department_name: {
                required: true
            }
        },
        messages: {
            department_name: {
                required: 'Please enter the department name'
            }
        },
        errorPlacement: function (error, element) {
            if (element.hasClass('select2') || element.hasClass('modal-select2')) {
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
            const department_id = $('#department-id').text();
            const transaction = 'save department';
        
            $.ajax({
                type: 'POST',
                url: 'controller/department-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&department_id=' + department_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-data');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert Department Success' : 'Update Department Success';
                        const notificationDescription = response.insertRecord ? 'The department has been inserted successfully.' : 'The department has been updated successfully.';
                        
                        setNotification(notificationMessage, notificationDescription, 'success');
                        window.location = 'department.php?id=' + response.departmentID;
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
        case 'get department details':
            const department_id = $('#department-id').text();
            
            $.ajax({
                url: 'controller/department-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    department_id : department_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetModalForm('department-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#department_id').val(department_id);
                        $('#department_name').val(response.departmentName);

                        checkOptionExist('#parent_department', response.parentDepartment, '');
                        checkOptionExist('#manager', null, '');

                        $('#department_name_label').text(response.departmentName);
                        $('#parent_department_label').text(response.parentDepartmentName);
                        $('#manager_label').text(null);
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Department Details Error', response.message, 'danger');
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