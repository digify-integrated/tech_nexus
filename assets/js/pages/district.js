(function($) {
    'use strict';

    $(function() {
        if($('#district-table').length){
            districtTable('#district-table');
        }

        if($('#district-form').length){
            districtForm();
        }

        if($('#district-id').length){
            displayDetails('get district details');
        }

        $(document).on('click','.delete-district',function() {
            const district_id = $(this).data('district-id');
            const transaction = 'delete district';
    
            Swal.fire({
                title: 'Confirm District Deletion',
                text: 'Are you sure you want to delete this district?',
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
                        url: 'controller/district-controller.php',
                        dataType: 'json',
                        data: {
                            district_id : district_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete District Success', 'The district has been deleted successfully.', 'success');
                                reloadDatatable('#district-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Delete District Error', 'The district does not exist.', 'danger');
                                    reloadDatatable('#district-table');
                                }
                                else {
                                    showNotification('Delete District Error', response.message, 'danger');
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

        $(document).on('click','#delete-district',function() {
            let district_id = [];
            const transaction = 'delete multiple district';

            $('.datatable-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    district_id.push(element.value);
                }
            });
    
            if(district_id.length > 0){
                Swal.fire({
                    title: 'Confirm Multiple Cities Deletion',
                    text: 'Are you sure you want to delete these cities?',
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
                            url: 'controller/district-controller.php',
                            dataType: 'json',
                            data: {
                                district_id: district_id,
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Delete District Success', 'The selected cities have been deleted successfully.', 'success');
                                        reloadDatatable('#district-table');
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else {
                                        showNotification('Delete District Error', response.message, 'danger');
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
                showNotification('Deletion Multiple District Error', 'Please select the cities you wish to delete.', 'danger');
            }
        });

        $(document).on('click','#delete-district-details',function() {
            const district_id = $('#district-id').text();
            const transaction = 'delete district';
    
            Swal.fire({
                title: 'Confirm District Deletion',
                text: 'Are you sure you want to delete this district?',
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
                        url: 'controller/district-controller.php',
                        dataType: 'json',
                        data: {
                            district_id : district_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Deleted District Success', 'The district has been deleted successfully.', 'success');
                                window.location = 'district.php';
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
                                    showNotification('Delete District Error', response.message, 'danger');
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
            discardCreate('district.php');
        });

        $(document).on('click','#edit-form',function() {
            displayDetails('get district details');

            enableForm();
        });

        $(document).on('click','#duplicate-district',function() {
            const district_id = $('#district-id').text();
            const transaction = 'duplicate district';
    
            Swal.fire({
                title: 'Confirm District Duplication',
                text: 'Are you sure you want to duplicate this district?',
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
                        url: 'controller/district-controller.php',
                        dataType: 'json',
                        data: {
                            district_id : district_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Duplicate District Success', 'The district has been duplicated successfully.', 'success');
                                window.location = 'district.php?id=' + response.districtID;
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Duplicate District Error', 'The district does not exist.', 'danger');
                                    reloadDatatable('#district-table');
                                }
                                else {
                                    showNotification('Duplicate District Error', response.message, 'danger');
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

        $(document).on('click','#filter-datatable',function() {
            districtTable('#district-table');
        });
    });
})(jQuery);

function districtTable(datatable_name, buttons = false, show_all = false){
    const type = 'district table';
    var filter_city = $('#filter_city').val();
    var filter_state = $('#filter_state').val();
    var filter_country = $('#filter_country').val();
    var settings;

    const column = [ 
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'DISTRICT_NAME' },
        { 'data' : 'CITY_ID' },
        { 'data' : 'STATE_ID' },
        { 'data' : 'COUNTRY_ID' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '1%','bSortable': false, 'aTargets': 0 },
        { 'width': '21%', 'aTargets': 1 },
        { 'width': '21%', 'aTargets': 2 },
        { 'width': '21%', 'aTargets': 3 },
        { 'width': '21%', 'aTargets': 4 },
        { 'width': '15%','bSortable': false, 'aTargets': 5 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_district_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {'type' : type, 'filter_city' : filter_city, 'filter_state' : filter_state, 'filter_country' : filter_country},
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

function districtForm(){
    $('#district-form').validate({
        rules: {
            district_name: {
                required: true
            },
            city_id: {
                required: true
            },
            state_id: {
                required: true
            },
            country_id: {
                required: true
            }
        },
        messages: {
            district_name: {
                required: 'Please enter the district name'
            },
            city_id: {
                required: 'Please choose the city'
            },
            state_id: {
                required: 'Please choose the state'
            },
            country_id: {
                required: 'Please choose the country'
            }
        },
        errorPlacement: function (error, element) {
            if (element.hasClass('select2')) {
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
            const district_id = $('#district-id').text();
            const transaction = 'save district';
        
            $.ajax({
                type: 'POST',
                url: 'controller/district-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&district_id=' + district_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-data');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert District Success' : 'Update District Success';
                        const notificationDescription = response.insertRecord ? 'The district has been inserted successfully.' : 'The district has been updated successfully.';
                        
                        setNotification(notificationMessage, notificationDescription, 'success');
                        window.location = 'district.php?id=' + response.districtID;
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
        case 'get district details':
            const district_id = $('#district-id').text();
            
            $.ajax({
                url: 'controller/district-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    district_id : district_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetModalForm('district-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#district_id').val(district_id);
                        $('#district_name').val(response.districtName);

                        checkOptionExist('#city_id', response.cityID, '');
                        checkOptionExist('#state_id', response.stateID, '');
                        checkOptionExist('#country_id', response.countryID, '');

                        $('#district_name_label').text(response.districtName);
                        $('#city_id_label').text(response.cityName);
                        $('#state_id_label').text(response.stateName);
                        $('#country_id_label').text(response.countryName);
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get District Details Error', response.message, 'danger');
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