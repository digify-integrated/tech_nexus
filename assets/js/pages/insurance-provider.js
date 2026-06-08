(function($) {
    'use strict';

    $(function() {
        if($('#insurance-provider-table').length){
            insuranceProviderTable('#insurance-provider-table');
        }

        if($('#insurance-provider-form').length){
            insuranceProviderForm();
        }

        if($('#provider-id').length){
            displayDetails('get insurance provider details');
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
    }
}