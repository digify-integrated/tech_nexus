(function($) {
    'use strict';

    $(function() {

        if($('#lead-table').length){
            leadTable('#lead-table');
        }

        if($('#lead-form').length){
            leadForm();
        }

        if($('#lead-id').length){
            displayDetails('get lead details');
        }

        /* -----------------------------
        DELETE SINGLE
        ----------------------------- */
        $(document).on('click','.delete-lead',function() {
            const lead_id = $(this).data('lead-id');
            const transaction = 'delete lead';

            Swal.fire({
                title: 'Confirm Lead Deletion',
                text: 'Are you sure you want to delete this lead?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Delete',
                cancelButtonText: 'Cancel',
                confirmButtonClass: 'btn btn-danger mt-2',
                cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                buttonsStyling: false
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        type: 'POST',
                        url: 'controller/lead-controller.php',
                        dataType: 'json',
                        data: {
                            lead_id : lead_id,
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete Lead Success', 'The lead has been deleted successfully.', 'success');
                                reloadDatatable('#lead-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Delete Lead Error', 'The lead does not exist.', 'danger');
                                    reloadDatatable('#lead-table');
                                }
                                else {
                                    showNotification('Delete Lead Error', response.message, 'danger');
                                }
                            }
                        },
                        error: function(xhr, status, error) {
                            showErrorDialog(`XHR status: ${status}, Error: ${error}, Response: ${xhr.responseText}`);
                        }
                    });
                }
            });
        });

        /* -----------------------------
        DELETE MULTIPLE
        ----------------------------- */
        $(document).on('click','#delete-lead',function() {
            let lead_id = [];
            const transaction = 'delete multiple lead';

            $('.datatable-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    lead_id.push(element.value);
                }
            });

            if(lead_id.length > 0){
                Swal.fire({
                    title: 'Confirm Multiple Lead Deletion',
                    text: 'Are you sure you want to delete these leads?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Delete',
                    cancelButtonText: 'Cancel',
                    confirmButtonClass: 'btn btn-danger mt-2',
                    cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                    buttonsStyling: false
                }).then(function(result) {
                    if (result.value) {
                        $.ajax({
                            type: 'POST',
                            url: 'controller/lead-controller.php',
                            dataType: 'json',
                            data: {
                                lead_id : lead_id,
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Delete Lead Success', 'Selected leads deleted successfully.', 'success');
                                    reloadDatatable('#lead-table');
                                }
                                else {
                                    if (response.isInactive) {
                                        window.location = 'logout.php?logout';
                                    }
                                    else {
                                        showNotification('Delete Lead Error', response.message, 'danger');
                                    }
                                }
                            },
                            error: function(xhr, status, error) {
                                showErrorDialog(`XHR status: ${status}, Error: ${error}`);
                            },
                            complete: function(){
                                toggleHideActionDropdown();
                            }
                        });
                    }
                });
            }
            else{
                showNotification('Delete Error', 'Please select at least one lead.', 'danger');
            }
        });

        /* -----------------------------
        DELETE FROM DETAILS
        ----------------------------- */
        $(document).on('click','#delete-lead-details',function() {
            const lead_id = $('#lead-id').text();
            const transaction = 'delete lead';

            Swal.fire({
                title: 'Confirm Lead Deletion',
                text: 'Are you sure you want to delete this lead?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Delete',
                cancelButtonText: 'Cancel',
                confirmButtonClass: 'btn btn-danger mt-2',
                cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                buttonsStyling: false
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        type: 'POST',
                        url: 'controller/lead-controller.php',
                        dataType: 'json',
                        data: {
                            lead_id : lead_id,
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Deleted Lead', 'Lead deleted successfully.', 'success');
                                window.location = 'lead-monitoring.php';
                            }
                            else {
                                if (response.notExist) {
                                    window.location = '404.php';
                                }
                                else {
                                    showNotification('Delete Error', response.message, 'danger');
                                }
                            }
                        },
                        error: function(xhr, status, error) {
                            showErrorDialog(`XHR status: ${status}, Error: ${error}`);
                        }
                    });
                }
            });
        });

        /* -----------------------------
        DUPLICATE
        ----------------------------- */
        $(document).on('click','#duplicate-lead',function() {
            const lead_id = $('#lead-id').text();
            const transaction = 'duplicate lead';

            Swal.fire({
                title: 'Confirm Lead Duplication',
                text: 'Are you sure you want to duplicate this lead?',
                icon: 'info',
                showCancelButton: true,
                confirmButtonText: 'Duplicate',
                cancelButtonText: 'Cancel',
                confirmButtonClass: 'btn btn-info mt-2',
                cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                buttonsStyling: false
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        type: 'POST',
                        url: 'controller/lead-controller.php',
                        dataType: 'json',
                        data: {
                            lead_id : lead_id,
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Duplicate Success', 'Lead duplicated successfully.', 'success');
                                window.location = 'lead-monitoring.php?id=' + response.leadID;
                            }
                            else {
                                showNotification('Duplicate Error', response.message, 'danger');
                            }
                        },
                        error: function(xhr, status, error) {
                            showErrorDialog(`XHR status: ${status}, Error: ${error}`);
                        }
                    });
                }
            });
        });

        /* -----------------------------
        FORM ACTIONS
        ----------------------------- */
        $(document).on('click','#discard-create',function() {
            discardCreate('lead-monitoring.php');
        });

        $(document).on('click','#edit-form',function() {
            displayDetails('get lead details');
            enableForm();
        });

    });
})(jQuery);

/* -----------------------------
DATATABLE
----------------------------- */
function leadTable(datatable_name){

    const type = 'lead table';

    const column = [
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'LEAD_NAME' },
        { 'data' : 'EMAIL' },
        { 'data' : 'PHONE' },
        { 'data' : 'STATUS' },
        { 'data' : 'ASSIGNED_TO' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '1%', 'bSortable': false, 'aTargets': 0 },
        { 'width': '20%', 'aTargets': 1 },
        { 'width': '20%', 'aTargets': 2 },
        { 'width': '15%', 'aTargets': 3 },
        { 'width': '15%', 'aTargets': 4 },
        { 'width': '15%', 'aTargets': 5 },
        { 'width': '14%', 'bSortable': false, 'aTargets': 6 }
    ];

    destroyDatatable(datatable_name);

    $(datatable_name).dataTable({
        'ajax': {
            'url' : 'view/_lead_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {'type' : type},
            'dataSrc' : ''
        },
        'order': [[1, 'asc']],
        'columns' : column,
        'columnDefs': column_definition,
        'language': {
            'emptyTable': 'No lead found',
            'searchPlaceholder': 'Search...',
            'search': ''
        }
    });
}

/* -----------------------------
FORM
----------------------------- */
function leadForm(){
    $('#lead-form').validate({
        rules: {
            lead_name: { required: true }
        },
        messages: {
            lead_name: { required: 'Please enter the lead name' }
        },
        submitHandler: function(form) {

            const lead_id = $('#lead-id').text();
            const transaction = 'save lead';

            $.ajax({
                type: 'POST',
                url: 'controller/lead-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&lead_id=' + lead_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-data');
                },
                success: function (response) {
                    if (response.success) {
                        setNotification('Success', 'Lead saved successfully.', 'success');
                        window.location = 'lead-monitoring.php?id=' + response.leadID;
                    }
                    else {
                        showNotification('Error', response.message, 'danger');
                    }
                },
                complete: function() {
                    enableFormSubmitButton('submit-data', 'Save');
                }
            });

            return false;
        }
    });
}

/* -----------------------------
DISPLAY DETAILS
----------------------------- */
function displayDetails(transaction){

    const lead_id = $('#lead-id').text();

    $.ajax({
        url: 'controller/lead-controller.php',
        method: 'POST',
        dataType: 'json',
        data: {
            lead_id : lead_id,
            transaction : transaction
        },
        success: function(response) {
            if (response.success) {

                $('#lead_name').val(response.leadName);
                $('#email').val(response.email);
                $('#phone').val(response.phone);
                $('#remarks').val(response.remarks);

                checkOptionExist('#lead_status_id', response.leadStatusId, '');
                checkOptionExist('#assigned_to', response.assignedTo, '');

                $('#lead_name_label').text(response.leadName);
                $('#email_label').text(response.email);
                $('#phone_label').text(response.phone);
                $('#lead_status_label').text(response.leadStatusName);
                $('#assigned_to_label').text(response.assignedToName);
                $('#remarks_label').text(response.remarks);
            }
        },
        error: function(xhr, status, error) {
            showErrorDialog(`XHR status: ${status}, Error: ${error}`);
        }
    });
}