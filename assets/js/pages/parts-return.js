(function($) {
    'use strict';

    $(function() {

         if($('#parts-return-table').length){
            partsReturnTable('#parts-return-table');
        }

        $(document).on('click','#apply-filter',function() {
            partsReturnTable('#parts-return-table');
        });
       
        if($('#parts-return-form').length){
            partsReturnForm();
        }

        if($('#add-part-form').length){
            addPartsForm();
        }

         if($('#part-item-form').length){
            partItemForm();
        }

         if($('#approve-return-form').length){
            approveReturnForm();
        }

        if($('#parts-return-id').length){
            displayDetails('get parts return details');
            displayDetails('get parts return cart total');
        }

        if($('#parts-item-table').length){
            partItemTable('#parts-item-table');
        }

        $(document).on('click','#add-part',function() {
            if($('#add-part-table').length){
                addPartTable('#add-part-table');
            }
        });

        $(document).on('click','.update-part-cart',function() {
            const parts_return_cart_id = $(this).data('parts-return-cart-id');

            $('#part_return_cart_id').val(parts_return_cart_id);
            displayDetails('get parts return cart details');
        });

        if($('#draft-return-form').length){
            draftReturnForm();
        }

        $(document).on('click','.delete-part-cart',function() {
            const parts_return_cart_id = $(this).data('parts-return-cart-id');
            var parts_return_id = $('#parts-return-id').text();
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
                        url: 'controller/parts-return-controller.php',
                        dataType: 'json',
                        data: {
                            parts_return_cart_id : parts_return_cart_id, 
                            parts_return_id : parts_return_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete Part Item Success', 'The part item has been deleted successfully.', 'success');
                                reloadDatatable('#parts-item-table');
                                displayDetails('get parts return cart total');
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
                discardCreate('supplies-return.php');
            }
            else if($('#page-company').val() == '2'){
                discardCreate('netruck-parts-return.php');
            }
            else{
                discardCreate('parts-return.php');
            }
        });

        $(document).on('click','#for-approval',function() {
            var parts_return_id = $('#parts-return-id').text();
            const transaction = 'tag return as for approval';
    
            Swal.fire({
                title: 'Confirm Return For Validation',
                text: 'Are you sure you want to tag this return as for validation?',
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
                        url: 'controller/parts-return-controller.php',
                        dataType: 'json',
                        data: {
                            parts_return_id : parts_return_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Return For Validation Success', 'The return has been tagged as for validation successfully.', 'success');
                                window.location.reload();
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.partQuantityExceed) {
                                    showNotification('Return For Validation Error', 'One of the parts added does not have enough quantity. Kindly check the added parts.', 'danger');
                                }
                                else if (response.noItem) {
                                    showNotification('Return For Validation Error', 'No parts added. Cannot be processed.', 'danger');
                                }
                                else if (response.jobOrder) {
                                    showNotification('Return For Validation Error', 'No job order or additional job order linked. Cannot be processed.', 'danger');
                                }
                                else if (response.cartQuantity) {
                                    showNotification('Return For Validation Error', 'One of the parts added does not have enough quantity. Kindly check the added parts.', 'danger');
                                }
                                else {
                                    showNotification('Return For Validation Error', response.message, 'danger');
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
            var parts_return_id = $('#parts-return-id').text();
            const transaction = 'tag return as released';
    
            Swal.fire({
                title: 'Confirm Return Release',
                text: 'Are you sure you want to tag this return as released?',
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
                        url: 'controller/parts-return-controller.php',
                        dataType: 'json',
                        data: {
                            parts_return_id : parts_return_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Return Released Success', 'The return has been tagged as released successfully.', 'success');
                                window.location.reload();
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.partQuantityExceed) {
                                    showNotification('Return Released Error', 'One of the parts added does not have enough quantity. Kindly check the added parts.', 'danger');
                                }
                                else if (response.cartQuantity) {
                                    showNotification('Return Released Error', 'One of the parts added does not have enough quantity. Kindly check the added parts.', 'danger');
                                }
                                else {
                                    showNotification('Return Released Error', response.message, 'danger');
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

function partsReturnTable(datatable_name, buttons = false, show_all = false){
    const type = 'parts return table';
    var filter_transaction_date_start_date = $('#filter_transaction_date_start_date').val();
    var filter_transaction_date_end_date = $('#filter_transaction_date_end_date').val();
    var filter_approval_date_start_date = $('#filter_approval_date_start_date').val();
    var filter_approval_date_end_date = $('#filter_approval_date_end_date').val();
    var company = $('#page-company').val();

    var return_status_filter = [];

    $('.return-status-checkbox:checked').each(function() {
        return_status_filter.push($(this).val());
    });

    var filter_return_status = return_status_filter.join(', ');

    var settings;

    const column = [
        { 'data' : 'REF_NO' },
        { 'data' : 'SUPPLIER' },
        { 'data' : 'REF_INVOICE_NUMBER' },
        { 'data' : 'REF_PO_NUMBER' },
        { 'data' : 'STATUS' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': 'auto', 'aTargets': 0 },
        { 'width': 'auto', 'aTargets': 1 },
        { 'width': 'auto', 'aTargets': 2 },
        { 'width': 'auto', 'aTargets': 3 },
        { 'width': 'auto', 'aTargets': 4 },
        { 'width': '15%','bSortable': false, 'aTargets': 5 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_parts_return_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {'type' : type, 
                'filter_transaction_date_start_date' : filter_transaction_date_start_date, 
                'filter_transaction_date_end_date' : filter_transaction_date_end_date,
                'filter_approval_date_start_date' : filter_approval_date_start_date,
                'filter_approval_date_end_date' : filter_approval_date_end_date,
                'filter_return_status' : filter_return_status,
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

function partsReturnForm(){
    $('#parts-return-form').validate({
        rules: {
            supplier_id: {
                required: true
            },
            return_type: {
                required: true
            },
            purchase_date: {
                required: true
            },
            ref_invoice_number: {
                required: true
            },
            ref_po_number: {
                required: true
            },
            ref_po_date: {
                required: true
            },

        },
        messages: {
            supplier_id: {
                required: 'Please choose the supplier'
            },
            return_type: {
                required: 'Please choose the return type'
            },
            purchase_date: {
                required: 'Please choose the purchase date'
            },
            ref_invoice_number: {
                required: 'Please enter the reference invoice number'
            },
            ref_po_number: {
                required: 'Please enter the reference PO number'
            },
            ref_po_date: {
                required: 'Please choose the reference PO date'
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
            const parts_return_id = $('#parts-return-id').text();
            const company_id = $('#page-company').val();
            const transaction = 'save parts return';
        
            $.ajax({
                type: 'POST',
                url: 'controller/parts-return-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&parts_return_id=' + parts_return_id + '&company_id=' + company_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-data');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert Return Parts Success' : 'Update Return Parts Success';
                        const notificationDescription = response.insertRecord ? 'The return parts has been inserted successfully.' : 'The return parts has been updated successfully.';
                        
                        setNotification(notificationMessage, notificationDescription, 'success');
                        if(company_id == '1'){
                            window.location = 'supplies-return.php?id=' + response.partsReturnID;
                        }
                        else if(company_id == '2'){
                            window.location = 'netruck-parts-return.php?id=' + response.partsReturnID;
                        }
                        else{
                            window.location = 'parts-return.php?id=' + response.partsReturnID;
                        }
                    }
                    else {
                        if (response.isInactive) {
                            setNotification('User Inactive', response.message, 'danger');
                            window.location = 'logout.php?logout';
                        }
                        else {
                            showNotification('Return Error', response.message, 'danger');
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

function draftReturnForm(){
    $('#draft-return-form').validate({
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
            var parts_return_id = $('#parts-return-id').text();
            const transaction = 'tag return as draft';
        
            $.ajax({
                type: 'POST',
                url: 'controller/parts-return-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&parts_return_id=' + parts_return_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-draft-return');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = 'Set to Draft Return Success';
                        const notificationDescription = 'The return has been set to draft successfully.';
                        
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
                    enableFormSubmitButton('submit-draft-return', 'Submit');
                }
            });
        
            return false;
        }
    });
}

function addPartsForm(){
    $('#add-part-form').validate({
        submitHandler: function(form) {
            var parts_return_id = $('#parts-return-id').text();
            var return_type = $('#return_type').val();
            const transaction = 'add parts return item';

            var part_transaction_cart_id = [];

            $('.assign-part').each(function(){
                if ($(this).is(':checked')){  
                    part_transaction_cart_id.push(this.value);  
                }
            });

            $.ajax({
                type: 'POST',
                url: 'controller/parts-return-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&parts_return_id=' + parts_return_id + '&part_transaction_cart_id=' + part_transaction_cart_id + '&return_type=' + return_type,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-add-part');
                },
                success: function(response) {
                    if (response.success) {
                        showNotification('Add Parts Issuance Success', 'The part issuance has been added successfully.', 'success');
                        
                        displayDetails('get parts return cart total');
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

function approveReturnForm(){
    $('#approve-return-form').validate({
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
            var parts_return_id = $('#parts-return-id').text();
            const transaction = 'tag return as approved';
        
            $.ajax({
                type: 'POST',
                url: 'controller/parts-return-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&parts_return_id=' + parts_return_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-approve-return');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = 'Validate Return Success';
                        const notificationDescription = 'The return has been tag as validated successfully.';
                        
                        setNotification(notificationMessage, notificationDescription, 'success');
                        window.location.reload();
                    }
                    else {
                        if (response.isInactive) {
                            setNotification('User Inactive', response.message, 'danger');
                            window.location = 'logout.php?logout';
                        }
                        else if (response.partQuantityExceed) {
                            showNotification('Validate Return Error', 'One of the parts added does not have enough quantity. Kindly check the added parts.', 'danger');
                        }
                        else if (response.cartQuantity) {
                            showNotification('Validate Return Error', 'One of the parts added does not have enough quantity. Kindly check the added parts.', 'danger');
                        }
                        else if (response.jobOrder) {
                            showNotification('Validate Return Error', 'No job order or additional job order linked. Cannot be processed.', 'danger');
                        }
                        else {
                            showNotification('Return Error', response.message, 'danger');
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
                    enableFormSubmitButton('submit-approve-return', 'Submit');
                }
            });
        
            return false;
        }
    });
}

function partItemForm(){
  $('#part-item-form').validate({
        rules: {
            return_quantity: {
                required: true,
                notGreaterThanRemaining: "#available_quantity"
            },
        },
        messages: {
            return_quantity: {
                required: 'Please enter the return quantity',
                maxStock: 'Return quantity cannot exceed available quantity'
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
                url: 'controller/parts-return-controller.php',
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
                        displayDetails('get parts return cart total');
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
                            showNotification('Return Error', response.message, 'danger');
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

function partItemTable(datatable_name, buttons = false, show_all = false){
    var parts_return_id = $('#parts-return-id').text();
    var return_type = $('#return_type').val();
    const type = 'part return item table';
    var settings;

    var column = [ 
            { 'data' : 'ACTION' },
            { 'data' : 'ISSUANCE_NO' },
            { 'data' : 'PART' },
            { 'data' : 'COST' },
            { 'data' : 'RETURN_QUANTITY' },
            { 'data' : 'AVAILABLE_QUANTITY' },
            { 'data' : 'REMARKS' }
        ];

        var column_definition = [
            { 'width': 'auto', 'bSortable': false, 'aTargets': 0 },
            { 'width': 'auto', 'aTargets': 1 },
            { 'width': 'auto', 'aTargets': 2 },
            { 'width': 'auto', 'aTargets': 3 },
            { 'width': 'auto', 'aTargets': 4 },
            { 'width': 'auto', 'aTargets': 5 },
            { 'width': 'auto', 'aTargets': 6 }
        ];
    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_parts_return_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {'type' : type, 'parts_return_id' : parts_return_id, 'return_type' : return_type},
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

function addPartTable(datatable_name, buttons = false, show_all = false){
    var parts_return_id = $('#parts-return-id').text();
    var return_type = $('#return_type').val();
    const company_id = $('#page-company').val();
    const type = 'add part table';
    var settings;

    const column = [ 
        { 'data' : 'ISSUANCE_NO' },
        { 'data' : 'PART' },
        { 'data' : 'COST' },
        { 'data' : 'AVAILABLE_QUANTITY' },
        { 'data' : 'ASSIGN' }
    ];

    const column_definition = [
        { 'width': '20%', 'aTargets': 0 },
        { 'width': '40%', 'aTargets': 1 },
        { 'width': 'auto', 'aTargets': 2 },
        { 'width': 'auto', 'aTargets': 3 },
        { 'width': '10%', 'bSortable': false, 'aTargets': 4 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_parts_return_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {'type' : type, 'parts_return_id' : parts_return_id, 'return_type' : return_type, 'company_id' : company_id},
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

$.validator.addMethod("notGreaterThanRemaining", function(value, element, params) {
    var remaining = parseFloat($(params).val());
    var received = parseFloat(value);
    return received <= remaining;
}, "Received quantity cannot be greater than remaining quantity.");

function displayDetails(transaction){
    switch (transaction) {
        case 'get parts return details':
             var parts_return_id = $('#parts-return-id').text();
            
            $.ajax({
                url: 'controller/parts-return-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    parts_return_id : parts_return_id, 
                    transaction : transaction
                },
                success: function(response) {
                    if (response.success) {
                        $('#ref_invoice_number').val(response.ref_invoice_number);
                        $('#ref_po_number').val(response.ref_po_number);
                        $('#prev_total_billing').val(response.prev_total_billing);
                        $('#adjusted_total_billing').val(response.adjusted_total_billing);
                        $('#remarks').val(response.remarks);
                        $('#purchase_date').val(response.purchase_date);
                        $('#ref_po_date').val(response.ref_po_date);

                        checkOptionExist('#supplier_id', response.supplier_id, '');
                        checkOptionExist('#return_type', response.return_type, '');
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
        case 'get parts return cart details':
            let part_return_cart_id = $('#part_return_cart_id').val();
            var return_type = $('#return_type').val();
            
            $.ajax({
                url: 'controller/parts-return-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    part_return_cart_id : part_return_cart_id, 
                    return_type : return_type, 
                    transaction : transaction
                },
                success: function(response) {
                    if (response.success) {
                        $('#issuance_no_description').val(response.slip_reference_no);
                        $('#part_description').val(response.description);
                        $('#available_quantity').val(response.available_return_quantity);
                        $('#return_quantity').val(response.return_quantity);
                        $('#part_remarks').val(response.remarks);
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
        case 'get receive parts return cart details':
            let parts_return_cart_id = sessionStorage.getItem('parts_return_cart_id');
            
            $.ajax({
                url: 'controller/parts-return-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    part_return_cart_id : parts_return_cart_id, 
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

        case 'get parts return cart total':
            var parts_return_id = $('#parts-return-id').text();
            
            $.ajax({
                url: 'controller/parts-return-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    parts_return_id : parts_return_id, 
                    transaction : transaction
                },
                success: function(response) {
                    if (response.success) {
                        $('#total-cost-summary').text(response.cost);
                        $('#total-item-summary').text(response.lines);
                        $('#total-quantity-summary').text(response.quantity);
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