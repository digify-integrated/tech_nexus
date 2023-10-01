(function($) {
    'use strict';

    $(function() {
        if($('#city-table').length){
            cityTable('#city-table');
        }

        if($('#city-form').length){
            cityForm();
        }

        if($('#city-id').length){
            displayDetails('get city details');
        }

        $(document).on('click','.delete-city',function() {
            const city_id = $(this).data('city-id');
            const transaction = 'delete city';
    
            Swal.fire({
                title: 'Confirm City Deletion',
                text: 'Are you sure you want to delete this city?',
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
                        url: 'controller/city-controller.php',
                        dataType: 'json',
                        data: {
                            city_id : city_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete City Success', 'The city has been deleted successfully.', 'success');
                                reloadDatatable('#city-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Delete City Error', 'The city does not exist.', 'danger');
                                    reloadDatatable('#city-table');
                                }
                                else {
                                    showNotification('Delete City Error', response.message, 'danger');
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

        $(document).on('click','#delete-city',function() {
            let city_id = [];
            const transaction = 'delete multiple city';

            $('.datatable-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    city_id.push(element.value);
                }
            });
    
            if(city_id.length > 0){
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
                            url: 'controller/city-controller.php',
                            dataType: 'json',
                            data: {
                                city_id: city_id,
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Delete City Success', 'The selected cities have been deleted successfully.', 'success');
                                        reloadDatatable('#city-table');
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else {
                                        showNotification('Delete City Error', response.message, 'danger');
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
                showNotification('Deletion Multiple City Error', 'Please select the cities you wish to delete.', 'danger');
            }
        });

        $(document).on('click','#delete-city-details',function() {
            const city_id = $('#city-id').text();
            const transaction = 'delete city';
    
            Swal.fire({
                title: 'Confirm City Deletion',
                text: 'Are you sure you want to delete this city?',
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
                        url: 'controller/city-controller.php',
                        dataType: 'json',
                        data: {
                            city_id : city_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Deleted City Success', 'The city has been deleted successfully.', 'success');
                                window.location = 'city.php';
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
                                    showNotification('Delete City Error', response.message, 'danger');
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
            discardCreate('city.php');
        });

        $(document).on('click','#edit-form',function() {
            displayDetails('get city details');

            enableForm();
        });

        $(document).on('click','#duplicate-city',function() {
            const city_id = $('#city-id').text();
            const transaction = 'duplicate city';
    
            Swal.fire({
                title: 'Confirm City Duplication',
                text: 'Are you sure you want to duplicate this city?',
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
                        url: 'controller/city-controller.php',
                        dataType: 'json',
                        data: {
                            city_id : city_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Duplicate City Success', 'The city has been duplicated successfully.', 'success');
                                window.location = 'city.php?id=' + response.cityID;
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Duplicate City Error', 'The city does not exist.', 'danger');
                                    reloadDatatable('#city-table');
                                }
                                else {
                                    showNotification('Duplicate City Error', response.message, 'danger');
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
            cityTable('#city-table');
        });
    });
})(jQuery);

function cityTable(datatable_name, buttons = false, show_all = false){
    const type = 'city table';
    var filter_state = $('#filter_state').val();
    var settings;

    const column = [ 
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'CITY_NAME' },
        { 'data' : 'STATE_ID' },
        { 'data' : 'COUNTRY_ID' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '1%','bSortable': false, 'aTargets': 0 },
        { 'width': '28%', 'aTargets': 1 },
        { 'width': '28%', 'aTargets': 2 },
        { 'width': '28%', 'aTargets': 3 },
        { 'width': '15%','bSortable': false, 'aTargets': 4 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_city_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {'type' : type, 'filter_state' : filter_state},
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

function cityForm(){
    $('#city-form').validate({
        rules: {
            city_name: {
                required: true
            },
            state_id: {
                required: true
            }
        },
        messages: {
            city_name: {
                required: 'Please enter the city name'
            },
            state_id: {
                required: 'Please choose the state'
            }
        },
        errorPlacement: function (error, element) {
            if (element.hasClass('select2') || element.hasClass('modal-select2')) {
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
            const city_id = $('#city-id').text();
            const transaction = 'save city';
        
            $.ajax({
                type: 'POST',
                url: 'controller/city-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&city_id=' + city_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-data');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert City Success' : 'Update City Success';
                        const notificationDescription = response.insertRecord ? 'The city has been inserted successfully.' : 'The city has been updated successfully.';
                        
                        setNotification(notificationMessage, notificationDescription, 'success');
                        window.location = 'city.php?id=' + response.cityID;
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
        case 'get city details':
            const city_id = $('#city-id').text();
            
            $.ajax({
                url: 'controller/city-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    city_id : city_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetForm('city-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#city_id').val(city_id);
                        $('#city_name').val(response.cityName);

                        checkOptionExist('#state_id', response.stateID, '');

                        $('#city_name_label').text(response.cityName);
                        $('#state_id_label').text(response.stateName);
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get City Details Error', response.message, 'danger');
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