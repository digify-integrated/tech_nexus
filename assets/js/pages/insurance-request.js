(function($) {
    'use strict';

    $(function() {
        if($('#insurance-request-table').length){
            insuranceRequestTable('#insurance-request-table');
        }

        if($('#insurance-request-form').length){
            insuranceRequestForm();
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

            $('.customer-field, .misc-field, .sales-proposal-field').addClass('d-none');

            if (customerType === 'Customer') {
                $('.customer-field').removeClass('d-none');
            }
            else if (customerType === 'Miscellaneous') {
                $('.misc-field').removeClass('d-none');
            }
            else if (customerType === 'Sales Proposal') {
                $('.sales-proposal-field').removeClass('d-none');
            }
        });
    });
})(jQuery);

function insuranceRequestTable(datatable_name, buttons = false, show_all = false){
    const request = 'insurance request table';
    var settings;

    const column = [ 
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'INQUIRY_TYPE_NAME' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '1%','bSortable': false, 'aTargets': 0 },
        { 'width': '84%', 'aTargets': 1 },
        { 'width': '15%','bSortable': false, 'aTargets': 2 }
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
            }
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

                        checkOptionExist('#customer_type', response.customerType, '');

                        if(response.customerType == 'Customer'){
                            checkOptionExist('#customer_id', response.customerId, '');
                        }
                        else if(response.customerType == 'Miscellaneous'){
                            checkOptionExist('##misc_id', response.customerId, '');
                        }
                        else{
                            checkOptionExist('#salesProposalId', response.salesProposalId, '');
                        }

                        checkOptionExist('#request_type', response.requestType, '');
                        checkOptionExist('#insurance_provider_id', response.insuranceProvider, '');
                        checkOptionExist('#insurance_type_id', response.insuranceTypeId, '');
                        
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