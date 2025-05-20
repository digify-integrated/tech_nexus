(function($) {
    'use strict';

    $(function() {
        if($('#parts-transaction-table').length){
            partsTransactionTable('#parts-transaction-table');
        }

        if($('#parts-item-table').length){
            partItemTable('#parts-item-table');
        }

        if($('#parts-transaction-document-table').length){
            partTransactionDocumentTable('#parts-transaction-document-table');
        }
        
        if($('#add-part-form').length){
            addPartsForm();
        }

        if($('#cancel-transaction-form').length){
            cancelTransactionForm();
        }

        if($('#approve-transaction-form').length){
            approveTransactionForm();
        }
        
        if($('#add-part-document-form').length){
            partsDocumentForm();
        }

        if($('#part-item-form').length){
            partItemForm();
        }

        $(document).on('click','#add-part',function() {
            if($('#add-part-table').length){
                addPartTable('#add-part-table');
            }
        });

        $(document).on('click','#apply-filter',function() {
            partsTransactionTable('#parts-transaction-table');
        });
        
        if($('#parts-transaction-id').length){
            displayDetails('get parts transaction cart total');

            document.getElementById('quantity').addEventListener('input', calculateTotals);
            document.getElementById('discount').addEventListener('input', calculateTotals);
            document.getElementById('add_on').addEventListener('input', calculateTotals);
        }

        $(document).on('change','#discount_type',function() {
            calculateTotals();
        });

        $(document).on('click','.update-part-cart',function() {
            const parts_transaction_cart_id = $(this).data('parts-transaction-cart-id');

            $('#part_transaction_cart_id').val(parts_transaction_cart_id);
            displayDetails('get parts transaction cart details');
        });

        $(document).on('click','#on-process',function() {
            var parts_transaction_id = $('#parts-transaction-id').text();
            const transaction = 'tag transaction as on process';
    
            Swal.fire({
                title: 'Confirm Transaction On-Process',
                text: 'Are you sure you want to tag this transaction as on-process?',
                icon: 'warning',
                showCancelButton: !0,
                confirmButtonText: 'On-Process',
                cancelButtonText: 'Cancel',
                confirmButtonClass: 'btn btn-info mt-2',
                cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                buttonsStyling: !1
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        type: 'POST',
                        url: 'controller/parts-transaction-controller.php',
                        dataType: 'json',
                        data: {
                            parts_transaction_id : parts_transaction_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Transaction On-Process Success', 'The transaction has been tagged as on-process successfully.', 'success');
                                window.location.reload();
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.cartQuantity) {
                                    showNotification('Transaction On-Process Error', 'One of the parts added does not have enough quantity. Kindly check the added parts.', 'danger');
                                }
                                else {
                                    showNotification('Transaction On-Process Error', response.message, 'danger');
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
            var parts_transaction_id = $('#parts-transaction-id').text();
            const transaction = 'tag transaction as for approval';
    
            Swal.fire({
                title: 'Confirm Transaction For Approval',
                text: 'Are you sure you want to tag this transaction as for approval?',
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
                        url: 'controller/parts-transaction-controller.php',
                        dataType: 'json',
                        data: {
                            parts_transaction_id : parts_transaction_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Transaction For Approval Success', 'The transaction has been tagged as for approval successfully.', 'success');
                                window.location.reload();
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.cartQuantity) {
                                    showNotification('Transaction For Approval Error', 'One of the parts added does not have enough quantity. Kindly check the added parts.', 'danger');
                                }
                                else {
                                    showNotification('Transaction For Approval Error', response.message, 'danger');
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
            var parts_transaction_id = $('#parts-transaction-id').text();
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
                        url: 'controller/parts-transaction-controller.php',
                        dataType: 'json',
                        data: {
                            parts_transaction_id : parts_transaction_id, 
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

        $(document).on('click','.delete-part-cart',function() {
            const parts_transaction_cart_id = $(this).data('parts-transaction-cart-id');
            var parts_transaction_id = $('#parts-transaction-id').text();
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
                        url: 'controller/parts-transaction-controller.php',
                        dataType: 'json',
                        data: {
                            parts_transaction_cart_id : parts_transaction_cart_id, 
                            parts_transaction_id : parts_transaction_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete Part Item Success', 'The part item has been deleted successfully.', 'success');
                                reloadDatatable('#parts-item-table');
                                displayDetails('get parts transaction cart total');
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

        $(document).on('click','#create-transaction',function() {
            var transaction = 'save parts transaction';
            
            $.ajax({
                type: 'POST',
                url: 'controller/parts-transaction-controller.php',
                dataType: 'json',
                data: {
                    transaction : transaction
                },
                success: function (response) {
                    if (response.success) {
                        window.location = 'parts-transaction.php?id=' + response.partsTransactionID;
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
                }
            });
        });
    });
})(jQuery);

function partsTransactionTable(datatable_name, buttons = false, show_all = false){
    const type = 'parts transaction table';
    var filter_transaction_date_start_date = $('#filter_transaction_date_start_date').val();
    var filter_transaction_date_end_date = $('#filter_transaction_date_end_date').val();
    var filter_approval_date_start_date = $('#filter_approval_date_start_date').val();
    var filter_approval_date_end_date = $('#filter_approval_date_end_date').val();

    var transaction_status_filter = [];

    $('.transaction-status-checkbox:checked').each(function() {
        transaction_status_filter.push($(this).val());
    });

    var filter_transaction_status = transaction_status_filter.join(', ');

    var settings;

    const column = [
        { 'data' : 'TRANSACTION_ID' },
        { 'data' : 'NUMBER_OF_ITEMS' },
        { 'data' : 'ADD_ON' },
        { 'data' : 'DISCOUNT' },
        { 'data' : 'SUB_TOTAL' },
        { 'data' : 'TOTAL_AMOUNT' },
        { 'data' : 'TRANSACTION_DATE' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': 'auto', 'aTargets': 0 },
        { 'width': 'auto', 'aTargets': 1 },
        { 'width': 'auto', 'aTargets': 2 },
        { 'width': 'auto', 'aTargets': 3 },
        { 'width': 'auto', 'aTargets': 4 },
        { 'width': 'auto', 'aTargets': 5 },
        { 'width': 'auto', 'type': 'date', 'aTargets': 6 },
        { 'width': '15%','bSortable': false, 'aTargets': 7 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_parts_transaction_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {'type' : type, 
                'filter_transaction_date_start_date' : filter_transaction_date_start_date, 
                'filter_transaction_date_end_date' : filter_transaction_date_end_date,
                'filter_approval_date_start_date' : filter_approval_date_start_date,
                'filter_approval_date_end_date' : filter_approval_date_end_date,
                'filter_transaction_status' : filter_transaction_status,
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
    var parts_transaction_id = $('#parts-transaction-id').text();
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
            'url' : 'view/_parts_transaction_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {'type' : type, 'parts_transaction_id' : parts_transaction_id},
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
    var parts_transaction_id = $('#parts-transaction-id').text();
    const type = 'part item table';
    var settings;

    const column = [ 
        { 'data' : 'PART' },
        { 'data' : 'PRICE' },
        { 'data' : 'QUANTITY' },
        { 'data' : 'AVAILABLE_STOCK' },
        { 'data' : 'ADD_ON' },
        { 'data' : 'DISCOUNT' },
        { 'data' : 'DISCOUNT_TOTAL' },
        { 'data' : 'SUBTOTAL' },
        { 'data' : 'TOTAL' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': 'auto', 'aTargets': 0 },
        { 'width': 'auto', 'aTargets': 1 },
        { 'width': 'auto', 'aTargets': 2 },
        { 'width': 'auto', 'aTargets': 3 },
        { 'width': 'auto', 'aTargets': 4 },
        { 'width': 'auto', 'aTargets': 5 },
        { 'width': 'auto', 'aTargets': 6 },
        { 'width': 'auto', 'aTargets': 7 },
        { 'width': 'auto', 'aTargets': 8 },
        { 'width': 'auto', 'bSortable': false, 'aTargets': 9 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_parts_transaction_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {'type' : type, 'parts_transaction_id' : parts_transaction_id},
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

function partTransactionDocumentTable(datatable_name, buttons = false, show_all = false){
    var parts_transaction_id = $('#parts-transaction-id').text();
    const type = 'part transaction document table';
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
            'url' : 'view/_parts_transaction_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {'type' : type, 'parts_transaction_id' : parts_transaction_id},
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
          var parts_transaction_id = $('#parts-transaction-id').text();
            const transaction = 'add parts transaction item';

            var part_id = [];

            $('.assign-part').each(function(){
                if ($(this).is(':checked')){  
                    part_id.push(this.value);  
                }
            });

            $.ajax({
                type: 'POST',
                url: 'controller/parts-transaction-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&parts_transaction_id=' + parts_transaction_id + '&part_id=' + part_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-add-part');
                },
                success: function(response) {
                    if (response.success) {
                        showNotification('Add Parts Success', 'The parts has been added successfully.', 'success');
                        displayDetails('get parts transaction cart total');
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
            transaction_document: {
                required: true
            },
        },
        messages: {
            document_name: {
                required: 'Please enter the document name'
            },
            transaction_document: {
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
            var parts_transaction_id = $('#parts-transaction-id').text();
            const transaction = 'add parts transaction document';
            var formData = new FormData(form);
            formData.append('transaction', transaction);
            formData.append('parts_transaction_id', parts_transaction_id);
        
            $.ajax({
                type: 'POST',
                url: 'controller/parts-transaction-controller.php',
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
                    enableFormSubmitButton('submit-add-part-document', 'Submit');
                    reloadDatatable('#parts-transaction-document-table');
                    $('#add-part-document-offcanvas').offcanvas('hide');
                    resetModalForm('add-part-document-form');
                }
            });
        
            return false;
        }
    });
}

function cancelTransactionForm(){
    $('#cancel-transaction-form').validate({
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
            var parts_transaction_id = $('#parts-transaction-id').text();
            const transaction = 'tag transaction as cancelled';
        
            $.ajax({
                type: 'POST',
                url: 'controller/parts-transaction-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&parts_transaction_id=' + parts_transaction_id,
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

function approveTransactionForm(){
    $('#approve-transaction-form').validate({
        rules: {
            approval_remarks: {
                required: true
            },
        },
        messages: {
            approval_remarks: {
                required: 'Please enter the approval remarks'
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
            var parts_transaction_id = $('#parts-transaction-id').text();
            const transaction = 'tag transaction as approved';
        
            $.ajax({
                type: 'POST',
                url: 'controller/parts-transaction-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&parts_transaction_id=' + parts_transaction_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-approve-transaction');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = 'Approve Transaction Success';
                        const notificationDescription = 'The transaction has been tag as approved successfully.';
                        
                        setNotification(notificationMessage, notificationDescription, 'success');
                        window.location.reload();
                    }
                    else {
                        if (response.isInactive) {
                            setNotification('User Inactive', response.message, 'danger');
                            window.location = 'logout.php?logout';
                        }
                        else if (response.cartQuantity) {
                            showNotification('Approve Transaction Error', 'One of the parts added does not have enough quantity. Kindly check the added parts.', 'danger');
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

$.validator.addMethod("maxStock", function(value, element) {
    var availableStock = parseInt($("#available_stock").val(), 10);
    var quantity = parseInt(value, 10);
    return quantity <= availableStock;
}, "Quantity cannot exceed available stock.");

function partItemForm(){
  $('#part-item-form').validate({
        rules: {
            discount_type: {
                required: {
                    depends: function(element) {
                        return $("#discount").val() > 0;
                    }
                }
            },
            quantity: {
                required: true,
                maxStock: true
            },
        },
        messages: {
            quantity: {
                required: 'Please enter the customer',
                maxStock: 'Quantity cannot exceed available stock'
            },
            discount_type: {
                required: 'Please choose the discount type'
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
                url: 'controller/parts-transaction-controller.php',
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
                        displayDetails('get parts transaction cart total');
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
        case 'get parts transaction cart details':
            const part_transaction_cart_id = $('#part_transaction_cart_id').val();
            
            $.ajax({
                url: 'controller/parts-transaction-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    part_transaction_cart_id : part_transaction_cart_id, 
                    transaction : transaction
                },
                success: function(response) {
                    if (response.success) {
                        $('#quantity').val(response.quantity);
                        $('#part_id').val(response.part_id);
                        $('#discount').val(response.discount);
                        $('#add_on').val(response.add_on);
                        
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
                        $('#part_price').val(response.part_price);
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
                },
                complete: function(){
                    calculateTotals();
                }
            });
            break;
        case 'get parts transaction cart total':
            var parts_transaction_id = $('#parts-transaction-id').text();
            
            $.ajax({
                url: 'controller/parts-transaction-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    parts_transaction_id : parts_transaction_id, 
                    transaction : transaction
                },
                success: function(response) {
                    if (response.success) {
                        $('#sub-total-summary').text(response.subTotal);
                        $('#total-discount-summary').text(response.discountAmount);
                        $('#total-summary').text(response.total);
                        $('#add-on-total-summary').text(response.addOn);
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
                },
                complete: function(){
                    calculateTotals();
                }
            });
            break;
    }
}

function calculateTotals() {
  const priceInput = document.getElementById('part_price');
  const quantityInput = document.getElementById('quantity');
  const addOnInput = document.getElementById('add_on');
  const discountInput = document.getElementById('discount');
  const discountTotal = document.getElementById('discount_total');
  const discountTypeSelect = document.getElementById('discount_type');
  const subtotalInput = document.getElementById('part_item_subtotal');
  const totalInput = document.getElementById('part_item_total');

  // Parse values with fallback to 0
  const price = Math.max(parseFloat(priceInput.value) || 0, 0);
  const quantity = Math.max(parseInt(quantityInput.value) || 0, 0);
  const discount = Math.max(parseFloat(discountInput.value) || 0, 0);
  const addOn = Math.max(parseFloat(addOnInput.value) || 0, 0);
  const discountType = discountTypeSelect.value;

  // Calculate subtotal
  const subtotal = price * quantity;
  const subtotal2 = (price * quantity) + addOn;
  subtotalInput.value = subtotal2.toFixed(2);

  // Calculate discount amount
  let discountAmount = 0;
  if (discountType === 'Percentage') {
    discountAmount = subtotal * (discount / 100);
  } else if (discountType === 'Amount') {
    discountAmount = discount;
  }

  // Prevent discount from exceeding subtotal
  discountAmount = Math.min(discountAmount, subtotal);
  discountTotal.value = discountAmount.toFixed(2); // Update the discount total field

  // Calculate total
  const total = (subtotal + addOn) - discountAmount;
  totalInput.value = total.toFixed(2);
}