(function($) {
    'use strict';

    $(function() {
        if($('#lead-status-table').length){
            leadStatusTable('#lead-status-table');
        }

        if($('#lead-status-form').length){
            leadStatusForm();
        }

        if($('#lead-status-id').length){
            displayDetails('get lead status details');
        }

        $(document).on('click','.delete-lead-status',function() {
            const lead_status_id = $(this).data('lead-status-id');
            const transaction = 'delete lead status';
    
            Swal.fire({
                title: 'Confirm Lead Status Deletion',
                text: 'Are you sure you want to delete this lead status?',
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
                        url: 'controller/lead-status-controller.php',
                        dataType: 'json',
                        data: {
                            lead_status_id : lead_status_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete Lead Status Success', 'The lead status has been deleted successfully.', 'success');
                                reloadDatatable('#lead-status-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Delete Lead Status Error', 'The lead status does not exist.', 'danger');
                                    reloadDatatable('#lead-status-table');
                                }
                                else {
                                    showNotification('Delete Lead Status Error', response.message, 'danger');
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

        $(document).on('click','#delete-lead-status',function() {
            let lead_status_id = [];
            const transaction = 'delete multiple lead status';

            $('.datatable-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    lead_status_id.push(element.value);
                }
            });
    
            if(lead_status_id.length > 0){
                Swal.fire({
                    title: 'Confirm Multiple Lead Status Deletion',
                    text: 'Are you sure you want to delete these lead status?',
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
                            url: 'controller/lead-status-controller.php',
                            dataType: 'json',
                            data: {
                                lead_status_id: lead_status_id,
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Delete Lead Status Success', 'The selected lead status have been deleted successfully.', 'success');
                                        reloadDatatable('#lead-status-table');
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else {
                                        showNotification('Delete Lead Status Error', response.message, 'danger');
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
                showNotification('Deletion Multiple Lead Status Error', 'Please select the lead status you wish to delete.', 'danger');
            }
        });

        $(document).on('click','#delete-lead-status-details',function() {
            const lead_status_id = $('#lead-status-id').text();
            const transaction = 'delete lead status';
    
            Swal.fire({
                title: 'Confirm Lead Status Deletion',
                text: 'Are you sure you want to delete this lead status?',
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
                        url: 'controller/lead-status-controller.php',
                        dataType: 'json',
                        data: {
                            lead_status_id : lead_status_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Deleted Lead Status Success', 'The lead status has been deleted successfully.', 'success');
                                window.location = 'lead-status.php';
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
                                    showNotification('Delete Lead Status Error', response.message, 'danger');
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
            discardCreate('lead-status.php');
        });

        $(document).on('click','#edit-form',function() {
            displayDetails('get lead status details');

            enableForm();
        });

        $(document).on('click','#duplicate-lead-status',function() {
            const lead_status_id = $('#lead-status-id').text();
            const transaction = 'duplicate lead status';
    
            Swal.fire({
                title: 'Confirm Lead Status Duplication',
                text: 'Are you sure you want to duplicate this lead status?',
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
                        url: 'controller/lead-status-controller.php',
                        dataType: 'json',
                        data: {
                            lead_status_id : lead_status_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Duplicate Lead Status Success', 'The lead status has been duplicated successfully.', 'success');
                                window.location = 'lead-status.php?id=' + response.leadStatusID;
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Duplicate Lead Status Error', 'The lead status does not exist.', 'danger');
                                    reloadDatatable('#lead-status-table');
                                }
                                else {
                                    showNotification('Duplicate Lead Status Error', response.message, 'danger');
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

function leadStatusTable(datatable_name, buttons = false, show_all = false){
    const type = 'lead status table';
    var settings;

    const column = [ 
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'LEAD_STATUS_NAME' },
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
            'url' : 'view/_lead_status_generation.php',
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

function leadStatusForm(){
    $('#lead-status-form').validate({
        rules: {
            lead_status_name: {
                required: true
            },
        },
        messages: {
            lead_status_name: {
                required: 'Please enter the lead status name'
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
            const lead_status_id = $('#lead-status-id').text();
            const transaction = 'save lead status';
        
            $.ajax({
                type: 'POST',
                url: 'controller/lead-status-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&lead_status_id=' + lead_status_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-data');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert Lead Status Success' : 'Update Lead Status Success';
                        const notificationDescription = response.insertRecord ? 'The lead status has been inserted successfully.' : 'The lead status has been updated successfully.';
                        
                        setNotification(notificationMessage, notificationDescription, 'success');
                        window.location = 'lead-status.php?id=' + response.leadStatusID;
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
        case 'get lead status details':
            const lead_status_id = $('#lead-status-id').text();
            
            $.ajax({
                url: 'controller/lead-status-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    lead_status_id : lead_status_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetForm('lead-status-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#lead_status_id').val(lead_status_id);
                        $('#lead_status_name').val(response.leadStatusName);

                        $('#lead_status_name_label').text(response.leadStatusName);
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Lead Status Details Error', response.message, 'danger');
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