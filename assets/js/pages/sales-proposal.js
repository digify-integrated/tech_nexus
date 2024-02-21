(function($) {
    'use strict';

    $(function() {
        if($('#sales-proposal-form').length){
            salesProposalForm();
        }

        if($('#sales-proposal-id').length){
            displayDetails('get sales proposal details');

            if($('#sales-proposal-accessories-table').length){
                salesProposalAccessoriesTable('#sales-proposal-accessories-table');
            }

            if($('#sales-proposal-accessories-form').length){
                salesProposalAccessoriesForm();
            }

            if($('#sales-proposal-job-order-table').length){
                salesProposalJobOrderTable('#sales-proposal-job-order-table');
            }

            if($('#sales-proposal-job-order-form').length){
                salesProposalJobOrderForm();
            }

            if($('#sales-proposal-additional-job-order-table').length){
                salesProposalAdditionalJobOrderTable('#sales-proposal-additional-job-order-table');
            }

            if($('#sales-proposal-additional-job-order-form').length){
                salesProposalAdditionalJobOrderForm();
            }
        }

        $(document).on('change','#product_id',function() {
            if($(this).val() != ''){
                displayDetails('get product details');
            }
            else{
                $('#product_engine_number').text('--');
                $('#product_chassis_number').text('--');
                $('#product_plate_number').text('--');
            }
        });
        
        $(document).on('change','#comaker_id',function() {
            if($(this).val() != ''){
                displayDetails('get comaker details');
            }
            else{
                $('#comaker_file_as').text('--');
                $('#comaker_address').text('--');
                $('#comaker_email').text('--');
                $('#comaker_mobile').text('--');
                $('#comaker_telephone').text('--');
            }
        });
        
        $(document).on('change','#term_type',function() {
            $('#payment_frequency').empty().append(new Option('--', '', false, false));

            if($(this).val() == 'Days'){
                $('#payment_frequency').append(new Option('Lumpsum', 'Lumpsum', false, false));
            }
            else if($(this).val() == 'Months'){
                $('#payment_frequency').append(new Option('Lumpsum', 'Lumpsum', false, false));
                $('#payment_frequency').append(new Option('Monthly', 'Monthly', false, false));
                $('#payment_frequency').append(new Option('Quarterly', 'Quarterly', false, false));
                $('#payment_frequency').append(new Option('Semi-Annual', 'Semi-Annual', false, false));
            }

            calculatePaymentNumber();
        });
        
        $(document).on('click','#next-step-1',function() {
            nextStep(1);
        });
        
        $(document).on('click','#prev-step-2',function() {
            prevStep(2);
        });
        
        $(document).on('click','#next-step-2',function() {
            nextStep(2);
        });
        
        $(document).on('click','#prev-step-3',function() {
            prevStep(3);
        });
        
        $(document).on('click','#next-step-3',function() {
            nextStep(3);
        });
        
        $(document).on('click','#prev-step-4',function() {
            prevStep(4);
        });
        
        $(document).on('click','#next-step-4',function() {
            nextStep(4);
        });
        
        $(document).on('click','#prev-step-5',function() {
            prevStep(5);
        });
        
        $(document).on('click','#next-step-5',function() {
            nextStep(5);
        });
        
        $(document).on('click','#prev-step-6',function() {
            prevStep(6);
        });
        
        $(document).on('change','#payment_frequency',function() {
            calculatePaymentNumber();
        });
        
        $(document).on('change','#term_length',function() {
            calculatePaymentNumber();
        });
        
        $(document).on('focusout', '#start_date', function () {
            calculatePaymentNumber();
        });
    });
})(jQuery);

function salesProposalAccessoriesTable(datatable_name, buttons = false, show_all = false){
    const sales_proposal_id = $('#sales-proposal-id').text();
    const type = 'sales proposal accessories table';
    var settings;

    const column = [ 
        { 'data' : 'ACCESSORIES' },
        { 'data' : 'COST' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '42%', 'aTargets': 0 },
        { 'width': '42%', 'aTargets': 1 },
        { 'width': '16%','bSortable': false, 'aTargets': 2 }
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

function salesProposalJobOrderTable(datatable_name, buttons = false, show_all = false){
    const sales_proposal_id = $('#sales-proposal-id').text();
    const type = 'sales proposal job order table';
    var settings;

    const column = [ 
        { 'data' : 'JOB_ORDER' },
        { 'data' : 'COST' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '42%', 'aTargets': 0 },
        { 'width': '42%', 'aTargets': 1 },
        { 'width': '16%','bSortable': false, 'aTargets': 2 }
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

function salesProposalAdditionalJobOrderTable(datatable_name, buttons = false, show_all = false){
    const sales_proposal_id = $('#sales-proposal-id').text();
    const type = 'sales proposal additional job order table';
    var settings;

    const column = [ 
        { 'data' : 'JOB_ORDER_NUMBER' },
        { 'data' : 'JOB_ORDER_DATE' },
        { 'data' : 'PARTICULARS' },
        { 'data' : 'COST' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '25%', 'aTargets': 0 },
        { 'width': '15%', 'aTargets': 1 },
        { 'width': '15%', 'aTargets': 2 },
        { 'width': '30%', 'aTargets': 3 },
        { 'width': '15%','bSortable': false, 'aTargets': 4 }
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

function salesProposalForm(){
    $('#sales-proposal-form').validate({
        rules: {
            product_id: {
                required: true
            },
            referred_by: {
                required: true
            },
            release_date: {
                required: true
            },
            start_date: {
                required: true
            },
            term_length: {
                required: true
            },
            term_type: {
                required: true
            },
            number_of_payments: {
                required: true
            },
            payment_frequency: {
                required: true
            },
            first_due_date: {
                required: true
            },
            for_registration: {
                required: true
            },
            with_cr: {
                required: true
            },
            for_transfer: {
                required: true
            },
        },
        messages: {
            product_id: {
                required: 'Please choose the product'
            },
            referred_by: {
                required: 'Please enter the referred by'
            },
            release_date: {
                required: 'Please choose the estimated date of release'
            },
            start_date: {
                required: 'Please choose the start date'
            },
            term_length: {
                required: 'Please enter the term length'
            },
            term_type: {
                required: 'Please choose the term type'
            },
            number_of_payments: {
                required: 'Please enter the number of payments'
            },
            payment_frequency: {
                required: 'Please choose the payment frequency'
            },
            first_due_date: {
                required: 'Please choose the first due date'
            },
        },
        errorPlacement: function (error, element) {
            if (element.hasClass('select2') || element.hasClass('modal-select2')) {
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
            const customer_id = $('#customer-id').text();
            const transaction = 'save sales proposal';
        
            $.ajax({
                type: 'POST',
                url: 'controller/sales-proposal-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&sales_proposal_id=' + sales_proposal_id + '&customer_id=' + customer_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-data');           
                },
                success: function (response) {
                    if (response.success) {
                        if(response.insertRecord){
                            setNotification('Insert Sales Proposal Success', 'The sales proposal has been inserted successfully.', 'success');
                            window.location = 'sales-proposal.php?customer='+ response.customerID +'&id=' + response.salesProposalID;
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
                    var fullErrorMessage = 'XHR status: ${status}, Error: ${error}';
                    if (xhr.responseText) {
                        fullErrorMessage += ', Response: ${xhr.responseText}';
                    }
                    showErrorDialog(fullErrorMessage);
                },
                complete: function() {
                    enableFormSubmitButton('submit-data', 'Submit');
                }
            });
        
            return false;
        }
    });
}

function salesProposalAccessoriesForm(){
    $('#sales-proposal-accessories-form').validate({
        rules: {
            accessories: {
                required: true
            },
            accessories_cost: {
                required: true
            }
        },
        messages: {
            accessories: {
                required: 'Please enter the accessories'
            },
            accessories_cost: {
                required: 'Please enter the cost'
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
            const sales_proposal_id = $('#sales-proposal-id').text();
            const transaction = 'save sales proposal accessories';
        
            $.ajax({
                type: 'POST',
                url: 'controller/sales-proposal-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&sales_proposal_id=' + sales_proposal_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-sales-proposal-accessories');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert Accessories Success' : 'Update Accessories Success';
                        const notificationDescription = response.insertRecord ? 'The accessories has been inserted successfully.' : 'The accessories has been updated successfully.';
                        
                        showNotification(notificationMessage, notificationDescription, 'success');
                    }
                    else {
                        if (response.isInactive) {
                            setNotification('User Inactive', response.message, 'danger');
                            window.location = 'logout.php?logout';
                        } else {
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
                    enableFormSubmitButton('submit-sales-proposal-accessories', 'Submit');
                    $('#sales-proposal-accessories-offcanvas').offcanvas('hide');
                    reloadDatatable('#sales-proposal-accessories-table');
                    resetModalForm('sales-proposal-accessories-form');
                }
            });
        
            return false;
        }
    });
}

function salesProposalJobOrderForm(){
    $('#sales-proposal-job-order-form').validate({
        rules: {
            job_order: {
                required: true
            },
            job_order_cost: {
                required: true
            }
        },
        messages: {
            job_order: {
                required: 'Please enter the job order'
            },
            job_order_cost: {
                required: 'Please enter the cost'
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
            const sales_proposal_id = $('#sales-proposal-id').text();
            const transaction = 'save sales proposal accessories';
        
            $.ajax({
                type: 'POST',
                url: 'controller/sales-proposal-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&sales_proposal_id=' + sales_proposal_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-sales-proposal-job-order');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert Job Order Success' : 'Update Job Order Success';
                        const notificationDescription = response.insertRecord ? 'The job order has been inserted successfully.' : 'The job order has been updated successfully.';
                        
                        showNotification(notificationMessage, notificationDescription, 'success');
                    }
                    else {
                        if (response.isInactive) {
                            setNotification('User Inactive', response.message, 'danger');
                            window.location = 'logout.php?logout';
                        } else {
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
                    enableFormSubmitButton('submit-sales-proposal-job-order', 'Submit');
                    $('#sales-proposal-job-order-offcanvas').offcanvas('hide');
                    reloadDatatable('#sales-proposal-job-order-table');
                    resetModalForm('sales-proposal-job-order-form');
                }
            });
        
            return false;
        }
    });
}

function salesProposalAdditionalJobOrderForm(){
    $('#sales-proposal-additional-job-order-form').validate({
        rules: {
            job_order_number: {
                required: true
            },
            job_order_date: {
                required: true
            },
            particulars: {
                required: true
            },
            additional_job_order_cost: {
                required: true
            }
        },
        messages: {
            job_order_number: {
                required: 'Please enter the job order'
            },
            job_order_date: {
                required: 'Please choose the job order date'
            },
            particulars: {
                required: 'Please enter the particulars'
            },
            additional_job_order_cost: {
                required: 'Please enter the cost'
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
            const sales_proposal_id = $('#sales-proposal-id').text();
            const transaction = 'save sales proposal accessories';
        
            $.ajax({
                type: 'POST',
                url: 'controller/sales-proposal-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&sales_proposal_id=' + sales_proposal_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-sales-proposal-additional-job-order');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert Additional Job Order Success' : 'Update Additional Job Order Success';
                        const notificationDescription = response.insertRecord ? 'The additional job order has been inserted successfully.' : 'The additional job order has been updated successfully.';
                        
                        showNotification(notificationMessage, notificationDescription, 'success');
                    }
                    else {
                        if (response.isInactive) {
                            setNotification('User Inactive', response.message, 'danger');
                            window.location = 'logout.php?logout';
                        } else {
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
                    enableFormSubmitButton('submit-sales-proposal-additional-job-order', 'Submit');
                    $('#sales-proposal-additional-job-order-offcanvas').offcanvas('hide');
                    reloadDatatable('#sales-proposal-additional-job-order-table');
                    resetModalForm('sales-proposal-additional-job-order-form');
                }
            });
        
            return false;
        }
    });
}

function displayDetails(transaction){
    switch (transaction) {
        case 'get sales proposal details':
            const sales_proposal_id = $('#sales-proposal-id').text();
            
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
                        $('#sales_proposal_number').text(response.salesProposalNumber);
                        $('#referred_by').val(response.referredBy);
                        $('#release_date').val(response.releaseDate);
                        $('#start_date').val(response.startDate);
                        $('#term_length').val(response.termLength);
                        $('#number_of_payments').val(response.numberOfPayments);
                        $('#first_due_date').val(response.firstDueDate);
                        $('#remarks').val(response.remarks);

                        checkOptionExist('#product_id', response.productID, '');
                        checkOptionExist('#comaker_id', response.comakerID, '');
                        checkOptionExist('#term_type', response.termType, '');
                        checkOptionExist('#payment_frequency', response.paymentFrequency, '');
                        checkOptionExist('#for_registration', response.forRegistration, '');
                        checkOptionExist('#with_cr', response.withCR, '');
                        checkOptionExist('#for_transfer', response.forTransfer, '');
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
                }
            });
            break;
        case 'get sales proposal accessories details':
                var sales_proposal_accessories_id = sessionStorage.getItem('sales_proposal_accessories_id');
                
                $.ajax({
                    url: 'controller/sales-proposal-controller.php',
                    method: 'POST',
                    dataType: 'json',
                    data: {
                        sales_proposal_accessories_id : sales_proposal_accessories_id, 
                        transaction : transaction
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#sales_proposal_accessories_id').val(sales_proposal_accessories_id);
                            $('#accessories').val(response.accessories);
                            $('#accessories_cost').val(response.cost);
                        } 
                        else {
                            if(response.isInactive){
                                window.location = 'logout.php?logout';
                            }
                            else{
                                showNotification('Get Sales Proposal Accessories Details Error', response.message, 'danger');
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
        case 'get sales proposal job order details':
                var sales_proposal_job_order_id = sessionStorage.getItem('sales_proposal_job_order_id');
                
                $.ajax({
                    url: 'controller/sales-proposal-controller.php',
                    method: 'POST',
                    dataType: 'json',
                    data: {
                        sales_proposal_job_order_id : sales_proposal_job_order_id, 
                        transaction : transaction
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#sales_proposal_job_order_id').val(sales_proposal_job_order_id);
                            $('#job_order').val(response.jobOrder);
                            $('#job_order_cost').val(response.cost);
                        } 
                        else {
                            if(response.isInactive){
                                window.location = 'logout.php?logout';
                            }
                            else{
                                showNotification('Get Sales Proposal Job Order Details Error', response.message, 'danger');
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
        case 'get sales proposal additional job order details':
                var sales_proposal_additional_job_order_id = sessionStorage.getItem('sales_proposal_additional_job_order_id');
                
                $.ajax({
                    url: 'controller/sales-proposal-controller.php',
                    method: 'POST',
                    dataType: 'json',
                    data: {
                        sales_proposal_additional_job_order_id : sales_proposal_additional_job_order_id, 
                        transaction : transaction
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#sales_proposal_additional_job_order_id').val(sales_proposal_additional_job_order_id);
                            $('#job_order_number').val(response.jobOrderNumber);
                            $('#job_order_date').val(response.jobOrderDate);
                            $('#particulars').val(response.particulars);
                            $('#additional_job_order_cost').val(response.cost);
                        } 
                        else {
                            if(response.isInactive){
                                window.location = 'logout.php?logout';
                            }
                            else{
                                showNotification('Get Sales Proposal Additional Job Order Details Error', response.message, 'danger');
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
        case 'get product details':
            const product_id = $('#product_id').val();
            
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
                        $('#product_engine_number').text(response.engineNumber);
                        $('#product_chassis_number').text(response.chassisNumber);
                        $('#product_plate_number').text(response.plateNumber);
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
            break;
        case 'get comaker details':
            const comaker_id = $('#comaker_id').val();
            
            $.ajax({
                url: 'controller/customer-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    comaker_id : comaker_id, 
                    transaction : transaction
                },
                success: function(response) {
                    if (response.success) {
                        $('#comaker_file_as').text(response.comakerName);
                        $('#comaker_address').text(response.comakerAddress);
                        $('#comaker_email').text(response.comakerEmail);
                        $('#comaker_mobile').text(response.chassisNumber);
                        $('#comaker_telephone').text(response.comakerMobile);
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Co-Maker Details Error', response.message, 'danger');
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

function calculatePaymentNumber() {
    var payment_frequency = $('#payment_frequency').val();
    var term_length = $('#term_length').val();

    let number_of_payments = 0;

    switch (payment_frequency) {
        case 'Lumpsum':
            number_of_payments = 1;
            break;
        case 'Monthly':
            number_of_payments = term_length;
            break;
        case 'Quarterly':
            number_of_payments = checkIntegerDivision(term_length, 3);
            break;
        case 'Semi-Annual':
            number_of_payments = checkIntegerDivision(term_length, 6);
            break;
        default:
            number_of_payments = 0;
    }

    $('#number_of_payments').val(number_of_payments);

    calculateFirstDueDate();
}

function checkIntegerDivision(dividend, divisor) {
    let result = dividend / divisor;
    return Number.isInteger(result) ? result : 0;
}

function calculateFirstDueDate(){
    var start_date = $("#start_date").val();
    var payment_frequency = $("#payment_frequency").val();
    var number_of_payments = $("#number_of_payments").val();

    $.ajax({
        type: "POST",
        url: "./config/calculate_first_due_date.php",
        data: { start_date: start_date, payment_frequency: payment_frequency, number_of_payments: number_of_payments },
        success: function (result) {
            $('#first_due_date').val(result);
        }
    });
}

function nextStep(currentStep) {
    $('#sales-proposal-tab-' + currentStep).attr('disabled', true);
    
    $('#sales-proposal-tab-' + (currentStep + 1)).tab('show');
    
    $('#sales-proposal-tab-' + currentStep + 1).removeAttr('disabled');
}

function prevStep(currentStep) {
    $('#sales-proposal-tab-' + currentStep).attr('disabled', true);
    
    $('#sales-proposal-tab-' + (currentStep - 1)).tab('show');
    
    $('#sales-proposal-tab-' + currentStep - 1).removeAttr('disabled');
}