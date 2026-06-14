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

        if($('#insurance-request-id').length){
            displayDetails('get insurance request details');
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

            resetField();
            $('.customer-field, .misc-field, .sales-proposal-field, .vehicle-field').addClass('d-none');

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
                $('.vehicle-field').addClass('d-none');
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

        $(document).on('change', '#insurance_category', function () {
            calculateInsurance();
        });
        
        $(document).on('change', '#od_theft_coverage', function () {
            calculateInsurance();
        });

        $(document).on('change', '#aon_coverage', function () {
            calculateInsurance();
        });

        $(document).on('change', '#tpbi_coverage', function () {
            calculateInsurance();
        });

        $(document).on('change', '#tppd_coverage', function () {
            calculateInsurance();
        });
    });
})(jQuery);

function resetField(){
    $('#year_model').val('');
    $('#color').val('');
    $('#make').val('');
    $('#plate_number').val('');
    $('#chassis_number').val('');
    $('#engine_number').val('');
    $('#mv_file_number').val('');
}

function insuranceRequestTable(datatable_name, buttons = false, show_all = false){
    const request = 'insurance request table';
    var settings;

    const column = [ 
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'CUSTOMER_NAME' },
        { 'data' : 'PROVIDER_NAME' },
        { 'data' : 'REQUEST_TYPE' },
        { 'data' : 'INSURANCE_TYPE' },
        { 'data' : 'INCEPTION_DATE' },
        { 'data' : 'STATUS' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '1%','bSortable': false, 'aTargets': 0 },
        { 'width': '15%','bSortable': false, 'aTargets': 7 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_insurance_request_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {'type' : request},
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
            insurance_request_id: {
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
                    return $('#customer_type').val() != 'Sales Proposal';
                }
            },
            make: {
                required: function () {
                    return $('#customer_type').val() != 'Sales Proposal';
                }
            },
            plate_number: {
                required: function () {
                    return $('#customer_type').val() != 'Sales Proposal';
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
            misc_id: {
                required: 'Please choose the customer'
            },
            insurance_request_id: {
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
            premium_amount: {
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
            premium_amount: {
                required: 'Please enter the premium amount'
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

function displayDetails(transaction){
    switch (transaction) {
        case 'get insurance request details':
            const insurance_request_id = $('#insurance-request-id').text();
            
            $.ajax({
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
                        $('#insurance_request_id').val(insurance_request_id);
                        $('#inception_date').val(response.inceptionDate);
                        $('#od_theft_coverage').val(response.odTheftCoverage);
                        $('#aon_coverage').val(response.aonCoverage);

                        $('#year_model').val(response.yearModel);
                        $('#color').val(response.color);
                        $('#make').val(response.make);
                        $('#plate_number').val(response.plateNumber);
                        $('#chassis_number').val(response.chassisNumber);
                        $('#engine_number').val(response.engineNumber);
                        $('#mv_file_number').val(response.mvFileNumber);

                        checkOptionExist('#customer_type', response.customerType, '');

                        checkOptionExist('#request_type', response.requestType, '');
                        checkOptionExist('#insurance_provider_id', response.insuranceProvider, '');
                        checkOptionExist('#insurance_type_id', response.insuranceTypeId, '');
                        checkOptionExist('#insurance_category', response.insuranceCategory, '');
                        checkOptionExist('#tpbi_coverage', response.tpbiCoverage, '');
                        checkOptionExist('#tppd_coverage', response.tppdCoverage, '');

                        if(response.customerType == 'Customer'){
                            checkOptionExist('#customer_id', response.customerId, '');
                        }
                        else if(response.customerType == 'Miscellaneous'){
                            checkOptionExist('#misc_id', response.customerId, '');
                        }
                        else{
                            checkOptionExist('#sales_proposal_id', response.salesProposalId, '');
                        }
                        
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
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

// 1. Centralized Business Logic Configuration (Easy to update later)
const RATES_CONFIG = {
    categories: {
        'Trucks':               { rate1: 0.024,  rate2: 0.26 },
        'Equipment':            { rate1: 0.015,  rate2: 0.20 },
        'Equipment with AON':   { rate1: 0.0185, rate2: 0.20 }
    },
    defaultCategory:            { rate1: 0.01,   rate2: 0 },
    aonRate: 0.005,
    coverageTables: {
        100000: { bi: 465,  pd: 1290 },
        200000: { bi: 660,  pd: 1395 },
        300000: { bi: 855,  pd: 1515 },
        400000: { bi: 975,  pd: 1590 },
        500000: { bi: 1095, pd: 1680 }
    }
};

function calculateInsurance() {
    // 2. Fetch Input Values safely (with fallbacks to prevent NaN errors)
    const category = $('#insurance_category').val();
    const odTheftCoverage = parseFloat($('#od_theft_coverage').val()) || 0;
    const aonCoverage = parseFloat($('#aon_coverage').val()) || 0;
    const tpbiCoverage = $('#tpbi_coverage').val();

    // 3. Resolve rates using config lookup map
    const catData = RATES_CONFIG.categories[category] || RATES_CONFIG.defaultCategory;
    const tableData = RATES_CONFIG.coverageTables[tpbiCoverage] || { bi: 0, pd: 0 };

    // 4. Perform Premium Calculations
    const odTheftPremium = odTheftCoverage * catData.rate1;
    const aonPremium = aonCoverage * RATES_CONFIG.aonRate;
    const tpbiPremium = tableData.bi;
    const tppdPremium = tableData.pd;

    const totalPremium = odTheftPremium + aonPremium + tpbiPremium + tppdPremium;
    
    // Tax Breakdowns
    const vatPremiumTax = totalPremium * 0.12;
    const docstamp = totalPremium * 0.125;
    const localTax = totalPremium * 0.005;
    const gross = totalPremium + vatPremiumTax + docstamp + localTax;

    // 5. Commission Calculations
    const premiumCommission = odTheftPremium * catData.rate2;
    const aonCommission = aonPremium * 0.05;
    const tpbiCommission = tpbiPremium * 0.15;
    const tppdCommission = tppdPremium * 0.15;
    const parCommission = 0;
    
    const commissionSubtotal = premiumCommission + aonCommission + tpbiCommission + tppdCommission + parCommission;
    const commissionDiscount = 0; // Keeping as placeholder if needed later
    const netCommission = commissionSubtotal - commissionDiscount;

    // Net Premium Calculation
    const netPremium = gross - netCommission;

    // 6. Helper function to format currency consistently
    const toPHP = num => '₱' + num.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

    // 7. Update Inputs fields (using .val)
    $('#od_theft_premium').val(odTheftPremium.toFixed(2));
    $('#aon_premium').val(aonPremium.toFixed(2));
    $('#tpbi_premium').val(tpbiPremium.toFixed(2));
    $('#tppd_premium').val(tppdPremium.toFixed(2));

    // 8. Update text layouts and fixed bug on KPI displays (using .text)
    $('#gross_display, #gross').text(toPHP(gross));
    $('#net_premium_display, #net_premium').text(toPHP(netPremium));
    $('#net_commission_display, #net_commission').text(toPHP(netCommission));

    $('#total_premium').text(toPHP(totalPremium));
    $('#vat_premium_tax').text(toPHP(vatPremiumTax));
    $('#docstamp').text(toPHP(docstamp));
    $('#local_tax').text(toPHP(localTax));

    $('#premium_comission').text(toPHP(premiumCommission));
    $('#aon_comission').text(toPHP(aonCommission));
    $('#tpbi_comission').text(toPHP(tpbiCommission));
    $('#tppd_comission').text(toPHP(tppdCommission));
    $('#par_comission').text(toPHP(parCommission));
    $('#comission_subtotal').text(toPHP(commissionSubtotal));
    $('#commission_discount').text(toPHP(commissionDiscount));

    const computationData = {
        insurance_request_id : $('#insurance-request-id').text(),
        transaction : 'save insurance request computation',
        insurance_category: category,
        od_theft_coverage: odTheftCoverage,
        aon_coverage: aonCoverage,
        tpbi_coverage: tpbiCoverage,
        tppd_coverage: $('#tppd_coverage').val(), // Added these if you need them sent
        ctpl_coverage: $('#ctpl_coverage').val() || 0,
        par_coverage: $('#par_coverage').val() || 0,
        
        od_theft_premium: odTheftPremium,
        aon_premium: aonPremium,
        tpbi_premium: tpbiPremium,
        tppd_premium: tppdPremium,
        ctpl_premium: 0, // placeholder based on your HTML
        par_premium: 0,
        
        total_premium: totalPremium,
        vat_premium_tax: vatPremiumTax,
        docstamp: docstamp,
        local_tax: localTax,
        gross: gross,
        net_premium: netPremium,
        
        premium_comission: premiumCommission,
        aon_comission: aonCommission,
        tpbi_comission: tpbiCommission,
        tppd_comission: tppdCommission,
        par_comission: parCommission,
        comission_subtotal: commissionSubtotal,
        commission_discount: commissionDiscount,
        net_commission: netCommission
    };

    saveComputation(computationData);
}

function saveComputation(payload) {
    $.ajax({
        type: 'POST',
        url: 'controller/insurance-request-controller.php',
        data: payload,
        success: function(response) {
            console.log('Saved successfully:', response);
        },
        error: function(xhr, status, error) {
            console.error('Save failed:', error);
        }
    });
}