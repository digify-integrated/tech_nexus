(function($) {
    'use strict';

    $(function() {
        if($('#purchase-request-table').length){
            purchaseRequestTable('#purchase-request-table');
        }

        if($('#purchase-request-item-table').length){
            purchaseRequestItemTable('#purchase-request-item-table');
        }

        $(document).on('click','#apply-filter',function() {
            purchaseRequestTable('#purchase-request-table');
        });
        
        if($('#purchase-request-id').length){
            displayDetails('get purchase request details');
        }

        if($('#purchase-request-form').length){
            purchaseRequestForm();
        }

        if($('#add-item-form').length){
            addItemForm();
        }

        if($('#cancel-purchase-request-form').length){
            cancelPurchaseRequestForm();
        }

        if($('#draft-purchase-request-form').length){
            draftPurchaseRequestForm();
        }

        if($('#approve-purchase-request-form').length){
            approvePurchaseRequestForm();
        }

        $(document).on('click','#for-approval',function() {
            var purchase_request_id = $('#purchase-request-id').text();
            const transaction = 'tag request as for approval';
    
            Swal.fire({
                title: 'Confirm Request For Approval',
                text: 'Are you sure you want to tag this request as for validation?',
                icon: 'warning',
                showCancelButton: !0,
                confirmButtonText: 'For Approval',
                cancelButtonText: 'Cancel',
                confirmButtonClass: 'btn btn-info mt-2',
                cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                buttonsStyling: !1
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        type: 'POST',
                        url: 'controller/purchase-request-controller.php',
                        dataType: 'json',
                        data: {
                            purchase_request_id : purchase_request_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Request For Approval Success', 'The request has been tagged as for approval successfully.', 'success');
                                window.location.reload();
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.noItem) {
                                    showNotification('Request For Approval Error', 'No item added. Cannot be processed.', 'danger');
                                }
                                else {
                                    showNotification('Request For Approval Error', response.message, 'danger');
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

        $(document).on('click','#release',function() {
            var purchase_request_id = $('#purchase-request-id').text();
            const transaction = 'tag transaction as released';
    
            Swal.fire({
                title: 'Confirm Transaction Release',
                text: 'Are you sure you want to tag this transaction as released?',
                icon: 'warning',
                showCancelButton: !0,
                confirmButtonText: 'Release',
                cancelButtonText: 'Cancel',
                confirmButtonClass: 'btn btn-success mt-2',
                cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                buttonsStyling: !1
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        type: 'POST',
                        url: 'controller/purchase-request-controller.php',
                        dataType: 'json',
                        data: {
                            purchase_request_id : purchase_request_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Transaction Released Success', 'The transaction has been tagged as released successfully.', 'success');
                                window.location.reload();
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.partQuantityExceed) {
                                    showNotification('Transaction Released Error', 'One of the parts added does not have enough quantity. Kindly check the added parts.', 'danger');
                                }
                                else if (response.cartQuantity) {
                                    showNotification('Transaction Released Error', 'One of the parts added does not have enough quantity. Kindly check the added parts.', 'danger');
                                }
                                else {
                                    showNotification('Transaction Released Error', response.message, 'danger');
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

        $(document).on('click','#checked',function() {
            var purchase_request_id = $('#purchase-request-id').text();
            const transaction = 'tag transaction as checked';
    
            Swal.fire({
                title: 'Confirm Transaction Checked',
                text: 'Are you sure you want to tag this transaction as checked?',
                icon: 'warning',
                showCancelButton: !0,
                confirmButtonText: 'Check',
                cancelButtonText: 'Cancel',
                confirmButtonClass: 'btn btn-success mt-2',
                cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                buttonsStyling: !1
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        type: 'POST',
                        url: 'controller/purchase-request-controller.php',
                        dataType: 'json',
                        data: {
                            purchase_request_id : purchase_request_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Transaction Checked Success', 'The transaction has been tagged as checked successfully.', 'success');
                                window.location.reload();
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else {
                                    showNotification('Transaction Checked Error', response.message, 'danger');
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
            const purchase_request_cart_id = $(this).data('purchase-request-cart-id');
            var purchase_request_id = $('#purchase-request-id').text();
            const transaction = 'delete item';
    
            Swal.fire({
                title: 'Confirm Item Deletion',
                text: 'Are you sure you want to delete this item?',
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
                        url: 'controller/purchase-request-controller.php',
                        dataType: 'json',
                        data: {
                            purchase_request_cart_id : purchase_request_cart_id, 
                            purchase_request_id : purchase_request_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete Item Success', 'The item has been deleted successfully.', 'success');
                                reloadDatatable('#purchase-request-item-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Delete Item Error', 'The item does not exists.', 'danger');
                                    reloadDatatable('#purchase-request-item-table');
                                }
                                else {
                                    showNotification('Delete Item Error', response.message, 'danger');
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

         $(document).on('click','.update-part-cart',function() {
            const purchase_request_cart_id = $(this).data('purchase-request-cart-id');

            $('#purchase_request_cart_id').val(purchase_request_cart_id);
            displayDetails('get purchase request cart details');
        });

        $(document).on('change', '#purchase_request_type', function () {
            if ($(this).val() == 'Supplies') {
                $('#supply_coverage').removeClass('d-none');
            } else {
                $('#supply_coverage').addClass('d-none');
                checkOptionExist('#coverage_period', '', '');
                checkOptionExist('#month_coverage', '', '');
            }
        });


        $(document).on('click','#discard-create',function() {
            discardCreate('purchase-request.php');
        });

    });
})(jQuery);

function purchaseRequestTable(datatable_name, buttons = false, show_all = false){
    const type = 'purchase request table';
    var filter_transaction_date_start_date = $('#filter_transaction_date_start_date').val();
    var filter_transaction_date_end_date = $('#filter_transaction_date_end_date').val();
    var filter_approval_date_start_date = $('#filter_approval_date_start_date').val();
    var filter_approval_date_end_date = $('#filter_approval_date_end_date').val();

    var request_status_filter = [];

    $('.request-status-checkbox:checked').each(function() {
        request_status_filter.push($(this).val());
    });

    var filter_request_status = request_status_filter.join(', ');

    var settings;

    const column = [
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'REFERENCE_NO' },
        { 'data' : 'PURCHASE_REQUEST_TYPE' },
        { 'data' : 'COMPANY' },
        { 'data' : 'STATUS' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '1%','bSortable': false, 'aTargets': 0 },
        { 'width': 'auto', 'aTargets': 1 },
        { 'width': 'auto', 'aTargets': 2 },
        { 'width': 'auto', 'aTargets': 3 },
        { 'width': 'auto', 'aTargets': 4 },
        { 'width': '15%','bSortable': false, 'aTargets': 5 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_purchase_request_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {'type' : type, 
                'filter_transaction_date_start_date' : filter_transaction_date_start_date, 
                'filter_transaction_date_end_date' : filter_transaction_date_end_date,
                'filter_approval_date_start_date' : filter_approval_date_start_date,
                'filter_approval_date_end_date' : filter_approval_date_end_date,
                'filter_request_status' : filter_request_status
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
        'order': [[ 1, 'desc' ]],
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

function purchaseRequestItemTable(datatable_name, buttons = false, show_all = false){
    const purchase_request_id = $('#purchase-request-id').text();
    const type = 'purchase request item table';
    var settings;

    const column = [ 
        { 'data' : 'ACTION' },
        { 'data' : 'DESCRIPTION' },
        { 'data' : 'QUANTITY' },
        { 'data' : 'REMARKS' }
    ];

    const column_definition = [
        { 'width': '5%', 'bSortable': false, 'aTargets': 0 },
        { 'width': 'auto', 'aTargets': 1 },
        { 'width': '15%', 'aTargets': 2 },
        { 'width': '25%', 'aTargets': 3 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_purchase_request_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {'type' : type, 'purchase_request_id' : purchase_request_id},
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

function purchaseRequestForm(){
    $('#purchase-request-form').validate({
        rules: {
            purchase_request_type: {
                required: true
            },
            company_id: {
                required: true
            },
            month_coverage: {
                required: function () {
                    return $('#purchase_request_type').val() === 'Supplies';
                }
            },
            coverage_period: {
                required: function () {
                    return $('#purchase_request_type').val() === 'Supplies';
                }
            }
        },
        messages: {
            purchase_request_type: {
                required: 'Please choose the purchase request type'
            },
            company_id: {
                required: 'Please choose the company'
            },
            month_coverage: {
                required: 'Please specify the month coverage'
            },
            coverage_period: {
                required: 'Please specify the coverage period'
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
            const purchase_request_id = $('#purchase-request-id').text();
            const transaction = 'save purchase request';
        
            $.ajax({
                type: 'POST',
                url: 'controller/purchase-request-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&purchase_request_id=' + purchase_request_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-data');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert Purchase Request Success' : 'Update Purchase Request Success';
                        const notificationDescription = response.insertRecord ? 'The purchase request has been inserted successfully.' : 'The purchase request has been updated successfully.';
                        
                        setNotification(notificationMessage, notificationDescription, 'success');

                        window.location = 'purchase-request.php?id=' + response.purchaseRequestID;
                    }
                    else {
                        if (response.isInactive) {
                            setNotification('User Inactive', response.message, 'danger');
                            window.location = 'logout.php?logout';
                        }
                        else {
                            showNotification('Purchase Request Error', response.message, 'danger');
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

function addItemForm(){
    $('#add-item-form').validate({
        rules: {
            description: {
                required: true
            },
            quantity: {
                required: true
            },
            unit_id: {
                required: true
            },
        },
        messages: {
            description: {
                required: 'Please enter the item'
            },
            quantity: {
                required: 'Please enter the quantity'
            },
            unit_id: {
                required: 'Please choose the unit'
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
            const purchase_request_id = $('#purchase-request-id').text();
            const transaction = 'save purchase request item';
        
            $.ajax({
                type: 'POST',
                url: 'controller/purchase-request-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&purchase_request_id=' + purchase_request_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-add-item');
                },
                success: function (response) {
                    if (response.success) {
                        showNotification('Save Item Success', 'The purchase request has been saved successfully', 'success');
                    }
                    else {
                        if (response.isInactive) {
                            setNotification('User Inactive', response.message, 'danger');
                            window.location = 'logout.php?logout';
                        }
                        else {
                            showNotification('Purchase Request Error', response.message, 'danger');
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
                    enableFormSubmitButton('submit-add-item', 'Submit');
                    $('#add-item-offcanvas').offcanvas('hide');
                    reloadDatatable('#purchase-request-item-table');
                    resetModalForm('add-item-form');
                }
            });
        
            return false;
        }
    });
}

function cancelPurchaseRequestForm(){
    $('#cancel-purchase-request-form').validate({
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
            var purchase_request_id = $('#purchase-request-id').text();
            const transaction = 'tag request as cancelled';
        
            $.ajax({
                type: 'POST',
                url: 'controller/purchase-request-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&purchase_request_id=' + purchase_request_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-cancel-transaction');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = 'Cancel Transaction Success';
                        const notificationDescription = 'The transaction has been tag as cancelled successfully.';
                        
                        setNotification(notificationMessage, notificationDescription, 'success');
                        window.location.reload();
                    }
                    else {
                        if (response.isInactive) {
                            setNotification('User Inactive', response.message, 'danger');
                            window.location = 'logout.php?logout';
                        }
                        else if (response.cartQuantity) {
                            showNotification('Cancel Transaction Error', 'One of the parts added does not have enough quantity. Kindly check the added parts.', 'danger');
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
                    enableFormSubmitButton('submit-cancel-transaction', 'Submit');
                }
            });
        
            return false;
        }
    });
}

function draftPurchaseRequestForm(){
    $('#draft-purchase-request-form').validate({
        rules: {
            draft_reason: {
                required: true
            },
        },
        messages: {
            draft_reason: {
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
            var purchase_request_id = $('#purchase-request-id').text();
            const transaction = 'tag request as draft';
        
            $.ajax({
                type: 'POST',
                url: 'controller/purchase-request-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&purchase_request_id=' + purchase_request_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-draft-transaction');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = 'Set to Draft Transaction Success';
                        const notificationDescription = 'The transaction has been set to draft successfully.';
                        
                        setNotification(notificationMessage, notificationDescription, 'success');
                        window.location.reload();
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
                    enableFormSubmitButton('submit-draft-transaction', 'Submit');
                }
            });
        
            return false;
        }
    });
}

function approvePurchaseRequestForm(){
    $('#approve-purchase-request-form').validate({
        rules: {
            approval_remarks: {
                required: true
            },
        },
        messages: {
            approval_remarks: {
                required: 'Please enter the validation remarks'
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
            var purchase_request_id = $('#purchase-request-id').text();
            const transaction = 'tag request as approved';
        
            $.ajax({
                type: 'POST',
                url: 'controller/purchase-request-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&purchase_request_id=' + purchase_request_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-approve-transaction');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = 'Validate Transaction Success';
                        const notificationDescription = 'The transaction has been tag as validated successfully.';
                        
                        setNotification(notificationMessage, notificationDescription, 'success');
                        window.location.reload();
                    }
                    else {
                        if (response.isInactive) {
                            setNotification('User Inactive', response.message, 'danger');
                            window.location = 'logout.php?logout';
                        }
                        else if (response.cartQuantity) {
                            showNotification('Validate Transaction Error', 'One of the parts added does not have enough quantity. Kindly check the added parts.', 'danger');
                        }
                        else if (response.jobOrder) {
                            showNotification('Validate Transaction Error', 'No job order or additional job order linked. Cannot be processed.', 'danger');
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
                    enableFormSubmitButton('submit-approve-transaction', 'Submit');
                }
            });
        
            return false;
        }
    });
}

function displayDetails(transaction){
    switch (transaction) {
        case 'get purchase request details':
            var purchase_request_id = $('#purchase-request-id').text();
            
            $.ajax({
                url: 'controller/purchase-request-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    purchase_request_id : purchase_request_id, 
                    transaction : transaction
                },
                success: function(response) {
                    if (response.success) {
                        $('#reference_no').val(response.reference_no);
                        $('#remarks').val(response.remarks);
                        
                        checkOptionExist('#purchase_request_type', response.purchase_request_type, '');       
                        checkOptionExist('#company_id', response.company_id, '');    
                        checkOptionExist('#coverage_period', response.coverage_period, '');
                        checkOptionExist('#month_coverage', response.month_coverage, '');   
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
        case 'get purchase request cart details':
            const purchase_request_cart_id = $('#purchase_request_cart_id').val();
            
            $.ajax({
                url: 'controller/purchase-request-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    purchase_request_cart_id : purchase_request_cart_id, 
                    transaction : transaction
                },
                success: function(response) {
                    if (response.success) {
                        $('#purchase_request_cart_id').val(purchase_request_cart_id);
                        $('#quantity').val(response.quantity);
                        $('#description').val(response.description);
                        $('#item_remarks').val(response.remarks);
                        
                        checkOptionExist('#unit_id', response.unit_id, '');
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