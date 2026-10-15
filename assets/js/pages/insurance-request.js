(function($) {
    'use strict';

    $(function() {
        if($('#insurance-request-table').length){
            insuranceRequestTable('#insurance-request-table');
        }

        if($('#insurance-request-form').length){
            insuranceRequestForm();
        }

        if($('#receive-form').length){
            receiveForm();
        }

        if($('#cancel-form').length){
            cancelForm();
        }

        if($('#insurance-request-id').length){
            window.isLoadingDetails = true; // Lock calculations/fetches
    
            displayDetails('get insurance request details').always(function() {
                // Unlock after details are fully loaded and rendered
                window.isLoadingDetails = false;
                calculateInsurance(); // Run once safely at the end
            });
        }

        $(document).on('click','.delete-insurance-request',function() {
            const insurance_request_id = $(this).data('insurance-request-id');
            const transaction = 'delete insurance request';
    
            Swal.fire({
                title: 'Confirm Insurance Request Deletion',
                text: 'Are you sure you want to delete this insurance request?',
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
                        url: 'controller/insurance-request-controller.php',
                        dataType: 'json',
                        data: {
                            insurance_request_id : insurance_request_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete Insurance Request Success', 'The insurance request has been deleted successfully.', 'success');
                                reloadDatatable('#insurance-request-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Delete Insurance Request Error', 'The insurance request does not exist.', 'danger');
                                    reloadDatatable('#insurance-request-table');
                                }
                                else {
                                    showNotification('Delete Insurance Request Error', response.message, 'danger');
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

        $(document).on('click','#delete-insurance-request',function() {
            let insurance_request_id = [];
            const transaction = 'delete multiple insurance request';

            $('.datatable-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    insurance_request_id.push(element.value);
                }
            });
    
            if(insurance_request_id.length > 0){
                Swal.fire({
                    title: 'Confirm Multiple Insurance Request Deletion',
                    text: 'Are you sure you want to delete these insurance request?',
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
                            url: 'controller/insurance-request-controller.php',
                            dataType: 'json',
                            data: {
                                insurance_request_id: insurance_request_id,
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Delete Insurance Request Success', 'The selected insurance request have been deleted successfully.', 'success');
                                        reloadDatatable('#insurance-request-table');
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else {
                                        showNotification('Delete Insurance Request Error', response.message, 'danger');
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
                                toggleHideActionDropdown();
                            }
                        });
                        
                        return false;
                    }
                });
            }
            else{
                showNotification('Deletion Multiple Insurance Request Error', 'Please select the insurance request you wish to delete.', 'danger');
            }
        });

        $(document).on('click','#delete-insurance-request-details',function() {
            const insurance_request_id = $('#insurance-request-id').text();
            const transaction = 'delete insurance request';
    
            Swal.fire({
                title: 'Confirm Insurance Request Deletion',
                text: 'Are you sure you want to delete this insurance request?',
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
                        url: 'controller/insurance-request-controller.php',
                        dataType: 'json',
                        data: {
                            insurance_request_id : insurance_request_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Deleted Insurance Request Success', 'The insurance request has been deleted successfully.', 'success');
                                window.location = 'insurance-request.php';
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
                                    showNotification('Delete Insurance Request Error', response.message, 'danger');
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
            discardCreate('insurance-request.php');
        });
        
        $(document).on('click','#duplicate-insurance-request',function() {
            const insurance_request_id = $('#insurance-request-id').text();
            const transaction = 'duplicate insurance request';
    
            Swal.fire({
                title: 'Confirm Insurance Request Duplication',
                text: 'Are you sure you want to duplicate this insurance request?',
                icon: 'info',
                showCancelButton: !0,
                confirmButtonText: 'Duplicate',
                cancelButtonText: 'Cancel',
                confirmButtonClass: 'btn btn-info mt-2',
                cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                buttonsStyling: !1
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        type: 'POST',
                        url: 'controller/insurance-request-controller.php',
                        dataType: 'json',
                        data: {
                            insurance_request_id : insurance_request_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Duplicate Insurance Request Success', 'The insurance request has been duplicated successfully.', 'success');
                                window.location = 'insurance-request.php?id=' + response.insuranceRequestID;
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Duplicate Insurance Request Error', 'The insurance request does not exist.', 'danger');
                                    reloadDatatable('#insurance-request-table');
                                }
                                else {
                                    showNotification('Duplicate Insurance Request Error', response.message, 'danger');
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

        $(document).on('change', '#customer_type', function () {
            let customerType = $(this).val();
            $('.customer-field, .misc-field, .sales-proposal-field, .product-field, .vehicle-field').addClass('d-none');
            
            if($('#assured').length){
                document.getElementById("assured").readOnly = false;
            }
                
            document.getElementById("year_model").readOnly = false;
            document.getElementById("color").readOnly = false;
            document.getElementById("make").readOnly = false;
            document.getElementById("plate_number").readOnly = false;
            document.getElementById("chassis_number").readOnly = false;
            document.getElementById("engine_number").readOnly = false;
            document.getElementById("mv_file_number").readOnly = false;

            if (customerType === 'Customer') {
                $('.customer-field').removeClass('d-none');
                $('.vehicle-field').removeClass('d-none');
            }
            else if (customerType === 'Miscellaneous') {
                $('.misc-field').removeClass('d-none');
                $('.vehicle-field').removeClass('d-none');
            }
            else if (customerType === 'Sales Proposal') {
                $('.sales-proposal-field').removeClass('d-none');
                $('.vehicle-field').removeClass('d-none');
                
                document.getElementById("year_model").readOnly = true;
                document.getElementById("color").readOnly = true;
                document.getElementById("make").readOnly = true;
                document.getElementById("plate_number").readOnly = true;
                document.getElementById("chassis_number").readOnly = true;
                document.getElementById("engine_number").readOnly = true;
                document.getElementById("mv_file_number").readOnly = true;
                
                if($('#assured').length){
                    document.getElementById("assured").readOnly = true;
                }
            }
            else if (customerType === 'Product') {
                $('.product-field').removeClass('d-none');
                $('.vehicle-field').removeClass('d-none');
                
                document.getElementById("year_model").readOnly = true;
                document.getElementById("color").readOnly = true;
                document.getElementById("make").readOnly = true;
                document.getElementById("plate_number").readOnly = true;
                document.getElementById("chassis_number").readOnly = true;
                document.getElementById("engine_number").readOnly = true;
                document.getElementById("mv_file_number").readOnly = true;
            }
        });

        $(document).on('change', '#product_id', function () {
            let product_id = $(this).val();
            displayProductDetails(product_id);
        });

        $(document).on('change', '#sales_proposal_id', function () {
            let sales_proposal_id = $(this).val();
            displaySalesProposalDetails(sales_proposal_id);
        });

        $(document).on('change', '#request_type', function () {
            let requestType = $(this).val();

            $('.renewal-field').addClass('d-none');

            if (requestType === 'Renewal') {
                $('.renewal-field').removeClass('d-none');
            }
        });

        $(document).on('click','#for-submission',function() {
            const insurance_request_id = $('#insurance-request-id').text();
            const transaction = 'tag request for submission';
    
            Swal.fire({
                title: 'Confirm Insurance Request For Submission',
                text: 'Are you sure you want to tag this insurance request for submission?',
                icon: 'warning',
                showCancelButton: !0,
                confirmButtonText: 'For Submission',
                cancelButtonText: 'Cancel',
                confirmButtonClass: 'btn btn-info mt-2',
                cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                buttonsStyling: !1
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        type: 'POST',
                        url: 'controller/insurance-request-controller.php',
                        dataType: 'json',
                        data: {
                            insurance_request_id : insurance_request_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Insurance Request For Submission Success', 'The insurance request has been tagged for submission successfully.', 'success');
                                window.location.reload();
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else {
                                    showNotification('Insurance Request For Submission Error', response.message, 'danger');
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

        $(document).on('click','#submitted',function() {
            const insurance_request_id = $('#insurance-request-id').text();
            const transaction = 'tag request submitted';
    
            Swal.fire({
                title: 'Confirm Insurance Request Submitted',
                text: 'Are you sure you want to tag this insurance request as submitted?',
                icon: 'warning',
                showCancelButton: !0,
                confirmButtonText: 'Submitted',
                cancelButtonText: 'Cancel',
                confirmButtonClass: 'btn btn-info mt-2',
                cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                buttonsStyling: !1
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        type: 'POST',
                        url: 'controller/insurance-request-controller.php',
                        dataType: 'json',
                        data: {
                            insurance_request_id : insurance_request_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Insurance Request Submitted Success', 'The insurance request has been tagged as submitted successfully.', 'success');
                                window.location.reload();
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else {
                                    showNotification('Insurance Request Submitted Error', response.message, 'danger');
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

        $(document).on('click','#draft',function() {
            const insurance_request_id = $('#insurance-request-id').text();
            const transaction = 'tag request draft';
    
            Swal.fire({
                title: 'Confirm Insurance Request Draft',
                text: 'Are you sure you want to tag this insurance request draft?',
                icon: 'warning',
                showCancelButton: !0,
                confirmButtonText: 'Draft',
                cancelButtonText: 'Cancel',
                confirmButtonClass: 'btn btn-info mt-2',
                cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                buttonsStyling: !1
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        type: 'POST',
                        url: 'controller/insurance-request-controller.php',
                        dataType: 'json',
                        data: {
                            insurance_request_id : insurance_request_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Insurance Request Draft Success', 'The insurance request has been tagged as draft successfully.', 'success');
                                window.location.reload();
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else {
                                    showNotification('Insurance Request Draft Error', response.message, 'danger');
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

        $(document).on('change', '#insurance_category, #insurance_provider_id', function () {
            if (window.isLoadingDetails) return;
            fetchCategoryRates();
            fetchTaxRates();
        });

        $(document).on('change', '#tpbi_coverage, #tppd_coverage, #insurance_provider_id', function () {
            fetchCoverageRates();
        });

        $(document).on('change', '#coverage_type', function () {
            // If we are currently loading details, skip resetting the rate
            if (window.isLoadingDetails) return;

            let coverage_type = $(this).val();
            var mode = $('#encoding_mode').val();
            $('#od_theft_rate').val(0);

            if (mode == 'Auto') {
                if (coverage_type == 'OD/Theft') {
                    fetchCategoryRates();
                    document.getElementById("od_theft_rate").readOnly = true;
                } else {
                    document.getElementById("od_theft_rate").readOnly = false;
                }
            } else {
                document.getElementById("od_theft_rate").readOnly = false;

                if (coverage_type == 'OD/Theft') {
                    fetchCategoryRates();
                }
            }            

            calculateInsurance();
        });

        const insuranceInputsSelectors = [
            '#insurance_category',
            '#ctpl_coverage', '#all_risk_coverage', '#od_theft_coverage', 
            '#aon_coverage', '#tpbi_coverage', '#tppd_coverage', '#par_coverage',
            '#ctpl_rate', '#all_risk_rate', '#od_theft_rate', 
            '#aon_rate', '#tpbi_rate', '#tppd_rate', '#par_rate',
            '#ctpl_premium', '#all_risk_premium', '#od_theft_premium', 
            '#aon_premium', '#tpbi_premium', '#tppd_premium', '#par_premium',
            '#total_premium', '#vat_premium_tax', '#docstamp', 
            '#local_tax', '#gross',
            '#od_theft_commission_rate', '#aon_commission_rate', 
            '#tpbi_commission_rate', '#tppd_commission_rate', '#par_commission_rate',
            '#od_theft_commission', '#aon_commission', 
            '#tpbi_commission', '#tppd_commission', '#par_commission',
            '#commission_subtotal', '#commission_discount_rate', 
            '#commission_discount', '#net_commission', '#net_premium'
        ].join(', ');

        $(document).on('input change', insuranceInputsSelectors, function () {
            if (window.isLoadingDetails) return;
            calculateInsurance();
        });

        function toggleReadonly() {
            var mode = $('#encoding_mode').val();
            var customerType = $('#customer_type').val(); // Adjust selector if needed
            
            // Exclude #collection_amount from the base collection
            var $allInputs = $('input[type="number"]').not('#collection_amount');

            if (mode === 'Auto') {
                // Set everything to readonly first
                $allInputs.prop('readonly', true);

                // If customer_type is Customer or Miscellaneous, make these specific fields editable
                if (customerType === 'Customer' || customerType === 'Miscellaneous') {
                    $('#od_theft_coverage, #aon_coverage').prop('readonly', false);
                }
            } else {
                // Remove readonly from all targeted inputs when not in Auto mode
                $allInputs.prop('readonly', false);
            }
        }

        // Run on initial page load
        toggleReadonly();

        // Run whenever Encoding Mode changes
        $('#encoding_mode').on('change', function () {
            toggleReadonly();
            calculateInsurance();
        });

        $('#inception_date2').on('changeDate', function (e) {
            let date = new Date(e.detail.date);

            date.setFullYear(date.getFullYear() + 1);

            let month = String(date.getMonth() + 1).padStart(2, '0');
            let day = String(date.getDate()).padStart(2, '0');
            let year = date.getFullYear();

            let expirationDate = `${month}/${day}/${year}`;

            $('#expiration_date').val(expirationDate);
        });

         $(document).on('click','#apply-filter',function() {
            insuranceRequestTable('#insurance-request-table');
        });
    });
})(jQuery);

function insuranceRequestTable(datatable_name, buttons = false, show_all = false){
    const request = 'insurance request table';
    
    const status_filter = $('#status_filter').val();
    const request_type_filter = $('#request_type_filter').val();
    const filter_inception_start_date = $('#filter_inception_start_date').val();
    const filter_inception_end_date = $('#filter_inception_end_date').val();
    var insurance_provider_filter_values = [];
    var insurance_category_filter_values = [];

    $('.provider-filter:checked').each(function() {
        insurance_provider_filter_values.push($(this).val());
    });

    $('.insurance-category-filter:checked').each(function() {
        insurance_category_filter_values.push($(this).val());
    });

    var insurance_provider_filter = insurance_provider_filter_values.join(', ');
    var insurance_category_filter = insurance_category_filter_values.join(', ');

    var settings;

    const column = [ 
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'CUSTOMER_NAME' },
        { 'data' : 'PROVIDER_NAME' },
        { 'data' : 'REQUEST_TYPE' },
        { 'data' : 'INSURANCE_TYPE' },
        { 'data' : 'INCEPTION_DATE' },
        { 'data' : 'CATEGORY' },
        { 'data' : 'STATUS' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '1%','bSortable': false, 'aTargets': 0 },
        { 'width': 'auto', 'type':'date', 'aTargets': 5 },
        { 'width': '15%','bSortable': false, 'aTargets': 8 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_insurance_request_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {
                'type' : request,
                'status_filter' : status_filter,
                'request_type_filter' : request_type_filter,
                'filter_inception_start_date' : filter_inception_start_date,
                'filter_inception_end_date' : filter_inception_end_date,
                'insurance_provider_filter' : insurance_provider_filter,
                'insurance_category_filter' : insurance_category_filter,
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
        'order': [[ 5, 'desc' ]],
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

function insuranceRequestForm(){
    $('#insurance-request-form').validate({
        rules: {
            request_type: {
                required: true
            },
            inception_date: {
                required: true
            },
            customer_type: {
                required: true
            },
            insurance_type_id: {
                required: true
            },
            paid_by: {
                required: true
            },
            collection_amount: {
                required: true
            },
            customer_id: {
                required: function () {
                    return $('#customer_type').val() === 'Customer';
                }
            },
            misc_id: {
                required: function () {
                    return $('#customer_type').val() === 'Miscellaneous';
                }
            },
            sales_proposal_id: {
                required: function () {
                    return $('#customer_type').val() === 'Sales Proposal';
                }
            },
            year_model: {
                required: function () {
                    return $('#customer_type').val() != 'Sales Proposal' || $('#customer_type').val() != 'Product';
                }
            },
            make: {
                required: function () {
                    return $('#customer_type').val() != 'Sales Proposal' || $('#customer_type').val() != 'Product';
                }
            },
            plate_number: {
                required: function () {
                    return $('#customer_type').val() != 'Sales Proposal' || $('#customer_type').val() != 'Product';
                }
            },
        },
        messages: {
            request_type: {
                required: 'Please choose the request type'
            },
            inception_date: {
                required: 'Please choose the inception date'
            },
            customer_type: {
                required: 'Please choose the customer type'
            },
            customer_id: {
                required: 'Please choose the customer'
            },
            paid_by: {
                required: 'Please enter the name of the person who paid'
            },
            misc_id: {
                required: 'Please choose the customer'
            },
            insurance_type_id: {
                required: 'Please choose the insurance type'
            },
            sales_proposal_id: {
                required: 'Please choose the sales proposal'
            },
            year_model: {
                required: 'Please enter the year model'
            },
            make: {
                required: 'Please enter the make'
            },
            plate_number: {
                required: 'Please enter the plate number'
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
            const insurance_request_id = $('#insurance-request-id').text();
            const transaction = 'save insurance request';

            $.ajax({
                type: 'POST',
                url: 'controller/insurance-request-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&insurance_request_id=' + insurance_request_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-data');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert Insurance Request Success' : 'Update Insurance Request Success';
                        const notificationDescription = response.insertRecord ? 'The insurance request has been inserted successfully.' : 'The insurance request has been updated successfully.';
                        
                        setNotification(notificationMessage, notificationDescription, 'success');
                        window.location = 'insurance-request.php?id=' + response.insuranceRequestID;
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

function receiveForm(){
    $('#receive-form').validate({
        rules: {
            policy_number: {
                required: true
            },
            inception_date2: {
                required: true
            },
            expiration_date: {
                required: true
            },
            gross: {
                required: true
            },
            net_premium: {
                required: true
            },
            coverage_amount: {
                required: true
            },
        },
        messages: {
            policy_number: {
                required: 'Please enter the policy number'
            },
            inception_date2: {
                required: 'Please choose the expiration date'
            },
            expiration_date: {
                required: 'Please choose the expiration date'
            },
            gross: {
                required: 'Please enter the gross amount'
            },
            net_premium: {
                required: 'Please enter the net premium amount'
            },
            coverage_amount: {
                required: 'Please enter the coverage amount'
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
            const insurance_request_id = $('#insurance-request-id').text();
            const transaction = 'tag request received';

            $.ajax({
                type: 'POST',
                url: 'controller/insurance-request-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&insurance_request_id=' + insurance_request_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-data');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = 'Tag Insurance Request As Received Success';
                        const notificationDescription = 'The insurance request has been tagged as received successfully.';
                        
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
                    enableFormSubmitButton('submit-data', 'Save');
                }
            });
        
            return false;
        }
    });
}

function cancelForm(){
    $('#cancel-form').validate({
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
            const insurance_request_id = $('#insurance-request-id').text();
            const transaction = 'tag request cancelled';

            $.ajax({
                type: 'POST',
                url: 'controller/insurance-request-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&insurance_request_id=' + insurance_request_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-cancel');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = 'Tag Insurance Request As Cancelled Success';
                        const notificationDescription = 'The insurance request has been tagged as cancelled successfully.';
                        
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
                    enableFormSubmitButton('submit-cancel', 'Save');
                }
            });
        
            return false;
        }
    });
}

function displayDetails(transaction) {
    switch (transaction) {
        case 'get insurance request details':
            const insurance_request_id = $('#insurance-request-id').text();
            
            return $.ajax({
                url: 'controller/insurance-request-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    insurance_request_id : insurance_request_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetForm('insurance-request-form');
                },
                success: function(response) {
                    if (response.success) {
                        window.isLoadingDetails = true;

                        $('#insurance_request_id').val(insurance_request_id);
                        $('#inception_date').val(response.inceptionDate);

                        $('#year_model').val(response.yearModel);
                        $('#color').val(response.color);
                        $('#make').val(response.make);
                        $('#plate_number').val(response.plateNumber);
                        $('#chassis_number').val(response.chassisNumber);
                        $('#engine_number').val(response.engineNumber);
                        $('#mv_file_number').val(response.mvFileNumber);
                        $('#paid_by').val(response.paidBy);
                        $('#assured').val(response.assured);
                        $('#collection_amount').val(response.collectionAmount);

                        checkOptionExist('#customer_type', response.customerType, '');
                        checkOptionExist('#insurance_policy_id', response.insurancePolicyId, '');
                        checkOptionExist('#request_type', response.requestType, '');
                        checkOptionExist('#insurance_provider_id', response.insuranceProvider, '');
                        checkOptionExist('#insurance_type_id', response.insuranceTypeId, '');
                        checkOptionExist('#insurance_category', response.insuranceCategory, '');
                        checkOptionExist('#tpbi_coverage', response.tpbiCoverage, '');
                        checkOptionExist('#tppd_coverage', response.tppdCoverage, '');
                        checkOptionExist('#coverage_type', response.coverageType, '');
                        checkOptionExist('#encoding_mode', response.encodingMode, '');

                        // Coverages
                        $('#ctpl_coverage').val(response.ctplCoverage);
                        $('#od_theft_coverage').val(response.odTheftCoverage);
                        $('#aon_coverage').val(response.aonCoverage);
                        $('#par_coverage').val(response.parCoverage);

                        // Rates
                        $('#ctpl_rate').val(response.ctplRate);
                        $('#od_theft_rate').val(response.odTheftRate);
                        $('#aon_rate').val(response.aonRate);
                        $('#tpbi_rate').val(response.tpbiRate);
                        $('#tppd_rate').val(response.tppdRate);
                        $('#par_rate').val(response.parRate);

                        // Premiums
                        $('#ctpl_premium').val(response.ctplPremium);
                        $('#od_theft_premium').val(response.odTheftPremium);
                        $('#aon_premium').val(response.aonPremium);
                        $('#tpbi_premium').val(response.tpbiPremium);
                        $('#tppd_premium').val(response.tppdPremium);
                        $('#par_premium').val(response.parPremium);

                        // Totals & Taxes
                        $('#total_premium').val(response.totalPremium);
                        $('#vat_premium_tax').val(response.vatPremiumTax);
                        $('#docstamp').val(response.docstamp);
                        $('#local_tax').val(response.localTax);
                        $('#gross').val(response.gross);

                        // Commission Rates
                        $('#od_theft_commission_rate').val(response.premiumComissionRate);
                        $('#aon_commission_rate').val(response.aonComissionRate);
                        $('#tpbi_commission_rate').val(response.tpbiComissionRate);
                        $('#tppd_commission_rate').val(response.tppdComissionRate);
                        $('#par_commission_rate').val(response.parComissionRate);

                        // Commissions
                        $('#od_theft_commission').val(response.premiumCommission);
                        $('#aon_commission').val(response.aonCommission);
                        $('#tpbi_commission').val(response.tpbiCommission);
                        $('#tppd_commission').val(response.tppdCommission);
                        $('#par_commission').val(response.parCommission);

                        // Subtotals & Summary
                        $('#commission_subtotal').val(response.commissionSubtotal);
                        $('#commission_discount_rate').val(response.commissionDiscountRate);
                        $('#commission_discount').val(response.commissionDiscount);
                        $('#net_commission').val(response.netCommission);
                        $('#net_premium').val(response.netPremium);

                        // Customer Selection Mapping
                        if (response.customerType === 'Customer') {
                            checkOptionExist('#customer_id', response.customerId, '');
                        } else if (response.customerType === 'Miscellaneous') {
                            checkOptionExist('#misc_id', response.customerId, '');
                        } else if(response.customerType === 'Product'){
                            checkOptionExist('#product_id', response.productId, '');
                            displayProductDetails(response.productId);
                        } else {
                            checkOptionExist('#sales_proposal_id', response.salesProposalId, '');
                            displaySalesProposalDetails(response.salesProposalId);
                        }

                        if(response.encodingMode == 'Auto'){
                            if(response.coverageType == 'OD/Theft'){
                                document.getElementById("od_theft_rate").readOnly = true;
                            }
                            else{
                                document.getElementById("od_theft_rate").readOnly = false;
                            }
                        }
                        else{
                            document.getElementById("od_theft_rate").readOnly = false;
                        }


                        window.isLoadingDetails = false;

                        // Recalculate options/totals dynamically
                        if (typeof calculateInsurance === 'function') {
                            calculateInsurance();
                        }
                    } else {
                        window.isLoadingDetails = false;

                        if (response.isInactive) {
                            window.location = 'logout.php?logout';
                        } else {
                            showNotification('Get Insurance Request Details Error', response.message, 'danger');
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

function displayProductDetails(product_id){
    let transaction = 'get product details';
    
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
                    $('#engine_number').val(response.engineNumber);
                    $('#chassis_number').val(response.chassisNumber);
                    $('#color').val(response.colorName);
                    $('#year_model').val(response.year_model);
                    $('#make').val(response.make_name);
                    $('#plate_number').val(response.plateNumber);
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
            },
        });
    }
}

function displaySalesProposalDetails(sales_proposal_id){
    let transaction = 'get sales proposal unit details';
    
    if(sales_proposal_id != '' && sales_proposal_id != 0){
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
                    let product_id = response.productID;
                    displayProductDetails(product_id);
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
            },
        });
    }
}

let saveTimer = null;

function calculateInsurance(skipSave = false) {
    // 1. Fetch Input Values safely
    const encoding_mode = $('#encoding_mode').val();
    const category = $('#insurance_category').val();
    const coverage_type = $('#coverage_type').val();

    // Shared field values for OD/Theft and All Risk
    const baseCoverage = parseFloat($('#od_theft_coverage').val()) || 0;
    const baseRate = parseFloat($('#od_theft_rate').val()) || 0;

    // Conditionally assign based on coverage_type and zero out the other for backend payload
    const odTheftCoverage = (coverage_type === 'OD/Theft') ? baseCoverage : 0;
    const allRiskCoverage = (coverage_type === 'All Risk') ? baseCoverage : 0;

    const odTheftRate = (coverage_type === 'OD/Theft') ? baseRate : 0;
    const allRiskRate = (coverage_type === 'All Risk') ? baseRate : 0;

    if (encoding_mode == 'Auto') {
        const ctplCoverage = parseFloat($('#ctpl_coverage').val()) || 0;
        const aonCoverage = parseFloat($('#aon_coverage').val()) || 0;
        const tpbiCoverage = parseFloat($('#tpbi_coverage').val()) || 0;
        const tppdCoverage = parseFloat($('#tppd_coverage').val()) || 0;
        const parCoverage = parseFloat($('#par_coverage').val()) || 0;
        const tppdPremium = parseFloat($('#tppd_premium').val()) || 0;
        const tpbiPremium = parseFloat($('#tpbi_premium').val()) || 0;
        const localGovernmentTaxRate = parseFloat($('#local_government_tax_rate').val()) || 0;

        const ctplRate = parseFloat($('#ctpl_rate').val()) || 0;
        const aonRate = parseFloat($('#aon_rate').val()) || 0;
        const tpbiRate = 0;
        const tppdRate = 0;
        const parRate = parseFloat($('#par_rate').val()) || 0;

        // 2. Premium Calculations
        const ctplPremium = ctplCoverage * (ctplRate / 100);
        const allRiskPremium = allRiskCoverage * (allRiskRate / 100);
        const odTheftPremium = odTheftCoverage * (odTheftRate > 1 ? odTheftRate / 100 : odTheftRate);
        const aonPremium = aonCoverage * (aonRate / 100);
        const parPremium = parCoverage * (parRate / 100);

        // Determine the active premium for the shared UI input field & commissions
        const activePremium = (coverage_type === 'All Risk') ? allRiskPremium : odTheftPremium;

        const totalPremium = ctplPremium + allRiskPremium + odTheftPremium + aonPremium + tpbiPremium + tppdPremium + parPremium;

        // 3. Tax Breakdowns
        const vatPremiumTax = totalPremium * 0.12;
        const docstamp = totalPremium * 0.125;
        const localTax = totalPremium * (localGovernmentTaxRate / 100);
        const gross = totalPremium + vatPremiumTax + docstamp + localTax;

        // 4. Commission Calculations (using activePremium so it works for both All Risk and OD/Theft)
        const odTheftCommissionRate = parseFloat($('#od_theft_commission_rate').val()) || 0;
        const premiumCommission = activePremium * (odTheftCommissionRate > 1 ? odTheftCommissionRate / 100 : odTheftCommissionRate);
        const aonCommission = aonPremium * (parseFloat($('#aon_commission_rate').val()) / 100 || 0.05);
        const tpbiCommission = tpbiPremium * (parseFloat($('#tpbi_commission_rate').val()) / 100 || 0.15);
        const tppdCommission = tppdPremium * (parseFloat($('#tppd_commission_rate').val()) / 100 || 0.15);
        const parCommission = parPremium * (parseFloat($('#par_commission_rate').val()) / 100 || 0);

        const commissionSubtotal = premiumCommission + aonCommission + tpbiCommission + tppdCommission + parCommission;
        const discountRate = parseFloat($('#commission_discount_rate').val()) || 0;
        const commissionDiscount = commissionSubtotal * (discountRate / 100);
        const netCommission = commissionSubtotal - commissionDiscount;

        // Net Premium Calculation
        const netPremium = gross - netCommission;

        // 5. Update Input fields (Using activePremium for the shared #od_theft_premium input)
        $('#ctpl_premium').val(ctplPremium.toFixed(2));
        $('#all_risk_premium').val(allRiskPremium.toFixed(2));
        $('#od_theft_premium').val(activePremium.toFixed(2)); 
        $('#aon_premium').val(aonPremium.toFixed(2));
        $('#tpbi_premium').val(tpbiPremium.toFixed(2));
        $('#tppd_premium').val(tppdPremium.toFixed(2));
        $('#par_premium').val(parPremium.toFixed(2));

        // 6. Update text displays
        $('#gross_display, #gross').val(gross.toFixed(2));
        $('#net_premium_display, #net_premium').val(netPremium.toFixed(2));
        $('#net_commission_display, #net_commission').val(netCommission.toFixed(2));

        $('#total_premium').val(totalPremium.toFixed(2));
        $('#vat_premium_tax').val(vatPremiumTax.toFixed(2));
        $('#docstamp').val(docstamp.toFixed(2));
        $('#local_tax').val(localTax.toFixed(2));

        $('#od_theft_commission').val(premiumCommission.toFixed(2));
        $('#aon_commission').val(aonCommission.toFixed(2));
        $('#tpbi_commission').val(tpbiCommission.toFixed(2));
        $('#tppd_commission').val(tppdCommission.toFixed(2));
        $('#par_commission').val(parCommission.toFixed(2));
        $('#commission_subtotal').val(commissionSubtotal.toFixed(2));
        $('#commission_discount').val(commissionDiscount.toFixed(2));

        // 7. Construct Payload for Backend API
        const computationData = {
            insurance_request_id: $('#insurance-request-id').text().trim(),
            transaction: 'save insurance request computation',
            
            insurance_category: category,
            coverage_type: coverage_type,
            encoding_mode: encoding_mode,
            
            // Coverages
            ctpl_coverage: ctplCoverage,
            all_risk_coverage: allRiskCoverage,
            od_theft_coverage: odTheftCoverage,
            aon_coverage: aonCoverage,
            tpbi_coverage: tpbiCoverage,
            tppd_coverage: tppdCoverage,
            par_coverage: parCoverage,
            
            // Rates
            ctpl_rate: ctplRate,
            all_risk_rate: allRiskRate,
            od_theft_rate: odTheftRate,
            aon_rate: aonRate,
            tpbi_rate: tpbiRate,
            tppd_rate: tppdRate,
            par_rate: parRate,
            
            // Premiums
            ctpl_premium: ctplPremium,
            all_risk_premium: allRiskPremium,
            od_theft_premium: odTheftPremium,
            aon_premium: aonPremium,
            tpbi_premium: tpbiPremium,
            tppd_premium: tppdPremium,
            par_premium: parPremium,
            
            // Totals & Taxes
            total_premium: totalPremium,
            vat_premium_tax: vatPremiumTax,
            docstamp: docstamp,
            local_tax: localTax,
            gross: gross,
            
            // Commission Rates
            premium_commission_rate: odTheftCommissionRate,
            aon_commission_rate: parseFloat($('#aon_commission_rate').val()) || 0,
            tpbi_commission_rate: parseFloat($('#tpbi_commission_rate').val()) || 0,
            tppd_commission_rate: parseFloat($('#tppd_commission_rate').val()) || 0,
            par_commission_rate: parseFloat($('#par_commission_rate').val()) || 0,
            
            // Commissions
            premium_commission: premiumCommission,
            aon_commission: aonCommission,
            tpbi_commission: tpbiCommission,
            tppd_commission: tppdCommission,
            par_commission: parCommission,
            
            // Subtotals & Discounts
            commission_subtotal: commissionSubtotal,
            commission_discount_rate: discountRate,
            commission_discount: commissionDiscount,
            net_commission: netCommission,
            net_premium: netPremium
        };

        if (!skipSave) {
            saveComputation(computationData);
        }
    } else {
        const basePremium = parseFloat($('#od_theft_premium').val()) || 0;
        const allRiskPremium = (coverage_type === 'All Risk') ? basePremium : 0;
        const odTheftPremium = (coverage_type === 'OD/Theft') ? basePremium : 0;

        const computationData = {
            insurance_request_id: $('#insurance-request-id').text().trim(),
            transaction: 'save insurance request computation',
            
            insurance_category: category,
            encoding_mode: encoding_mode,
            coverage_type: coverage_type,
            
            // Coverages
            ctplCoverage : parseFloat($('#ctpl_coverage').val()) || 0,
            allRiskCoverage : allRiskCoverage,
            odTheftCoverage : odTheftCoverage,
            aonCoverage : parseFloat($('#aon_coverage').val()) || 0,
            tpbiCoverage : parseFloat($('#tpbi_coverage').val()) || 0,
            tppdCoverage : parseFloat($('#tppd_coverage').val()) || 0,
            parCoverage : parseFloat($('#par_coverage').val()) || 0,

            ctplRate : parseFloat($('#ctpl_rate').val()) || 0,
            allRiskRate : allRiskRate,
            odTheftRate : odTheftRate,
            aonRate : parseFloat($('#aon_rate').val()) || 0,
            tpbiRate : parseFloat($('#tpbi_rate').val()) || 0,
            tppdRate : parseFloat($('#tppd_rate').val()) || 0,
            parRate : parseFloat($('#par_rate').val()) || 0,

            ctplPremium : parseFloat($('#ctpl_premium').val()) || 0,
            allRiskPremium : allRiskPremium,
            odTheftPremium : odTheftPremium,
            aonPremium : parseFloat($('#aon_premium').val()) || 0,
            tpbiPremium : parseFloat($('#tpbi_premium').val()) || 0,
            tppdPremium : parseFloat($('#tppd_premium').val()) || 0,
            parPremium : parseFloat($('#par_premium').val()) || 0,

            totalPremium : parseFloat($('#total_premium').val()) || 0,
            vatPremiumTax : parseFloat($('#vat_premium_tax').val()) || 0,
            docstamp : parseFloat($('#docstamp').val()) || 0,
            localTax : parseFloat($('#local_tax').val()) || 0,
            gross : parseFloat($('#gross').val()) || 0,

            odTheftCommissionRate : parseFloat($('#od_theft_commission_rate').val()) || 0,
            aonCommissionRate : parseFloat($('#aon_commission_rate').val()) || 0,
            tpbiCommissionRate : parseFloat($('#tpbi_commission_rate').val()) || 0,
            tppdCommissionRate : parseFloat($('#tppd_commission_rate').val()) || 0,
            parCommissionRate : parseFloat($('#par_commission_rate').val()) || 0,

            premiumCommission : parseFloat($('#od_theft_commission').val()) || 0,
            aonCommission : parseFloat($('#aon_commission').val()) || 0,
            tpbiCommission : parseFloat($('#tpbi_commission').val()) || 0,
            tppdCommission : parseFloat($('#tppd_commission').val()) || 0,
            parCommission : parseFloat($('#par_commission').val()) || 0,

            commissionSubtotal : parseFloat($('#commission_subtotal').val()) || 0,
            discountRate : parseFloat($('#commission_discount_rate').val()) || 0,
            commissionDiscount : parseFloat($('#commission_discount').val()) || 0,
            netCommission : parseFloat($('#net_commission').val()) || 0,
            netPremium : parseFloat($('#net_premium').val()) || 0,
        };

        if (!skipSave) {
            saveComputation(computationData);
        }
    }
}

function saveComputation(payload) {
    // Prevent missing request ID from firing requests
    if (!payload.insurance_request_id) return;

    // Clear previous pending save request
    clearTimeout(saveTimer);

    // Delay request by 500ms (Debounce)
    saveTimer = setTimeout(function () {
        $.ajax({
            type: 'POST',
            url: 'controller/insurance-request-controller.php',
            data: payload,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    console.log('Saved successfully:', response);
                } else {
                    if (response.isInactive) {
                        window.location = 'logout.php?logout';
                    } else {
                        console.error('Save failed:', response.message);
                    }
                }
            },
            error: function(xhr, status, error) {
                console.error('Save AJAX failed:', error);
            }
        });
    }, 500);
}

// Function to fetch fixed OD/Theft rate & commission rate based on Provider & Category
function fetchCategoryRates() {
    if (window.isLoadingDetails) {
        return; 
    }
    
    const providerId = $('#insurance_provider_id').val();
    const categoryId = $('#insurance_category').val();

    if (!providerId || !categoryId) return;

    $.ajax({
        url: 'controller/insurance-request-controller.php',
        method: 'POST',
        dataType: 'json',
        data: {
            transaction: 'get category rates',
            insurance_provider_id: providerId,
            insurance_category_id: categoryId
        },
        success: function (response) {
            if (response.success) {
                $('#od_theft_rate').val(response.od_theft_rate);
                $('#od_theft_commission_rate').val(response.commission_rate);
                calculateInsurance();
            } else if (response.isInactive) {
                window.location = 'logout.php?logout';
            }
        },
        error: function (xhr, status, error) {
            console.error('Fetch Category Rates AJAX error:', error);
        }
    });
}

function fetchTaxRates() {
    const providerId = $('#insurance_provider_id').val();

    if (!providerId) return;

    $.ajax({
        url: 'controller/insurance-request-controller.php',
        method: 'POST',
        dataType: 'json',
        data: {
            transaction: 'get tax rates',
            insurance_provider_id: providerId,
        },
        success: function (response) {
            if (response.success) {
                $('#local_government_tax_rate').val(response.local_government_tax_rate);
                calculateInsurance();
            } else if (response.isInactive) {
                window.location = 'logout.php?logout';
            }
        },
        error: function (xhr, status, error) {
            console.error('Fetch Tax Rates AJAX error:', error);
        }
    });
}

// Function to fetch fixed TPBI & TPPD rates/amounts based on Coverage selected
function fetchCoverageRates() {
    const providerId = $('#insurance_provider_id').val();
    const tpbiCoverage = parseFloat($('#tpbi_coverage').val()) || 0;
    const tppdCoverage = parseFloat($('#tppd_coverage').val()) || 0;

    if (!providerId) return;

    // Fetch TPBI rate matching coverage
    if (tpbiCoverage > 0) {
        $.ajax({
            url: 'controller/insurance-request-controller.php',
            method: 'POST',
            dataType: 'json',
            data: {
                transaction: 'get coverage rates',
                insurance_provider_id: providerId,
                coverage_amount: tpbiCoverage
            },
            success: function (response) {
                if (response.success) {
                    $('#tpbi_premium').val(response.bi_rate);
                    calculateInsurance();
                }
            }
        });
    }
    else{
        $('#tpbi_premium').val(0);
    }

    // Fetch TPPD fixed amount matching coverage
    if (tppdCoverage > 0) {
        $.ajax({
            url: 'controller/insurance-request-controller.php',
            method: 'POST',
            dataType: 'json',
            data: {
                transaction: 'get coverage rates',
                insurance_provider_id: providerId,
                coverage_amount: tppdCoverage
            },
            success: function (response) {
                if (response.success) {
                    // Update tppd_rate with fixed pd_amount rate
                    $('#tppd_premium').val(response.pd_amount);
                    calculateInsurance();
                }
            }
        });
    }
    else{
        $('#tppd_premium').val(0);
    }
}