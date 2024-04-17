(function($) {
    'use strict';

    $(function() {
        if($('#sales-proposal-table').length){
            salesProposalTable('#sales-proposal-table');
        }

        if($('#all-sales-proposal-table').length){
            allSalesProposalTable('#all-sales-proposal-table');
        }

        if($('#sales-proposal-job-order-table').length){
            salesProposalJobOrderTable('#sales-proposal-job-order-table');
        }

        if($('#sales-proposal-form').length){
            salesProposalForm();
        }

        if($('#sales-proposal-unit-details-form').length){
            salesProposalUnitForm();
        }

        if($('#sales-proposal-fuel-details-form').length){
            salesProposalFuelForm();
        }

        if($('#sales-proposal-refinancing-details-form').length){
            salesProposalRefinancingForm();
        }

        if($('#sales-proposal-job-order-form').length){
            salesProposalJobOrderForm();
        }

        $(document).on('change','#product_type',function() {
            var productType = $(this).val();

            if($('#sales-proposal-tab-2').length && $('#sales-proposal-tab-3').length && $('#sales-proposal-tab-4').length){
                $('#sales-proposal-tab-2, #sales-proposal-tab-3, #sales-proposal-tab-4').addClass('d-none');

                if (productType === 'Unit') {
                    $('#sales-proposal-tab-2').removeClass('d-none');
                    resetModalForm('sales-proposal-unit-details-form');
                }
                else if (productType === 'Fuel') {
                    $('#sales-proposal-tab-3').removeClass('d-none');
                    resetModalForm('sales-proposal-fuel-details-form');
                }
                else if (productType === 'Refinancing') {
                    $('#sales-proposal-tab-4').removeClass('d-none');
                    resetModalForm('sales-proposal-refinancing-details-form');
                }
            }

            if($(this).val() == 'Unit' || $(this).val() == 'Fuel'){
                if($('#delivery_price').length){
                    $('#delivery_price').prop('readonly', true);
                }
            }
            else{
                if($('#delivery_price').length){
                    $('#delivery_price').prop('readonly', false);
                }
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
        });

        $(document).on('change','#start_date',function() {
            calculateFirstDueDate();
            alert();
        });

        $(document).on('change','#payment_frequency',function() {
            calculateFirstDueDate();
        });

        $(document).on('change','#number_of_payments',function() {
            calculateFirstDueDate();
        });

        $(document).on('change','#payment_frequency',function() {
            calculatePaymentNumber();
        });

        $(document).on('change','#term_length',function() {
            calculatePaymentNumber();
        });

        $(document).on('change','#product_id',function() {  
            if($(this).val() != ''){
                displayDetails('get product details');
            }
            else{
                $('#old_color').val('');
                $('#old_body').val('');
                $('#old_engine').val('');
            }
        });

        $(document).on('change','#for_change_color',function() {
            if($(this).val() == 'Yes'){
                $("#new_color").attr("readonly", false); 
            }
            else{
                $('#new_color').val('');
                $("#new_color").attr("readonly", true); 
            }
        });

        $(document).on('change','#for_change_body',function() {
            if($(this).val() == 'Yes'){
                $("#new_body").attr("readonly", false); 
            }
            else{
                $('#new_body').val('');
                $("#new_body").attr("readonly", true); 
            }
        });

        $(document).on('change','#for_change_engine',function() {
            if($(this).val() == 'Yes'){
                $("#new_engine").attr("readonly", false); 
            }
            else{
                $('#new_engine').val('');
                $("#new_engine").attr("readonly", true); 
            }
        });

        $(document).on('click','#next-step',function() {
            traverseTabs('next');
        });

        $(document).on('click','#previous-step',function() {
            traverseTabs('previous');
        });

        $(document).on('click','#first-step',function() {
            traverseTabs('first');
        });

        $(document).on('click','#last-step',function() {
            traverseTabs('last');
        });

        $(document).on('click','.update-sales-proposal-job-order',function() {
            const sales_proposal_job_order_id = $(this).data('sales-proposal-job-order-id');
    
            sessionStorage.setItem('sales_proposal_job_order_id', sales_proposal_job_order_id);
            
            displayDetails('get sales proposal job order details');
        });

        $(document).on('click','.delete-sales-proposal-job-order',function() {
            const sales_proposal_job_order_id = $(this).data('sales-proposal-job-order-id');
            const transaction = 'delete sales proposal job order';
    
            Swal.fire({
                title: 'Confirm Job Order Deletion',
                text: 'Are you sure you want to delete this job order?',
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
                            sales_proposal_job_order_id : sales_proposal_job_order_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete Job Order Success', 'The job order has been deleted successfully.', 'success');
                                reloadDatatable('#sales-proposal-job-order-table');
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
                                    showNotification('Delete Job Order Error', response.message, 'danger');
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

        displayDetails('get sales proposal basic details');
        displayDetails('get sales proposal unit details');
        displayDetails('get sales proposal fuel details');
        displayDetails('get sales proposal refinancing details');
    });
})(jQuery);

function salesProposalTable(datatable_name, buttons = false, show_all = false){
    const type = 'sales proposal table';
    var customer_id = $('#customer_id').val();
    var sales_proposal_status_filter = $('.sales-proposal-status-filter:checked').val();

    var settings;

    const column = [ 
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'SALES_PROPOSAL_NUMBER' },
        { 'data' : 'CUSTOMER' },
        { 'data' : 'PRODUCT_TYPE' },
        { 'data' : 'PRODUCT' },
        { 'data' : 'STATUS' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '1%','bSortable': false, 'aTargets': 0 },
        { 'width': '14%', 'aTargets': 1 },
        { 'width': '25%', 'aTargets': 2 },
        { 'width': '15%', 'aTargets': 3 },
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
            'data': {'type' : type, 'customer_id' : customer_id, 'sales_proposal_status_filter' : sales_proposal_status_filter},
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

function allSalesProposalTable(datatable_name, buttons = false, show_all = false){
    const type = 'all sales proposal table';
    var sales_proposal_status_filter = $('.sales-proposal-status-filter:checked').val();

    var settings;

    const column = [ 
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'SALES_PROPOSAL_NUMBER' },
        { 'data' : 'CUSTOMER' },
        { 'data' : 'PRODUCT_TYPE' },
        { 'data' : 'PRODUCT' },
        { 'data' : 'CREATED_DATE' },
        { 'data' : 'STATUS' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '1%','bSortable': false, 'aTargets': 0 },
        { 'width': '14%', 'aTargets': 1 },
        { 'width': '15%', 'aTargets': 2 },
        { 'width': '15%', 'aTargets': 3 },
        { 'width': '15%', 'aTargets': 4 },
        { 'width': '10%', 'aTargets': 5},
        { 'width': '10%', 'aTargets': 6 },
        { 'width': '10%','bSortable': false, 'aTargets': 7 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_sales_proposal_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {'type' : type, 'sales_proposal_status_filter' : sales_proposal_status_filter},
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

function salesProposalForm(){
    $('#sales-proposal-form').validate({
        rules: {
            renewal_tag: {
                required: true
            },
            product_type: {
                required: true
            },
            transaction_type: {
                required: true
            },
            financing_institution: {
                required: {
                    depends: function(element) {
                        return $("select[name='transaction_type']").val() === 'Bank Financing';
                    }
                }
            },
            comaker_id: {
                required: {
                    depends: function(element) {
                        return $("select[name='transaction_type']").val() === 'Installment Sales';
                    }
                }
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
            payment_frequency: {
                required: true
            },
            initial_approving_officer: {
                required: true
            },
            final_approving_officer: {
                required: true
            },
        },
        messages: {
            renewal_tag: {
                required: 'Please choose the renewal tag'
            },
            product_type: {
                required: 'Please choose the product type'
            },
            transaction_type: {
                required: 'Please choose the transaction type'
            },
            financing_institution: {
                required: 'Please enter the financing institution'
            },
            comaker_id: {
                required: 'Please choose the co-maker'
            },
            release_date: {
                required: 'Please choose the release date'
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
            payment_frequency: {
                required: 'Please choose the payment frequency'
            },
            initial_approving_officer: {
                required: 'Please choose the initial approving officer'
            },
            final_approving_officer: {
                required: 'Please choose the final approving officer'
            },
        },
        errorPlacement: function (error, element) {
            if (element.hasClass('select2') || element.hasClass('modal-select2')) {
              error.insertAfter(element.next('.select2-container'));
            }
            else if (element.hasClass('form-check-input')) {
                error.insertAfter(element.closest('.form-check-inline'));
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
                    displayDetails('get sales proposal details');
                }
            });
        
            return false;
        }
    });
}

function salesProposalUnitForm(){
    $('#sales-proposal-unit-details-form').validate({
        rules: {
            product_id: {
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
            for_change_color: {
                required: true
            },
            new_color: {
                required: {
                    depends: function(element) {
                        return $("select[name='for_change_color']").val() === 'Yes';
                    }
                }
            },
            for_change_body: {
                required: true
            },
            new_body: {
                required: {
                    depends: function(element) {
                        return $("select[name='for_change_body']").val() === 'Yes';
                    }
                }
            },
            for_change_engine: {
                required: true
            },
            new_engine: {
                required: {
                    depends: function(element) {
                        return $("select[name='for_change_engine']").val() === 'Yes';
                    }
                }
            },
        },
        messages: {
            product_id: {
                required: 'Please choose the stock'
            },
            new_color: {
                required: 'Please enter the new color'
            },
            new_body: {
                required: 'Please enter the new body'
            },
            new_engine: {
                required: 'Please enter the new engine'
            },
        },
        errorPlacement: function (error, element) {
            if (element.hasClass('select2') || element.hasClass('modal-select2')) {
              error.insertAfter(element.next('.select2-container'));
            }
            else if (element.hasClass('form-check-input')) {
                error.insertAfter(element.closest('.form-check-inline'));
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
            const transaction = 'save sales proposal unit';
        
            $.ajax({
                type: 'POST',
                url: 'controller/sales-proposal-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&sales_proposal_id=' + sales_proposal_id + '&customer_id=' + customer_id,
                dataType: 'json',
                success: function (response) {
                    if (!response.success) {
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
                    displayDetails('get sales proposal details');
                }
            });
        
            return false;
        }
    });
}

function salesProposalFuelForm(){
    $('#sales-proposal-fuel-details-form').validate({
        rules: {
            diesel_fuel_quantity: {
                required: true
            },
            diesel_price_per_liter: {
                required: true
            },
            regular_fuel_quantity: {
                required: true
            },
            regular_price_per_liter: {
                required: true
            },
            premium_fuel_quantity: {
                required: true
            },
            premium_price_per_liter: {
                required: true
            },
        },
        messages: {
            diesel_fuel_quantity: {
                required: 'Please enter the fuel quantity'
            },
            diesel_price_per_liter: {
                required: 'Please enter the price per liter'
            },
            regular_fuel_quantity: {
                required: 'Please enter the fuel quantity'
            },
            regular_price_per_liter: {
                required: 'Please enter the price per liter'
            },
            premium_fuel_quantity: {
                required: 'Please enter the fuel quantity'
            },
            premium_price_per_liter: {
                required: 'Please enter the price per liter'
            },
        },
        errorPlacement: function (error, element) {
            if (element.hasClass('select2') || element.hasClass('modal-select2')) {
              error.insertAfter(element.next('.select2-container'));
            }
            else if (element.hasClass('form-check-input')) {
                error.insertAfter(element.closest('.form-check-inline'));
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
            const transaction = 'save sales proposal fuel';
        
            $.ajax({
                type: 'POST',
                url: 'controller/sales-proposal-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&sales_proposal_id=' + sales_proposal_id + '&customer_id=' + customer_id,
                dataType: 'json',
                success: function (response) {
                    if (!response.success) {
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
                    displayDetails('get sales proposal details');
                }
            });
        
            return false;
        }
    });
}

function salesProposalRefinancingForm(){
    $('#sales-proposal-refinancing-details-form').validate({
        rules: {
            ref_engine_no: {
                required: true
            },
            ref_chassis_no: {
                required: true
            },
            ref_plate_no: {
                required: true
            },
        },
        messages: {
            ref_engine_no: {
                required: 'Please enter the engine number'
            },
            ref_chassis_no: {
                required: 'Please enter the chassis number'
            },
            ref_plate_no: {
                required: 'Please enter the plate number'
            },
        },
        errorPlacement: function (error, element) {
            if (element.hasClass('select2') || element.hasClass('modal-select2')) {
              error.insertAfter(element.next('.select2-container'));
            }
            else if (element.hasClass('form-check-input')) {
                error.insertAfter(element.closest('.form-check-inline'));
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
            const transaction = 'save sales proposal refinancing';
        
            $.ajax({
                type: 'POST',
                url: 'controller/sales-proposal-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&sales_proposal_id=' + sales_proposal_id + '&customer_id=' + customer_id,
                dataType: 'json',
                success: function (response) {
                    if (!response.success) {
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
                    displayDetails('get sales proposal details');
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
            const transaction = 'save sales proposal job order';
        
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
                    displayDetails('get sales proposal job order total details');
                    salesProposalSummaryJobOrderTable();
                }
            });
        
            return false;
        }
    });
}

function displayDetails(transaction){
    switch (transaction) {
        case 'get sales proposal basic details':
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
                        $('#sales_proposal_number').text(response.salesProposalNumber);

                        $('#financing_institution').val(response.financingInstitution);
                        $('#referred_by').val(response.referredBy);
                        $('#commission_amount').val(response.commissionAmount);
                        $('#release_date').val(response.releaseDate);
                        $('#start_date').val(response.startDate);
                        $('#term_length').val(response.termLength);
                        $('#number_of_payments').val(response.numberOfPayments);
                        $('#first_due_date').val(response.firstDueDate);
                        $('#remarks').val(response.remarks);

                        checkOptionExist('#renewal_tag', response.renewalTag, '');
                        checkOptionExist('#product_type', response.productType, '');
                        checkOptionExist('#transaction_type', response.transactionType, '');
                        checkOptionExist('#comaker_id', response.comakerID, '');
                        checkOptionExist('#term_type', response.termType, '');
                        checkOptionExist('#payment_frequency', response.paymentFrequency, '');
                        checkOptionExist('#initial_approving_officer', response.initialApprovingOfficer, '');
                        checkOptionExist('#final_approving_officer', response.finalApprovingOfficer, '');
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
                complete: function(){

                }
            });
            break;
        case 'get sales proposal unit details':
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
                        $('#old_color').val(response.oldColor);
                        $('#new_color').val(response.newColor);
                        $('#old_body').val(response.oldBody);
                        $('#new_body').val(response.newBody);
                        $('#old_engine').val(response.oldEngine);
                        $('#new_engine').val(response.newEngine);

                        checkOptionExist('#product_id', response.productID, '');
                        checkOptionExist('#for_registration', response.forRegistration, '');
                        checkOptionExist('#with_cr', response.withCR, '');
                        checkOptionExist('#for_transfer', response.forTransfer, '');
                        checkOptionExist('#for_change_color', response.forChangeColor, '');
                        checkOptionExist('#for_change_body', response.forChangeBody, '');
                        checkOptionExist('#for_change_engine', response.forChangeEngine, '');
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
                complete: function(){

                }
            });
            break;
        case 'get sales proposal fuel details':
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
                        $('#diesel_fuel_quantity').val(response.dieselFuelQuantity);
                        $('#diesel_price_per_liter').val(response.dieselPricePerLiter);
                        $('#regular_fuel_quantity').val(response.regularFuelQuantity);
                        $('#regular_price_per_liter').val(response.regularPricePerLiter);
                        $('#premium_fuel_quantity').val(response.premiumFuelQuantity);
                        $('#premium_price_per_liter').val(response.premiumPricePerLiter);
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
                complete: function(){

                }
            });
            break;
        case 'get sales proposal refinancing details':
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
                        $('#ref_stock_no').text(response.refStockNo);
                        $('#ref_engine_no').val(response.refEngineNo);
                        $('#ref_chassis_no').val(response.refChassisNo);
                        $('#ref_plate_no').val(response.refPlateNo);
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
                complete: function(){

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
        case 'get product details':
            var product_id;
    
            if($('#product_id_details').length){
                product_id = $('#product_id_details').text();
            }
            else{
                product_id = $('#product_id').val();
            }
    
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
                            $('#old_color').val(response.colorName);
                            $('#old_body').val(response.bodyTypeName);
                            $('#old_engine').val(response.engineNumber);
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
    }
}

function checkIntegerDivision(dividend, divisor) {
    let result = dividend / divisor;
    return Number.isInteger(result) ? result : 0;
}

function calculateFirstDueDate(){
    var start_date = $('#start_date').val();
    var payment_frequency = $('#payment_frequency').val();
    var number_of_payments = $('#number_of_payments').val();

    $.ajax({
        type: 'POST',
        url: './config/calculate_first_due_date.php',
        data: { start_date: start_date, payment_frequency: payment_frequency, number_of_payments: number_of_payments },
        success: function (result) {
            $('#first_due_date').val(result);
        }
    });
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

function traverseTabs(direction) {
    var activeTab = $('.nav-link.active');
    var currentTabId = activeTab.attr('href');
    var currentIndex = $('.nav-link').index(activeTab);
    var nextIndex;
    var totalTabs = $('.nav-link').length;

    // Calculate total number of visible tabs
    var visibleTabs = $('.nav-link:not(.d-none)').length;

    function findNextVisibleIndex(startIndex, direction) {
        var index = startIndex;
        var increment = direction === 'next' ? 1 : -1;
        while (true) {
            index = (index + increment + totalTabs) % totalTabs;
            if (!$('.nav-link:eq(' + index + ')').hasClass('d-none')) {
                return index;
            }
            if (index === startIndex) {
                break;
            }
        }
        return startIndex;
    }

    if (direction === 'next') {
        nextIndex = findNextVisibleIndex(currentIndex, 'next');
    } else if (direction === 'previous') {
        nextIndex = findNextVisibleIndex(currentIndex, 'previous');
    } else if (direction === 'first') {
        nextIndex = 0;
    } else if (direction === 'last') {
        nextIndex = totalTabs - 1;
    }

    if (currentIndex == 0) {
        if ($('#sales-proposal-form').valid()) {
            $("#sales-proposal-form").submit();
        } else {
            return;
        }
    }
    else if (currentIndex == 1 && direction === 'next') {
        if ($('#sales-proposal-unit-details-form').valid()) {
            $("#sales-proposal-unit-details-form").submit();
        } else {
            return;
        }
    }
    else if (currentIndex == 2 && direction === 'next') {
        if ($('#sales-proposal-fuel-details-form').valid()) {
            $("#sales-proposal-fuel-details-form").submit();
        } else {
            return;
        }
    }
    else if (currentIndex == 3 && direction === 'next') {
        if ($('#sales-proposal-refinancing-details-form').valid()) {
            $("#sales-proposal-refinancing-details-form").submit();
        } else {
            return;
        }
    }

    if (nextIndex == 4) {
        $('#add-sales-proposal-job-order').removeClass('d-none');
    }
    else{
        $('#add-sales-proposal-job-order').addClass('d-none');
    }

    var nextTabId = $('.nav-link:eq(' + nextIndex + ')').attr('href');

    if (nextTabId !== undefined) {
        if (nextIndex === 0) {
            $('#first-step').addClass('disabled');
            $('#previous-step').addClass('disabled');
        } else {
            $('#first-step').removeClass('disabled');
            $('#previous-step').removeClass('disabled');
        }

        if (nextIndex === totalTabs - 1) {
            $('#last-step').addClass('disabled');
            $('#next-step').addClass('disabled');
        } else {
            $('#last-step').removeClass('disabled');
            $('#next-step').removeClass('disabled');
        }

        $('.nav-link[href="' + nextTabId + '"]').tab('show');

        // Calculate width of the progress bar based on visible tabs
        var progressBarWidth = ((nextIndex + 1) / visibleTabs) * 100;
        $('#bar .progress-bar').css('width', progressBarWidth + '%');
    }
}
