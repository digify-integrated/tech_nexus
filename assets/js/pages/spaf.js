(function($) {
    'use strict';

    $(function() {
        if($('#spaf-table').length){
            spafTable('#spaf-table');
        }

        if($('#spaf-form').length){
            spafForm();
        }

        $(document).on('click','.delete-spaf',function() {
            const spaf_id = $(this).data('spaf-id');
            const transaction = 'delete spaf';
    
            Swal.fire({
                title: 'Confirm SPAF Deletion',
                text: 'Are you sure you want to delete this spaf?',
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
                            spaf_id : spaf_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete SPAF Success', 'The spaf has been deleted successfully.', 'success');
                                reloadDatatable('#spaf-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Delete SPAF Error', 'The spaf does not exist.', 'danger');
                                    reloadDatatable('#spaf-table');
                                }
                                else {
                                    showNotification('Delete SPAF Error', response.message, 'danger');
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

        $(document).on('click','#delete-spaf',function() {
            let spaf_id = [];
            const transaction = 'delete multiple spaf';

            $('.datatable-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    spaf_id.push(element.value);
                }
            });
    
            if(spaf_id.length > 0){
                Swal.fire({
                    title: 'Confirm Multiple Unit Categories Deletion',
                    text: 'Are you sure you want to delete these unit categories?',
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
                                spaf_id: spaf_id,
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Delete SPAF Success', 'The selected unit categories have been deleted successfully.', 'success');
                                    reloadDatatable('#spaf-table');
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else {
                                        showNotification('Delete SPAF Error', response.message, 'danger');
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
                showNotification('Deletion Multiple SPAF Error', 'Please select the unit categories you wish to delete.', 'danger');
            }
        });

         $(document).on('click','.update-spaf',function() {
            const spaf_id = $(this).data('spaf-id');

            $("#spaf_id").val(spaf_id);
            displaySPAFDetails();
        });

        // Cancel SPAF
        $(document).on('click', '.cancel-spaf', function(){
            const spaf_id = $(this).data('spaf-id');

            Swal.fire({
                title: 'Cancel SPAF',
                text: 'Are you sure you want to cancel this SPAF?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, cancel it',
                cancelButtonText: 'Cancel',
            }).then((result) => {
                if(result.isConfirmed){
                    $.ajax({
                        type: 'POST',
                        url: 'controller/lead-controller.php',
                        data: {
                            transaction: 'cancel spaf',
                            spaf_id: spaf_id
                        },
                        dataType: 'json',
                        success: function(response){
                            if(response.success){
                                showNotification(
                                    'Success',
                                    'The SPAF has been cancelled successfully.',
                                    'success'
                                );
                                reloadDatatable('#spaf-table');
                            } else {
                                showNotification(
                                    'Error',
                                    response.message || 'Unable to cancel SPAF.',
                                    'danger'
                                );
                            }
                        },
                        error: function(){
                            showNotification(
                                'Error',
                                'An error occurred while processing your request.',
                                'danger'
                            );
                        }
                    });
                }
            });
        });

        $(document).on('click', '.for-approval', function(){
            const spaf_id = $(this).data('spaf-id');

            Swal.fire({
                title: 'Tag for Approval',
                text: 'Are you sure you want to submit this SPAF for approval?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, tag for approval',
                cancelButtonText: 'Cancel',
            }).then((result) => {
                if(result.isConfirmed){
                    $.ajax({
                        type: 'POST',
                        url: 'controller/lead-controller.php',
                        data: {
                            transaction: 'for approval spaf',
                            spaf_id: spaf_id
                        },
                        dataType: 'json',
                        success: function(response){
                            if(response.success){
                                showNotification(
                                    'Success',
                                    'The SPAF has been tagged for approval.',
                                    'success'
                                );
                                reloadDatatable('#spaf-table');
                            } else {
                                showNotification(
                                    'Error',
                                    response.message || 'Unable to tag SPAF for approval.',
                                    'danger'
                                );
                            }
                        },
                        error: function(){
                            showNotification(
                                'Error',
                                'An error occurred while processing your request.',
                                'danger'
                            );
                        }
                    });
                }
            });
        });

        // Approve SPAF (Submit SPAF)
        $(document).on('click', '.approve-spaf', function(){
            const spaf_id = $(this).data('spaf-id');

            Swal.fire({
                title: 'Approve SPAF',
                text: 'Are you sure you want to approve this SPAF?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, approve',
                cancelButtonText: 'Cancel',
            }).then((result) => {
                if(result.isConfirmed){
                    $.ajax({
                        type: 'POST',
                        url: 'controller/lead-controller.php',
                        data: {
                            transaction: 'approve spaf',
                            spaf_id: spaf_id
                        },
                        dataType: 'json',
                        success: function(response){
                            if(response.success){
                                showNotification(
                                    'Success',
                                    'The SPAF has been approved successfully.',
                                    'success'
                                );
                                reloadDatatable('#spaf-table');
                            } else {
                                showNotification(
                                    'Error',
                                    response.message || 'Unable to approve SPAF.',
                                    'danger'
                                );
                            }
                        },
                        error: function(){
                            showNotification(
                                'Error',
                                'An error occurred while processing your request.',
                                'danger'
                            );
                        }
                    });
                }
            });
        });

        // Delete SPAF
        $(document).on('click', '.delete-spaf', function(){
            const spaf_id = $(this).data('spaf-id');

            Swal.fire({
                title: 'Delete SPAF',
                text: 'Are you sure you want to delete this SPAF? This action cannot be undone.',
                icon: 'error',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete',
                cancelButtonText: 'Cancel',
            }).then((result) => {
                if(result.isConfirmed){
                    $.ajax({
                        type: 'POST',
                        url: 'controller/lead-controller.php',
                        data: {
                            transaction: 'delete spaf',
                            spaf_id: spaf_id
                        },
                        dataType: 'json',
                        success: function(response){
                            if(response.success){
                                showNotification(
                                    'Success',
                                    'The SPAF has been deleted.',
                                    'success'
                                );
                                reloadDatatable('#spaf-table');
                            } else {
                                showNotification(
                                    'Error',
                                    response.message || 'Unable to delete SPAF.',
                                    'danger'
                                );
                            }
                        },
                        error: function(){
                            showNotification(
                                'Error',
                                'An error occurred while processing your request.',
                                'danger'
                            );
                        }
                    });
                }
            });
        });

        $(document).on('change', '#unit_price, #dealer_discount', function() {
            // Get values from the inputs, fallback to 0 if empty or invalid
            const unitPrice = parseFloat($('#unit_price').val()) || 0;
            const dealerDiscount = parseFloat($('#dealer_discount').val()) || 0;

            // Compute net price: unit price minus discount
            let netPrice = unitPrice - dealerDiscount;

            // Optional: Ensure net price doesn't drop below 0
            if (netPrice < 0) {
                netPrice = 0;
            }

            // Set the value to the net price input formatted to 2 decimal places
            $('#net_price').val(netPrice.toFixed(2));
        });
    });
})(jQuery);

function spafTable(datatable_name, buttons = false, show_all = false){
    const type = 'spaf table';

    let status_filter = [];
    var settings;

    $('.status-draft:checked').each(function () {
        status_filter.push($(this).val());
    });

    const filter_status = status_filter.join(', ');

    const column = [ 
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'SPAF_REFERENCE' },
        { 'data' : 'CUSTOMER' },
        { 'data' : 'PRODUCT' },
        { 'data' : 'UNIT_PRICE' },
        { 'data' : 'DEALER_DISCOUNT' },
        { 'data' : 'TOTAL' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '1%','bSortable': false, 'aTargets': 0 },
        { 'width': '15%','bSortable': false, 'aTargets': 7 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_lead_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {'type' : type, 'filter_status': filter_status},
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

function spafForm(){
    $('#spaf-form').validate({
        rules: {
            cif_id: {
                required: true
            },
            unit_price: {
                required: true
            },
        },
        messages: {
            cif_id: {
                required: 'Please choose the CIF'
            },
            unit_price: {
                required: 'Please enter the unit price'
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
            const transaction = 'save spaf';
        
            $.ajax({
                type: 'POST',
                url: 'controller/lead-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-spaf');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = 'Save SPAF Success';
                        const notificationDescription = 'The spaf has been saved successfully.';
                        
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
                    enableFormSubmitButton('submit-spaf', 'Save');
                     reloadDatatable('#spaf-table');
                    $('#spaf-modal').modal('hide');
                    resetModalForm('spaf-form');
                }
            });
        
            return false;
        }
    });
}

function displayDetails(transaction){
    switch (transaction) {
        case 'get spaf details':
            const spaf_id = $('#spaf-id').text();
            
            $.ajax({
                url: 'controller/lead-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    spaf_id : spaf_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetForm('spaf-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#spaf_id').val(spaf_id);
                        $('#spaf_name').val(response.spafName);
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get SPAF Details Error', response.message, 'danger');
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

function displaySPAFDetails(){
    let transaction = 'get spaf details';
    var spaf_id = $('#spaf_id').val();
    
    $.ajax({
        url: 'controller/lead-controller.php',
        method: 'POST',
        dataType: 'json',
        data: {
            spaf_id : spaf_id, 
            transaction : transaction
        },
        success: function(response) {
            if (response.success) {
                $('#unit_price').val(response.unit_price);
                $('#dealer_discount').val(response.dealer_discount);
                $('#net_price').val(response.net_price);
                $('#remarks').val(response.remarks);
                $('#spaf_supplier_id').val(response.supplier_id);

                checkOptionExist('#cif_id', response.cif_id, '');
            } 
            else {
                if(response.isInactive){
                    window.location = 'logout.php?logout';
                }
                else{
                    showNotification('Get SPAF Details Error', response.message, 'danger');
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