(function($) {
    'use strict';

    $(function() {
        if($('#insurance-policy-table').length){
            insurancePolicyTable('#insurance-policy-table');
        }

        if($('#cancel-form').length){
            cancelForm();
        }

        if($('#insurance-policy-id').length){
            displayDetails('get insurance policy details');
        }

        $(document).on('click','.delete-insurance-policy',function() {
            const insurance_policy_id = $(this).data('insurance-policy-id');
            const transaction = 'delete insurance policy';
    
            Swal.fire({
                title: 'Confirm Insurance Policy Deletion',
                text: 'Are you sure you want to delete this insurance policy?',
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
                        url: 'controller/insurance-policy-controller.php',
                        dataType: 'json',
                        data: {
                            insurance_policy_id : insurance_policy_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete Insurance Policy Success', 'The insurance policy has been deleted successfully.', 'success');
                                reloadDatatable('#insurance-policy-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Delete Insurance Policy Error', 'The insurance policy does not exist.', 'danger');
                                    reloadDatatable('#insurance-policy-table');
                                }
                                else {
                                    showNotification('Delete Insurance Policy Error', response.message, 'danger');
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

        $(document).on('click','#delete-insurance-policy',function() {
            let insurance_policy_id = [];
            const transaction = 'delete multiple insurance policy';

            $('.datatable-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    insurance_policy_id.push(element.value);
                }
            });
    
            if(insurance_policy_id.length > 0){
                Swal.fire({
                    title: 'Confirm Multiple Insurance Policy Deletion',
                    text: 'Are you sure you want to delete these insurance policy?',
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
                            url: 'controller/insurance-policy-controller.php',
                            dataType: 'json',
                            data: {
                                insurance_policy_id: insurance_policy_id,
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Delete Insurance Policy Success', 'The selected insurance policy have been deleted successfully.', 'success');
                                        reloadDatatable('#insurance-policy-table');
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else {
                                        showNotification('Delete Insurance Policy Error', response.message, 'danger');
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
                showNotification('Deletion Multiple Insurance Policy Error', 'Please select the insurance policy you wish to delete.', 'danger');
            }
        });
    });
})(jQuery);

function insurancePolicyTable(datatable_name, buttons = false, show_all = false){
    const policy = 'insurance policy table';
    var settings;

    const column = [ 
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'POLICY_NUMBER' },
        { 'data' : 'CUSTOMER_NAME' },
        { 'data' : 'INSURANCE_TYPE' },
        { 'data' : 'PROVIDER_NAME' },
        { 'data' : 'PREMIUM_AMOUNT' },
        { 'data' : 'INCEPTION_DATE' },
        { 'data' : 'EXPIRY_DATE' },
        { 'data' : 'STATUS' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '1%','bSortable': false, 'aTargets': 0 },
        { 'width': '15%','bSortable': false, 'aTargets': 9 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_insurance_policy_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {'type' : policy},
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

function insurancePolicyForm(){
    $('#insurance-policy-form').validate({
        rules: {
            policy_type: {
                required: true
            },
            inception_date: {
                required: true
            },
            customer_type: {
                required: true
            },
            insurance_policy_id: {
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
            policy_type: {
                required: 'Please choose the policy type'
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
            insurance_policy_id: {
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
            const insurance_policy_id = $('#insurance-policy-id').text();
            const transaction = 'save insurance policy';

            $.ajax({
                type: 'POST',
                url: 'controller/insurance-policy-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&insurance_policy_id=' + insurance_policy_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-data');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert Insurance Policy Success' : 'Update Insurance Policy Success';
                        const notificationDescription = response.insertRecord ? 'The insurance policy has been inserted successfully.' : 'The insurance policy has been updated successfully.';
                        
                        setNotification(notificationMessage, notificationDescription, 'success');
                        window.location = 'insurance-policy.php?id=' + response.insurancePolicyID;
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
            const insurance_policy_id = $('#insurance-policy-id').text();
            const transaction = 'tag cancel';

            $.ajax({
                type: 'POST',
                url: 'controller/insurance-policy-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&insurance_policy_id=' + insurance_policy_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-data');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = 'Tag Insurance Policy As Received Success';
                        const notificationDescription = 'The insurance policy has been tagged as canceld successfully.';
                        
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
        case 'get insurance policy details':
            const insurance_policy_id = $('#insurance-policy-id').text();
            
            $.ajax({
                url: 'controller/insurance-policy-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    insurance_policy_id : insurance_policy_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetForm('insurance-policy-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#insurance_policy_id').val(insurance_policy_id);

                        $('#policy_number').text(response.policyNumber);
                        $('#insurance_type_1').text(response.insuranceType);
                        $('#insurance_type_2').text(response.insuranceType);
                        $('#coverage_amount').text(response.coverageAmount);
                        $('#premium_amount').text(response.premiumAmount);
                        $('#expiry_date_1').text(response.expiryDate);
                        $('#expiry_date_2').text(response.expiryDate);
                        $('#inception_date').text(response.inceptionDate);
                        $('#provider_name').text(response.providerName);
                        $('#customer_name').text(response.customer);
                        $('#mobile').text(response.mobile);
                        $('#address').text(response.address);
                        $('#year_model').text(response.yearModel);
                        $('#make').text(response.make);
                        $('#color').text(response.color);
                        $('#plate_number').text(response.plateNumber);
                        $('#engine_number').text(response.engineNumber);
                        $('#chassis_number').text(response.chassisNumber);
                        $('#mv_file_number').text(response.mvFileNumber);
                        
                        $('#policy_status_1').html(`
                            <span class="badge bg-${response.statusBadge}">
                                ${response.status}
                            </span>
                        `);
                        
                        $('#policy_status_2').html(`
                            <span class="badge bg-${response.statusBadge}">
                                ${response.status}
                            </span>
                        `);
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Insurance Policy Details Error', response.message, 'danger');
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