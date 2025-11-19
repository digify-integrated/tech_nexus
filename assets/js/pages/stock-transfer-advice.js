(function($) {
    'use strict';

    $(function() {
        if($('#stock-transfer-advice-table').length){
            stockTransferAdviceTable('#stock-transfer-advice-table');
        }

        if($('#parts-item-table').length){
           partItemTable('#parts-item-table');
        }
        
        if($('#add-part-form').length){
            addPartsForm();
        }

        if($('#cancel-transaction-form').length){
            cancelTransactionForm();
        }

        if($('#draft-transaction-form').length){
            draftTransactionForm();
        }

        if($('#part-item-form').length){
            partItemForm();
        }

        if($('#stock-transfer-advice-form').length){
            stockTransferAdviceForm();
        }

        $(document).on('click','#add-part-from',function() {
            $('#part-from-source').val('From');
            if($('#add-part-table').length){
                addPartFromTable('#add-part-table');
            }
        });

        $(document).on('click','#add-part-to',function() {
            $('#part-from-source').val('To');
            if($('#add-part-table').length){
                addPartToTable('#add-part-table');
            }
        });

        $(document).on('click','#apply-filter',function() {
            stockTransferAdviceTable('#stock-transfer-advice-table');
        });
        
        if($('#stock-transfer-advice-id').length){            
            displayDetails('get stock transfer advice cart total');
            displayDetails('get stock transfer advice details');
        }

        $(document).on('click','.update-part-cart',function() {
            const stock_transfer_advice_cart_id = $(this).data('stock-transfer-advice-cart-id');

            $('#stock_transfer_advice_cart_id').val(stock_transfer_advice_cart_id);
            displayDetails('get stock transfer advice cart details');
        });

        $(document).on('click','#on-process',function() {
            var stock_transfer_advice_id = $('#stock-transfer-advice-id').text();
            const transaction = 'tag transaction as on process';
    
            Swal.fire({
                title: 'Confirm Stock Transfer Advice On-Process',
                text: 'Are you sure you want to tag this stock transfer advice as on-process?',
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
                        url: 'controller/stock-transfer-advice-controller.php',
                        dataType: 'json',
                        data: {
                            stock_transfer_advice_id : stock_transfer_advice_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Stock Transfer Advice On-Process Success', 'The stock transfer advice has been tagged as on-process successfully.', 'success');
                                window.location.reload();
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.cartQuantity) {
                                    showNotification('Stock Transfer Advice On-Process Error', 'Please add parts you want to transfer.', 'danger');
                                }
                                else if (response.cartPrice) {
                                    showNotification('Stock Transfer Advice On-Process Error', 'One of the parts assigned does not have a price.', 'danger');
                                }
                                else if (response.jobOrderTotal) {
                                    showNotification('Stock Transfer Advice On-Process Error', 'Please add a job order or additional job order.', 'danger');
                                }
                                else {
                                    showNotification('Stock Transfer Advice On-Process Error', response.message, 'danger');
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
            var stock_transfer_advice_id = $('#stock-transfer-advice-id').text();
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
                        url: 'controller/stock-transfer-advice-controller.php',
                        dataType: 'json',
                        data: {
                            stock_transfer_advice_id : stock_transfer_advice_id, 
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
            var stock_transfer_advice_id = $('#stock-transfer-advice-id').text();
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
                        url: 'controller/stock-transfer-advice-controller.php',
                        dataType: 'json',
                        data: {
                            stock_transfer_advice_id : stock_transfer_advice_id, 
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
            const stock_transfer_advice_cart_id = $(this).data('stock-transfer-advice-cart-id');
            var stock_transfer_advice_id = $('#stock-transfer-advice-id').text();
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
                        url: 'controller/stock-transfer-advice-controller.php',
                        dataType: 'json',
                        data: {
                            stock_transfer_advice_cart_id : stock_transfer_advice_cart_id, 
                            stock_transfer_advice_id : stock_transfer_advice_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete Part Item Success', 'The part item has been deleted successfully.', 'success');
                                    reloadDatatable('#parts-item-table');
                                displayDetails('get stock transfer advice cart total');
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

        $(document).on('click','#discard-create',function() {
            if($('#page-company').val() == '1'){
                discardCreate('supplies-transaction.php');
            }
            else if($('#page-company').val() == '2'){
                discardCreate('netruck-stock-transfer-advice.php');
            }
            else{
                discardCreate('stock-transfer-advice.php');
            }
        });

        $(document).on('click','#add-job-order',function() {
            $('#generate-job-order').val('job order');

            if($('#add-job-order-table').length){
                addJobOrderTable('#add-job-order-table');
            }
        });

        $(document).on('click','#add-internal-job-order',function() {
            $('#generate-job-order').val('internal job order');

            if($('#add-job-order-table').length){
                addJobOrderTable('#add-job-order-table');
            }
        });

        $(document).on('click','#add-additional-job-order',function() {
            $('#generate-additional-job-order').val('additional job order');

            if($('#add-additional-job-order-table').length){
                addAdditionalJobOrderTable('#add-additional-job-order-table');
            }
        });

        $(document).on('click','#add-internal-additional-job-order',function() {
            $('#generate-additional-job-order').val('internal additional job order');

            if($('#add-additional-job-order-table').length){
                addAdditionalJobOrderTable('#add-additional-job-order-table');
            }
        });

        if($('#job-order-table').length){
            jobOrderTable('#job-order-table');
        }

        if($('#internal-job-order-table').length){
            internalJobOrderTable('#internal-job-order-table');
        }

        if($('#additional-job-order-table').length){
            additionalJobOrderTable('#additional-job-order-table');
        }

        if($('#internal-additional-job-order-table').length){
            internalAdditionalJobOrderTable('#internal-additional-job-order-table');
        }

        if($('#add-job-order-form').length){
            addJobOrderForm();
        }

        if($('#add-additional-job-order-form').length){
            addAdditionalJobOrderForm();
        }

        $(document).on('click','.delete-job-order',function() {
            const stock_transfer_advice_job_order_id = $(this).data('stock-transfer-advice-job-order-id');
            var stock_transfer_advice_id = $('#stock-transfer-advice-id').text();
            const transaction = 'delete job order';
    
            Swal.fire({
                title: 'Confirm Job Order Unlink',
                text: 'Are you sure you want to unlink this job order?',
                icon: 'warning',
                showCancelButton: !0,
                confirmButtonText: 'Unlink',
                cancelButtonText: 'Cancel',
                confirmButtonClass: 'btn btn-danger mt-2',
                cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                buttonsStyling: !1
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        type: 'POST',
                        url: 'controller/stock-transfer-advice-controller.php',
                        dataType: 'json',
                        data: {
                            stock_transfer_advice_job_order_id : stock_transfer_advice_job_order_id, 
                            stock_transfer_advice_id : stock_transfer_advice_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Unlink Job Order Success', 'The job order has been unlinked successfully.', 'success');
                                reloadDatatable('#job-order-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Unlink Job Order Error', 'The job order does not exists.', 'danger');
                                    reloadDatatable('#job-order-table');
                                }
                                else {
                                    showNotification('Unlink Job Order Error', response.message, 'danger');
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

        $(document).on('click','.delete-internal-job-order',function() {
            const stock_transfer_advice_job_order_id = $(this).data('stock-transfer-advice-job-order-id');
            var stock_transfer_advice_id = $('#stock-transfer-advice-id').text();
            const transaction = 'delete internal job order';
    
            Swal.fire({
                title: 'Confirm Internal Job Order Unlink',
                text: 'Are you sure you want to unlink this internal job order?',
                icon: 'warning',
                showCancelButton: !0,
                confirmButtonText: 'Unlink',
                cancelButtonText: 'Cancel',
                confirmButtonClass: 'btn btn-danger mt-2',
                cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                buttonsStyling: !1
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        type: 'POST',
                        url: 'controller/stock-transfer-advice-controller.php',
                        dataType: 'json',
                        data: {
                            stock_transfer_advice_job_order_id : stock_transfer_advice_job_order_id, 
                            stock_transfer_advice_id : stock_transfer_advice_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Unlink Internal Job Order Success', 'The job order has been unlinked successfully.', 'success');
                                reloadDatatable('#internal-job-order-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Unlink Internal Job Order Error', 'The internal job order does not exists.', 'danger');
                                    reloadDatatable('#internal-job-order-table');
                                }
                                else {
                                    showNotification('Unlink Internal Job Order Error', response.message, 'danger');
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

        $(document).on('click','.delete-additional-job-order',function() {
            const stock_transfer_advice_additional_job_order_id = $(this).data('stock-transfer-advice-additional-job-order-id');
            var stock_transfer_advice_id = $('#stock-transfer-advice-id').text();
            const transaction = 'delete additional job order';
    
            Swal.fire({
                title: 'Confirm Additional Job Order Unlink',
                text: 'Are you sure you want to unlink this additional job order?',
                icon: 'warning',
                showCancelButton: !0,
                confirmButtonText: 'Unlink',
                cancelButtonText: 'Cancel',
                confirmButtonClass: 'btn btn-danger mt-2',
                cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                buttonsStyling: !1
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        type: 'POST',
                        url: 'controller/stock-transfer-advice-controller.php',
                        dataType: 'json',
                        data: {
                            stock_transfer_advice_additional_job_order_id : stock_transfer_advice_additional_job_order_id, 
                            stock_transfer_advice_id : stock_transfer_advice_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Unlink Additional Job Order Success', 'The additional job order has been unlinked successfully.', 'success');
                                reloadDatatable('#additional-job-order-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Unlink Additional Job Order Error', 'The additional job order does not exists.', 'danger');
                                    reloadDatatable('#additional-job-order-table');
                                }
                                else {
                                    showNotification('Unlink Additional Job Order Error', response.message, 'danger');
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

        $(document).on('click','.delete-internal-additional-job-order',function() {
            const stock_transfer_advice_additional_job_order_id = $(this).data('stock-transfer-advice-additional-job-order-id');
            var stock_transfer_advice_id = $('#stock-transfer-advice-id').text();
            const transaction = 'delete additional job order';
    
            Swal.fire({
                title: 'Confirm Additional Job Order Unlink',
                text: 'Are you sure you want to unlink this additional job order?',
                icon: 'warning',
                showCancelButton: !0,
                confirmButtonText: 'Unlink',
                cancelButtonText: 'Cancel',
                confirmButtonClass: 'btn btn-danger mt-2',
                cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                buttonsStyling: !1
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        type: 'POST',
                        url: 'controller/stock-transfer-advice-controller.php',
                        dataType: 'json',
                        data: {
                            stock_transfer_advice_additional_job_order_id : stock_transfer_advice_additional_job_order_id, 
                            stock_transfer_advice_id : stock_transfer_advice_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Unlink Additional Job Order Success', 'The additional job order has been unlinked successfully.', 'success');
                                reloadDatatable('#additional-job-order-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Unlink Additional Job Order Error', 'The additional job order does not exists.', 'danger');
                                    reloadDatatable('#additional-job-order-table');
                                }
                                else {
                                    showNotification('Unlink Additional Job Order Error', response.message, 'danger');
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

function stockTransferAdviceTable(datatable_name, buttons = false, show_all = false){
    const type = 'stock transfer advice table';
    var filter_created_date_start_date = $('#filter_created_date_start_date').val();
    var filter_created_date_end_date = $('#filter_created_date_end_date').val();
    var filter_on_process_date_start_date = $('#filter_on_process_date_start_date').val();
    var filter_on_process_date_end_date = $('#filter_on_process_date_end_date').val();
    var filter_completed_date_start_date = $('#filter_completed_date_start_date').val();
    var filter_completed_date_end_date = $('#filter_completed_date_end_date').val();

    var sta_status_filter = [];

    $('.sta-status-checkbox:checked').each(function() {
        sta_status_filter.push($(this).val());
    });

    var filter_sta_status = sta_status_filter.join(', ');

    var settings;

    const column = [
        { 'data' : 'REFERENCE_NO' },
        { 'data' : 'FROM' },
        { 'data' : 'TO' },
        { 'data' : 'STATUS' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': 'auto', 'aTargets': 0 },
        { 'width': 'auto', 'aTargets': 1 },
        { 'width': 'auto', 'aTargets': 2 },
        { 'width': 'auto', 'aTargets': 3 },
        { 'width': '15%','bSortable': false, 'aTargets': 4 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_stock_transfer_advice_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {'type' : type, 
                'filter_created_date_start_date' : filter_created_date_start_date, 
                'filter_created_date_end_date' : filter_created_date_end_date,
                'filter_on_process_date_start_date' : filter_on_process_date_start_date,
                'filter_on_process_date_end_date' : filter_on_process_date_end_date,
                'filter_completed_date_start_date' : filter_completed_date_start_date,
                'filter_completed_date_end_date' : filter_completed_date_end_date,
                'filter_sta_status' : filter_sta_status,
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

function addPartFromTable(datatable_name, buttons = false, show_all = false){
    var stock_transfer_advice_id = $('#stock-transfer-advice-id').text();
    const type = 'add part from table';
    var settings;

    const column = [ 
        { 'data' : 'PART' },
        { 'data' : 'ASSIGN' }
    ];

    const column_definition = [
        { 'width': 'auto', 'aTargets': 0 },
        { 'width': '10%', 'bSortable': false, 'aTargets': 1 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_stock_transfer_advice_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {'type' : type, 'stock_transfer_advice_id' : stock_transfer_advice_id},
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

function addPartToTable(datatable_name, buttons = false, show_all = false){
    var stock_transfer_advice_id = $('#stock-transfer-advice-id').text();
    const type = 'add part to table';
    var settings;

    const column = [ 
        { 'data' : 'PART' },
        { 'data' : 'ASSIGN' }
    ];

    const column_definition = [
        { 'width': 'auto', 'aTargets': 0 },
        { 'width': '10%', 'bSortable': false, 'aTargets': 1 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_stock_transfer_advice_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {'type' : type, 'stock_transfer_advice_id' : stock_transfer_advice_id},
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

function addJobOrderTable(datatable_name, buttons = false, show_all = false){
    var stock_transfer_advice_id = $('#stock-transfer-advice-id').text();
    var company = $('#page-company').val();
    var generate_job_order = $('#generate-job-order').val();
    const type = 'add job order table';
    var settings;

    const column = [ 
        { 'data' : 'CUSTOMER_NAME' },
        { 'data' : 'REFERENCE_ID' },
        { 'data' : 'JOB_ORDER' },
        { 'data' : 'ASSIGN' }
    ];

    const column_definition = [
        { 'width': '30%', 'aTargets': 0 },
        { 'width': '30%', 'aTargets': 1 },
        { 'width': '30%', 'aTargets': 2 },
        { 'width': '10%', 'bSortable': false, 'aTargets': 3 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_stock_transfer_advice_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {'type' : type, 'stock_transfer_advice_id' : stock_transfer_advice_id, 'company' : company, 'generate_job_order' : generate_job_order},
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

function jobOrderTable(datatable_name, buttons = false, show_all = false){
    var stock_transfer_advice_id = $('#stock-transfer-advice-id').text();
    const type = 'job order table';
    var settings;

    const column = [ 
        { 'data' : 'OS_NUMBER' },
        { 'data' : 'JOB_ORDER' },
        { 'data' : 'CONTRACTOR' },
        { 'data' : 'WORK_CENTER' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': 'auto', 'aTargets': 0 },
        { 'width': 'auto', 'aTargets': 1 },
        { 'width': 'auto', 'aTargets': 2 },
        { 'width': 'auto', 'aTargets': 3 },
        { 'width': '10%', 'bSortable': false, 'aTargets': 4 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_stock_transfer_advice_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {'type' : type, 'stock_transfer_advice_id' : stock_transfer_advice_id},
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

function internalJobOrderTable(datatable_name, buttons = false, show_all = false){
    var stock_transfer_advice_id = $('#stock-transfer-advice-id').text();
    const type = 'internal job order table';
    var settings;

    const column = [ 
        { 'data' : 'TYPE' },
        { 'data' : 'OS_NUMBER' },
        { 'data' : 'JOB_ORDER' },
        { 'data' : 'CONTRACTOR' },
        { 'data' : 'WORK_CENTER' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': 'auto', 'aTargets': 0 },
        { 'width': 'auto', 'aTargets': 1 },
        { 'width': 'auto', 'aTargets': 2 },
        { 'width': 'auto', 'aTargets': 3 },
        { 'width': 'auto', 'aTargets': 4 },
        { 'width': '10%', 'bSortable': false, 'aTargets': 5 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_stock_transfer_advice_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {'type' : type, 'stock_transfer_advice_id' : stock_transfer_advice_id},
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

function additionalJobOrderTable(datatable_name, buttons = false, show_all = false){
    var stock_transfer_advice_id = $('#stock-transfer-advice-id').text();
    const type = 'additional job order table';
    var settings;

    const column = [ 
        { 'data' : 'OS_NUMBER' },
        { 'data' : 'JOB_ORDER' },
        { 'data' : 'CONTRACTOR' },
        { 'data' : 'WORK_CENTER' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': 'auto', 'aTargets': 0 },
        { 'width': 'auto', 'aTargets': 1 },
        { 'width': 'auto', 'aTargets': 2 },
        { 'width': 'auto', 'aTargets': 3 },
        { 'width': '10%', 'bSortable': false, 'aTargets': 4 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_stock_transfer_advice_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {'type' : type, 'stock_transfer_advice_id' : stock_transfer_advice_id},
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

function internalAdditionalJobOrderTable(datatable_name, buttons = false, show_all = false){
    var stock_transfer_advice_id = $('#stock-transfer-advice-id').text();
    const type = 'internal additional job order table';
    var settings;

    const column = [ 
        { 'data' : 'TYPE' },
        { 'data' : 'OS_NUMBER' },
        { 'data' : 'JOB_ORDER' },
        { 'data' : 'CONTRACTOR' },
        { 'data' : 'WORK_CENTER' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': 'auto', 'aTargets': 0 },
        { 'width': 'auto', 'aTargets': 1 },
        { 'width': 'auto', 'aTargets': 2 },
        { 'width': 'auto', 'aTargets': 3 },
        { 'width': 'auto', 'aTargets': 4 },
        { 'width': '10%', 'bSortable': false, 'aTargets': 5 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_stock_transfer_advice_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {'type' : type, 'stock_transfer_advice_id' : stock_transfer_advice_id},
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

function addAdditionalJobOrderTable(datatable_name, buttons = false, show_all = false){
    var stock_transfer_advice_id = $('#stock-transfer-advice-id').text();
    var company = $('#page-company').val();
    var generate_job_order = $('#generate-additional-job-order').val();
    const type = 'add additional job order table';
    var settings;

    const column = [ 
        { 'data' : 'CUSTOMER_NAME' },
        { 'data' : 'REFERENCE_ID' },
        { 'data' : 'JOB_ORDER' },
        { 'data' : 'ASSIGN' }
    ];

    const column_definition = [
        { 'width': '30%', 'aTargets': 0 },
        { 'width': '30%', 'aTargets': 1 },
        { 'width': '30%', 'aTargets': 2 },
        { 'width': '10%', 'bSortable': false, 'aTargets': 3 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_stock_transfer_advice_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {'type' : type, 'stock_transfer_advice_id' : stock_transfer_advice_id, 'company' : company, 'generate_job_order' : generate_job_order},
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

function partItemTable(datatable_name, buttons = false, show_all = false){
    var stock_transfer_advice_id = $('#stock-transfer-advice-id').text();
    const type = 'part item table';
    var settings;

    const column = [ 
        { 'data' : 'ACTION' },
        { 'data' : 'TRANSFERRED_FROM' },
        { 'data' : 'TRANSFERRED_TO' },
        { 'data' : 'PART' },
        { 'data' : 'QUANTITY' },
        { 'data' : 'PRICE' }
    ];

    const column_definition = [
        { 'width': 'auto', 'bSortable': false, 'aTargets': 0 },
        { 'width': 'auto', 'aTargets': 1 },
        { 'width': 'auto', 'aTargets': 2 },
        { 'width': 'auto', 'aTargets': 3 },
        { 'width': 'auto', 'aTargets': 4 },
        { 'width': 'auto', 'aTargets': 5 },
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_stock_transfer_advice_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {'type' : type, 'stock_transfer_advice_id' : stock_transfer_advice_id},
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

$.validator.addMethod("notEqualTo", function (value, element, param) {
    return this.optional(element) || value !== $(param).val();
}, "Transferred from and to cannot be the same");

function stockTransferAdviceForm(){
    $('#stock-transfer-advice-form').validate({
        rules: {
            transferred_from: {
                required: true
            },
            sta_type: {
                required: true
            },
            transferred_to: {
                required: true,
                notEqualTo: "#transferred_from"
            },
        },
        messages: {
            transferred_from: {
                required: 'Please choose the customer'
            },
            sta_type: {
                required: 'Please choose the sta type'
            },
            transferred_to: {
                required: 'Please choose the customer',
                notEqualTo: "Transferred from and transferred to cannot be the same"
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
            const stock_transfer_advice_id = $('#stock-transfer-advice-id').text();
            const transaction = 'save stock transfer advice';
            const company_id = $('#page-company').val();
        
            $.ajax({
                type: 'POST',
                url: 'controller/stock-transfer-advice-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&stock_transfer_advice_id=' + stock_transfer_advice_id + '&company_id=' + company_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-data');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert Transaction Success' : 'Update Transaction Success';
                        const notificationDescription = response.insertRecord ? 'The transaction has been inserted successfully.' : 'The transaction has been updated successfully.';
                        
                        setNotification(notificationMessage, notificationDescription, 'success');

                        if(company_id == '1'){
                             window.location = 'supplies-transaction.php?id=' + response.stockTransferAdviceID;
                        }
                        else if(company_id == '2'){
                             window.location = 'netruck-stock-transfer-advice.php?id=' + response.stockTransferAdviceID;
                        }
                        else{
                             window.location = 'stock-transfer-advice.php?id=' + response.stockTransferAdviceID;
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

function addPartsForm(){
    $('#add-part-form').validate({
        submitHandler: function(form) {
          var stock_transfer_advice_id = $('#stock-transfer-advice-id').text();
          var part_from_source = $('#part-from-source').val();
            const transaction = 'add stock transfer advice item';

            var part_id = [];

            $('.assign-part').each(function(){
                if ($(this).is(':checked')){  
                    part_id.push(this.value);  
                }
            });

            $.ajax({
                type: 'POST',
                url: 'controller/stock-transfer-advice-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&stock_transfer_advice_id=' + stock_transfer_advice_id + '&part_from_source=' + part_from_source + '&part_id=' + part_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-add-part');
                },
                success: function(response) {
                    if (response.success) {
                        showNotification('Add Parts Success', 'The parts has been added successfully.', 'success');
                        displayDetails('get stock transfer advice cart total');
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

function addJobOrderForm(){
    $('#add-job-order-form').validate({
        submitHandler: function(form) {
          var stock_transfer_advice_id = $('#stock-transfer-advice-id').text();
          var generate_job_order = $('#generate-job-order').val();
            const transaction = 'add job order';

            var job_order_id = [];

            $('.assign-job-order').each(function(){
                if ($(this).is(':checked')){  
                    job_order_id.push(this.value);  
                }
            });

            $.ajax({
                type: 'POST',
                url: 'controller/stock-transfer-advice-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&stock_transfer_advice_id=' + stock_transfer_advice_id + '&job_order_id=' + job_order_id + '&generate_job_order=' + generate_job_order,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-add-job-order');
                },
                success: function(response) {
                    if (response.success) {
                        showNotification('Link Job Order Success', 'The job order has been linked successfully.', 'success');
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
                    enableFormSubmitButton('submit-add-job-order', 'Submit');
                    $('#add-job-order-offcanvas').offcanvas('hide');
                    reloadDatatable('#job-order-table');
                }
            });
            return false;
        }
    });
}

function addAdditionalJobOrderForm(){
    $('#add-additional-job-order-form').validate({
        submitHandler: function(form) {
          var stock_transfer_advice_id = $('#stock-transfer-advice-id').text();
          var generate_job_order = $('#generate-additional-job-order').val();
            const transaction = 'add additional job order';

            var additional_job_order_id = [];

            $('.assign-additional-job-order').each(function(){
                if ($(this).is(':checked')){  
                    additional_job_order_id.push(this.value);  
                }
            });

            $.ajax({
                type: 'POST',
                url: 'controller/stock-transfer-advice-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&stock_transfer_advice_id=' + stock_transfer_advice_id + '&additional_job_order_id=' + additional_job_order_id + '&generate_job_order=' + generate_job_order,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-additional-job-order');
                },
                success: function(response) {
                    if (response.success) {
                        showNotification('Link Additional Job Order Success', 'The additional job order has been linked successfully.', 'success');
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
                    enableFormSubmitButton('submit-additional-job-order', 'Submit');
                    $('#add-additional-job-order-offcanvas').offcanvas('hide');
                    reloadDatatable('#additional-job-order-table');
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
            var stock_transfer_advice_id = $('#stock-transfer-advice-id').text();
            const transaction = 'tag transaction as cancelled';
        
            $.ajax({
                type: 'POST',
                url: 'controller/stock-transfer-advice-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&stock_transfer_advice_id=' + stock_transfer_advice_id,
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

function draftTransactionForm(){
    $('#draft-transaction-form').validate({
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
            var stock_transfer_advice_id = $('#stock-transfer-advice-id').text();
            const transaction = 'tag transaction as draft';
        
            $.ajax({
                type: 'POST',
                url: 'controller/stock-transfer-advice-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&stock_transfer_advice_id=' + stock_transfer_advice_id,
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

function partItemForm(){
  $('#part-item-form').validate({
        rules: {
            part_price: {
                required: true,
            },
            quantity: {
                required: true,
            },
        },
        messages: {
            part_price: {
                required: 'Please enter the price'
            },
            quantity: {
                required: 'Please enter the quantity'
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
                url: 'controller/stock-transfer-advice-controller.php',
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
                        displayDetails('get stock transfer advice cart total');
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
        case 'get stock transfer advice details':
            var stock_transfer_advice_id = $('#stock-transfer-advice-id').text();
            
            $.ajax({
                url: 'controller/stock-transfer-advice-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    stock_transfer_advice_id : stock_transfer_advice_id, 
                    transaction : transaction
                },
                success: function(response) {
                    if (response.success) {
                        $('#reference_no').val(response.reference_no);
                        $('#remarks').val(response.remarks);
                        
                        checkOptionExist('#transferred_from', response.transferred_from, '');
                        checkOptionExist('#transferred_to', response.transferred_to, '');
                        checkOptionExist('#sta_type', response.sta_type, '');
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
        case 'get stock transfer advice cart total':
            var stock_transfer_advice_id = $('#stock-transfer-advice-id').text();
            
            $.ajax({
                url: 'controller/stock-transfer-advice-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    stock_transfer_advice_id : stock_transfer_advice_id, 
                    transaction : transaction
                },
                success: function(response) {
                    if (response.success) {
                        $('#total_summary_from').text(response.total_summary_from + ' PHP');
                        $('#total_summary_to').text(response.total_summary_to + ' PHP');
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
        case 'get stock transfer advice cart details':
            const stock_transfer_advice_cart_id = $('#stock_transfer_advice_cart_id').val();
            
            $.ajax({
                url: 'controller/stock-transfer-advice-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    stock_transfer_advice_cart_id : stock_transfer_advice_cart_id, 
                    transaction : transaction
                },
                success: function(response) {
                    if (response.success) {
                        $('#quantity').val(response.quantity);
                        $('#part_id').val(response.part_id);
                        $('#part_price').val(response.price);
                        $('#part_name').val(response.part_name);
                        $('#item_remarks').val(response.remarks);
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
            });
            break;
    }
}