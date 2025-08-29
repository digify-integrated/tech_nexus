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

        if($('#draft-transaction-form').length){
            draftTransactionForm();
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

        if($('#parts-transaction-form').length){
            partsTransactionForm();
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
            
            displayDetails('get parts transaction details');

            document.getElementById('quantity').addEventListener('input', calculateTotals);
            document.getElementById('discount').addEventListener('input', calculateTotals);
            document.getElementById('add_on').addEventListener('input', calculateTotals);

            document.getElementById('overall_discount').addEventListener('input', calculateOverallTotals);
            document.getElementById('overall_discount_type').addEventListener('input', calculateOverallTotals);
        }

        $(document).on('change','#discount_type',function() {
            calculateTotals();
        });

         $(document).on('change','#overall_discount_type',function() {
            calculateOverallTotals();
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
                                else if (response.noItem) {
                                    showNotification('Transaction On-Process Error', 'No parts added. Cannot be processed.', 'danger');
                                }
                                else if (response.partQuantityExceed) {
                                    showNotification('Transaction On-Process Error', 'One of the parts added does not have enough quantity. Kindly check the added parts.', 'danger');
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
                title: 'Confirm Transaction For Validation',
                text: 'Are you sure you want to tag this transaction as for validation?',
                icon: 'warning',
                showCancelButton: !0,
                confirmButtonText: 'For Validation',
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
                                setNotification('Transaction For Validation Success', 'The transaction has been tagged as for validation successfully.', 'success');
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
                                else if (response.noItem) {
                                    showNotification('Transaction For Validation Error', 'No parts added. Cannot be processed.', 'danger');
                                }
                                else if (response.jobOrder) {
                                    showNotification('Transaction For Validation Error', 'No job order or additional job order linked. Cannot be processed.', 'danger');
                                }
                                else if (response.cartQuantity) {
                                    showNotification('Transaction For Validation Error', 'One of the parts added does not have enough quantity. Kindly check the added parts.', 'danger');
                                }
                                else {
                                    showNotification('Transaction For Validation Error', response.message, 'danger');
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
            var parts_transaction_id = $('#parts-transaction-id').text();
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
                        url: 'controller/parts-transaction-controller.php',
                        dataType: 'json',
                        data: {
                            parts_transaction_id : parts_transaction_id, 
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

        $(document).on('click','#discard-create',function() {
            if($('#page-company').val() == '2'){
                discardCreate('netruck-parts-transaction.php');
            }
            else{
                discardCreate('parts-transaction.php');
            }
        });

        $(document).on('change','#customer_type',function() {
            checkOptionExist('#misc_id', '', '');
            checkOptionExist('#product_id', '', '');
            checkOptionExist('#customer_id', '', '');

            if($(this).val() == 'Customer'){
                $('#misc-select').addClass('d-none');
                $('#internal-select').addClass('d-none');
                $('#internal-label').addClass('d-none');

                $('#customer-select').removeClass('d-none');
                $('#customer-label').removeClass('d-none');
            }
            else if($(this).val() == 'Miscellaneous'){
                $('#customer-select').addClass('d-none');
                $('#internal-select').addClass('d-none');
                $('#internal-label').addClass('d-none');

                $('#misc-select').removeClass('d-none');
                $('#customer-label').removeClass('d-none');
            }
            else{
                $('#customer-select').addClass('d-none');
                $('#misc-select').addClass('d-none');
                $('#customer-label').addClass('d-none');

                $('#internal-select').removeClass('d-none');
                $('#internal-label').removeClass('d-none');
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
            const parts_transaction_job_order_id = $(this).data('parts-transaction-job-order-id');
            var parts_transaction_id = $('#parts-transaction-id').text();
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
                        url: 'controller/parts-transaction-controller.php',
                        dataType: 'json',
                        data: {
                            parts_transaction_job_order_id : parts_transaction_job_order_id, 
                            parts_transaction_id : parts_transaction_id, 
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
            const parts_transaction_job_order_id = $(this).data('parts-transaction-job-order-id');
            var parts_transaction_id = $('#parts-transaction-id').text();
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
                        url: 'controller/parts-transaction-controller.php',
                        dataType: 'json',
                        data: {
                            parts_transaction_job_order_id : parts_transaction_job_order_id, 
                            parts_transaction_id : parts_transaction_id, 
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
            const parts_transaction_additional_job_order_id = $(this).data('parts-transaction-additional-job-order-id');
            var parts_transaction_id = $('#parts-transaction-id').text();
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
                        url: 'controller/parts-transaction-controller.php',
                        dataType: 'json',
                        data: {
                            parts_transaction_additional_job_order_id : parts_transaction_additional_job_order_id, 
                            parts_transaction_id : parts_transaction_id, 
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

        $(document).on('click','.delete-internal-job-order',function() {
            const parts_transaction_additional_job_order_id = $(this).data('parts-transaction-additional-job-order-id');
            var parts_transaction_id = $('#parts-transaction-id').text();
            const transaction = 'delete internal additional job order';
    
            Swal.fire({
                title: 'Confirm Internal Additional Job Order Unlink',
                text: 'Are you sure you want to unlink this internal additional job order?',
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
                        url: 'controller/parts-transaction-controller.php',
                        dataType: 'json',
                        data: {
                            parts_transaction_additional_job_order_id : parts_transaction_additional_job_order_id, 
                            parts_transaction_id : parts_transaction_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Unlink Internal Additional Job Order Success', 'The internal additional job order has been unlinked successfully.', 'success');
                                reloadDatatable('#internal-additional-job-order-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Unlink Internal Additional Job Order Error', 'The internal additional job order does not exists.', 'danger');
                                    reloadDatatable('#internal-additional-job-order-table');
                                }
                                else {
                                    showNotification('Unlink Internal Additional Job Order Error', response.message, 'danger');
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

function partsTransactionTable(datatable_name, buttons = false, show_all = false){
    const type = 'parts transaction table';
    var filter_transaction_date_start_date = $('#filter_transaction_date_start_date').val();
    var filter_transaction_date_end_date = $('#filter_transaction_date_end_date').val();
    var filter_approval_date_start_date = $('#filter_approval_date_start_date').val();
    var filter_approval_date_end_date = $('#filter_approval_date_end_date').val();
    var company = $('#page-company').val();

    var transaction_status_filter = [];

    $('.transaction-status-checkbox:checked').each(function() {
        transaction_status_filter.push($(this).val());
    });

    var filter_transaction_status = transaction_status_filter.join(', ');

    var settings;

    const column = [
        { 'data' : 'TRANSACTION_ID' },
        { 'data' : 'PRODUCT' },
        { 'data' : 'TOTAL_AMOUNT' },
        { 'data' : 'TRANSACTION_DATE' },
        { 'data' : 'ISSUANCE_DATE' },
        { 'data' : 'STATUS' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': 'auto', 'aTargets': 0 },
        { 'width': 'auto', 'aTargets': 1 },
        { 'width': 'auto', 'aTargets': 2 },
        { 'width': 'auto', 'type': 'date', 'aTargets': 3 },
        { 'width': 'auto', 'aTargets': 4 },
        { 'width': 'auto', 'aTargets': 5 },
        { 'width': '15%','bSortable': false, 'aTargets': 6 }
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
                'company' : company,
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
    var company = $('#page-company').val();
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
            'data': {'type' : type, 'parts_transaction_id' : parts_transaction_id, 'company' : company},
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
    var parts_transaction_id = $('#parts-transaction-id').text();
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
            'url' : 'view/_parts_transaction_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {'type' : type, 'parts_transaction_id' : parts_transaction_id, 'company' : company, 'generate_job_order' : generate_job_order},
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
    var parts_transaction_id = $('#parts-transaction-id').text();
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

function internalJobOrderTable(datatable_name, buttons = false, show_all = false){
    var parts_transaction_id = $('#parts-transaction-id').text();
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

function additionalJobOrderTable(datatable_name, buttons = false, show_all = false){
    var parts_transaction_id = $('#parts-transaction-id').text();
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

function internalAdditionalJobOrderTable(datatable_name, buttons = false, show_all = false){
    var parts_transaction_id = $('#parts-transaction-id').text();
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

function addAdditionalJobOrderTable(datatable_name, buttons = false, show_all = false){
    var parts_transaction_id = $('#parts-transaction-id').text();
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
            'url' : 'view/_parts_transaction_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {'type' : type, 'parts_transaction_id' : parts_transaction_id, 'company' : company, 'generate_job_order' : generate_job_order},
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
    var parts_transaction_id = $('#parts-transaction-id').text();
    const type = 'part item table';
    var settings;

    const column = [ 
        { 'data' : 'ACTION' },
        { 'data' : 'ORDER' },
        { 'data' : 'PART' },
        { 'data' : 'PRICE' },
        { 'data' : 'QUANTITY' },
        { 'data' : 'AVAILABLE_STOCK' },
        { 'data' : 'ADD_ON' },
        { 'data' : 'DISCOUNT' },
        { 'data' : 'DISCOUNT_TOTAL' },
        { 'data' : 'SUBTOTAL' },
        { 'data' : 'TOTAL' },
    ];

    const column_definition = [
        { 'width': 'auto', 'bSortable': false, 'aTargets': 0 },
        { 'width': 'auto', 'aTargets': 1 },
        { 'width': 'auto', 'aTargets': 2 },
        { 'width': 'auto', 'aTargets': 3 },
        { 'width': 'auto', 'aTargets': 4 },
        { 'width': 'auto', 'aTargets': 5 },
        { 'width': 'auto', 'aTargets': 6 },
        { 'width': 'auto', 'aTargets': 7 },
        { 'width': 'auto', 'aTargets': 8 },
        { 'width': 'auto', 'aTargets': 9 },
        { 'width': 'auto', 'aTargets': 10 },
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

function partsTransactionForm(){
    $('#parts-transaction-form').validate({
        rules: {
            customer_id: {
                required: {
                    depends: function(element) {
                        return $("select[name='customer_type']").val() === 'Customer';
                    }
                }
            },
            misc_id: {
                required: {
                    depends: function(element) {
                        return $("select[name='customer_type']").val() === 'Miscellaneous';
                    }
                }
            },
            product_id: {
                required: {
                    depends: function(element) {
                        return $("select[name='customer_type']").val() === 'Internal';
                    }
                }
            },
            customer_ref_id: {
                required: true
            },
            request_by: {
                required: true
            },
        },
        messages: {
            customer_id: {
                required: 'Please choose the customer'
            },
            misc_id: {
                required: 'Please choose the customer'
            },
            product_id: {
                required: 'Please choose the product'
            },
            customer_ref_id: {
                required: 'Please choose the customer reference'
            },
            request_by: {
                required: 'Please enter the request by'
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
            const parts_transaction_id = $('#parts-transaction-id').text();
            const transaction = 'save parts transaction';
            const company_id = $('#page-company').val();
        
            $.ajax({
                type: 'POST',
                url: 'controller/parts-transaction-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&parts_transaction_id=' + parts_transaction_id + '&company_id=' + company_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-data');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert Transaction Success' : 'Update Transaction Success';
                        const notificationDescription = response.insertRecord ? 'The transaction has been inserted successfully.' : 'The transaction has been updated successfully.';
                        
                        setNotification(notificationMessage, notificationDescription, 'success');

                        if(company_id == '2'){
                             window.location = 'netruck-parts-transaction.php?id=' + response.partsTransactionID;
                        }
                        else{
                             window.location = 'parts-transaction.php?id=' + response.partsTransactionID;
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

function addJobOrderForm(){
    $('#add-job-order-form').validate({
        submitHandler: function(form) {
          var parts_transaction_id = $('#parts-transaction-id').text();
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
                url: 'controller/parts-transaction-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&parts_transaction_id=' + parts_transaction_id + '&job_order_id=' + job_order_id + '&generate_job_order=' + generate_job_order,
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
          var parts_transaction_id = $('#parts-transaction-id').text();
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
                url: 'controller/parts-transaction-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&parts_transaction_id=' + parts_transaction_id + '&additional_job_order_id=' + additional_job_order_id + '&generate_job_order=' + generate_job_order,
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
            var parts_transaction_id = $('#parts-transaction-id').text();
            const transaction = 'tag transaction as draft';
        
            $.ajax({
                type: 'POST',
                url: 'controller/parts-transaction-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&parts_transaction_id=' + parts_transaction_id,
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

function approveTransactionForm(){
    $('#approve-transaction-form').validate({
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
                required: 'Please enter the quantity',
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
        case 'get parts transaction details':
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
                        $('#issuance_no').val(response.issuance_no);
                        $('#issuance_date').val(response.issuance_date);
                        $('#reference_number').val(response.reference_number);
                        $('#reference_date').val(response.reference_date);
                        $('#remarks').val(response.remarks);
                        $('#request_by').val(response.request_by);
                        $('#overall_discount').val(response.discount);
                        $('#overall_discount_total').val(response.overall_total);
                        $('#total-overall-discount-summary').text(response.addOnDiscount);
                        
                        checkOptionExist('#customer_type', response.customer_type, '');
                        checkOptionExist('#company_id', response.company_id, '');
                        checkOptionExist('#overall_discount_type', response.discount_type, '');
                        checkOptionExist('#customer_ref_id', response.customer_ref_id, '');

                        if(response.customer_type == 'Customer'){
                            checkOptionExist('#customer_id', response.customer_id, '');
                        }
                        else if(response.customer_type == 'Internal'){
                            checkOptionExist('#product_id', response.customer_id, '');
                        }
                        else{
                            checkOptionExist('#misc_id', response.customer_id, '');
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
                },
                complete: function (){
                    calculateOverallTotals();
                }
            });
            break;
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
                        $('#remarks').val(response.remarks);
                        $('#part_price').val(response.price);
                        
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
                        $('#subtotal-reference').val(response.subtotal_reference);
                        $('#sub-total-summary').text(response.subTotal);
                        $('#total-discount-summary').text(response.discountAmount);
                        $('#item-total-summary').text(response.total);
                        $('#add-on-total-summary').text(response.addOn);
                        $('#total-summary').text(response.overallTotal);
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
  const quantity = Math.max(parseFloat(quantityInput.value) || 0, 0);
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

function calculateOverallTotals() {
  const subtotal_reference = document.getElementById('subtotal-reference');
  const overall_discount = document.getElementById('overall_discount');
  const overall_discount_type = document.getElementById('overall_discount_type');
  const overall_discount_total = document.getElementById('overall_discount_total');
  const total_summary = document.getElementById('total-summary');

  // Parse values safely
  const subtotal = Math.max(parseFloat(subtotal_reference?.value) || 0, 0);
  const discountValue = Math.max(parseFloat(overall_discount?.value) || 0, 0);
  const discountType = overall_discount_type?.value;

  // Calculate discount amount
  let discountAmount = 0;
  if (discountType === 'Percentage') {
    discountAmount = subtotal * (discountValue / 100);
  } else if (discountType === 'Amount') {
    discountAmount = discountValue;
  }

  // Prevent discount from exceeding subtotal
  discountAmount = Math.min(discountAmount, subtotal);

  // Update the discount total field
  if (overall_discount_total) {
    overall_discount_total.value = discountAmount.toFixed(2);
  }

  // Calculate and display the final total summary
  const finalTotal = subtotal - discountAmount;
  if (total_summary) {
    total_summary.textContent = `${finalTotal.toFixed(2)} PHP`;
  }
}

