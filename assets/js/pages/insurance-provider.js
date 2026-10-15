(function($) {
    'use strict';

    $(function() {
        if($('#insurance-provider-table').length){
            insuranceProviderTable('#insurance-provider-table');
        }

        if($('#coverage-table').length){
            coverageTable('#coverage-table');
        }

        if($('#insurance-provider-form').length){
            insuranceProviderForm();
        }

        if($('#coverage-form').length){
            coverageForm();
        }

        if($('#category-rate-form').length){
            categoryRateForm();
        }

        $(document).on('click','#add-coverage',function() {
            resetModalForm('coverage-form');
        });

        $(document).on('click','.update-coverage',function() {
            const insurance_coverage_rate_id = $(this).data('insurance-coverage-rate-id');
    
            sessionStorage.setItem('insurance_coverage_rate_id', insurance_coverage_rate_id);
            
            displayDetails('get coverage details');
        });

        $(document).on('click','.delete-coverage',function() {
            const insurance_coverage_rate_id = $(this).data('insurance-coverage-rate-id');
            const transaction = 'delete coverage';
    
            Swal.fire({
                title: 'Confirm Coverage Deletion',
                text: 'Are you sure you want to delete this coverage?',
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
                        url: 'controller/insurance-provider-controller.php',
                        dataType: 'json',
                        data: {
                            insurance_coverage_rate_id : insurance_coverage_rate_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete Coverage Success', 'The coverage has been deleted successfully.', 'success');
                                reloadDatatable('#coverage-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Delete Coverage Error', 'The coverage does not exists.', 'danger');
                                    reloadDatatable('#coverage-table');
                                }
                                else {
                                    showNotification('Delete Coverage Error', response.message, 'danger');
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

        if($('#provider-id').length){
            displayDetails('get insurance provider details');
            loadCategoryRateForm();
        }

        $(document).on('click','.delete-insurance-provider',function() {
            const provider_id = $(this).data('insurance-provider-id');
            const transaction = 'delete insurance provider';
    
            Swal.fire({
                title: 'Confirm Insurance Provider Deletion',
                text: 'Are you sure you want to delete this insurance provider?',
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
                        url: 'controller/insurance-provider-controller.php',
                        dataType: 'json',
                        data: {
                            provider_id : provider_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete Insurance Provider Success', 'The insurance provider has been deleted successfully.', 'success');
                                reloadDatatable('#insurance-provider-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Delete Insurance Provider Error', 'The insurance provider does not exist.', 'danger');
                                    reloadDatatable('#insurance-provider-table');
                                }
                                else {
                                    showNotification('Delete Insurance Provider Error', response.message, 'danger');
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

        $(document).on('click','#delete-insurance-provider',function() {
            let provider_id = [];
            const transaction = 'delete multiple insurance provider';

            $('.datatable-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    provider_id.push(element.value);
                }
            });
    
            if(provider_id.length > 0){
                Swal.fire({
                    title: 'Confirm Multiple Insurance Providers Deletion',
                    text: 'Are you sure you want to delete these insurance providers?',
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
                            url: 'controller/insurance-provider-controller.php',
                            dataType: 'json',
                            data: {
                                provider_id: provider_id,
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Delete Insurance Provider Success', 'The selected insurance providers have been deleted successfully.', 'success');
                                    reloadDatatable('#insurance-provider-table');
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else {
                                        showNotification('Delete Insurance Provider Error', response.message, 'danger');
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
                showNotification('Deletion Multiple Insurance Provider Error', 'Please select the insurance providers you wish to delete.', 'danger');
            }
        });

        $(document).on('click','#delete-insurance-provider-details',function() {
            const provider_id = $('#insurance-provider-id').text();
            const transaction = 'delete insurance provider';
    
            Swal.fire({
                title: 'Confirm Insurance Provider Deletion',
                text: 'Are you sure you want to delete this insurance provider?',
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
                        url: 'controller/insurance-provider-controller.php',
                        dataType: 'json',
                        data: {
                            provider_id : provider_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Deleted Insurance Provider Success', 'The insurance provider has been deleted successfully.', 'success');
                                window.location = 'insurance-provider.php';
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
                                    showNotification('Delete Insurance Provider Error', response.message, 'danger');
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
            discardCreate('insurance-provider.php');
        });

        $(document).on('click','#edit-form',function() {
            displayDetails('get insurance provider details');

            enableForm();
        });

        $(document).on('click','#duplicate-insurance-provider',function() {
            const provider_id = $('#insurance-provider-id').text();
            const transaction = 'duplicate insurance provider';
    
            Swal.fire({
                title: 'Confirm Insurance Provider Duplication',
                text: 'Are you sure you want to duplicate this insurance provider?',
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
                        url: 'controller/insurance-provider-controller.php',
                        dataType: 'json',
                        data: {
                            provider_id : provider_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Duplicate Insurance Provider Success', 'The insurance provider has been duplicated successfully.', 'success');
                                window.location = 'insurance-provider.php?id=' + response.insuranceProviderID;
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Duplicate Insurance Provider Error', 'The insurance provider does not exist.', 'danger');
                                    reloadDatatable('#insurance-provider-table');
                                }
                                else {
                                    showNotification('Duplicate Insurance Provider Error', response.message, 'danger');
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

        $(document).on('click','#apply-filter',function() {
            insuranceProviderTable('#insurance-provider-table');
        });
    });
})(jQuery);

function insuranceProviderTable(datatable_name, buttons = false, show_all = false){
    const type = 'insurance provider table';
    var filter_city_values = [];

    $('.city-filter:checked').each(function() {
        filter_city_values.push($(this).val());
    });

    var filter_city = filter_city_values.join(', ');
    var settings;

    const column = [ 
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'INSURANCE_PROVIDER_NAME' },
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
            'url' : 'view/_insurance_provider_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {'type' : type, 'filter_city' : filter_city},
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

function coverageTable(datatable_name, buttons = false, show_all = false){
    const type = 'coverage table';
    const insurance_provider_id = $('#provider-id').text();

    var settings;

   const column = [ 
        { 'data' : 'COVERAGE' },
        { 'data' : 'BI' },
        { 'data' : 'PD' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': 'auto', 'aTargets': 0 },
        { 'width': 'auto', 'aTargets': 1 },
        { 'width': 'auto', 'aTargets': 2 },
        { 'width': '5%','bSortable': false, 'aTargets': 3 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_insurance_provider_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {
                'type' : type, 
                'insurance_provider_id' : insurance_provider_id,
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

function insuranceProviderForm(){
    $('#insurance-provider-form').validate({
        rules: {
            insurance_provider_name: {
                required: true
            },
            address: {
                required: true
            },
            city_id: {
                required: true
            }
        },
        messages: {
            insurance_provider_name: {
                required: 'Please enter the insurance provider name'
            },
            address: {
                required: 'Please enter the address'
            },
            city_id: {
                required: 'Please choose the city'
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
            const provider_id = $('#provider-id').text();
            const transaction = 'save insurance provider';
        
            $.ajax({
                type: 'POST',
                url: 'controller/insurance-provider-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&provider_id=' + provider_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-data');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert Insurance Provider Success' : 'Update Insurance Provider Success';
                        const notificationDescription = response.insertRecord ? 'The insurance provider has been inserted successfully.' : 'The insurance provider has been updated successfully.';
                        
                        setNotification(notificationMessage, notificationDescription, 'success');
                        window.location = 'insurance-provider.php?id=' + response.providerID;
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

function coverageForm(){
    $('#coverage-form').validate({
        rules: {
            coverage_amount_start: {
                required: true
            },
            coverage_amount_end: {
                required: true
            },
            bi_rate: {
                required: true
            },
            pd_rate: {
                required: true
            }
        },
        messages: {
            coverage_amount_start: {
                required: 'Please enter the coverage amount start'
            },
            coverage_amount_end: {
                required: 'Please enter the coverage amount end'
            },
            bi_rate: {
                required: 'Please enter the BI rate'
            },
            pd_rate: {
                required: 'Please enter the PD rate'
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
            const insurance_provider_id = $('#provider-id').text();
            const transaction = 'save coverage';
        
            $.ajax({
                type: 'POST',
                url: 'controller/insurance-provider-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&insurance_provider_id=' + insurance_provider_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-coverage');
                },
                success: function (response) {
                    if (response.success) {                        
                        const notificationMessage = response.insertRecord ? 'Insert Coverage Success' : 'Update Coverage Success';
                        const notificationDescription = response.insertRecord ? 'The coverage has been inserted successfully.' : 'The coverage has been updated successfully.';
                        
                        showNotification(notificationMessage, notificationDescription, 'success');

                        coverageTable('#coverage-table');
                        resetModalForm('coverage-form');
                        $('#coverage-offcanvas').offcanvas('hide');
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
                    enableFormSubmitButton('submit-coverage', 'Submit');
                }
            });
        
            return false;
        }
    });
}

function displayDetails(transaction){
    switch (transaction) {
        case 'get insurance provider details':
            const provider_id = $('#provider-id').text();
            
            $.ajax({
                url: 'controller/insurance-provider-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    provider_id : provider_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetForm('insurance-provider-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#provider_name').val(response.providerName);
                        $('#address').val(response.address);
                        $('#tax_id').val(response.taxID);
                        $('#phone').val(response.phone);
                        $('#mobile').val(response.mobile);
                        $('#telephone').val(response.telephone);
                        $('#email').val(response.email);
                        $('#website').val(response.website);
                        $('#local_government_tax_rate').val(response.localGovernmentTaxRate);

                        checkOptionExist('#city_id', response.cityID, '');
                        checkOptionExist('#currency_id', response.currencyID, '');

                        $('#city_id_label').text(response.cityName);
                        $('#tax_id_label').text(response.taxID);
                        $('#currency_id_label').text(response.currencyName);
                        $('#provider_name_label').text(response.providerName);
                        $('#address_label').text(response.address);
                        $('#phone_label').text(response.phone);
                        $('#mobile_label').text(response.mobile);
                        $('#telephone_label').text(response.telephone);
                        $('#email_label').text(response.email);
                        $('#website_label').text(response.website);
                        $('#local_government_tax_rate_label').text(response.localGovernmentTaxRate + '%');
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Insurance Provider Details Error', response.message, 'danger');
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
        case 'get coverage details':
            var insurance_coverage_rate_id = sessionStorage.getItem('insurance_coverage_rate_id');
            
            $.ajax({
                url: 'controller/insurance-provider-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    insurance_coverage_rate_id : insurance_coverage_rate_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetForm('ci-residence-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#insurance_coverage_rate_id').val(insurance_coverage_rate_id);
                        $('#coverage_amount_start').val(response.coverage_amount_start);
                        $('#coverage_amount_end').val(response.coverage_amount_end);
                        $('#bi_rate').val(response.bi_rate);
                        $('#pd_amount').val(response.pd_amount);
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Details Error', response.message, 'danger');
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

function loadCategoryRateForm() {
    const provider_id = $('#provider-id').text(); // or provider select value

    $.ajax({
        url: 'controller/insurance-provider-controller.php',
        type: 'POST',
        dataType: 'json',
        data: {
            provider_id: provider_id,
            transaction: 'generate category rate form'
        },
        success: function(response) {
            if (response.success) {
                let html = '';
                
                response.categories.forEach(function(cat) {
                    html += `
                        <div class="card p-3">
                            <div class="card-body p-0">
                                <h6>${cat.insurance_category_name}</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="form-label">Insurance Rate (%)</label>
                                        <input type="number" step="0.01" 
                                               name="rates[${cat.insurance_category_id}][insurance_rate]" 
                                               class="form-control category-rate-input" 
                                               value="${cat.insurance_rate}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Commission Rate (%)</label>
                                        <input type="number" step="0.01" 
                                               name="rates[${cat.insurance_category_id}][commission_rate]" 
                                               class="form-control category-rate-input" 
                                               value="${cat.commission_rate}" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                });

                $('#category-rates-container').html(html);
            } else {
                showNotification('Error', response.message, 'danger');
            }
        },
        error: function(xhr, status, error) {
            showErrorDialog(`XHR status: ${status}, Error: ${error}`);
        }
    });
}

function categoryRateForm() {
    $('#category-rate-form').validate({
        errorPlacement: function (error, element) {
            if (element.parent('.input-group').length) {
                error.insertAfter(element.parent());
            } else {
                error.insertAfter(element);
            }
        },
        highlight: function(element) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function(element) {
            $(element).removeClass('is-invalid');
        },
        submitHandler: function(form) {
            const insurance_provider_id = $('#provider-id').text();
            const transaction = 'save category rates';

            $.ajax({
                type: 'POST',
                url: 'controller/insurance-provider-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&insurance_provider_id=' + insurance_provider_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-category-rate');
                },
                success: function (response) {
                    if (response.success) {
                        showNotification('Success', 'Category rates saved successfully.', 'success');
                    } else {
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
                    enableFormSubmitButton('submit-category-rate', 'Save Category Rates');
                }
            });

            return false;
        }
    });
}