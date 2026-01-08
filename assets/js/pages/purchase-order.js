(function($) {
    'use strict';

    $(function() {
        if($('#purchase-order-table').length){
            purchaseOrderTable('#purchase-order-table');
        }

        if($('#unit-order-item-table').length){
            purchaseOrderItemUnitTable('#unit-order-item-table');
        }

        if($('#part-order-item-table').length){
            purchaseOrderItemPartTable('#part-order-item-table');
        }

        if($('#supply-order-item-table').length){
            purchaseOrderItemSupplyTable('#supply-order-item-table');
        }

        $(document).on('click','#apply-filter',function() {
            purchaseOrderTable('#purchase-order-table');
        });
        
        if($('#purchase-order-id').length){
            displayDetails('get purchase order details');
        }

        if($('#purchase-order-form').length){
            purchaseOrderForm();
        }

        if($('#add-item-unit-form').length){
            addItemUnitForm();
        }

        if($('#add-item-part-form').length){
            addItemPartForm();
        }

        if($('#add-item-supply-form').length){
            addItemSupplyForm();
        }

        if($('#receive-item-form').length){
            receiveItemForm();
        }

        if($('#cancel-receive-item-form').length){
            cancelItemForm();
        }

         if($('#cancel-purchase-order-form').length){
            cancelPurchaseOrderForm();
        }

        if($('#draft-purchase-order-form').length){
            draftPurchaseOrderForm();
        }

        if($('#approve-purchase-order-form').length){
            approvePurchaseOrderForm();
        }

        $(document).on('click','#for-approval',function() {
            var purchase_order_id = $('#purchase-order-id').text();
            const transaction = 'tag request as for approval';
    
            Swal.fire({
                title: 'Confirm Order For Approval',
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
                        url: 'controller/purchase-order-controller.php',
                        dataType: 'json',
                        data: {
                            purchase_order_id : purchase_order_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Order For Approval Success', 'The request has been tagged as for approval successfully.', 'success');
                                window.location.reload();
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.noItem) {
                                    showNotification('Order For Approval Error', 'No item added. Cannot be processed.', 'danger');
                                }
                                else {
                                    showNotification('Order For Approval Error', response.message, 'danger');
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

        $(document).on('click','#on-process',function() {
            var purchase_order_id = $('#purchase-order-id').text();
            const transaction = 'tag request as on-process';
    
            Swal.fire({
                title: 'Confirm Order On-Process',
                text: 'Are you sure you want to tag this request as on-process?',
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
                        url: 'controller/purchase-order-controller.php',
                        dataType: 'json',
                        data: {
                            purchase_order_id : purchase_order_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Order On-Process Success', 'The request has been tagged as on-process successfully.', 'success');
                                window.location.reload();
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else {
                                    showNotification('Order On-Process Error', response.message, 'danger');
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

        $(document).on('click','#complete',function() {
            var purchase_order_id = $('#purchase-order-id').text();
            const transaction = 'tag request as complete';
    
            Swal.fire({
                title: 'Confirm Order Complete',
                text: 'Are you sure you want to tag this purchase order as complete?',
                icon: 'warning',
                showCancelButton: !0,
                confirmButtonText: 'Complete',
                cancelButtonText: 'Cancel',
                confirmButtonClass: 'btn btn-info mt-2',
                cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                buttonsStyling: !1
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        type: 'POST',
                        url: 'controller/purchase-order-controller.php',
                        dataType: 'json',
                        data: {
                            purchase_order_id : purchase_order_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Order Complete Success', 'The purchase order has been tagged as complete successfully.', 'success');
                                window.location.reload();
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else {
                                    showNotification('Order Complete Error', response.message, 'danger');
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
            var purchase_order_id = $('#purchase-order-id').text();
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
                        url: 'controller/purchase-order-controller.php',
                        dataType: 'json',
                        data: {
                            purchase_order_id : purchase_order_id, 
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
            var purchase_order_id = $('#purchase-order-id').text();
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
                        url: 'controller/purchase-order-controller.php',
                        dataType: 'json',
                        data: {
                            purchase_order_id : purchase_order_id, 
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

        $(document).on('click','.delete-part-cart-unit',function() {
            const purchase_order_unit_id = $(this).data('purchase-order-unit-id');
            var purchase_order_id = $('#purchase-order-id').text();
            const transaction = 'delete item unit';
    
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
                        url: 'controller/purchase-order-controller.php',
                        dataType: 'json',
                        data: {
                            purchase_order_unit_id : purchase_order_unit_id, 
                            purchase_order_id : purchase_order_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete Item Success', 'The item has been deleted successfully.', 'success');
                                reloadDatatable('#unit-order-item-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Delete Item Error', 'The item does not exists.', 'danger');
                                    reloadDatatable('#unit-order-item-table');
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

        $(document).on('click','.update-part-cart-unit',function() {
            const purchase_order_unit_id = $(this).data('purchase-order-unit-id');

            $('#purchase_order_unit_id').val(purchase_order_unit_id);
            displayDetails('get purchase order unit details');
        });

        $(document).on('click','.delete-part-cart-part',function() {
            const purchase_order_part_id = $(this).data('purchase-order-part-id');
            var purchase_order_id = $('#purchase-order-id').text();
            const transaction = 'delete item part';
    
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
                        url: 'controller/purchase-order-controller.php',
                        dataType: 'json',
                        data: {
                            purchase_order_part_id : purchase_order_part_id, 
                            purchase_order_id : purchase_order_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete Item Success', 'The item has been deleted successfully.', 'success');
                                reloadDatatable('#part-order-item-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Delete Item Error', 'The item does not exists.', 'danger');
                                    reloadDatatable('#part-order-item-table');
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

        $(document).on('click','.update-part-cart-part',function() {
            const purchase_order_part_id = $(this).data('purchase-order-part-id');

            $('#purchase_order_part_id').val(purchase_order_part_id);
            displayDetails('get purchase order part details');
        });

        $(document).on('click','.delete-part-cart-supply',function() {
            const purchase_order_supply_id = $(this).data('purchase-order-supply-id');
            var purchase_order_id = $('#purchase-order-id').text();
            const transaction = 'delete item supply';
    
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
                        url: 'controller/purchase-order-controller.php',
                        dataType: 'json',
                        data: {
                            purchase_order_supply_id : purchase_order_supply_id, 
                            purchase_order_id : purchase_order_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete Item Success', 'The item has been deleted successfully.', 'success');
                                reloadDatatable('#supply-order-item-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Delete Item Error', 'The item does not exists.', 'danger');
                                    reloadDatatable('#supply-order-item-table');
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

        $(document).on('click','.update-supply-cart-supply',function() {
            const purchase_order_supply_id = $(this).data('purchase-order-supply-id');

            $('#purchase_order_supply_id').val(purchase_order_supply_id);
            displayDetails('get purchase order supply details');
        });

        $(document).on('click','#discard-create',function() {
            discardCreate('purchase-order.php');
        });

         $(document).on('click','.receive-quantity',function() {
            const purchase_order_cart_id = $(this).data('purchase-order-cart-id');
            const type = $(this).data('type');

            sessionStorage.setItem('purchase_order_cart_id', purchase_order_cart_id);
            sessionStorage.setItem('type', type);

            displayDetails('get receive details');
        });

        $(document).on('click','.cancel-receive-quantity',function() {
            const purchase_order_cart_id = $(this).data('purchase-order-cart-id');
            const type = $(this).data('type');

            sessionStorage.setItem('purchase_order_cart_id', purchase_order_cart_id);
            sessionStorage.setItem('type', type);

            displayDetails('get receive details');
        });

    });
})(jQuery);

function purchaseOrderTable(datatable_name, buttons = false, show_all = false){
    const type = 'purchase order table';
    var filter_transaction_date_start_date = $('#filter_transaction_date_start_date').val();
    var filter_transaction_date_end_date = $('#filter_transaction_date_end_date').val();
    var filter_approval_date_start_date = $('#filter_approval_date_start_date').val();
    var filter_approval_date_end_date = $('#filter_approval_date_end_date').val();
    var filter_onprocess_date_start_date = $('#filter_onprocess_date_start_date').val();
    var filter_onprocess_date_end_date = $('#filter_onprocess_date_end_date').val();
    var filter_completion_date_start_date = $('#filter_completion_date_start_date').val();
    var filter_completion_date_end_date = $('#filter_completion_date_end_date').val();

    var order_status_filter = [];

    $('.order-status-checkbox:checked').each(function() {
        order_status_filter.push($(this).val());
    });

    var filter_order_status = order_status_filter.join(', ');

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
            'url' : 'view/_purchase_order_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {'type' : type, 
                'filter_transaction_date_start_date' : filter_transaction_date_start_date, 
                'filter_transaction_date_end_date' : filter_transaction_date_end_date,
                'filter_approval_date_start_date' : filter_approval_date_start_date,
                'filter_approval_date_end_date' : filter_approval_date_end_date,
                'filter_onprocess_date_start_date' : filter_onprocess_date_start_date,
                'filter_onprocess_date_end_date' : filter_onprocess_date_end_date,
                'filter_completion_date_start_date' : filter_completion_date_start_date,
                'filter_completion_date_end_date' : filter_completion_date_end_date,
                'filter_order_status' : filter_order_status
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

function purchaseOrderItemUnitTable(datatable_name, buttons = false, show_all = false){
    const purchase_order_id = $('#purchase-order-id').text();
    const type = 'purchase order item unit table';
    var settings;

    const column = [ 
        { 'data' : 'ACTION' },
        { 'data' : 'UNIT' },
        { 'data' : 'REQUEST' },
        { 'data' : 'QUANTITY' },
        { 'data' : 'ACTUAL_QUANTITY' },
        { 'data' : 'CANCELLED_QUANTITY' },
        { 'data' : 'PRICE' },
        { 'data' : 'REMARKS' }
    ];

    const column_definition = [
        { 'width': '5%', 'bSortable': false, 'aTargets': 0 },
        { 'width': 'auto', 'aTargets': 1 },
        { 'width': 'auto', 'aTargets': 2 },
        { 'width': 'auto', 'aTargets': 3 },
        { 'width': 'auto', 'aTargets': 4 },
        { 'width': 'auto', 'aTargets': 5 },
        { 'width': 'auto', 'aTargets': 6 },
        { 'width': 'auto', 'aTargets': 7 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_purchase_order_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {'type' : type, 'purchase_order_id' : purchase_order_id},
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

function purchaseOrderItemPartTable(datatable_name, buttons = false, show_all = false){
    const purchase_order_id = $('#purchase-order-id').text();
    const type = 'purchase order item part table';
    var settings;

    const column = [ 
        { 'data' : 'ACTION' },
        { 'data' : 'PART' },
        { 'data' : 'REQUEST' },
        { 'data' : 'QUANTITY' },
        { 'data' : 'ACTUAL_QUANTITY' },
        { 'data' : 'CANCELLED_QUANTITY' },
        { 'data' : 'PRICE' },
        { 'data' : 'REMARKS' }
    ];

    const column_definition = [
        { 'width': '5%', 'bSortable': false, 'aTargets': 0 },
        { 'width': 'auto', 'aTargets': 1 },
        { 'width': 'auto', 'aTargets': 2 },
        { 'width': 'auto', 'aTargets': 3 },
        { 'width': 'auto', 'aTargets': 4 },
        { 'width': 'auto', 'aTargets': 5 },
        { 'width': 'auto', 'aTargets': 6 },
        { 'width': 'auto', 'aTargets': 7 },
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_purchase_order_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {'type' : type, 'purchase_order_id' : purchase_order_id},
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

function purchaseOrderItemSupplyTable(datatable_name, buttons = false, show_all = false){
    const purchase_order_id = $('#purchase-order-id').text();
    const type = 'purchase order item supply table';
    var settings;

    const column = [ 
        { 'data' : 'ACTION' },
        { 'data' : 'SUPPLY' },
        { 'data' : 'REQUEST' },
        { 'data' : 'QUANTITY' },
        { 'data' : 'ACTUAL_QUANTITY' },
        { 'data' : 'CANCELLED_QUANTITY' },
        { 'data' : 'PRICE' },
        { 'data' : 'REMARKS' }
    ];

    const column_definition = [
        { 'width': '5%', 'bSortable': false, 'aTargets': 0 },
        { 'width': 'auto', 'aTargets': 1 },
        { 'width': 'auto', 'aTargets': 2 },
        { 'width': 'auto', 'aTargets': 3 },
        { 'width': 'auto', 'aTargets': 4 },
        { 'width': 'auto', 'aTargets': 5 },
        { 'width': 'auto', 'aTargets': 6 },
        { 'width': 'auto', 'aTargets': 7 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_purchase_order_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {'type' : type, 'purchase_order_id' : purchase_order_id},
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

function purchaseOrderForm(){
    $('#purchase-order-form').validate({
        rules: {
            purchase_order_type: {
                required: true
            },
            company_id: {
                required: true
            },
            supplier_id: {
                required: true
            },
        },
        messages: {
            purchase_order_type: {
                required: 'Please choose the purchase order type'
            },
            company_id: {
                required: 'Please choose the company'
            },
            supplier_id: {
                required: 'Please choose the supplier'
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
            const purchase_order_id = $('#purchase-order-id').text();
            const transaction = 'save purchase order';
        
            $.ajax({
                type: 'POST',
                url: 'controller/purchase-order-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&purchase_order_id=' + purchase_order_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-data');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert Purchase Order Success' : 'Update Purchase Order Success';
                        const notificationDescription = response.insertRecord ? 'The purchase order has been inserted successfully.' : 'The purchase order has been updated successfully.';
                        
                        setNotification(notificationMessage, notificationDescription, 'success');

                        window.location = 'purchase-order.php?id=' + response.purchaseOrderID;
                    }
                    else {
                        if (response.isInactive) {
                            setNotification('User Inactive', response.message, 'danger');
                            window.location = 'logout.php?logout';
                        }
                        else {
                            showNotification('Purchase Order Error', response.message, 'danger');
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

function addItemUnitForm(){
    $('#add-item-unit-form').validate({
        rules: {
            purchase_request_cart_id: {
                required: true
            },
            product_subcategory_id: {
                required: true
            },
            brand_id: {
                required: true
            },
            price_unit: {
                required: true
            },
            model_id: {
                required: true
            },
            quantity_unit: {
                required: true
            },
            quantity_unit_id: {
                required: true
            },
        },
        messages: {
            purchase_request_cart_id: {
                required: 'Please choose the purchase order'
            },
            product_subcategory_id: {
                required: 'Please choose the category'
            },
            price_unit: {
                required: 'Please enter the price'
            },
            brand_id: {
                required: 'Please choose the brand'
            },
            model_id: {
                required: 'Please choose the model'
            },
            quantity_unit: {
                required: 'Please enter the quantity'
            },
            quantity_unit_id: {
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
            const purchase_order_id = $('#purchase-order-id').text();
            const transaction = 'save purchase order item unit';
        
            $.ajax({
                type: 'POST',
                url: 'controller/purchase-order-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&purchase_order_id=' + purchase_order_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-add-item');
                },
                success: function (response) {
                    if (response.success) {
                        showNotification('Save Item Success', 'The purchase order has been saved successfully', 'success');
                        $('#add-item-unit-offcanvas').offcanvas('hide');
                    }
                    else {
                        if (response.isInactive) {
                            setNotification('User Inactive', response.message, 'danger');
                            window.location = 'logout.php?logout';
                        }
                        else {
                            showNotification('Purchase Order Error', response.message, 'danger');
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
                    reloadDatatable('#unit-order-item-table');
                    resetModalForm('add-item-unit-form');
                }
            });
        
            return false;
        }
    });
}

function addItemPartForm(){
    $('#add-item-part-form').validate({
        rules: {
            purchase_request_cart_part_id: {
                required: true
            },
            part_id: {
                required: true
            },
            price_part: {
                required: true
            },
            quantity_part: {
                required: true
            },
            quantity_part_id: {
                required: true
            },
        },
        messages: {
            purchase_request_cart_part_id: {
                required: 'Please choose the purchase order'
            },
            part_id: {
                required: 'Please choose the part'
            },
            price_part: {
                required: 'Please enter the price'
            },
            quantity_part: {
                required: 'Please enter the quantity'
            },
            quantity_part_id: {
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
            const purchase_order_id = $('#purchase-order-id').text();
            const transaction = 'save purchase order item part';
        
            $.ajax({
                type: 'POST',
                url: 'controller/purchase-order-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&purchase_order_id=' + purchase_order_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-add-item-part');
                },
                success: function (response) {
                    if (response.success) {
                        showNotification('Save Item Success', 'The purchase order has been saved successfully', 'success');
                    }
                    else {
                        if (response.isInactive) {
                            setNotification('User Inactive', response.message, 'danger');
                            window.location = 'logout.php?logout';
                        }
                        else {
                            showNotification('Purchase Order Error', response.message, 'danger');
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
                    enableFormSubmitButton('submit-add-item-part', 'Submit');
                    $('#add-item-part-offcanvas').offcanvas('hide');
                    reloadDatatable('#part-order-item-table');
                    resetModalForm('add-item-part-form');
                }
            });
        
            return false;
        }
    });
}

function addItemSupplyForm(){
    $('#add-item-supply-form').validate({
        rules: {
            purchase_request_cart_supply_id: {
                required: true
            },
            supply_id: {
                required: true
            },
            price_part: {
                required: true
            },
            quantity_part: {
                required: true
            },
            quantity_part_id: {
                required: true
            },
        },
        messages: {
            purchase_request_cart_part_id: {
                required: 'Please choose the purchase order'
            },
            supply_id: {
                required: 'Please choose the supply'
            },
            price_part: {
                required: 'Please enter the price'
            },
            quantity_part: {
                required: 'Please enter the quantity'
            },
            quantity_part_id: {
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
            const purchase_order_id = $('#purchase-order-id').text();
            const transaction = 'save purchase order item supply';
        
            $.ajax({
                type: 'POST',
                url: 'controller/purchase-order-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&purchase_order_id=' + purchase_order_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-add-item-supply');
                },
                success: function (response) {
                    if (response.success) {
                        showNotification('Save Item Success', 'The purchase order has been saved successfully', 'success');
                    }
                    else {
                        if (response.isInactive) {
                            setNotification('User Inactive', response.message, 'danger');
                            window.location = 'logout.php?logout';
                        }
                        else {
                            showNotification('Purchase Order Error', response.message, 'danger');
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
                    enableFormSubmitButton('submit-add-item-supply', 'Submit');
                    $('#add-item-supply-offcanvas').offcanvas('hide');
                    reloadDatatable('#supply-order-item-table');
                    resetModalForm('add-item-supply-form');
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
            let purchase_order_cart_id = sessionStorage.getItem('purchase_order_cart_id');
            let type = sessionStorage.getItem('type');
            const transaction = 'save purchase order receive';
        
            $.ajax({
                type: 'POST',
                url: 'controller/purchase-order-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&purchase_order_cart_id=' + purchase_order_cart_id + '&type=' + type,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-receive');
                },
                success: function (response) {
                    if (response.success) {
                        showNotification('Update Received Item Success', 'The received item has been updated successfully', 'success');

                        if(type == 'unit'){
                            reloadDatatable('#unit-order-item-table');
                        }
                        else if(type == 'part'){
                            reloadDatatable('#part-order-item-table');
                        }
                        else{
                            reloadDatatable('#supply-order-item-table');
                        }

                        $('#receive-item-offcanvas').offcanvas('hide');
                    }
                    else {
                        if (response.isInactive) {
                            setNotification('User Inactive', response.message, 'danger');
                            window.location = 'logout.php?logout';
                        }
                        else if (response.remainingQuantity) {
                            showNotification('Update Received Item', 'Received quantity cannot be greater than remaining quantity', 'danger');
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
                    enableFormSubmitButton('submit-receive', 'Submit');
                }
            });
        
            return false;
        }
    });
}

function cancelItemForm(){
    $('#cancel-receive-item-form').validate({
        rules: {
            cancel_received_quantity: {
                required: true,
                notGreaterThanRemaining: "#cancel_remaining_quantity"
            },
        },
        messages: {
            cancel_received_quantity: {
                required: 'Please enter the cancel quantity',
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
            let purchase_order_cart_id = sessionStorage.getItem('purchase_order_cart_id');
            let type = sessionStorage.getItem('type');
            const transaction = 'save purchase order receive cancel';
        
            $.ajax({
                type: 'POST',
                url: 'controller/purchase-order-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&purchase_order_cart_id=' + purchase_order_cart_id + '&type=' + type,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-cancel-receive');
                },
                success: function (response) {
                    if (response.success) {
                        showNotification('Cancel Remaining Quantity Success', 'The remaining quantity item has been updated successfully', 'success');

                        if(type == 'unit'){
                            reloadDatatable('#unit-order-item-table');
                        }
                        else if(type == 'part'){
                            reloadDatatable('#part-order-item-table');
                        }
                        else{
                            reloadDatatable('#supply-order-item-table');
                        }

                        $('#cancel-receive-item-offcanvas').offcanvas('hide');
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

function cancelPurchaseOrderForm(){
    $('#cancel-purchase-order-form').validate({
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
            var purchase_order_id = $('#purchase-order-id').text();
            const transaction = 'tag request as cancelled';
        
            $.ajax({
                type: 'POST',
                url: 'controller/purchase-order-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&purchase_order_id=' + purchase_order_id,
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

function draftPurchaseOrderForm(){
    $('#draft-purchase-order-form').validate({
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
            var purchase_order_id = $('#purchase-order-id').text();
            const transaction = 'tag request as draft';
        
            $.ajax({
                type: 'POST',
                url: 'controller/purchase-order-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&purchase_order_id=' + purchase_order_id,
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

function approvePurchaseOrderForm(){
    $('#approve-purchase-order-form').validate({
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
            var purchase_order_id = $('#purchase-order-id').text();
            const transaction = 'tag request as approved';
        
            $.ajax({
                type: 'POST',
                url: 'controller/purchase-order-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&purchase_order_id=' + purchase_order_id,
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
        case 'get purchase order details':
            var purchase_order_id = $('#purchase-order-id').text();
            
            $.ajax({
                url: 'controller/purchase-order-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    purchase_order_id : purchase_order_id, 
                    transaction : transaction
                },
                success: function(response) {
                    if (response.success) {
                        $('#reference_no').val(response.reference_no);
                        $('#remarks').val(response.remarks);
                        
                        checkOptionExist('#purchase_order_type', response.purchase_order_type, '');       
                        checkOptionExist('#company_id', response.company_id, '');       
                        checkOptionExist('#supplier_id', response.supplier_id, '');       
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
        case 'get purchase order unit details':
            const purchase_order_unit_id = $('#purchase_order_unit_id').val();
            
            $.ajax({
                url: 'controller/purchase-order-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    purchase_order_unit_id : purchase_order_unit_id, 
                    transaction : transaction
                },
                success: function(response) {
                    if (response.success) {
                        $('#purchase_order_unit_id').val(purchase_order_unit_id);
                        $('#length').val(response.length);
                        $('#year_model').val(response.year_model);
                        $('#quantity_unit').val(response.quantity);
                        $('#unit_remarks').val(response.remarks);
                        $('#price_unit').val(response.price_unit);
                        
                        checkOptionExist('#purchase_request_cart_id', response.purchase_request_cart_id, '');
                        checkOptionExist('#product_subcategory_id', response.product_category_id, '');
                        checkOptionExist('#brand_id', response.brand_id, '');
                        checkOptionExist('#model_id', response.model_id, '');
                        checkOptionExist('#length_unit', response.length_unit, '');
                        checkOptionExist('#body_type_id', response.body_type_id, '');
                        checkOptionExist('#class_id', response.class_id, '');
                        checkOptionExist('#color_id', response.color_id, '');
                        checkOptionExist('#make_id', response.make_id, '');
                        checkOptionExist('#cabin_id', response.cabin_id, '');
                        checkOptionExist('#quantity_unit_id', response.unit_id, '');
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
        case 'get purchase order part details':
            const purchase_order_part_id = $('#purchase_order_part_id').val();
            
            $.ajax({
                url: 'controller/purchase-order-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    purchase_order_part_id : purchase_order_part_id, 
                    transaction : transaction
                },
                success: function(response) {
                    if (response.success) {
                        $('#purchase_order_part_id').val(purchase_order_part_id);
                        $('#price_part').val(response.price);
                        $('#quantity_part').val(response.quantity);
                        $('#part_remarks').val(response.remarks);
                        
                        checkOptionExist('#purchase_request_cart_part_id', response.purchase_request_cart_id, '');
                        checkOptionExist('#part_id', response.part_id, '');
                        checkOptionExist('#quantity_part_id', response.unit_id, '');
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
        case 'get purchase order supply details':
            const purchase_order_supply_id = $('#purchase_order_supply_id').val();
            
            $.ajax({
                url: 'controller/purchase-order-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    purchase_order_supply_id : purchase_order_supply_id, 
                    transaction : transaction
                },
                success: function(response) {
                    if (response.success) {
                        $('#purchase_order_supply_id').val(purchase_order_supply_id);
                        $('#price_supply').val(response.price);
                        $('#quantity_supply').val(response.quantity);
                        $('#supply_remarks').val(response.remarks);
                        
                        checkOptionExist('#purchase_request_cart_supply_id', response.purchase_request_cart_id, '');
                        checkOptionExist('#supply_id', response.part_id, '');
                        checkOptionExist('#quantity_supply_id', response.unit_id, '');
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
        case 'get receive details':
            let purchase_order_cart_id = sessionStorage.getItem('purchase_order_cart_id');
            let type = sessionStorage.getItem('type');
            
            $.ajax({
                url: 'controller/purchase-order-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    purchase_order_cart_id : purchase_order_cart_id, 
                    type : type, 
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
    }
}