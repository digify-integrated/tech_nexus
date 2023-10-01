(function($) {
    'use strict';

    $(function() {
        if($('#educational-stage-table').length){
            educationalStageTable('#educational-stage-table');
        }

        if($('#educational-stage-form').length){
            educationalStageForm();
        }

        if($('#educational-stage-id').length){
            displayDetails('get educational stage details');
        }

        $(document).on('click','.delete-educational-stage',function() {
            const educational_stage_id = $(this).data('educational-stage-id');
            const transaction = 'delete educational stage';
    
            Swal.fire({
                title: 'Confirm Educational Stage Deletion',
                text: 'Are you sure you want to delete this educational stage?',
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
                        url: 'controller/educational-stage-controller.php',
                        dataType: 'json',
                        data: {
                            educational_stage_id : educational_stage_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete Educational Stage Success', 'The educational stage has been deleted successfully.', 'success');
                                reloadDatatable('#educational-stage-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Delete Educational Stage Error', 'The educational stage does not exist.', 'danger');
                                    reloadDatatable('#educational-stage-table');
                                }
                                else {
                                    showNotification('Delete Educational Stage Error', response.message, 'danger');
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

        $(document).on('click','#delete-educational-stage',function() {
            let educational_stage_id = [];
            const transaction = 'delete multiple educational stage';

            $('.datatable-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    educational_stage_id.push(element.value);
                }
            });
    
            if(educational_stage_id.length > 0){
                Swal.fire({
                    title: 'Confirm Multiple Educational Stages Deletion',
                    text: 'Are you sure you want to delete these educational stages?',
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
                            url: 'controller/educational-stage-controller.php',
                            dataType: 'json',
                            data: {
                                educational_stage_id: educational_stage_id,
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Delete Educational Stage Success', 'The selected educational stages have been deleted successfully.', 'success');
                                    reloadDatatable('#educational-stage-table');
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else {
                                        showNotification('Delete Educational Stage Error', response.message, 'danger');
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
                showNotification('Deletion Multiple Educational Stage Error', 'Please select the educational stages you wish to delete.', 'danger');
            }
        });

        $(document).on('click','#delete-educational-stage-details',function() {
            const educational_stage_id = $('#educational-stage-id').text();
            const transaction = 'delete educational stage';
    
            Swal.fire({
                title: 'Confirm Educational Stage Deletion',
                text: 'Are you sure you want to delete this educational stage?',
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
                        url: 'controller/educational-stage-controller.php',
                        dataType: 'json',
                        data: {
                            educational_stage_id : educational_stage_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Deleted Educational Stage Success', 'The educational stage has been deleted successfully.', 'success');
                                window.location = 'educational-stage.php';
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
                                    showNotification('Delete Educational Stage Error', response.message, 'danger');
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
            discardCreate('educational-stage.php');
        });

        $(document).on('click','#edit-form',function() {
            displayDetails('get educational stage details');

            enableForm();
        });

        $(document).on('click','#duplicate-educational-stage',function() {
            const educational_stage_id = $('#educational-stage-id').text();
            const transaction = 'duplicate educational stage';
    
            Swal.fire({
                title: 'Confirm Educational Stage Duplication',
                text: 'Are you sure you want to duplicate this educational stage?',
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
                        url: 'controller/educational-stage-controller.php',
                        dataType: 'json',
                        data: {
                            educational_stage_id : educational_stage_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Duplicate Educational Stage Success', 'The educational stage has been duplicated successfully.', 'success');
                                window.location = 'educational-stage.php?id=' + response.educationalStageID;
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Duplicate Educational Stage Error', 'The educational stage does not exist.', 'danger');
                                    reloadDatatable('#educational-stage-table');
                                }
                                else {
                                    showNotification('Duplicate Educational Stage Error', response.message, 'danger');
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

function educationalStageTable(datatable_name, buttons = false, show_all = false){
    const type = 'educational stage table';
    var settings;

    const column = [ 
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'EDUCATIONAL_STAGE_NAME' },
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
            'url' : 'view/_educational_stage_generation.php',
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

function educationalStageForm(){
    $('#educational-stage-form').validate({
        rules: {
            educational_stage_name: {
                required: true
            },
        },
        messages: {
            educational_stage_name: {
                required: 'Please enter the educational stage name'
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
            const educational_stage_id = $('#educational-stage-id').text();
            const transaction = 'save educational stage';
        
            $.ajax({
                type: 'POST',
                url: 'controller/educational-stage-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&educational_stage_id=' + educational_stage_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-data');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert Educational Stage Success' : 'Update Educational Stage Success';
                        const notificationDescription = response.insertRecord ? 'The educational stage has been inserted successfully.' : 'The educational stage has been updated successfully.';
                        
                        setNotification(notificationMessage, notificationDescription, 'success');
                        window.location = 'educational-stage.php?id=' + response.educationalStageID;
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
        case 'get educational stage details':
            const educational_stage_id = $('#educational-stage-id').text();
            
            $.ajax({
                url: 'controller/educational-stage-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    educational_stage_id : educational_stage_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetForm('educational-stage-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#educational_stage_id').val(educational_stage_id);
                        $('#educational_stage_name').val(response.educationalStageName);

                        $('#educational_stage_name_label').text(response.educationalStageName);
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Educational Stage Details Error', response.message, 'danger');
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