(function($) {
    'use strict';

    $(function() {
        if($('#neighborhood-type-table').length){
            neighborhoodTypeTable('#neighborhood-type-table');
        }

        if($('#neighborhood-type-form').length){
            neighborhoodTypeForm();
        }

        if($('#neighborhood-type-id').length){
            displayDetails('get neighborhood type details');
        }

        $(document).on('click','.delete-neighborhood-type',function() {
            const neighborhood_type_id = $(this).data('neighborhood-type-id');
            const transaction = 'delete neighborhood type';
    
            Swal.fire({
                title: 'Confirm Neighborhood Type Deletion',
                text: 'Are you sure you want to delete this neighborhood type?',
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
                        url: 'controller/neighborhood-type-controller.php',
                        dataType: 'json',
                        data: {
                            neighborhood_type_id : neighborhood_type_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete Neighborhood Type Success', 'The neighborhood type has been deleted successfully.', 'success');
                                reloadDatatable('#neighborhood-type-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Delete Neighborhood Type Error', 'The neighborhood type does not exist.', 'danger');
                                    reloadDatatable('#neighborhood-type-table');
                                }
                                else {
                                    showNotification('Delete Neighborhood Type Error', response.message, 'danger');
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

        $(document).on('click','#delete-neighborhood-type',function() {
            let neighborhood_type_id = [];
            const transaction = 'delete multiple neighborhood type';

            $('.datatable-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    neighborhood_type_id.push(element.value);
                }
            });
    
            if(neighborhood_type_id.length > 0){
                Swal.fire({
                    title: 'Confirm Multiple Neighborhood Types Deletion',
                    text: 'Are you sure you want to delete these neighborhood types?',
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
                            url: 'controller/neighborhood-type-controller.php',
                            dataType: 'json',
                            data: {
                                neighborhood_type_id: neighborhood_type_id,
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Delete Neighborhood Type Success', 'The selected neighborhood types have been deleted successfully.', 'success');
                                    reloadDatatable('#neighborhood-type-table');
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else {
                                        showNotification('Delete Neighborhood Type Error', response.message, 'danger');
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
                showNotification('Deletion Multiple Neighborhood Type Error', 'Please select the neighborhood types you wish to delete.', 'danger');
            }
        });

        $(document).on('click','#delete-neighborhood-type-details',function() {
            const neighborhood_type_id = $('#neighborhood-type-id').text();
            const transaction = 'delete neighborhood type';
    
            Swal.fire({
                title: 'Confirm Neighborhood Type Deletion',
                text: 'Are you sure you want to delete this neighborhood type?',
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
                        url: 'controller/neighborhood-type-controller.php',
                        dataType: 'json',
                        data: {
                            neighborhood_type_id : neighborhood_type_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Deleted Neighborhood Type Success', 'The neighborhood type has been deleted successfully.', 'success');
                                window.location = 'neighborhood-type.php';
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
                                    showNotification('Delete Neighborhood Type Error', response.message, 'danger');
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
            discardCreate('neighborhood-type.php');
        });

        $(document).on('click','#edit-form',function() {
            displayDetails('get neighborhood type details');

            enableForm();
        });

        $(document).on('click','#duplicate-neighborhood-type',function() {
            const neighborhood_type_id = $('#neighborhood-type-id').text();
            const transaction = 'duplicate neighborhood type';
    
            Swal.fire({
                title: 'Confirm Neighborhood Type Duplication',
                text: 'Are you sure you want to duplicate this neighborhood type?',
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
                        url: 'controller/neighborhood-type-controller.php',
                        dataType: 'json',
                        data: {
                            neighborhood_type_id : neighborhood_type_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Duplicate Neighborhood Type Success', 'The neighborhood type has been duplicated successfully.', 'success');
                                window.location = 'neighborhood-type.php?id=' + response.neighborhoodTypeID;
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Duplicate Neighborhood Type Error', 'The neighborhood type does not exist.', 'danger');
                                    reloadDatatable('#neighborhood-type-table');
                                }
                                else {
                                    showNotification('Duplicate Neighborhood Type Error', response.message, 'danger');
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

function neighborhoodTypeTable(datatable_name, buttons = false, show_all = false){
    const type = 'neighborhood type table';
    var settings;

    const column = [ 
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'NEIGHBORHOOD_TYPE_NAME' },
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
            'url' : 'view/_neighborhood_type_generation.php',
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

function neighborhoodTypeForm(){
    $('#neighborhood-type-form').validate({
        rules: {
            neighborhood_type_name: {
                required: true
            },
        },
        messages: {
            neighborhood_type_name: {
                required: 'Please enter the neighborhood type name'
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
            const neighborhood_type_id = $('#neighborhood-type-id').text();
            const transaction = 'save neighborhood type';
        
            $.ajax({
                type: 'POST',
                url: 'controller/neighborhood-type-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&neighborhood_type_id=' + neighborhood_type_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-data');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert Neighborhood Type Success' : 'Update Neighborhood Type Success';
                        const notificationDescription = response.insertRecord ? 'The neighborhood type has been inserted successfully.' : 'The neighborhood type has been updated successfully.';
                        
                        setNotification(notificationMessage, notificationDescription, 'success');
                        window.location = 'neighborhood-type.php?id=' + response.neighborhoodTypeID;
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
        case 'get neighborhood type details':
            const neighborhood_type_id = $('#neighborhood-type-id').text();
            
            $.ajax({
                url: 'controller/neighborhood-type-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    neighborhood_type_id : neighborhood_type_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetForm('neighborhood-type-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#neighborhood_type_id').val(neighborhood_type_id);
                        $('#neighborhood_type_name').val(response.neighborhoodTypeName);

                        $('#neighborhood_type_name_label').text(response.neighborhoodTypeName);
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Neighborhood Type Details Error', response.message, 'danger');
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