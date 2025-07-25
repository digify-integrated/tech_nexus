(function($) {
    'use strict';

    $(function() {
        if($('#parts-incoming-table').length){
            partsIncomingTable('#parts-incoming-table');
        }

        if($('#parts-incoming-form').length){
            partsIncomingForm();
        }

        if($('#parts-incoming-id').length){
            displayDetails('get parts incoming details');
            displayDetails('get parts incoming cart total');
        }

        if($('#parts-item-table').length){
            partItemTable('#parts-item-table');
        }

        if($('#parts-incoming-document-table').length){
            partIncomingDocumentTable('#parts-incoming-document-table');
        }
        
        if($('#add-part-form').length){
            addPartsForm();
        }

        if($('#cancel-incoming-form').length){
            cancelIncomingForm();
        }

        if($('#draft-incoming-form').length){
            draftIncomingForm();
        }

        if($('#release-incoming-form').length){
            releaseIncomingForm();
        }

        if($('#approve-incoming-form').length){
            approveIncomingForm();
        }
        
        if($('#add-part-document-form').length){
            partsDocumentForm();
        }

        if($('#part-item-form').length){
            partItemForm();
        }

        if($('#receive-item-form').length){
            receiveItemForm();
        }

        if($('#cancel-receive-item-form').length){
            cancelReceiveItemForm();
        }

        $(document).on('click','#add-part',function() {
            if($('#add-part-table').length){
                addPartTable('#add-part-table');
            }
        });

        $(document).on('click','#apply-filter',function() {
            partsIncomingTable('#parts-incoming-table');
        });

        $(document).on('click','.update-part-cart',function() {
            const parts_incoming_cart_id = $(this).data('parts-incoming-cart-id');

            $('#part_incoming_cart_id').val(parts_incoming_cart_id);
            displayDetails('get parts incoming cart details');

            var view_cost = $('#view-cost').val();
            var update_cost = $('#update-cost').val();

            if(view_cost > 0 && update_cost > 0){
                $('#cost-row').removeClass('d-none');
            }
            else{
                $('#cost-row').addClass('d-none');
            }
        });

        $(document).on('click','.receive-quantity',function() {
            const parts_incoming_cart_id = $(this).data('parts-incoming-cart-id');

            sessionStorage.setItem('parts_incoming_cart_id', parts_incoming_cart_id);
            displayDetails('get receive parts incoming cart details');
        });

        $(document).on('click','.cancel-receive-quantity',function() {
            const parts_incoming_cart_id = $(this).data('parts-incoming-cart-id');

            sessionStorage.setItem('parts_incoming_cart_id', parts_incoming_cart_id);
            displayDetails('get receive parts incoming cart details');
        });

        $(document).on('click','#on-process',function() {
            var parts_incoming_id = $('#parts-incoming-id').text();
            const transaction = 'tag incoming as on-process';
    
            Swal.fire({
                title: 'Confirm Incoming Approval',
                text: 'Are you sure you want to approve this incoming?',
                icon: 'warning',
                showCancelButton: !0,
                confirmButtonText: 'Approve',
                cancelButtonText: 'Cancel',
                confirmButtonClass: 'btn btn-success mt-2',
                cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                buttonsStyling: !1
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        type: 'POST',
                        url: 'controller/parts-incoming-controller.php',
                        dataType: 'json',
                        data: {
                            parts_incoming_id : parts_incoming_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Incoming Approve Success', 'The incoming has been tagged as approve successfully.', 'success');
                                window.location.reload();
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.noItem) {
                                    showNotification('Incoming Approve Error', 'No parts added. Cannot be approved.', 'danger');
                                }
                                else {
                                    showNotification('Incoming Approve Error', response.message, 'danger');
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

        $(document).on('click','#for-approval',function() {
            var parts_incoming_id = $('#parts-incoming-id').text();
            const transaction = 'tag incoming as for approval';
    
            Swal.fire({
                title: 'Confirm Incoming For Approval',
                text: 'Are you sure you want to tag this incoming as for approval?',
                icon: 'warning',
                showCancelButton: !0,
                confirmButtonText: 'For Approval',
                cancelButtonText: 'Cancel',
                confirmButtonClass: 'btn btn-success mt-2',
                cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                buttonsStyling: !1
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        type: 'POST',
                        url: 'controller/parts-incoming-controller.php',
                        dataType: 'json',
                        data: {
                            parts_incoming_id : parts_incoming_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Incoming For Approval Success', 'The incoming has been tagged for approval successfully.', 'success');
                                window.location.reload();
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.noItem) {
                                    showNotification('Incoming For Approval Error', 'No parts added. Cannot be for approval.', 'danger');
                                }
                                else {
                                    showNotification('Incoming For Approval Error', response.message, 'danger');
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

        $(document).on('click','.delete-part-cart',function() {
            const parts_incoming_cart_id = $(this).data('parts-incoming-cart-id');
            var parts_incoming_id = $('#parts-incoming-id').text();
            const transaction = 'delete part item';
    
            Swal.fire({
                title: 'Confirm Part Item Deletion',
                text: 'Are you sure you want to delete this part item?',
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
                        url: 'controller/parts-incoming-controller.php',
                        dataType: 'json',
                        data: {
                            parts_incoming_cart_id : parts_incoming_cart_id, 
                            parts_incoming_id : parts_incoming_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete Part Item Success', 'The part item has been deleted successfully.', 'success');
                                reloadDatatable('#parts-item-table');
                                displayDetails('get parts incoming cart total');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Delete Part Item Error', 'The part item does not exists.', 'danger');
                                    reloadDatatable('#parts-item-table');
                                }
                                else {
                                    showNotification('Delete Part Item Error', response.message, 'danger');
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

        $(document).on('click','.cancel-item-quantity',function() {
            const parts_incoming_cart_id = $(this).data('parts-incoming-cart-id');
            var parts_incoming_id = $('#parts-incoming-id').text();
            const transaction = 'cancel part item';
    
            Swal.fire({
                title: 'Confirm Part Item Cancellation',
                text: 'Are you sure you want to cancel this part item?',
                icon: 'warning',
                showCancelButton: !0,
                confirmButtonText: 'Cancel Item',
                cancelButtonText: 'Cancel',
                confirmButtonClass: 'btn btn-danger mt-2',
                cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                buttonsStyling: !1
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        type: 'POST',
                        url: 'controller/parts-incoming-controller.php',
                        dataType: 'json',
                        data: {
                            parts_incoming_cart_id : parts_incoming_cart_id, 
                            parts_incoming_id : parts_incoming_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Cancel Part Item Success', 'The part item has been cancelled successfully.', 'success');
                                reloadDatatable('#parts-item-table');
                                displayDetails('get parts incoming cart total');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Cancel Part Item Error', 'The part item does not exists.', 'danger');
                                    reloadDatatable('#parts-item-table');
                                }
                                else {
                                    showNotification('Cancel Part Item Error', response.message, 'danger');
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
            if($('#page-company').val() == '2'){
                discardCreate('netruck-parts-incoming.php');
            }
            else{
                discardCreate('parts-incoming.php');
            }
        });
    });
})(jQuery);

function partsIncomingForm(){
    $('#parts-incoming-form').validate({
        rules: {
            reference_number: {
                required: {
                    depends: function () {
                        return $('#page-company').val() === '3';
                    }
                }
            },
            purchase_date: {
                required: true
            },
            supplier_id: {
                required: true
            },
            product_id: {
                required: true
            },
        },
        messages: {
            reference_number: {
                required: 'Please enter the reference number'
            },
            purchase_date: {
                required: 'Please choose the purchase date'
            },
            supplier_id: {
                required: 'Please choose the supplier'
            },
            product_id: {
                required: 'Please choose the product'
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
            const parts_incoming_id = $('#parts-incoming-id').text();
            const company_id = $('#page-company').val();
            const transaction = 'save parts incoming';
        
            $.ajax({
                type: 'POST',
                url: 'controller/parts-incoming-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&parts_incoming_id=' + parts_incoming_id + '&company_id=' + company_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-data');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert Incoming Parts Success' : 'Update Incoming Parts Success';
                        const notificationDescription = response.insertRecord ? 'The incoming parts has been inserted successfully.' : 'The incoming parts has been updated successfully.';
                        
                        setNotification(notificationMessage, notificationDescription, 'success');
                        if(company_id == '2'){
                            window.location = 'netruck-parts-incoming.php?id=' + response.partsIncomingID;
                        }
                        else{
                            window.location = 'parts-incoming.php?id=' + response.partsIncomingID;
                        }
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

function partsIncomingTable(datatable_name, buttons = false, show_all = false){
    const type = 'parts incoming table';
    var filter_transaction_date_start_date = $('#filter_transaction_date_start_date').val();
    var filter_transaction_date_end_date = $('#filter_transaction_date_end_date').val();
    var filter_released_date_start_date = $('#filter_released_date_start_date').val();
    var filter_released_date_end_date = $('#filter_released_date_end_date').val();
    var filter_purchased_date_start_date = $('#filter_purchased_date_start_date').val();
    var filter_purchased_date_end_date = $('#filter_purchased_date_end_date').val();
    var view_cost = $('#view-cost').val();
    var company = $('#page-company').val();

    var incoming_status_filter = [];

    $('.incoming-status-checkbox:checked').each(function() {
        incoming_status_filter.push($(this).val());
    });

    var filter_incoming_status = incoming_status_filter.join(', ');

    var settings;

    if(view_cost > 0){
        var column = [
            { 'data' : 'TRANSACTION_ID' },
            { 'data' : 'LINES' },
            { 'data' : 'QUANTITY' },
            { 'data' : 'RECEIVED' },
            { 'data' : 'REMAINING' },
            { 'data' : 'COST' },
            { 'data' : 'COMPLETION_DATE' },
            { 'data' : 'PURCHASE_DATE' },
            { 'data' : 'TRANSACTION_DATE' },
            { 'data' : 'STATUS' },
            { 'data' : 'ACTION' }
        ];

        var column_definition = [
            { 'width': 'auto', 'aTargets': 0 },
            { 'width': 'auto', 'aTargets': 1 },
            { 'width': 'auto', 'aTargets': 2 },
            { 'width': 'auto', 'aTargets': 3 },
            { 'width': 'auto', 'aTargets': 4 },
            { 'width': 'auto', 'aTargets': 5 },
            { 'width': 'auto', 'type': 'date', 'aTargets': 6 },
            { 'width': 'auto', 'type': 'date', 'aTargets': 7 },
            { 'width': 'auto', 'type': 'date', 'aTargets': 8 },
            { 'width': 'auto', 'aTargets': 9 },
            { 'width': '15%','bSortable': false, 'aTargets': 10 }
        ];
    }
    else{
        var column = [
            { 'data' : 'TRANSACTION_ID' },
            { 'data' : 'LINES' },
            { 'data' : 'QUANTITY' },
            { 'data' : 'RECEIVED' },
            { 'data' : 'REMAINING' },
            { 'data' : 'COMPLETION_DATE' },
            { 'data' : 'PURCHASE_DATE' },
            { 'data' : 'TRANSACTION_DATE' },
            { 'data' : 'STATUS' },
            { 'data' : 'ACTION' }
        ];

        var column_definition = [
            { 'width': 'auto', 'aTargets': 0 },
            { 'width': 'auto', 'aTargets': 1 },
            { 'width': 'auto', 'aTargets': 2 },
            { 'width': 'auto', 'aTargets': 3 },
            { 'width': 'auto', 'aTargets': 4 },
            { 'width': 'auto', 'type': 'date', 'aTargets': 5 },
            { 'width': 'auto', 'type': 'date', 'aTargets': 6 },
            { 'width': 'auto', 'type': 'date', 'aTargets': 7 },
            { 'width': 'auto', 'aTargets': 8 },
            { 'width': '15%','bSortable': false, 'aTargets': 9 }
        ];
    }

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_parts_incoming_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {'type' : type, 
                'company' : company, 
                'filter_transaction_date_start_date' : filter_transaction_date_start_date, 
                'filter_transaction_date_end_date' : filter_transaction_date_end_date,
                'filter_released_date_start_date' : filter_released_date_start_date,
                'filter_released_date_end_date' : filter_released_date_end_date,
                'filter_purchased_date_start_date' : filter_purchased_date_start_date,
                'filter_purchased_date_end_date' : filter_purchased_date_end_date,
                'filter_incoming_status' : filter_incoming_status
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
        'order': [[ 0, 'desc' ]],
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

function addPartTable(datatable_name, buttons = false, show_all = false){
    var parts_incoming_id = $('#parts-incoming-id').text();
    const company_id = $('#page-company').val();
    const type = 'add part table';
    var settings;

    const column = [ 
        { 'data' : 'PART' },
        { 'data' : 'PRICE' },
        { 'data' : 'STOCK' },
        { 'data' : 'ASSIGN' }
    ];

    const column_definition = [
        { 'width': '60%', 'aTargets': 0 },
        { 'width': 'auto', 'aTargets': 1 },
        { 'width': 'auto', 'aTargets': 2 },
        { 'width': '10%', 'bSortable': false, 'aTargets': 3 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_parts_incoming_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {'type' : type, 'parts_incoming_id' : parts_incoming_id, 'company_id' : company_id},
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

function partItemTable(datatable_name, buttons = false, show_all = false){
    var parts_incoming_id = $('#parts-incoming-id').text();
    var view_cost = $('#view-cost').val();
    const type = 'part item table';
    var settings;

    if(view_cost > 0){
        var column = [ 
            { 'data' : 'ACTION' },
            { 'data' : 'PART' },
            { 'data' : 'QUANTITY' },
            { 'data' : 'RECEIVED_QUANTITY' },
            { 'data' : 'REMAINING_QUANTITY' },
            { 'data' : 'COST' },
            { 'data' : 'TOTAL_COST' },
            { 'data' : 'AVAILABLE_STOCK' },
            { 'data' : 'REMARKS' }
        ];

        var column_definition = [
            { 'width': 'auto', 'bSortable': false, 'aTargets': 0 },
            { 'width': 'auto', 'aTargets': 1 },
            { 'width': 'auto', 'aTargets': 2 },
            { 'width': 'auto', 'aTargets': 3 },
            { 'width': 'auto', 'aTargets': 4 },
            { 'width': 'auto', 'aTargets': 5 },
            { 'width': 'auto', 'aTargets': 6 },
            { 'width': 'auto', 'aTargets': 7 },
            { 'width': 'auto', 'aTargets': 8 },
        ];
    }
    else{
        var column = [ 
            { 'data' : 'ACTION' },
            { 'data' : 'PART' },
            { 'data' : 'QUANTITY' },
            { 'data' : 'RECEIVED_QUANTITY' },
            { 'data' : 'REMAINING_QUANTITY' },
            { 'data' : 'AVAILABLE_STOCK' },
            { 'data' : 'REMARKS' }
        ];

        var column_definition = [
            { 'width': 'auto', 'bSortable': false, 'aTargets': 0 },
            { 'width': 'auto', 'aTargets': 1 },
            { 'width': 'auto', 'aTargets': 2 },
            { 'width': 'auto', 'aTargets': 3 },
            { 'width': 'auto', 'aTargets': 4 },
            { 'width': 'auto', 'aTargets': 5 },
            { 'width': 'auto', 'aTargets': 6 },
        ];
    }

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_parts_incoming_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {'type' : type, 'parts_incoming_id' : parts_incoming_id},
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

function partIncomingDocumentTable(datatable_name, buttons = false, show_all = false){
    var parts_incoming_id = $('#parts-incoming-id').text();
    const type = 'part incoming document table';
    var settings;

    const column = [ 
        { 'data' : 'DOCUMENT' },
        { 'data' : 'UPLOAD_DATE' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': 'auto', 'aTargets': 0 },
        { 'width': 'auto', 'aTargets': 1 },
        { 'width': '5%', 'bSortable': false, 'aTargets': 2 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_parts_incoming_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {'type' : type, 'parts_incoming_id' : parts_incoming_id},
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

function addPartsForm(){
    $('#add-part-form').validate({
        submitHandler: function(form) {
          var parts_incoming_id = $('#parts-incoming-id').text();
            const transaction = 'add parts incoming item';

            var part_id = [];

            $('.assign-part').each(function(){
                if ($(this).is(':checked')){  
                    part_id.push(this.value);  
                }
            });

            $.ajax({
                type: 'POST',
                url: 'controller/parts-incoming-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&parts_incoming_id=' + parts_incoming_id + '&part_id=' + part_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-add-part');
                },
                success: function(response) {
                    if (response.success) {
                        showNotification('Add Parts Success', 'The parts has been added successfully.', 'success');
                        
                        displayDetails('get parts incoming cart total');
                    }
                    else {
                        if (response.isInactive) {
                            setNotification('User Inactive', response.message, 'danger');
                            window.location = 'logout.php?logout';
                        }
                        else {
                            showNotification('Incoming Error', response.message, 'danger');
                        }
                    }
                },
                error: function(xhr, status, error) {
                    var fullErrorMessage = 'XHR status: ' + status + ', Error: ' + error;
                    fullErrorMessage += ', Response: ' + xhr.responseText;
                    showErrorDialog(fullErrorMessage);
                },
                complete: function() {
                    enableFormSubmitButton('submit-add-part', 'Submit');
                    $('#add-part-offcanvas').offcanvas('hide');
                    reloadDatatable('#parts-item-table');
                }
            });
            return false;
        }
    });
}

function partsDocumentForm(){
    $('#add-part-document-form').validate({
        rules: {
            document_name: {
                required: true
            },
            incoming_document: {
                required: true
            },
        },
        messages: {
            document_name: {
                required: 'Please enter the document name'
            },
            incoming_document: {
                required: 'Please choose the document'
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
            var parts_incoming_id = $('#parts-incoming-id').text();
            const transaction = 'add parts incoming document';
            var formData = new FormData(form);
            formData.append('transaction', transaction);
            formData.append('parts_incoming_id', parts_incoming_id);
        
            $.ajax({
                type: 'POST',
                url: 'controller/parts-incoming-controller.php',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-add-part-document');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = 'Insert Document Success';
                        const notificationDescription = 'The document has been inserted successfully.';
                        
                        showNotification(notificationMessage, notificationDescription, 'success');
                    }
                    else {
                        if (response.isInactive) {
                            setNotification('User Inactive', response.message, 'danger');
                            window.location = 'logout.php?logout';
                        }
                        else {
                            showNotification('Incoming Error', response.message, 'danger');
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
                    enableFormSubmitButton('submit-add-part-document', 'Submit');
                    reloadDatatable('#parts-incoming-document-table');
                    $('#add-part-document-offcanvas').offcanvas('hide');
                    resetModalForm('add-part-document-form');
                }
            });
        
            return false;
        }
    });
}

function cancelIncomingForm(){
    $('#cancel-incoming-form').validate({
        rules: {
            cancellation_reason: {
                required: true
            },
        },
        messages: {
            cancellation_reason: {
                required: 'Please enter the cancellation reason'
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
            var parts_incoming_id = $('#parts-incoming-id').text();
            const transaction = 'tag incoming as cancelled';
        
            $.ajax({
                type: 'POST',
                url: 'controller/parts-incoming-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&parts_incoming_id=' + parts_incoming_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-cancel-incoming');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = 'Cancel Incoming Success';
                        const notificationDescription = 'The incoming has been tag as cancelled successfully.';
                        
                        setNotification(notificationMessage, notificationDescription, 'success');
                        window.location.reload();
                    }
                    else {
                        if (response.isInactive) {
                            setNotification('User Inactive', response.message, 'danger');
                            window.location = 'logout.php?logout';
                        }
                        else if (response.cartQuantity) {
                            showNotification('Cancel Incoming Error', 'One of the parts added does not have enough quantity. Kindly check the added parts.', 'danger');
                        }
                        else {
                            showNotification('Incoming Error', response.message, 'danger');
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
                    enableFormSubmitButton('submit-cancel-incoming', 'Submit');
                }
            });
        
            return false;
        }
    });
}

function draftIncomingForm(){
    $('#draft-incoming-form').validate({
        rules: {
            set_to_draft_reason: {
                required: true
            },
        },
        messages: {
            set_to_draft_reason: {
                required: 'Please enter the set to draft reason'
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
            var parts_incoming_id = $('#parts-incoming-id').text();
            const transaction = 'tag incoming as draft';
        
            $.ajax({
                type: 'POST',
                url: 'controller/parts-incoming-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&parts_incoming_id=' + parts_incoming_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-draft-incoming');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = 'Set To Draft Incoming Success';
                        const notificationDescription = 'The incoming has been set to draft successfully.';
                        
                        setNotification(notificationMessage, notificationDescription, 'success');
                        window.location.reload();
                    }
                    else {
                        if (response.isInactive) {
                            setNotification('User Inactive', response.message, 'danger');
                            window.location = 'logout.php?logout';
                        }
                        else {
                            showNotification('Incoming Error', response.message, 'danger');
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
                    enableFormSubmitButton('submit-draft-incoming', 'Submit');
                }
            });
        
            return false;
        }
    });
}

function releaseIncomingForm(){
    $('#release-incoming-form').validate({
        rules: {
            invoice_number: {
                required: true
            },
            delivery_date: {
                required: true
            },
        },
        messages: {
            invoice_number: {
                required: 'Please enter the invoice number'
            },
            delivery_date: {
                required: 'Please enter the delivery date'
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
            var parts_incoming_id = $('#parts-incoming-id').text();
            const transaction = 'tag incoming as released';
        
            $.ajax({
                type: 'POST',
                url: 'controller/parts-incoming-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&parts_incoming_id=' + parts_incoming_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-cancel-incoming');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = 'Complete Incoming Success';
                        const notificationDescription = 'The incoming has been tag as complete successfully.';
                        
                        setNotification(notificationMessage, notificationDescription, 'success');
                        window.location.reload();
                    }
                    else {
                        if (response.isInactive) {
                            setNotification('User Inactive', response.message, 'danger');
                            window.location = 'logout.php?logout';
                        }
                        else if (response.remaining) {
                            showNotification('Incoming Complete Error', 'Some items have not been fully received yet. Please complete the receipt before proceeding.', 'danger');
                        }
                        else {
                            showNotification('Incoming Error', response.message, 'danger');
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
                    enableFormSubmitButton('submit-cancel-incoming', 'Submit');
                }
            });
        
            return false;
        }
    });
}

function partItemForm(){
  $('#part-item-form').validate({
        rules: {
            quantity: {
                required: true,
            },
        },
        messages: {
            quantity: {
                required: 'Please enter the quantity',
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
            const transaction = 'save part item';
        
            $.ajax({
                type: 'POST',
                url: 'controller/parts-incoming-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-data');
                },
                success: function (response) {
                    if (response.success) {
                        showNotification('Update Part Item Success', 'The part item has been updated successfully', 'success');
                        reloadDatatable('#parts-item-table');
                        $('#part-cart-offcanvas').offcanvas('hide');
                        displayDetails('get parts incoming cart total');
                    }
                    else {
                        if (response.isInactive) {
                            setNotification('User Inactive', response.message, 'danger');
                            window.location = 'logout.php?logout';
                        }
                        else if (response.quantity) {
                            showNotification('Update Part Item', 'Quantity cannot exceed available stock', 'danger');
                        }
                        else {
                            showNotification('Incoming Error', response.message, 'danger');
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

$.validator.addMethod("notGreaterThanRemaining", function(value, element, params) {
    var remaining = parseFloat($(params).val());
    var received = parseFloat(value);
    return received <= remaining;
}, "Received quantity cannot be greater than remaining quantity.");

function receiveItemForm(){
  $('#receive-item-form').validate({
        rules: {
            received_quantity: {
                required: true,
                notGreaterThanRemaining: "#remaining_quantity"
            },
        },
        messages: {
            received_quantity: {
                required: 'Please enter the received quantity',
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
            const transaction = 'save received part item';
             let part_incoming_cart_id = sessionStorage.getItem('parts_incoming_cart_id');
        
            $.ajax({
                type: 'POST',
                url: 'controller/parts-incoming-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&part_incoming_cart_id=' + part_incoming_cart_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-data');
                },
                success: function (response) {
                    if (response.success) {
                        showNotification('Update Received Item Success', 'The received item has been updated successfully', 'success');
                        reloadDatatable('#parts-item-table');
                        $('#receive-item-offcanvas').offcanvas('hide');
                        displayDetails('get parts incoming cart total');
                    }
                    else {
                        if (response.isInactive) {
                            setNotification('User Inactive', response.message, 'danger');
                            window.location = 'logout.php?logout';
                        }
                        else if (response.quantity) {
                            showNotification('Update Received Item', 'Quantity cannot exceed available stock', 'danger');
                        }
                        else {
                            showNotification('Incoming Error', response.message, 'danger');
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

function cancelReceiveItemForm(){
  $('#cancel-receive-item-form').validate({
        rules: {
            cancel_received_quantity: {
                required: true,
                notGreaterThanRemaining: "#cancel_remaining_quantity"
            },
        },
        messages: {
            cancel_received_quantity: {
                required: 'Please enter the received quantity',
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
            const transaction = 'save cancel received part item';
             let part_incoming_cart_id = sessionStorage.getItem('parts_incoming_cart_id');
        
            $.ajax({
                type: 'POST',
                url: 'controller/parts-incoming-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&part_incoming_cart_id=' + part_incoming_cart_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-cancel-receive');
                },
                success: function (response) {
                    if (response.success) {
                        showNotification('Cancel Remaining Quantity Success', 'The remaining quantity item has been updated successfully', 'success');
                        reloadDatatable('#parts-item-table');
                        $('#cancel-receive-item-offcanvas').offcanvas('hide');
                        displayDetails('get parts incoming cart total');
                    }
                    else {
                        if (response.isInactive) {
                            setNotification('User Inactive', response.message, 'danger');
                            window.location = 'logout.php?logout';
                        }
                        else if (response.quantity) {
                            showNotification('Cancel Remaining Quantity', 'Cancel quantity cannot exceed remaining quantity to receive', 'danger');
                        }
                        else {
                            showNotification('Incoming Error', response.message, 'danger');
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
                    enableFormSubmitButton('submit-cancel-receive', 'Submit');
                }
            });
        
            return false;
        }
    });
}

function displayDetails(transaction){
    switch (transaction) {
        case 'get parts incoming details':
             var parts_incoming_id = $('#parts-incoming-id').text();
            
            $.ajax({
                url: 'controller/parts-incoming-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    parts_incoming_id : parts_incoming_id, 
                    transaction : transaction
                },
                success: function(response) {
                    if (response.success) {
                        $('#reference_number').val(response.reference_number);
                        $('#purchase_date').val(response.purchase_date);
                        $('#request_by').val(response.request_by);

                        checkOptionExist('#supplier_id', response.supplier_id, '');
                        checkOptionExist('#product_id', response.product_id, '');
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Part Details Error', response.message, 'danger');
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
        case 'get parts incoming cart details':
            let part_incoming_cart_id = $('#part_incoming_cart_id').val();
            
            $.ajax({
                url: 'controller/parts-incoming-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    part_incoming_cart_id : part_incoming_cart_id, 
                    transaction : transaction
                },
                success: function(response) {
                    if (response.success) {
                        $('#quantity').val(response.quantity);
                        $('#cost').val(response.cost);
                        $('#part_id').val(response.part_id);
                        $('#remarks').val(response.remarks);
                        
                        checkOptionExist('#discount_type', response.discount_type, '');

                        displayDetails('get parts details');
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Part Details Error', response.message, 'danger');
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
        case 'get receive parts incoming cart details':
            let parts_incoming_cart_id = sessionStorage.getItem('parts_incoming_cart_id');
            
            $.ajax({
                url: 'controller/parts-incoming-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    part_incoming_cart_id : parts_incoming_cart_id, 
                    transaction : transaction
                },
                success: function(response) {
                    if (response.success) {
                        $('#remaining_quantity').val(response.remaining_quantity);
                        $('#cancel_remaining_quantity').val(response.remaining_quantity);
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Part Details Error', response.message, 'danger');
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
        case 'get parts details':
            const parts_id = $('#part_id').val();
            
            $.ajax({
                url: 'controller/parts-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    parts_id : parts_id, 
                    transaction : transaction
                },
                success: function(response) {
                    if (response.success) {
                        $('#part_description').val(response.description);
                        $('#available_stock').val(response.quantity);
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Part Details Error', response.message, 'danger');
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

        case 'get parts incoming cart total':
             var parts_incoming_id = $('#parts-incoming-id').text();
            
            $.ajax({
                url: 'controller/parts-incoming-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    parts_incoming_id : parts_incoming_id, 
                    transaction : transaction
                },
                success: function(response) {
                    if (response.success) {
                        $('#total-cost-summary').text(response.cost);
                        $('#total-item-summary').text(response.lines);
                        $('#total-quantity-summary').text(response.quantity);
                        $('#total-received-quantity-summary').text(response.received);
                        $('#total-remaining-quantity-summary').text(response.remaining);

                        if(response.total_received > 0){
                            $('#cancelled').addClass('d-none');
                            $('#draft').addClass('d-none');
                        }
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Part Details Error', response.message, 'danger');
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