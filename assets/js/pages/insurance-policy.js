(function($) {
    'use strict';

    $(function() {
        if($('#insurance-policy-table').length){
            insurancePolicyTable('#insurance-policy-table');
        }

        if($('#cancel-form').length){
            cancelForm();
        }

        if($('#collect-form').length){
            collectForm();
        }

        if($('#remit-form').length){
            remitForm();
        }

        if($('#multiple-collect-form').length){
            multipleCollectForm();
        }

        if($('#multiple-remit-form').length){
            multipleRemitForm();
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

        $(document).on('click','#apply-filter',function() {
            insurancePolicyTable('#insurance-policy-table');
        });
    });
})(jQuery);

function insurancePolicyTable(datatable_name) {

    const type = 'insurance policy table';

    const status_filter = $('#status_filter').val();
    const remittance_status_filter = $('#remittance_status_filter').val();
    const payment_status_filter = $('#payment_status_filter').val();
    const filter_inception_start_date = $('#filter_inception_start_date').val();
    const filter_inception_end_date = $('#filter_inception_end_date').val();
    const filter_expiry_start_date = $('#filter_expiry_start_date').val();
    const filter_expiry_end_date = $('#filter_expiry_end_date').val();

    const column = [
        { data: 'CHECK_BOX' },
        { data: 'PROVIDER_NAME' },
        { data: 'ASSURED' },
        { data: 'STOCK_NUMBER' },
        { data: 'PLATE_NUMBER' },
        { data: 'CHASSIS_NUMBER' },
        { data: 'ENGINE_NUMBER' },
        { data: 'LAST_YEAR_COVERAGE' },
        { data: 'COVERAGE' },
        { data: 'STATUS' },
        { data: 'MATURITY_DATE' },
        { data: 'POLICY_NUMBER' },
        { data: 'CUSTOMER_NAME' },
        { data: 'INSURANCE_TYPE' },
        { data: 'GROSS' },
        { data: 'NET_PREMIUM' },
        { data: 'INCEPTION_DATE' },
        { data: 'EXPIRY_DATE' },
        { data: 'REMITTANCE_DATE' },
        { data: 'PAYMENT_DATE' },
        { data: 'INS_STATUS' },
        { data: 'ACTION' }
    ];

    const column_definition = [
        { width: '1%', bSortable: false, targets: 0 },
        { width: 'auto', type: 'date', targets: 10 },
        { width: 'auto', type: 'date', targets: 16 },
        { width: 'auto', type: 'date', targets: 17 },
        { width: 'auto', type: 'date', targets: 18 },
        { width: '15%', bSortable: false, targets: 19 }
    ];

    destroyDatatable(datatable_name);

    const table = $(datatable_name).DataTable({
        ajax: {
            url: 'view/_insurance_policy_generation.php',
            method: 'POST',
            dataType: 'json',
            data: {
                type: type,
                status_filter: status_filter,
                remittance_status_filter: remittance_status_filter,
                payment_status_filter: payment_status_filter,
                filter_inception_start_date: filter_inception_start_date,
                filter_inception_end_date: filter_inception_end_date,
                filter_expiry_start_date: filter_expiry_start_date,
                filter_expiry_end_date: filter_expiry_end_date
            },
            dataSrc: '',
            error: function (xhr, status, error) {
                let fullErrorMessage = `XHR status: ${status}, Error: ${error}`;

                if (xhr.responseText) {
                    fullErrorMessage += `, Response: ${xhr.responseText}`;
                }

                showErrorDialog(fullErrorMessage);
            }
        },

        order: [[7, 'asc']],
        columns: column,
        columnDefs: column_definition,

        // Hide the default DataTables buttons
        dom: 'flrtip',

        language: {
            emptyTable: 'No data found',
            searchPlaceholder: 'Search...',
            search: '',
            loadingRecords: 'Just a moment while we fetch your data...'
        },

        buttons: [
            {
                extend: 'csvHtml5',
                title: 'Insurance Policy List'
            },
            {
                extend: 'excelHtml5',
                title: 'Insurance Policy List'
            },
            {
                extend: 'pdfHtml5',
                title: 'Insurance Policy List'
            }
        ]
    });

    $('#export-csv').off('click').on('click', function () {
        table.button(0).trigger();
    });

    $('#export-excel').off('click').on('click', function () {
        table.button(1).trigger();
    });

    $('#export-pdf').off('click').on('click', function () {
        table.button(2).trigger();
    });
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
                        const notificationMessage = 'Tag Insurance Policy As Cancelled Success';
                        const notificationDescription = 'The insurance policy has been tagged as cancelled successfully.';
                        
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

function collectForm(){
    $('#collect-form').validate({
        rules: {
            payment_date: {
                required: true
            },
        },
        messages: {
            payment_date: {
                required: 'Please choose the collection date'
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
            const transaction = 'collect insurance policy';

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
                        const notificationMessage = 'Tag Insurance Policy As Collected Success';
                        const notificationDescription = 'The insurance policy has been tagged as collected successfully.';
                        
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

function multipleCollectForm(){
    $('#multiple-collect-form').validate({
        rules: {
            payment_date: {
                required: true
            },
        },
        messages: {
            payment_date: {
                required: 'Please choose the collection date'
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
            let insurance_policy_id = [];
            const transaction = 'collect multiple insurance policy'; // or 'collect multiple insurance policy'

            $('.datatable-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    insurance_policy_id.push(element.value);
                }
            });

            // Build the data string properly with array brackets []
            let formData = $(form).serialize() + '&transaction=' + encodeURIComponent(transaction);
            insurance_policy_id.forEach(id => {
                formData += '&insurance_policy_id[]=' + encodeURIComponent(id);
            });

            $.ajax({
                type: 'POST',
                url: 'controller/insurance-policy-controller.php',
                data: formData,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-collect');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = 'Tag Insurance Policy As Collected Success';
                        const notificationDescription = 'The insurance policy has been tagged as collected successfully.';
                        
                        showNotification(notificationMessage, notificationDescription, 'success');
                        reloadDatatable('#insurance-policy-table');
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
                    enableFormSubmitButton('submit-collect', 'Save');
                    $('#collect-offcanvas').offcanvas('hide');
                }
            });
        
            return false;
        }
    });
}

function remitForm(){
    $('#remit-form').validate({
        rules: {
            remittance_date: {
                required: true
            },
        },
        messages: {
            remittance_date: {
                required: 'Please choose the remittance date'
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
            const transaction = 'remit insurance policy';

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
                        const notificationMessage = 'Tag Insurance Policy As Remitted Success';
                        const notificationDescription = 'The insurance policy has been tagged as remitted successfully.';
                        
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

function multipleRemitForm(){
    $('#multiple-remit-form').validate({
        rules: {
            remittance_date: {
                required: true
            },
        },
        messages: {
            remittance_date: {
                required: 'Please choose the remittance date'
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
            let insurance_policy_id = [];
            const transaction = 'remit multiple insurance policy'; // or 'collect multiple insurance policy'

            $('.datatable-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    insurance_policy_id.push(element.value);
                }
            });

            // Build the data string properly with array brackets []
            let formData = $(form).serialize() + '&transaction=' + encodeURIComponent(transaction);
            insurance_policy_id.forEach(id => {
                formData += '&insurance_policy_id[]=' + encodeURIComponent(id);
            });

            $.ajax({
                type: 'POST',
                url: 'controller/insurance-policy-controller.php',
                data: formData,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-remit');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = 'Tag Insurance Policy As Remitted Success';
                        const notificationDescription = 'The insurance policy has been tagged as remitted successfully.';
                        
                        showNotification(notificationMessage, notificationDescription, 'success');
                        reloadDatatable('#insurance-policy-table');
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
                    enableFormSubmitButton('submit-remit', 'Save');
                    $('#remit-offcanvas').offcanvas('hide');
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
                        $('#gross_amount').text(response.grossAmount);
                        $('#net_premium_amount').text(response.netPremiumAmount);
                        $('#expiry_date_1').text(response.expiryDate);
                        $('#expiry_date_2').text(response.expiryDate);
                        $('#collection_date').text(response.paymentDate);
                        $('#remittance_date').text(response.remittanceDate);
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
                        $('#paid_by').text(response.paidBy);
                        $('#assured').text(response.assured);
                        
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
                        
                        $('#collection_status').html(`
                            <span class="badge bg-${response.collectionBadge}">
                                ${response.payment_status}
                            </span>
                        `);
                        
                        $('#remittance_status').html(`
                            <span class="badge bg-${response.remittanceBadge}">
                                ${response.remittance_status}
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