(function($) {
    'use strict';

    $(function() {
        if($('#work-center-table').length){
            workCenterTable('#work-center-table');
        }

        if($('#work-center-form').length){
            workCenterForm();
        }

        if($('#work-center-id').length){
            displayDetails('get work center details');
        }

        $(document).on('click','.delete-work-center',function() {
            const work_center_id = $(this).data('work-center-id');
            const transaction = 'delete work center';
    
            Swal.fire({
                title: 'Confirm Work Center Deletion',
                text: 'Are you sure you want to delete this work center?',
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
                        url: 'controller/work-center-controller.php',
                        dataType: 'json',
                        data: {
                            work_center_id : work_center_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete Work Center Success', 'The work center has been deleted successfully.', 'success');
                                reloadDatatable('#work-center-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Delete Work Center Error', 'The work center does not exist.', 'danger');
                                    reloadDatatable('#work-center-table');
                                }
                                else {
                                    showNotification('Delete Work Center Error', response.message, 'danger');
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

        $(document).on('click','#delete-work-center',function() {
            let work_center_id = [];
            const transaction = 'delete multiple work center';

            $('.datatable-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    work_center_id.push(element.value);
                }
            });
    
            if(work_center_id.length > 0){
                Swal.fire({
                    title: 'Confirm Multiple Work Centers Deletion',
                    text: 'Are you sure you want to delete these work centers?',
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
                            url: 'controller/work-center-controller.php',
                            dataType: 'json',
                            data: {
                                work_center_id: work_center_id,
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Delete Work Center Success', 'The selected work centers have been deleted successfully.', 'success');
                                    reloadDatatable('#work-center-table');
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else {
                                        showNotification('Delete Work Center Error', response.message, 'danger');
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
                showNotification('Deletion Multiple Work Center Error', 'Please select the work centers you wish to delete.', 'danger');
            }
        });

        $(document).on('click','#delete-work-center-details',function() {
            const work_center_id = $('#work-center-id').text();
            const transaction = 'delete work center';
    
            Swal.fire({
                title: 'Confirm Work Center Deletion',
                text: 'Are you sure you want to delete this work center?',
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
                        url: 'controller/work-center-controller.php',
                        dataType: 'json',
                        data: {
                            work_center_id : work_center_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Deleted Work Center Success', 'The work center has been deleted successfully.', 'success');
                                window.location = 'work-center.php';
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
                                    showNotification('Delete Work Center Error', response.message, 'danger');
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
            discardCreate('work-center.php');
        });

        $(document).on('click','#edit-form',function() {
            displayDetails('get work center details');

            enableForm();
        });

        $(document).on('click','#duplicate-work-center',function() {
            const work_center_id = $('#work-center-id').text();
            const transaction = 'duplicate work center';
    
            Swal.fire({
                title: 'Confirm Work Center Duplication',
                text: 'Are you sure you want to duplicate this work center?',
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
                        url: 'controller/work-center-controller.php',
                        dataType: 'json',
                        data: {
                            work_center_id : work_center_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Duplicate Work Center Success', 'The work center has been duplicated successfully.', 'success');
                                window.location = 'work-center.php?id=' + response.workCenterID;
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Duplicate Work Center Error', 'The work center does not exist.', 'danger');
                                    reloadDatatable('#work-center-table');
                                }
                                else {
                                    showNotification('Duplicate Work Center Error', response.message, 'danger');
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

function workCenterTable(datatable_name, buttons = false, show_all = false){
    const type = 'work center table';
    var settings;

    const column = [ 
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'WORK_CENTER_NAME' },
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
            'url' : 'view/_work_center_generation.php',
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

function workCenterForm(){
    $('#work-center-form').validate({
        rules: {
            work_center_name: {
                required: true
            },
        },
        messages: {
            work_center_name: {
                required: 'Please enter the work center name'
            },
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
            const work_center_id = $('#work-center-id').text();
            const transaction = 'save work center';
        
            $.ajax({
                type: 'POST',
                url: 'controller/work-center-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&work_center_id=' + work_center_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-data');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert Work Center Success' : 'Update Work Center Success';
                        const notificationDescription = response.insertRecord ? 'The work center has been inserted successfully.' : 'The work center has been updated successfully.';
                        
                        setNotification(notificationMessage, notificationDescription, 'success');
                        window.location = 'work-center.php?id=' + response.workCenterID;
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
        case 'get work center details':
            const work_center_id = $('#work-center-id').text();
            
            $.ajax({
                url: 'controller/work-center-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    work_center_id : work_center_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetForm('work-center-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#work_center_id').val(work_center_id);
                        $('#work_center_name').val(response.workCenterName);

                        $('#work_center_name_label').text(response.workCenterName);
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Work Center Details Error', response.message, 'danger');
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