(function($) {
    'use strict';

    $(function() {
        if($('#employee-table').length){
            employeeTable('#employee-table');
        }

        if($('#add-employee-form').length){
            addEmployeeForm();
        }

        if($('#personal-information-form').length){
            personalInformationForm();
        }

        if($('#employment-information-form').length){
            employmentInformationForm();
        }

        if($('#employee-id').length){
            displayDetails('get personal information details');
            displayDetails('get employment information details');

            $('#employee_image').change(function() {
                var selectedFile = $(this)[0].files[0];

                if (selectedFile) {
                    const transaction = 'change employee image';
                    const employee_id = $('#employee-id').text();

                    var formData = new FormData();
                    formData.append('employee_id', employee_id);
                    formData.append('transaction', transaction);
                    formData.append('employee_image', selectedFile);
            
                    $.ajax({
                        type: 'POST',
                        url: 'controller/employee-controller.php',
                        data: formData,
                        processData: false,
                        contentType: false,
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                showNotification('Employee Image Change Success', 'The employee image has been successfully updated.', 'success');
                                displayDetails('get personal information details');
                            }
                            else{
                                if(response.isInactive){
                                    window.location = 'logout.php?logout';
                                }
                                else{
                                    showNotification('Employee Image Change Error', response.message, 'danger');
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
                }
            });
        }

        $(document).on('click','#filter-datatable',function() {
            employeeTable('#employee-table');
        });
    });
})(jQuery);

function employeeTable(datatable_name, buttons = false, show_all = false){
    const type = 'employee table';
    var filter_employment_status = $('#filter_employment_status').val();
    var filter_company = $('#filter_company').val();
    var filter_department = $('#filter_department').val();
    var filter_job_position = $('#filter_job_position').val();
    var filter_job_level = $('#filter_job_level').val();
    var filter_branch = $('#filter_branch').val();
    var filter_employee_type = $('#filter_employee_type').val();

    var settings;

    const column = [ 
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'EMPLOYEE' },
        { 'data' : 'DEPARTMENT' },
        { 'data' : 'BRANCH' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '1%','bSortable': false, 'aTargets': 0 },
        { 'width': '44%', 'aTargets': 1 },
        { 'width': '20%', 'aTargets': 2 },
        { 'width': '20%', 'aTargets': 3 },
        { 'width': '15%','bSortable': false, 'aTargets': 4 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_employee_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {
                'type' : type,
                'filter_employment_status' : filter_employment_status,
                'filter_company' : filter_company,
                'filter_department' : filter_department,
                'filter_job_position' : filter_job_position,
                'filter_job_level' : filter_job_level,
                'filter_branch' : filter_branch,
                'filter_employee_type' : filter_employee_type,
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

function addEmployeeForm(){
    $('#add-employee-form').validate({
        rules: {
            first_name: {
                required: true
            },
            last_name: {
                required: true
            },
            company_id: {
                required: true
            },
            branch_id: {
                required: true
            },
            department_id: {
                required: true
            },
            job_position_id: {
                required: true
            },
        },
        messages: {
            first_name: {
                required: 'Please enter the first name'
            },
            last_name: {
                required: 'Please enter the last name'
            },
            company_id: {
                required: 'Please choose the company'
            },
            branch_id: {
                required: 'Please choose the branch'
            },
            department_id: {
                required: 'Please choose the department'
            },
            job_position_id: {
                required: 'Please choose the job position'
            },
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
            const employee_id = $('#employee-id').text();
            const transaction = 'add employee';
        
            $.ajax({
                type: 'POST',
                url: 'controller/employee-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&employee_id=' + employee_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-data');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = 'Insert Employee Success';
                        const notificationDescription = 'The employee has been inserted successfully.';
                        
                        setNotification(notificationMessage, notificationDescription, 'success');
                        window.location = 'employee.php?id=' + response.employeeID;
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

function personalInformationForm(){
    $('#personal-information-form').validate({
        rules: {
            first_name: {
                required: true
            },
            last_name: {
                required: true
            },
            birthday: {
                required: true
            },
            gender: {
                required: true
            },
            civil_status: {
                required: true
            },
        },
        messages: {
            first_name: {
                required: 'Please enter the first name'
            },
            last_name: {
                required: 'Please enter the last name'
            },
            gender: {
                required: 'Please choose the gender'
            },
            civil_status: {
                required: 'Please choose the civil status'
            },
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
            const employee_id = $('#employee-id').text();
            const transaction = 'save personal information';
        
            $.ajax({
                type: 'POST',
                url: 'controller/employee-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&employee_id=' + employee_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-personal-information-data');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = 'Update Personal Information Success';
                        const notificationDescription = 'The personal information has been updated successfully.';
                        
                        showNotification(notificationMessage, notificationDescription, 'success');
                        displayDetails('get personal information details');
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
                    enableFormSubmitButton('submit-personal-information-data', 'Save');
                }
            });
        
            return false;
        }
    });
}

function employmentInformationForm(){
    $('#employment-information-form').validate({
        rules: {
            badge_id: {
                required: true
            },
            company_id: {
                required: true
            },
            department_id: {
                required: true
            },
            job_position_id: {
                required: true
            },
            gender: {
                required: true
            },
            employee_type_id: {
                required: true
            },
            job_level_id: {
                required: true
            },
            branch_id: {
                required: true
            },
            onboard_date: {
                required: true
            },
        },
        messages: {
            badge_id: {
                required: 'Please enter the badge ID'
            },
            company_id: {
                required: 'Please choose the company'
            },
            department_id: {
                required: 'Please choose the department'
            },
            job_position_id: {
                required: 'Please choose the job position'
            },
            employee_type_id: {
                required: 'Please choose the employee type'
            },
            job_level_id: {
                required: 'Please choose the job level'
            },
            branch_id: {
                required: 'Please choose the job level'
            },
            onboard_date: {
                required: 'Please choose the on-board date'
            },
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
            const employee_id = $('#employee-id').text();
            const transaction = 'save employment information';
        
            $.ajax({
                type: 'POST',
                url: 'controller/employee-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&employee_id=' + employee_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-employment-information-data');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = 'Update Employment Information Success';
                        const notificationDescription = 'The employment information has been updated successfully.';
                        
                        showNotification(notificationMessage, notificationDescription, 'success');
                        displayDetails('get employment information details');
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
                    enableFormSubmitButton('submit-employment-information-data', 'Save');
                }
            });
        
            return false;
        }
    });
}

function displayDetails(transaction){
    switch (transaction) {
        case 'get personal information details':
            var employee_id = $('#employee-id').text();
            
            $.ajax({
                url: 'controller/employee-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    employee_id : employee_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetForm('personal-information-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#first_name').val(response.firstName);
                        $('#last_name').val(response.lastName);
                        $('#middle_name').val(response.middleName);
                        $('#suffix').val(response.suffix);
                        $('#nickname').val(response.nickname);
                        $('#birthday').val(response.birthday);
                        $('#birth_place').val(response.birth_place);
                        $('#height').val(response.height);
                        $('#weight').val(response.weight);
                        $('#bio').val(response.bio);

                        $('#emp_image').attr('src', response.employeeImage);

                        checkOptionExist('#gender', response.genderID, '');
                        checkOptionExist('#civil_status', response.civilStatusID, '');
                        checkOptionExist('#religion', response.religionID, '');
                        checkOptionExist('#blood_type', response.bloodTypeID, '');
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Personal Information Details Error', response.message, 'danger');
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
        case 'get employment information details':
            var employee_id = $('#employee-id').text();
            
            $.ajax({
                url: 'controller/employee-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    employee_id : employee_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetForm('employment-information-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#badge_id').val(response.badgeID);
                        $('#onboard_date').val(response.onboardDate);
                        $('#permanency_date').val(response.permanencyDate);

                        checkOptionExist('#company_id', response.companyID, '');
                        checkOptionExist('#department_id', response.departmentID, '');
                        checkOptionExist('#job_position_id', response.jobPositionID, '');
                        checkOptionExist('#employee_type_id', response.employeeTypeID, '');
                        checkOptionExist('#job_level_id', response.jobLevelID, '');
                        checkOptionExist('#branch_id', response.branchID, '');
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Employment Information Details Error', response.message, 'danger');
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