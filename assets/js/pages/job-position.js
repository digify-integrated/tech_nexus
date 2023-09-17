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

            if($('#job-position-responsibility-table').length){
                jobPositionResponsibilityTable('#job-position-responsibility-table');
            }

            if($('#job-position-requirement-table').length){
                jobPositionRequirementTable('#job-position-requirement-table');
            }

            if($('#job-position-qualification-table').length){
                jobPositionQualificationTable('#job-position-qualification-table');
            }

            if($('#start-job-position-recruitment-form').length){
                startJobPositionRecruitmentForm();
            }

            if($('#job-position-responsibility-form').length){
                jobPositionResponsibilityForm();
            }

            if($('#job-position-requirement-form').length){
                jobPositionRequirementForm();
            }

            if($('#job-position-qualification-form').length){
                jobPositionQualificationForm();
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

            $(document).on('click','#add-job-position-responsibility',function() {
                resetModalForm('job-position-responsibility-form');

                $('#job-position-responsibility-modal').modal('show');
            });

            $(document).on('click','.update-job-position-responsibility',function() {
                const job_position_responsibility_id = $(this).data('job-position-responsibility-id');
        
                sessionStorage.setItem('job_position_responsibility_id', job_position_responsibility_id);
                
                displayDetails('get job position responsibility details');
        
                $('#job-position-responsibility-modal').modal('show');
            });

            $(document).on('click','.delete-job-position-responsibility',function() {
                const job_position_responsibility_id = $(this).data('job-position-responsibility-id');
                const transaction = 'delete job position responsibility';
        
                Swal.fire({
                    title: 'Confirm Responsibility Deletion',
                    text: 'Are you sure you want to delete this responsibility?',
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
                                job_position_responsibility_id : job_position_responsibility_id, 
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Delete Responsibility Success', 'The responsibility has been deleted successfully.', 'success');
                                    reloadDatatable('#job-position-responsibility-table');
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else if (response.notExist) {
                                        showNotification('Delete Responsibility Error', 'The job position does not exist.', 'danger');
                                        reloadDatatable('#job-position-responsibility-table');
                                    }
                                    else {
                                        showNotification('Delete Responsibility Error', response.message, 'danger');
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

            $(document).on('click','#add-job-position-requirement',function() {
                resetModalForm('job-position-requirement-form');

                $('#job-position-requirement-modal').modal('show');
            });

            $(document).on('click','.update-job-position-requirement',function() {
                const job_position_requirement_id = $(this).data('job-position-requirement-id');
        
                sessionStorage.setItem('job_position_requirement_id', job_position_requirement_id);
                
                displayDetails('get job position requirement details');
        
                $('#job-position-requirement-modal').modal('show');
            });

            $(document).on('click','.delete-job-position-requirement',function() {
                const job_position_requirement_id = $(this).data('job-position-requirement-id');
                const transaction = 'delete job position requirement';
        
                Swal.fire({
                    title: 'Confirm Requirement Deletion',
                    text: 'Are you sure you want to delete this requirement?',
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
                                job_position_requirement_id : job_position_requirement_id, 
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Delete Requirement Success', 'The requirement has been deleted successfully.', 'success');
                                    reloadDatatable('#job-position-requirement-table');
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else if (response.notExist) {
                                        showNotification('Delete Requirement Error', 'The job position does not exist.', 'danger');
                                        reloadDatatable('#job-position-requirement-table');
                                    }
                                    else {
                                        showNotification('Delete Requirement Error', response.message, 'danger');
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

            $(document).on('click','#add-job-position-qualification',function() {
                resetModalForm('job-position-qualification-form');

                $('#job-position-qualification-modal').modal('show');
            });

            $(document).on('click','.update-job-position-qualification',function() {
                const job_position_qualification_id = $(this).data('job-position-qualification-id');
        
                sessionStorage.setItem('job_position_qualification_id', job_position_qualification_id);
                
                displayDetails('get job position qualification details');
        
                $('#job-position-qualification-modal').modal('show');
            });

            $(document).on('click','.delete-job-position-qualification',function() {
                const job_position_qualification_id = $(this).data('job-position-qualification-id');
                const transaction = 'delete job position qualification';
        
                Swal.fire({
                    title: 'Confirm Qualification Deletion',
                    text: 'Are you sure you want to delete this qualification?',
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
                                job_position_qualification_id : job_position_qualification_id, 
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Delete Qualification Success', 'The qualification has been deleted successfully.', 'success');
                                    reloadDatatable('#job-position-qualification-table');
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else if (response.notExist) {
                                        showNotification('Delete Qualification Error', 'The job position does not exist.', 'danger');
                                        reloadDatatable('#job-position-qualification-table');
                                    }
                                    else {
                                        showNotification('Delete Qualification Error', response.message, 'danger');
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

function jobPositionResponsibilityTable(datatable_name, buttons = false, show_all = false){
    const type = 'job position responsibility table';
    const job_position_id = $('#job-position-id').text();
    var settings;

    const column = [ 
        { 'data' : 'RESPONSIBILITY' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '85%','bSortable': false, 'aTargets': 0 },
        { 'width': '15%','bSortable': false, 'aTargets': 1 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_job_position_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {'type' : type, 'job_position_id' : job_position_id},
            'dataSrc' : '',
            'error': function(xhr, status, error) {
                var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                if (xhr.responseText) {
                    fullErrorMessage += `, Response: ${xhr.responseText}`;
                }
                showErrorDialog(fullErrorMessage);
            }
        },
        'order': [[ 0, 'asc' ]],
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

function jobPositionRequirementTable(datatable_name, buttons = false, show_all = false){
    const type = 'job position requirement table';
    const job_position_id = $('#job-position-id').text();
    var settings;

    const column = [ 
        { 'data' : 'REQUIREMENT' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '85%','bSortable': false, 'aTargets': 0 },
        { 'width': '15%','bSortable': false, 'aTargets': 1 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_job_position_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {'type' : type, 'job_position_id' : job_position_id},
            'dataSrc' : '',
            'error': function(xhr, status, error) {
                var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                if (xhr.responseText) {
                    fullErrorMessage += `, Response: ${xhr.responseText}`;
                }
                showErrorDialog(fullErrorMessage);
            }
        },
        'order': [[ 0, 'asc' ]],
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

function jobPositionQualificationTable(datatable_name, buttons = false, show_all = false){
    const type = 'job position qualification table';
    const job_position_id = $('#job-position-id').text();
    var settings;

    const column = [ 
        { 'data' : 'QUALIFICATION' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '85%','bSortable': false, 'aTargets': 0 },
        { 'width': '15%','bSortable': false, 'aTargets': 1 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_job_position_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {'type' : type, 'job_position_id' : job_position_id},
            'dataSrc' : '',
            'error': function(xhr, status, error) {
                var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                if (xhr.responseText) {
                    fullErrorMessage += `, Response: ${xhr.responseText}`;
                }
                showErrorDialog(fullErrorMessage);
            }
        },
        'order': [[ 0, 'asc' ]],
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

function jobPositionResponsibilityForm(){
    $('#job-position-responsibility-form').validate({
        rules: {
            responsibility: {
                required: true
            }
        },
        messages: {
            responsibility: {
                required: 'Please enter the responsibility'
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
            const job_position_id = $('#job-position-id').text();
            const transaction = 'save job position responsibility';
        
            $.ajax({
                type: 'POST',
                url: 'controller/job-position-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&job_position_id=' + job_position_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-job-position-responsibility-form');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert Responsibility Success' : 'Update Responsibility Success';
                        const notificationDescription = response.insertRecord ? 'The responsibility has been inserted successfully.' : 'The responsibility has been updated successfully.';
                        
                        showNotification(notificationMessage, notificationDescription, 'success');
                    }
                    else {
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
                   enableFormSubmitButton('submit-job-position-responsibility-form', 'Submit');
                    $('#job-position-responsibility-modal').modal('hide');
                    reloadDatatable('#job-position-responsibility-table');
                    resetModalForm('job-position-responsibility-form');
                }
            });
        
            return false;
        }
    });
}

function jobPositionRequirementForm(){
    $('#job-position-requirement-form').validate({
        rules: {
            requirement: {
                required: true
            }
        },
        messages: {
            requirement: {
                required: 'Please enter the requirement'
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
            const job_position_id = $('#job-position-id').text();
            const transaction = 'save job position requirement';
        
            $.ajax({
                type: 'POST',
                url: 'controller/job-position-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&job_position_id=' + job_position_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-job-position-requirement-form');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert Requirement Success' : 'Update Requirement Success';
                        const notificationDescription = response.insertRecord ? 'The requirement has been inserted successfully.' : 'The requirement has been updated successfully.';
                        
                        showNotification(notificationMessage, notificationDescription, 'success');
                    }
                    else {
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
                   enableFormSubmitButton('submit-job-position-requirement-form', 'Submit');
                    $('#job-position-requirement-modal').modal('hide');
                    reloadDatatable('#job-position-requirement-table');
                    resetModalForm('job-position-requirement-form');
                }
            });
        
            return false;
        }
    });
}

function jobPositionQualificationForm(){
    $('#job-position-qualification-form').validate({
        rules: {
            qualification: {
                required: true
            }
        },
        messages: {
            qualification: {
                required: 'Please enter the qualification'
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
            const job_position_id = $('#job-position-id').text();
            const transaction = 'save job position qualification';
        
            $.ajax({
                type: 'POST',
                url: 'controller/job-position-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&job_position_id=' + job_position_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-job-position-qualification-form');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert Qualification Success' : 'Update Qualification Success';
                        const notificationDescription = response.insertRecord ? 'The qualification has been inserted successfully.' : 'The qualification has been updated successfully.';
                        
                        showNotification(notificationMessage, notificationDescription, 'success');
                    }
                    else {
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
                   enableFormSubmitButton('submit-job-position-qualification-form', 'Submit');
                    $('#job-position-qualification-modal').modal('hide');
                    reloadDatatable('#job-position-qualification-table');
                    resetModalForm('job-position-qualification-form');
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
        case 'get job position responsibility details':
            var job_position_responsibility_id = sessionStorage.getItem('job_position_responsibility_id');
                
            $.ajax({
                url: 'controller/job-position-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    job_position_responsibility_id : job_position_responsibility_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetModalForm('job-position-responsibility-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#job_position_responsibility_id').val(job_position_responsibility_id);
                        $('#responsibility').val(response.responsibility);
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                           showNotification('Get Responsibility Details Error', response.message, 'danger');
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
        case 'get job position requirement details':
            var job_position_requirement_id = sessionStorage.getItem('job_position_requirement_id');
                
            $.ajax({
                url: 'controller/job-position-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    job_position_requirement_id : job_position_requirement_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetModalForm('job-position-requirement-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#job_position_requirement_id').val(job_position_requirement_id);
                        $('#requirement').val(response.requirement);
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                           showNotification('Get Requirement Details Error', response.message, 'danger');
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
        case 'get job position qualification details':
            var job_position_qualification_id = sessionStorage.getItem('job_position_qualification_id');
                
            $.ajax({
                url: 'controller/job-position-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    job_position_qualification_id : job_position_qualification_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetModalForm('job-position-qualification-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#job_position_qualification_id').val(job_position_qualification_id);
                        $('#qualification').val(response.qualification);
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                           showNotification('Get Attachment Details Error', response.message, 'danger');
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