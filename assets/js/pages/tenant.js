(function($) {
    'use strict';

    $(function() {
        if($('#tenant-table').length){
            tenantTable('#tenant-table');
        }

        if($('#tenant-form').length){
            tenantForm();
        }

        if($('#tenant-id').length){
            displayDetails('get tenant details');
        }

        $(document).on('click','.delete-tenant',function() {
            const tenant_id = $(this).data('tenant-id');
            const transaction = 'delete tenant';
    
            Swal.fire({
                title: 'Confirm Tenant Deletion',
                text: 'Are you sure you want to delete this tenant?',
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
                        url: 'controller/tenant-controller.php',
                        dataType: 'json',
                        data: {
                            tenant_id : tenant_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete Tenant Success', 'The tenant has been deleted successfully.', 'success');
                                reloadDatatable('#tenant-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Delete Tenant Error', 'The tenant does not exist.', 'danger');
                                    reloadDatatable('#tenant-table');
                                }
                                else {
                                    showNotification('Delete Tenant Error', response.message, 'danger');
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

        $(document).on('click','#delete-tenant',function() {
            let tenant_id = [];
            const transaction = 'delete multiple tenant';

            $('.datatable-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    tenant_id.push(element.value);
                }
            });
    
            if(tenant_id.length > 0){
                Swal.fire({
                    title: 'Confirm Multiple Tenants Deletion',
                    text: 'Are you sure you want to delete these tenants?',
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
                            url: 'controller/tenant-controller.php',
                            dataType: 'json',
                            data: {
                                tenant_id: tenant_id,
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Delete Tenant Success', 'The selected tenants have been deleted successfully.', 'success');
                                    reloadDatatable('#tenant-table');
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else {
                                        showNotification('Delete Tenant Error', response.message, 'danger');
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
                showNotification('Deletion Multiple Tenant Error', 'Please select the tenants you wish to delete.', 'danger');
            }
        });

        $(document).on('click','#delete-tenant-details',function() {
            const tenant_id = $('#tenant-id').text();
            const transaction = 'delete tenant';
    
            Swal.fire({
                title: 'Confirm Tenant Deletion',
                text: 'Are you sure you want to delete this tenant?',
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
                        url: 'controller/tenant-controller.php',
                        dataType: 'json',
                        data: {
                            tenant_id : tenant_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Deleted Tenant Success', 'The tenant has been deleted successfully.', 'success');
                                window.location = 'tenant.php';
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
                                    showNotification('Delete Tenant Error', response.message, 'danger');
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
            discardCreate('tenant.php');
        });

        $(document).on('click','#edit-form',function() {
            displayDetails('get tenant details');

            enableForm();
        });

        $(document).on('click','#duplicate-tenant',function() {
            const tenant_id = $('#tenant-id').text();
            const transaction = 'duplicate tenant';
    
            Swal.fire({
                title: 'Confirm Tenant Duplication',
                text: 'Are you sure you want to duplicate this tenant?',
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
                        url: 'controller/tenant-controller.php',
                        dataType: 'json',
                        data: {
                            tenant_id : tenant_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Duplicate Tenant Success', 'The tenant has been duplicated successfully.', 'success');
                                window.location = 'tenant.php?id=' + response.tenantID;
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Duplicate Tenant Error', 'The tenant does not exist.', 'danger');
                                    reloadDatatable('#tenant-table');
                                }
                                else {
                                    showNotification('Duplicate Tenant Error', response.message, 'danger');
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
            tenantTable('#tenant-table');
        });
    });
})(jQuery);

function tenantTable(datatable_name, buttons = false, show_all = false){
    const type = 'tenant table';
    var filter_city_values = [];

    $('.city-filter:checked').each(function() {
        filter_city_values.push($(this).val());
    });

    var filter_city = filter_city_values.join(', ');
    var settings;

    const column = [ 
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'TENANT_NAME' },
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
            'url' : 'view/_tenant_generation.php',
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

function tenantForm(){
    $('#tenant-form').validate({
        rules: {
            tenant_name: {
                required: true
            },
            contact_person: {
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
            tenant_name: {
                required: 'Please enter the tenant name'
            },
            contact_person: {
                required: 'Please enter the contact person'
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
            const tenant_id = $('#tenant-id').text();
            const transaction = 'save tenant';
        
            $.ajax({
                type: 'POST',
                url: 'controller/tenant-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&tenant_id=' + tenant_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-data');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert Tenant Success' : 'Update Tenant Success';
                        const notificationDescription = response.insertRecord ? 'The tenant has been inserted successfully.' : 'The tenant has been updated successfully.';
                        
                        setNotification(notificationMessage, notificationDescription, 'success');
                        window.location = 'tenant.php?id=' + response.tenantID;
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
        case 'get tenant details':
            const tenant_id = $('#tenant-id').text();
            
            $.ajax({
                url: 'controller/tenant-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    tenant_id : tenant_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetForm('tenant-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#tenant_id').val(tenant_id);
                        $('#tenant_name').val(response.tenantName);
                        $('#contact_person').val(response.contactPerson);
                        $('#address').val(response.address);
                        $('#phone').val(response.phone);
                        $('#mobile').val(response.mobile);
                        $('#telephone').val(response.telephone);
                        $('#email').val(response.email);

                        checkOptionExist('#city_id', response.cityID, '');

                        $('#city_id_label').text(response.cityName);
                        $('#tenant_name_label').text(response.tenantName);
                        $('#contact_person_label').text(response.contactPerson);
                        $('#address_label').text(response.address);
                        $('#phone_label').text(response.phone);
                        $('#mobile_label').text(response.mobile);
                        $('#telephone_label').text(response.telephone);
                        $('#email_label').text(response.email);
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Tenant Details Error', response.message, 'danger');
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