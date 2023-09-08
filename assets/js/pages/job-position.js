(function($) {
    'use strict';

    $(function() {
        if($('#job-position-table').length){
            jobPositionTable('#job-position-table');
        }

        if($('#job-position-form').length){
            jobPositionForm();
        }

        if($('#job-position-id').length){
            displayDetails('get job position details');

            if($('#start-job-position-recruitment-form').length){
                startJobPositionRecruitmentForm();
            }

            $(document).on('click','#start-job-position-recruitment',function() {    
                $('#start-job-position-recruitment-modal').modal('show');
            });

            $(document).on('click','#stop-job-position-recruitment',function() {
                const job_position_id = $('#job-position-id').text();
                const transaction = 'stop job position recruitment';
        
                Swal.fire({
                    title: 'Confirm Job Position Recruitment Stop',
                    text: 'Are you sure you want to stop this job position recruitment?',
                    icon: 'warning',
                    showCancelButton: !0,
                    confirmButtonText: 'Stop',
                    cancelButtonText: 'Cancel',
                    confirmButtonClass: 'btn btn-warning mt-2',
                    cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                    buttonsStyling: !1
                }).then(function(result) {
                    if (result.value) {
                        $.ajax({
                            type: 'POST',
                            url: 'controller/job-position-controller.php',
                            dataType: 'json',
                            data: {
                                job_position_id : job_position_id, 
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    setNotification('Stop Job Position Recruitment Success', 'The job position recruitment has been stopped successfully.', 'success');
                                    location.reload();
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
                                        showNotification('Stop Job Position Recruitment Error', response.message, 'danger');
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
        }

        $(document).on('click','.delete-job-position',function() {
            const job_position_id = $(this).data('job-position-id');
            const transaction = 'delete job position';
    
            Swal.fire({
                title: 'Confirm Job Position Deletion',
                text: 'Are you sure you want to delete this job position?',
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
                        url: 'controller/job-position-controller.php',
                        dataType: 'json',
                        data: {
                            job_position_id : job_position_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete Job Position Success', 'The job position has been deleted successfully.', 'success');
                                reloadDatatable('#job-position-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Delete Job Position Error', 'The job position does not exist.', 'danger');
                                    reloadDatatable('#job-position-table');
                                }
                                else {
                                    showNotification('Delete Job Position Error', response.message, 'danger');
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

        $(document).on('click','#delete-job-position',function() {
            let job_position_id = [];
            const transaction = 'delete multiple job position';

            $('.datatable-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    job_position_id.push(element.value);
                }
            });
    
            if(job_position_id.length > 0){
                Swal.fire({
                    title: 'Confirm Multiple Job Positions Deletion',
                    text: 'Are you sure you want to delete these job positions?',
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
                            url: 'controller/job-position-controller.php',
                            dataType: 'json',
                            data: {
                                job_position_id: job_position_id,
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Delete Job Position Success', 'The selected job positions have been deleted successfully.', 'success');
                                        reloadDatatable('#job-position-table');
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else {
                                        showNotification('Delete Job Position Error', response.message, 'danger');
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
                showNotification('Deletion Multiple Job Position Error', 'Please select the job positions you wish to delete.', 'danger');
            }
        });

        $(document).on('click','#delete-job-position-details',function() {
            const job_position_id = $('#job-position-id').text();
            const transaction = 'delete job position';
    
            Swal.fire({
                title: 'Confirm Job Position Deletion',
                text: 'Are you sure you want to delete this job position?',
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
                        url: 'controller/job-position-controller.php',
                        dataType: 'json',
                        data: {
                            job_position_id : job_position_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Deleted Job Position Success', 'The job position has been deleted successfully.', 'success');
                                window.location = 'job-position.php';
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
                                    showNotification('Delete Job Position Error', response.message, 'danger');
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
            discardCreate('job-position.php');
        });

        $(document).on('click','#edit-form',function() {
            displayDetails('get job position details');

            enableForm();
        });

        $(document).on('click','#duplicate-job-position',function() {
            const job_position_id = $('#job-position-id').text();
            const transaction = 'duplicate job position';
    
            Swal.fire({
                title: 'Confirm Job Position Duplication',
                text: 'Are you sure you want to duplicate this job position?',
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
                        url: 'controller/job-position-controller.php',
                        dataType: 'json',
                        data: {
                            job_position_id : job_position_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Duplicate Job Position Success', 'The job position has been duplicated successfully.', 'success');
                                window.location = 'job-position.php?id=' + response.jobPositionID;
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Duplicate Job Position Error', 'The job position does not exist.', 'danger');
                                    reloadDatatable('#job-position-table');
                                }
                                else {
                                    showNotification('Duplicate Job Position Error', response.message, 'danger');
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

        $(document).on('click','#filter-datatable',function() {
            jobPositionTable('#job-position-table');
        });
    });
})(jQuery);

function jobPositionTable(datatable_name, buttons = false, show_all = false){
    const type = 'job position table';
    var filter_recruitment_status = $('#filter_recruitment_status').val();
    var filter_department = $('#filter_department').val();
    var settings;

    const column = [ 
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'JOB_POSITION_NAME' },
        { 'data' : 'DEPARTMENT_NAME' },
        { 'data' : 'RECRUITMENT_STATUS' },
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
            'url' : 'view/_job_position_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {'type' : type, 'filter_recruitment_status' : filter_recruitment_status, 'filter_department' : filter_department},
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

function jobPositionForm(){
    $('#job-position-form').validate({
        rules: {
            job_position_name: {
                required: true
            },
            job_position_description: {
                required: true
            }
        },
        messages: {
            job_position_name: {
                required: 'Please enter the job position name'
            },
            job_position_description: {
                required: 'Please enter the job position description'
            }
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
            const job_position_id = $('#job-position-id').text();
            const transaction = 'save job position';
        
            $.ajax({
                type: 'POST',
                url: 'controller/job-position-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&job_position_id=' + job_position_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-data');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert Job Position Success' : 'Update Job Position Success';
                        const notificationDescription = response.insertRecord ? 'The job position has been inserted successfully.' : 'The job position has been updated successfully.';
                        
                        setNotification(notificationMessage, notificationDescription, 'success');
                        window.location = 'job-position.php?id=' + response.jobPositionID;
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

function startJobPositionRecruitmentForm(){
    $('#start-job-position-recruitment-form').validate({
        rules: {
            expected_new_employees: {
                required: true
            }
        },
        messages: {
            expected_new_employees: {
                required: 'Please enter the expected new employees'
            }
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
            const job_position_id = $('#job-position-id').text();
            const transaction = 'start job position recruitment';
          
            $.ajax({
                type: 'POST',
                url: 'controller/job-position-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&job_position_id=' + job_position_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-start-job-position-recruitment-form');
                },
                success: function (response) {
                    if (response.success) {
                        setNotification('Start Job Position Recruitment Success', 'The job position recruitment has been started successfully.', 'success');
                        location.reload();
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
                            showNotification('Start Job Position Recruitment Error', response.message, 'danger');
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
                    enableFormSubmitButton('submit-start-job-position-recruitment-form', 'Submit');
                }
            });
        
            return false;
        }
    });
}

function displayDetails(transaction){
    switch (transaction) {
        case 'get job position details':
            const job_position_id = $('#job-position-id').text();
            
            $.ajax({
                url: 'controller/job-position-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    job_position_id : job_position_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetModalForm('job-position-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#job_position_id').val(job_position_id);
                        $('#job_position_name').val(response.jobPositionName);
                        $('#job_position_description').val(response.jobPositionDescription);
                        $('#expected_new_employees').val(response.expectedNewEmployees);

                        checkOptionExist('#department_id', response.departmentID, '');

                        $('#job_position_name_label').text(response.jobPositionName);
                        $('#job_position_description_label').text(response.jobPositionDescription);
                        $('#department_id_label').text(response.departmentName);
                        $('#expected_new_employees_label').text(response.expectedNewEmployees);
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Job Position Details Error', response.message, 'danger');
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