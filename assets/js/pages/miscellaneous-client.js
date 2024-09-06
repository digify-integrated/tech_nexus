(function($) {
    'use strict';

    $(function() {
        if($('#miscellaneous-client-table').length){
            miscellaneousClientTable('#miscellaneous-client-table');
        }

        if($('#miscellaneous-client-form').length){
            miscellaneousClientForm();
        }

        if($('#miscellaneous-client-id').length){
            displayDetails('get miscellaneous client details');
        }

        $(document).on('click','.delete-miscellaneous-client',function() {
            const miscellaneous_client_id = $(this).data('miscellaneous-client-id');
            const transaction = 'delete miscellaneous client';
    
            Swal.fire({
                title: 'Confirm Miscellaneous Client Deletion',
                text: 'Are you sure you want to delete this miscellaneous client?',
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
                        url: 'controller/miscellaneous-client-controller.php',
                        dataType: 'json',
                        data: {
                            miscellaneous_client_id : miscellaneous_client_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete Miscellaneous Client Success', 'The miscellaneous client has been deleted successfully.', 'success');
                                reloadDatatable('#miscellaneous-client-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Delete Miscellaneous Client Error', 'The miscellaneous client does not exist.', 'danger');
                                    reloadDatatable('#miscellaneous-client-table');
                                }
                                else {
                                    showNotification('Delete Miscellaneous Client Error', response.message, 'danger');
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

        $(document).on('click','#delete-miscellaneous-client',function() {
            let miscellaneous_client_id = [];
            const transaction = 'delete multiple miscellaneous client';

            $('.datatable-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    miscellaneous_client_id.push(element.value);
                }
            });
    
            if(miscellaneous_client_id.length > 0){
                Swal.fire({
                    title: 'Confirm Multiple Miscellaneous Clients Deletion',
                    text: 'Are you sure you want to delete these miscellaneous clients?',
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
                            url: 'controller/miscellaneous-client-controller.php',
                            dataType: 'json',
                            data: {
                                miscellaneous_client_id: miscellaneous_client_id,
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Delete Miscellaneous Client Success', 'The selected miscellaneous clients have been deleted successfully.', 'success');
                                    reloadDatatable('#miscellaneous-client-table');
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else {
                                        showNotification('Delete Miscellaneous Client Error', response.message, 'danger');
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
                showNotification('Deletion Multiple Miscellaneous Client Error', 'Please select the miscellaneous clients you wish to delete.', 'danger');
            }
        });

        $(document).on('click','#delete-miscellaneous-client-details',function() {
            const miscellaneous_client_id = $('#miscellaneous-client-id').text();
            const transaction = 'delete miscellaneous client';
    
            Swal.fire({
                title: 'Confirm Miscellaneous Client Deletion',
                text: 'Are you sure you want to delete this miscellaneous client?',
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
                        url: 'controller/miscellaneous-client-controller.php',
                        dataType: 'json',
                        data: {
                            miscellaneous_client_id : miscellaneous_client_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Deleted Miscellaneous Client Success', 'The miscellaneous client has been deleted successfully.', 'success');
                                window.location = 'miscellaneous-client.php';
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
                                    showNotification('Delete Miscellaneous Client Error', response.message, 'danger');
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
            discardCreate('miscellaneous-client.php');
        });

        $(document).on('click','#edit-form',function() {
            displayDetails('get miscellaneous client details');

            enableForm();
        });

        $(document).on('click','#duplicate-miscellaneous-client',function() {
            const miscellaneous_client_id = $('#miscellaneous-client-id').text();
            const transaction = 'duplicate miscellaneous client';
    
            Swal.fire({
                title: 'Confirm Miscellaneous Client Duplication',
                text: 'Are you sure you want to duplicate this miscellaneous client?',
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
                        url: 'controller/miscellaneous-client-controller.php',
                        dataType: 'json',
                        data: {
                            miscellaneous_client_id : miscellaneous_client_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Duplicate Miscellaneous Client Success', 'The miscellaneous client has been duplicated successfully.', 'success');
                                window.location = 'miscellaneous-client.php?id=' + response.miscellaneousClientID;
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Duplicate Miscellaneous Client Error', 'The miscellaneous client does not exist.', 'danger');
                                    reloadDatatable('#miscellaneous-client-table');
                                }
                                else {
                                    showNotification('Duplicate Miscellaneous Client Error', response.message, 'danger');
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

function miscellaneousClientTable(datatable_name, buttons = false, show_all = false){
    const type = 'miscellaneous client table';
    var settings;

    const column = [ 
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'CLIENT_NAME' },
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
            'url' : 'view/_miscellaneous_client_generation.php',
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

function miscellaneousClientForm(){
    $('#miscellaneous-client-form').validate({
        rules: {
            client_name: {
                required: true
            },
        },
        messages: {
            client_name: {
                required: 'Please enter the miscellaneous client name'
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
            const miscellaneous_client_id = $('#miscellaneous-client-id').text();
            const transaction = 'save miscellaneous client';
        
            $.ajax({
                type: 'POST',
                url: 'controller/miscellaneous-client-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&miscellaneous_client_id=' + miscellaneous_client_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-data');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert Miscellaneous Client Success' : 'Update Miscellaneous Client Success';
                        const notificationDescription = response.insertRecord ? 'The miscellaneous client has been inserted successfully.' : 'The miscellaneous client has been updated successfully.';
                        
                        setNotification(notificationMessage, notificationDescription, 'success');
                        window.location = 'miscellaneous-client.php?id=' + response.miscellaneousClientID;
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
        case 'get miscellaneous client details':
            const miscellaneous_client_id = $('#miscellaneous-client-id').text();
            
            $.ajax({
                url: 'controller/miscellaneous-client-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    miscellaneous_client_id : miscellaneous_client_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetForm('miscellaneous-client-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#miscellaneous_client_id').val(miscellaneous_client_id);
                        $('#client_name').val(response.clientName);

                        $('#client_name_label').text(response.clientName);
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Miscellaneous Client Details Error', response.message, 'danger');
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