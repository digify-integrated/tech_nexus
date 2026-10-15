(function($) {
    'use strict';

    $(function() {

        if($('#lead-table').length){
            leadTable('#lead-table');
        }

        if($('#product-inquiry-table').length){
            productInquiryTable('#product-inquiry-table');
        }

        if($('#cif-table').length){
            cifTable('#cif-table');
        }

        if($('#quotation-table').length){
            quotationTable('#quotation-table');
        }

        if($('#lead-form').length){
            leadForm();
        }

        if($('#product-inquiry-form').length){
            productInquiryForm();
        }

        if($('#cif-form').length){
            cifForm();
        }

        if($('#quotation-form').length){
            quotationForm();
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

        $(document).on('click','.delete-inquiry',function() {
            const leads_product_inquiry_id = $(this).data('leads-product-inquiry-id');
            const transaction = 'delete product inquiry';
    
            Swal.fire({
                title: 'Confirm Product Inquiry Deletion',
                text: 'Are you sure you want to delete this product inquiry?',
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
                        url: 'controller/lead-controller.php',
                        dataType: 'json',
                        data: {
                            leads_product_inquiry_id : leads_product_inquiry_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete Product Inquiry Success', 'The product inquiry has been deleted successfully.', 'success');
                                reloadDatatable('#product-inquiry-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Delete Product Inquiry Error', 'The product inquiry does not exists.', 'danger');
                                    reloadDatatable('#product-inquiry-table');
                                }
                                else {
                                    showNotification('Delete Product Inquiry Error', response.message, 'danger');
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


        $(document).on('click','.add-quotation',function() {
            const leads_product_inquiry_id = $(this).data('leads-product-inquiry-id');
            const product_id = $(this).data('product-id');

            $("#leads_product_inquiry_id").val(leads_product_inquiry_id);
            displaySuggestivePriceDetails(product_id)

            displayInclusionsDetails('get inclusions details');
        });

        $(document).on('click','.add-cif',function() {
            const leads_product_inquiry_id = $(this).data('leads-product-inquiry-id');

            $("#leads_product_inquiry_id2").val(leads_product_inquiry_id);
        });

        $(document).on('click','.update-quotation',function() {
            const quotation_id = $(this).data('quotation-id'); 

            $("#quotation_id").val(quotation_id);
            displayQuotationDetails();
        });

        $(document).on('click','.update-cif',function() {
            const cif_id = $(this).data('cif-id');

            $("#cif_id").val(cif_id);
            displayCifDetails();
        });

        $(document).on('click', '.delete-quotation', function(){
            const quotation_id = $(this).data('quotation-id');

            Swal.fire({
                title: 'Delete Quotation',
                text: 'Are you sure you want to delete this quotation?',
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
                            transaction: 'delete quotation',
                            quotation_id: quotation_id
                        },
                        dataType: 'json',

                        success: function(response){

                            if(response.success){
                                showNotification(
                                    'Success',
                                    'The quotation has been deleted.',
                                    'success'
                                );

                                reloadDatatable('#quotation-table');
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
                                'Unable to delete quotation.',
                                'danger'
                            );
                        }
                    });
                }
            });
        });

        $(document).on('click', '.delete-cif', function(){
            const cif_id = $(this).data('cif-id');

            Swal.fire({
                title: 'Delete CIF',
                text: 'Are you sure you want to delete this CIF?',
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
                            transaction: 'delete cif',
                            cif_id: cif_id
                        },
                        dataType: 'json',

                        success: function(response){

                            if(response.success){
                                showNotification(
                                    'Success',
                                    'The CIF has been deleted.',
                                    'success'
                                );

                                reloadDatatable('#cif-table');
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
                                'Unable to delete cif.',
                                'danger'
                            );
                        }
                    });
                }
            });
        });

        $(document).on('click', '.send-for-approval-quotation', function(){
            const quotation_id = $(this).data('quotation-id');

            Swal.fire({
                title: 'For Approval Quotation',
                text: 'Are you sure you want to tag this quotation for approval?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'For Approval',
                cancelButtonText: 'Cancel',
            }).then((result) => {

                if(result.isConfirmed){

                    $.ajax({
                        type: 'POST',
                        url: 'controller/lead-controller.php',
                        data: {
                            transaction: 'for approval quotation',
                            quotation_id: quotation_id
                        },
                        dataType: 'json',

                        success: function(response){

                            if(response.success){
                                showNotification(
                                    'Success',
                                    'The quotation has been tagged for approval.',
                                    'success'
                                );

                                reloadDatatable('#quotation-table');
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
                                'Unable to tag quotation for approval.',
                                'danger'
                            );
                        }
                    });
                }
            });
        });

        $(document).on('click', '.complete-cif', function(){
            const cif_id = $(this).data('cif-id');

            Swal.fire({
                title: 'Complete CIF',
                text: 'Are you sure you want to tag this CIF as complete?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Submit',
                cancelButtonText: 'Cancel',
            }).then((result) => {

                if(result.isConfirmed){

                    $.ajax({
                        type: 'POST',
                        url: 'controller/lead-controller.php',
                        data: {
                            transaction: 'complete cif',
                            cif_id: cif_id
                        },
                        dataType: 'json',

                        success: function(response){

                            if(response.success){
                                showNotification(
                                    'Success',
                                    'The CIF has been tagged as completed.',
                                    'success'
                                );

                                reloadDatatable('#cif-table');
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
                                'Unable to submit CIF for approval.',
                                'danger'
                            );
                        }
                    });
                }
            });
        });

        $(document).on('click', '.set-as-draft-quotation', function(){
            const quotation_id = $(this).data('quotation-id');

            Swal.fire({
                title: 'Draft Quotation',
                text: 'Are you sure you want to tag this quotation as draft?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Draft',
                cancelButtonText: 'Cancel',
            }).then((result) => {

                if(result.isConfirmed){

                    $.ajax({
                        type: 'POST',
                        url: 'controller/lead-controller.php',
                        data: {
                            transaction: 'draft quotation',
                            quotation_id: quotation_id
                        },
                        dataType: 'json',

                        success: function(response){

                            if(response.success){
                                showNotification(
                                    'Success',
                                    'The quotation has been tagged as draft.',
                                    'success'
                                );

                                reloadDatatable('#quotation-table');
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
                                'Unable to tag quotation for approval.',
                                'danger'
                            );
                        }
                    });
                }
            });
        });

        $(document).on('click', '.cancel-quotation', function(){
            const quotation_id = $(this).data('quotation-id');

            Swal.fire({
                title: 'Cancel Quotation',
                text: 'Are you sure you want to tag this quotation as cancel?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Cancel',
                cancelButtonText: 'Cancel',
            }).then((result) => {

                if(result.isConfirmed){

                    $.ajax({
                        type: 'POST',
                        url: 'controller/lead-controller.php',
                        data: {
                            transaction: 'cancel quotation',
                            quotation_id: quotation_id
                        },
                        dataType: 'json',

                        success: function(response){

                            if(response.success){
                                showNotification(
                                    'Success',
                                    'The quotation has been tagged as cancelled.',
                                    'success'
                                );

                                reloadDatatable('#quotation-table');
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
                                'Unable to tag quotation for approval.',
                                'danger'
                            );
                        }
                    });
                }
            });
        });

        $(document).on('click', '.cancel-cif', function(){
            const cif_id = $(this).data('cif-id');

            Swal.fire({
                title: 'Cancel CIF',
                text: 'Are you sure you want to tag this CIF as cancel?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Cancel',
                cancelButtonText: 'Cancel',
            }).then((result) => {

                if(result.isConfirmed){

                    $.ajax({
                        type: 'POST',
                        url: 'controller/lead-controller.php',
                        data: {
                            transaction: 'cancel cif',
                            cif_id: cif_id
                        },
                        dataType: 'json',

                        success: function(response){

                            if(response.success){
                                showNotification(
                                    'Success',
                                    'The CIF has been tagged as cancelled.',
                                    'success'
                                );

                                reloadDatatable('#cif-table');
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
                                'Unable to tag CIF for approval.',
                                'danger'
                            );
                        }
                    });
                }
            });
        });

        $(document).on('click', '.on-process-cif', function(){
            const cif_id = $(this).data('cif-id');

            Swal.fire({
                title: 'On-Process CIF',
                text: 'Are you sure you want to tag this CIF as on-process?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'On-Process',
                cancelButtonText: 'Cancel',
            }).then((result) => {

                if(result.isConfirmed){

                    $.ajax({
                        type: 'POST',
                        url: 'controller/lead-controller.php',
                        data: {
                            transaction: 'on-process cif',
                            cif_id: cif_id
                        },
                        dataType: 'json',

                        success: function(response){

                            if(response.success){
                                showNotification(
                                    'Success',
                                    'The CIF has been tagged as on-process.',
                                    'success'
                                );

                                reloadDatatable('#cif-table');
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
                                'Unable to tag CIF as on-process.',
                                'danger'
                            );
                        }
                    });
                }
            });
        });

        $(document).on('change', '#stock_number', function () {
            document.getElementById("unit_description").value = '';
            document.getElementById("engine_no").value = '';
            document.getElementById("chassis_no").value = '';
            document.getElementById("color").value = '';
            document.getElementById("length").value = '';
            checkOptionExist('#year_model', '', '');
            checkOptionExist('#product_subcategory_id', '', '');
            checkOptionExist('#transmission_type', '', '');
            checkOptionExist('#length_unit', '8', '');

            if($(this).val() != ''){
                document.getElementById("engine_no").readOnly = true;
                document.getElementById("chassis_no").readOnly = true;
                document.getElementById("color").readOnly = true;
                document.getElementById("length").readOnly = true;
                $('#year_model').prop('disabled', true).trigger('change');
                $('#product_subcategory_id').prop('disabled', true).trigger('change');
                $('#transmission_type').prop('disabled', true).trigger('change');
                $('#length_unit').prop('disabled', true).trigger('change');

                displayProductDetails();
            }
            else{
                document.getElementById("engine_no").readOnly = false;
                document.getElementById("chassis_no").readOnly = false;
                document.getElementById("color").readOnly = false;
                document.getElementById("length").readOnly = false;
                $('#year_model').prop('disabled', false).trigger('change');
                $('#product_subcategory_id').prop('disabled', false).trigger('change');
                $('#transmission_type').prop('disabled', false).trigger('change');
                $('#length_unit').prop('disabled', false).trigger('change');
            }
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
    let assigned_to_filter = [];

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

    $('.assigned-to-filter:checked').each(function () {
        assigned_to_filter.push($(this).val());
    });

    const filter_lead_status = lead_status_filter.join(', ');
    const filter_lead_source = lead_source_filter.join(', ');
    const filter_lead_priority = lead_priority_filter.join(', ');
    const filter_inquiry_type = inquiry_type_filter.join(', ');
    const filter_assigned_to = assigned_to_filter.join(', ');

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
        { data: 'LEAD_NOTE_DATE' },
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
        { width: 'auto', type: 'date', targets: 6 },
        { width: 'auto', targets: 7 },
        { width: 'auto', targets: 8 },
        { width: 'auto', targets: 9 },
        { width: 'auto', type: 'date', targets: 10 },
        { width: 'auto', targets: 11 },
        { width: '14%', bSortable: false, targets: 12 }
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
                filter_assigned_to: filter_assigned_to,
            },
            dataSrc: ''
        },

        order: [[10, 'desc']],
        columns: column,
        columnDefs: column_definition,

        // Hide DataTables default buttons UI
        dom: 'flrtip',

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

function productInquiryTable(datatable_name, buttons = false, show_all = false){
    const type = 'product inquiry table';
    const lead_id = $('#lead-id').text();

    var settings;

   const column = [ 
        { 'data' : 'PRODUCT' },
        { 'data' : 'CATEGORY' },
        { 'data' : 'TRANSMISSION_TYPE' },
        { 'data' : 'LENGTH' },
        { 'data' : 'ENGINE_NO' },
        { 'data' : 'CHASSIS_NO' },
        { 'data' : 'COLOR' },
        { 'data' : 'YEAR_MODEL' },
        { 'data' : 'CREATED_DATE' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'type': 'date', 'aTargets': 8 },
        { 'width': '5%','bSortable': false, 'aTargets': 9 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_lead_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {
                'type' : type, 
                'lead_id' : lead_id,
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

function quotationTable(datatable_name, buttons = false, show_all = false){
    const type = 'quotation table';
    const lead_id = $('#lead-id').text();

    var settings;

   const column = [ 
        { 'data' : 'PRODUCT' },
        { 'data' : 'QUANTITY' },
        { 'data' : 'PRICE' },
        { 'data' : 'DISCOUNT' },
        { 'data' : 'TOTAL' },
        { 'data' : 'STATUS' },
        { 'data' : 'COMPANY' },
        { 'data' : 'INCLUSIONS' },
        { 'data' : 'DELIVERY_ADDRESS' },
        { 'data' : 'EXPIRATION_DATE' },
        { 'data' : 'CREATED_DATE' },
        { 'data' : 'APPROVAL_CONFIRMATION' },
        { 'data' : 'REMARKS' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '5%','bSortable': false, 'aTargets': 12 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_lead_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {
                'type' : type, 
                'lead_id' : lead_id,
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

function cifTable(datatable_name, buttons = false, show_all = false){
    const type = 'cif table';
    const lead_id = $('#lead-id').text();

    var settings;

   const column = [ 
        { 'data' : 'REFERENCE_NO' },
        { 'data' : 'PRODUCT' },
        { 'data' : 'COMPANY' },
        { 'data' : 'SUPPLIER' },
        { 'data' : 'ADD_ON' },
        { 'data' : 'STATUS' },
        { 'data' : 'REMARKS' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '5%','bSortable': false, 'aTargets': 7 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_lead_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {
                'type' : type, 
                'lead_id' : lead_id,
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

/* -----------------------------
FORM
----------------------------- */
function submitLeadForm(form, confirmDuplicate = false) {
    const lead_id = $('#lead-id').text();
    const transaction = 'save lead';
    
    let formData = $(form).serialize() + '&transaction=' + transaction + '&lead_id=' + lead_id;
    if (confirmDuplicate) {
        formData += '&confirm_duplicate=1';
    }

    $.ajax({
        type: 'POST',
        url: 'controller/lead-controller.php',
        data: formData,
        dataType: 'json',
        success: function (response) {
            if (response.success) {
                setNotification('Success', 'Lead saved successfully.', 'success');
                window.location = 'lead-monitoring.php?id=' + response.leadID;
            } 
            // Handle duplicate warning prompt
            else if (response.isDuplicate) {
                // Option A: SweetAlert2
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Duplicate Lead Warning',
                        text: response.message,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, submit form',
                        cancelButtonText: 'No'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Call helper directly with confirmDuplicate = true
                            submitLeadForm(form, true);
                        }
                    });
                } 
                // Option B: Standard Browser Confirmation Fallback
                else {
                    if (confirm(response.message)) {
                        // Call helper directly with confirmDuplicate = true
                        submitLeadForm(form, true);
                    }
                }
            } 
            else {
                showNotification('Error', response.message, 'danger');
            }
        },
    });
}

function leadForm(){
    $('#lead-form').validate({
        rules: {
            first_name: { required: true },
            last_name: { required: true },
            inquiry_type_id: { required: true },
            lead_status_id: { required: true },
            lead_priority: { required: true },
            lead_source_id: { required: true }
        },
        messages: {
            first_name: { required: 'Please enter the first name' },
            last_name: { required: 'Please enter the last name' },
            inquiry_type_id: { required: 'Please choose the inquiry type' },
            lead_status_id: { required: 'Please choose the lead status' },
            lead_priority: { required: 'Please choose the lead priority' },
            lead_source_id: { required: 'Please choose the lead source' },
        },
        submitHandler: function(form) {
            submitLeadForm(form, false);
            return false;
        }
    });
}

function productInquiryForm(){
    $('#product-inquiry-form').validate({
        rules: {
            product_subcategory_id: {
                required: true
            },
            length: {
                required: true
            },
            unit_description: {
                required: true
            },
        },
        messages: {
            product_subcategory_id: {
                required: 'Please choose the product category'
            },
            length: {
                required: 'Please enter the length'
            },
            unit_description: {
                required: 'Please enter the unit description'
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
            const lead_id = $('#lead-id').text();
            const transaction = 'save product inquiry';
            var formData = new FormData(form);
            formData.append('transaction', transaction);
            formData.append('lead_id', lead_id);
        
            $.ajax({
                type: 'POST',
                url: 'controller/lead-controller.php',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-product-inquiry');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = 'Save Product Inquiry Success';
                        const notificationDescription = 'The product inquiry has been saved successfully.';
                        
                        showNotification(notificationMessage, notificationDescription, 'success');

                        resetForm('product-inquiry-form');
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
                    enableFormSubmitButton('submit-product-inquiry', 'Add Product Inquiry');
                    reloadDatatable('#product-inquiry-table');
                }
            });
        
            return false;
        }
    });
}

function cifForm(){
    $('#cif-form').validate({
        rules: {
            company_id2: {
                required: true
            },
        },
        messages: {
            company_id2: {
                required: 'Please choose the company'
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
            const lead_id = $('#lead-id').text();
            const transaction = 'save cif';
            var formData = new FormData(form);
            formData.append('transaction', transaction);
            formData.append('lead_id', lead_id);
        
            $.ajax({
                type: 'POST',
                url: 'controller/lead-controller.php',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-cif');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = 'Save CIF Success';
                        const notificationDescription = 'The CIF has been saved successfully.';
                        
                        showNotification(notificationMessage, notificationDescription, 'success');
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
                    enableFormSubmitButton('submit-cif', 'Save');
                    reloadDatatable('#cif-table');
                    $('#cif-modal').modal('hide');
                    resetModalForm('cif-form');
                }
            });
        
            return false;
        }
    });
}

function quotationForm(){
    $('#quotation-form').validate({
        rules: {
            company_id: {
                required: true
            },
            expiration_date: {
                required: true
            },
            price: {
                required: true
            },
            approval_confirmation: { 
                required: function(element) {
                    return $('#pre_approve').val() === 'Yes';
                }
            },
        },
        messages: {
            company_id: {
                required: 'Please choose the company'
            },
            expiration_date: {
                required: 'Please choose the expiration date'
            },
            price: {
                required: 'Please enter the quoted price'
            },
            approval_confirmation: { required: 'Please provide the approval confirmation' }
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
            const lead_id = $('#lead-id').text();
            const transaction = 'save quotation';
            var formData = new FormData(form);
            formData.append('transaction', transaction);
            formData.append('lead_id', lead_id);
        
            $.ajax({
                type: 'POST',
                url: 'controller/lead-controller.php',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-quotation');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = 'Save Quotation Success';
                        const notificationDescription = 'The quotation has been saved successfully.';
                        
                        showNotification(notificationMessage, notificationDescription, 'success');
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
                    enableFormSubmitButton('submit-quotation', 'Save');
                    reloadDatatable('#quotation-table');
                    $('#quotation-modal').modal('hide');
                    resetModalForm('quotation-form');
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
                        
                    resetModalForm('lead-status-form');

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

function displayInclusionsDetails(transaction){

    const leads_product_inquiry_id = $('#leads_product_inquiry_id').val();

    $.ajax({
        url: 'controller/lead-controller.php',
        method: 'POST',
        dataType: 'json',
        data: {
            leads_product_inquiry_id : leads_product_inquiry_id,
            transaction : transaction
        },
        success: function(response) {
            if (response.success) {
                $('#inclusions').val(response.standard_inclusions);
            }
        },
        error: function(xhr, status, error) {
            showErrorDialog(`XHR status: ${status}, Error: ${error}`);
        }
    });
}

function displaySuggestivePriceDetails(product_id){
    let transaction = 'get product details';
    
    if(product_id != '' && product_id != 0){
        $.ajax({
            url: 'controller/product-controller.php',
            method: 'POST',
            dataType: 'json',
            data: {
                product_id: product_id, 
                transaction : transaction
            },
            success: function(response) {
                if (response.success) {
                    $('#price').val(response.productPrice);
                } 
                else {
                    if(response.isInactive){
                        window.location = 'logout.php?logout';
                    }
                    else{
                        showNotification('Get Product Details Error', response.message, 'danger');
                    }
                }
            },
            error: function(xhr, status, error) {
                var fullErrorMessage = 'XHR status: ${status}, Error: ${error}';
                if (xhr.responseText) {
                    fullErrorMessage += ', Response: ${xhr.responseText}';
                }
                showErrorDialog(fullErrorMessage);
            },
        });
    }
}

function displayProductDetails(){
    let transaction = 'get product details';
    var stock_number = $('#stock_number').val();
    
    if(stock_number != '' && stock_number != 0){
        $.ajax({
            url: 'controller/product-controller.php',
            method: 'POST',
            dataType: 'json',
            data: {
                product_id : stock_number, 
                transaction : transaction
            },
            success: function(response) {
                if (response.success) {
                    $('#unit_description').val(response.remarks);
                    $('#engine_no').val(response.engineNumber);
                    $('#chassis_no').val(response.chassisNumber);
                    $('#color').val(response.colorName);
                    $('#price').val(response.productPrice);
                    $('#length').val(response.length);

                    checkOptionExist('#year_model', response.year_model, '');
                    checkOptionExist('#length_unit', response.lengthUnit, '');
                    checkOptionExist('#product_subcategory_id', response.productSubcategoryID, '');
                } 
                else {
                    if(response.isInactive){
                        window.location = 'logout.php?logout';
                    }
                    else{
                        showNotification('Get Product Details Error', response.message, 'danger');
                    }
                }
            },
            error: function(xhr, status, error) {
                var fullErrorMessage = 'XHR status: ${status}, Error: ${error}';
                if (xhr.responseText) {
                    fullErrorMessage += ', Response: ${xhr.responseText}';
                }
                showErrorDialog(fullErrorMessage);
            },
        });
    }
}

function displayQuotationDetails(){
    let transaction = 'get quotation details';
    var quotation_id = $('#quotation_id').val();
    
    $.ajax({
        url: 'controller/lead-controller.php',
        method: 'POST',
        dataType: 'json',
        data: {
            quotation_id : quotation_id, 
            transaction : transaction
        },
        success: function(response) {
            if (response.success) {
                $('#leads_product_inquiry_id').val(response.leads_product_inquiry_id);
                $('#price').val(response.price);
                $('#quantity').val(response.quantity);
                $('#discount').val(response.discount);
                $('#inclusions').val(response.inclusions);
                $('#delivery_address').val(response.delivery_address);
                $('#delivery_instructions').val(response.delivery_instructions);
                $('#delivery_conditions').val(response.delivery_conditions);
                $('#expiration_date').val(response.expiration_date);
                $('#remarks2').val(response.remarks);

                checkOptionExist('#company_id', response.company_id, '');
            } 
            else {
                if(response.isInactive){
                    window.location = 'logout.php?logout';
                }
                else{
                    showNotification('Get Quotation Details Error', response.message, 'danger');
                }
            }
        },
        error: function(xhr, status, error) {
            var fullErrorMessage = 'XHR status: ${status}, Error: ${error}';
            if (xhr.responseText) {
                fullErrorMessage += ', Response: ${xhr.responseText}';
            }
            showErrorDialog(fullErrorMessage);
        },
    });
}

function displayCifDetails(){
    let transaction = 'get cif details';
    var cif_id = $('#cif_id').val();
    
    $.ajax({
        url: 'controller/lead-controller.php',
        method: 'POST',
        dataType: 'json',
        data: {
            cif_id : cif_id, 
            transaction : transaction
        },
        success: function(response) {
            if (response.success) {
                $('#leads_product_inquiry_id').val(response.leads_product_inquiry_id);
                $('#add_ons').val(response.add_ons);
                $('#remarks_3').val(response.remarks);

                checkOptionExist('#company_id2', response.company_id, '');
                
                // Set multiple values in Select2 and trigger change
                $('#supplier_id').val(response.supplier_id).trigger('change');
            } 
            else {
                if(response.isInactive){
                    window.location = 'logout.php?logout';
                }
                else{
                    showNotification('Get CIF Details Error', response.message, 'danger');
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
    });
}