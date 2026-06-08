(function($) {
    'use strict';

    $(function() {

        if($('#lead-table').length){
            leadTable('#lead-table');
        }

        if($('#lead-form').length){
            leadForm();
        }

        if($('#lead-note-form').length){
            leadNoteForm();
        }

        if($('#lead-status-form').length){
            leadStatusForm();
        }

        if($('#lead-id').length){
            displayDetails('get lead details');
            loadLeadNotes();
        }

        $(document).on('click','#apply-filter',function() {
            leadTable('#lead-table');
        });

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

        $(document).on('click', '.delete-lead-note', function(){

                const lead_note_id = $(this).data('lead-note-id');

                Swal.fire({
                    title: 'Delete Internal Note',
                    text: 'Are you sure you want to delete this internal note?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Delete',
                    cancelButtonText: 'Cancel',
                    confirmButtonColor: '#dc3545'
                }).then((result) => {

                    if(result.isConfirmed){

                        $.ajax({
                            type: 'POST',
                            url: 'controller/lead-controller.php',
                            data: {
                                transaction: 'delete lead note',
                                lead_note_id: lead_note_id
                            },
                            dataType: 'json',

                            success: function(response){

                                if(response.success){

                                    setNotification(
                                        'Deleted',
                                        'Lead note deleted successfully.',
                                        'success'
                                    );

                                    loadLeadNotes();
                                }
                                else{

                                    showNotification(
                                        'Error',
                                        response.message,
                                        'danger'
                                    );
                                }
                            },

                            error: function(){

                                showNotification(
                                    'Error',
                                    'Unable to delete note.',
                                    'danger'
                                );
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
function leadTable(datatable_name) {

    const type = 'lead table';

    const filter_created_date_start_date = $('#filter_created_date_start_date').val();
    const filter_created_date_end_date = $('#filter_created_date_end_date').val();
    const filter_inquiry_date_start_date = $('#filter_inquiry_date_start_date').val();
    const filter_inquiry_date_end_date = $('#filter_inquiry_date_end_date').val();

    let lead_status_filter = [];
    let lead_source_filter = [];
    let lead_priority_filter = [];
    let inquiry_type_filter = [];

    $('.lead-status-filter:checked').each(function () {
        lead_status_filter.push($(this).val());
    });

    $('.lead-source-filter:checked').each(function () {
        lead_source_filter.push($(this).val());
    });

    $('.lead-priority-filter:checked').each(function () {
        lead_priority_filter.push($(this).val());
    });

    $('.inquiry-type-filter:checked').each(function () {
        inquiry_type_filter.push($(this).val());
    });

    const filter_lead_status = lead_status_filter.join(', ');
    const filter_lead_source = lead_source_filter.join(', ');
    const filter_lead_priority = lead_priority_filter.join(', ');
    const filter_inquiry_type = inquiry_type_filter.join(', ');

    const column = [
        { data: 'CHECK_BOX' },
        { data: 'LEAD_NAME' },
        { data: 'LEAD_SOURCE' },
        { data: 'LEAD_PRIORITY' },
        { data: 'PHONE' },
        { data: 'INQUIRY_TYPE' },
        { data: 'INQUIRY_DATE' },
        { data: 'PRODUCT' },
        { data: 'STATUS' },
        { data: 'LEAD_NOTE' },
        { data: 'ASSIGNED_TO' },
        { data: 'ACTION' }
    ];

    const column_definition = [
        { width: '1%', bSortable: false, targets: 0 },
        { width: 'auto', targets: 1 },
        { width: 'auto', targets: 2 },
        { width: 'auto', targets: 3 },
        { width: 'auto', targets: 4 },
        { width: 'auto', targets: 5 },
        { width: 'auto', targets: 6 },
        { width: 'auto', targets: 7 },
        { width: 'auto', targets: 8 },
        { width: 'auto', targets: 9 },
        { width: 'auto', targets: 10 },
        { width: '14%', bSortable: false, targets: 11 }
    ];

    destroyDatatable(datatable_name);

    const table = $(datatable_name).DataTable({
        ajax: {
            url: 'view/_lead_generation.php',
            method: 'POST',
            dataType: 'json',
            data: {
                type: type,
                filter_created_date_start_date: filter_created_date_start_date,
                filter_created_date_end_date: filter_created_date_end_date,
                filter_inquiry_date_start_date: filter_inquiry_date_start_date,
                filter_inquiry_date_end_date: filter_inquiry_date_end_date,
                filter_lead_status: filter_lead_status,
                filter_lead_source: filter_lead_source,
                filter_lead_priority: filter_lead_priority,
                filter_inquiry_type: filter_inquiry_type,
            },
            dataSrc: ''
        },

        order: [[1, 'asc']],
        columns: column,
        columnDefs: column_definition,

        // Hide DataTables default buttons UI
        dom: 'rtip',

        language: {
            emptyTable: 'No lead found',
            searchPlaceholder: 'Search...',
            search: ''
        },

        // Keep buttons functionality internally
        buttons: [
            {
                extend: 'csvHtml5',
                title: 'Lead List'
            },
            {
                extend: 'excelHtml5',
                title: 'Lead List'
            },
            {
                extend: 'pdfHtml5',
                title: 'Lead List'
            }
        ]
    });

    // Custom Export Buttons
    $('#export-csv').off('click').on('click', function () {
        table.button(0).trigger();
    });

    $('#export-excel').off('click').on('click', function () {
        table.button(1).trigger();
    });

    $('#export-pdf').off('click').on('click', function () {
        table.button(2).trigger();
    });
}

/* -----------------------------
FORM
----------------------------- */
function leadForm(){
    $('#lead-form').validate({
        rules: {
            first_name: {
                required: true
            },
            last_name: {
                required: true
            },
            inquiry_type_id: {
                required: true
            },
            lead_status_id: {
                required: true
            },
            lead_source_id: {
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
            inquiry_type_id: {
                required: 'Please choose the inquiry type'
            },
            lead_status_id: {
                required: 'Please choose the lead status'
            },
            lead_source_id: {
                required: 'Please choose the lead source'
            },
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

function leadNoteForm(){

    $('#lead-note-form').validate({
        rules: {
            lead_note: {
                required: true
            }
        },
        messages: {
            lead_note: {
                required: 'Please enter the note'
            }
        },
        submitHandler: function(form) {

            const lead_id = $('#lead-id').text();
            const transaction = 'save lead note';

            $.ajax({
                type: 'POST',
                url: 'controller/lead-controller.php',
                data: $(form).serialize() +
                      '&transaction=' + transaction +
                      '&lead_id=' + lead_id,
                dataType: 'json',

                beforeSend: function() {
                    disableFormSubmitButton('submit-lead-note');
                },

                success: function (response) {

                    if (response.success) {

                        setNotification(
                            'Success',
                            'Lead note saved successfully.',
                            'success'
                        );

                        $('#add-internal-note-modal').modal('hide');

                        $('#lead-note-form')[0].reset();

                        loadLeadNotes();

                    }
                    else {
                        showNotification(
                            'Error',
                            response.message,
                            'danger'
                        );
                    }
                },

                complete: function() {
                    enableFormSubmitButton(
                        'submit-lead-note',
                        'Save Note'
                    );
                }
            });

            return false;
        }
    });
}

function leadStatusForm(){

    $('#lead-status-form').validate({
        rules: {
            lead_status_id2: {
                required: true
            }
        },
        messages: {
            lead_status_id2: {
                required: 'Choose the lead status'
            }
        },
        submitHandler: function(form) {

            const lead_id = $('#lead-id').text();
            const transaction = 'save lead status';

            $.ajax({
                type: 'POST',
                url: 'controller/lead-controller.php',
                data: $(form).serialize() +
                      '&transaction=' + transaction +
                      '&lead_id=' + lead_id,
                dataType: 'json',

                beforeSend: function() {
                    disableFormSubmitButton('submit-lead-status');
                },

                success: function (response) {

                    if (response.success) {

                        setNotification(
                            'Success',
                            'Lead status saved successfully.',
                            'success'
                        );

                        $('#update-lead-status-modal').modal('hide');
                        $('#add-internal-note-modal').modal('show');

                        $('#lead-status-form')[0].reset();

                        displayDetails('get lead details');

                    }
                    else {
                        showNotification(
                            'Error',
                            response.message,
                            'danger'
                        );
                    }
                },

                complete: function() {
                    enableFormSubmitButton(
                        'submit-lead-status',
                        'Save'
                    );
                }
            });

            return false;
        }
    });
}

function loadLeadNotes(){

    const lead_id = $('#lead-id').text();

    $.ajax({
        url: 'view/_lead_generation.php',
        type: 'POST',
        data: {
            type: 'lead note table',
            lead_id: lead_id
        },

        beforeSend: function(){

            $('#lead-notes-container').html(`
                <div class="text-center py-4 text-muted">
                    Loading notes...
                </div>
            `);
        },

        success: function(response){

            if($.trim(response) === ''){

                $('#lead-notes-container').html('');

                $('#no-lead-notes').removeClass('d-none');
            }
            else{

                $('#lead-notes-container').html(response);

                $('#no-lead-notes').addClass('d-none');
            }
        },

        error: function(){

            $('#lead-notes-container').html(`
                <div class="alert alert-danger mb-0">
                    Unable to load notes.
                </div>
            `);
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

                $('#first_name').val(response.firstName);
                $('#middle_name').val(response.middleName);
                $('#last_name').val(response.lastName);

                $('#corporate_name').val(response.corporateName);

                $('#email').val(response.email);
                $('#phone').val(response.phone);

                $('#address').val(response.address);
                $('#remarks').val(response.remarks);
                $('#inquiry_date').val(response.inquiryDate);

                checkOptionExist('#gender_id', response.genderId, '');
                checkOptionExist('#city_id', response.cityId, '');
                checkOptionExist('#stock_number', response.stockNumber, '');
                checkOptionExist('#lead_status_id', response.leadStatusId, '');
                checkOptionExist('#lead_status_id2', response.leadStatusId, '');
                checkOptionExist('#inquiry_type_id', response.inquiryTypeId, '');
                checkOptionExist('#lead_source_id', response.leadSourceId, '');
                checkOptionExist('#lead_priority', response.leadPriority, '');
            }
        },
        error: function(xhr, status, error) {
            showErrorDialog(`XHR status: ${status}, Error: ${error}`);
        }
    });
}