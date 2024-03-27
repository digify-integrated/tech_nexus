(function($) {
    'use strict';

    $(function() {        
        if($('#other-charges-rows').length){
            salesProposalSummaryPDCManualInputTable();
        }

        if($('#sales-proposal-for-dr-table').length){
            salesProposalForDRTable('#sales-proposal-for-dr-table');
        }

        if($('#sales-proposal-deposit-amount-table').length){
            salesProposalDepositAmountTable('#sales-proposal-deposit-amount-table');
        }

        if($('#sales-proposal-pdc-manual-input-table').length){
            salesProposalPDCManualInputTable('#sales-proposal-pdc-manual-input-table');
        }

        if($('#product_id').length){
            displayDetails('get product details');
        }

        if($('#sales-proposal-tag-as-released-form').length){
            salesProposalReleaseForm();
        }

        if($('#sales-proposal-other-product-details-form').length){
            salesProposalOtherProductDetailsForm();            
            
            displayDetails('get sales proposal pricing computation details');
            displayDetails('get sales proposal other charges details');
            displayDetails('get sales proposal renewal amount details');
        }

        if($('#sales-proposal-tab-1').length){
            displayDetails('get sales proposal other product details');
            displayDetails('get sales proposal details');
        }

        if($('#sales-proposal-pdc-manual-input-form').length){
            salesProposalPDCManualInputForm();
        }

        $(document).on('click','.delete-sales-proposal-manual-pdc-input',function() {
            const sales_proposal_manual_pdc_input_id = $(this).data('sales-proposal-manual-pdc-input-id');
            const transaction = 'delete sales proposal pdc manual input';
    
            Swal.fire({
                title: 'Confirm PDC Manual Input Deletion',
                text: 'Are you sure you want to delete this pdc manual input?',
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
                        url: 'controller/sales-proposal-controller.php',
                        dataType: 'json',
                        data: {
                            sales_proposal_manual_pdc_input_id : sales_proposal_manual_pdc_input_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete PDC Manual Input Success', 'The PDC manula input has been deleted successfully.', 'success');
                                reloadDatatable('#sales-proposal-pdc-manual-input-table');
                                salesProposalSummaryPDCManualInputTable();
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    window.location = '404.php';
                                }
                                else {
                                    showNotification('Delete PDC Manual Input Error', response.message, 'danger');
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

function salesProposalForDRTable(datatable_name, buttons = false, show_all = false){
    const type = 'sales proposal for dr table';

    var settings;

    const column = [ 
        { 'data' : 'SALES_PROPOSAL_NUMBER' },
        { 'data' : 'CUSTOMER' },
        { 'data' : 'PRODUCT_TYPE' },
        { 'data' : 'PRODUCT' },
        { 'data' : 'FOR_DR_DATE' },
        { 'data' : 'STATUS' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '15%', 'aTargets': 0 },
        { 'width': '15%', 'aTargets': 1 },
        { 'width': '15%', 'aTargets': 2 },
        { 'width': '25%', 'aTargets': 3 },
        { 'width': '25%', 'aTargets': 4 },
        { 'width': '10%', 'aTargets': 5 },
        { 'width': '10%','bSortable': false, 'aTargets': 6 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_sales_proposal_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {'type' : type},
            'dataSrc' : '',
            'error': function(xhr, status, error) {
                var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                if (xhr.responseText) {
                    fullErrorMessage += `, Response: ${xhr.responseText}`;
                }
                showErrorDialog(fullErrorMessage);
            }
        },
        'order': [[ 4, 'desc' ]],
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

function salesProposalDepositAmountTable(datatable_name, buttons = false, show_all = false){
    const sales_proposal_id = $('#sales-proposal-id').text();
    const type = 'sales proposal deposit amount table';
    var settings;

    const column = [ 
        { 'data' : 'DEPOSIT_DATE' },
        { 'data' : 'REFERENCE_NUMBER' },
        { 'data' : 'DEPOSIT_AMOUNT' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '25%', 'aTargets': 0 },
        { 'width': '30%', 'aTargets': 1 },
        { 'width': '30%', 'aTargets': 2 },
        { 'width': '15%','bSortable': false, 'aTargets': 3 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_sales_proposal_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {'type' : type, 'sales_proposal_id' : sales_proposal_id},
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

function salesProposalPDCManualInputTable(datatable_name, buttons = false, show_all = false){
    const sales_proposal_id = $('#sales-proposal-id').text();
    const type = 'sales proposal pdc manual input table';
    var settings;

    const column = [ 
        { 'data' : 'BANK_BRANCH' },
        { 'data' : 'CHECK_DATE' },
        { 'data' : 'CHECK_NUMBER' },
        { 'data' : 'PAYMENT_FOR' },
        { 'data' : 'GROSS_AMOUNT' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '17%', 'aTargets': 0 },
        { 'width': '17%', 'aTargets': 1 },
        { 'width': '17%', 'aTargets': 2 },
        { 'width': '17%', 'aTargets': 3 },
        { 'width': '17%', 'aTargets': 4 },
        { 'width': '15%','bSortable': false, 'aTargets': 5 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_sales_proposal_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {'type' : type, 'sales_proposal_id' : sales_proposal_id},
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
        'fnDrawCallback': function( oSettings ) {
            readjustDatatableColumn();
        },
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

function salesProposalOtherProductDetailsForm(){
    $('#sales-proposal-other-product-details-form').validate({
        rules: {
            dr_number: {
                required: true
            },
            actual_start_date: {
                required: true
            },
            product_description: {
                required: true
            },
        },
        messages: {
            dr_number: {
                required: 'Please enter the DR number'
            },
            actual_start_date: {
                required: 'Please choose the actual start date'
            },
            product_description: {
                required: 'Please enter the product description'
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
            const sales_proposal_id = $('#sales-proposal-id').text();
            const transaction = 'save sales proposal other product details';
        
            $.ajax({
                type: 'POST',
                url: 'controller/sales-proposal-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&sales_proposal_id=' + sales_proposal_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-other-product-details-data');
                },
                success: function (response) {
                    if (!response.success) {
                        if (response.isInactive) {
                            setNotification('User Inactive', response.message, 'danger');
                            window.location = 'logout.php?logout';
                        } else {
                            showNotification('Transaction Error', response.message, 'danger');
                        }
                    }
                    else{
                        window.location.reload();
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
                    enableFormSubmitButton('submit-other-product-details-data', 'Submit');
                    displayDetails('get sales proposal other product details');
                }
            });
        
            return false;
        }
    });
}

function salesProposalReleaseForm(){
    $('#sales-proposal-tag-as-released-form').validate({
        rules: {
            release_remarks: {
                required: true
            },
        },
        messages: {
            release_remarks: {
                required: 'Please eneter the release remarks'
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
            const sales_proposal_id = $('#sales-proposal-id').text();
            const transaction = 'tag for release';
        
            $.ajax({
                type: 'POST',
                url: 'controller/sales-proposal-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&sales_proposal_id=' + sales_proposal_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-other-product-details-data');
                },
                success: function (response) {
                    if (!response.success) {
                        if (response.isInactive) {
                            setNotification('User Inactive', response.message, 'danger');
                            window.location = 'logout.php?logout';
                        } else {
                            showNotification('Transaction Error', response.message, 'danger');
                        }
                    }
                    else{
                        window.location.reload();
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
                    enableFormSubmitButton('submit-other-product-details-data', 'Submit');
                    displayDetails('get sales proposal other product details');
                }
            });
        
            return false;
        }
    });
}

function salesProposalSummaryPDCManualInputTable(){
    const sales_proposal_id = $('#sales-proposal-id').text();
    const type = 'summary pdc manual input table';

    $.ajax({
        type: "POST",
        url: "view/_sales_proposal_generation.php",
        dataType: 'json',
        data: { type: type, sales_proposal_id: sales_proposal_id },
        success: function (result) {
            if($('#other-charges-rows').length){
                document.getElementById('other-charges-rows').innerHTML = result[0].table;
            }
        }
    });
}

function salesProposalPDCManualInputForm(){
    $('#sales-proposal-pdc-manual-input-form').validate({
        rules: {
            payment_frequency: {
                required: true
            },
            payment_for: {
                required: true
            },
            bank_branch: {
                required: true
            },
            no_of_payments: {
                required: true
            },
            first_check_number: {
                required: true
            },
            first_check_date: {
                required: true
            },
            amount_due: {
                required: true
            },
        },
        messages: {
            payment_frequency: {
                required: 'Please choose the payment frequency'
            },
            payment_for: {
                required: 'Please choose the payment for'
            },
            bank_branch: {
                required: 'Please enter the bank/branch'
            },
            no_of_payments: {
                required: 'Please enter the number of payments'
            },
            first_check_number: {
                required: 'Please enter the first check number'
            },
            first_check_date: {
                required: 'Please choose the first check date'
            },
            amount_due: {
                required: 'Please enter the gross amount'
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
            const sales_proposal_id = $('#sales-proposal-id').text();
            const transaction = 'save sales proposal pdc manual input';
        
            $.ajax({
                type: 'POST',
                url: 'controller/sales-proposal-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&sales_proposal_id=' + sales_proposal_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-sales-proposal-pdc-manual-input');
                },
                success: function (response) {
                    if (!response.success) {
                        if (response.isInactive) {
                            setNotification('User Inactive', response.message, 'danger');
                            window.location = 'logout.php?logout';
                        } else {
                            showNotification('Transaction Error', response.message, 'danger');
                        }
                    }
                    else{
                        reloadDatatable('#sales-proposal-pdc-manual-input-table');
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
                    enableFormSubmitButton('submit-sales-proposal-pdc-manual-input', 'Submit');
                    salesProposalSummaryPDCManualInputTable();
                }
            });
        
            return false;
        }
    });
}

function displayDetails(transaction){
    switch (transaction) {
        case 'get sales proposal details':
            var sales_proposal_id = $('#sales-proposal-id').text();
            
            $.ajax({
                url: 'controller/sales-proposal-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    sales_proposal_id : sales_proposal_id, 
                    transaction : transaction
                },
                success: function(response) {
                    if (response.success) {
                        $('#actual_start_date').val(response.actualStartDate);
                        $('#actual_start_date_label').text(response.actualStartDate);
                        $('#dr_number').val(response.drNumber);
                        $('#dr_number_label').text(response.drNumber);
                        $('#sales_proposal_number').text(response.salesProposalNumber);

                        $('#product_id_label').text(response.productName);
                        $('#comaker_id_label').text(response.comakerName);
                        $('#created-by').text(response.createdByName);
                        $('#product_type_label').text(response.productType);
                        $('#transaction_type_label').text(response.transactionType);
                        $('#financing_institution_label').text(response.financingInstitution);

                        $('#initial-approval-by').text(response.initialApprovalByName);
                        $('#initial-approval-remarks').text(response.initialApprovalRemarks);
                        $('#initial-approval-date').text(response.initialApprovalDate);
                        $('#approval-by').text(response.approvalByName);
                        $('#final-approval-remarks').text(response.finalApprovalRemarks);
                        $('#final-approval-date').text(response.approvalDate);
                        $('#for-ci-date').text(response.forCIDate);
                        $('#rejection-reason').text(response.rejectionReason);
                        $('#rejection-date').text(response.rejectionDate);
                        $('#cancellation-reason').text(response.cancellationReason);
                        $('#cancellation-date').text(response.cancellationDate);
                        $('#set-to-draft-reason').text(response.setToDraftReason);
                        
                        $('#comaker_id_details').text(response.comakerID);
                        $('#product_id_details').text(response.productID);
                        $('#referred_by_label').text(response.referredBy);
                        $('#release_date_label').text(response.releaseDate);
                        $('#start_date_label').text(response.startDate);
                        $('#term_length_label').text(response.termLength + ' ' + response.termType);
                        $('#number_of_payments_label').text(response.numberOfPayments);
                        $('#payment_frequency_label').text('Frequency of Payment: ' + response.paymentFrequency);
                        $('#first_due_date_label').text(response.firstDueDate);
                        $('#for_registration_label').text(response.forRegistration);
                        $('#with_cr_label').text(response.withCR);
                        $('#for_transfer_label').text(response.forTransfer);
                        $('#remarks_label').text(response.remarks);
                        $('#initial_approving_officer_label').text(response.initialApprovingOfficerName);
                        $('#final_approving_officer_label').text(response.finalApprovingOfficerName);
                        $('#for_change_color_label').text(response.forChangeColor);
                        $('#new_color_label').text(response.newColor);
                        $('#for_change_body_label').text(response.forChangeBody);
                        $('#for_change_engine_label').text(response.forChangeEngine);
                        $('#fuel_type_label').text(response.fuelType);
                        $('#fuel_quantity_label').text(response.fuelQuantity + ' lt');
                        $('#new_body_label').text(response.newBody);
                        $('#new_engine_label').text(response.newEngine);
                        $('#price_per_liter_label').text(response.pricePerLiter);
                        $('#commission_amount_label').text(response.commissionAmount);
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Sales Proposal Details Error', response.message, 'danger');
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
            break;
        case 'get product details':
            var product_id = $('#product_id').val();

            if(product_id != '' && product_id != 0){
                $.ajax({
                    url: 'controller/product-controller.php',
                    method: 'POST',
                    dataType: 'json',
                    data: {
                        product_id : product_id, 
                        transaction : transaction
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#color-summary').text(response.colorName);
                            $('#body-type-summary').text(response.bodyTypeName);
    
                            $('#stock-number-summary').text(response.summaryStockNumber);
                            $('#unit_id_gatepass').text(response.summaryStockNumber);
                            $('#unit_id_gatepass2').text(response.summaryStockNumber);
                            $('#engine-number-summary').text(response.engineNumber);
                            $('#chassis-number-summary').text(response.chassisNumber);
                            $('#plate-number-summary').text(response.plateNumber);    
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
                    }
                });
            }
            break;
        case 'get sales proposal other product details':
            var sales_proposal_id = $('#sales-proposal-id').text();

            $.ajax({
                url: 'controller/sales-proposal-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    sales_proposal_id : sales_proposal_id, 
                    transaction : transaction
                },
                success: function(response) {
                    if (response.success) {
                        $('#year_model').val(response.yearModel);
                        $('#year_model_label').text(response.yearModel);
                        $('#cr_no').val(response.crNo);
                        $('#cr_no_label').text(response.crNo);
                        $('#mv_file_no').val(response.mvFileNo);
                        $('#mv_file_no_label').text(response.mvFileNo);
                        $('#make').val(response.make);
                        $('#make_label').text(response.make);
                        $('#product_description').val(response.productDescription);
                        $('#product_description_label').text(response.productDescription);
                        $('#product_description_gatepass').text(response.productDescription);
                        $('#product_description_gatepass2').text(response.productDescription);
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Other Product Details Error', response.message, 'danger');
                        }
                    }
                },
                error: function(xhr, status, error) {
                    var fullErrorMessage = 'XHR status: ${status}, Error: ${error}';
                    if (xhr.responseText) {
                        fullErrorMessage += ', Response: ${xhr.responseText}';
                    }
                    showErrorDialog(fullErrorMessage);
                }
            });
            break;
            case 'get sales proposal pricing computation details':
                var sales_proposal_id = $('#sales-proposal-id').text();
            
                $.ajax({
                    url: 'controller/sales-proposal-controller.php',
                    method: 'POST',
                    dataType: 'json',
                    data: {
                        sales_proposal_id : sales_proposal_id, 
                        transaction : transaction
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#delivery_price').val(response.deliveryPrice);
                            $('#cost_of_accessories').val(response.costOfAccessories);
                            $('#reconditioning_cost').val(response.reconditioningCost);
                            $('#downpayment').val(response.downpayment);
                            $('#interest_rate').val(response.interestRate);
    
                            $('#summary-deliver-price').text(parseFloat(response.deliveryPrice).toLocaleString("en-US"));
                            $('#summary-cost-of-accessories').text(parseFloat(response.costOfAccessories).toLocaleString("en-US"));
                            $('#summary-reconditioning-cost').text(parseFloat(response.reconditioningCost).toLocaleString("en-US"));
                            $('#summary-downpayment').text(parseFloat(response.downpayment).toLocaleString("en-US"));
                            $('#summary-repayment-amount').text(parseFloat(response.repaymentAmount).toLocaleString("en-US"));
                            $('#summary-outstanding-balance').text(parseFloat(response.outstandingBalance).toLocaleString("en-US"));
                            $('#summary-sub-total').text(parseFloat(response.subtotal).toLocaleString("en-US"));
    
                            $('#delivery_price_label').text(parseFloat(response.deliveryPrice).toLocaleString("en-US"));
                            $('#cost_of_accessories_label').text(parseFloat(response.costOfAccessories).toLocaleString("en-US"));
                            $('#reconditioning_cost_label').text(parseFloat(response.reconditioningCost).toLocaleString("en-US"));
                            $('#downpayment_label').text(parseFloat(response.downpayment).toLocaleString("en-US"));
                            $('#interest_rate_label').text(parseFloat(response.interestRate).toLocaleString("en-US"));
                            $('#subtotal_label').text(parseFloat(response.subtotal).toLocaleString("en-US"));
                            $('#outstanding_balance_label').text(parseFloat(response.outstandingBalance).toLocaleString("en-US"));
                            $('#amount_financed_label').text(parseFloat(response.amountFinanced).toLocaleString("en-US"));
                            $('#pn_amount_label').text(parseFloat(response.pnAmount).toLocaleString("en-US"));
                            $('#repayment_amount_label').text(parseFloat(response.repaymentAmount).toLocaleString("en-US"));
                        } 
                        else {
                            if(response.isInactive){
                                window.location = 'logout.php?logout';
                            }
                            else{
                                showNotification('Get Sales Proposal Pricing Computation Details Error', response.message, 'danger');
                            }
                        }
                    },
                    error: function(xhr, status, error) {
                        var fullErrorMessage = 'XHR status: ${status}, Error: ${error}';
                        if (xhr.responseText) {
                            fullErrorMessage += ', Response: ${xhr.responseText}';
                        }
                        showErrorDialog(fullErrorMessage);
                    }
                });
                break;
            case 'get sales proposal other charges details':
                var sales_proposal_id = $('#sales-proposal-id').text();
            
                $.ajax({
                    url: 'controller/sales-proposal-controller.php',
                    method: 'POST',
                    dataType: 'json',
                    data: {
                        sales_proposal_id : sales_proposal_id, 
                        transaction : transaction
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#insurance_coverage').val(response.insuranceCoverage);
                            $('#insurance_premium').val(response.insurancePremium);
                            $('#handling_fee').val(response.handlingFee);
                            $('#transfer_fee').val(response.transferFee);
                            $('#registration_fee').val(response.registrationFee);
                            $('#doc_stamp_tax').val(response.docStampTax);
                            $('#transaction_fee').val(response.transactionFee);
    
                            $('#summary-insurance-coverage').text(parseFloat(response.insuranceCoverage).toLocaleString("en-US"));
                            $('#summary-insurance-premium').text(parseFloat(response.insurancePremium).toLocaleString("en-US"));
                            $('#summary-handing-fee').text(parseFloat(response.handlingFee).toLocaleString("en-US"));
                            $('#summary-transfer-fee').text(parseFloat(response.transferFee).toLocaleString("en-US"));
                            $('#summary-registration-fee').text(parseFloat(response.registrationFee).toLocaleString("en-US"));
                            $('#summary-doc-stamp-tax').text(parseFloat(response.docStampTax).toLocaleString("en-US"));
                            $('#summary-transaction-fee').text(parseFloat(response.transactionFee).toLocaleString("en-US"));
                            $('#summary-other-charges-total').text(parseFloat(response.totalOtherCharges).toLocaleString("en-US"));
    
                            $('#insurance_coverage_label').text(parseFloat(response.insuranceCoverage).toLocaleString("en-US"));
                            $('#insurance_premium_label').text(parseFloat(response.insurancePremium).toLocaleString("en-US"));
                            $('#handling_fee_label').text(parseFloat(response.handlingFee).toLocaleString("en-US"));
                            $('#transfer_fee_label').text(parseFloat(response.transferFee).toLocaleString("en-US"));
                            $('#registration_fee_label').text(parseFloat(response.registrationFee).toLocaleString("en-US"));
                            $('#doc_stamp_tax_label').text(parseFloat(response.docStampTax).toLocaleString("en-US"));
                            $('#transaction_fee_label').text(parseFloat(response.transactionFee).toLocaleString("en-US"));
                        } 
                        else {
                            if(response.isInactive){
                                window.location = 'logout.php?logout';
                            }
                            else{
                                showNotification('Get Sales Proposal Other Charges Details Error', response.message, 'danger');
                            }
                        }
                    },
                    error: function(xhr, status, error) {
                        var fullErrorMessage = 'XHR status: ${status}, Error: ${error}';
                        if (xhr.responseText) {
                            fullErrorMessage += ', Response: ${xhr.responseText}';
                        }
                        showErrorDialog(fullErrorMessage);
                    }
                });
                break;
            case 'get sales proposal renewal amount details':
                var sales_proposal_id = $('#sales-proposal-id').text();
            
                $.ajax({
                    url: 'controller/sales-proposal-controller.php',
                    method: 'POST',
                    dataType: 'json',
                    data: {
                        sales_proposal_id : sales_proposal_id, 
                        transaction : transaction
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#registration_second_year').val(response.registrationSecondYear);
                            $('#registration_third_year').val(response.registrationThirdYear);
                            $('#registration_fourth_year').val(response.registrationFourthYear);
                            $('#insurance_coverage_second_year').val(response.insuranceCoverageSecondYear);
                            $('#insurance_coverage_third_year').val(response.insuranceCoverageThirdYear);
                            $('#insurance_coverage_fourth_year').val(response.insuranceCoverageFourthYear);
                            $('#insurance_premium_second_year').val(response.insurancePremiumSecondYear);
                            $('#insurance_premium_third_year').val(response.insurancePremiumThirdYear);
                            $('#insurance_premium_fourth_year').val(response.insurancePremiumFourthYear);
    
                            $('#summary-registration-second-year').text(parseFloat(response.registrationSecondYear).toLocaleString("en-US"));
                            $('#summary-registration-third-year').text(parseFloat(response.registrationThirdYear).toLocaleString("en-US"));
                            $('#summary-registration-fourth-year').text(parseFloat(response.registrationFourthYear).toLocaleString("en-US"));
                            $('#summary-insurance-coverage-second-year').text(parseFloat(response.insuranceCoverageSecondYear).toLocaleString("en-US"));
                            $('#summary-insurance-coverage-third-year').text(parseFloat(response.insuranceCoverageThirdYear).toLocaleString("en-US"));
                            $('#summary-insurance-coverage-fourth-year').text(parseFloat(response.insuranceCoverageFourthYear).toLocaleString("en-US"));
                            $('#summary-insurance-premium-second-year').text(parseFloat(response.insurancePremiumSecondYear).toLocaleString("en-US"));
                            $('#summary-insurance-premium-third-year').text(parseFloat(response.insurancePremiumThirdYear).toLocaleString("en-US"));
                            $('#summary-insurance-premium-fourth-year').text(parseFloat(response.insurancePremiumFourthYear).toLocaleString("en-US"));
    
                            $('#registration_second_year_label').text(parseFloat(response.registrationSecondYear).toLocaleString("en-US"));
                            $('#registration_third_year_label').text(parseFloat(response.registrationThirdYear).toLocaleString("en-US"));
                            $('#registration_fourth_year_label').text(parseFloat(response.registrationFourthYear).toLocaleString("en-US"));
                            $('#insurance_coverage_second_year_label').text(parseFloat(response.insuranceCoverageSecondYear).toLocaleString("en-US"));
                            $('#insurance_coverage_third_year_label').text(parseFloat(response.insuranceCoverageThirdYear).toLocaleString("en-US"));
                            $('#insurance_coverage_fourth_year_label').text(parseFloat(response.insuranceCoverageFourthYear).toLocaleString("en-US"));
                            $('#insurance_premium_second_year_label').text(parseFloat(response.insurancePremiumSecondYear).toLocaleString("en-US"));
                            $('#insurance_premium_third_year_label').text(parseFloat(response.insurancePremiumThirdYear).toLocaleString("en-US"));
                            $('#insurance_premium_fourth_year_label').text(parseFloat(response.insurancePremiumFourthYear).toLocaleString("en-US"));
                        } 
                        else {
                            if(response.isInactive){
                                window.location = 'logout.php?logout';
                            }
                            else{
                                showNotification('Get Sales Proposal Renewal Amount Details Error', response.message, 'danger');
                            }
                        }
                    },
                    error: function(xhr, status, error) {
                        var fullErrorMessage = 'XHR status: ${status}, Error: ${error}';
                        if (xhr.responseText) {
                            fullErrorMessage += ', Response: ${xhr.responseText}';
                        }
                            showErrorDialog(fullErrorMessage);
                    }
                });
                break;
    }
}