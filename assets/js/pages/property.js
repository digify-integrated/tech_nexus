(function($) {
    'use strict';

    $(function() {
        if($('#property-table').length){
            propertyTable('#property-table');
        }

        if($('#property-form').length){
            propertyForm();
        }

        if($('#property-id').length){
            displayDetails('get property details');
        }

        $(document).on('click','.delete-property',function() {
            const property_id = $(this).data('property-id');
            const transaction = 'delete property';
    
            Swal.fire({
                title: 'Confirm Property Deletion',
                text: 'Are you sure you want to delete this property?',
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
                        url: 'controller/property-controller.php',
                        dataType: 'json',
                        data: {
                            property_id : property_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete Property Success', 'The property has been deleted successfully.', 'success');
                                reloadDatatable('#property-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Delete Property Error', 'The property does not exist.', 'danger');
                                    reloadDatatable('#property-table');
                                }
                                else {
                                    showNotification('Delete Property Error', response.message, 'danger');
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

        $(document).on('click','#delete-property',function() {
            let property_id = [];
            const transaction = 'delete multiple property';

            $('.datatable-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    property_id.push(element.value);
                }
            });
    
            if(property_id.length > 0){
                Swal.fire({
                    title: 'Confirm Multiple Propertys Deletion',
                    text: 'Are you sure you want to delete these propertys?',
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
                            url: 'controller/property-controller.php',
                            dataType: 'json',
                            data: {
                                property_id: property_id,
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Delete Property Success', 'The selected propertys have been deleted successfully.', 'success');
                                    reloadDatatable('#property-table');
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else {
                                        showNotification('Delete Property Error', response.message, 'danger');
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
                showNotification('Deletion Multiple Property Error', 'Please select the propertys you wish to delete.', 'danger');
            }
        });

        $(document).on('click','#delete-property-details',function() {
            const property_id = $('#property-id').text();
            const transaction = 'delete property';
    
            Swal.fire({
                title: 'Confirm Property Deletion',
                text: 'Are you sure you want to delete this property?',
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
                        url: 'controller/property-controller.php',
                        dataType: 'json',
                        data: {
                            property_id : property_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Deleted Property Success', 'The property has been deleted successfully.', 'success');
                                window.location = 'property.php';
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
                                    showNotification('Delete Property Error', response.message, 'danger');
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
            discardCreate('property.php');
        });

        $(document).on('click','#edit-form',function() {
            displayDetails('get property details');

            enableForm();
        });
    });
})(jQuery);

function propertyTable(datatable_name, buttons = false, show_all = false){
    const type = 'property table';
    var filter_city_values = [];

    $('.city-filter:checked').each(function() {
        filter_city_values.push($(this).val());
    });

    var filter_city = filter_city_values.join(', ');
    var settings;

    const column = [ 
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'PROPERTY_NAME' },
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
            'url' : 'view/_property_generation.php',
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

function propertyForm(){
    $('#property-form').validate({
        rules: {
            property_name: {
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
            property_name: {
                required: 'Please enter the property name'
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
            const property_id = $('#property-id').text();
            const transaction = 'save property';
        
            $.ajax({
                type: 'POST',
                url: 'controller/property-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&property_id=' + property_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-data');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert Property Success' : 'Update Property Success';
                        const notificationDescription = response.insertRecord ? 'The property has been inserted successfully.' : 'The property has been updated successfully.';
                        
                        setNotification(notificationMessage, notificationDescription, 'success');
                        window.location = 'property.php?id=' + response.propertyID;
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
        case 'get property details':
            const property_id = $('#property-id').text();
            
            $.ajax({
                url: 'controller/property-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    property_id : property_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetForm('property-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#property_id').val(property_id);
                        $('#property_name').val(response.propertyName);
                        $('#address').val(response.address);
                        $('#phone').val(response.phone);
                        $('#mobile').val(response.mobile);
                        $('#telephone').val(response.telephone);
                        $('#email').val(response.email);

                        checkOptionExist('#city_id', response.cityID, '');
                        checkOptionExist('#company_id', response.companyID, '');

                        $('#city_id_label').text(response.cityName);
                        $('#company_id_label').text(response.companyName);
                        $('#property_name_label').text(response.propertyName);
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
                            showNotification('Get Property Details Error', response.message, 'danger');
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