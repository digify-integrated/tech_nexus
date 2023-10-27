(function($) {
    'use strict';

    $(function() {
        if($('#job-level-table').length){
            jobLevelTable('#job-level-table');
        }

        if($('#job-level-form').length){
            jobLevelForm();
        }

        if($('#job-level-id').length){
            displayDetails('get job level details');
        }

        $(document).on('click','.delete-job-level',function() {
            const job_level_id = $(this).data('job-level-id');
            const transaction = 'delete job level';
    
            Swal.fire({
                title: 'Confirm Job Level Deletion',
                text: 'Are you sure you want to delete this job level?',
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
                        url: 'controller/job-level-controller.php',
                        dataType: 'json',
                        data: {
                            job_level_id : job_level_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete Job Level Success', 'The job level has been deleted successfully.', 'success');
                                reloadDatatable('#job-level-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Delete Job Level Error', 'The job level does not exist.', 'danger');
                                    reloadDatatable('#job-level-table');
                                }
                                else {
                                    showNotification('Delete Job Level Error', response.message, 'danger');
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

        $(document).on('click','#delete-job-level',function() {
            let job_level_id = [];
            const transaction = 'delete multiple job level';

            $('.datatable-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    job_level_id.push(element.value);
                }
            });
    
            if(job_level_id.length > 0){
                Swal.fire({
                    title: 'Confirm Multiple Job Levels Deletion',
                    text: 'Are you sure you want to delete these job levels?',
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
                            url: 'controller/job-level-controller.php',
                            dataType: 'json',
                            data: {
                                job_level_id: job_level_id,
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Delete Job Level Success', 'The selected job levels have been deleted successfully.', 'success');
                                        reloadDatatable('#job-level-table');
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else {
                                        showNotification('Delete Job Level Error', response.message, 'danger');
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
                showNotification('Deletion Multiple Job Level Error', 'Please select the job levels you wish to delete.', 'danger');
            }
        });

        $(document).on('click','#delete-job-level-details',function() {
            const job_level_id = $('#job-level-id').text();
            const transaction = 'delete job level';
    
            Swal.fire({
                title: 'Confirm Job Level Deletion',
                text: 'Are you sure you want to delete this job level?',
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
                        url: 'controller/job-level-controller.php',
                        dataType: 'json',
                        data: {
                            job_level_id : job_level_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Deleted Job Level Success', 'The job level has been deleted successfully.', 'success');
                                window.location = 'job-level.php';
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
                                    showNotification('Delete Job Level Error', response.message, 'danger');
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
            discardCreate('job-level.php');
        });

        $(document).on('click','#edit-form',function() {
            displayDetails('get job level details');

            enableForm();
        });

        $(document).on('click','#duplicate-job-level',function() {
            const job_level_id = $('#job-level-id').text();
            const transaction = 'duplicate job level';
    
            Swal.fire({
                title: 'Confirm Job Level Duplication',
                text: 'Are you sure you want to duplicate this job level?',
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
                        url: 'controller/job-level-controller.php',
                        dataType: 'json',
                        data: {
                            job_level_id : job_level_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Duplicate Job Level Success', 'The job level has been duplicated successfully.', 'success');
                                window.location = 'job-level.php?id=' + response.jobLevelID;
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Duplicate Job Level Error', 'The job level does not exist.', 'danger');
                                    reloadDatatable('#job-level-table');
                                }
                                else {
                                    showNotification('Duplicate Job Level Error', response.message, 'danger');
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

function jobLevelTable(datatable_name, buttons = false, show_all = false){
    const type = 'job level table';
    var settings;

    const column = [ 
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'CURRENT_LEVEL' },
        { 'data' : 'RANK' },
        { 'data' : 'FUNCTIONAL_LEVEL' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '1%','bSortable': false, 'aTargets': 0 },
        { 'width': '28%', 'aTargets': 1 },
        { 'width': '28%', 'aTargets': 2 },
        { 'width': '28%', 'aTargets': 3 },
        { 'width': '15%','bSortable': false, 'aTargets': 4 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_job_level_generation.php',
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

function jobLevelForm(){
    $('#job-level-form').validate({
        rules: {
            current_level: {
                required: true
            },
            rank: {
                required: true
            },
            functional_level: {
                required: true
            }
        },
        messages: {
            current_level: {
                required: 'Please enter the current level'
            },
            rank: {
                required: 'Please enter the rank'
            },
            functional_level: {
                required: 'Please enter the functional level'
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
            const job_level_id = $('#job-level-id').text();
            const transaction = 'save job level';
        
            $.ajax({
                type: 'POST',
                url: 'controller/job-level-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&job_level_id=' + job_level_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-data');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert Job Level Success' : 'Update Job Level Success';
                        const notificationDescription = response.insertRecord ? 'The job level has been inserted successfully.' : 'The job level has been updated successfully.';
                        
                        setNotification(notificationMessage, notificationDescription, 'success');
                        window.location = 'job-level.php?id=' + response.jobLevelID;
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
        case 'get job level details':
            const job_level_id = $('#job-level-id').text();
            
            $.ajax({
                url: 'controller/job-level-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    job_level_id : job_level_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetForm('job-level-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#job_level_id').val(job_level_id);
                        $('#current_level').val(response.currentLevel);
                        $('#rank').val(response.rank);
                        $('#functional_level').val(response.functionalLevel);


                        $('#current_level_label').text(response.currentLevel);
                        $('#rank_label').text(response.rank);
                        $('#functional_level_label').text(response.functionalLevel);
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Job Level Details Error', response.message, 'danger');
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