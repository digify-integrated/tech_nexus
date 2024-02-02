(function($) {
    'use strict';

    $(function() {
        if($('#transmittal-table').length){
            idTypeTable('#transmittal-table');
        }

        if($('#transmittal-form').length){
            idTypeForm();
        }

        if($('#transmittal-id').length){
            displayDetails('get transmittal details');
        }

        $(document).on('click','.delete-transmittal',function() {
            const transmittal_id = $(this).data('transmittal-id');
            const transaction = 'delete transmittal';
    
            Swal.fire({
                title: 'Confirm Transmittal Deletion',
                text: 'Are you sure you want to delete this transmittal?',
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
                        url: 'controller/transmittal-controller.php',
                        dataType: 'json',
                        data: {
                            transmittal_id : transmittal_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete Transmittal Success', 'The transmittal has been deleted successfully.', 'success');
                                reloadDatatable('#transmittal-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Delete Transmittal Error', 'The transmittal does not exist.', 'danger');
                                    reloadDatatable('#transmittal-table');
                                }
                                else {
                                    showNotification('Delete Transmittal Error', response.message, 'danger');
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

        $(document).on('click','#delete-transmittal',function() {
            let transmittal_id = [];
            const transaction = 'delete multiple transmittal';

            $('.datatable-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    transmittal_id.push(element.value);
                }
            });
    
            if(transmittal_id.length > 0){
                Swal.fire({
                    title: 'Confirm Multiple Transmittals Deletion',
                    text: 'Are you sure you want to delete these transmittals?',
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
                            url: 'controller/transmittal-controller.php',
                            dataType: 'json',
                            data: {
                                transmittal_id: transmittal_id,
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Delete Transmittal Success', 'The selected transmittals have been deleted successfully.', 'success');
                                        reloadDatatable('#transmittal-table');
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else {
                                        showNotification('Delete Transmittal Error', response.message, 'danger');
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
                showNotification('Deletion Multiple Transmittal Error', 'Please select the transmittals you wish to delete.', 'danger');
            }
        });

        $(document).on('click','#delete-transmittal-details',function() {
            const transmittal_id = $('#transmittal-id').text();
            const transaction = 'delete transmittal';
    
            Swal.fire({
                title: 'Confirm Transmittal Deletion',
                text: 'Are you sure you want to delete this transmittal?',
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
                        url: 'controller/transmittal-controller.php',
                        dataType: 'json',
                        data: {
                            transmittal_id : transmittal_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Deleted Transmittal Success', 'The transmittal has been deleted successfully.', 'success');
                                window.location = 'transmittal.php';
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
                                    showNotification('Delete Transmittal Error', response.message, 'danger');
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
            discardCreate('transmittal.php');
        });

        $(document).on('click','#edit-form',function() {
            displayDetails('get transmittal details');

            enableForm();
        });

        $(document).on('click','#duplicate-transmittal',function() {
            const transmittal_id = $('#transmittal-id').text();
            const transaction = 'duplicate transmittal';
    
            Swal.fire({
                title: 'Confirm Transmittal Duplication',
                text: 'Are you sure you want to duplicate this transmittal?',
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
                        url: 'controller/transmittal-controller.php',
                        dataType: 'json',
                        data: {
                            transmittal_id : transmittal_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Duplicate Transmittal Success', 'The transmittal has been duplicated successfully.', 'success');
                                window.location = 'transmittal.php?id=' + response.idTypeID;
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Duplicate Transmittal Error', 'The transmittal does not exist.', 'danger');
                                    reloadDatatable('#transmittal-table');
                                }
                                else {
                                    showNotification('Duplicate Transmittal Error', response.message, 'danger');
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

function idTypeTable(datatable_name, buttons = false, show_all = false){
    const type = 'transmittal table';
    var settings;

    const column = [ 
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'ID_TYPE_NAME' },
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
            'url' : 'view/_transmittal_generation.php',
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

function idTypeForm(){
    $('#transmittal-form').validate({
        rules: {
            transmittal_name: {
                required: true
            },
        },
        messages: {
            transmittal_name: {
                required: 'Please enter the transmittal name'
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
            const transmittal_id = $('#transmittal-id').text();
            const transaction = 'save transmittal';
        
            $.ajax({
                type: 'POST',
                url: 'controller/transmittal-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&transmittal_id=' + transmittal_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-data');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert Transmittal Success' : 'Update Transmittal Success';
                        const notificationDescription = response.insertRecord ? 'The transmittal has been inserted successfully.' : 'The transmittal has been updated successfully.';
                        
                        setNotification(notificationMessage, notificationDescription, 'success');
                        window.location = 'transmittal.php?id=' + response.idTypeID;
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
        case 'get transmittal details':
            const transmittal_id = $('#transmittal-id').text();
            
            $.ajax({
                url: 'controller/transmittal-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    transmittal_id : transmittal_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetForm('transmittal-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#transmittal_id').val(transmittal_id);
                        $('#transmittal_name').val(response.idTypeName);

                        $('#transmittal_name_label').text(response.idTypeName);
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Transmittal Details Error', response.message, 'danger');
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