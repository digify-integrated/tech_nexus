(function($) {
    'use strict';

    $(function() {
        if($('#zoom-api-table').length){
            zoomAPITable('#zoom-api-table');
        }

        if($('#zoom-api-form').length){
            zoomAPIForm();
        }

        if($('#zoom-api-id').length){
            displayDetails('get zoom API details');
        }

        $(document).on('click','.delete-zoom-api',function() {
            const zoom_api_id = $(this).data('zoom-api-id');
            const transaction = 'delete zoom API';
    
            Swal.fire({
                title: 'Confirm Zoom API Deletion',
                text: 'Are you sure you want to delete this Zoom API?',
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
                        url: 'controller/zoom-api-controller.php',
                        dataType: 'json',
                        data: {
                            zoom_api_id : zoom_api_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete Zoom API Success', 'The Zoom API has been deleted successfully.', 'success');
                                reloadDatatable('#zoom-api-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Delete Zoom API Error', 'The Zoom API does not exist.', 'danger');
                                    reloadDatatable('#zoom-api-table');
                                }
                                else {
                                    showNotification('Delete Zoom API Error', response.message, 'danger');
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

        $(document).on('click','#delete-zoom-api',function() {
            let zoom_api_id = [];
            const transaction = 'delete multiple zoom API';

            $('.datatable-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    zoom_api_id.push(element.value);
                }
            });
    
            if(zoom_api_id.length > 0){
                Swal.fire({
                    title: 'Confirm Multiple Zoom APIs Deletion',
                    text: 'Are you sure you want to delete these Zoom APIs?',
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
                            url: 'controller/zoom-api-controller.php',
                            dataType: 'json',
                            data: {
                                zoom_api_id: zoom_api_id,
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Delete Zoom API Success', 'The selected Zoom APIs have been deleted successfully.', 'success');
                                        reloadDatatable('#zoom-api-table');
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else {
                                        showNotification('Delete Zoom API Error', response.message, 'danger');
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
                showNotification('Deletion Multiple Zoom API Error', 'Please select the Zoom APIs you wish to delete.', 'danger');
            }
        });

        $(document).on('click','#delete-zoom-api-details',function() {
            const zoom_api_id = $('#zoom-api-id').text();
            const transaction = 'delete zoom API';
    
            Swal.fire({
                title: 'Confirm Zoom API Deletion',
                text: 'Are you sure you want to delete this Zoom API?',
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
                        url: 'controller/zoom-api-controller.php',
                        dataType: 'json',
                        data: {
                            zoom_api_id : zoom_api_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Deleted Zoom API Success', 'The Zoom API has been deleted successfully.', 'success');
                                window.location = 'zoom-api.php';
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
                                    showNotification('Delete Zoom API Error', response.message, 'danger');
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
            discardCreate('zoom-api.php');
        });

        $(document).on('click','#edit-form',function() {
            displayDetails('get zoom API details');

            enableForm();
        });

        $(document).on('click','#duplicate-zoom-api',function() {
            const zoom_api_id = $('#zoom-api-id').text();
            const transaction = 'duplicate zoom API';
    
            Swal.fire({
                title: 'Confirm Zoom API Duplication',
                text: 'Are you sure you want to duplicate this Zoom API?',
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
                        url: 'controller/zoom-api-controller.php',
                        dataType: 'json',
                        data: {
                            zoom_api_id : zoom_api_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Duplicate Zoom API Success', 'The Zoom API has been duplicated successfully.', 'success');
                                window.location = 'zoom-api.php?id=' + response.zoomAPIID;
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Duplicate Zoom API Error', 'The Zoom API does not exist.', 'danger');
                                    reloadDatatable('#zoom-api-table');
                                }
                                else {
                                    showNotification('Duplicate Zoom API Error', response.message, 'danger');
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
    });
})(jQuery);

function zoomAPITable(datatable_name, buttons = false, show_all = false){
    const type = 'zoom API table';
    var settings;

    const column = [ 
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'SYSTEM_SETTING_NAME' },
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
            'url' : 'view/_zoom_api_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {'type' : type},
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

function zoomAPIForm(){
    $('#zoom-api-form').validate({
        rules: {
            zoom_api_name: {
                required: true
            },
            zoom_api_description: {
                required: true
            },
            api_key: {
                required: true
            },
            api_secret: {
                required: true
            },
        },
        messages: {
            zoom_api_name: {
                required: 'Please enter the Zoom API name'
            },
            zoom_api_description: {
                required: 'Please enter the Zoom API description'
            },
            api_key: {
                required: 'Please enter the API key'
            },
            api_secret: {
                required: 'Please enter the API secret'
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
            const zoom_api_id = $('#zoom-api-id').text();
            const transaction = 'save zoom API';
        
            $.ajax({
                type: 'POST',
                url: 'controller/zoom-api-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&zoom_api_id=' + zoom_api_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-data');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert Zoom API Success' : 'Update Zoom API Success';
                        const notificationDescription = response.insertRecord ? 'The Zoom API has been inserted successfully.' : 'The Zoom API has been updated successfully.';
                        
                        setNotification(notificationMessage, notificationDescription, 'success');
                        window.location = 'zoom-api.php?id=' + response.zoomAPIID;
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
        case 'get zoom API details':
            const zoom_api_id = $('#zoom-api-id').text();
            
            $.ajax({
                url: 'controller/zoom-api-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    zoom_api_id : zoom_api_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetForm('zoom-api-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#zoom_api_id').val(zoom_api_id);
                        $('#zoom_api_name').val(response.zoomAPIName);
                        $('#zoom_api_description').val(response.zoomAPIDescription);
                        $('#api_key').val(response.apiKey);
                        $('#api_secret').val(response.apiSecret);

                        $('#zoom_api_name_label').text(response.zoomAPIName);
                        $('#zoom_api_description_label').text(response.zoomAPIDescription);
                        $('#api_key_label').text(response.apiKey);
                        $('#api_secret_label').text(response.apiSecret);
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Zoom API Details Error', response.message, 'danger');
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