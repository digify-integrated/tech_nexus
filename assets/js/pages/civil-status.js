(function($) {
    'use strict';

    $(function() {
        if($('#civil-status-table').length){
            civilStatusTable('#civil-status-table');
        }

        if($('#civil-status-form').length){
            civilStatusForm();
        }

        if($('#civil-status-id').length){
            displayDetails('get civil status details');
        }

        $(document).on('click','.delete-civil-status',function() {
            const civil_status_id = $(this).data('civil-status-id');
            const transaction = 'delete civil status';
    
            Swal.fire({
                title: 'Confirm Civil Status Deletion',
                text: 'Are you sure you want to delete this civil status?',
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
                        url: 'controller/civil-status-controller.php',
                        dataType: 'json',
                        data: {
                            civil_status_id : civil_status_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete Civil Status Success', 'The civil status has been deleted successfully.', 'success');
                                reloadDatatable('#civil-status-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Delete Civil Status Error', 'The civil status does not exist.', 'danger');
                                    reloadDatatable('#civil-status-table');
                                }
                                else {
                                    showNotification('Delete Civil Status Error', response.message, 'danger');
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

        $(document).on('click','#delete-civil-status',function() {
            let civil_status_id = [];
            const transaction = 'delete multiple civil status';

            $('.datatable-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    civil_status_id.push(element.value);
                }
            });
    
            if(civil_status_id.length > 0){
                Swal.fire({
                    title: 'Confirm Multiple Civil Status Deletion',
                    text: 'Are you sure you want to delete these civil status?',
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
                            url: 'controller/civil-status-controller.php',
                            dataType: 'json',
                            data: {
                                civil_status_id: civil_status_id,
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Delete Civil Status Success', 'The selected civil status have been deleted successfully.', 'success');
                                        reloadDatatable('#civil-status-table');
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else {
                                        showNotification('Delete Civil Status Error', response.message, 'danger');
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
                showNotification('Deletion Multiple Civil Status Error', 'Please select the civil status you wish to delete.', 'danger');
            }
        });

        $(document).on('click','#delete-civil-status-details',function() {
            const civil_status_id = $('#civil-status-id').text();
            const transaction = 'delete civil status';
    
            Swal.fire({
                title: 'Confirm Civil Status Deletion',
                text: 'Are you sure you want to delete this civil status?',
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
                        url: 'controller/civil-status-controller.php',
                        dataType: 'json',
                        data: {
                            civil_status_id : civil_status_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Deleted Civil Status Success', 'The civil status has been deleted successfully.', 'success');
                                window.location = 'civil-status.php';
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
                                    showNotification('Delete Civil Status Error', response.message, 'danger');
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
            discardCreate('civil-status.php');
        });

        $(document).on('click','#edit-form',function() {
            displayDetails('get civil status details');

            enableForm();
        });

        $(document).on('click','#duplicate-civil-status',function() {
            const civil_status_id = $('#civil-status-id').text();
            const transaction = 'duplicate civil status';
    
            Swal.fire({
                title: 'Confirm Civil Status Duplication',
                text: 'Are you sure you want to duplicate this civil status?',
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
                        url: 'controller/civil-status-controller.php',
                        dataType: 'json',
                        data: {
                            civil_status_id : civil_status_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Duplicate Civil Status Success', 'The civil status has been duplicated successfully.', 'success');
                                window.location = 'civil-status.php?id=' + response.civilStatusID;
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Duplicate Civil Status Error', 'The civil status does not exist.', 'danger');
                                    reloadDatatable('#civil-status-table');
                                }
                                else {
                                    showNotification('Duplicate Civil Status Error', response.message, 'danger');
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

function civilStatusTable(datatable_name, buttons = false, show_all = false){
    const type = 'civil status table';
    var settings;

    const column = [ 
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'CIVIL_STATUS_NAME' },
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
            'url' : 'view/_civil_status_generation.php',
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

function civilStatusForm(){
    $('#civil-status-form').validate({
        rules: {
            civil_status_name: {
                required: true
            },
        },
        messages: {
            civil_status_name: {
                required: 'Please enter the civil status name'
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
            const civil_status_id = $('#civil-status-id').text();
            const transaction = 'save civil status';
        
            $.ajax({
                type: 'POST',
                url: 'controller/civil-status-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&civil_status_id=' + civil_status_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-data');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert Civil Status Success' : 'Update Civil Status Success';
                        const notificationDescription = response.insertRecord ? 'The civil status has been inserted successfully.' : 'The civil status has been updated successfully.';
                        
                        setNotification(notificationMessage, notificationDescription, 'success');
                        window.location = 'civil-status.php?id=' + response.civilStatusID;
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
        case 'get civil status details':
            const civil_status_id = $('#civil-status-id').text();
            
            $.ajax({
                url: 'controller/civil-status-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    civil_status_id : civil_status_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetForm('civil-status-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#civil_status_id').val(civil_status_id);
                        $('#civil_status_name').val(response.civilStatusName);

                        $('#civil_status_name_label').text(response.civilStatusName);
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Civil Status Details Error', response.message, 'danger');
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