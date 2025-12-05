(function($) {
    'use strict';

    $(function() {
        if($('#purchase-request-table').length){
            purchaseRequestTable('#purchase-request-table');
        }

        if($('#parts-item-table').length){
            partItemTable('#parts-item-table');
        }

        if($('#cancel-transaction-form').length){
            cancelPurchaseRequestForm();
        }

        if($('#draft-transaction-form').length){
            draftPurchaseRequestForm();
        }

        if($('#approve-transaction-form').length){
            approvePurchaseRequestForm();
        }

        if($('#part-item-form').length){
            partItemForm();
        }

        if($('#purchase-request-form').length){
            purchaseRequestForm();
        }

        $(document).on('click','#apply-filter',function() {
            purchaseRequestTable('#purchase-request-table');
        });
        
        if($('#purchase-request-id').length){
            displayDetails('get purchase request cart total');
            
            displayDetails('get purchase request details');

            document.getElementById('quantity').addEventListener('input', calculateTotals);
            document.getElementById('discount').addEventListener('input', calculateTotals);
            document.getElementById('add_on').addEventListener('input', calculateTotals);

            document.getElementById('overall_discount').addEventListener('input', calculateOverallTotals);
            document.getElementById('overall_discount_type').addEventListener('input', calculateOverallTotals);
        }

        $(document).on('click','#for-approval',function() {
            var purchase_request_id = $('#purchase-request-id').text();
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
                        url: 'controller/purchase-request-controller.php',
                        dataType: 'json',
                        data: {
                            purchase_request_id : purchase_request_id, 
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
                                    showNotification('Transaction For Validation Error', 'One of the parts added does not have enough quantity. Kindly check the added parts.', 'danger');
                                }
                                else if (response.noItem) {
                                    showNotification('Transaction For Validation Error', 'No parts added. Cannot be processed.', 'danger');
                                }
                                else if (response.jobOrder) {
                                    showNotification('Transaction For Validation Error', 'No job order or additional job order linked. Cannot be processed.', 'danger');
                                }
                                else if (response.tools) {
                                    showNotification('Transaction For Validation Error', 'The cost of the issuance is less than 5,000 please change the issuance for to repairs.', 'danger');
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
                        url: 'controller/purchase-request-controller.php',
                        dataType: 'json',
                        data: {
                            purchase_request_cart_id : purchase_request_cart_id, 
                            purchase_request_id : purchase_request_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete Part Item Success', 'The part item has been deleted successfully.', 'success');
                                reloadDatatable('#parts-item-table');
                                displayDetails('get purchase request cart total');
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
            discardCreate('purchase-request.php');
        });

        $(document).on('change','#product_id',function() {

            let product_id = $(this).val();
            let val = $('#customer_type').val();
            checkOptionExist('#issuance_for', '', '');            

            if (val === 'Internal' && product_id === '958') {
                $('.issuance-for-details').removeClass('d-none');
            } else {
                $('.issuance-for-details').addClass('d-none');
            }
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

function purchaseRequestForm(){
    $('#purchase-request-form').validate({
        rules: {
            purchase_request_type: {
                required: true
            },
            company_id: {
                required: true
            },
        },
        messages: {
            purchase_request_type: {
                required: 'Please choose the purchase request type'
            },
            company_id: {
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

function cancelPurchaseRequestForm(){
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
            var purchase_request_id = $('#purchase-request-id').text();
            const transaction = 'tag transaction as cancelled';
        
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
            var purchase_request_id = $('#purchase-request-id').text();
            const transaction = 'tag transaction as draft';
        
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
            var purchase_request_id = $('#purchase-request-id').text();
            const transaction = 'tag transaction as approved';
        
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
                        checkOptionExist('#issuance_for', response.issuance_for, '');

                        if(response.customer_type == 'Customer'){
                            checkOptionExist('#customer_id', response.customer_id, '');
                        }
                        else if(response.customer_type == 'Internal'){
                            checkOptionExist('#product_id', response.customer_id, '');
                        }
                        else if(response.customer_type == 'Department'){
                            checkOptionExist('#department_id', response.customer_id, '');
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
        case 'get purchase request cart details':
            const part_transaction_cart_id = $('#part_transaction_cart_id').val();
            
            $.ajax({
                url: 'controller/purchase-request-controller.php',
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
    }
}