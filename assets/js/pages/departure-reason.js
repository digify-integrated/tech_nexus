(function($) {
    'use strict';

    $(function() {
        if($('#departure-reason-table').length){
            departureReasonTable('#departure-reason-table');
        }

        if($('#departure-reason-form').length){
            departureReasonForm();
        }

        if($('#departure-reason-id').length){
            displayDetails('get departure reason details');
        }

        $(document).on('click','.delete-departure-reason',function() {
            const departure_reason_id = $(this).data('departure-reason-id');
            const transaction = 'delete departure reason';
    
            Swal.fire({
                title: 'Confirm Departure Reason Deletion',
                text: 'Are you sure you want to delete this departure reason?',
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
                        url: 'controller/departure-reason-controller.php',
                        dataType: 'json',
                        data: {
                            departure_reason_id : departure_reason_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete Departure Reason Success', 'The departure reason has been deleted successfully.', 'success');
                                reloadDatatable('#departure-reason-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Delete Departure Reason Error', 'The departure reason does not exist.', 'danger');
                                    reloadDatatable('#departure-reason-table');
                                }
                                else {
                                    showNotification('Delete Departure Reason Error', response.message, 'danger');
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

        $(document).on('click','#delete-departure-reason',function() {
            let departure_reason_id = [];
            const transaction = 'delete multiple departure reason';

            $('.datatable-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    departure_reason_id.push(element.value);
                }
            });
    
            if(departure_reason_id.length > 0){
                Swal.fire({
                    title: 'Confirm Multiple Departure Reasons Deletion',
                    text: 'Are you sure you want to delete these departure reasons?',
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
                            url: 'controller/departure-reason-controller.php',
                            dataType: 'json',
                            data: {
                                departure_reason_id: departure_reason_id,
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Delete Departure Reason Success', 'The selected departure reasons have been deleted successfully.', 'success');
                                        reloadDatatable('#departure-reason-table');
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else {
                                        showNotification('Delete Departure Reason Error', response.message, 'danger');
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
                showNotification('Deletion Multiple Departure Reason Error', 'Please select the departure reasons you wish to delete.', 'danger');
            }
        });

        $(document).on('click','#delete-departure-reason-details',function() {
            const departure_reason_id = $('#departure-reason-id').text();
            const transaction = 'delete departure reason';
    
            Swal.fire({
                title: 'Confirm Departure Reason Deletion',
                text: 'Are you sure you want to delete this departure reason?',
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
                        url: 'controller/departure-reason-controller.php',
                        dataType: 'json',
                        data: {
                            departure_reason_id : departure_reason_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Deleted Departure Reason Success', 'The departure reason has been deleted successfully.', 'success');
                                window.location = 'departure-reason.php';
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
                                    showNotification('Delete Departure Reason Error', response.message, 'danger');
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
            discardCreate('departure-reason.php');
        });

        $(document).on('click','#edit-form',function() {
            displayDetails('get departure reason details');

            enableForm();
        });

        $(document).on('click','#duplicate-departure-reason',function() {
            const departure_reason_id = $('#departure-reason-id').text();
            const transaction = 'duplicate departure reason';
    
            Swal.fire({
                title: 'Confirm Departure Reason Duplication',
                text: 'Are you sure you want to duplicate this departure reason?',
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
                        url: 'controller/departure-reason-controller.php',
                        dataType: 'json',
                        data: {
                            departure_reason_id : departure_reason_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Duplicate Departure Reason Success', 'The departure reason has been duplicated successfully.', 'success');
                                window.location = 'departure-reason.php?id=' + response.departureReasonID;
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Duplicate Departure Reason Error', 'The departure reason does not exist.', 'danger');
                                    reloadDatatable('#departure-reason-table');
                                }
                                else {
                                    showNotification('Duplicate Departure Reason Error', response.message, 'danger');
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

function departureReasonTable(datatable_name, buttons = false, show_all = false){
    const type = 'departure reason table';
    var settings;

    const column = [ 
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'DEPARTURE_REASON_NAME' },
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
            'url' : 'view/_departure_reason_generation.php',
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

function departureReasonForm(){
    $('#departure-reason-form').validate({
        rules: {
            departure_reason_name: {
                required: true
            },
        },
        messages: {
            departure_reason_name: {
                required: 'Please enter the departure reason name'
            },
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
            const departure_reason_id = $('#departure-reason-id').text();
            const transaction = 'save departure reason';
        
            $.ajax({
                type: 'POST',
                url: 'controller/departure-reason-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&departure_reason_id=' + departure_reason_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-data');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert Departure Reason Success' : 'Update Departure Reason Success';
                        const notificationDescription = response.insertRecord ? 'The departure reason has been inserted successfully.' : 'The departure reason has been updated successfully.';
                        
                        setNotification(notificationMessage, notificationDescription, 'success');
                        window.location = 'departure-reason.php?id=' + response.departureReasonID;
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
        case 'get departure reason details':
            const departure_reason_id = $('#departure-reason-id').text();
            
            $.ajax({
                url: 'controller/departure-reason-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    departure_reason_id : departure_reason_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetModalForm('departure-reason-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#departure_reason_id').val(departure_reason_id);
                        $('#departure_reason_name').val(response.departureReasonName);

                        $('#departure_reason_name_label').text(response.departureReasonName);
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Departure Reason Details Error', response.message, 'danger');
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